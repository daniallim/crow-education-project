<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

// Detect if PHP is running 
echo "<!-- PHP is working -->";

require 'db_connect.php';

$role = $_GET['role'] ?? 'student';

if ($role === 'teacher') {
    $role_value = 'admin';
} else {
    $role_value = 'user';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (empty($name) || empty($email) || empty($_POST['password'])) {
        echo json_encode(['error' => 'All fields are required']);
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email format']);
        exit;
    }

    // Used user_id to check existing email
    $check_sql = "SELECT user_id, role FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $existing_user = $check_result->fetch_assoc();
        $existing_role_display = ($existing_user['role'] === 'admin') ? 'teacher' : 'student';
        echo json_encode(['error' => "Email already registered as a {$existing_role_display}"]);
        $check_stmt->close();
        exit;
    }
    $check_stmt->close();

    // insert new user
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role_value);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'User registered successfully']);
    } else {
        echo json_encode(['error' => 'Registration failed: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
} 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Crow Education</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
    <link rel="stylesheet" href="../CSS/register.css">
</head>

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
            <div class="left-subtext">Join thousands of students who are already advancing their careers with Crow
                Education</div>
        </div>
    
        <div class="right-panel">
            <h1>Create Your Account</h1>
            <p>Join Crow Education today</p>
    
            <div class="form-group">
                <input type="text" id="name" placeholder="e.g. Jason Tan" required />
                <i class="input-icon fas fa-user"></i>
            </div>
    
            <div class="form-group">
                <input type="email" id="email" placeholder="e.g. jason@example.com" required />
                <i class="input-icon fas fa-envelope"></i>
            </div>
    
            <div class="form-group">
                <input type="password" id="password" placeholder="Enter password" required />
                <i class="input-icon fas fa-lock"></i>
                <i class="input-icon fas fa-eye password-toggle" id="togglePassword"></i>
            </div>
    
            <div class="password-rules">
                <p id="lengthRule">❌ 8–12 characters</p>
                <p id="numberRule">❌ At least one number (0–9)</p>
                <p id="symbolRule">❌ At least one special symbol (e.g. @, #, $)</p>
            </div>
    
            <div class="form-group">
                <input type="password" id="confirmPassword" placeholder="Confirm password" required />
                <i class="input-icon fas fa-lock"></i>
            </div>
            <p id="matchMsg"></p>
    
            <button id="registerBtn" disabled>
                <span class="btn-text">Register Now</span>
                <div class="loading"></div>
            </button>
    
            <div class="success-message" id="successMessage">
                <i class="fas fa-check-circle"></i> Registration successful! Welcome to Crow Education.
            </div>
    
            <div class="login-link">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>

<script src="../js/register.js"></script>

</body>

</html>