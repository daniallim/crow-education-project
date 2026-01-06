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
    <title>Feynman Technique - Master Any Subject</title>
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

        .key{
            color: #16213e;
            border-bottom: none;
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
            <h1>Feynman Technique</h1>
            <p class="tagline">Master Any Subject Through Simple Explanation</p>
            <a href="#how-to-use" class="btn">Start Learning Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What is the Feynman Technique?</h2>
            <ul>
                <li>The Feynman Technique is a learning method developed by <mark>Richard Feynman</mark>, a Nobel Prize-winning physicist famous for his ability to explain complex topics in simple, clear terms.</li>
                <li>This method focuses on achieving deep understanding through active explanation — if you can teach a topic in simple language, it means you truly understand it.</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/Richard Feynman.png" alt="Richard Feynman" class="profile-img">
                <div class="profile-info">
                    <h4>Richard Feynman</h4>
                    <p>Nobel Prize-winning Physicist</p>
                    <p>"If you can't explain it simply, you don't understand it well enough."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Choose a concept or topic you want to learn</strong> - Select one idea, formula, or theory that you want to master.</li>
                <li><strong>Explain it in your own words as if you're teaching someone else</strong> - Write or say your explanation as if you're explaining it to a beginner.</li>
                <li><strong>Identify the gaps in your understanding</strong> - When you struggle to explain a part, that's where your knowledge is incomplete.</li>
                <li><strong>Go back to the source material and relearn</strong> - Review books, notes, or videos to fill in those gaps.</li>
                <li><strong>Simplify and create analogies</strong> - Use simple words or real-life examples to make the concept easy to grasp.</li>
            </ol>

            <div class="example">
                <strong>Topic: Why We Get Hungry</strong><br>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Explanation 1</div>
                            <div>"Hunger is the body's way of signaling that it needs more energy."</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Explanation 2</div>
                            <div>"It's like a car running low on fuel — our body reminds us to refuel by eating."</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Explanation 3</div>
                            <div>"I'm not fully sure how the body knows when to feel hungry. I think it's related to hormones or blood sugar levels — I should review that."</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Explanation 4</div>
                            <div>Review and refine by learning about the hormones ghrelin (which increases hunger) and leptin (which reduces hunger), and how the brain's hypothalamus controls appetite.</div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Explanation 5</div>
                            <div>"Our brain and hormones work together to manage hunger. When energy is low, ghrelin tells the brain to eat; when full, leptin signals to stop. Hunger is simply our body's energy alarm system."</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <h3 class="key">The Feynman Technique is built on three key cognitive principles:</h3>
                <li><strong>Active Recall:</strong> Actively retrieving information strengthens memory.</li>
                <li><strong>Metacognition:</strong> Becoming aware of what you know and what you don't.</li>
                <li><strong>Elaboration:</strong> Rephrasing and connecting ideas builds deeper understanding.</li>
                <p>When you explain something aloud or in writing, your brain reorganizes information, forms stronger neural connections, and transforms shallow knowledge into deep comprehension.</p>
            </ul>
            
            <div class="quote">
                "The Feynman Technique forces you to confront what you don't understand, turning confusion into clarity through the act of explanation."
                <div class="author">- Dr. Barbara Oakley, Learning Expert</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">50%</div>
                    <div class="stat-text">Better Retention</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">37%</div>
                    <div class="stat-text">Improved Comprehension</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30%</div>
                    <div class="stat-text">Higher Performance</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Deepens Understanding:</strong> Explaining in your own words turns passive study into active learning. It helps you internalize the logic behind each idea rather than memorizing facts.</li>
                <li><strong>Reveals Knowledge Gaps:</strong> When you cannot explain clearly, you immediately discover what you don't understand. This helps you focus your review more efficiently.</li>
                <li><strong>Improves Long-term Memory:</strong> Teaching or explaining forces your brain to recall, organize, and restate knowledge — improving long-term retention and recall accuracy.</li>
                <li><strong>Boosts Confidence and Communication:</strong> Regularly practicing explanations builds confidence and strengthens your ability to communicate complex ideas in simple, logical terms.</li>
                <li><strong>Encourages Curiosity and Deeper Learning:</strong> The process of explaining often leads to new questions — inspiring curiosity and motivating you to learn more deeply.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Active Recall & Retrieval Practice</h3>
            <ul>
                <li>Research by Karpicke & Roediger (2008, Science) found that retrieval practice (actively recalling information) improves long-term retention by up to 50% compared to rereading.</li>
                <li>The Feynman Technique is a strong form of retrieval learning.</li>
                <li>Conclusion: Actively explaining concepts strengthens understanding and memory far more than passive review.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Metacognitive Awareness</h3>
            <ul>
                <li>According to Flavell (1979), metacognition — awareness of one's own thinking — allows learners to monitor and adjust learning strategies effectively.</li>
                <li>The "find your gaps" step in the Feynman Technique directly trains this ability.</li>
                <li>Conclusion: Students who identify and correct their weaknesses improve performance and learning outcomes by 25–30%.</li>
            </ul>

            <h3><i class="fas fa-chalkboard-teacher"></i> Teaching Effect & Learning by Explaining</h3>
            <ul>
                <li>Studies by Fiorella & Mayer (2013, Educational Psychology Review) revealed that learning by teaching improves comprehension by 37%.</li>
                <li>When learners prepare to teach, they process material more deeply and organize it better.</li>
                <li>Conclusion: Teaching others — or pretending to — strengthens both comprehension and memory.</li>
            </ul>

            <h3><i class="fas fa-brain"></i> Memory Consolidation</h3>
            <ul>
                <li>Neuroscience research (Immordino-Yang, 2016) shows that explaining ideas activates multiple brain regions responsible for understanding, emotion, and long-term memory.</li>
                <li>Conclusion: Explaining information engages the whole brain, leading to more meaningful and lasting learning.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Malaysia & Local Context</h3>
            <ul>
                <li>Although Malaysian universities have not yet conducted specific studies on the Feynman Technique, many have explored active learning and peer-teaching approaches that share the same principles.</li>
                <li>Examples: Active Learning in Computer Science Education — Noraini Idris (UM, 2018); Peer Teaching as a Learning Strategy — Tan Yee Lin (UKM, 2020).</li>
                <li>Conclusion: Local studies emphasize that active explanation and peer learning significantly enhance students' understanding and engagement, aligning with the Feynman approach.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Write it down — don't just think it:</strong> Writing forces clarity and helps visualize your weak points.</li>
                <li><strong>Teach someone (real or imaginary):</strong> Explain to a friend, classmate, or even an imaginary audience.</li>
                <li><strong>Simplify, but don't oversimplify:</strong> Aim for clarity without losing the core meaning or accuracy.</li>
                <li><strong>Repeat and review regularly:</strong> Re-explain the same concept later to strengthen retention.</li>
                <li><strong>Combine with other study techniques:</strong> For example, use the Pomodoro Technique — one 25-minute session per explanation cycle.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Feynman Technique is a simple yet powerful method for mastering any subject. By explaining concepts in your own words, you transform passive learning into active understanding.</p>
            <p>Supported by decades of psychological and neuroscience research, it enhances comprehension, memory, and confidence — making it one of the most effective learning strategies ever developed.</p>
            
            <div class="quote">
                "In a world of information overload, the Feynman Technique offers a structured approach to true understanding and mastery of complex subjects."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Try the Feynman Technique</a>
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