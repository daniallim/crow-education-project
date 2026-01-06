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
    <title>Cornell Note-Taking Method - Organize and Review Efficiently</title>
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

        /* Cornell 笔记布局示例 */
        .cornell-layout {
            display: flex;
            flex-direction: column;
            max-width: 800px;
            margin: 30px auto;
            border: 2px solid var(--primary);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        .cornell-header {
            background-color: var(--primary);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        
        .cornell-body {
            display: flex;
            min-height: 300px;
        }
        
        .cornell-cues {
            width: 30%;
            background-color: #f0f8ff;
            padding: 15px;
            border-right: 2px dashed var(--primary);
        }
        
        .cornell-notes {
            width: 70%;
            padding: 15px;
            background-color: white;
        }
        
        .cornell-summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-top: 2px dashed var(--primary);
        }
        
        .cornell-section-title {
            font-weight: bold;
            color: var(--secondary);
            margin-bottom: 10px;
            font-size: 0.9rem;
        }
        
        .cornell-item {
            margin-bottom: 10px;
            padding: 5px;
            border-radius: 4px;
        }
        
        .cornell-cues .cornell-item {
            background-color: #e6f2ff;
        }
        
        .cornell-notes .cornell-item {
            background-color: #f5f5f5;
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
            
            .cornell-body {
                flex-direction: column;
            }
            
            .cornell-cues, .cornell-notes {
                width: 100%;
            }
            
            .cornell-cues {
                border-right: none;
                border-bottom: 2px dashed var(--primary);
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
            <h1>Cornell Note-Taking Method</h1>
            <p class="tagline">Organize and Review Information Efficiently</p>
            <a href="#how-to-use" class="btn">Start Learning Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What it is</h2>
            <ul>
                <li>The Cornell Note-Taking Method is a structured system for organizing and reviewing notes, created by <mark>Dr. Walter Pauk</mark> at Cornell University in the 1940s.</li>
                <li>It divides a page into three sections — a cue column, a note-taking area, and a summary section — to help learners record, reflect, and recall information efficiently.</li>
                <li>This method is widely used by students and educators because it turns passive note-taking into an active learning process.</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/walter pauk.png" alt="Dr. Walter Pauk" class="profile-img">
                <div class="profile-info">
                    <h4>Dr. Walter Pauk</h4>
                    <p>Creator of the Cornell Note-Taking Method</p>
                    <p>"Effective note-taking is the foundation of effective learning."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to use it</h2>
            <ol>
                <li><strong>Prepare the layout:</strong> Divide the page into three parts:
                    <ul>
                        <li>Left column (Cues): for key terms, main ideas, or questions.</li>
                        <li>Right column (Notes): for detailed explanations and examples.</li>
                        <li>Bottom section (Summary): for summarizing the main points.</li>
                    </ul>
                </li>
                <li><strong>During class or reading:</strong>
                    <ul>
                        <li>Write detailed notes in the right column — definitions, formulas, or explanations.</li>
                        <li>Use bullet points and abbreviations to stay clear and concise.</li>
                    </ul>
                </li>
                <li><strong>After class:</strong>
                    <ul>
                        <li>Fill in the left column with questions or keywords based on your notes.</li>
                        <li>Write a short summary (2–3 sentences) at the bottom to capture what you learned.</li>
                    </ul>
                </li>
                <li><strong>Review regularly:</strong>
                    <ul>
                        <li>Cover the right column and test yourself using the cues on the left.</li>
                        <li>Review within 24 hours, then again weekly to strengthen memory.</li>
                    </ul>
                </li>
            </ol>

            <div class="example">
                <strong>Cornell Note Layout Example</strong>
                <div class="cornell-layout">
                    <div class="cornell-header">Topic: Photosynthesis Process</div>
                    <div class="cornell-body">
                        <div class="cornell-cues">
                            <div class="cornell-section-title">Cues / Questions</div>
                            <div class="cornell-item">What is photosynthesis?</div>
                            <div class="cornell-item">Reactants & Products?</div>
                            <div class="cornell-item">Where does it occur?</div>
                            <div class="cornell-item">Importance for life?</div>
                        </div>
                        <div class="cornell-notes">
                            <div class="cornell-section-title">Notes</div>
                            <div class="cornell-item">Process by which plants convert light energy into chemical energy.</div>
                            <div class="cornell-item">Reactants: CO₂ + H₂O + light energy. Products: Glucose + O₂.</div>
                            <div class="cornell-item">Occurs in chloroplasts, specifically in the thylakoid membranes.</div>
                            <div class="cornell-item">Produces oxygen and food for nearly all life on Earth.</div>
                        </div>
                    </div>
                    <div class="cornell-summary">
                        <div class="cornell-section-title">Summary</div>
                        <div>Photosynthesis is the process plants use to convert light energy into chemical energy (glucose) using CO₂ and H₂O, releasing O₂ as a byproduct. It occurs in chloroplasts and is essential for life on Earth.</div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <h3 class="key">The Cornell Method is based on active recall and metacognitive reflection:</h3>
                <li><strong>Active Recall:</strong> By turning notes into questions, you force your brain to retrieve information, which strengthens memory.</li>
                <li><strong>Metacognition:</strong> Summarizing promotes conceptual understanding rather than rote memorization.</li>
                <li><strong>Encoding-Retrieval Principle:</strong> Organizing and recalling information improves long-term retention.</li>
                <p>This method transforms passive note-taking into an active learning strategy that engages multiple cognitive processes.</p>
            </ul>
            
            <div class="quote">
                "The Cornell Method transforms passive note-taking into an active learning process, engaging students in higher-order thinking skills."
                <div class="author">- Dr. Kenneth A. Kiewra, Educational Psychologist</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">30-40%</div>
                    <div class="stat-text">Better Performance</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">80%</div>
                    <div class="stat-text">Retention with Review</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">50%</div>
                    <div class="stat-text">Time Saved in Review</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Encourages active learning and critical thinking:</strong> The process of creating cues and summaries engages higher-order thinking.</li>
                <li><strong>Improves memory retention and comprehension:</strong> The structured format helps organize information for better recall.</li>
                <li><strong>Keeps notes organized, structured, and easy to review:</strong> The consistent layout makes it easy to find and review information.</li>
                <li><strong>Saves revision time by focusing on key ideas:</strong> The cue column highlights the most important concepts for quick review.</li>
                <li><strong>Helps identify what you do not understand early on:</strong> The process reveals knowledge gaps during the summarization phase.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Cornell University Research</h3>
            <ul>
                <li>Research from Cornell University shows that students using structured note-taking methods like Cornell perform 30–40% better on comprehension and recall tests.</li>
                <li>The method was specifically designed based on principles of cognitive psychology and learning science.</li>
                <li>Conclusion: Structured note-taking significantly enhances learning outcomes compared to conventional methods.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Educational Psychology Studies</h3>
            <ul>
                <li>A 2013 study in the Journal of Educational Psychology found that students who reviewed notes using self-questioning and summarization scored significantly higher than those who passively reread notes.</li>
                <li>The cue column in the Cornell Method directly implements the self-questioning technique proven effective in these studies.</li>
                <li>Conclusion: Active review strategies like those in the Cornell Method improve learning efficiency and retention.</li>
            </ul>

            <h3><i class="fas fa-chalkboard-teacher"></i> Memory Retention Research</h3>
            <ul>
                <li>Studies confirm that reviewing within 24 hours helps retain up to 80% of new information, compared to only 20% after a week without review.</li>
                <li>The Cornell Method's emphasis on immediate and regular review aligns with these findings on memory consolidation.</li>
                <li>Conclusion: The structured review process in the Cornell Method maximizes long-term retention of information.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Global Adoption & Effectiveness</h3>
            <ul>
                <li>The Cornell Method has been adopted by educational institutions worldwide, from high schools to medical schools.</li>
                <li>Medical students using the Cornell Method report better organization of complex information and more efficient exam preparation.</li>
                <li>Conclusion: The Cornell Method's effectiveness spans diverse subjects and educational levels.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Write in your own words — don't just copy slides:</strong> Paraphrasing helps process and understand information.</li>
                <li><strong>Review soon after class; delay reduces effectiveness:</strong> Immediate review maximizes the forgetting curve benefits.</li>
                <li><strong>Keep the layout neat and consistent for quick scanning:</strong> Consistency makes notes easier to review later.</li>
                <li><strong>Use colors or highlights for emphasis, but avoid clutter:</strong> Visual cues can enhance memory but should not distract.</li>
                <li><strong>Ask meaningful questions in the cue column:</strong> Focus on "Why does ___ happen?" rather than just "What is ___?"</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Cornell Note-Taking Method is a simple yet powerful system that turns note-taking into an active learning strategy.</p>
            <p>By combining structured organization, questioning, and summarization, it enhances understanding and long-term memory.</p>
            <p>When used correctly and reviewed regularly, it becomes one of the most efficient study tools for any subject.</p>
            
            <div class="quote">
                "In an age of information overload, the Cornell Method provides a structured approach to capturing, organizing, and retaining knowledge effectively."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Try the Cornell Method</a>
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