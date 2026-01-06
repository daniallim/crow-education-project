CREATE DATABASE IF NOT EXISTS crowdedu;
USE crowdedu;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    age INT NULL,
    role ENUM('user','admin','teacher','student') DEFAULT 'user',
    status ENUM('active','inactive','suspended') DEFAULT 'active',
    last_active DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE user_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    progress INT DEFAULT 0,
    profile_image VARCHAR(255),
    phone VARCHAR(20),
    address TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id)
);

CREATE TABLE student_profiles (
    student_profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    average_score DECIMAL(5,2) DEFAULT 0.00,
    last_active DATE,
    status ENUM('Active','At Risk') DEFAULT 'Active',
    total_assignments INT DEFAULT 0,
    completed_assignments INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_user (user_id)
);

CREATE TABLE student_performance (
    performance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    assessment_type ENUM('homework','quiz','exam','project') DEFAULT 'homework',
    score DECIMAL(5,2) NOT NULL,
    max_score INT DEFAULT 100,
    subject VARCHAR(100),
    completed_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE lessons (
    lesson_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    content TEXT,
    subject VARCHAR(100),
    class_level VARCHAR(50),
    status ENUM('draft','published','archived') DEFAULT 'draft',
    viewer_count INT DEFAULT 0,
    average_score DECIMAL(5,2) DEFAULT 0,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_date DATE NULL,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE lesson_materials (
    material_id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type ENUM('pdf','doc','ppt','jpg','png','mp4','mp3','other') DEFAULT 'other',
    file_size INT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE CASCADE
);

CREATE TABLE homework (
    homework_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    lesson_id INT NULL,
    assigned_by INT NOT NULL,
    due_date DATETIME,
    max_score INT DEFAULT 100,
    status ENUM('draft','assigned','completed','graded') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE SET NULL,
    FOREIGN KEY (assigned_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE homework_questions (
    question_id INT AUTO_INCREMENT PRIMARY KEY,
    homework_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice','essay','short_answer','true_false') DEFAULT 'multiple_choice',
    correct_answer TEXT,
    points INT DEFAULT 1,
    question_order INT DEFAULT 0,
    FOREIGN KEY (homework_id) REFERENCES homework(homework_id) ON DELETE CASCADE
);

CREATE TABLE homework_submissions (
    submission_id INT AUTO_INCREMENT PRIMARY KEY,
    homework_id INT NOT NULL,
    student_id INT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_score DECIMAL(5,2) DEFAULT 0,
    status ENUM('submitted','graded','returned') DEFAULT 'submitted',
    teacher_feedback TEXT,
    FOREIGN KEY (homework_id) REFERENCES homework(homework_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE homework_answers (
    answer_id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    question_id INT NOT NULL,
    student_answer TEXT,
    score DECIMAL(5,2) DEFAULT 0,
    teacher_feedback TEXT,
    FOREIGN KEY (submission_id) REFERENCES homework_submissions(submission_id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES homework_questions(question_id) ON DELETE CASCADE
);

CREATE TABLE assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    subject VARCHAR(100),
    assessment_type ENUM('written_exam','practical','essay','quiz','project') DEFAULT 'quiz',
    duration_minutes INT DEFAULT 60,
    assessment_date DATE,
    max_score INT DEFAULT 100,
    average_score DECIMAL(5,2) DEFAULT 0,
    highest_score DECIMAL(5,2) DEFAULT 0,
    lowest_score DECIMAL(5,2) DEFAULT 0,
    total_participants INT DEFAULT 0,
    created_by INT NOT NULL,
    status ENUM('draft','scheduled','completed','cancelled') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE assessment_results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id INT NOT NULL,
    student_id INT NOT NULL,
    score DECIMAL(5,2) DEFAULT 0,
    grade VARCHAR(10),
    status ENUM('passed','failed','absent') DEFAULT 'passed',
    completed_at TIMESTAMP NULL,
    teacher_notes TEXT,
    FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE chats (
    chat_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_name VARCHAR(255),
    chat_type ENUM('direct','group') DEFAULT 'direct',
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE chat_members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT NOT NULL,
    user_id INT NOT NULL,
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role ENUM('member','admin') DEFAULT 'member',
    FOREIGN KEY (chat_id) REFERENCES chats(chat_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id INT NOT NULL,
    sender_id INT NOT NULL,
    message_text TEXT,
    message_type ENUM('text','file','image','video') DEFAULT 'text',
    file_path VARCHAR(500) NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    FOREIGN KEY (chat_id) REFERENCES chats(chat_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE music (
    music_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    artist VARCHAR(150),
    album VARCHAR(150),
    duration INT,
    file_path VARCHAR(500) NOT NULL,
    file_size INT,
    category ENUM('focus','relax','study','motivation') DEFAULT 'focus',
    playlist_id INT NULL,
    play_count INT DEFAULT 0,
    liked BOOLEAN DEFAULT FALSE,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE playlists (
    playlist_id INT AUTO_INCREMENT PRIMARY KEY,
    playlist_name VARCHAR(200) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    is_public BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE playlist_tracks (
    playlist_id INT NOT NULL,
    music_id INT NOT NULL,
    track_order INT DEFAULT 0,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (playlist_id, music_id),
    FOREIGN KEY (playlist_id) REFERENCES playlists(playlist_id) ON DELETE CASCADE,
    FOREIGN KEY (music_id) REFERENCES music(music_id) ON DELETE CASCADE
);

CREATE TABLE settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('string','integer','boolean','json') DEFAULT 'string',
    category VARCHAR(50) DEFAULT 'general',
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    notification_type ENUM('system','homework','assessment','message','lesson') DEFAULT 'system',
    related_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE assesments (
    assesment_id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    title VARCHAR(200),
    content TEXT,
    difficulty ENUM('easy','medium','hard') DEFAULT 'easy',
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE CASCADE
);

CREATE TABLE community_posts (
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200),
    content TEXT,
    note_file VARCHAR(255),
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE community_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES community_posts(post_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE lesson_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    lesson_id INT NOT NULL,
    user_id INT NOT NULL,
    comment_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE admin_feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    comment_id INT NOT NULL,
    admin_id INT NOT NULL,
    feedback_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (comment_id) REFERENCES lesson_comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE certificates (
    certificate_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NULL,
    course_name VARCHAR(200),
    issued_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    certificate_file VARCHAR(255),
    verification_code VARCHAR(100) UNIQUE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(lesson_id) ON DELETE SET NULL
);

CREATE TABLE admin_dashboard (
    dashboard_id INT AUTO_INCREMENT PRIMARY KEY,
    total_users INT DEFAULT 0,
    total_lessons INT DEFAULT 0,
    most_active_user INT NULL,
    FOREIGN KEY (most_active_user) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE TABLE admin_activity_log (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE user_schedule (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    start_time DATETIME,
    end_time DATETIME,
    category ENUM('class','exam','task') DEFAULT 'class',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE user_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255),
    content TEXT,
    shared BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE resources (
    resource_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100),
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_milestones (
    milestone_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    progress_percentage INT DEFAULT 0,
    achieved BOOLEAN DEFAULT FALSE,
    achieved_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE study_methods (
    method_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    method_name VARCHAR(255),
    description TEXT,
    effectiveness_rating INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

INSERT INTO users (username, email, password, full_name, role, status) VALUES 
('admin', 'admin@crownedu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', 'active'),
('teacher_yc', 'yeeching@crownedu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Yee Ching', 'teacher', 'active');

INSERT INTO users (username, email, password, full_name, role, status, last_active) VALUES 
('alice_tan', 'alice.tan@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alice Tan', 'student', 'active', NOW()),
('john_lim', 'john.lim@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Lim', 'student', 'active', DATE_SUB(NOW(), INTERVAL 5 DAY)),
('shang_jin', 'shang.jin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lim Shang Jin', 'student', 'active', DATE_SUB(NOW(), INTERVAL 2 DAY)),
('siti_aminah', 'siti.aminah@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Aminah', 'student', 'active', DATE_SUB(NOW(), INTERVAL 4 DAY)),
('david_chen', 'david.chen@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'David Chen', 'student', 'active', NOW());

INSERT INTO user_profiles (user_id, bio, progress) VALUES 
(1, 'System Administrator', 100),
(2, 'Mathematics and Science Teacher', 100),
(3, 'Dedicated student interested in science', 75),
(4, 'Working on improving math skills', 45),
(5, 'Top performing student', 95),
(6, 'Working hard to improve grades', 50),
(7, 'Excellent in all subjects', 90);

INSERT INTO student_profiles (user_id, average_score, last_active, status) VALUES 
(3, 86.00, '2024-11-01', 'Active'),
(4, 42.00, '2024-10-25', 'At Risk'),
(5, 100.00, '2024-10-29', 'Active'),
(6, 45.00, '2024-10-26', 'At Risk'),
(7, 92.00, '2024-11-02', 'Active');

INSERT INTO student_performance (user_id, assessment_type, score, max_score, subject, completed_date) VALUES 
(3, 'homework', 85, 100, 'Mathematics', '2024-10-28'),
(3, 'quiz', 88, 100, 'Science', '2024-10-30'),
(3, 'exam', 85, 100, 'English', '2024-11-01'),
(4, 'homework', 40, 100, 'Mathematics', '2024-10-20'),
(4, 'quiz', 45, 100, 'Science', '2024-10-22'),
(4, 'exam', 41, 100, 'English', '2024-10-25'),
(5, 'homework', 100, 100, 'Mathematics', '2024-10-25'),
(5, 'quiz', 100, 100, 'Science', '2024-10-27'),
(5, 'exam', 100, 100, 'English', '2024-10-29'),
(6, 'homework', 42, 100, 'Mathematics', '2024-10-22'),
(6, 'quiz', 48, 100, 'Science', '2024-10-24'),
(6, 'exam', 45, 100, 'English', '2024-10-26'),
(7, 'homework', 90, 100, 'Mathematics', '2024-10-29'),
(7, 'quiz', 94, 100, 'Science', '2024-10-31'),
(7, 'exam', 92, 100, 'English', '2024-11-02');

INSERT INTO settings (setting_key, setting_value, setting_type, category, description) VALUES 
('site_name', 'Crow Education', 'string', 'general', 'Website name'),
('site_description', 'Online Learning Platform', 'string', 'general', 'Website description'),
('dark_mode_enabled', 'false', 'boolean', 'appearance', 'Enable dark mode'),
('notifications_enabled', 'true', 'boolean', 'notifications', 'Enable notifications');

INSERT INTO lessons (title, description, subject, class_level, status, viewer_count, average_score, created_by, published_date) VALUES 
('Science Chapter 2: Atoms', 'Introduction to atomic structure and properties', 'Chemistry Basics', 'Form 4', 'published', 124, 85.5, 2, '2025-03-15'),
('Math: Fractions & Ratios', 'Understanding fractions, ratios and proportions', 'Math Fundamentals', 'Form 3', 'draft', 0, 0, 2, NULL),
('English Grammar Test', 'Comprehensive grammar assessment', 'Grammar Practice', 'Form 5', 'published', 228, 78.2, 2, '2025-03-18');

INSERT INTO assessments (title, description, subject, assessment_type, duration_minutes, assessment_date, max_score, average_score, highest_score, lowest_score, total_participants, created_by, status) VALUES 
('Task - Mathematics', 'Written examination on algebra and geometry', 'Mathematics', 'written_exam', 90, '2025-03-20', 100, 82.0, 98.0, 65.0, 28, 2, 'completed'),
('Science Lab Report', 'Practical assessment of laboratory skills', 'Science', 'practical', 120, '2025-03-22', 100, 91.0, 100.0, 78.0, 32, 2, 'completed'),
('English Essay Writing', 'Essay composition and analysis', 'English', 'essay', 60, '2025-03-25', 100, 79.0, 95.0, 62.0, 24, 2, 'scheduled');

INSERT INTO chats (chat_name, chat_type, created_by) VALUES 
('Teacher-Student Chat', 'direct', 2);

INSERT INTO chat_members (chat_id, user_id, role) VALUES 
(1, 2, 'admin'),
(1, 3, 'member');

INSERT INTO messages (chat_id, sender_id, message_text, sent_at) VALUES 
(1, 3, 'Hello Teacher, I have a question about the homework.', NOW()),
(1, 2, 'Hi Alice, what would you like to know?', NOW());

INSERT INTO playlists (playlist_name, description, created_by, is_public) VALUES 
('Focus Playlist', 'A collection of focus-enhancing tracks', 2, true),
('Study Music', 'Background music for studying', 2, true);

INSERT INTO notifications (user_id, title, message, notification_type, is_read) VALUES 
(2, 'New Assignment Submitted', 'Alice Tan submitted Math homework', 'homework', false),
(2, 'Parent Meeting Reminder', 'Meeting with John Lim''s parents tomorrow', 'system', false),
(2, 'System Update', 'New features available in gradebook', 'system', false);