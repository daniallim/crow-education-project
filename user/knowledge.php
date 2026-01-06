<?php
// user/knowledge.php
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
    <title>Knowledge Hub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/knowledge.css">
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
    
    <!-- Floating Background -->
    <div class="floating-elements"></div>

    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <!-- Main Knowledge Hub Page -->
    <div id="main-page" class="page active">
        <main class="content">
            <div class="content-header">
                <div class="content-tab">
                    <i class="fas fa-book-bookmark"></i>
                    <span>Knowledge Hub</span>
                </div>
            </div>

            <div class="knowledge-hub-box">
                <div class="knowledge-hub-row" onclick="showPage('question-papers')">
                    <div class="icon-container">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                        <span class="knowledge-hub-label">Question Papers</span>
                    </div>
                    <button class="go-view">View</button>
                </div>

                <div class="knowledge-hub-row" onclick="showPage('notes')">
                    <div class="icon-container">
                        <i class="fas fa-sticky-note" style="color: var(--primary);"></i>
                        <span class="knowledge-hub-label">Notes</span>
                    </div>
                    <button class="go-view">View</button>
                </div>

                <div class="knowledge-hub-row" onclick="showPage('other-courses')">
                    <div class="icon-container">
                        <i class="fas fa-graduation-cap" style="color: var(--primary);"></i>
                        <span class="knowledge-hub-label">Other Courses</span>
                    </div>
                    <button class="go-view">View</button>
                </div>
            </div>
        </main>
    </div>

    <!-- Question Papers Page -->
    <div id="question-papers" class="page">
        <main class="content">
            <div class="content-header">
                <div class="content-tab">
                    <i class="fas fa-file-alt"></i>
                    <span>Question Papers</span>
                </div>
                <button class="back-btn" onclick="showPage('main-page')">
                    <i class="fas fa-arrow-left"></i> Back to Hub
                </button>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search question papers..." onkeyup="searchFiles(this.value)">
            </div>

            <div class="file-list">
                <!-- Question Papers from pastyear folder -->
                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Bahasa Malaysia Karangan</h3>
                            <p>BM_karangan.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/BM_karangan.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/BM_karangan.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Bahasa Malaysia K1K2</h3>
                            <p>BMK1K2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/BMK1K2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/BMK1K2.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Chemistry Paper 2</h3>
                            <p>ChemistryP2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/ChemistryP2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/ChemistryP2.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Chemistry Paper 1</h3>
                            <p>ChemP1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/ChemP1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/ChemP1.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>English Paper 1</h3>
                            <p>EnglishP1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/EnglishP1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/EnglishP1.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>English Paper 2</h3>
                            <p>EnglishP2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/EnglishP2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/EnglishP2.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Mathematics Paper 1</h3>
                            <p>Math P1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/Math P1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/Math P1.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Mathematics Paper 2</h3>
                            <p>Math P2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/Math P2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/Math P2.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Physics Paper 1</h3>
                            <p>Physics P1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/Physics P1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/Physics P1.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Physics Paper 2</h3>
                            <p>Physics P2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/Physics P2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/Physics P2.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Sejarah Paper 1</h3>
                            <p>SejarahP1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/SejarahP1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/SejarahP1.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Sejarah Paper 2</h3>
                            <p>SejarahP2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/pastyear/SejarahP2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/pastyear/SejarahP2.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Notes Page -->
    <div id="notes" class="page">
        <main class="content">
            <div class="content-header">
                <div class="content-tab">
                    <i class="fas fa-sticky-note"></i>
                    <span>Study Notes</span>
                </div>
                <button class="back-btn" onclick="showPage('main-page')">
                    <i class="fas fa-arrow-left"></i> Back to Hub
                </button>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search notes..." onkeyup="searchFiles(this.value)">
            </div>

            <div class="file-list">
                <!-- Notes files -->
                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Chemistry</h3>
                            <p>F4 C7.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/F4_C7.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/F4_C7.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Chemistrry</h3>
                            <p>F5 C3.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/F5_C3.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/F5_C3.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Mathematics</h3>
                            <p>LUAS DAN PERIMETER BULATAN.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/Luas.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/Luas.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Note Karangan BM SPM 2021</h3>
                            <p>Note Karangan BM SPM 2021.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/NotaBM.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/NotaBM.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Mathematics</h3>
                            <p>PENAAKULAN LOGIK.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/Logik.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/Logik.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Physics F5 Chap 5 Modul SPM Percubaan</h3>
                            <p>physics F5 chap 5 modul spm percubaan.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/notes/Physic_notes.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/notes/Physic_notes.pdf" download
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Other Courses Page - UPDATED WITH ONLY 3 MORAL FILES -->
    <div id="other-courses" class="page">
        <main class="content">
            <div class="content-header">
                <div class="content-tab">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Other Courses</span>
                </div>
                <button class="back-btn" onclick="showPage('main-page')">
                    <i class="fas fa-arrow-left"></i> Back to Hub
                </button>
            </div>

            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search courses..." onkeyup="searchFiles(this.value)">
            </div>

            <div class="file-list">
                <!-- Only your 3 Moral files from the other folder -->
                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Moral Bab 1</h3>
                            <p>MoralBab1.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/other/MoralBab1.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/other/MoralBab1.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Moral Bab 2</h3>
                            <p>MoralBab2.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/other/MoralBab2.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/other/MoralBab2.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>

                <div class="file-item">
                    <div class="file-info">
                        <i class="far fa-file-pdf"></i>
                        <div class="file-info-text">
                            <h3>Moral Bab 3</h3>
                            <p>MoralBab3.pdf</p>
                        </div>
                    </div>
                    <div class="file-actions">
                        <a href="../files/other/MoralBab3.pdf" target="_blank"
                            style="text-decoration: none; color: inherit;">
                            <i class="fas fa-eye" title="View"></i>
                        </a>
                        <a href="../files/other/MoralBab3.pdf" download style="text-decoration: none; color: inherit;">
                            <i class="fas fa-download" title="Download"></i>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- File View Modal -->
    <div id="fileModal" class="modal">
        <div class="modal-content">
            <h3 id="modalTitle">File Preview</h3>
            <p>This is a preview of: <strong id="fileName"></strong></p>
            <p>In a real application, this would show the actual file content.</p>
            <button class="close-modal" onclick="closeModal()">Close</button>
        </div>
    </div>

    <!-- Page Footer -->
    <div id="footer-container"></div>
    
    <script src="../js/knowledge.js"></script>
    <script src="../js/sidebar.js"></script>

</body>

</html>