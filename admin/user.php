<?php
include('db_connect.php');

// 临时禁用认证
// include('auth_functions.php');
// checkAdminAuth();

// 获取当前页面用于菜单高亮
$current_page = 'users';

// 临时设置教师ID
$teacher_id = 2;

// 获取学生数据
$students = [];
$student_stats = [
    'total' => 0,
    'active' => 0,
    'at_risk' => 0,
    'top_performers' => 0
];

$sql = "SELECT u.user_id, u.full_name, u.email, u.last_active, 
               sp.average_score, sp.status as student_status 
        FROM users u 
        JOIN student_profiles sp ON u.user_id = sp.user_id 
        WHERE u.role = 'student' 
        ORDER BY u.full_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
        $student_stats['total']++;
        
        if ($row['student_status'] == 'Active') {
            $student_stats['active']++;
            if ($row['average_score'] >= 90) {
                $student_stats['top_performers']++;
            }
        } else if ($row['student_status'] == 'At Risk') {
            $student_stats['at_risk']++;
        }
    }
}

// 处理搜索和过滤
$filtered_students = $students;
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

if (!empty($search_term)) {
    $filtered_students = array_filter($students, function($student) use ($search_term) {
        return stripos($student['full_name'], $search_term) !== false;
    });
}

if ($status_filter !== 'all') {
    $filtered_students = array_filter($filtered_students, function($student) use ($status_filter) {
        return $student['student_status'] == $status_filter;
    });
}

// 处理添加/编辑学生
if (isset($_POST['save_student'])) {
    $student_name = $_POST['student_name'];
    $student_score = $_POST['student_score'];
    $student_last_active = $_POST['student_last_active'];
    $student_status = $_POST['student_status'];
    $student_id = isset($_POST['student_id']) ? $_POST['student_id'] : null;
    
    if ($student_id) {
        // 编辑现有学生
        $update_sql = "UPDATE users u JOIN student_profiles sp ON u.user_id = sp.user_id 
                      SET u.full_name = ?, sp.average_score = ?, u.last_active = ?, sp.status = ? 
                      WHERE u.user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sissi", $student_name, $student_score, $student_last_active, $student_status, $student_id);
        
        if ($update_stmt->execute()) {
            header("Location: user.php?student_updated=1");
            exit();
        } else {
            header("Location: user.php?error=update_failed&message=" . urlencode($conn->error));
            exit();
        }
    } else {
        // 添加新学生
        // 首先在users表中创建用户
        $email = strtolower(str_replace(' ', '.', $student_name)) . '@crowedu.com';
        $password = password_hash('temp123', PASSWORD_DEFAULT);
        
        // 修复：使用正确的列名 'password' 而不是 'password_hash'
        $insert_user_sql = "INSERT INTO users (full_name, email, password, role, last_active) 
                           VALUES (?, ?, ?, 'student', ?)";
        $insert_user_stmt = $conn->prepare($insert_user_sql);
        $insert_user_stmt->bind_param("ssss", $student_name, $email, $password, $student_last_active);
        
        if ($insert_user_stmt->execute()) {
            $new_user_id = $conn->insert_id;
            
            // 然后在student_profiles表中创建记录
            $insert_profile_sql = "INSERT INTO student_profiles (user_id, average_score, status) 
                                  VALUES (?, ?, ?)";
            $insert_profile_stmt = $conn->prepare($insert_profile_sql);
            $insert_profile_stmt->bind_param("iis", $new_user_id, $student_score, $student_status);
            
            if ($insert_profile_stmt->execute()) {
                header("Location: user.php?student_added=1");
                exit();
            } else {
                // 如果student_profiles插入失败，回滚users表的插入
                $delete_user_sql = "DELETE FROM users WHERE user_id = ?";
                $delete_user_stmt = $conn->prepare($delete_user_sql);
                $delete_user_stmt->bind_param("i", $new_user_id);
                $delete_user_stmt->execute();
                
                header("Location: user.php?error=add_failed&message=" . urlencode($conn->error));
                exit();
            }
        } else {
            header("Location: user.php?error=add_failed&message=" . urlencode($conn->error));
            exit();
        }
    }
}

// 处理删除学生
if (isset($_POST['delete_student'])) {
    $user_id = $_POST['user_id'];
    
    error_log("DELETE ATTEMPT: User ID $user_id");
    
    // 直接删除用户（由于有ON DELETE CASCADE，相关记录会自动删除）
    $delete_sql = "DELETE FROM users WHERE user_id = ? AND role = 'student'";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $user_id);
    
    if ($delete_stmt->execute()) {
        // 检查是否真的删除了
        $check_sql = "SELECT COUNT(*) as count FROM users WHERE user_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        $row = $check_result->fetch_assoc();
        
        if ($row['count'] == 0) {
            error_log("SUCCESS: User $user_id deleted completely");
            header("Location: user.php?student_deleted=1");
            exit();
        } else {
            error_log("ERROR: User $user_id still exists after delete");
            header("Location: user.php?error=delete_failed");
            exit();
        }
    } else {
        error_log("DELETE ERROR: " . $conn->error);
        header("Location: user.php?error=delete_failed&message=" . urlencode($conn->error));
        exit();
    }
}

// 显示操作结果消息
$success_message = '';
$error_message = '';

if (isset($_GET['student_deleted'])) {
    $success_message = 'Student deleted successfully!';
}
if (isset($_GET['student_added'])) {
    $success_message = 'Student added successfully!';
}
if (isset($_GET['student_updated'])) {
    $success_message = 'Student updated successfully!';
}
if (isset($_GET['error'])) {
    $error_message = 'Operation failed. Please try again.';
    if (isset($_GET['message'])) {
        $error_message .= ' Error: ' . htmlspecialchars($_GET['message']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crow Education - Teacher Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="../admin_php/CSS/user.css">
    <style>
        .alert {
            padding: 12px 16px;
            margin: 16px;
            border-radius: 4px;
            font-weight: 500;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .delete-form {
            display: inline;
        }
        .delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            text-decoration: underline;
            font: inherit;
            padding: 0;
        }
        .delete-btn:hover {
            color: #c82333;
        }
        
        /* Modal Styles */
        .delete-confirm-modal, .student-form-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .delete-confirm-content, .student-form-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-width: 90%;
        }
        
        .student-form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close-form-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .delete-confirm-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .confirm-delete-btn {
            background-color: #dc3545;
            color: white;
        }
        
        .cancel-delete-btn {
            background-color: #6c757d;
            color: white;
        }
        
        .form-error {
            color: #dc3545;
            font-size: 12px;
            display: none;
        }
        
        /* Content sections */
        .dashboard-content, .homework-content, .assessment-content, 
        .calendar-content, .messages-content, .music-content, .settings-content {
            display: none;
        }
        
        .class-container.active, .dashboard-content.active, .homework-content.active,
        .assessment-content.active, .calendar-content.active, .messages-content.active,
        .music-content.active, .settings-content.active {
            display: block;
        }
        
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        
        .content-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        /* Notification panel */
        .notification-panel {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 300px;
            z-index: 100;
        }
        
        .notification-panel.active {
            display: block;
        }
        
        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        /* User menu */
        .user-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 200px;
            z-index: 100;
        }
        
        .user-menu.active {
            display: block;
        }
        
        .user-menu-item {
            padding: 10px;
            cursor: pointer;
        }
        
        .user-menu-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- 删除确认模态窗口 -->
    <div class="delete-confirm-modal" id="deleteConfirmModal">
        <div class="delete-confirm-content">
            <h3>Confirm Delete</h3>
            <p>Are you sure you want to delete <strong id="studentNameToDelete"></strong>? This action cannot be undone.</p>
            <div class="delete-confirm-buttons">
                <button class="confirm-delete-btn" id="confirmDeleteBtn">Yes, Delete</button>
                <button class="cancel-delete-btn" id="cancelDeleteBtn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- 学生表单模态窗口 -->
    <div class="student-form-modal" id="studentFormModal">
        <div class="student-form-content">
            <div class="student-form-header">
                <h3 id="studentFormTitle">Add Student</h3>
                <button class="close-form-btn" id="closeFormBtn">&times;</button>
            </div>
            <form id="studentForm" method="POST">
                <input type="hidden" id="studentId" name="student_id">
                <div class="form-group">
                    <label for="studentName">Student Name</label>
                    <input type="text" id="studentName" name="student_name" class="form-control" placeholder="Enter student name" required>
                    <div class="form-error" id="nameError">Please enter a valid name</div>
                </div>
                <div class="form-group">
                    <label for="studentScore">Average Score (%)</label>
                    <input type="number" id="studentScore" name="student_score" class="form-control" min="0" max="100" placeholder="Enter average score" required>
                    <div class="form-error" id="scoreError">Please enter a valid score between 0 and 100</div>
                </div>
                <div class="form-group">
                    <label for="studentLastActive">Last Active Date</label>
                    <input type="text" id="studentLastActive" name="student_last_active" class="form-control" placeholder="e.g. Nov 5" required>
                    <div class="form-error" id="activeError">Please enter a valid date</div>
                </div>
                <div class="form-group">
                    <label for="studentStatus">Status</label>
                    <select id="studentStatus" name="student_status" class="form-control form-select">
                        <option value="Active">Active</option>
                        <option value="At Risk">At Risk</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelFormBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="save_student" id="submitFormBtn">Save Student</button>
                </div>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <i class="fas fa-crow"></i>
                <h1>Crow Education</h1>
            </div>
            <ul class="menu">
                <li class="menu-item">
                    <a href="dashboard.php" class="menu-link" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item active">
                    <a href="user.php" class="menu-link" data-page="classes">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                        <span class="menu-badge" id="classesBadge"><?php echo $student_stats['total']; ?></span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="lessons.php" class="menu-link" data-page="lessons">
                        <i class="fas fa-book-open"></i>
                        <span>Manage Lessons</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="homework.php" class="menu-link" data-page="homework">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Homework</span>
                        <span class="menu-badge">12</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="assessments.php" class="menu-link" data-page="assessment">
                        <i class="fas fa-file-alt"></i>
                        <span>Manage Assessments</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="messages.php" class="menu-link" data-page="messages">
                        <i class="fas fa-comments"></i>
                        <span>Manage Messages</span>
                        <span class="menu-badge">3</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="music.php" class="menu-link" data-page="music">
                        <i class="fas fa-music"></i>
                        <span>Manage Music</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php" class="menu-link" data-page="settings">
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
                <h2 id="pageTitle">Teacher Dashboard</h2>
                <div class="header-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="globalSearch" placeholder="Search students...">
                    </div>
                    <div class="user-info">
                        <div class="dark-mode-toggle" id="darkModeToggle">
                            <i class="fas fa-moon toggle-icon"></i>
                            <span class="toggle-text">Dark Mode</span>
                        </div>
                        <div class="notification-icon" id="notificationIcon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-count">5</span>
                        </div>
                        <div class="notification-panel" id="notificationPanel">
                            <h3>Notifications</h3>
                            <div class="notification-item">
                                <strong>New Assignment Submitted</strong>
                                <p>Alice Tan submitted Math homework</p>
                                <small>2 minutes ago</small>
                            </div>
                            <div class="notification-item">
                                <strong>Parent Meeting Reminder</strong>
                                <p>Meeting with John Lim's parents tomorrow</p>
                                <small>1 hour ago</small>
                            </div>
                            <div class="notification-item">
                                <strong>System Update</strong>
                                <p>New features available in gradebook</p>
                                <small>3 hours ago</small>
                            </div>
                        </div>
                        <div class="user-avatar" id="userAvatar">YC</div>
                        <div class="user-menu" id="userMenu">
                            <div class="user-menu-item" id="profileBtn">My Profile</div>
                            <div class="user-menu-item" id="settingsBtn">Account Settings</div>
                            <div class="user-menu-item" id="logoutBtn">Logout</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 消息显示 -->
            <?php if (!empty($success_message)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
            <?php endif; ?>

            <div class="dashboard-content" id="dashboardContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Quick Stats</h3>
                        <p>View your teaching statistics and performance metrics</p>
                        <button class="card-btn" id="viewStatsBtn">View Statistics</button>
                    </div>
                    <div class="content-card">
                        <h3>Recent Activities</h3>
                        <p>Check recent student activities and submissions</p>
                        <button class="card-btn" id="viewActivitiesBtn">View Activities</button>
                    </div>
                    <div class="content-card">
                        <h3>Upcoming Events</h3>
                        <p>See your schedule and upcoming important dates</p>
                        <button class="card-btn" id="viewEventsBtn">View Calendar</button>
                    </div>
                </div>
            </div>

            <div class="class-container active" id="classesContent">
                <header class="page-header">
                    <h1>Manage Users</h1>
                    <div class="header-right">
                        <div class="search-filter-container">
                            <div class="class-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" placeholder="Search students" id="studentSearch">
                            </div>
                            <div class="filter-dropdown">
                                <select id="status-filter">
                                    <option>All status</option>
                                    <option>Active</option>
                                    <option>At Risk</option>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>

                        <button class="add-student-btn" id="addStudentBtn">
                            <i class="fa-solid fa-plus"></i>
                            Add Student
                        </button>
                    </div>
                </header>

                <main class="main-content">
                    <div class="stats-container">
                        <div class="stat-card stat-card--total">
                            <div class="stat-value" id="totalStudents"><?php echo $student_stats['total']; ?></div>
                            <div class="stat-label">
                                <i class="fa-solid fa-users"></i>
                                Total Students
                            </div>
                        </div>

                        <div class="stat-card stat-card--active">
                            <div class="stat-value" id="activeStudents"><?php echo $student_stats['active']; ?></div>
                            <div class="stat-label">
                                <i class="fa-solid fa-chart-line"></i>
                                Active Students
                            </div>
                            <div class="stat-detail">Based on avg. score &gt; 50% but &lt; 90%</div>
                        </div>

                        <div class="stat-card stat-card--risk">
                            <div class="stat-value" id="riskStudents"><?php echo $student_stats['at_risk']; ?></div>
                            <div class="stat-label">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                At Risk
                            </div>
                            <div class="stat-detail">Based on avg. score &lt; 50%</div>
                        </div>

                        <div class="stat-card stat-card--top">
                            <div class="stat-value" id="topStudents"><?php echo $student_stats['top_performers']; ?></div>
                            <div class="stat-label">
                                <i class="fa-solid fa-star"></i>
                                Top Performers
                            </div>
                            <div class="stat-detail">Based on avg. score &gt; 90% and high task completion</div>
                        </div>
                    </div>

                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Average Score</th>
                                    <th scope="col">Last Active</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody">
                                <?php foreach($filtered_students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['average_score']); ?>%</td>
                                    <td><?php echo htmlspecialchars($student['last_active']); ?></td>
                                    <td class="status-<?php echo strtolower(str_replace(' ', '-', $student['student_status'])); ?>">
                                        <?php echo htmlspecialchars($student['student_status']); ?>
                                    </td>
                                    <td class="actions">
                                        <!-- 编辑按钮 -->
                                        <a href="#" class="action-link edit-btn" 
                                           data-student-id="<?php echo $student['user_id']; ?>"
                                           data-student-name="<?php echo htmlspecialchars($student['full_name']); ?>"
                                           data-student-score="<?php echo htmlspecialchars($student['average_score']); ?>"
                                           data-student-last-active="<?php echo htmlspecialchars($student['last_active']); ?>"
                                           data-student-status="<?php echo htmlspecialchars($student['student_status']); ?>">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                        
                                        <!-- 删除按钮 -->
                                        <a href="#" class="action-link delete-btn" 
                                           data-student-id="<?php echo $student['user_id']; ?>"
                                           data-student-name="<?php echo htmlspecialchars($student['full_name']); ?>">
                                            <i class="fa-solid fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </main>
            </div>

            <div class="homework-content" id="homeworkContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Assign Homework</h3>
                        <p>Create and assign new homework to your classes</p>
                        <button class="card-btn" id="assignHomeworkBtn">Assign Homework</button>
                    </div>
                    <div class="content-card">
                        <h3>View Submissions</h3>
                        <p>Check and grade student homework submissions</p>
                        <button class="card-btn" id="viewSubmissionsBtn">View Submissions</button>
                    </div>
                    <div class="content-card">
                        <h3>Homework Templates</h3>
                        <p>Use and create homework templates for quick assignment</p>
                        <button class="card-btn" id="templatesBtn">Manage Templates</button>
                    </div>
                </div>
            </div>

            <div class="assessment-content" id="assessmentContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Create Assessment</h3>
                        <p>Design tests, quizzes, and exams for your students</p>
                        <button class="card-btn" id="createAssessmentBtn">Create Assessment</button>
                    </div>
                    <div class="content-card">
                        <h3>Grade Assessments</h3>
                        <p>Review and grade completed student assessments</p>
                        <button class="card-btn" id="gradeAssessmentsBtn">Grade Assessments</button>
                    </div>
                    <div class="content-card">
                        <h3>Assessment Results</h3>
                        <p>View detailed results and analytics for assessments</p>
                        <button class="card-btn" id="viewResultsBtn">View Results</button>
                    </div>
                </div>
            </div>

            <div class="calendar-content" id="calendarContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>View Calendar</h3>
                        <p>Check your teaching schedule and important dates</p>
                        <button class="card-btn" id="viewCalendarBtn">Open Calendar</button>
                    </div>
                    <div class="content-card">
                        <h3>Add Event</h3>
                        <p>Schedule new events, meetings, or deadlines</p>
                        <button class="card-btn" id="addEventBtn">Add New Event</button>
                    </div>
                    <div class="content-card">
                        <h3>Class Schedule</h3>
                        <p>Manage your class timetables and schedules</p>
                        <button class="card-btn" id="manageScheduleBtn">Manage Schedule</button>
                    </div>
                </div>
            </div>

            <div class="messages-content" id="messagesContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Inbox</h3>
                        <p>Check messages from students and parents</p>
                        <button class="card-btn" id="openInboxBtn">Open Inbox</button>
                    </div>
                    <div class="content-card">
                        <h3>Compose Message</h3>
                        <p>Send messages to students, parents, or colleagues</p>
                        <button class="card-btn" id="composeMessageBtn">Compose Message</button>
                    </div>
                    <div class="content-card">
                        <h3>Message Templates</h3>
                        <p>Use pre-written templates for common communications</p>
                        <button class="card-btn" id="messageTemplatesBtn">Templates</button>
                    </div>
                </div>
            </div>

            <div class="music-content" id="musicContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Music Library</h3>
                        <p>Access your collection of educational music resources</p>
                        <button class="card-btn" id="musicLibraryBtn">Open Library</button>
                    </div>
                    <div class="content-card">
                        <h3>Create Playlist</h3>
                        <p>Create music playlists for different classroom activities</p>
                        <button class="card-btn" id="createPlaylistBtn">Create Playlist</button>
                    </div>
                    <div class="content-card">
                        <h3>Music Resources</h3>
                        <p>Find and organize music teaching materials</p>
                        <button class="card-btn" id="musicResourcesBtn">Resources</button>
                    </div>
                </div>
            </div>

            <div class="settings-content" id="settingsContent">
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Account Settings</h3>
                        <p>Update your profile information and preferences</p>
                        <button class="card-btn" id="accountSettingsBtn">Account Settings</button>
                    </div>
                    <div class="content-card">
                        <h3>Notification Settings</h3>
                        <p>Configure how and when you receive notifications</p>
                        <button class="card-btn" id="notificationSettingsBtn">Notification Settings</button>
                    </div>
                    <div class="content-card">
                        <h3>Privacy & Security</h3>
                        <p>Manage your privacy settings and security options</p>
                        <button class="card-btn" id="privacySettingsBtn">Privacy Settings</button>
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
                                    <a href="#" class="footer-link" id="birdTeamLink">Bird2 Team</a>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-contact">
                                    Contact us
                                </button>
                                <div class="footer-panel" id="panel-contact">
                                    <a href="mailto:birdbird945@gmail.com" class="footer-link" id="emailLink">birdbird945@gmail.com</a>
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
                            <li><a href="#" class="footer-link" id="classLink">Class</a></li>
                            <li><a href="#" class="footer-link" id="discussionHubLink">Discussion Hub</a></li>
                            <li><a href="#" class="footer-link" id="subjectFinderLink">Subject Finder</a></li>
                            <li><a href="#" class="footer-link" id="knowledgeHubLink">Knowledge Hub</a></li>
                            <li><a href="#" class="footer-link" id="studyMethodLink">Study Method</a></li>
                        </ul>
                    </div>

                    <div class="footer-column">
                        <h4>[HELP & SUPPORT]</h4>
                        <ul class="normal-list">
                            <li><a href="#" class="footer-link" id="feedbackLink">Feedback</a></li>
                        </ul>
                    </div>
                </div>
                <p class="last">© 2025 Crow Education EduTeach Platform · Teacher Dashboard</p>
            </footer>
        </div>
    </div>

    <script src="../admin_php/JS/user.js"></script>
    <script>
        // JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const deleteModal = document.getElementById('deleteConfirmModal');
            const studentFormModal = document.getElementById('studentFormModal');
            const studentForm = document.getElementById('studentForm');
            const studentFormTitle = document.getElementById('studentFormTitle');
            const studentIdInput = document.getElementById('studentId');
            const studentNameInput = document.getElementById('studentName');
            const studentScoreInput = document.getElementById('studentScore');
            const studentLastActiveInput = document.getElementById('studentLastActive');
            const studentStatusInput = document.getElementById('studentStatus');
            const studentNameToDelete = document.getElementById('studentNameToDelete');
            
            // Buttons
            const addStudentBtn = document.getElementById('addStudentBtn');
            const closeFormBtn = document.getElementById('closeFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            
            // Navigation
            const menuLinks = document.querySelectorAll('.menu-link[data-page]');
            const contentSections = document.querySelectorAll('.dashboard-content, .class-container, .homework-content, .assessment-content, .calendar-content, .messages-content, .music-content, .settings-content');
            
            // User interface
            const darkModeToggle = document.getElementById('darkModeToggle');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationPanel = document.getElementById('notificationPanel');
            const userAvatar = document.getElementById('userAvatar');
            const userMenu = document.getElementById('userMenu');
            
            // Search and filter
            const studentSearch = document.getElementById('studentSearch');
            const statusFilter = document.getElementById('status-filter');
            const globalSearch = document.getElementById('globalSearch');
            
            // Current student to delete
            let currentStudentToDelete = null;
            
            // Add Student Button
            addStudentBtn.addEventListener('click', function() {
                studentFormTitle.textContent = 'Add Student';
                studentForm.reset();
                studentIdInput.value = '';
                studentFormModal.style.display = 'block';
            });
            
            // Edit Student Buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const studentId = this.getAttribute('data-student-id');
                    const studentName = this.getAttribute('data-student-name');
                    const studentScore = this.getAttribute('data-student-score');
                    const studentLastActive = this.getAttribute('data-student-last-active');
                    const studentStatus = this.getAttribute('data-student-status');
                    
                    studentFormTitle.textContent = 'Edit Student';
                    studentIdInput.value = studentId;
                    studentNameInput.value = studentName;
                    studentScoreInput.value = studentScore;
                    studentLastActiveInput.value = studentLastActive;
                    studentStatusInput.value = studentStatus;
                    
                    studentFormModal.style.display = 'block';
                });
            });
            
            // Delete Student Buttons
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const studentId = this.getAttribute('data-student-id');
                    const studentName = this.getAttribute('data-student-name');
                    
                    currentStudentToDelete = studentId;
                    studentNameToDelete.textContent = studentName;
                    deleteModal.style.display = 'block';
                });
            });
            
            // Close Form Modal
            closeFormBtn.addEventListener('click', function() {
                studentFormModal.style.display = 'none';
            });
            
            cancelFormBtn.addEventListener('click', function() {
                studentFormModal.style.display = 'none';
            });
            
            // Delete Modal Actions
            confirmDeleteBtn.addEventListener('click', function() {
                if (currentStudentToDelete) {
                    // Create a form to submit the delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_id';
                    input.value = currentStudentToDelete;
                    
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'delete_student';
                    deleteInput.value = '1';
                    
                    form.appendChild(input);
                    form.appendChild(deleteInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
            
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.style.display = 'none';
                currentStudentToDelete = null;
            });
            
            // Navigation
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetPage = this.getAttribute('data-page');
                    
                    // Update active menu item
                    menuLinks.forEach(l => l.parentElement.classList.remove('active'));
                    this.parentElement.classList.add('active');
                    
                    // Show target content
                    contentSections.forEach(section => {
                        section.classList.remove('active');
                    });
                    
                    document.getElementById(targetPage + 'Content').classList.add('active');
                    document.getElementById('pageTitle').textContent = this.querySelector('span').textContent + ' - Teacher Dashboard';
                });
            });
            
            // Dark Mode Toggle
            darkModeToggle.addEventListener('click', function() {
                document.body.classList.toggle('dark-mode');
                const icon = this.querySelector('.toggle-icon');
                const text = this.querySelector('.toggle-text');
                
                if (document.body.classList.contains('dark-mode')) {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                    text.textContent = 'Light Mode';
                } else {
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                    text.textContent = 'Dark Mode';
                }
            });
            
            // Notification Panel
            notificationIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('active');
                userMenu.classList.remove('active');
            });
            
            // User Menu
            userAvatar.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('active');
                notificationPanel.classList.remove('active');
            });
            
            // Close menus when clicking outside
            document.addEventListener('click', function() {
                notificationPanel.classList.remove('active');
                userMenu.classList.remove('active');
            });
            
            // Form Validation
            studentForm.addEventListener('submit', function(e) {
                let valid = true;
                
                // Name validation
                if (studentNameInput.value.trim() === '') {
                    document.getElementById('nameError').style.display = 'block';
                    valid = false;
                } else {
                    document.getElementById('nameError').style.display = 'none';
                }
                
                // Score validation
                const score = parseInt(studentScoreInput.value);
                if (isNaN(score) || score < 0 || score > 100) {
                    document.getElementById('scoreError').style.display = 'block';
                    valid = false;
                } else {
                    document.getElementById('scoreError').style.display = 'none';
                }
                
                // Last active validation
                if (studentLastActiveInput.value.trim() === '') {
                    document.getElementById('activeError').style.display = 'block';
                    valid = false;
                } else {
                    document.getElementById('activeError').style.display = 'none';
                }
                
                if (!valid) {
                    e.preventDefault();
                }
            });
            
            // Search functionality
            studentSearch.addEventListener('input', function() {
                filterStudents();
            });
            
            statusFilter.addEventListener('change', function() {
                filterStudents();
            });
            
            function filterStudents() {
                const searchTerm = studentSearch.value.toLowerCase();
                const statusValue = statusFilter.value;
                
                document.querySelectorAll('#studentsTableBody tr').forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    const status = row.cells[3].textContent;
                    
                    const matchesSearch = name.includes(searchTerm);
                    const matchesStatus = statusValue === 'All status' || status === statusValue;
                    
                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Global search
            globalSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                document.querySelectorAll('#studentsTableBody tr').forEach(row => {
                    const name = row.cells[0].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
            // Footer toggle functionality
            document.querySelectorAll('.footer-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    
                    const panel = document.getElementById(this.getAttribute('aria-controls'));
                    if (expanded) {
                        panel.style.display = 'none';
                    } else {
                        panel.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>