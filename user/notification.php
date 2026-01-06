<?php
// user/notification.php
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
    <title>Crow Education - Notifications</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/notification.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="../css/header.css">

</head>

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





    <!-- Settings Modal -->
    <div class="modal-overlay" id="settingsModal">
        <div class="settings-modal">
            <div class="modal-header">
                <div class="modal-title">Notification Settings</div>
                <button class="close-modal" id="closeSettings">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="settings-section">
                <h3><i class="fas fa-bell"></i> Notification Preferences</h3>
                <div class="setting-item">
                    <span class="setting-label">Push Notifications</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="pushNotifications" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Email Notifications</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="emailNotifications" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Sound Alerts</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="soundAlerts">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="settings-section">
                <h3><i class="fas fa-filter"></i> Notification Filters</h3>
                <div class="setting-item">
                    <span class="setting-label">Course Updates</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="courseUpdates" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Assignment Alerts</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="assignmentAlerts" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <span class="setting-label">System Messages</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="systemMessages" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="settings-section">
                <h3><i class="fas fa-clock"></i> Notification Schedule</h3>
                <div class="setting-item">
                    <span class="setting-label">Quiet Hours</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="quietHours">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Start Time</span>
                    <select class="select-setting" id="quietStart">
                        <option value="22:00">10:00 PM</option>
                        <option value="23:00">11:00 PM</option>
                        <option value="00:00">12:00 AM</option>
                    </select>
                </div>
                <div class="setting-item">
                    <span class="setting-label">End Time</span>
                    <select class="select-setting" id="quietEnd">
                        <option value="06:00">6:00 AM</option>
                        <option value="07:00">7:00 AM</option>
                        <option value="08:00">8:00 AM</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="delete-confirmation-modal">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="delete-modal-title">Delete Notification</div>
            <div class="delete-modal-message" id="deleteModalMessage">
                Are you sure you want to delete this notification? This action cannot be undone.
            </div>
            <div class="delete-modal-actions">
                <button class="delete-modal-btn btn-cancel" id="cancelDelete">Cancel</button>
                <button class="delete-modal-btn btn-confirm-delete" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>

    <main class="content">
        <div class="notification-container">
            <div class="notification-header">
                <h1>Notifications</h1>
                <div class="notification-actions">
                    <button class="notification-action-btn btn-mark-all" id="markAllAsRead">
                        <i class="fas fa-check-double"></i> Mark All as Read
                    </button>
                    <button class="notification-action-btn btn-filter" id="notificationSettings">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                </div>
            </div>

            <div class="notification-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="unread">Unread</button>
                <button class="filter-btn" data-filter="system">System</button>
                <button class="filter-btn" data-filter="assignment">Assignments</button>
                <button class="filter-btn" data-filter="course">Courses</button>
            </div>

            <div class="notification-list" id="notificationList">
                <!-- Notification items will be dynamically added here -->
            </div>
        </div>


    <!-- Page Footer -->
    <div id="footer-container"></div>
</main>

<script src="../js/notification.js"></script>
<script src="../js/sidebar.js"></script>
</body>

</html>