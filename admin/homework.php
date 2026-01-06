<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'homework';

// 获取学生数据
$students = [];
$student_stats = [
    'total' => 0,
    'avg_score' => 0,
    'completed' => 0,
    'pending' => 0
];

$sql = "SELECT u.user_id, u.full_name, sp.average_score, sp.last_active, sp.status 
        FROM users u 
        JOIN student_profiles sp ON u.user_id = sp.user_id 
        WHERE u.role = 'student'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
        $student_stats['total']++;
        $student_stats['avg_score'] += $row['average_score'];
    }
}

if ($student_stats['total'] > 0) {
    $student_stats['avg_score'] = round($student_stats['avg_score'] / $student_stats['total'], 1);
}

// 获取作业数据
$homeworks = [];
$hw_sql = "SELECT * FROM homework WHERE assigned_by = ? ORDER BY created_at DESC";
$hw_stmt = $conn->prepare($hw_sql);
$teacher_id = 2;
$hw_stmt->bind_param("i", $teacher_id);
$hw_stmt->execute();
$hw_result = $hw_stmt->get_result();

if ($hw_result->num_rows > 0) {
    while($row = $hw_result->fetch_assoc()) {
        $homeworks[] = $row;
    }
}

// 处理保存反馈
if (isset($_POST['save_feedback'])) {
    $submission_id = $_POST['submission_id'];
    $feedback = $_POST['feedback'];
    
    $update_sql = "UPDATE homework_submissions SET teacher_feedback = ?, status = 'graded' WHERE submission_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $feedback, $submission_id);
    
    if ($update_stmt->execute()) {
        header("Location: homework.php?feedback_saved=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Homework Review</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/homework.css">
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
                </li>
                <li class="menu-item">
                    <a href="lessons.php" class="menu-link">
                        <i class="fas fa-book-open"></i>
                        <span>Manage Lessons</span>
                    </a>
                </li>
                 <li class="menu-item active">
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
                <li class="menu-item">
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
                        <input type="text" placeholder="Search students...">
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

            <h1 class="page-title">Manage Homework</h1>
            
            <div class="dashboard">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Students</h2>
                    </div>
                    
                    <div class="student-list">
                        <div class="student-item active" data-student="1">
                            <h3 class="student-name">Alice Johnson</h3>
                            <div class="student-details">
                                <div class="student-stats">
                                    <div class="stat">
                                        <span>Score:</span>
                                        <span class="stat-value">85%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Correct:</span>
                                        <span class="stat-value">17/20</span>
                                    </div>
                                    <div class="stat">
                                        <span>Errors:</span>
                                        <span class="stat-value">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="student-item" data-student="2">
                            <h3 class="student-name">Bob Smith</h3>
                            <div class="student-details">
                                <div class="student-stats">
                                    <div class="stat">
                                        <span>Score:</span>
                                        <span class="stat-value">70%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Correct:</span>
                                        <span class="stat-value">14/20</span>
                                    </div>
                                    <div class="stat">
                                        <span>Errors:</span>
                                        <span class="stat-value">6</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="student-item" data-student="3">
                            <h3 class="student-name">Carol Williams</h3>
                            <div class="student-details">
                                <div class="student-stats">
                                    <div class="stat">
                                        <span>Score:</span>
                                        <span class="stat-value">95%</span>
                                    </div>
                                    <div class="stat">
                                        <span>Correct:</span>
                                        <span class="stat-value">19/20</span>
                                    </div>
                                    <div class="stat">
                                        <span>Errors:</span>
                                        <span class="stat-value">1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value" id="totalStudents">24</div>
                            <div class="stat-label">Total Students</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="avgScore">78%</div>
                            <div class="stat-label">Average Score</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="completed">18</div>
                            <div class="stat-label">Completed</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value" id="pending">6</div>
                            <div class="stat-label">Pending</div>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Question Review - Alice Johnson</h2>
                        <div class="card-actions">
                            <button class="btn btn-primary" id="saveFeedbackBtn">
                                <i class="fas fa-save"></i>
                                Save Feedback
                            </button>
                        </div>
                    </div>
                    
                    <div class="question-review">
                        <div class="question-item incorrect">
                            <div class="question-header">
                                <h3 class="question-title">Question 1: Solve for x: 2x + 5 = 15</h3>
                                <span class="question-status status-incorrect">Incorrect</span>
                            </div>
                            <div class="question-content">
                                <p>Find the value of x in the equation: 2x + 5 = 15</p>
                            </div>
                            <div class="answer-section">
                                <div class="answer-box correct-answer">
                                    <h4>Correct Answer</h4>
                                    <p>x = 5</p>
                                    <p><strong>Explanation:</strong> Subtract 5 from both sides: 2x = 10, then divide by 2: x = 5</p>
                                </div>
                                <div class="answer-box student-answer">
                                    <h4>Student's Answer</h4>
                                    <p>x = 10</p>
                                    <p><strong>Error Analysis:</strong> Student forgot to divide by 2 after subtracting 5 from both sides.</p>
                                </div>
                            </div>
                            <div class="feedback-section">
                                <h4>Teacher Feedback</h4>
                                <textarea class="feedback-input" placeholder="Enter feedback for the student...">Remember to perform the same operation on both sides of the equation. After subtracting 5, you need to divide by 2 to isolate x.</textarea>
                            </div>
                        </div>
                    
                        <div class="question-item incorrect">
                            <div class="question-header">
                                <h3 class="question-title">Question 7: Calculate the area of a circle with radius 7cm</h3>
                                <span class="question-status status-incorrect">Incorrect</span>
                            </div>
                            <div class="question-content">
                                <p>Use π = 3.14 to calculate the area of a circle with radius 7cm.</p>
                            </div>
                            <div class="answer-section">
                                <div class="answer-box correct-answer">
                                    <h4>Correct Answer</h4>
                                    <p>153.86 cm²</p>
                                    <p><strong>Explanation:</strong> Area = πr² = 3.14 × 7² = 3.14 × 49 = 153.86</p>
                                </div>
                                <div class="answer-box student-answer">
                                    <h4>Student's Answer</h4>
                                    <p>43.96 cm²</p>
                                    <p><strong>Error Analysis:</strong> Student calculated the circumference (2πr) instead of the area (πr²).</p>
                                </div>
                            </div>
                            <div class="feedback-section">
                                <h4>Teacher Feedback</h4>
                                <textarea class="feedback-input" placeholder="Enter feedback for the student...">Remember the formula for area of a circle is πr², not 2πr which is the circumference formula.</textarea>
                            </div>
                        </div>
                        
                        <div class="question-item correct">
                            <div class="question-header">
                                <h3 class="question-title">Question 12: Simplify 3(x + 4) - 2x</h3>
                                <span class="question-status status-correct">Correct</span>
                            </div>
                            <div class="question-content">
                                <p>Simplify the expression: 3(x + 4) - 2x</p>
                            </div>
                            <div class="answer-section">
                                <div class="answer-box correct-answer">
                                    <h4>Correct Answer</h4>
                                    <p>x + 12</p>
                                    <p><strong>Explanation:</strong> Distribute 3: 3x + 12 - 2x = x + 12</p>
                                </div>
                                <div class="answer-box student-answer">
                                    <h4>Student's Answer</h4>
                                    <p>x + 12</p>
                                    <p><strong>Analysis:</strong> Student correctly distributed and combined like terms.</p>
                                </div>
                            </div>
                            <div class="feedback-section">
                                <h4>Teacher Feedback</h4>
                                <textarea class="feedback-input" placeholder="Enter feedback for the student...">Well done! You correctly applied the distributive property and combined like terms.</textarea>
                            </div>
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

    <script src="../admin_php/JS/homework.js"></script>
</body>
</html>