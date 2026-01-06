<?php
// ../user/sidebar.php

$current_file = basename($_SERVER['PHP_SELF']);

$page_mapping = [
    'index.php' => 'dashboard',
    'dashboard.php' => 'dashboard',  
    'class.php' => 'class', 
    'course.php' => 'course',
    'notification.php' => 'notification',
    'schedule.php' => 'schedule',
    'community.php' => 'community',
    'chat.php' => 'chat',
    'notes.php' => 'notes',        
    'music.php' => 'music',
    'method.php' => 'method',
    'knowledge.php' => 'knowledge',
    'milestone.php' => 'milestone',
    'aboutus.php' => 'aboutus',    
    'abouts.php' => 'aboutus' ,
];

$current_page = $page_mapping[$current_file] ?? '';

error_log("Sidebar Debug - Current file: " . $current_file);
error_log("Sidebar Debug - Current page: " . $current_page);
error_log("Sidebar Debug - REQUEST_URI: " . $_SERVER['REQUEST_URI']);
?>




<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/sidebar.css">
</head>
<body>
<nav class="sidebar">
    <ul>
        <li><a href="index.php" class="nav-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">                <i class="fas fa-home"></i>
                <span class="item-text">Dashboard</span>
            </a></li>

        <li><a href="class.php" class="nav-link <?php echo $current_page === 'class' ? 'active' : ''; ?>">
                <i class="fas fa-book-open"></i>
                <span class="item-text">Class</span>
            </a></li>

        <li><a href="course.php" class="nav-link <?php echo $current_page === 'course' ? 'active' : ''; ?>">
                <i class="fas fa-search"></i>
                <span class="item-text">Course Finder</span>
            </a></li>

        <li><a href="notification.php" class="nav-link <?php echo $current_page === 'notification' ? 'active' : ''; ?>">
                <i class="fas fa-bell"></i>
                <span class="item-text">Notification</span>
            </a></li>

        <li><a href="schedule.php" class="nav-link <?php echo $current_page === 'schedule' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i>
                <span class="item-text">Schedule</span>
            </a></li>

        <li><a href="community.php" class="nav-link <?php echo $current_page === 'community' ? 'active' : ''; ?>">
                <i class="fas fa-comments"></i>
                <span class="item-text">Discussion Hub</span>
            </a></li>

        <li><a href="chat.php" class="nav-link <?php echo $current_page === 'chat' ? 'active' : ''; ?>">
                <i class="fas fa-comment-dots"></i>
                <span class="item-text">Chat</span>
            </a></li>

        <li><a href="notes.php" class="nav-link <?php echo $current_page === 'notes' ? 'active' : ''; ?>">
                <i class="fas fa-sticky-note"></i>
                <span class="item-text">Note</span>
            </a></li>

        <li><a href="music.php" class="nav-link <?php echo $current_page === 'music' ? 'active' : ''; ?>">
                <i class="fas fa-music"></i>
                <span class="item-text">Music</span>
            </a></li>

        <li><a href="method.php" class="nav-link <?php echo $current_page === 'method' ? 'active' : ''; ?>">
                <i class="fas fa-lightbulb"></i>
                <span class="item-text">Study Method</span>
            </a></li>

        <li><a href="knowledge.php" class="nav-link <?php echo $current_page === 'knowledge' ? 'active' : ''; ?>">
                <i class="fas fa-graduation-cap"></i>
                <span class="item-text">Knowledge Hub</span>
            </a></li>

        <li><a href="milestone.php" class="nav-link <?php echo $current_page === 'milestone' ? 'active' : ''; ?>">
                <i class="fas fa-trophy"></i>
                <span class="item-text">Milestones</span>
            </a></li>

        <li><a href="aboutus.php" class="nav-link <?php echo $current_page === 'about us' ? 'active' : ''; ?>">
                <i class="fas fa-info-circle"></i>
                <span class="item-text">About Us</span>
            </a></li>
    </ul>

</nav>        
<script src="../js/sidebar.js"></script>
</body>