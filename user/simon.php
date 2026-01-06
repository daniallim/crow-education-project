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
    <title>Simon Learning Method - Master Deep Understanding</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #9c27b0;
            --secondary: #7b1fa2; 
            --accent: #e91e63; 
            --light: #ffffff;
            --dark: #2c3e50;
            --gray: #7f8c8d;
            --light-gray: #ecf0f1;
            --shadow: 0 4px 15px rgba(156, 39, 176, 0.15);
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
            background: rgba(156, 39, 176, 0.1);
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
            box-shadow: 0 4px 15px rgba(233, 30, 99, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
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
            background-color: #f9f0ff;
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
            background: #f5f0ff;
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
            <h1>Simon Learning Method</h1>
            <p class="tagline">Master Deep Understanding Through Cognitive Science</p>
            <a href="#how-to-use" class="btn">Start Learning Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What is the Simon Learning Method?</h2>
            <ul>
                <li>The Simon Learning Method is a cognitive learning theory developed by <mark>Herbert A. Simon</mark>, a Nobel Prize-winning cognitive scientist and economist.</li>
                <li>It emphasizes understanding, reasoning, and problem-solving rather than memorizing facts.</li>
                <li>According to Simon, effective learning happens when students actively process information, form mental connections, and apply knowledge in new contexts.</li>
                <li>In simple terms, this method trains learners to "think like scientists" — not just to know what, but to understand why and how things work.</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/herbert a. simon.png" alt="Herbert A. Simon" class="profile-img">
                <div class="profile-info">
                    <h4>Herbert A. Simon</h4>
                    <p>Nobel Prize-winning Cognitive Scientist</p>
                    <p>"Learning results from what the student does and thinks and only from what the student does and thinks. The teacher can advance learning only by influencing what the student does to learn."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Understand the Concept</strong> - Before memorizing, focus on the meaning and logic behind the topic.</li>
                <li><strong>Analyze and Connect</strong> - Link new information to what you already know to create a "knowledge network."</li>
                <li><strong>Apply and Practice</strong> - Use what you've learned to solve real-world problems or exercises.</li>
                <li><strong>Reflect and Transfer</strong> - Think about how the same concept can be applied in new or different situations.</li>
                <li><strong>Review and Refine</strong> - Review the concepts regularly to strengthen understanding and correct misconceptions.</li>
            </ol>

            <div class="example">
                <strong>Example Application:</strong><br>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Step 1</div>
                            <div>Understand the Concept - Instead of memorizing math formulas, understand how and why the formula works.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Step 2</div>
                            <div>Analyze and Connect - When learning calculus, connect it to algebraic concepts such as slope and change.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Step 3</div>
                            <div>Apply and Practice - Practice different problems that use the same mathematical principle.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Step 4</div>
                            <div>Reflect and Transfer - Apply the logic of equations from math to analyze economics or computer science.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Step 5</div>
                            <div>Review and Refine - Summarize what you've learned each week and identify areas that need deeper understanding.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <li>The Simon Learning Method is based on Cognitive Psychology and Information Processing Theory.</li>
                <li>It views the human mind as an active processor of information rather than a passive receiver.</li>
                <li><strong>Core Idea:</strong> Humans learn best when they understand, connect, and apply information — not through rote memorization.</li>
            </ul>
            
            <h3>Key Principles:</h3>
            <ul>
                <li><strong>Understanding before memorizing</strong> – True learning begins with comprehension.</li>
                <li><strong>Schema Theory</strong> – Knowledge is stored in structured mental models called schemas that help us organize and recall information.</li>
                <li><strong>Transfer of Learning</strong> – The ability to apply previously learned knowledge to new and different problems.</li>
            </ul>
            
            <div class="quote">
                "The capacity of the human mind for formulating and solving complex problems is very small compared with the size of the problems whose solution is required for objectively rational behavior in the real world."
                <div class="author">- Herbert A. Simon</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">40-60%</div>
                    <div class="stat-text">Better Problem-Solving</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30%</div>
                    <div class="stat-text">Higher Retention</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">25-35%</div>
                    <div class="stat-text">Improved Performance</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Promotes Deep Understanding:</strong> Helps learners grasp the meaning behind concepts instead of memorizing isolated facts.</li>
                <li><strong>Enhances Problem-Solving Skills:</strong> Encourages logical reasoning and critical thinking in real-life problem contexts.</li>
                <li><strong>Improves Knowledge Retention:</strong> Understanding builds stronger, longer-lasting memory connections.</li>
                <li><strong>Encourages Creativity and Flexibility:</strong> Learners can apply what they know across different subjects and scenarios.</li>
                <li><strong>Applicable to All Disciplines:</strong> Effective for science, mathematics, language, economics, and even art-based learning.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Understanding vs. Memorization</h3>
            <ul>
                <li>Simon & Newell (1972) found that students who learned through understanding principles performed 40–60% better on transfer and problem-solving tasks than those who memorized answers.</li>
                <li>Conclusion: Deep comprehension leads to better application and adaptability.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Schema Formation & Knowledge Networks</h3>
            <ul>
                <li>Chi, Glaser & Farr (1988) demonstrated that experts build strong mental "schemas" that help them recognize and solve new problems faster — confirming Simon's theory.</li>
                <li>Conclusion: Organized knowledge structures enable efficient learning and reasoning.</li>
            </ul>

            <h3><i class="fas fa-clock"></i> Learning Efficiency</h3>
            <ul>
                <li>Anderson (1990) reported that learners who studied conceptually retained 30% more information after two weeks compared to rote learners.</li>
                <li>Conclusion: Understanding-based study improves long-term retention.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Problem-Solving Performance</h3>
            <ul>
                <li>At Carnegie Mellon University, where Simon taught, structured understanding training improved STEM students' performance by 25–35%.</li>
                <li>Conclusion: Applying the Simon approach enhances critical thinking and academic results.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Don't just memorize — explain in your own words:</strong> Rephrase and teach concepts to yourself to ensure comprehension.</li>
                <li><strong>Use real examples:</strong> Every new idea should be connected to a real-world or personal example.</li>
                <li><strong>Review connections regularly:</strong> Revisiting how topics interlink strengthens your mental schema.</li>
                <li><strong>Avoid multitasking:</strong> Deep understanding requires focused attention and uninterrupted thought.</li>
                <li><strong>Ask "Why?" and "How?" questions:</strong> Constant questioning develops deeper analytical and reasoning skills.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Simon Learning Method emphasizes understanding, connection, and application as the foundation of true learning. Instead of memorizing isolated facts, you build a flexible network of knowledge that can be applied to new challenges.</p>
            
            <div class="quote">
                "In a world overflowing with information, the Simon Learning Method teaches us how to transform that information into genuine understanding and practical wisdom."
            </div>
            
            <p style="text-align: center; margin-top: 20px; font-weight: bold;">Learn deeply → Connect widely → Apply flexibly.</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Start Your Simon Learning Journey</a>
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