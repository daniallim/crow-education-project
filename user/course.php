<?php
// user/course.php
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
    <title>Course Finder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/finder.css">
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
    <div class="floating-elements">
    </div>

    <main class="content">
        <div class="content-tab">
            <i class="fas fa-search"></i>
            <span>Course Finder</span>
        </div>

        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search by name, subject, or experience..." id="search-bar">
        </div>

        <div class="teacher-grid" id="teacher-grid">

            <div class="teacher-card" data-teacher-id="1">
                <img src="../img1/Tokito.png" alt="MR. Tokito Muichiro" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MR. Tokito Muichiro</h4>
                    <p><strong>Subject:</strong> Mathematics</p>
                    <p><strong>Teaching Experience:</strong> 2 years</p>
                </div>
            </div>

            <div class="teacher-card" data-teacher-id="2">
                <img src="../img1/Shinazugawa.png" alt="MR. Shinazugawa Sanemi" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MR. Shinazugawa Sanemi</h4>
                    <p><strong>Subject:</strong> Chinese</p>
                    <p><strong>Teaching Experience:</strong> 3 years</p>
                </div>
            </div>

            <div class="teacher-card" data-teacher-id="3">
                <img src="../img1/Tomioka.png" alt="MR. Tomioka Giyu" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MR. Tomioka Giyu</h4>
                    <p><strong>Subject:</strong> Accounts Economics Business</p>
                    <p><strong>Teaching Experience:</strong> 4 years</p>
                </div>
            </div>

            <div class="teacher-card" data-teacher-id="4">
                <img src="../img1/Himejima.png" alt="MR. Himejima Gyoumei" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MR. Himejima Gyoumei</h4>
                    <p><strong>Subject:</strong> English</p>
                    <p><strong>Teaching Experience:</strong> 10 years</p>
                </div>
            </div>

            <div class="teacher-card" data-teacher-id="5">
                <img src="../img1/Kocho.png" alt="MS. Kocho Shinobu" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MS. Kocho Shinobu</h4>
                    <p><strong>Subject:</strong> Physics Chemistry Biology</p>
                    <p><strong>Teaching Experience:</strong> 7 years</p>
                </div>
            </div>

            <div class="teacher-card" data-teacher-id="6">
                <img src="../img1/Uzui.png" alt="MR. Uzui Tengen" class="teacher-avatar">
                <div class="teacher-info">
                    <h4>MR. Uzui Tengen</h4>
                    <p><strong>Subject:</strong> Computer Science</p>
                    <p><strong>Teaching Experience:</strong> 5 years</p>
                </div>
            </div>

        </div>
    </main>

    <!-- TEACHER DETAIL MODAL -->
    <div class="teacher-modal" id="teacherModal">
        <div class="modal-content">
            <div class="modal-close" id="modalClose">
                <i class="fas fa-times"></i>
            </div>
            <div class="modal-header">
                <img src="" alt="" class="modal-avatar" id="modalAvatar">
                <div class="modal-teacher-info">
                    <h2 id="modalName"></h2>
                    <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
                    <p><strong>Teaching Experience:</strong> <span id="modalExperience"></span></p>
                    <div class="rating">
                        <div class="stars" id="modalRating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span id="modalRatingText">4.5/5</span>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h3><i class="fas fa-user-graduate"></i> About Me</h3>
                    <p id="modalAbout"></p>
                </div>
                <div class="modal-section">
                    <h3><i class="fas fa-book"></i> Subjects</h3>
                    <div class="subject-list" id="modalSubjectsList"></div>
                </div>
                <div class="modal-section">
                    <h3><i class="fas fa-chart-line"></i> Teaching Style</h3>
                    <p id="modalTeachingStyle"></p>
                </div>
                <div class="modal-section">
                    <h3><i class="fas fa-award"></i> Qualifications</h3>
                    <p id="modalQualifications"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="modalJoinClass">
                    <i class="fas fa-user-plus"></i> Join the Class
                </button>
                <button class="btn btn-primary" id="modalContact">
                    <i class="fas fa-envelope"></i> Contact Teacher
                </button>
            </div>
        </div>
    </div>

    <!-- Page Footer -->
    <div id="footer-container"></div>

    <script src="../js/finder.js"></script>
    <script src="../js/sidebar.js"></script>

</body>

</html>