<?php

// user/abouts.php
session_start();

// Set current page as hightlighted in sidebar
$current_page = 'about us';

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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Introduction - Bird² Team</title>

    <link rel="stylesheet" href="../css/aboutus.css">
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

    <h1>About Us</h1>

    <div class="container">
        <img src="../img1/bird2 team.jpg" alt="Bird² Team">

        <h2 class="team-name">Bird<sup>2</sup> Team</h2>

        <p>
            Every great idea begins with a simple intention — to make things better.
            <b>Crow Education</b> was founded by a group of university friends who shared the same dream:
            to improve the way students in our country learn and succeed.
        </p>

        <p>
            During our university journey, we noticed a worrying trend —
            <b>the SPM passing rate was falling year by year.</b>
            As students who once went through that same system, we understood the struggles, the pressure, and
            the lack of proper guidance many learners faced. That’s when we realized:
            <b>if no one takes the first step, nothing will change.</b>
        </p>

        <p>
            We noticed that many education platforms has beeen charged with high fees , which lets the students cannot afford. We wanted to create a platform that is affordable for all students.
            So we decided to create <b>Crow Education</b> — a platform built for students, by teachers and learners alike.
            A place where learning becomes simpler, more accessible, and truly meaningful.
            Here, we believe education should not be a burden, but a bridge to a better future.
        </p>

        <div class="mission">
          <p class="mission1"><strong><ins>Our mission</ins></strong></p>
            <b>✨ To help students learn smarter.</b>
            <b>💬 To support teachers in sharing knowledge.</b>
            <b>🌱 To make education easier — for everyone.</b>
        </div>

        <footer>
            Because of Crow Education, we believe that <b>Education makes life easier.</b>
        </footer>
    </div>


    <!-- Page Footer -->
    <div id="footer-container"></div>
    
    <script src="../js/aboutus.js"></script>
    <script src="../js/sidebar.js"></script>

</body>

</html>
