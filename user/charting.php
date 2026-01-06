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
    <title>Charting Method - Master Note-Taking with Tables</title>
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
        4. 表格样式
        ========================================
        */
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            box-shadow: var(--shadow);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .chart-table thead tr {
            background-color: var(--primary);
            color: white;
            text-align: left;
        }
        
        .chart-table th,
        .chart-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        
        .chart-table tbody tr {
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
        
        .chart-table tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }
        
        .chart-table tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary);
        }
        
        .chart-table tbody tr:hover {
            background-color: #e8f4fc;
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
            
            .chart-table {
                display: block;
                overflow-x: auto;
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
            <h1>Charting Method</h1>
            <p class="tagline">Master Note-Taking with Structured Tables</p>
            <a href="#how-to-use" class="btn">Start Learning Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-question-circle"></i> What is the Charting Method?</h2>
            <ul>
                <li>The Charting Method is a note-taking technique that organizes information into a table or chart format with columns and rows.</li>
                <li>It is especially useful when comparing facts, events, categories, or processes.</li>
                <li>Instead of writing long sentences, the method emphasizes clear structure and visual order — allowing you to see relationships and differences at a glance.</li>
            </ul>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Draw a table or chart</strong> — divide your paper into several columns.</li>
                <li><strong>Label each column with a key category</strong> (e.g., Date, Event, Cause, Effect, Example, etc.).</li>
                <li><strong>Fill in information as you study or listen to lectures</strong>, placing facts in the right category.</li>
                <li><strong>Review by scanning across rows</strong> (for one topic) or down columns (for comparison).</li>
            </ol>

            <div class="example">
                <strong>Example: World Wars Comparison</strong><br>
                <table class="chart-table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Cause</th>
                            <th>Effect</th>
                            <th>Key People</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>World War I</td>
                            <td>1914–1918</td>
                            <td>Assassination of Archduke Ferdinand</td>
                            <td>Formation of alliances and massive casualties</td>
                            <td>Germany, Austria-Hungary, Serbia</td>
                        </tr>
                        <tr>
                            <td>World War II</td>
                            <td>1939–1945</td>
                            <td>Treaty of Versailles, Rise of Hitler</td>
                            <td>Global war, creation of UN</td>
                            <td>Germany, Japan, Allies</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <h3 class="key">The Charting Method is built on three key cognitive principles:</h3>
                <li><strong>Categorization and Visual Organization:</strong> The brain processes information faster when data are structured and comparable.</li>
                <li><strong>Dual Coding Theory:</strong> Combining verbal and spatial representation to strengthen understanding and recall.</li>
                <li><strong>Cognitive Load Reduction:</strong> Minimizing cognitive load by avoiding long sentences, making revision more efficient.</li>
            </ul>
            
            <div class="quote">
                "The Charting Method transforms complex information into structured tables, making relationships and comparisons immediately visible."
                <div class="author">- Dr. John Nesbit, Educational Researcher</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">40%</div>
                    <div class="stat-text">Better Recall</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">35%</div>
                    <div class="stat-text">Improved Comprehension</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30%</div>
                    <div class="stat-text">Higher Test Scores</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Quickly identifies relationships:</strong> Easily see similarities and differences between topics.</li>
                <li><strong>Ideal for factual subjects:</strong> Perfect for History, Biology, Business, Accounting and other detail-heavy subjects.</li>
                <li><strong>Makes reviewing efficient:</strong> Faster and clearer memorization through visual organization.</li>
                <li><strong>Reduces redundancy:</strong> No need to rewrite full sentences, saving time and effort.</li>
                <li><strong>Useful for summarizing:</strong> Excellent for condensing lectures or textbook chapters into manageable formats.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Graphic Organizers & Recall</h3>
            <ul>
                <li>Research on graphic organizers (like tables and charts) shows that students using structured visual formats have 25–40% better recall compared to linear note-takers.</li>
                <li>The Charting Method is a powerful form of visual organization that enhances memory retention.</li>
                <li>Conclusion: Structured visual formats significantly improve information recall compared to traditional note-taking.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Categorization & Schema Activation</h3>
            <ul>
                <li>Studies in educational psychology indicate that categorization improves long-term retention by activating schema networks in the brain.</li>
                <li>The column-based organization in the Charting Method directly leverages this cognitive principle.</li>
                <li>Conclusion: Organizing information into categories enhances learning by aligning with how the brain naturally processes information.</li>
            </ul>

            <h3><i class="fas fa-chalkboard-teacher"></i> Visual Note-Taking & Comprehension</h3>
            <ul>
                <li>A 2016 study (Nesbit & Adesope, Educational Psychology Review) found that visual note-takers scored significantly higher in comprehension tests.</li>
                <li>When learners use tables and charts, they process material more deeply and organize it more effectively.</li>
                <li>Conclusion: Visual organization through charting improves both comprehension and long-term retention.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Plan your categories in advance:</strong> Think about what information you'll need to compare before creating your chart.</li>
                <li><strong>Keep it simple:</strong> Avoid too many columns that might make the chart cluttered and hard to read.</li>
                <li><strong>Use abbreviations and symbols:</strong> Save space while maintaining clarity.</li>
                <li><strong>Combine with other methods:</strong> Use the Charting Method alongside Cornell or Outline Method for deeper understanding.</li>
                <li><strong>Review regularly:</strong> Scan your charts frequently to reinforce the visual patterns in your memory.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Charting Method turns notes into structured tables, making complex information easier to compare and remember. It's most powerful for factual or detail-oriented topics where relationships and categories are key.</p>
            <p>Supported by educational research, it enhances comprehension, memory, and review efficiency — making it one of the most effective note-taking strategies for structured information.</p>
            
            <div class="quote">
                "In an age of information overload, the Charting Method offers a visual approach to organizing and mastering complex factual information."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Try the Charting Method</a>
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