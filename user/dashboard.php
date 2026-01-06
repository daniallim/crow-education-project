<?php
// user/dashboard.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// make sure user data is available
if (!isset($user) && isset($_SESSION['user_id'])) {
    require_once '../user/db_connect.php';
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_id, username, email, full_name, role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // make sure user data is fetched correctly
    error_log("User data fetched: " . print_r($user, true));
}

$display_name = 'There';
if (isset($user['full_name']) && !empty($user['full_name'])) {
    $display_name = htmlspecialchars($user['full_name']);
    error_log("Using full_name: " . $user['full_name']);
} elseif (isset($user['username']) && !empty($user['username'])) {
    $display_name = htmlspecialchars($user['username']);
    error_log("Using username: " . $user['username']);
} else {
    error_log("No user data found, using default");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Notifications</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/notification.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/header.css">

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


<div class="welcome-section">
    <h1>Hi <?php echo $display_name; ?>!</h1>
    <p>What do you want to do today?</p>
</div>

<div class="search-container">
    <div class="search-bar" id="searchBar">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search for courses, resources, tools...">
    </div>
</div>

<div class="motivation-section">
    <h3>Today's Motivation</h3>
    <p id="motivation-quote">...</p>
</div>

<div class="dashboard-grid">
    <!-- Class Card -->
    <a href="class.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h3>Class</h3>
                    <p>Continue your learning journey</p>
                </div>
            </div>
            <div class="card-stats">
                <span>3 active courses</span>
                <span>2 assignments due</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-enroll">
                    <i class="fas fa-user-plus"></i> Enroll Now
                </button>
            </div>
        </div>
    </a>

    <!-- Course Finder Card -->
    <a href="course.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.1">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-search"></i>
                </div>
                <div>
                    <h3>Course Finder</h3>
                    <p>Discover new learning opportunities</p>
                </div>
            </div>
            <div class="card-stats">
                <span>15 new courses</span>
                <span>5 recommendations</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-enroll">
                    <i class="fas fa-user-plus"></i> Enroll Now
                </button>
            </div>
        </div>
    </a>

    <!-- Notification Card -->
    <a href="notification.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.2">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <h3>Notification</h3>
                    <p>Stay updated with your learning</p>
                </div>
            </div>
            <div class="card-stats">
                <span>5 unread</span>
                <span>2 important</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- Schedule Card -->
    <a href="schedule.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.3">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h3>Schedule</h3>
                    <p>Manage your study time effectively</p>
                </div>
            </div>
            <div class="card-stats">
                <span>3 classes today</span>
                <span>1 exam next week</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- Discussion Hub Card -->
    <a href="community.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.4">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <h3>Discussion Hub</h3>
                    <p>Connect with peers and instructors</p>
                </div>
            </div>
            <div class="card-stats">
                <span>8 new discussions</span>
                <span>3 your questions</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-chat">
                    <i class="fas fa-comment"></i> Chat Now
                </button>
            </div>
        </div>
    </a>

    <!-- Chat Card -->
    <a href="chat.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.5">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-comment-dots"></i>
                </div>
                <div>
                    <h3>Chat</h3>
                    <p>Instant communication with your study group</p>
                </div>
            </div>
            <div class="card-stats">
                <span>12 unread messages</span>
                <span>3 active chats</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-chat">
                    <i class="fas fa-comment"></i> Chat Now
                </button>
            </div>
        </div>
    </a>

    <!-- Note Card -->
    <a href="notes.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.6">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-sticky-note"></i>
                </div>
                <div>
                    <h3>Note</h3>
                    <p>Organize your thoughts and ideas</p>
                </div>
            </div>
            <div class="card-stats">
                <span>15 notes</span>
                <span>3 shared</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="action-btn btn-edit">
                    <i class="fas fa-plus"></i> Create Now
                </button>
            </div>
        </div>
    </a>

    <!-- Music Card -->
    <a href="music.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.7">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-music"></i>
                </div>
                <div>
                    <h3>Music</h3>
                    <p>Focus music for productive study sessions</p>
                </div>
            </div>
            <div class="card-stats">
                <span>5 playlists</span>
                <span>2 hours played</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- Study Method Card -->
    <a href="method.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="1.1">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div>
                    <h3>Study Method</h3>
                    <p>Don't know how to study? Come here</p>
                </div>
            </div>
            <div class="card-stats">
                <span>10 tips</span>
                <span>Top rated</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- Knowledge Hub Card -->
    <a href="knowledge.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.9">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                    <h3>Knowledge Hub</h3>
                    <p>Myriad of notes and questions waiting for you</p>
                </div>
            </div>
            <div class="card-stats">
                <span>10 new knowledge</span>
                <span>Updated daily</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- Milestones Card -->
    <a href="milestone.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="1.0">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div>
                    <h3>Milestones</h3>
                    <p>Track your progress and reach new goals</p>
                </div>
            </div>
            <div class="card-stats">
                <span>8 completed</span>
                <span>Next: Level 9</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>

    <!-- About Us Card -->
    <a href="aboutus.php" class="card-link" style="text-decoration: none; color: inherit;">
        <div class="card" data-delay="0.8">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h3>About Us</h3>
                    <p>Let's look about our team story :]</p>
                </div>
            </div>
            <div class="card-stats">
                <span>Our Journey</span>
                <span>Meet the Team</span>
            </div>
            <div class="card-actions">
                <button class="action-btn btn-view">
                    <i class="fas fa-eye"></i> View
                </button>
            </div>
        </div>
    </a>
</div>