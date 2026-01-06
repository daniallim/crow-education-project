<?php
// category.php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// clear cache output
while (ob_get_level()) {
    ob_end_clean();
}

// process post request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $role = $_POST['role'];
    
    // verify role
    if (in_array($role, ['student', 'teacher'])) {
        //  store selected role in session
        $_SESSION['selected_role'] = $role;
        header('Location: register.php?role=' . $role);
        exit;
    }
}

// process get request for direct links
if (isset($_GET['role']) && in_array($_GET['role'], ['student', 'teacher'])) {
    $_SESSION['selected_role'] = $_GET['role'];
    header('Location: register.php?role=' . $_GET['role']);
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Category - Crow Education</title>

    <link rel="stylesheet" href="../CSS/main.css">
    <link rel="stylesheet" href="../CSS/category.css">
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
            <div class="left-subtext">Join thousands of students and teachers who are already advancing their careers
                with Crow Education</div>
        </div>

        <div class="right-panel">
            <h1>Welcome to Crow Education</h1>
            <p>Select your category to continue</p>

            <div class="category-buttons">
                <div class="category-btn" id="student-btn" onclick="selectCategory('student')">
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" alt="Student">
                    <span>Student</span>
                </div>

                <div class="category-btn" id="teacher-btn" onclick="selectCategory('teacher')">
                    <img src="https://cdn-icons-png.flaticon.com/512/4140/4140048.png" alt="Teacher">
                    <span>Teacher</span>
                </div>
            </div>

            <button class="continue-btn" id="continueBtn" onclick="continueNext()" disabled>Continue</button>
        </div>
    </div>
    
    <script src="../js/category.js"></script>
</body>

</html>