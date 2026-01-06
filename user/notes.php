<?php
// user/notes.php
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
    <title>Crow Education - Notes Module</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/notes.css">
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

        <main class="content">
            <div class="welcome-section">
                <h1>Notes</h1>
                <p>Organize your thoughts and ideas</p>
            </div>

            <div class="notes-container">
                <div class="notes-sidebar">
                    <div class="notes-search">
                        <input type="text" placeholder="Search notes..." id="searchNotes">
                    </div>

                    <div class="notes-list" id="notesList">
                        <!-- Notes will be dynamically added here -->
                    </div>
                </div>

                <div class="notes-main">
                    <div class="notes-header">
                        <h2>Note Editor</h2>
                        <button class="create-note-btn" id="createNoteBtn">
                            <i class="fas fa-plus"></i> New Note
                        </button>
                    </div>

                    <div class="note-editor" id="noteEditor">
                        <div class="empty-state" id="emptyState">
                            <i class="fas fa-sticky-note"></i>
                            <h3>No Note Selected</h3>
                            <p>Select a note from the sidebar or create a new one to start editing.</p>
                        </div>

                        <div class="editor-content" id="editorContent" style="display: none;">
                            <input type="text" class="note-title-input" id="noteTitle" placeholder="Note Title">

                            <div class="tag-input">
                                <input type="text" id="tagInput" placeholder="Add tags (separate with commas)">
                            </div>

                            <div class="note-tags" id="noteTags">
                                <!-- Tags will be dynamically added here -->
                            </div>

                            <div class="editor-toolbar">
                                <button class="toolbar-btn" title="Bold" data-command="bold">
                                    <i class="fas fa-bold"></i>
                                </button>
                                <button class="toolbar-btn" title="Italic" data-command="italic">
                                    <i class="fas fa-italic"></i>
                                </button>
                                <button class="toolbar-btn" title="Underline" data-command="underline">
                                    <i class="fas fa-underline"></i>
                                </button>
                                <button class="toolbar-btn" title="Bullet List" data-command="insertUnorderedList">
                                    <i class="fas fa-list-ul"></i>
                                </button>
                                <button class="toolbar-btn" title="Numbered List" data-command="insertOrderedList">
                                    <i class="fas fa-list-ol"></i>
                                </button>
                                <button class="toolbar-btn" title="Link" data-command="createLink">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>

                            <div class="note-content" id="noteContent" contenteditable="true"
                                placeholder="Start typing your note here..."></div>

                            <div class="editor-actions">
                                <button class="delete-btn" id="deleteNoteBtn">
                                    <i class="fas fa-trash"></i> Delete Note
                                </button>
                                <button class="save-btn" id="saveNoteBtn">
                                    <i class="fas fa-save"></i> Save Note
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Page Footer -->
    <div id="footer-container"></div>    

    <script src="../js/notes.js"></script>
<script src="../js/sidebar.js"></script>

</body>

</html>