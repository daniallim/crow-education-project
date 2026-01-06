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
    <title>Spaced Repetition - Boost Your Memory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        :root {
            --primary: #9b59b6; 
            --secondary: #8e44ad; 
            --accent: #e74c3c; 
            --light: #ffffff;
            --dark: #2c3e50;
            --gray: #7f8c8d;
            --light-gray: #ecf0f1;
            --shadow: 0 4px 15px rgba(155, 89, 182, 0.15);
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
            background: rgba(155, 89, 182, 0.1); 
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
            background-color: #f5eef8;
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
            background: #f9f5ff;
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
        4. 响应式 (MEDIA QUERIES)
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
    </style>
</head>

<body>
    
    <div class="floating-elements"></div>

    <button class="back-button" id="backButton">
        <i class="fas fa-arrow-left"></i> Back
    </button>

    <header>
        <div class="container">
            <h1>Spaced Repetition</h1>
            <p class="tagline">Master Any Subject with Scientifically Proven Memory Techniques</p>
            <a href="#how-to-use" class="btn">Get Started Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What is Spaced Repetition?</h2>
            <ul>
                <li>Spaced Repetition is a scientifically proven learning technique that involves reviewing information at increasing intervals over time.</li>
                <li>It is based on the idea that our brains remember information better when we revisit it periodically instead of cramming it all at once.</li>
                <li>The concept was first introduced by psychologist <mark>Hermann Ebbinghaus</mark> in the late 19th century, who discovered the "Forgetting Curve."</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/Hermann Ebbinghaus.png" alt="Hermann Ebbinghaus" class="profile-img">
                <div class="profile-info">
                    <h4>Hermann Ebbinghaus</h4>
                    <p>Pioneer of Memory Research</p>
                    <p>"The forgetting curve shows that memory retention declines rapidly after learning but can be reinforced with timely reviews."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Choose what you want to remember</strong> - Vocabulary, formulas, concepts, or any information</li>
                <li><strong>Review it right after learning</strong> - Initial review on Day 0</li>
                <li><strong>Review again after increasing intervals</strong> - 1 day, 3 days, 7 days, 14 days, etc.</li>
                <li><strong>Each review strengthens memory</strong> - Slows down forgetting process</li>
                <li><strong>Use digital tools to automate</strong> - Apps like Anki, Quizlet, or Notion</li>
            </ol>

            <div class="example">
                <strong>Example Schedule:</strong><br>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 0</div>
                            <div>Initial Learning - Learn 10 new English words</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 1</div>
                            <div>First Review - Review same 10 words</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 3</div>
                            <div>Second Review - Review again</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 7</div>
                            <div>Third Review - 7-day review</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 14</div>
                            <div>Fourth Review - 2-week interval</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Day 30</div>
                            <div>Fifth Review - Monthly maintenance</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <li>Spaced Repetition is based on Ebbinghaus's Forgetting Curve, which shows that memory retention declines rapidly after learning but can be reinforced with timely reviews.</li>
                <li>Each review resets the curve, making the memory last longer and requiring less effort to recall.</li>
                <li>It also applies the <strong>Testing Effect</strong> — actively recalling information strengthens neural connections, improving long-term retention.</li>
            </ul>
            
            <div class="quote">
                "Instead of studying hard once, you study smart multiple times — just before you forget."
                <div class="author">- Cognitive Learning Principle</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">200%</div>
                    <div class="stat-text">Memory Improvement</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">40%</div>
                    <div class="stat-text">Less Study Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30%</div>
                    <div class="stat-text">Better Test Performance</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Enhances Long-Term Memory:</strong> Reviewing material at spaced intervals strengthens neural connections, helping you remember information for months or even years.</li>
                <li><strong>Increases Learning Efficiency:</strong> You spend less total time relearning because reviews happen only when memory starts to fade — maximizing results with minimal time.</li>
                <li><strong>Prevents Cramming & Reduces Stress:</strong> Instead of last-minute studying, Spaced Repetition promotes consistent, low-stress learning over time.</li>
                <li><strong>Improves Active Recall & Critical Thinking:</strong> Frequent retrieval practice improves your ability to explain and apply knowledge.</li>
                <li><strong>Suitable for All Subjects:</strong> Whether it's vocabulary, math formulas, programming syntax, or historical facts, the method works universally.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-memory"></i> Memory Retention & Forgetting Curve</h3>
            <ul>
                <li>Hermann Ebbinghaus (1885) discovered the "Forgetting Curve," showing that people forget up to 70% of new information within 24 hours if not reviewed.</li>
                <li>Each spaced review flattens the curve, extending how long you remember the material.</li>
                <li>Conclusion: Spaced Repetition directly counters natural forgetting, creating strong, durable memories.</li>
            </ul>

            <h3><i class="fas fa-chart-bar"></i> The Spacing Effect</h3>
            <ul>
                <li>Cepeda et al. (2006, Psychological Science): Spaced study sessions improved memory by up to 200% compared to massed (crammed) study.</li>
                <li>Kornell & Bjork (2008): Students who used spaced practice performed 30% better on tests than those who studied all at once.</li>
                <li>Bahrick (1979): Participants who reviewed foreign words every few weeks remembered 4× more after 9 years than those who learned in one sitting.</li>
            </ul>

            <h3><i class="fas fa-search"></i> Active Recall & Testing Effect</h3>
            <ul>
                <li>Roediger & Karpicke (2006, Science): Testing yourself (recall) improves memory more than rereading.</li>
                <li>Combining Spaced Repetition + Active Recall increases long-term retention by 50–70%.</li>
                <li>Conclusion: Spaced testing, not just spaced studying, helps build stronger retrieval pathways in the brain.</li>
            </ul>

            <h3><i class="fas fa-clock"></i> Learning Efficiency & Study Habits</h3>
            <ul>
                <li>Dempster (1988): Students using spaced review needed 40% less study time to achieve the same performance.</li>
                <li>Harvard University (2019): Students who used spaced review systems like Anki or Quizlet scored 20–30% higher in final exams.</li>
                <li>Conclusion: Spaced Repetition maximizes learning efficiency — you remember more in less time.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Malaysia & Local Research Context</h3>
            <ul>
                <li>While there is limited direct research on Spaced Repetition in Malaysia, studies on student learning strategies and memory improvement highlight its potential.</li>
                <li>Time Management & Study Strategy Research — TAR UMT (2018–2022): Emphasizes consistent review habits as a key factor in academic success.</li>
                <li>Cognitive Learning Techniques — Universiti Malaya (2020): Found that spaced review schedules significantly improved students' test recall compared to cramming.</li>
                <li>Conclusion: Malaysian educational trends are shifting toward evidence-based learning methods like Spaced Repetition.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Start small and stay consistent:</strong> Review a few items daily instead of many at once. Consistency is key to long-term success.</li>
                <li><strong>Use digital tools:</strong> Apps like Anki, RemNote, or Quizlet automatically schedule your reviews using the spacing algorithm.</li>
                <li><strong>Combine with Active Recall:</strong> Don't just reread — test yourself with flashcards or questions to boost retention.</li>
                <li><strong>Adjust intervals for your memory speed:</strong> Everyone's forgetting rate differs. If you forget too quickly, shorten your review gaps.</li>
                <li><strong>Don't worry about perfection:</strong> Missing one review won't ruin your progress — learning is a long-term process.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>Spaced Repetition is a powerful and scientifically grounded learning technique that strengthens memory through repeated reviews over time. Supported by global research and local studies, it improves long-term retention, efficiency, and confidence. When combined with active recall and consistency, it becomes one of the most effective methods for mastering any subject.</p>
            
            <div class="quote">
                "Spaced Repetition leverages how the brain naturally forgets — turning forgetting into a tool for learning."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Start Your Spaced Repetition Journey</a>
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