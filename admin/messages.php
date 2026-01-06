<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'messages';

// 获取联系人列表（学生）
$contacts = [];
$sql = "SELECT u.user_id, u.full_name, u.email, up.profile_image, sp.status as student_status 
        FROM users u 
        LEFT JOIN user_profiles up ON u.user_id = up.user_id 
        LEFT JOIN student_profiles sp ON u.user_id = sp.user_id 
        WHERE u.role = 'student' 
        ORDER BY u.full_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
}

// 获取聊天记录
$chats = [];
$messages = [];
$selected_chat = isset($_GET['chat_id']) ? $_GET['chat_id'] : null;

if ($selected_chat) {
    $msg_sql = "SELECT m.*, u.full_name as sender_name 
                FROM messages m 
                JOIN users u ON m.sender_id = u.user_id 
                WHERE m.chat_id = ? 
                ORDER BY m.sent_at ASC";
    $msg_stmt = $conn->prepare($msg_sql);
    $msg_stmt->bind_param("i", $selected_chat);
    $msg_stmt->execute();
    $msg_result = $msg_stmt->get_result();
    
    if ($msg_result->num_rows > 0) {
        while($row = $msg_result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
}

// 处理发送消息
if (isset($_POST['send_message'])) {
    $chat_id = $_POST['chat_id'];
    $message_text = $_POST['message_text'];
    $sender_id = 2; // 教师ID
    
    $insert_sql = "INSERT INTO messages (chat_id, sender_id, message_text) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iis", $chat_id, $sender_id, $message_text);
    
    if ($insert_stmt->execute()) {
        header("Location: messages.php?chat_id=" . $chat_id . "&sent=1");
        exit();
    }
}

// 处理添加学生
if (isset($_POST['add_student'])) {
    $name = $_POST['student_name'];
    $email = $_POST['student_email'];
    $phone = $_POST['student_phone'];
    
    // 首先检查学生是否已存在
    $check_sql = "SELECT user_id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows == 0) {
        // 创建新学生账户
        $insert_user_sql = "INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'student')";
        $insert_user_stmt = $conn->prepare($insert_user_sql);
        $username = strtolower(str_replace(' ', '_', $name));
        $password = password_hash('student123', PASSWORD_DEFAULT);
        $insert_user_stmt->bind_param("ssss", $username, $email, $password, $name);
        
        if ($insert_user_stmt->execute()) {
            $new_user_id = $conn->insert_id;
            
            // 创建学生档案
            $insert_profile_sql = "INSERT INTO student_profiles (user_id, average_score, status) VALUES (?, 0, 'Active')";
            $insert_profile_stmt = $conn->prepare($insert_profile_sql);
            $insert_profile_stmt->bind_param("i", $new_user_id);
            $insert_profile_stmt->execute();
            
            header("Location: messages.php?student_added=1");
            exit();
        }
    } else {
        header("Location: messages.php?error=student_exists");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Chat Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/message.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-crow"></i>
                <h1>Crow Education</h1>
            </div>
            <ul class="menu">
                <li class="menu-item">
                    <a href="dashboard.php" class="menu-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="user.php" class="menu-link">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <span class="menu-badge">5</span>
                    </a>
                     <li class="menu-item">
                    <a href="lessons.php" class="menu-link">
                        <i class="fas fa-book-open"></i>
                        <span>Manage Lessons</span>
                    </a>
                </li>
                </li>
                <li class="menu-item">
                    <a href="homework.php" class="menu-link">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Homework</span>
                        <span class="menu-badge">12</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="assessments.php" class="menu-link">
                        <i class="fas fa-file-alt"></i>
                        <span>Manage Assessments</span>
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="messages.php" class="menu-link">
                        <i class="fas fa-comments"></i>
                        <span>Manage Messages</span>
                        <span class="menu-badge">3</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="music.php" class="menu-link">
                        <i class="fas fa-music"></i>
                        <span>Manage Music</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php" class="menu-link">
                        <i class="fas fa-cog"></i>
                        <span>Manage Settings</span>
                    </a>
                </li>
            </ul>
            <div class="teacher-profile">
                <div class="teacher-avatar">YC</div>
                <div class="teacher-info">
                    <div class="teacher-name">Yee Ching</div>
                    <div class="teacher-role">Teacher</div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <h2>Teacher Dashboard</h2>
                <div class="header-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search messages..." id="globalSearch">
                    </div>
                    <div class="user-info">
                        <div class="dark-mode-toggle" id="darkModeToggle">
                            <i class="fas fa-moon toggle-icon"></i>
                            <span class="toggle-text">Dark Mode</span>
                        </div>
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">5</span>
                        </div>
                        <div class="user-avatar">YC</div>
                    </div>
                </div>
            </div>

            <div class="chat-content">
                <div class="chat-dashboard">
                    <div class="contacts-panel">
                        <div class="contacts-header">
                            <div class="contacts-title">Manage Messages</div>
                            <div class="header-actions-right">
                                <button class="add-student-btn" id="addStudentBtn" title="Add Student">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                                <button class="new-chat-btn" id="newChatBtn" title="New Chat">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="contacts-search">
                            <input type="text" placeholder="Search contacts..." id="contactsSearch">
                        </div>
                        <div class="contacts-list" id="contactsList">

                        </div>
                    </div>

                    <div class="chat-panel">
                        <div class="chat-header" id="chatHeader" style="display: none;">
                            <div class="chat-contact">
                                <div class="chat-contact-avatar" id="chatAvatar">JD</div>
                                <div class="chat-contact-info">
                                    <div class="chat-contact-name" id="chatName">John Doe</div>
                                    <div class="chat-contact-status" id="chatStatus">Online</div>
                                </div>
                            </div>
                            <div class="chat-actions">
                                <button class="chat-action-btn" id="deleteStudentBtn" title="Delete Student">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="chat-action-btn" id="contactInfoBtn" title="Contact Info">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>

                        <div class="chat-messages" id="chatMessages">
                            <div class="empty-chat" id="emptyChat">
                                <div class="empty-chat-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h3 class="empty-chat-text">Select a conversation to start messaging</h3>
                                <p class="empty-chat-text">Choose a contact from the list to view your messages</p>
                            </div>
                        </div>

                        <div class="chat-input" id="chatInput" style="display: none;">
                            <button class="attachment-btn" id="attachmentBtn">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <textarea class="message-input" id="messageInput" placeholder="Type a message..." rows="1"></textarea>
                            <button class="send-btn" id="sendBtn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="site-footer">
                <div class="footer-container">
                    <div class="footer-column">
                        <div class="footer-logo">
                            <i class="fas fa-crow"></i>
                            <span>Crow Education</span>
                        </div>
                    </div>

                    <div class="footer-column">
                        <h4>[ABOUT]</h4>
                        <ul class="toggle-list">
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-about-us">
                                    About us
                                </button>
                                <div class="footer-panel" id="panel-about-us">
                                    <a href="#">Bird2 Team</a>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-contact">
                                    Contact us
                                </button>
                                <div class="footer-panel" id="panel-contact">
                                    <a href="mailto:birdbird945@gmail.com">birdbird945@gmail.com</a>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-mission">
                                    Our Mission
                                </button>
                                <div class="footer-panel" id="panel-mission">
                                    <em>✨ To help students learn smarter.
                                        <div>💬 To support teachers in sharing knowledge.</div>
                                        <div>🌱 To make education easier — for everyone.</div>
                                    </em>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-slogan">
                                    Slogan
                                </button>
                                <div class="footer-panel" id="panel-slogan">
                                    <em>Grow with us, Glow your future</em>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>[LEARN]</h4>
                        <ul class="normal-list">
                            <li><a href="#">Class</a></li>
                            <li><a href="#">Discussion Hub</a></li>
                            <li><a href="#">Subject Finder</a></li>
                            <li><a href="#">Knowledge Hub</a></li>
                            <li><a href="#">Study Method</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>[HELP & SUPPORT]</h4>
                        <ul class="normal-list">
                            <li><a href="#">Feedback</a></li>
                        </ul>
                    </div>
                </div>
                <p class="last">© 2025 Crow Education EduTeach Platform · Teacher Dashboard</p>
            </footer>
        </div>
    </div>

    <div class="modal" id="newChatModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">New Conversation</h3>
                <button class="modal-close" id="closeNewChatModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Select Contact</label>
                    <input type="text" class="form-control" id="modalSearch" placeholder="Search contacts...">
                </div>
                <div id="modalContactsList">
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn secondary-btn" id="cancelNewChatBtn">Cancel</button>
                <button class="action-btn primary-btn" id="startChatBtn">Start Chat</button>
            </div>
        </div>
    </div>

    <div class="modal" id="contactInfoModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Contact Information</h3>
                <button class="modal-close" id="closeContactInfoModal">&times;</button>
            </div>
            <div class="modal-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div class="contact-avatar" id="infoAvatar" style="width: 80px; height: 80px; margin: 0 auto 15px; font-size: 24px;">JD</div>
                    <h3 id="infoName">John Doe</h3>
                    <p id="infoStatus" style="color: var(--gray);">Online</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="form-control" style="background-color: var(--light);" id="infoEmail">john.doe@example.com</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone</label>
                    <div class="form-control" style="background-color: var(--light);" id="infoPhone">+1 (555) 123-4567</div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn secondary-btn" id="closeContactInfoBtn">Close</button>
            </div>
        </div>
    </div>

    <div class="modal" id="addStudentModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Student</h3>
                <button class="modal-close" id="closeAddStudentModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="studentName" placeholder="Enter student's full name">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" id="studentEmail" placeholder="Enter student's email">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="studentPhone" placeholder="Enter student's phone number">
                </div>
            </div>
            <div class="modal-footer">
                <button class="action-btn secondary-btn" id="cancelAddStudentBtn">Cancel</button>
                <button class="action-btn primary-btn" id="saveStudentBtn">Save Student</button>
            </div>
        </div>
    </div>

    <div class="attachment-modal" id="attachmentModal">
        <div class="attachment-modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Attach File</h3>
                <button class="modal-close" id="closeAttachmentModal">&times;</button>
            </div>
            <div class="attachment-options">
                <button class="attachment-option" id="attachDocument">
                    <i class="fas fa-file-pdf"></i>
                    <span>Document</span>
                </button>
                <button class="attachment-option" id="attachImage">
                    <i class="fas fa-image"></i>
                    <span>Image</span>
                </button>
                <button class="attachment-option" id="attachVideo">
                    <i class="fas fa-video"></i>
                    <span>Video</span>
                </button>
                <button class="attachment-option" id="attachAudio">
                    <i class="fas fa-music"></i>
                    <span>Audio</span>
                </button>
                <input type="file" id="fileInput" class="file-input" accept="*/*">
            </div>
        </div>
    </div>

    <script src="../admin_php/JS/messages.js"></script>
</body>
</html>