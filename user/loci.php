<?php
// user/Memory Palace Technique.php
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
    <title>Memory Palace Technique - Master Your Memory</title>
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

        .key{
            color: #16213e;
            border-bottom: none;
        }

        .planet-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px 0;
        }
        
        .planet-item {
            text-align: center;
            margin: 10px;
            flex: 1;
            min-width: 120px;
        }
        
        .planet-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .planet-name {
            font-weight: bold;
            color: var(--primary);
        }
        
        .planet-order {
            font-size: 0.9rem;
            color: var(--gray);
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
            <h1>Memory Palace Technique</h1>
            <p class="tagline">Master Your Memory with Ancient Mnemonics</p>
            <a href="#how-to-use" class="btn">Start Building Your Palace</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-landmark"></i> What is the Memory Palace Technique?</h2>
            <ul>
                <li>The Memory Palace Technique, also known as the <mark>Method of Loci</mark>, is an ancient mnemonic strategy used to enhance memory.</li>
                <li>It originated from ancient Greece and was famously used by the philosopher <mark>Simonides of Ceos</mark>.</li>
                <li>The method works by associating information you want to remember with specific locations in a familiar place — your "palace," such as your house, school, or route to class.</li>
                <li>By mentally "walking" through these locations, you can recall the information more easily.</li>
            </ul>
            
            <div class="profile">
                <img src="../img1/simonides of ceos.png" alt="Simonides of Ceos" class="profile-img">
                <div class="profile-info">
                    <h4>Simonides of Ceos</h4>
                    <p>Ancient Greek Poet & Philosopher</p>
                    <p>"The inventor of the Method of Loci, who discovered the power of spatial memory."</p>
                </div>
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use It?</h2>
            <ol>
                <li><strong>Choose your palace</strong> - Select a familiar location — your home, classroom, or daily walking route. You must know this place very well.</li>
                <li><strong>Define a route</strong> - Decide on a clear path through your palace (for example, from the front door → living room → kitchen → bedroom).</li>
                <li><strong>Place images along the route</strong> - Mentally assign vivid, exaggerated, or funny images to each location, representing the information you want to remember.</li>
                <li><strong>Visualize vividly</strong> - Use color, movement, sound, and emotion to make each image memorable. The stronger the image, the better you'll remember.</li>
                <li><strong>Review and walk through</strong> - Mentally "walk" through your palace several times to strengthen the connections and recall the sequence of information.</li>
            </ol>

            <div class="example">
                <strong>Remembering the Planets in Order</strong>
                
                <div class="planet-list">
                    <img src="img1/planet.png">
                </div>
                
                <p><strong>You could imagine that you saw a big house. It very beautiful and has 8 rooms.</strong></p>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Main Door</div>
                            <div>You open the main door, and the heat rushes toward you.<br>
                                    Golden light fills the hall — it's dazzling and powerful.<br>
                                    <strong>Cue</strong>: The Sun = energy, light, power.<br>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 1</div>
                            <div>When you enter room 1, you saw a small, silver room that feels scorching hot.<br>
                                    You see a statue of a winged messenger running in circles.<br>
                                    <strong>Cue</strong>: Mercury = smallest and fastest planet.<br>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 2</div>
                            <div>When you enter room 2, you step into a thick golden mist.<br>
                                    It’s so hot and heavy that you can hardly breathe.<br>
                                    <strong>Cue</strong>: Venus = hottest planet, thick atmosphere.<br>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 3</div>
                            <div>When you enter room 3, the room's air is cool and fresh.<br>
                                    You hear ocean waves, birds singing, and feel peace.<br>
                                    <strong>Cue</strong>: Earth = life, water, balance.<br>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 4</div>
                            <div>When you enter room 4, you saw a red desert landscape.<br>
                                    Dust swirls around a small robot rover exploring the surface.<br>
                                    <strong>Cue</strong>: Mars = red planet, exploration.
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 5</div>
                            <div>When you enter room 5, you saw a massive orange planet fills this enormous hall.<br>
                                    You look up — a Great Red Spot storm spins endlessly.<br>
                                    <strong>Cue</strong>: Jupiter = biggest planet, giant storm.
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 6</div>
                            <div>When you enter room 6, you saw the room is elegant and glowing gold.<br>
                                    In the center floats a planet with shining golden rings.<br>
                                    <strong>Cue</strong>: Saturn = rings and beauty
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 7</div>
                            <div>When you enter room 7, you saw The room turns pale blue-green and icy cold.<br>
                                    The planet inside is tilted sideways, spinning slowly.<br>
                                    <strong>Cue</strong>: Uranus = cold, tilted, blue-green.
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-time">Room 8</div>
                            <div>You enter the final room — dark blue and stormy.<br>
                                    Waves crash around as if you're deep under the sea.<br>
                                    <strong>Cue</strong>: Neptune = deep blue, strong wind, farthest planet.
                            </div>
                        </div>
                    </div>

                </div>
                <p><strong>Important</strong>: You need good at imaging.</p>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-brain"></i> The Principle Behind It</h2>
            <ul>
                <li>The Memory Palace Technique works by connecting spatial memory (our natural ability to remember locations) with abstract information (facts, numbers, or words).</li>
                <li>Our brain's hippocampus — responsible for both spatial navigation and memory formation — becomes highly active when using this method.</li>
                <li>This is why information stored in specific spatial contexts becomes easier to recall.</li>
            </ul>
            
            <div class="quote">
                "Your brain is better at remembering places than plain facts — the Memory Palace combines both."
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">72%</div>
                    <div class="stat-text">Better Recall</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">40%</div>
                    <div class="stat-text">Improved Retention</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30-70%</div>
                    <div class="stat-text">Memory Enhancement</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Improves memory retention:</strong> Turning information into vivid, spatial images makes it stick longer in long-term memory.</li>
                <li><strong>Enhances recall speed:</strong> You can retrieve information in the exact order you placed it — ideal for speeches, lists, or study materials.</li>
                <li><strong>Boosts creativity and imagination:</strong> The technique encourages creative visualization, which activates more areas of the brain during learning.</li>
                <li><strong>Reduces rote memorization stress:</strong> Instead of repeatedly memorizing text, you "walk" through an imaginative space — making study more enjoyable.</li>
                <li><strong>Applies to many subjects:</strong> Can be used for history dates, biological processes, formulas, speeches, language vocabulary, and even presentations.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Brain Imaging Studies</h3>
            <ul>
                <li>Maguire et al. (2003, Nature Neuroscience): London taxi drivers who trained to memorize city routes had larger hippocampal volumes — showing a link between spatial memory and recall.</li>
                <li>Bower (1970, Cognitive Psychology): Participants using the Method of Loci recalled up to 72% more items than those using simple repetition.</li>
                <li>Conclusion: Spatial mapping in the brain strengthens both visual and long-term memory networks.</li>
            </ul>

            <h3><i class="fas fa-trophy"></i> Memory Competition Results</h3>
            <ul>
                <li>Top memory athletes (World Memory Championships) almost all use the Memory Palace Technique.</li>
                <li>Foer (2011) noted that champions can memorize hundreds of random numbers or decks of cards using this method — proving its real-world power.</li>
                <li>Conclusion: This technique scales effectively from daily learning to extreme memory performance.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Educational Research</h3>
            <ul>
                <li>Legge et al. (2012, Applied Cognitive Psychology): Students using Memory Palace strategies performed 30–40% better in recall tests compared to control groups.</li>
                <li>Bellezza (1996) found that combining visual imagery and spatial structure significantly improves recall accuracy and retention time.</li>
                <li>Conclusion: Educational data supports its use as a learning enhancer, not just a memory trick.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Start small:</strong> Begin with 5–10 locations before expanding.</li>
                <li><strong>Use familiar places:</strong> The more personal and detailed, the better.</li>
                <li><strong>Be creative:</strong> Funny, emotional, or exaggerated images are easier to remember.</li>
                <li><strong>Review often:</strong> Walk through your palace regularly to strengthen memory connections.</li>
                <li><strong>Build multiple palaces:</strong> Use different locations for different subjects.</li>
            </ul>
            
            <h3>Cautions</h3>
            <ul>
                <li><strong>Avoid overcrowding:</strong> Too many items in one room can confuse memory.</li>
                <li><strong>Not ideal for all learners:</strong> Visual-spatial learners benefit more; abstract thinkers may need practice.</li>
                <li><strong>Time-consuming at first:</strong> It takes time to create and visualize palaces effectively.</li>
                <li><strong>Don't rely only on it:</strong> Combine with understanding-based methods like the Feynman Technique for deep learning.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>The Memory Palace Technique is one of the most effective and scientifically supported memory systems ever developed.</p>
            <p>It transforms abstract information into vivid, spatial experiences, activating both visual and spatial parts of the brain.</p>
            <p>Research shows that this method can improve memory retention by 30–70%, enhance recall speed, and make learning more engaging.</p>
            <p>Used properly, it's not just a memory trick — it's a lifelong skill for efficient and creative learning.</p>
            
            <div class="quote">
                "Don't just memorize words — walk through your memories."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Build Your Memory Palace</a>
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