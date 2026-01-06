<?php
// user/profile.php
session_start();

// Set current page as highlighted in sidebar
$current_page = 'profile';

// Get user data
if (!isset($user) && isset($_SESSION['user_id'])) {
    require_once '../user/db_connect.php';
    $user_id = $_SESSION['user_id'];
    
    // Get user basic info
    $sql = "SELECT user_id, username, email, full_name, role, created_at FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Get user profile info
    $profile_sql = "SELECT bio, profile_image, progress FROM user_profiles WHERE user_id = ?";
    $profile_stmt = $conn->prepare($profile_sql);
    $profile_stmt->bind_param("i", $user_id);
    $profile_stmt->execute();
    $profile_result = $profile_stmt->get_result();
    $user_profile = $profile_result->fetch_assoc();
}

// Process form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $start_date = $_POST['start_date'];
    
    // 处理文件上传
    $profile_image = $user_profile['profile_image'] ?? null; // 保持原有图片
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        // 文件上传验证
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES['avatar']['type'], $allowed_types) && $_FILES['avatar']['size'] <= $max_size) {
            $upload_dir = '../uploads/profiles/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // 生成唯一文件名
            $file_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $user_id . '_' . time() . '.' . $file_extension;
            $target_path = $upload_dir . $filename;
            
            // 移动上传的文件
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target_path)) {
                $profile_image = $filename;
                
                // 如果有旧图片，删除旧文件
                if (!empty($user_profile['profile_image']) && file_exists($upload_dir . $user_profile['profile_image'])) {
                    unlink($upload_dir . $user_profile['profile_image']);
                }
            } else {
                $error_message = "文件上传失败，请重试。";
            }
        } else {
            $error_message = "请上传 JPEG、PNG、GIF 或 WebP 格式的图片，且大小不超过 2MB。";
        }
    }
    
    // update user info
    $update_sql = "UPDATE users SET full_name = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $full_name, $email, $user_id);
    $stmt->execute();
    
    // 更新或插入用户资料信息（包括头像）
    if ($user_profile) {
        // 更新现有记录
        $profile_update_sql = "UPDATE user_profiles SET profile_image = ? WHERE user_id = ?";
        $profile_stmt = $conn->prepare($profile_update_sql);
        $profile_stmt->bind_param("si", $profile_image, $user_id);
        $profile_stmt->execute();
    } else {
        // 插入新记录
        $profile_insert_sql = "INSERT INTO user_profiles (user_id, profile_image) VALUES (?, ?)";
        $profile_stmt = $conn->prepare($profile_insert_sql);
        $profile_stmt->bind_param("is", $user_id, $profile_image);
        $profile_stmt->execute();
    }
    
    // Refetch updated user info
    $sql = "SELECT user_id, username, email, full_name, role, created_at FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Refetch user profile info
    $profile_sql = "SELECT bio, profile_image, progress FROM user_profiles WHERE user_id = ?";
    $profile_stmt = $conn->prepare($profile_sql);
    $profile_stmt->bind_param("i", $user_id);
    $profile_stmt->execute();
    $profile_result = $profile_stmt->get_result();
    $user_profile = $profile_result->fetch_assoc();
    
    $success_message = "Profile updated successfully!";
}

// Set initial value
$display_name = $user['full_name'] ?? $user['username'] ?? 'User';
$email = $user['email'] ?? '';
$account_number = 'CROW' . str_pad($user['user_id'] ?? '001', 3, '0', STR_PAD_LEFT);
$category = ucfirst($user['role'] ?? 'Student');
$start_date = $user['created_at'] ? date('Y-m-d', strtotime($user['created_at'])) : '2020-01-01';
$start_date_display = $user['created_at'] ? date('d M Y', strtotime($user['created_at'])) : '01 Jan 2020';
$motivation_quote = '"Never give up! Keep moving forward no matter how hard it gets."'; // 固定语录

// 构建完整的头像URL - 这是关键修改
if (!empty($user_profile['profile_image'])) {
    // 使用相对于当前文件的路径
    $profile_image = '../uploads/profiles/' . $user_profile['profile_image'];
    
    // 检查文件是否存在，如果不存在则使用默认头像
    if (!file_exists($profile_image)) {
        $profile_image = 'https://i.pravatar.cc/150?img=12';
    }
} else {
    $profile_image = 'https://i.pravatar.cc/150?img=12';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/profile.css">
</head>

<body>
    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="success-message" id="successMessage" style="display: block;">
                <?php echo $success_message; ?>
            </div>
        <?php else: ?>
            <div class="success-message" id="successMessage" style="display: none;">
                Profile updated successfully!
            </div>
        <?php endif; ?>

        <div class="profile-container">
            <!-- View Mode -->
            <div class="profile-view" id="profileView">
                <div class="profile-header">
                    <div class="profile-sidebar">
                        <img src="<?php echo htmlspecialchars($profile_image); ?>" class="profile-avatar" id="profileAvatar">
                        <div class="profile-badges">
                            <span>🏅</span>
                            <span>🥇</span>
                            <span>🎖️</span>
                        </div>
                    </div>

                    <div class="profile-details">
                        <table>
                            <tr>
                                <td>Name:</td>
                                <td id="viewName"><?php echo htmlspecialchars($display_name); ?></td>
                            </tr>
                            <tr>
                                <td>Account Number:</td>
                                <td id="viewAccount"><?php echo htmlspecialchars($account_number); ?></td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td id="viewEmail"><?php echo htmlspecialchars($email); ?></td>
                            </tr>
                            <tr>
                                <td>Category:</td>
                                <td id="viewCategory"><?php echo htmlspecialchars($category); ?></td>
                            </tr>
                            <tr>
                                <td>Start Learning:</td>
                                <td id="viewStartDate"><?php echo htmlspecialchars($start_date_display); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Quote Section - View Mode -->
                <div class="quote-section">
                    <div class="quote-label">My Motivation Quote:</div>
                    <div class="quote-content" id="viewQuote"><?php echo htmlspecialchars($motivation_quote); ?></div>
                </div>

                <div class="profile-footer">
                    <button class="edit-profile-btn" id="editProfileBtn">Edit Profile</button>
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-form" id="editForm" style="display: none;">
                <form method="POST" action="">
                    <input type="hidden" name="update_profile" value="1">
                    
                    <div class="profile-header">
                        <div class="profile-sidebar">
                            <div class="avatar-upload">
                                <img src="<?php echo htmlspecialchars($profile_image); ?>" class="avatar-preview" id="avatarPreview">
                                <div class="upload-controls">
                                    <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                                    <button type="button" class="upload-btn" id="uploadBtn">Choose Image</button>
                                    <button type="button" class="remove-btn" id="removeBtn">Remove</button>
                                </div>
                                <div class="file-info" id="fileInfo">No file chosen</div>
                            </div>
                            <div class="profile-badges">
                                <span>🏅</span>
                                <span>🥇</span>
                                <span>🎖️</span>
                            </div>
                        </div>

                        <div class="profile-details">
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" id="editName" name="full_name" value="<?php echo htmlspecialchars($display_name); ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="editAccount">Account Number</label>
                                    <input type="text" id="editAccount" value="<?php echo htmlspecialchars($account_number); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="editCategory">Category</label>
                                    <input type="text" id="editCategory" value="<?php echo htmlspecialchars($category); ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="editEmail">Email</label>
                                <input type="email" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>">
                            </div>

                            <div class="form-group">
                                <label for="editStartDate">Start Learning</label>
                                <input type="date" id="editStartDate" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Quote Section - Edit Mode -->
                    <div class="quote-section edit-quote">
                        <div class="form-group">
                            <label for="editQuote">My Motivation Quote</label>
                            <textarea id="editQuote" rows="3" placeholder="Write your motivation quote here..." 
                                      disabled style="background: #f5f5f5; color: #666;"><?php echo htmlspecialchars($motivation_quote); ?></textarea>
                            <small style="color: #888; font-size: 12px;">Motivation quotes feature coming soon!</small>
                        </div>
                    </div>

                    <div class="profile-footer">
                        <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                        <button type="submit" class="save-profile-btn" id="saveProfileBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/profile.js"></script>

</body>
</html>