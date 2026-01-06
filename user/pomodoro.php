<?php
// user/abouts.php
session_start();

// Set current page as hightlighted in sidebar
$current_page = 'about us';

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
    <title>Pomodoro Technique - Boost Your Productivity</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        :root {
            --primary: #3498db;
            --secondary: #2980b9;
            --accent: #e74c3c;
            --light: #ffffff;
            --dark: #2c3e50;
            --gray: #7f8c8d;
            --light-gray: #ecf0f1;
            --shadow: 0 4px 15px rgba(52, 152, 219, 0.15);
            --transition: all 0.3s ease;
            
        
            --bg-color: #f8f9fa;
            --text-color: #2c3e50;
            --card-bg: #ffffff; 
            --header-bg: #ffffff;
            --sidebar-bg: rgba(255, 255, 255, 0.95);
        }

       
        body.dark-theme {
            --bg-color: #1a1a2e;
            --text-color: #e6e6e6;
            --card-bg: #16213e;
            --header-bg: #16213e;
            --sidebar-bg: rgba(22, 33, 62, 0.95);
            --light: #16213e;
            --dark: #e6e6e6;
            --gray: #b0b0b0;
            --light-gray: #2d3748;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        /* ========================================
        2. BACKGROUND (CSS)
        ========================================
        */
        body {
            background: var(--bg-color); 
            color: var(--text-color);
            min-height: 100vh;
            overflow-x: hidden;
            font-family: "Poppins", sans-serif;
            transition: var(--transition);
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1; 
        }

        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(52, 152, 219, 0.1); 
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 0.9;
            }
            100% {
                transform: translateY(0) rotate(360deg);
                opacity: 0.7;
            }
        }

  
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background: linear-gradient(to right, var(--secondary), var(--primary));
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: cover;
        }
        
        h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }
        
        .tagline {
            font-size: 1.5rem;
            margin-bottom: 30px;
            font-weight: 300;
            position: relative;
        }
        
        .btn {
            display: inline-block;
            background-color: var(--accent);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: bold;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
        }
        
        section {
            background-color: var(--card-bg);
            padding: 40px;
            margin: 40px auto;
            border-radius: 15px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        
        section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--accent));
        }
        
        h2 {
            color: var(--secondary);
            margin-bottom: 25px;
            font-size: 2rem;
            display: flex;
            align-items: center;
        }
        
        h2 i {
            margin-right: 15px;
            color: var(--primary);
        }
        
        h3 {
            color: var(--secondary);
            margin: 25px 0 15px;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
        }
        
        ul, ol {
            margin-left: 20px;
            margin-bottom: 20px;
        }
        
        li {
            margin-bottom: 12px;
            position: relative;
            padding-left: 10px;
        }
        
        li::before {
            content: "•";
            color: var(--primary);
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
        
        strong {
            color: var(--primary);
        }
        
        .example {
            background-color: #eef9ff;
            padding: 20px;
            border-left: 5px solid var(--primary);
            margin: 20px 0;
            border-radius: 8px;
            position: relative;
            margin-top: 100px;
        }
        
        .example::before {
            content: "Example";
            position: absolute;
            top: -12px;
            left: 20px;
            background: var(--primary);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: bold;
        }
        
        .profile {
            display: flex;
            align-items: center;
            margin-top: 30px;
            background: #f5f9ff;
            padding: 20px;
            border-radius: 10px;
        }
        
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 25px;
            border: 5px solid var(--primary);
        }
        
        .profile-info h4 {
            color: var(--secondary);
            margin-bottom: 5px;
        }
        
        .profile-info p {
            color: #666;
            font-style: italic;
        }
        
        .stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 40px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            flex: 1;
            min-width: 200px;
            margin: 10px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .stat-text {
            color: var(--secondary);
            font-weight: 500;
        }
        
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 40px auto;
        }
        
        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background-color: var(--primary);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }
        
        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }
        
        .timeline-item:nth-child(odd) {
            left: 0;
        }
        
        .timeline-item:nth-child(even) {
            left: 50%;
        }
        
        .timeline-content {
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            position: relative;
        }
        
        .timeline-item:nth-child(odd) .timeline-content::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: white;
            top: 20px;
            border-radius: 50%;
            z-index: 1;
            border: 4px solid var(--primary);
        }
        
        .timeline-item:nth-child(even) .timeline-content::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            left: -10px;
            background-color: white;
            top: 20px;
            border-radius: 50%;
            z-index: 1;
            border: 4px solid var(--primary);
        }
        
        .timeline-time {
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 5px;
        }
        
        .quote {
            font-style: italic;
            border-left: 4px solid var(--accent);
            padding-left: 20px;
            margin: 30px 0;
            color: #555;
            font-size: 1.1rem;
        }
        
        .author {
            text-align: right;
            font-weight: bold;
            color: var(--secondary);
            margin-top: 10px;
        }
                
        .social-icons {
            margin: 20px 0;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 15px;
            transition: var(--transition);
        }
        
        .social-icons a:hover {
            color: var(--primary);
        }
        
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* ========================================
        4. 返回按钮样式
        ========================================
        */
        .back-button {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            box-shadow: var(--shadow);
            cursor: pointer;
            transition: var(--transition);
            z-index: 1000;
        }

        .back-button i {
            margin-right: 8px;
        }

        .back-button:hover {
            background: var(--secondary);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        /* ========================================
        5. 响应式 (MEDIA QUERIES)
        ========================================
        */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            
            .tagline {
                font-size: 1.2rem;
            }
            
            section {
                padding: 25px;
            }
            
            .profile {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-img {
                margin-right: 0;
                margin-bottom: 15px;
            }
            
            .timeline::after {
                left: 31px;
            }
            
            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
            
            .timeline-item:nth-child(even) {
                left: 0;
            }
            
            .timeline-item:nth-child(odd) .timeline-content::after,
            .timeline-item:nth-child(even) .timeline-content::after {
                left: 21px;
            }

            .back-button {
                bottom: 20px;
                left: 20px;
                padding: 10px 20px;
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .content {
                padding: 15px;
            }
            h1 {
                font-size: 2rem;
            }
            p {
                font-size: 1rem;
            }
        }

        ul, ol {
            list-style: none;
            padding-left: 0;
        }

        li {
            padding-left: 0;
            margin-bottom: 15px;
        }

    </style>
</head>

<body>
    
    <div class="floating-elements"></div>

    <!-- 返回按钮 -->
    <button class="back-button" id="backButton">
        <i class="fas fa-arrow-left"></i> Back
    </button>

    <header>
        <div class="container">
            <h1>Pomodoro Technique</h1>
            <p class="tagline">Transform Your Productivity with Time-Tested Focus Sessions</p>
            <a href="#how-to-use" class="btn">Get Started Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What is the Pomodoro Technique?</h2>
            <ul>
                <li>The Pomodoro Technique is a revolutionary time management method developed by <mark>Francesco Cirillo</mark> in the late 1980s.</li>
                <li>The word "Pomodoro" means "tomato" in Italian — inspired by the tomato-shaped kitchen timer he used while studying.</li>
                <li>It divides work into short, focused sessions (25 minutes) followed by short breaks (5 minutes), and a longer break (15–30 minutes) after every four sessions.</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/Francesco Cirillo.png" alt="Francesco Cirillo" class="profile-img">
                <div class="profile-info">
                    <h4>Francesco Cirillo</h4>
                    <p>Creator of the Pomodoro Technique</p>
                    <p>"The Pomodoro Technique is a way to work with time, instead of struggling against it."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Choose a specific task</strong> - Be clear about what you want to accomplish</li>
                <li><strong>Set the timer for 25 minutes</strong> - Commit to focusing for this period</li>
                <li><strong>Focus fully</strong> - No distractions allowed during the Pomodoro</li>
                <li><strong>When the timer rings, record one Pomodoro</strong> - Track your progress</li>
                <li><strong>Take a 5-minute break</strong> - Step away from your work completely</li>
                <li><strong>After four Pomodoros, take a longer 15–30-minute break</strong> - Recharge your mental energy</li>
            </ol>

            <div class="example">
                <strong>Example Schedule:</strong><br>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">08:00-08:25</div>
                            <div>Pomodoro 1 - Deep Work Session</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">08:25-08:30</div>
                            <div>Short Break - Stretch & Hydrate</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">08:30-08:55</div>
                            <div>Pomodoro 2 - Continued Focus</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">08:55-09:00</div>
                            <div>Short Break - Rest Your Eyes</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">09:00-09:25</div>
                            <div>Pomodoro 3 - Building Momentum</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">09:25-09:30</div>
                            <div>Short Break - Quick Walk</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">09:30-09:55</div>
                            <div>Pomodoro 4 - Final Push</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">09:55-10:10</div>
                            <div>Long Break - Reward Yourself!</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Science Behind It</h2>
            <ul>
                <li>The Pomodoro Technique works through a focus-rest cycle, preventing mental fatigue and maintaining high performance.</li>
                <li>According to the <strong>Attention Restoration Theory</strong>, concentration naturally drops after 20-30 minutes, and short breaks restore focus.</li>
                <li>It also uses the <strong>time pressure effect</strong>—a limited time frame triggers the brain's "flow state," enhancing performance and creativity.</li>
            </ul>
            
            <div class="quote">
                "The Pomodoro Technique aligns with our brain's natural attention rhythms, making sustained focus not just possible but enjoyable."
                <div class="author">- Dr. Maria Konnikova, Cognitive Scientist</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">40%</div>
                    <div class="stat-text">Increase in Productivity</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25%</div>
                    <div class="stat-text">Reduction in Procrastination</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">35%</div>
                    <div class="stat-text">Higher Task Completion</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Improves focus and productivity:</strong> Helps you concentrate fully for short periods, reducing distractions and increasing efficiency. By working in 25-minute focused sessions, your brain can enter an optimal state for completing tasks.</li>
                <li><strong>Reduces procrastination and distraction:</strong> Knowing that you only need to focus for 25 minutes reduces the urge to delay tasks. Regular short breaks prevent fatigue and maintain motivation.</li>
                <li><strong>Makes large tasks easier to manage:</strong> By breaking a big task into small 25-minute segments, you make it less overwhelming. Completing each Pomodoro gives a sense of accomplishment and motivation.</li>
                <li><strong>Builds time awareness and self-discipline:</strong> Recording completed Pomodoros helps you understand your work pace and improve time management skills. Over time, this strengthens self-discipline and efficiency.</li>
                <li><strong>Prevents burnout through regular breaks:</strong> Short, scheduled breaks restore mental energy and prevent fatigue. This makes long-term study or work more sustainable and reduces stress.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Focus & Attention Mechanism</h3>
            <ul>
                <li>Research shows that the average human attention span lasts 20-45 minutes before fatigue and distraction occur. This supports the 25-minute focus + 5-minute break structure.</li>
                <li>Johnstone & Percival (1976) found students' attention drops after about 25-30 minutes.</li>
                <li>Rosen et al. (2013) observed that students are distracted by phones roughly every 6 minutes during study sessions. Pomodoro cycles minimize interruptions.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Memory & Spaced Learning</h3>
            <ul>
                <li>Pomodoro aligns with the Spacing Effect—learning spaced over time improves retention.</li>
                <li>Cepeda et al. (2006) found spaced learning increases memory retention by up to 200%.</li>
                <li>Kornell & Bjork (2008) found studying in shorter sessions improves test performance by about 30%.</li>
            </ul>

            <h3><i class="fas fa-clock"></i> Time Management & Productivity</h3>
            <ul>
                <li>Short focus intervals increase productivity by 25-40% (Harvard Business Review, 2018).</li>
                <li>Workers taking short breaks maintained 13% higher focus than those working 50 minutes straight (University of Illinois, 2011).</li>
            </ul>

            <h3><i class="fas fa-heart"></i> Mental & Emotional Well-being</h3>
            <ul>
                <li>Structured intervals reduce stress and anxiety. Individuals without breaks had higher cortisol levels (UC Irvine, 2016).</li>
                <li>Short, timed sessions resulted in 20% less anxiety and 35% higher feelings of accomplishment.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Train focus, not force work:</strong> Focus on improving concentration rather than overworking. Adjust session length if necessary.</li>
                <li><strong>If interrupted, restart:</strong> Distractions break focus. Restart Pomodoro to maintain benefits.</li>
                <li><strong>Use multiple sessions for complex tasks:</strong> Divide big tasks into several Pomodoros to stay motivated.</li>
                <li><strong>Adjust time to suit your rhythm:</strong> Some work better with longer or shorter periods (e.g., 40 minute focus and 10 minute break).</li>
                <li><strong>Don't stress over the timer:</strong> Focus on flow state rather than the timer itself.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Pomodoro Technique is a simple yet powerful strategy for managing time and focus. It boosts discipline, motivation, and long-term learning performance. Both global and Malaysian studies highlight its value as an effective learning method that aligns with how our brains naturally work.</p>
            
            <div class="quote">
                "In a world of constant distractions, the Pomodoro Technique offers a structured approach to reclaiming your focus and accomplishing what matters most."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Start Your First Pomodoro</a>
            </div>
        </section>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
    
            const floatingElementsContainer = document.querySelector('.floating-elements');

            function createFloatingElement() {
                if (!floatingElementsContainer) return;
                const element = document.createElement('div');
                element.className = 'floating-element';
                
                const size = Math.random() * 150 + 50; 
                element.style.width = `${size}px`;
                element.style.height = `${size}px`;
                element.style.top = `${Math.random() * 100}vh`;
                element.style.left = `${Math.random() * 100}vw`;
                element.style.animationDuration = `${Math.random() * 20 + 15}s`; 
                element.style.animationDelay = `${Math.random() * -30}s`; 
                
                floatingElementsContainer.appendChild(element);
            }
            
         
            for (let i = 0; i < 5; i++) {
                createFloatingElement();
            }

    
            const sections = document.querySelectorAll('section');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = 1;
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            sections.forEach(section => {
                section.style.opacity = 0;
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(section);
            });


            // Back button functionality
            const backButton = document.getElementById('backButton');
            backButton.addEventListener('click', () => {
                window.location.href = 'method.php';
            });
        });
    </script>
            <script src="../js/main.js"></script>

</body>
</html>