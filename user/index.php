<?php
session_start();
require_once '../user/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT user_id, username, email, full_name, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header('Location: login.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Smart Dashboard</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/index.css">


</head>

<body>
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


    <!-- Header Component -->
<div id="user-data" 
     data-user-name="<?php echo htmlspecialchars($user['full_name'] ?: $user['username'] ?: 'User'); ?>"
     data-user-role="<?php echo htmlspecialchars($user['role'] ?: 'user'); ?>"
     data-user-avatar="<?php echo htmlspecialchars(strtoupper(substr($user['full_name'] ?: $user['username'] ?: 'U', 0, 1))); ?>"
     style="display: none;">
</div>

    <!-- Header Container -->
    <div id="header-container"></div>

    <!-- Page Container -->
    <div class="page-container">
    
    <!-- Sidebar Component -->
    <div id="sidebar-container"></div>

    <!-- Main Content Area -->
    <main class="content" id="main-content">

    <!-- Footer Component -->
    <div id="footer-container"></div>
    


    <script src="../js/index.js"></script>
    
</body>

</html>