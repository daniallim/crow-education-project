<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'lessons';

// 获取课程数据
$lessons = [];
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM lessons WHERE created_by = ?";
$params = ["i", 2];
$param_values = [2];

if ($filter !== 'all') {
    $sql .= " AND status = ?";
    $params[0] .= "s";
    $param_values[] = $filter;
}

if (!empty($search_term)) {
    $sql .= " AND (title LIKE ? OR subject LIKE ? OR description LIKE ?)";
    $params[0] .= "sss";
    $search_like = "%" . $search_term . "%";
    $param_values[] = $search_like;
    $param_values[] = $search_like;
    $param_values[] = $search_like;
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param(...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $lessons[] = $row;
        }
    }
}

// 处理文件上传
if (isset($_POST['confirm_upload'])) {
    if (isset($_FILES['lesson_files']) && !empty($_FILES['lesson_files']['name'][0])) {
        $upload_dir = "../uploads/lessons/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $files = $_FILES['lesson_files'];
        $uploaded_files = [];
        
        for ($i = 0; $i < count($files['name']); $i++) {
            $file_name = time() . '_' . basename($files['name'][$i]);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                $uploaded_files[] = [
                    'name' => $files['name'][$i],
                    'path' => $file_path,
                    'type' => pathinfo($files['name'][$i], PATHINFO_EXTENSION)
                ];
            }
        }
        
        // 保存到数据库的逻辑可以在这里添加
        header("Location: lessons.php?upload_success=1");
        exit();
    }
}

// 处理发布课程
if (isset($_POST['publish_lesson'])) {
    $lesson_id = $_POST['lesson_id'];
    $update_sql = "UPDATE lessons SET status = 'published', published_date = CURDATE() WHERE lesson_id = ? AND created_by = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $lesson_id, 2);
    
    if ($update_stmt->execute()) {
        header("Location: lessons.php?publish_success=1");
        exit();
    }
}

// 处理删除课程
if (isset($_POST['delete_lesson'])) {
    $lesson_id = $_POST['lesson_id'];
    $delete_sql = "DELETE FROM lessons WHERE lesson_id = ? AND created_by = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $lesson_id, 2);
    
    if ($delete_stmt->execute()) {
        header("Location: lessons.php?delete_success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Manage Lessons</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/lesson.css">
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
                    <a href="dashboard.php" class="menu-link <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>">
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

            <h1 class="page-title">Manage Lessons</h1>
            
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">All Lessons</button>
                <button class="filter-tab" data-filter="drafts">Drafts</button>
                <button class="filter-tab" data-filter="published">Published</button>
            </div>

            <div class="search-box-content">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search lessons..." id="searchInput">
            </div>

            <div class="upload-section">
                <h2 class="upload-title">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Upload New Learning Materials
                </h2>
                <div class="upload-area" id="uploadArea">
                    <i class="fas fa-file-upload"></i>
                    <p class="upload-text">Drag & drop files here or click the button below</p>
                    <p class="upload-hint">Supported formats: PDF, DOC, PPT, JPG, PNG, MP4</p>
                    <button class="btn btn-primary" id="selectFilesBtn">
                        Select Files
                    </button>
                    <input type="file" class="file-input" id="fileInput" multiple>
                </div>
                <div class="file-list" id="fileList"></div>
                <div class="confirm-section">
                    <button class="btn btn-success" id="confirmUploadBtn" style="display: none;">
                        <i class="fas fa-check"></i>
                        Confirm Upload
                    </button>
                </div>
            </div>

            <h2 class="section-title">Your Lessons</h2>

            <div class="lesson-list" id="lessonList">
                <div class="lesson-item" data-status="published">
                    <div class="lesson-header">
                        <div>
                            <h3 class="lesson-title">Science Chapter 2: Atoms</h3>
                            <p class="lesson-subject">Subject: Chemistry Basics</p>
                        </div>
                        <span class="status-badge status-published">Published</span>
                    </div>
                    <div class="lesson-details">
                        <div class="lesson-stats">
                            <div class="stat">
                                <i class="fas fa-users"></i>
                                <span>Viewer: <span class="stat-value">124</span></span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-calendar"></i>
                                <span>Date: <span class="stat-value">Mar 15, 2025</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lesson-actions">
                        <button class="btn btn-outline edit-btn">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-success preview-btn">
                            <i class="fas fa-eye"></i>
                            Preview
                        </button>
                        <button class="btn btn-danger delete-btn">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <div class="lesson-item" data-status="drafts">
                    <div class="lesson-header">
                        <div>
                            <h3 class="lesson-title">Math: Fractions & Ratios</h3>
                            <p class="lesson-subject">Subject: Math Fundamentals</p>
                        </div>
                        <span class="status-badge status-draft">Draft</span>
                    </div>
                    <div class="lesson-details">
                        <div class="lesson-stats">
                            <div class="stat">
                                <i class="fas fa-users"></i>
                                <span>Viewer: <span class="stat-value">-</span></span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-calendar"></i>
                                <span>Date: <span class="stat-value date-value">-</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lesson-actions">
                        <button class="btn btn-primary publish-btn">
                            <i class="fas fa-paper-plane"></i>
                            Publish
                        </button>
                        <button class="btn btn-outline edit-btn">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-success preview-btn">
                            <i class="fas fa-eye"></i>
                            Preview
                        </button>
                        <button class="btn btn-danger delete-btn">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>

                <div class="lesson-item" data-status="published">
                    <div class="lesson-header">
                        <div>
                            <h3 class="lesson-title">English Grammar Test</h3>
                            <p class="lesson-subject">Subject: Grammar Practice</p>
                        </div>
                        <span class="status-badge status-published">Published</span>
                    </div>
                    <div class="lesson-details">
                        <div class="lesson-stats">
                            <div class="stat">
                                <i class="fas fa-users"></i>
                                <span>Viewer: <span class="stat-value">228</span></span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-calendar"></i>
                                <span>Date: <span class="stat-value">Mar 18, 2025</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="lesson-actions">
                        <button class="btn btn-outline edit-btn">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-success preview-btn">
                            <i class="fas fa-eye"></i>
                            Preview
                        </button>
                        <button class="btn btn-danger delete-btn">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
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
                                    <em>Grow with us, Glow your future </em>
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

    <script src="../admin_php/JS/lesson.js"></script>
</body>
</html>