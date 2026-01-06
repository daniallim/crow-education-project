<?php
// user/community.php
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
    <title>Crow Education - Community </title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/community.css">
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
            <h1>Community</h1>
        </div>

        <div class="chat-container" id="chatContainer">
            <div class="chat-sidebar" id="chatSidebar">
                <div class="chat-header">
                    <h2>Conversations</h2>
                    <button class="new-chat-btn" id="newChatBtn">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="chat-search">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search by name or email..." id="chatSearch">
                    <div class="search-results" id="searchResults"></div>
                </div>
                <div class="chat-list" id="chatList">
                    <div class="chat-item active" data-chat-id="1">
                        <div class="chat-avatar">SJ</div>
                        <div class="chat-info">
                            <div class="chat-name">Maths Study Group</div>
                            <div class="chat-preview">Hey, what is y=mx+c again? I forgot!</div>
                            <div class="chat-time">10:24 AM</div>
                        </div>
                        <div class="chat-badge">3</div>
                        <button class="chat-item-delete" title="Delete chat">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="chat-item" data-chat-id="2">
                        <div class="chat-avatar">AC</div>
                        <div class="chat-info">
                            <div class="chat-name">Alex Chen</div>
                            <div class="chat-preview">I found a great resource for calculus</div>
                            <div class="chat-time">Yesterday</div>
                        </div>
                        <button class="chat-item-delete" title="Delete chat">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <!-- Resize Handle -->
                <div class="resize-handle" id="resizeHandle"></div>
            </div>

            <div class="chat-main" id="chatMain">
                <!-- Layout Controls -->
                <div class="chat-layout-controls">
                    <button class="layout-btn" id="expandSidebar" title="Expand Sidebar">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="layout-btn" id="collapseSidebar" title="Collapse Sidebar">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="layout-btn" id="resetLayout" title="Reset Layout">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>

                <div class="chat-main-header">
                    <div class="chat-main-info">
                        <div class="chat-main-avatar">SJ</div>
                        <div class="chat-main-details">
                            <h3>Maths Study Group</h3>
                            <p>sarah.johnson@example.com</p>
                        </div>
                    </div>
                    <div class="chat-actions">
                        <button class="chat-action-btn" id="chatSettingsBtn" title="More Options">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                    <div class="chat-settings-menu" id="chatSettingsMenu">
                        <div class="settings-menu-item" id="deleteChatBtn">
                            <i class="fas fa-trash"></i>
                            <span>Delete Chat</span>
                        </div>
                    </div>
                </div>
                <div class="chat-messages">
                    <div class="message received">
                        <div class="message-text">Hey, what is y=mx+c again? I forgot!
                        </div>
                        <div class="message-time">Sarah • 10:15 AM</div>
                    </div>
                    <div class="message sent">
                        <div class="message-text">Yes, I'm stuck on the first question. I am so confused.
                        </div>
                        <div class="message-time">You • 10:18 AM</div>
                        <div class="message-status"><i class="fas fa-check-double"></i></div>
                    </div>
                    <div class="message received">
                        <div class="message-text">I finished that one! It requires the quadratic formula.</div>
                        <div class="message-time">Sarah • 10:20 AM</div>
                    </div>
                </div>
                <div class="chat-input-container">
                    <div class="chat-input-actions">
                        <button class="chat-input-action" id="fileUploadBtn">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <input type="file" id="fileUploadInput" class="file-upload-input"
                            accept=".pdf,.doc,.docx,.jpg,.png">
                        <button class="chat-input-action" id="emojiBtn">
                            <i class="fas fa-smile"></i>
                        </button>
                    </div>
                    <textarea class="chat-input" placeholder="Type a message..." id="chatInput"></textarea>
                    <button class="chat-send-btn" id="sendBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                <div class="emoji-picker" id="emojiPicker">
                    <div class="emoji-option">😀</div>
                    <div class="emoji-option">😂</div>
                    <div class="emoji-option">😍</div>
                    <div class="emoji-option">🤔</div>
                    <div class="emoji-option">👍</div>
                    <div class="emoji-option">❤️</div>
                    <div class="emoji-option">🎉</div>
                    <div class="emoji-option">🔥</div>
                    <div class="emoji-option">⭐</div>
                    <div class="emoji-option">📚</div>
                    <div class="emoji-option">✏️</div>
                    <div class="emoji-option">✅</div>
                </div>
            </div>
        </div>
    </main>

    <!-- New Chat Modal -->
    <div class="modal-overlay" id="newChatModal">
        <div class="new-chat-modal">
            <div class="modal-header">
                <h3>Start a New Conversation</h3>
                <button class="close-modal" id="closeModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-search">
                    <i class="fas fa-search modal-search-icon"></i>
                    <input type="text" placeholder="Search users by name or email..." id="userSearch">
                </div>
                <div class="user-list" id="userList">
                    <!-- Users will be populated here by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" id="cancelNewChat">Cancel</button>
                <button class="start-chat-modal-btn" id="startNewChat" disabled>Start Chat</button>
            </div>
        </div>
    </div>

    <!-- Delete Chat Modal -->
    <div class="modal-overlay" id="deleteChatModal">
        <div class="delete-chat-modal">
            <div class="modal-header">
                <h3>Delete Chat</h3>
                <button class="close-modal" id="closeDeleteModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="delete-modal-body">
                <div class="delete-modal-icon">
                    <i class="fas fa-trash-alt"></i>
                </div>
                <div class="delete-modal-text">
                    <h3>Are you sure?</h3>
                    <p>This action will permanently delete your conversation with <span id="deleteChatName">Sarah
                            Johnson</span>. This cannot be undone.</p>
                </div>
            </div>
            <div class="delete-modal-footer">
                <button class="cancel-delete-btn" id="cancelDeleteChat">Cancel</button>
                <button class="confirm-delete-btn" id="confirmDeleteChat">Delete Chat</button>
            </div>
        </div>
    </div>


    <!-- User Profile Modal -->
    <div class="modal-overlay" id="userProfileModal">
        <div class="user-profile-modal">
            <div class="modal-header">
                <h3>User Profile</h3>
                <button class="close-modal" id="closeProfileModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="profile-modal-body">
                <div class="profile-avatar-large">
                    <div class="profile-avatar-img" id="profileAvatar">SJ</div>
                </div>
                <div class="profile-info">
                    <h2 id="profileName">Maths Study Group</h2>
                    <p class="profile-email" id="profileEmail">sarah.johnson@example.com</p>
                    <div class="profile-stats">
                        <div class="stat-item">
                            <span class="stat-number" id="profileMessages">24</span>
                            <span class="stat-label">Messages</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="profileFiles">3</span>
                            <span class="stat-label">Files Shared</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="profileJoined">15d</span>
                            <span class="stat-label">Joined</span>
                        </div>
                    </div>
                    <div class="profile-details">
                        <div class="detail-item">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Computer Science Science</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-university"></i>
                            <span>University of Technology</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>New York, NY</span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>Last seen: Today at 10:30 AM</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-modal-footer">
                <button class="profile-action-btn" id="sendMessageBtn">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
                <button class="profile-action-btn secondary" id="viewSharedFilesBtn">
                    <i class="fas fa-folder-open"></i>
                    View Shared Files
                </button>
            </div>
        </div>
    </div>

    <!-- Page Footer -->
    <div id="footer-container"></div>    


    <script src="../js/community.js"></script>
    <script src="../js/sidebar.js"></script>

</body>

</html>