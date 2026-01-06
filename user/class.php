<?php
include 'sidebar.php';
// user/chat.php
session_start();

// Set current page as hightlighted in sidebar
$current_page = 'class';

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
    <title>School Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/class.css">
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
<body>
    <!-- Header Container -->
    <div id="header-container"></div>

    <!-- Sidebar Component -->
    <div id="sidebar-container"></div>

    <!-- Page Container -->
    <div class="page-container"></div>

 



    <div class="floating-elements"></div>

    <div id="class-overview" class="page active">
        <div class="class-container">
            <div class="box">
                <div class="class-title-container">
                    <i class="fas fa-book-open"></i>
                    <span>Class</span>
                </div>

                <div class="teacher-card" onclick="showTeacherPage('history')">
                    <div class="teacher-avatar">
                        <img src="../img1/Rengoku.png" alt="Rengoku">
                        
                    </div>
                    <div class="teacher-info">
                        <h2>History</h2>
                        <p>Rengoku Kyoujurou</p>
                    </div>
                </div>

                <div class="teacher-card" onclick="showTeacherPage('math')">
                    <div class="teacher-avatar">
                        <img src="../img1/Iguro.png" alt="Iguro">
                    </div>
                    <div class="teacher-info">
                        <h2>Additional Mathematics</h2>
                        <p>Iguro Obanai</p>
                    </div>
                </div>

                <div class="teacher-card" onclick="showTeacherPage('art')">
                    <div class="teacher-avatar">
                        <img src="../img1/Kanroji.png" alt="Mitsuri">
                    </div>
                    <div class="teacher-info">
                        <h2>Art</h2>
                        <p>Kanroji Mitsuri</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="history-page" class="page">
        <div class="back-button" onclick="showPage('class-overview')">
            <i class="fas fa-arrow-left"></i>
            Back to Classes
        </div>

        <div class="class-title-container">
            <i class="fas fa-book-open"></i>
            <span>History Class - Rengoku Kyoujurou</span>
        </div>

        <div class="content">
            <div class="button-container">
                <button class="nav-button active" data-filter="all">All</button>
                <button class="nav-button" data-filter="homework">Homework</button>
                <button class="nav-button" data-filter="note">Lesson Note</button>
                <button class="nav-button" data-filter="video">Video</button>
                <button class="nav-button" data-filter="chat">Chat</button>
            </div>

            <div class="post-container">
                <div class="post-card post-category-chat">
                    <div class="post-header">
                        <img src="../img1/Rengoku.png" alt="Avatar" class="avatar">
                        <span class="username">Rengoku Kyoujurou</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone,<br>
                            Tomorrow is our SPM trial exam!<br>
                            Let your hearts burn with passion! Give it everything you've got! 🔥💪<br>
                            Believe in yourself — and go beyond your limits!</p>
                    </div>
                </div>

                <div class="post-card post-category-video">
                    <div class="post-header">
                        <img src="../img1/Rengoku.png" alt="Avatar" class="avatar">
                        <span class="username">Rengoku Kyoujurou</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone,<br>
                            I've recorded a video lecture on the key concepts from Chapter 5! 🔥<br>
                            Watch it with full focus and passion! Let your spirit burn with determination!<br>
                            Understand it well — for knowledge is the flame that lights your path! 💪🔥<br></p>
                    </div>
                    <div class="video-container">
                        <div class="video-wrapper">
                            <video id="rengokuVideo" poster="../img1/history.png">
                                <source src="../video1/sejarah.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="video-controls">
                                <div class="video-duration" id="historyVideoDuration">00:00</div>
                                <div class="video-actions">
                                    <i class="fas fa-play" id="historyPlayPauseBtn"></i>
                                    <i class="fas fa-download" id="historyDownloadVideoBtn"></i>
                                    <i class="fas fa-expand" id="historyFullscreenBtn"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="video-info">
                        <div class="file-info">
                            <i class="fas fa-video"></i>
                            <span class="file-name">F4 Chapter 5: PTN 1948</span>
                        </div>
                        <div class="video-duration">1:13:14</div>
                    </div>
                </div>

                <div class="post-card post-category-note">
                    <div class="post-header">
                        <img src="../img1/Rengoku.png" alt="Avatar" class="avatar">
                        <span class="username">Rengoku Kyoujurou</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone!<br>
                            Here are the notes from Form 5!<br>
                            Study them with passion and give it everything you've got! 🔥<br>
                            Remember — your effort will never betray you!<br></p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">Sejarah F5 notes</span>
                        </div>
                        <div class="file-actions">
                            <a href="../notes/sejarah.pdf" target="_blank" style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../notes/sejarah.pdf" download style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="post-card post-category-homework">
                    <div class="post-header">
                        <img src="../img1/Rengoku.png" alt="Avatar" class="avatar">
                        <span class="username">Rengoku Kyoujurou</span>
                    </div>
                    <div class="post-body">
                        <p>Here are the History question papers!<br>
                            Complete them with all your strength and hand them in once you're done! 🔥<br>
                            Give it your best — let your spirit burn bright!<br></p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">Sejarah pastyear question paper 2024</span>
                        </div>
                        <div class="file-actions">
                            <a href="../notes/passyear sej.pdf" target="_blank"
                                style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../notes/passyear sej.pdf" download style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>

                    <div class="submission-section">
                        <button class="submit-homework-button">
                            <i class="fas fa-inbox"></i> Turn In
                            <i class="fas fa-chevron-down"></i> </button>
                        <span class="submission-status-text">Not Submitted</span>

                        <div class="inline-submission-area">
                            <div class="file-upload-area-inline">
                                <input type="file" id="file-input-history" class="file-input-hidden-inline">
                                <label for="file-input-history" class="file-input-label-inline">
                                    <i class="fas fa-upload"></i>
                                    <span>Click to select a file</span>
                                </label>
                                <span class="file-name-display-inline">No file selected</span>
                            </div>
                            <button class="modal-button-submit-inline">Submit Homework</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="math-page" class="page">
        <div class="back-button" onclick="showPage('class-overview')">
            <i class="fas fa-arrow-left"></i>
            Back to Classes
        </div>

        <div class="class-title-container">
            <i class="fas fa-book-open"></i>
            <span>Additional Mathematics - Iguro Obanai</span>
        </div>

        <div class="content">
            <div class="button-container">
                <button class="nav-button active" data-filter="all">All</button>
                <button class="nav-button" data-filter="homework">Homework</button>
                <button class="nav-button" data-filter="note">Lesson Note</button>
                <button class="nav-button" data-filter="video">Video</button>
                <button class="nav-button" data-filter="chat">Chat</button>
            </div>

            <div class="post-container">
                <div class="post-card post-category-chat">
                    <div class="post-header">
                        <img src="../img1/Iguro.png" alt="Avatar" class="avatar">
                        <span class="username">Iguro Obanai</span>
                    </div>
                    <div class="post-body">
                        <p>Tomorrow is the SPM trial exam.<br>
                            I don't want to hear any excuses.<br>
                            You've practiced enough — now prove it with your results.<br>
                            I believe you can do better, so don't disappoint yourself.</p>
                    </div>
                </div>

                <div class="post-card post-category-video">
                    <div class="post-header">
                        <img src="../img1/Iguro.png" alt="Avatar" class="avatar">
                        <span class="username">Iguro Obanai</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone,<br>
                            I've recorded a lecture on the key points of Chapter 8.<br>
                            Make sure you watch it — properly, not half-heartedly.<br>
                            Don't waste my effort, understood?<br></p>
                    </div>
                    <div class="video-container">
                        <div class="video-wrapper">
                            <video id="mathVideo" poster="../img1/addmath.png">
                                <source src="../video1/Addmath.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="video-controls">
                                <div class="video-duration" id="mathVideoDuration">00:00</div>
                                <div class="video-actions">
                                    <i class="fas fa-play" id="mathPlayPauseBtn"></i>
                                    <i class="fas fa-download" id="mathDownloadVideoBtn"></i>
                                    <i class="fas fa-expand" id="mathFullscreenBtn"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="post-card post-category-homework">
                    <div class="post-header">
                        <img src="../img1/Iguro.png" alt="Avatar" class="avatar">
                        <span class="username">Iguro Obanai</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone,<br>
                            Here are the Add Math question papers. Make sure to submit them to me once you're done — no
                            delays, no excuses.<br>
                            Do it properly, and don't make careless mistakes.</p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">Addmath F5 question</span>
                        </div>
                        <div class="file-actions">
                            <a href="../notes/passyear addmath.pdf" target="_blank"
                                style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../notes/passyear addmath.pdf" download
                                style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>

                    <div class="submission-section">
                        <button class="submit-homework-button">
                            <i class="fas fa-inbox"></i> Turn In
                            <i class="fas fa-chevron-down"></i> </button>
                        <span class="submission-status-text">Not Submitted</span>

                        <div class="inline-submission-area">
                            <div class="file-upload-area-inline">
                                <input type="file" id="file-input-math" class="file-input-hidden-inline">
                                <label for="file-input-math" class="file-input-label-inline">
                                    <i class="fas fa-upload"></i>
                                    <span>Click to select a file</span>
                                </label>
                                <span class="file-name-display-inline">No file selected</span>
                            </div>
                            <button class="modal-button-submit-inline">Submit Homework</button>
                        </div>
                    </div>
                </div>

                <div class="post-card post-category-note">
                    <div class="post-header">
                        <img src="../img1/Iguro.png" alt="Avatar" class="avatar">
                        <span class="username">Iguro Obanai</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone,<br>
                            Here are the notes from Form 4 to Form 5.<br>
                            Go through them properly — don't just skim.<br>
                            Use them well, and make sure your effort shows in your results.</p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">Addmath F5 notes</span>
                        </div>
                        <div class="file-actions">
                            <a href="../notes/addmath.pdf" target="_blank" style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../notes/addmath.pdf" download style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="art-page" class="page">
        <div class="back-button" onclick="showPage('class-overview')">
            <i class="fas fa-arrow-left"></i>
            Back to Classes
        </div>

        <div class="class-title-container">
            <i class="fas fa-book-open"></i>
            <span>Art Class - Kanroji Mitsuri</span>
        </div>

        <div class="content">
            <div class="button-container">
                <button class="nav-button active" data-filter="all">All</button>
                <button class="nav-button" data-filter="homework">Homework</button>
                <button class="nav-button" data-filter="note">Lesson Note</button>
                <button class="nav-button" data-filter="video">Video</button>
                <button class="nav-button" data-filter="chat">Chat</button>
            </div>

            <div class="post-container">
                <div class="post-card post-category-chat">
                    <div class="post-header">
                        <img src="../img1/Kanroji.png" alt="Avatar" class="avatar">
                        <span class="username">Kanroji Mitsuri</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone~ 💕<br>
                            Tomorrow is our SPM trial exam! ✨<br>
                            Do your very best, okay? Put all your heart into it — I know you can do it! 💖<br>
                            Believe in yourself and shine brightly like the beautiful stars you are~ 🌸🌟<br>
                            I'll be cheering for you with all my love! 💞💪🎨<br></p>
                    </div>
                </div>

                <div class="post-card post-category-video">
                    <div class="post-header">
                        <img src="../img1/Kanroji.png" alt="Avatar" class="avatar">
                        <span class="username">Kanroji Mitsuri</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone~ 💕<br>
                            I recorded a super helpful video lecture on Chapter 10! 🌸<br>
                            Make sure to watch it before our next class, okay?<br>
                            I worked really hard on it for you all~ so don't forget! 💞✨<br></p>
                    </div>
                    <div class="video-container">
                        <div class="video-wrapper">
                            <video id="artVideo" poster="../img1/art.png">
                                <source src="video1/Computer Design.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="video-controls">
                                <div class="video-duration" id="artVideoDuration">00:00</div>
                                <div class="video-actions">
                                    <i class="fas fa-play" id="artPlayPauseBtn"></i>
                                    <i class="fas fa-download" id="artDownloadVideoBtn"></i>
                                    <i class="fas fa-expand" id="artFullscreenBtn"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="post-card post-category-homework">
                    <div class="post-header">
                        <img src="../img1/Kanroji.png" alt="Avatar" class="avatar">
                        <span class="username">Kanroji Mitsuri</span>
                    </div>
                    <div class="post-body">
                        <p>Hi everyone<br>
                            Here are the Art question papers! 🖌️<br>
                            Please hand them in to me once you're done, okay? Don't forget~ 🌸<br></p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">Past Year Question 2024 (Art)</span>
                        </div>
                        <div class="file-actions">
                            <a href="../img1/PSV homework.png" target="_blank"
                                style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../img1/PSV homework.png" download style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>

                    <div class="submission-section">
                        <button class="submit-homework-button">
                            <i class="fas fa-inbox"></i> Turn In
                            <i class="fas fa-chevron-down"></i> </button>
                        <span class="submission-status-text">Not Submitted</span>

                        <div class="inline-submission-area">
                            <div class="file-upload-area-inline">
                                <input type="file" id="file-input-art" class="file-input-hidden-inline">
                                <label for="file-input-art" class="file-input-label-inline">
                                    <i class="fas fa-upload"></i>
                                    <span>Click to select a file</span>
                                </label>
                                <span class="file-name-display-inline">No file selected</span>
                            </div>
                            <button class="modal-button-submit-inline">Submit Homework</button>
                        </div>
                    </div>
                </div>

                <div class="post-card post-category-note">
                    <div class="post-header">
                        <img src="../img1/Kanroji.png" alt="Avatar" class="avatar">
                        <span class="username">Kanroji Mitsuri</span>
                    </div>
                    <div class="post-body">
                        <p>Hey everyone~ 💕<br>
                            Here are the notes!<br>
                            I really hope they inspire your creativity and help you with your Art studies! 🎨✨<br>
                            You've all been working so hard — I'm so proud of you! 💖<br></p>
                    </div>
                    <div class="post-attachment">
                        <div class="file-info">
                            <i class="fas fa-file-lines"></i>
                            <span class="file-name">pen's notes</span>
                        </div>
                        <div class="file-actions">
                            <a href="../img1/pen.png" target="_blank" style="text-decoration: none; color: inherit;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="../img1/pen.png" download style="text-decoration: none; color: inherit;">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Footer -->
    <div id="footer-container"></div>

</body>
    <script src="../js/class.js"></script>
    <script src="../js/main.js"></script>
</html>