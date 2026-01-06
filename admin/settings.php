<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'settings';

// 获取教师信息
$teacher_info = [];
$sql = "SELECT u.*, up.phone, up.bio 
        FROM users u 
        LEFT JOIN user_profiles up ON u.user_id = up.user_id 
        WHERE u.user_id = ?";
$stmt = $conn->prepare($sql);
$teacher_id = 2;
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $teacher_info = $result->fetch_assoc();
}

// 获取设置
$settings = [];
$settings_sql = "SELECT * FROM settings WHERE category IN ('general', 'appearance', 'notifications')";
$settings_result = $conn->query($settings_sql);

if ($settings_result->num_rows > 0) {
    while($row = $settings_result->fetch_assoc()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
}

// 处理个人信息更新
if (isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bio = $_POST['bio'];
    
    // 更新用户表
    $update_user_sql = "UPDATE users SET full_name = ?, email = ? WHERE user_id = ?";
    $update_user_stmt = $conn->prepare($update_user_sql);
    $update_user_stmt->bind_param("ssi", $full_name, $email, $teacher_id);
    
    // 更新用户档案
    $update_profile_sql = "INSERT INTO user_profiles (user_id, phone, bio, updated_at) 
                           VALUES (?, ?, ?, NOW()) 
                           ON DUPLICATE KEY UPDATE phone = ?, bio = ?, updated_at = NOW()";
    $update_profile_stmt = $conn->prepare($update_profile_sql);
    $update_profile_stmt->bind_param("issss", $teacher_id, $phone, $bio, $phone, $bio);
    
    if ($update_user_stmt->execute() && $update_profile_stmt->execute()) {
        header("Location: settings.php?update_success=1");
        exit();
    }
}

// 处理密码更改
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // 验证当前密码
    $check_sql = "SELECT password FROM users WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $teacher_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_row = $check_result->fetch_assoc()) {
        if (password_verify($current_password, $check_row['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $hashed_password, $teacher_id);
                
                if ($update_stmt->execute()) {
                    header("Location: settings.php?password_changed=1");
                    exit();
                }
            } else {
                header("Location: settings.php?error=password_mismatch");
                exit();
            }
        } else {
            header("Location: settings.php?error=wrong_password");
            exit();
        }
    }
}

// 处理设置更新
if (isset($_POST['update_settings'])) {
    $dark_mode = isset($_POST['dark_mode']) ? 'true' : 'false';
    $notifications = isset($_POST['notifications']) ? 'true' : 'false';
    $theme_color = $_POST['theme_color'];
    
    // 更新设置
    $update_dark_sql = "INSERT INTO settings (setting_key, setting_value, setting_type, category) 
                        VALUES ('dark_mode_enabled', ?, 'boolean', 'appearance') 
                        ON DUPLICATE KEY UPDATE setting_value = ?";
    $update_dark_stmt = $conn->prepare($update_dark_sql);
    $update_dark_stmt->bind_param("ss", $dark_mode, $dark_mode);
    
    $update_notif_sql = "INSERT INTO settings (setting_key, setting_value, setting_type, category) 
                         VALUES ('notifications_enabled', ?, 'boolean', 'notifications') 
                         ON DUPLICATE KEY UPDATE setting_value = ?";
    $update_notif_stmt = $conn->prepare($update_notif_sql);
    $update_notif_stmt->bind_param("ss", $notifications, $notifications);
    
    $update_theme_sql = "INSERT INTO settings (setting_key, setting_value, setting_type, category) 
                         VALUES ('theme_color', ?, 'string', 'appearance') 
                         ON DUPLICATE KEY UPDATE setting_value = ?";
    $update_theme_stmt = $conn->prepare($update_theme_sql);
    $update_theme_stmt->bind_param("ss", $theme_color, $theme_color);
    
    if ($update_dark_stmt->execute() && $update_notif_stmt->execute() && $update_theme_stmt->execute()) {
        header("Location: settings.php?settings_updated=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Settings Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/setting.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-crow"></i>
                <h1>Crow Education</h1>
            </div>
            <ul class="menu">
                <li class="menu-item">
                    <a href="dashboard.php" class="menu-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="user.php" class="menu-link">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <span class="menu-badge">5</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="lessons.php" class="menu-link">
                        <i class="fas fa-book-open"></i>
                        <span>Manage Lessons</span>
                    </a>
                </li>
                 <li class="menu-item">
                    <a href="homework.php" class="menu-link">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Homework</span>
                        <span class="menu-badge">12</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="assessments.php" class="menu-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Manage Assessments</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="messages.php" class="menu-link">
                        <i class="fas fa-comments"></i>
                        <span>Manage Messages</span>
                        <span class="menu-badge">3</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="music.php" class="menu-link">
                        <i class="fas fa-music"></i>
                        <span>Manage Music</span>
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="settings.php" class="menu-link">
                        <i class="fas fa-cog"></i>
                        <span>Manage Settings</span>
                    </a>
                </li>
            </ul>
            <div class="teacher-profile">
                <div class="teacher-avatar">YC</div>
                <div class="teacher-info">
                    <div class="teacher-name">Sum Yee Ching</div>
                    <div class="teacher-role">Teacher</div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Teacher Dashboard</h2>
                <div class="header-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search settings...">
                    </div>
                    <div class="user-info">
                        <div class="dark-mode-toggle" id="darkModeToggle">
                            <i class="fas fa-moon toggle-icon"></i>
                            <span class="toggle-text">Dark Mode</span>
                        </div>
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">5</span>
                        </div>
                        <div class="user-avatar">YC</div>
                    </div>
                </div>
            </div>

            <div class="settings-content">
                <div class="settings-dashboard">
                    <div class="settings-nav">
                        <div class="settings-nav-header">
                            <div class="settings-nav-title">Settings</div>
                        </div>
                        <ul class="settings-nav-list">
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link active" data-panel="profile">
                                    <i class="fas fa-user"></i>
                                    <span>Profile</span>
                                </a>
                            </li>
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link" data-panel="account">
                                    <i class="fas fa-key"></i>
                                    <span>Account</span>
                                </a>
                            </li>
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link" data-panel="appearance">
                                    <i class="fas fa-palette"></i>
                                    <span>Appearance</span>
                                </a>
                            </li>
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link" data-panel="notifications">
                                    <i class="fas fa-bell"></i>
                                    <span>Notifications</span>
                                </a>
                            </li>
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link" data-panel="privacy">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Privacy & Security</span>
                                </a>
                            </li>
                            <li class="settings-nav-item">
                                <a href="#" class="settings-nav-link" data-panel="about">
                                    <i class="fas fa-info-circle"></i>
                                    <span>About</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="settings-panels">
                        <div class="settings-panel active" id="profile-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Profile Settings</h3>
                                <p class="settings-panel-description">Manage your personal information and profile settings</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="profile-section">
                                    <div class="profile-avatar">
                                        YC
                                        <div class="profile-avatar-edit">
                                            <i class="fas fa-camera"></i>
                                        </div>
                                    </div>
                                    <h3 class="profile-name">Yee Ching</h3>
                                    <p class="profile-role">Teacher</p>
                                    <button class="action-btn secondary-btn">
                                        <i class="fas fa-edit"></i>
                                        Edit Profile
                                    </button>
                                </div>

                                <div class="settings-section">
                                    <h4 class="settings-section-title">Personal Information</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Full Name</div>
                                            <div class="settings-item-description">Your full name as displayed to others</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <input type="text" class="form-control" value="Yee Ching" style="width: 200px;">
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Email Address</div>
                                            <div class="settings-item-description">Your primary email address</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <input type="email" class="form-control" value="yee.ching@crownedu.com" style="width: 200px;">
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Phone Number</div>
                                            <div class="settings-item-description">Your contact phone number</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <input type="tel" class="form-control" value="+1 (555) 123-4567" style="width: 200px;">
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <button class="action-btn primary-btn">
                                        <i class="fas fa-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="account-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Account Settings</h3>
                                <p class="settings-panel-description">Manage your account security and preferences</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Login & Security</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Change Password</div>
                                            <div class="settings-item-description">Update your password regularly to keep your account secure</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <button class="action-btn secondary-btn">Change Password</button>
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Two-Factor Authentication</div>
                                            <div class="settings-item-description">Add an extra layer of security to your account</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="appearance-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Appearance Settings</h3>
                                <p class="settings-panel-description">Customize the look and feel of the application</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Theme</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Theme Color</div>
                                            <div class="settings-item-description">Choose your preferred accent color</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <div class="color-options">
                                                <div class="color-option active" style="background-color: #3a86ff;" data-color="#3a86ff"></div>
                                                <div class="color-option" style="background-color: #8338ec;" data-color="#8338ec"></div>
                                                <div class="color-option" style="background-color: #ff006e;" data-color="#ff006e"></div>
                                                <div class="color-option" style="background-color: #38b000;" data-color="#38b000"></div>
                                                <div class="color-option" style="background-color: #ff9e00;" data-color="#ff9e00"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <button class="action-btn primary-btn">
                                        <i class="fas fa-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="notifications-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Notification Settings</h3>
                                <p class="settings-panel-description">Manage how and when you receive notifications</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Email Notifications</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">New Messages</div>
                                            <div class="settings-item-description">Receive email notifications for new messages</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Assignment Updates</div>
                                            <div class="settings-item-description">Get notified when assignments are created or updated</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <label class="toggle-switch">
                                                <input type="checkbox" checked>
                                                <span class="toggle-slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <button class="action-btn primary-btn">
                                        <i class="fas fa-save"></i>
                                        Save Preferences
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="privacy-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Privacy & Security</h3>
                                <p class="settings-panel-description">Manage your privacy settings and security preferences</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Privacy Settings</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Profile Visibility</div>
                                            <div class="settings-item-description">Control who can see your profile information</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <select class="form-select" style="width: 200px;">
                                                <option>Everyone</option>
                                                <option>Teachers Only</option>
                                                <option>Students Only</option>
                                                <option>Private</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <button class="action-btn primary-btn">
                                        <i class="fas fa-save"></i>
                                        Save Settings
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="preferences-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">Preferences</h3>
                                <p class="settings-panel-description">Customize your application experience</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Language & Region</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Language</div>
                                            <div class="settings-item-description">Choose your preferred language</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <select class="form-select" style="width: 200px;">
                                                <option selected>English</option>
                                                <option>Spanish</option>
                                                <option>French</option>
                                                <option>German</option>
                                                <option>Chinese</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <button class="action-btn primary-btn">
                                        <i class="fas fa-save"></i>
                                        Save Preferences
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="settings-panel" id="about-panel">
                            <div class="settings-panel-header">
                                <h3 class="settings-panel-title">About Crow Education</h3>
                                <p class="settings-panel-description">Learn more about our platform and services</p>
                            </div>
                            <div class="settings-panel-body">
                                <div class="settings-section">
                                    <h4 class="settings-section-title">Platform Information</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Version</div>
                                            <div class="settings-item-description">Current version of the application</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <span>v2.4.1</span>
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Last Updated</div>
                                            <div class="settings-item-description">Date of the last application update</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <span>October 15, 2023</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="settings-section">
                                    <h4 class="settings-section-title">Support</h4>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Documentation</div>
                                            <div class="settings-item-description">Access user guides and documentation</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <button class="action-btn secondary-btn">View Docs</button>
                                        </div>
                                    </div>
                                    <div class="settings-item">
                                        <div class="settings-item-info">
                                            <div class="settings-item-title">Contact Support</div>
                                            <div class="settings-item-description">Get help from our support team</div>
                                        </div>
                                        <div class="settings-item-control">
                                            <button class="action-btn secondary-btn">Contact Us</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="site-footer">
                <div class="footer-container">
                    <div class="footer-column">
                        <div class="footer-logo">
                            <i class="fas fa-crow"></i>
                            <span>Crow Education</span>
                        </div>
                    </div>

                    <div class="footer-column">
                        <h4>[ABOUT]</h4>
                        <ul class="toggle-list">
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-about-us">
                                    About us
                                </button>
                                <div class="footer-panel" id="panel-about-us">
                                    <a href="#">Bird2 Team</a>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-contact">
                                    Contact us
                                </button>
                                <div class="footer-panel" id="panel-contact">
                                    <a href="mailto:birdbird945@gmail.com">birdbird945@gmail.com</a>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-mission">
                                    Our Mission
                                </button>
                                <div class="footer-panel" id="panel-mission">
                                    <em>✨ To help students learn smarter.
                                        <div>💬 To support teachers in sharing knowledge.</div>
                                        <div>🌱 To make education easier — for everyone.</div>
                                    </em>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-slogan">
                                    Slogan
                                </button>
                                <div class="footer-panel" id="panel-slogan">
                                    <em>Grow with us, Glow your future</em>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>[LEARN]</h4>
                        <ul class="normal-list">
                            <li><a href="#">Class</a></li>
                            <li><a href="#">Discussion Hub</a></li>
                            <li><a href="#">Subject Finder</a></li>
                            <li><a href="#">Knowledge Hub</a></li>
                            <li><a href="#">Study Method</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>[HELP & SUPPORT]</h4>
                        <ul class="normal-list">
                            <li><a href="#">Feedback</a></li>
                        </ul>
                    </div>
                </div>
                <p class="last">© 2025 Crow Education EduTeach Platform · Teacher Dashboard</p>
            </footer>
        </div>
    </div>

    <div class="toast" id="toast">
        <i class="fas fa-check-circle"></i>
        <span>Settings saved successfully!</span>
    </div>

    <script src="../admin_php/JS/setting.js"></script>
</body>
</html>