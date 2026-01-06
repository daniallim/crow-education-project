<?php
include('db_connect.php');

// 获取当前页面用于菜单高亮
$current_page = 'music';

// 获取播放列表
$playlists = [];
$sql = "SELECT * FROM playlists WHERE created_by = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$teacher_id = 2;
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $playlists[] = $row;
    }
}

// 获取音乐曲目
$tracks = [];
$current_playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;

if ($current_playlist_id) {
    $track_sql = "SELECT m.*, pt.track_order 
                  FROM music m 
                  JOIN playlist_tracks pt ON m.music_id = pt.music_id 
                  WHERE pt.playlist_id = ? 
                  ORDER BY pt.track_order ASC";
    $track_stmt = $conn->prepare($track_sql);
    $track_stmt->bind_param("i", $current_playlist_id);
    $track_stmt->execute();
    $track_result = $track_stmt->get_result();
} else {
    $track_sql = "SELECT * FROM music WHERE uploaded_by = ? ORDER BY uploaded_at DESC";
    $track_stmt = $conn->prepare($track_sql);
    $track_stmt->bind_param("i", $teacher_id);
    $track_stmt->execute();
    $track_result = $track_stmt->get_result();
}

if ($track_result->num_rows > 0) {
    while($row = $track_result->fetch_assoc()) {
        $tracks[] = $row;
    }
}

// 处理文件上传
if (isset($_POST['upload_music'])) {
    if (isset($_FILES['music_files']) && !empty($_FILES['music_files']['name'][0])) {
        $upload_dir = "../uploads/music/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $files = $_FILES['music_files'];
        $uploaded_tracks = [];
        
        for ($i = 0; $i < count($files['name']); $i++) {
            $file_name = time() . '_' . basename($files['name'][$i]);
            $file_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($files['tmp_name'][$i], $file_path)) {
                // 获取音频文件信息
                $file_info = pathinfo($files['name'][$i]);
                $title = $file_info['filename'];
                $file_size = $files['size'][$i];
                
                // 插入到数据库
                $insert_sql = "INSERT INTO music (title, artist, file_path, file_size, uploaded_by, category) 
                               VALUES (?, 'Unknown', ?, ?, ?, 'focus')";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ssii", $title, $file_path, $file_size, $teacher_id);
                
                if ($insert_stmt->execute()) {
                    $uploaded_tracks[] = $title;
                }
            }
        }
        
        header("Location: music.php?upload_success=1&count=" . count($uploaded_tracks));
        exit();
    }
}

// 处理创建播放列表
if (isset($_POST['create_playlist'])) {
    $playlist_name = $_POST['playlist_name'];
    $description = $_POST['playlist_description'];
    
    $insert_sql = "INSERT INTO playlists (playlist_name, description, created_by) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssi", $playlist_name, $description, $teacher_id);
    
    if ($insert_stmt->execute()) {
        header("Location: music.php?playlist_created=1");
        exit();
    }
}

// 处理添加到播放列表
if (isset($_POST['add_to_playlist'])) {
    $music_id = $_POST['music_id'];
    $playlist_id = $_POST['playlist_id'];
    
    // 获取当前播放列表中的最大顺序
    $order_sql = "SELECT MAX(track_order) as max_order FROM playlist_tracks WHERE playlist_id = ?";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("i", $playlist_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $max_order = 0;
    
    if ($order_row = $order_result->fetch_assoc()) {
        $max_order = $order_row['max_order'] + 1;
    }
    
    $insert_sql = "INSERT INTO playlist_tracks (playlist_id, music_id, track_order) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iii", $playlist_id, $music_id, $max_order);
    
    if ($insert_stmt->execute()) {
        header("Location: music.php?added_to_playlist=1");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../admin_php/CSS/music.css">
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
                    <a href="dashboard.php" class="menu-link" data-page="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="user.php" class="menu-link" data-page="classes">
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
                        <input type="text" id="globalSearch" placeholder="Search students, assignments...">
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

            <div id="content-switcher">
                <div id="dashboard-page" class="page-content"
                    style="display: flex; flex: 1; align-items: center; justify-content: center;">
                    <div class="dashboard-content">
                        </div>
                </div>

                <div id="classes-page" class="page-content">
                    <div class="placeholder-card">
                       
                    </div>
                </div>
                 <li class="menu-item">
                    <a href="#" class="menu-link">
                        <i class="fas fa-book-open"></i>
                    
                    </a>
                </li>
                <div id="homework-page" class="page-content">
                    <div class="placeholder-card">
                     
                    </div>
                </div>

                <div id="assessment-page" class="page-content">
                    <div class="placeholder-card">
                    
                    </div>
                </div>

                <div id="calendar-page" class="page-content">
                    <div class="placeholder-card">
                        <h2>Manage Calendar</h2>
                        <p>Your personal and school-wide event calendar.</p>
                    </div>
                </div>

                <div id="messages-page" class="page-content">
                    <div class="placeholder-card">
                        <h2>Manage Messages</h2>
                        <p>An inbox for communicating with students, parents, and administrators.</p>
                    </div>
                </div>

                <div id="settings-page" class="page-content">
                    <div class="placeholder-card">
                        <h2>Manage Settings</h2>
                        <p>Your account profile, notification preferences, and other settings.</p>
                    </div>
                </div>

                <div id="music-page" class="page-content">
                    <main class="content">
                        <div class="music-page">
                            <section class="playlist-area">
                                <div class="top-controls">
                                    <div>
                                        <h2 style="margin:0 0 6px 0" id="currentPlaylistName">Focus Playlist</h2>
                                        <div style="color:var(--gray); font-size:13px" id="currentPlaylistDescription">A
                                            collection of
                                            focus-enhancing tracks</div>
                                    </div>

                                    <div style="display:flex; gap:10px; align-items:center;">
                                        <div class="tabs">
                                            <div class="tab active" id="tabAll">All Songs</div>
                                            <div class="tab" id="tabFavs">Favorites</div>
                                        </div>

                                        <div class="playlist-search">
                                            <i class="fas fa-search" style="color:var(--gray)"></i>
                                            <input id="searchInput" placeholder="Search songs, artists..." />
                                        </div>

                                        <button class="control-btn" id="shuffleBtn" title="Shuffle"><i
                                                class="fas fa-random"></i></button>
                                        <button class="control-btn" id="likeAllBtn" title="Like all"><i
                                                class="fas fa-heart"></i></button>
                                        <button class="add-button" id="addBtn">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <table class="tracks-table" aria-hidden="false">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;"></th>
                                                <th>Title</th>
                                                <th>Artist</th>
                                                <th style="text-align:right;padding-right:12px">Duration</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tracksBody">
                                            </tbody>
                                    </table>
                                </div>

                                <div class="upload-area" id="uploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag and drop music files here, or click to upload</p>
                                    <button class="upload-btn" id="uploadBtn">Select Files</button>
                                    <input type="file" id="fileInput" accept="audio/*" multiple>
                                </div>

                                <div class="action-buttons">
                                    <button class="action-btn">Edit Order</button>
                                    <button class="action-btn">Settings</button>
                                    <button class="action-btn">Instructions</button>
                                    <button class="action-btn">Artists...</button>
                                </div>

                                <audio id="audio"></audio>
                            </section>

                            <aside class="library">
                                <h3>Your Playlists</h3>
                                <div id="playlistsContainer">
                                    </div>
                            </aside>
                        </div>
                    </main>

                    <div class="player-bar" id="playerBar">
                        <img src="" alt="cover" class="player-cover" id="playerCover">
                        <div class="player-meta">
                            <div class="title" id="playerTitle">Not playing</div>
                            <div class="artist" id="playerArtist">—</div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:8px; align-items:center; flex:1;">
                            <div class="player-controls">
                                <button class="control-btn" id="prevTrack"><i class="fas fa-backward"></i></button>
                                <button class="control-btn" id="playPauseMain"><i class="fas fa-play"></i></button>
                                <button class="control-btn" id="nextTrack"><i class="fas fa-forward"></i></button>
                                <button class="control-btn" id="loopBtn" title="Loop"><i
                                        class="fas fa-redo"></i></button>
                            </div>
                            <div class="progress-wrap">
                                <div class="progress" id="progressBar">
                                    <div class="progress-fill" id="progressFill"></div>
                                </div>
                                <div class="time-row">
                                    <span id="currentTime">0:00</span>
                                    <span id="totalTime">0:00</span>
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="volume">
                                <i class="fas fa-volume-up" style="color:var(--gray)"></i>
                                <input id="volumeRange" type="range" min="0" max="1" step="0.01" value="0.8">
                            </div>
                            <button class="control-btn" id="queueBtn" title="Queue"><i class="fas fa-list"></i></button>
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
                                        <🌱 To make education easier — for everyone.</em>
                                </div>
                            </li>
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-slogan">
                                    Slogan
                                </button>
                                <div class="footer-panel" id="panel-slogan">
                                    <em>Simplicity in learning. Mastery in life</em>
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
                            <li>
                                <button class="footer-toggle" type="button" aria-expanded="false" aria-controls="panel-tutorial">
                                    Website Tutorial
                                </button>
                                <div class="footer-panel" id="panel-tutorial">
                                    <a href="#">How to use Crow Education Website?</a>
                                </div>
                            </li>
                            <li><a href="#">Feedback</a></li>
                        </ul>
                    </div>
                </div>
                <p class="last">© 2025 Crow Education EduTeach Platform · Teacher Dashboard</p>
            </footer>
        </div>
    </div>

    <script src="../admin_php/JS/music.js"></script>
        
</body>

</html>