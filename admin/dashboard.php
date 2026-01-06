<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'dashboard';

// 获取课程数据
$lessons = [];
$lesson_stats = [
    'total' => 0,
    'onprogress' => 0,
    'completed' => 0
];

$sql = "SELECT * FROM lessons WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$teacher_id = 2;
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lessons[] = $row;
        $lesson_stats['total']++;
        if ($row['status'] == 'published') {
            $lesson_stats['onprogress']++;
        } else if ($row['status'] == 'archived') {
            $lesson_stats['completed']++;
        }
    }
}

// 获取仪表板统计数据
$dashboard_stats = [
    'total_viewers' => 5201,
    'pending_tasks' => 12,
    'pending_homework' => 8
];

// 获取最近活动
$recent_activities = [];
$activity_sql = "SELECT a.*, u.full_name 
                 FROM activity_log a 
                 JOIN users u ON a.user_id = u.user_id 
                 ORDER BY a.created_at DESC 
                 LIMIT 3";
$activity_result = $conn->query($activity_sql);

if ($activity_result->num_rows > 0) {
    while($row = $activity_result->fetch_assoc()) {
        $recent_activities[] = $row;
    }
}

// 处理创建新课程
if (isset($_POST['create_lesson'])) {
    $title = $_POST['lesson_title'];
    $subject = $_POST['lesson_subject'];
    $class_level = $_POST['lesson_form'];
    $description = $_POST['lesson_description'];
    
    $insert_sql = "INSERT INTO lessons (title, description, subject, class_level, created_by, status) 
                   VALUES (?, ?, ?, ?, ?, 'draft')";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssssi", $title, $description, $subject, $class_level, $teacher_id);
    
    if ($insert_stmt->execute()) {
        header("Location: dashboard.php?create_success=1");
        exit();
    }
}

// 处理删除课程
if (isset($_POST['delete_lesson'])) {
    $lesson_id = $_POST['lesson_id'];
    $delete_sql = "DELETE FROM lessons WHERE lesson_id = ? AND created_by = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $lesson_id, $teacher_id);
    
    if ($delete_stmt->execute()) {
        header("Location: dashboard.php?delete_success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/dashboard.css">
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-crow"></i>
                <h1>Crow Education</h1>
            </div>
            <ul class="menu">
                <li class="menu-item active">
                    <a href="#" class="menu-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="user.php" class="menu-link <?php echo $current_page === 'users' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <span class="menu-badge">5</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="lessons.php" class="menu-link <?php echo $current_page === 'lessons' ? 'active' : ''; ?>">
                        <i class="fas fa-book-open"></i>
                        <span>Manage Lessons</span>
                    </a>
                </li>
                 <li class="menu-item">
                    <a href="homework.php" class="menu-link <?php echo $current_page === 'homework' ? 'active' : ''; ?>">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Homework</span>
                        <span class="menu-badge">12</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="assessments.php" class="menu-link <?php echo $current_page === 'assessments' ? 'active' : ''; ?>">
                        <i class="fas fa-file-alt"></i>
                        <span>Manage Assessments</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="messages.php" class="menu-link <?php echo $current_page === 'messages' ? 'active' : ''; ?>">
                        <i class="fas fa-comments"></i>
                        <span>Manage Messages</span>
                        <span class="menu-badge">3</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="music.php" class="menu-link <?php echo $current_page === 'music' ? 'active' : ''; ?>">
                        <i class="fas fa-music"></i>
                        <span>Manage Music</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php" class="menu-link <?php echo $current_page === 'settings' ? 'active' : ''; ?>">
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
                        <input type="text" placeholder="Search lessons...">
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

            <div class="todays-classes" id="todaysClassesSection">
                <div class="section-header">
                    <h3 class="section-title">All Lessons</h3>
                    <div class="section-actions">
                        <button class="btn btn-primary" id="createLessonBtn">
                            <i class="fas fa-plus"></i>
                            <span>Create Lesson</span>
                        </button>
                    </div>
                </div>
                
                <div class="class-tabs">
                    <button class="class-tab active" data-filter="all">
                        <span>All Lessons</span>
                        <span class="class-tab-badge">6</span>
                    </button>
                    <button class="class-tab" data-filter="onprogress">
                        <span>On Progress</span>
                        <span class="class-tab-badge">4</span>
                    </button>
                    <button class="class-tab" data-filter="completed">
                        <span>Completed</span>
                        <span class="class-tab-badge">2</span>
                    </button>
                </div>
                
                <div class="classes-grid">
                    <div class="class-card onprogress" data-status="onprogress">
                        <div class="class-status">
                            <div class="status-indicator onprogress"></div>
                            <span>On Progress</span>
                        </div>
                        <div class="class-subject">History</div>
                        <div class="class-details">
                            <span>Form 4</span>
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-onprogress">ACTIVE</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="History">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="class-card onprogress" data-status="onprogress">
                        <div class="class-status">
                            <div class="status-indicator onprogress"></div>
                            <span>On Progress</span>
                        </div>
                        <div class="class-subject">Add.Math</div>
                        <div class="class-details">
                            <span>Form 5</span>
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-onprogress">ACTIVE</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="Add.Math">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="class-card completed" data-status="completed">
                        <div class="class-status">
                            <div class="status-indicator completed"></div>
                            <span>Completed</span>
                        </div>
                        <div class="class-subject">Art</div>
                        <div class="class-details">
                            <span>Form 3</span> 
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-completed">COMPLETED</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="Art">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="class-card onprogress" data-status="onprogress">
                        <div class="class-status">
                            <div class="status-indicator onprogress"></div>
                            <span>On Progress</span>
                        </div>
                        <div class="class-subject">Science</div>
                        <div class="class-details">
                            <span>Form 4</span>
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-onprogress">ACTIVE</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="Science">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="class-card onprogress" data-status="onprogress">
                        <div class="class-status">
                            <div class="status-indicator onprogress"></div>
                            <span>On Progress</span>
                        </div>
                        <div class="class-subject">English</div>
                        <div class="class-details">
                            <span>Form 5</span>
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-onprogress">ACTIVE</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="English">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="class-card completed" data-status="completed">
                        <div class="class-status">
                            <div class="status-indicator completed"></div>
                            <span>Completed</span>
                        </div>
                        <div class="class-subject">Geography</div>
                        <div class="class-details">
                            <span>Form 3</span> 
                        </div>
                        <div class="class-progress">
                            <div class="progress-info">
                                <span>Viewer</span>
                                <span>60%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="class-actions">
                            <span class="status-badge status-completed">COMPLETED</span>
                            <button class="btn btn-outline view-materials-btn" data-subject="Geography">
                                <span>View Materials</span>
                            </button>
                            <button class="btn btn-danger delete-lesson-btn">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Overview</h3>
                        <div class="card-icon" style="background-color: var(--primary);">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value">5,201</div>
                            <div class="stat-label">Total Viewers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">12</div>
                            <div class="stat-label">Pending Tasks</div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">8</div>
                        <div class="stat-label">Pending Homework</div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent Activity</h3>
                        <div class="card-icon" style="background-color: var(--accent);">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: var(--success);">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">Agus submitted Algebra assignment</div>
                                <div class="activity-time">10 minutes ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: var(--primary);">
                                <i class="fas fa-comment"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">Seah Yang posted a question in History forum</div>
                                <div class="activity-time">45 minutes ago</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: var(--warning);">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-text">New announcement posted for Form 5</div>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Homework Progress</h3>
                        <div class="card-icon" style="background-color: var(--secondary);">
                            <i class="fas fa-tasks"></i>
                        </div>
                    </div>
                    <div class="progress-list">
                        <div class="progress-item">
                            <div class="progress-header">
                                <div class="progress-title">History Essay</div>
                                <div class="progress-percent">78%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 78%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <div class="progress-title">Add.Math Problems</div>
                                <div class="progress-percent">65%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="progress-item">
                            <div class="progress-header">
                                <div class="progress-title">Art Portfolio</div>
                                <div class="progress-percent">42%</div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 42%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quick Actions</h3>
                        <div class="card-icon" style="background-color: var(--warning);">
                            <i class="fas fa-bolt"></i>
                        </div>
                    </div>
                    <div class="quick-actions">
                        <a href = "homework.php" class="card-link" style="text-decoration: none; color: inherit;">
                        <div class="action-btn">
                            <div class="action-icon" style="background-color: var(--primary);">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-text">Create Homework</div>
                        </div>
                        </a>
                        <a href = "assessments.php" class="card-link" style="text-decoration: none; color: inherit;">
                        <div class="action-btn">
                            <div class="action-icon" style="background-color: var(--secondary);">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div class="action-text">View Assessments</div>
                        </div>
                        </a>
                        <a href = "messages.php" class="card-link" style="text-decoration: none; color: inherit;">
                        <div class="action-btn">
                            <div class="action-icon" style="background-color: var(--success);">
                                <i class="fas fa-comments"></i>
                            </div>
                            <div class="action-text">Chat Student</div>
                        </div>
                        </a>
                        <a href = "music.php" class="card-link" style="text-decoration: none; color: inherit;">
                        <div class="action-btn">
                            <div class="action-icon" style="background-color: var(--accent);">
                                <i class="fas fa-music"></i>
                            </div>
                            <div class="action-text">Listen Music</div>
                        </div>
                        </a>
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

    <div class="modal" id="materialsModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalSubjectTitle">Subject Materials</h2>
                <button class="close-modal" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="materials-section">
                    <h3>PDF Documents</h3>
                    <div class="materials-grid" id="pdfMaterials">
                    </div>
                </div>
                
                <div class="materials-section">
                    <h3>Video Lessons</h3>
                    <div class="materials-grid" id="videoMaterials">
                    </div>
                </div>
                
                <div class="materials-section">
                    <h3>Audio Resources</h3>
                    <div class="materials-grid" id="audioMaterials">
                    </div>
                </div>
                
                <div class="materials-section">
                    <h3>Interactive Activities</h3>
                    <div class="materials-grid" id="interactiveMaterials">
                    </div>
                </div>
                
                <div class="material-preview">
                    <div class="preview-header">
                        <h3 class="preview-title">Preview</h3>
                        <div class="preview-actions">
                            <button class="btn btn-outline" id="downloadBtn">
                                <i class="fas fa-download"></i>
                                <span>Download</span>
                            </button>
                            <button class="btn btn-primary" id="openBtn">
                                <i class="fas fa-external-link-alt"></i>
                                <span>Open</span>
                            </button>
                        </div>
                    </div>
                    <div class="preview-content">
                        <div class="preview-placeholder" id="previewPlaceholder">
                            Select a material to preview
                        </div>
                        <div id="previewContent" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="create-lesson-modal" id="createLessonModal">
        <div class="create-lesson-content">
            <div class="create-lesson-header">
                <h2 class="create-lesson-title">Create New Lesson</h2>
                <button class="close-create-lesson" id="closeCreateLessonModal">&times;</button>
            </div>
            <div class="create-lesson-body">
                <form id="createLessonForm">
                    <div class="form-group">
                        <label class="form-label" for="lessonTitle">Lesson Title</label>
                        <input type="text" id="lessonTitle" class="form-control" placeholder="Enter lesson title" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="lessonSubject">Subject</label>
                        <select id="lessonSubject" class="form-select" required>
                            <option value="">Select a subject</option>
                            <option value="History">History</option>
                            <option value="Add.Math">Add.Math</option>
                            <option value="Art">Art</option>
                            <option value="Science">Science</option>
                            <option value="English">English</option>
                            <option value="Geography">Geography</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Biology">Biology</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Physics">Physics</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="lessonForm">Form/Class</label>
                        <select id="lessonForm" class="form-select" required>
                            <option value="">Select form/class</option>
                            <option value="Form 1">Form 1</option>
                            <option value="Form 2">Form 2</option>
                            <option value="Form 3">Form 3</option>
                            <option value="Form 4">Form 4</option>
                            <option value="Form 5">Form 5</option>
                            <option value="Form 6">Form 6</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="lessonDescription">Description</label>
                        <textarea id="lessonDescription" class="form-control form-textarea" placeholder="Enter lesson description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="lessonMaterials">Materials (Optional)</label>
                        <input type="file" id="lessonMaterials" class="form-control" multiple>
                        <small style="color: var(--gray); font-size: 12px;">You can upload PDFs, videos, images, or other materials</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" id="cancelCreateLesson">Cancel</button>
                        <button type="submit" class="btn btn-create">Create Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="delete-confirmation-modal" id="deleteConfirmationModal">
        <div class="delete-confirmation-content">
            <div class="delete-confirmation-header">
                <h2 class="delete-confirmation-title">Delete Lesson</h2>
                <button class="close-delete-confirmation" id="closeDeleteConfirmation">&times;</button>
            </div>
            <div class="delete-confirmation-body">
                <p class="delete-confirmation-text" id="deleteConfirmationText">Are you sure you want to delete this lesson? This action cannot be undone.</p>
                <div class="delete-confirmation-actions">
                    <button class="btn btn-cancel-delete" id="cancelDelete">Cancel</button>
                    <button class="btn btn-confirm-delete" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i>
        <span id="successText">Lesson created successfully!</span>
    </div>

    <div class="delete-message" id="deleteMessage">
        <i class="fas fa-check-circle"></i>
        <span id="deleteText">Lesson deleted successfully!</span>
    </div>

    <div class="download-indicator" id="downloadIndicator">
        <i class="fas fa-download"></i>
        <div>
            <span id="downloadText">Downloading file...</span>
            <div class="download-progress">
                <div class="download-progress-bar" id="downloadProgressBar"></div>
            </div>
        </div>
    </div>

    <script src="../admin_php/JS/dashboard.js"></script>
</body>
</html>