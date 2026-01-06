<?php
// reset_password.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Clear cached output
while (ob_get_level()) {
    ob_end_clean();
}

require 'db_connect.php';

// Process POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $action = $_POST['action'] ?? '';
    $email = trim($_POST['email'] ?? '');
    
    try {
        switch ($action) {
            case 'request_reset':
                // Request password reset
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    echo json_encode(['success' => false, 'error' => 'Invalid email address']);
                    exit;
                }
                
                // Verify email exists
                $check_sql = "SELECT user_id FROM users WHERE email = ?";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $result = $check_stmt->get_result();
                
                if ($result->num_rows === 0) {
                    echo json_encode(['success' => false, 'error' => 'Email not found']);
                    $check_stmt->close();
                    exit;
                }
                $check_stmt->close();
                
                // Store reset session data
                $_SESSION['reset_email'] = $email;
                $_SESSION['reset_verified'] = false;
                $_SESSION['reset_token'] = bin2hex(random_bytes(32));
                $_SESSION['reset_expires'] = time() + 1800; 
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Email verified. Please complete human verification.'
                ]);
                break;
                
            case 'verify_captcha':
                // Verify CAPTCHA       
                $email = $_SESSION['reset_email'] ?? '';
                
                if (empty($email)) {
                    echo json_encode(['success' => false, 'error' => 'Session expired. Please start over.']);
                    exit;
                }
                
                // set verified flag
                $_SESSION['reset_verified'] = true;
                
                echo json_encode([
                    'success' => true, 
                    'token' => $_SESSION['reset_token'],
                    'message' => 'Verification successful'
                ]);
                break;


                case 'go_back':
                   // Handle going back to previous step
                    $target_step = intval($_POST['step'] ?? 1);
                   $_SESSION['reset_step'] = $target_step;
        
                   echo json_encode([
                  'success' => true,
                   'step' => $target_step
                   ]);
                   break;



                case 'reset_password':
                    // Reset password
                      $new_password = $_POST['new_password'] ?? '';
                      $confirm_password = $_POST['confirm_password'] ?? '';
                        $token = $_POST['token'] ?? '';
                      $email = $_SESSION['reset_email'] ?? '';
    
                      error_log("=== Start Reset ===");
                      error_log("Email: " . $email);
                      error_log("Length Password: " . strlen($new_password));
                      error_log("verify session: " . ($_SESSION['reset_verified'] ? 'YES' : 'NO'));
                      error_log("verify session: " . ($_SESSION['reset_token'] === $token ? 'YES' : 'NO'));
    
    // verify session
    $session_verified = isset($_SESSION['reset_verified']) && $_SESSION['reset_verified'];
    $session_token_valid = isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token;
    $email_valid = !empty($email);
    $session_expired = isset($_SESSION['reset_expires']) && time() > $_SESSION['reset_expires'];
    
    if (!$session_verified || !$session_token_valid || !$email_valid || $session_expired) {
        error_log("verification failed");
        echo json_encode([
            'success' => false, 
            'error' => 'Session expired or invalid. Please start the reset process again.'
        ]);
        exit;
    }
    
    // verify passwords
    if (empty($new_password) || $new_password !== $confirm_password) {
        error_log("Pasword not match");
        echo json_encode(['success' => false, 'error' => 'Passwords do not match']);
        exit;
    }
    
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    error_log("Hash Password: " . substr($password_hash, 0, 20) . "...");
    
    // Update password in database
    $update_sql = "UPDATE users SET password = ? WHERE email = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ss", $password_hash, $email);
    
    if ($update_stmt->execute()) {
        error_log("Updated successful: " . $update_stmt->affected_rows);
        
        // Verify update
        $verify_sql = "SELECT password FROM users WHERE email = ?";
        $verify_stmt = $conn->prepare($verify_sql);
        $verify_stmt->bind_param("s", $email);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        
        if ($verify_row = $verify_result->fetch_assoc()) {
            error_log("Updated Hash Password: " . substr($verify_row['password'], 0, 20) . "...");
            $password_matches = password_verify($new_password, $verify_row['password']);
            error_log("Verify result: " . ($password_matches ? 'Sucess' : 'Failed'));
        }
        $verify_stmt->close();
        
        // Clear reset session data
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);
        unset($_SESSION['reset_token']);
        unset($_SESSION['reset_expires']);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Password reset successfully! Redirecting to login...'
        ]);
    } else {
        error_log("Database update failed: " . $update_stmt->error);
        echo json_encode(['success' => false, 'error' => 'Failed to reset password: ' . $update_stmt->error]);
    }
    $update_stmt->close();
    break;
                
            default:
                echo json_encode(['success' => false, 'error' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    }
    
    $conn->close();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/reset.css">
</head>

<body>
    <!-- Enhanced Animated Background -->
    <div class="particles-container" id="particlesContainer"></div>
    <div class="gradient-overlay"></div>
    
    <!-- Floating shapes -->
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    
    <div class="container">
        <div class="logo">
            <i class="logo-icon fas fa-crow"></i>
            <div class="logo-text">Crow Education</div>
        </div>
    
        <h1>Reset Your Password</h1>
        <p class="description">Enter your email address and complete the verification to reset your password.</p>
    
        <div class="step-indicator">
            <div class="step active" id="step1">1</div>
            <div class="step-line" id="line1"></div>
            <div class="step" id="step2">2</div>
            <div class="step-line" id="line2"></div>
            <div class="step" id="step3">3</div>
        </div>
    
        <div class="content-area">
            <div class="form-group" id="emailStep">
                <input type="email" id="email" placeholder="e.g. jason@example.com" required>
                <i class="input-icon fas fa-envelope"></i>
            </div>
    
            <div class="captcha-container" id="captchaStep">
                <div class="captcha-instructions">
                    <i class="fas fa-shield-alt"></i>
                    <strong>Human Verification Required</strong>
                    <p style="margin-top: 8px; font-size: 0.9rem;" id="instructionText">Drag the <strong>book
                            icon</strong> into the drop zone to verify you're human</p>
                </div>
    
                <div class="captcha-area">
                    <div class="drag-container" id="dragContainer">
                        <!-- Draggable items will be generated here -->
                    </div>
    
                    <div class="drop-zone" id="dropZone">
                        <div class="drop-zone-content">
                            <i class="fas fa-arrow-down"></i>
                            <p id="dropZoneText">Drag the book here</p>
                        </div>
                    </div>
                </div>
    
                <div class="captcha-feedback" id="captchaFeedback"></div>
    
                <button class="refresh-captcha" id="refreshCaptcha">
                    <i class="fas fa-redo"></i> Get New Challenge
                </button>
    
                <p id="captchaError" style="color: red; font-size: 0.9rem; margin-bottom: 15px; display: none;">
                    Verification failed. Please try again.
                </p>
            </div>
    
            <div class="new-password-container" id="passwordStep">
                <div class="form-group">
                    <input type="password" id="newPassword" placeholder="Enter new password" required>
                    <i class="input-icon fas fa-lock"></i>
                </div>
    
                <div class="password-rules">
                    <p id="lengthRule">❌ 8–12 characters</p>
                    <p id="numberRule">❌ At least one number (0–9)</p>
                    <p id="symbolRule">❌ At least one special symbol (e.g. @, #, $)</p>
                </div>
    
                <div class="form-group">
                    <input type="password" id="confirmNewPassword" placeholder="Confirm new password" required>
                    <i class="input-icon fas fa-lock"></i>
                </div>
                <p id="matchMsg"></p>
            </div>
    
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> Password reset successful! You can now log in with your new
                password.
            </div>
        </div>
    
        <!-- Centered Action Buttons - Now positioned higher -->
        <div class="action-buttons">
            <button class="previous-btn" id="previousBtn" style="display: none;">
                <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button class="submit-btn" id="submitBtn">
                <span class="btn-text">Verify Email</span>
                <div class="loading"></div>
            </button>
        </div>
    </div>
    
    <script src="../js/reset.js"></script>
</body>

</html>