<?php
// user/milestone.php
session_start();

// Set current page as hightlighted in sidebar
$current_page = 'about';

// Get user data
if (!isset($user) && isset($_SESSION['user_id'])) {
    require_once '../user/db_connect.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_id, username, email, full_name, role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milestones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/milestone.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<!-- Theme Selector - Index 版本 -->
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
<body>
    <!-- Header Container -->
    <div id="header-container"></div>

    <!-- Page Container -->
    <div class="page-container">
        <!-- Sidebar Component -->
<div id="sidebar-container"></div>
    
    <!-- Main Content Area -->
    <main class="main-content" id="main-content">
    <div class="floating-elements"></div>
    
    <div class="content">
        <div class="title-container">
            <i class="fas fa-trophy"></i>
            <span>Milestones</span>
        </div>

        <div class="progress-container">
            <div class="progress-info">
                <div class="progress-text">Overall Progress</div>
                <div class="progress-percentage" id="progressPercentage">0%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <div class="milestone-container">
            <div class="box">
                <div class="box-content">
                    <div class="task-info">
                        <h3>Daily Check In</h3>
                    </div>
                    <div class="task-status">
                        <div class="status-indicator" data-task="Daily Check In">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-content">
                    <div class="task-info">
                        <h3>Complete Mathematics Quiz</h3>
                    </div>
                    <div class="task-status">
                        <div class="status-indicator" data-task="Complete Mathematics Quiz">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-content">
                    <div class="task-info">
                        <h3>Study for 1 Hour</h3>
                    </div>
                    <div class="task-status">
                        <div class="status-indicator" data-task="Study for 1 Hour">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-content">
                    <div class="task-info">
                        <h3>Read 1 Chapter</h3>
                    </div>
                    <div class="task-status">
                        <div class="status-indicator" data-task="Read 1 Chapter">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-content">
                    <div class="task-info">
                        <h3>Practice Coding</h3>
                    </div>
                    <div class="task-status">
                        <div class="status-indicator" data-task="Practice Coding">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Footer -->
    <div id="footer-container"></div>

<script src="../js/milestone.js"></script>
    <script src="../js/sidebar.js"></script>

</body>
</html>