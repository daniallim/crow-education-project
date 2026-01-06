<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'assessments';

// 获取所有评估数据
$assessments = [];
$assessment_stats = [
    'total' => 0,
    'average_score' => 0,
    'highest_score' => 0,
    'improvement' => 0
];

$sql = "SELECT * FROM assessments WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$teacher_id = 2; // 假设当前教师ID为2
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $assessments[] = $row;
    }
}

// 计算统计数据
if (!empty($assessments)) {
    $total_score = 0;
    $count = 0;
    $highest = 0;
    
    foreach($assessments as $assessment) {
        $total_score += $assessment['average_score'];
        $count++;
        if ($assessment['highest_score'] > $highest) {
            $highest = $assessment['highest_score'];
        }
    }
    
    $assessment_stats['total'] = count($assessments);
    $assessment_stats['average_score'] = $count > 0 ? round($total_score / $count, 1) : 0;
    $assessment_stats['highest_score'] = $highest;
    $assessment_stats['improvement'] = 5.2; // 模拟数据
}

// 处理搜索
$search_results = [];
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = "%" . $_GET['search'] . "%";
    $search_sql = "SELECT * FROM assessments WHERE (title LIKE ? OR subject LIKE ? OR description LIKE ?) AND created_by = ?";
    $search_stmt = $conn->prepare($search_sql);
    $search_stmt->bind_param("sssi", $search_term, $search_term, $search_term, $teacher_id);
    $search_stmt->execute();
    $search_result = $search_stmt->get_result();
    
    while($row = $search_result->fetch_assoc()) {
        $search_results[] = $row;
    }
}

// 处理删除评估
if (isset($_POST['delete_assessment'])) {
    $assessment_id = $_POST['assessment_id'];
    $delete_sql = "DELETE FROM assessments WHERE assessment_id = ? AND created_by = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $assessment_id, $teacher_id);
    
    if ($delete_stmt->execute()) {
        header("Location: assessments.php?delete_success=1");
        exit();
    }
}

// 处理创建新评估
if (isset($_POST['create_assessment'])) {
    $title = $_POST['assessment_title'];
    $subject = $_POST['assessment_subject'];
    $type = $_POST['assessment_type'];
    $date = $_POST['assessment_date'];
    $duration = $_POST['assessment_duration'];
    $description = $_POST['assessment_description'];
    
    $insert_sql = "INSERT INTO assessments (title, description, subject, assessment_type, duration_minutes, assessment_date, created_by, status) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'draft')";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssssisi", $title, $description, $subject, $type, $duration, $date, $teacher_id);
    
    if ($insert_stmt->execute()) {
        header("Location: assessments.php?create_success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Assessment Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../admin_php/CSS/assessments.css">
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
                        <input type="text" id="searchInput" placeholder="Search assessments...">
                        <div class="search-results" id="searchResults"></div>
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

            <h1 class="page-title">Manage Assessments</h1>
            
            <div class="dashboard">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Student Performance Overview</h2>
                        <div class="card-actions">
                            <button class="btn btn-primary" id="exportChartBtn">
                                <i class="fas fa-download"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <p>Average Assessment Scores (in %)</p>
                    
                    <div class="chart-controls">
                        <button class="chart-control-btn active" data-chart="subject">By Subject</button>
                        <button class="chart-control-btn" data-chart="time">Over Time</button>
                    </div>
                    
                    <div class="chart-container">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value">84.5%</div>
                            <div class="stat-label">Overall Average</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">92%</div>
                            <div class="stat-label">Highest Subject</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">+5.2%</div>
                            <div class="stat-label">Improvement</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value">28</div>
                            <div class="stat-label">Assessments</div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Recent Assessments</h2>
                        <div class="card-actions">
                            <button class="btn btn-primary" id="newAssessmentBtn">
                                <i class="fas fa-plus"></i>
                                New Assessment
                            </button>
                        </div>
                    </div>
                    
                    <div class="assessment-list" id="assessmentList">
                        <div class="assessment-item" data-title="Task - Mathematics" data-topic="Assessment Type: Written Exam" data-viewer="28" data-avg="82" data-high="98" data-low="65">
                            <h3 class="assessment-title">Task - Mathematics</h3>
                            <p class="assessment-topic">Assessment Type: Written Exam</p>
                            <div class="assessment-details">
                                <div class="assessment-stats">
                                    <div class="stat">
                                        <span>Viewer:</span>
                                        <span class="stat-value">28</span>
                                    </div>
                                    <div class="stat">
                                        <span>Avg Score:</span>
                                        <span class="stat-value">82%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Highest:</span>
                                        <span class="stat-value">98%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Lowest:</span>
                                        <span class="stat-value">65%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="assessment-actions">
                                <button class="btn btn-primary results-btn">View Results</button>
                                <button class="btn btn-outline edit-btn">Edit</button>
                                <button class="btn btn-danger delete-btn">Delete</button>
                            </div>
                        </div>
                        
                        <div class="assessment-item" data-title="Science Lab Report" data-topic="Assessment Type: Practical" data-viewer="32" data-avg="91" data-high="100" data-low="78">
                            <h3 class="assessment-title">Science Lab Report</h3>
                            <p class="assessment-topic">Assessment Type: Practical</p>
                            <div class="assessment-details">
                                <div class="assessment-stats">
                                    <div class="stat">
                                        <span>Viewer:</span>
                                        <span class="stat-value">32</span>
                                    </div>
                                    <div class="stat">
                                        <span>Avg Score:</span>
                                        <span class="stat-value">91%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Highest:</span>
                                        <span class="stat-value">100%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Lowest:</span>
                                        <span class="stat-value">78%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="assessment-actions">
                                <button class="btn btn-primary results-btn">View Results</button>
                                <button class="btn btn-outline edit-btn">Edit</button>
                                <button class="btn btn-danger delete-btn">Delete</button>
                            </div>
                        </div>
                        
                        <div class="assessment-item" data-title="English Essay Writing" data-topic="Assessment Type: Essay" data-viewer="24" data-avg="79" data-high="95" data-low="62">
                            <h3 class="assessment-title">English Essay Writing</h3>
                            <p class="assessment-topic">Assessment Type: Essay</p>
                            <div class="assessment-details">
                                <div class="assessment-stats">
                                    <div class="stat">
                                        <span>Viewer:</span>
                                        <span class="stat-value">24</span>
                                    </div>
                                    <div class="stat">
                                        <span>Avg Score:</span>
                                        <span class="stat-value">79%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Highest:</span>
                                        <span class="stat-value">95%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Lowest:</span>
                                        <span class="stat-value">62%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="assessment-actions">
                                <button class="btn btn-primary results-btn">View Results</button>
                                <button class="btn btn-outline edit-btn">Edit</button>
                                <button class="btn btn-danger delete-btn">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal" id="newAssessmentModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Create New Assessment</h2>
                        <button class="close-btn" id="closeNewAssessmentModal">&times;</button>
                    </div>
                    <form id="newAssessmentForm">
                        <div class="form-group">
                            <label class="form-label" for="assessmentTitle">Assessment Title</label>
                            <input type="text" class="form-control" id="assessmentTitle" placeholder="Enter assessment title" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="assessmentSubject">Subject</label>
                            <select class="form-control form-select" id="assessmentSubject" required>
                                <option value="">Select a subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Geography">Geography</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="assessmentType">Assessment Type</label>
                            <select class="form-control form-select" id="assessmentType" required>
                                <option value="">Select assessment type</option>
                                <option value="Written Exam">Written Exam</option>
                                <option value="Practical">Practical</option>
                                <option value="Essay">Essay</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Project">Project</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="assessmentDate">Assessment Date</label>
                                    <input type="date" class="form-control" id="assessmentDate" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label" for="assessmentDuration">Duration (minutes)</label>
                                    <input type="number" class="form-control" id="assessmentDuration" min="1" placeholder="e.g., 60" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="assessmentDescription">Description</label>
                            <textarea class="form-control form-textarea" id="assessmentDescription" placeholder="Enter assessment description"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline" id="cancelNewAssessment">Cancel</button>
                            <button type="submit" class="btn btn-primary">Create Assessment</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal" id="viewResultsModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="resultsModalTitle">Assessment Results</h2>
                        <button class="close-btn" id="closeResultsModal">&times;</button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Student Performance</label>
                        <div class="chart-container">
                            <canvas id="resultsChart"></canvas>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Detailed Results</label>
                        <div class="table-responsive">
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Score</th>
                                        <th>Grade</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="exportResultsBtn">
                            <i class="fas fa-download"></i>
                            Export Results
                        </button>
                        <button class="btn btn-outline" id="closeResultsModalBtn">Close</button>
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

<script src="../admin_php/JS/assessments.js"></script>

</body>
</html>