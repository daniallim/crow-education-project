<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



require 'db_connect.php';

//  process login request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // set json header
    header('Content-Type: application/json');
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // verify inputs
    if (empty($email) || empty($password)) {
        echo json_encode(['error' => 'Email and password are required']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }

try {
    // check user existence
    $check_sql = "SELECT user_id, email, password, full_name, role FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    error_log("check email: " . $email);
    error_log("find user number: " . $check_result->num_rows);
    
    if ($check_result->num_rows === 0) {
        error_log("User not found");
        echo json_encode(['error' => 'Invalid email or password']);
        $check_stmt->close();
        exit;
    }

    $user = $check_result->fetch_assoc();
    $check_stmt->close();
    
    error_log("User information: " . print_r($user, true));
    error_log("Input password: " . $password);
    error_log("set as Hash: " . $user['password']); 

    // verify password
    if (password_verify($password, $user['password'])) {
        error_log("Password verified successfully");
        // login successful,
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['full_name'] ?: 'User';
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        echo json_encode([
            'success' => 'Login successful! Welcome back to Crow Education.',
            'user' => [
                'name' => $user['full_name'] ?: 'User',
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    } else {
        error_log("Password verification failed");
        echo json_encode(['error' => 'Invalid email or password']);
    }
    
} catch (Exception $e) {
    error_log("Login faid: " . $e->getMessage());
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
    
    $conn->close();
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Crow Education</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>
<link rel="stylesheet" href="../css/login.css" />
<body>
<body>
    <!-- Animated background elements -->
    <div class="bg-element"></div>
    <div class="bg-element"></div>
    <div class="bg-element"></div>

    <!-- Floating shapes -->
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>
    <div class="floating-shape"></div>

    <div class="container">
        <div class="left-panel">
            <div class="logo">
                <i class="fas fa-crow"></i> Crow Education
            </div>
            <div class="left-text">Empowering Learning, Empowering You</div>
            <div class="left-subtext">Join thousands of students growing their potential with Crow Education</div>
        </div>

        <div class="right-panel">
            <h1>Welcome Back</h1>
            <p>Sign in to continue your learning journey</p>

            <div class="form-group">
                <input type="email" id="email" placeholder="e.g. jason@example.com" required />
                <i class="input-icon fas fa-envelope"></i>
            </div>

            <div class="form-group">
                <input type="password" id="password" placeholder="Enter password" required />
                <i class="input-icon fas fa-lock"></i>
                <i class="input-icon fas fa-eye password-toggle" id="togglePassword"></i>
            </div>

            <div class="forgot-password">
                <a href="reset.php">Forgot password?</a>
            </div>

            <div class="error-message" id="errorMessage">
                <i class="fas fa-exclamation-circle"></i> Invalid email or password.
            </div>

            <button id="loginBtn">Sign In</button>

            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> Login successful! Redirecting...
            </div>

            <div class="register-link">
                Don't have an account? <a href="category.php">Register now</a>
            </div>
        </div>
    </div>

    <script src="../js/login.js"></script>
</body>

</html>