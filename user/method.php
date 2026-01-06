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
    <title>Study Methods Master - All Learning Techniques</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/method.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/header.css">


</head>
<!-- Theme Selector - Index 版本 -->
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
<body>
    <!-- Header Container -->
    <div id="header-container"></div>

    <!-- Sidebar Component -->
    <div id="sidebar-container"></div>

    <!-- Page Container -->
    <div class="page-container">


    <div class="floating-elements"></div>



    <!-- Home Page -->
    <div class="page active" id="home-page">
        <header class="page-header">
            <div class="container">
                <h1>Study Methods Master</h1>
                <p class="tagline">Discover the Best Learning Techniques for Academic Success</p>
                <button class="btn" onclick="showPage('methods-page')">Explore All Methods</button>
            </div>
        </header>

        <div class="container">
            <section id="introduction">
                <h2><i class="fas fa-lightbulb"></i> Why Study Methods Matter</h2>
                <p>Effective learning isn't just about spending more time studying - it's about using the right
                    techniques. Research shows that using proven study methods can improve learning outcomes by up to
                    50% compared to traditional approaches.</p>
                <p>This comprehensive guide brings together the most effective study methods backed by cognitive science
                    and educational research. Whether you're preparing for exams, learning a new skill, or trying to
                    retain complex information, these techniques will transform how you learn.</p>
            </section>

            <section id="methods-overview">
                <h2><i class="fas fa-list"></i> Available Study Methods</h2>
                <div class="method-cards-container">
                 <a href="charting.php" class="charting-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('charting')">
                        <div class="method-card-icon">
                            <span>📊</span>
                        </div>
                        <div class="method-card-text">
                            Charting Method
                        </div>
                    </div>

                 <a href="cornell.php" class="cornell-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('cornell')">
                        <div class="method-card-icon">
                            <span>📝</span>
                        </div>
                        <div class="method-card-text">
                            Cornell Method
                        </div>
                    </div>

                 <a href="feynman.php" class="feynman-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('feynman')">
                        <div class="method-card-icon">
                            <span>👨‍🔬</span>
                        </div>
                        <div class="method-card-text">
                            Feynman Technique
                        </div>
                    </div>

                 <a href="loci.php" class="loci-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('loci')">
                        <div class="method-card-icon">
                            <span>🏰</span>
                        </div>
                        <div class="method-card-text">
                            Memory Palace Technique
                        </div>
                    </div>

                 <a href="mnemonic.php" class="mnemonic-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('mnemonic')">
                        <div class="method-card-icon">
                            <span>🧠</span>
                        </div>
                        <div class="method-card-text">
                            Mnemonic Devices
                        </div>
                    </div>

                 <a href="pomodoro.php" class="pomodoro-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('pomodoro')">
                        <div class="method-card-icon">
                            <span>🍅</span>
                        </div>
                        <div class="method-card-text">
                            Pomodoro Technique
                        </div>
                    </div>

                 <a href="question.php" class="question-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('question')">
                        <div class="method-card-icon">
                            <span>❓</span>
                        </div>
                        <div class="method-card-text">
                            Question and Answer Method
                        </div>
                    </div>

                 <a href="simon.php" class="simon-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('simon')">
                        <div class="method-card-icon">
                            <span>👨‍🏫</span>
                        </div>
                        <div class="method-card-text">
                            Simon Learning Method
                        </div>
                    </div>

                 <a href="spaced.php" class="spaced-link" style="text-decoration: none; color: inherit;">
                    <div class="method-card" onclick="openMethod('spaced')">
                        <div class="method-card-icon">
                            <span>⏰</span>
                        </div>
                        <div class="method-card-text">
                            Spaced Repetition
                        </div>
                    </div>
                </div>
            </section>

            <section id="how-to-choose">
                <h2><i class="fas fa-check-circle"></i> How to Choose the Right Method</h2>
                <ul>
                    <li><strong>For memorizing facts and details:</strong> Spaced Repetition, Mnemonic Devices, Memory
                        Palace</li>
                    <li><strong>For understanding complex concepts:</strong> Feynman Technique, Simon Learning Method
                    </li>
                    <li><strong>For organizing information:</strong> Charting Method, Cornell Method</li>
                    <li><strong>For improving focus and productivity:</strong> Pomodoro Technique</li>
                    <li><strong>For active learning and self-testing:</strong> Question and Answer Method</li>
                </ul>
                <p>Remember, the most effective approach often combines multiple methods based on your learning goals
                    and the type of material you're studying.</p>
            </section>
        </div>
    </div>

    <!-- Methods Page -->
    <div class="page" id="methods-page">
        <header class="page-header">
            <div class="container">
                <h1>Study Methods</h1>
                <p class="tagline">Choose a learning technique to explore</p>
                <button class="btn" onclick="showPage('../user/method.php')">Back to Home</button>
            </div>
        </header>

        <div class="container">
            <div class="category-nav-container">
                <button class="category-nav-btn active" data-category="all">All Methods</button>
                <button class="category-nav-btn" data-category="memory">Memory</button>
                <button class="category-nav-btn" data-category="organization">Organization</button>
                <button class="category-nav-btn" data-category="productivity">Productivity</button>
            </div>

            <div class="method-nav-container">
                <button class="method-nav-btn active" data-tab="all">All Methods</button>
                <button class="method-nav-btn" data-tab="new">New</button>
                <button class="method-nav-btn" data-tab="bookmark">Bookmark</button>
            </div>

            <div class="method-cards-container">
                <div class="method-card" data-method-id="charting" data-category="organization"
                    onclick="openMethod('charting')">
                    <div class="method-card-icon">
                        <span>📊</span>
                    </div>
                    <div class="method-card-text">
                        Charting Method
                    </div>
                    <button class="bookmark-btn" data-method-id="charting" data-method-name="Charting Method">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="cornell" data-category="organization"
                    onclick="openMethod('cornell')">
                    <div class="method-card-icon">
                        <span>📝</span>
                    </div>
                    <div class="method-card-text">
                        Cornell Method
                    </div>
                    <button class="bookmark-btn" data-method-id="cornell" data-method-name="Cornell Method">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="feynman" data-category="understanding"
                    onclick="openMethod('feynman')">
                    <div class="method-card-icon">
                        <span>👨‍🔬</span>
                    </div>
                    <div class="method-card-text">
                        Feynman Technique
                    </div>
                    <button class="bookmark-btn" data-method-id="feynman" data-method-name="Feynman Technique">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="loci" data-category="memory" onclick="openMethod('loci')">
                    <div class="method-card-icon">
                        <span>🏰</span>
                    </div>
                    <div class="method-card-text">
                        Memory Palace Technique
                    </div>
                    <button class="bookmark-btn" data-method-id="loci" data-method-name="Memory Palace Technique">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="mnemonic" data-category="memory"
                    onclick="openMethod('mnemonic')">
                    <div class="method-card-icon">
                        <span>🧠</span>
                    </div>
                    <div class="method-card-text">
                        Mnemonic Devices
                    </div>
                    <button class="bookmark-btn" data-method-id="mnemonic" data-method-name="Mnemonic Devices">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="pomodoro" data-category="productivity"
                    onclick="openMethod('pomodoro')">
                    <div class="method-card-icon">
                        <span>🍅</span>
                    </div>
                    <div class="method-card-text">
                        Pomodoro Technique
                    </div>
                    <button class="bookmark-btn" data-method-id="pomodoro" data-method-name="Pomodoro Technique">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="question" data-category="understanding"
                    onclick="openMethod('question')">
                    <div class="method-card-icon">
                        <span>❓</span>
                    </div>
                    <div class="method-card-text">
                        Question and Answer Method
                    </div>
                    <button class="bookmark-btn" data-method-id="question"
                        data-method-name="Question and Answer Method">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="simon" data-category="understanding"
                    onclick="openMethod('simon')">
                    <div class="method-card-icon">
                        <span>👨‍🏫</span>
                    </div>
                    <div class="method-card-text">
                        Simon Learning Method
                    </div>
                    <button class="bookmark-btn" data-method-id="simon" data-method-name="Simon Learning Method">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>
                </div>

                <div class="method-card" data-method-id="spaced" data-category="memory" onclick="openMethod('spaced')">
                    <div class="method-card-icon">
                        <span>⏰</span>
                    </div>
                    <div class="method-card-text">
                        Spaced Repetition
                    </div>
                    <button class="bookmark-btn" data-method-id="spaced" data-method-name="Spaced Repetition">
                        <i class="far fa-bookmark bookmark-icon"></i>
                        <span class="bookmark-text">Bookmark</span>
                    </button>

    <!-- Page Footer -->
    <div id="footer-container"></div>
                </div>
            </div>
        </div>
    </div>





</body>

    <script src="../js/method.js"></script> 
    <script src="../js/main.js"></script>
</html>


