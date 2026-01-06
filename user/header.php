<?php


// only fetch user data if not already available
if (isset($_SESSION['user_id']) && (!isset($user) || empty($user))) {
    require_once '../user/db_connect.php';
    
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_id, username, email, full_name, role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

$display_user = isset($user) ? $user : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Category - Crow Education</title>

    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="stylesheet" href="../CSS/header.css">
</head>
    <!-- Floating elements -->
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>

    <!-- Theme selector with integrated auto theme -->
    <div class="theme-selector" id="themeSelector">
        <div class="theme-options-grid">
            <div class="theme-option theme-1 active" data-theme="1"></div>
            <div class="theme-option theme-2" data-theme="2"></div>
            <div class="theme-option theme-3" data-theme="3"></div>
            <div class="theme-option theme-4" data-theme="4"></div>
            <div class="theme-option theme-5" data-theme="5"></div>
        </div>

        <!-- Auto Theme Toggle inside Theme Selector -->
        <div class="auto-theme-section" id="autoThemeSection">
            <div class="auto-theme-info">
                <i class="fas fa-sync-alt"></i>
                <span>Auto Theme</span>
            </div>
            <div class="auto-theme-status"></div>
        </div>
    </div>

<header class="header">

<!-- Notification Dropdown -->
<div class="notification-dropdown" id="notificationDropdown" style="display: none;">
    <div class="dropdown-content">
        <h4>Notifications</h4>
        <div class="notification-item">
            <i class="fas fa-book"></i>
            <span>New assignment in History class</span>
        </div>
        <div class="notification-item">
            <i class="fas fa-comment"></i>
            <span>New message from Rengoku Sensei</span>
        </div>
        <div class="notification-item">
            <i class="fas fa-calendar"></i>
            <span>Math quiz due tomorrow</span>
        </div>
    </div>
</div>

<!-- Profile Dropdown -->
<div class="profile-dropdown" id="profileDropdown" style="display: none;">
    <div class="dropdown-content">
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
    <div class="logo-container">
        <div class="logo-box"><i class="fas fa-crow"></i></div>
        <span class="title">Crow Education</span>
    </div>

    <div class="header-right">
        <a href="notification.php" class="notification-link" style="text-decoration: none; color: inherit;">
            <div class="notification-icon" id="notificationIcon">
                <i class="fas fa-bell"></i>
                <div class="notification-badge">5</div>
            </div>
        </a>

        <div class="settings-icon" id="settingsIcon">
            <i class="fas fa-cog"></i>
        </div>
        
        <!-- This is the paint palette icon that will open the theme selector -->
        <div class="theme-toggle" id="themeToggle">
            <i class="fas fa-palette"></i>
        </div>

            <a href="../user/profile.php">      
            <div class="user-profile" id="userProfile">
                <div class="user-avatar">
                    <?php 
                    // display first letter of user's name or username
                    if (isset($user['full_name']) && !empty($user['full_name'])) {
                        echo strtoupper(substr($user['full_name'], 0, 1));
                    } elseif (isset($user['username']) && !empty($user['username'])) {
                        echo strtoupper(substr($user['username'], 0, 1));
                    } else {
                        echo 'U';
                    }
                    ?>
                </div>
                
                <div class="user-info-text">
                    <div class="user-name">
                        <?php 
                        if (isset($user['full_name']) && !empty($user['full_name'])) {
                            echo htmlspecialchars($user['full_name']);
                        } elseif (isset($user['username']) && !empty($user['username'])) {
                            echo htmlspecialchars($user['username']);
                        } else {
                            echo 'User';
                        }
                        ?>
                    </div>
                    <div class="user-role">
                        <?php 
                        if (isset($user['role'])) {
                            echo ucfirst($user['role']);
                        } else {
                            echo 'User';
                        }
                        ?>
                    </div>
                </div>
                <div class="dropdown-arrow">
                <i class="fas fa-chevron-down"></i>
                </div>
            </div>
</a>
    </div>

    <script src="../js/header.js"></script>


</header>