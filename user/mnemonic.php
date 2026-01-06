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
    <title>Mnemonic Devices - Enhance Your Memory</title>
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
            --accent: #ff9800; 
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
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
            position: relative;
            z-index: 1;
        }
        
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
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
            background-color: #f5e6ff;
            padding: 20px;
            border-left: 5px solid var(--primary);
            margin: 20px 0;
            border-radius: 8px;
            position: relative;
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
            background: #f9f2ff;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: var(--primary);
            color: white;
            font-weight: bold;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tr:hover {
            background-color: #f0f0f0;
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
            color: var(--accent);
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
            
            table {
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
            <h1>Mnemonic Devices</h1>
            <p class="tagline">Transform Your Memory with Powerful Association Techniques</p>
            <a href="#how-to-use" class="btn">Get Started Now</a>
        </div>
    </header>
    
    <div class="container">
        <section id="what-it-is">
            <h2><i class="fas fa-brain"></i> What are Mnemonic Devices?</h2>
            <ul>
                <li>A Mnemonic Device is a memory aid that helps people remember information by linking it to meaningful patterns, images, or structures.</li>
                <li>The word "Mnemonic" comes from the Greek <em>mnēmōn</em>, meaning "mindful" or "related to memory."</li>
                <li>It works by transforming abstract information into more memorable and meaningful forms through association, visualization, or organization.</li>
            </ul>
            
            <div class="example">
                <strong>Examples:</strong><br><br>
                <strong>Acronym:</strong> PEMDAS → Parentheses, Exponents, Multiplication, Division, Addition, Subtraction<br><br>
                <strong>Rhyme:</strong> "I before E, except after C."<br><br>
                <strong>Story:</strong> "My Very Educated Mother Just Served Us Noodles." → (Planets: Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptune)
            </div>
        </section>
        
        <section id="how-to-use">
            <h2><i class="fas fa-play-circle"></i> How to Use Mnemonic Devices?</h2>
            <ol>
                <li><strong>Identify the information</strong> that's hard to remember (e.g., lists, formulas, definitions).</li>
                <li><strong>Choose a suitable mnemonic type</strong> (acronym, rhyme, image, story, or song).</li>
                <li><strong>Create meaningful connections</strong> or vivid mental images.</li>
                <li><strong>Review and test your recall</strong> regularly.</li>
                <li><strong>Adjust if the mnemonic</strong> feels confusing or ineffective.</li>
            </ol>

            <div class="example">
                <strong>Examples:</strong><br><br>
                <strong>Geography:</strong> Great Lakes → "HOMES" = Huron, Ontario, Michigan, Erie, Superior<br><br>
                <strong>Math (Trigonometry):</strong> SOH CAH TOA<br>
                Sine = Opposite / Hypotenuse<br>
                Cosine = Adjacent / Hypotenuse<br>
                Tangent = Opposite / Adjacent
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-th-list"></i> Common Types of Mnemonic Devices</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Example</th>
                        <th>Explanation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Acronym Mnemonics</td>
                        <td>PEMDAS → Parentheses, Exponents, Multiplication, Division, Addition, Subtraction</td>
                        <td>Combine the first letters of words into a single term to help remember a sequence or list.</td>
                    </tr>
                    <tr>
                        <td>Acrostic Mnemonics</td>
                        <td>"My Very Educated Mother Just Served Us Noodles" → Mercury, Venus, Earth, Mars, Jupiter, Saturn, Uranus, Neptune</td>
                        <td>Create a sentence or phrase where each initial letter represents the information to remember.</td>
                    </tr>
                    <tr>
                        <td>Rhyming / Song Mnemonics</td>
                        <td>"I before E, except after C" / "Thirty days hath September..."</td>
                        <td>Use rhythm, rhyme, or melody to make information easier to recall.</td>
                    </tr>
                    <tr>
                        <td>Visualization Mnemonics</td>
                        <td>Imagine an electron as a tiny fairy spinning around the nucleus.</td>
                        <td>Turn abstract concepts into vivid mental images that the brain can store more easily.</td>
                    </tr>
                    <tr>
                        <td>Chunking Mnemonics</td>
                        <td>Phone number: 012-345-6789</td>
                        <td>Break long information into smaller "chunks" to reduce memory load.</td>
                    </tr>
                    <tr>
                        <td>Keyword Mnemonics</td>
                        <td>French word "pain" (bread) → Link it to "eating too much bread causes pain."</td>
                        <td>Use words that sound similar or have related meanings to form associations.</td>
                    </tr>
                    <tr>
                        <td>Story Mnemonics</td>
                        <td>"A dog was reading a book under the moonlight beside a car full of apples."</td>
                        <td>Connect multiple items in a logical or funny story to remember their order.</td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <section>
            <h2><i class="fas fa-cogs"></i> The Principle Behind It</h2>
            <ul>
                <li>Mnemonic devices work based on association, visualization, and chunking — key principles in cognitive psychology.</li>
                <li>The brain remembers meaningful, image-based, or organized data more easily than random facts.</li>
                <li>This method also follows Dual-Coding Theory (Paivio, 1971), which states that combining words and images activates both the verbal and visual parts of the brain — strengthening memory.</li>
                <li>By connecting new information to existing knowledge, mnemonics enhance neural pathways, making recall faster and more accurate.</li>
            </ul>
            
            <div class="quote">
                "Mnemonic devices leverage the brain's natural tendency to remember vivid, unusual, and emotionally charged information far better than abstract facts."
                <div class="author">- Dr. Alan Baddeley, Cognitive Psychologist</div>
            </div>
        </section>
        
        <section>
            <h2><i class="fas fa-chart-line"></i> Proven Benefits</h2>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">73%</div>
                    <div class="stat-text">Better Recall</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">40-60%</div>
                    <div class="stat-text">Memory Improvement</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">100%</div>
                    <div class="stat-text">For Students with Learning Difficulties</div>
                </div>
            </div>
            
            <ul>
                <li><strong>Improves Memory Retention:</strong> Mnemonics turn abstract information into meaningful forms, strengthening long-term memory.</li>
                <li><strong>Enhances Recall Speed:</strong> Organized structures allow quicker access to information during exams or presentations.</li>
                <li><strong>Makes Learning Fun:</strong> Creative use of rhymes, stories, or songs makes studying more enjoyable and engaging.</li>
                <li><strong>Reduces Cognitive Load:</strong> By grouping information into smaller chunks, the brain processes less at once — reducing overload.</li>
                <li><strong>Useful Across Subjects:</strong> Especially effective for memorizing vocabulary, formulas, dates, or sequences in subjects like languages, science, and history.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-flask"></i> Scientific Evidence & Data</h2>
            <h3><i class="fas fa-search"></i> Cognitive Psychology Research</h3>
            <ul>
                <li>Bower (1970): Participants using mnemonics recalled 73% of word lists, compared to only 17% for the control group.</li>
                <li>Bellezza (1981): Mnemonics improve memory by providing clear retrieval cues that guide recall.</li>
                <li><strong>Conclusion:</strong> Mnemonics strengthen memory by creating structured recall pathways.</li>
            </ul>

            <h3><i class="fas fa-memory"></i> Neuroscience & Dual-Coding Theory</h3>
            <ul>
                <li>Paivio (1971): Using both verbal and visual encoding increases recall rates by 40–60%.</li>
                <li>Kuhl et al. (2012): Brain scans show that mnemonic learning activates both visual and language areas, supporting long-term retention.</li>
                <li><strong>Conclusion:</strong> Combining imagery and words creates stronger, longer-lasting memories.</li>
            </ul>

            <h3><i class="fas fa-graduation-cap"></i> Education & Learning Effectiveness</h3>
            <ul>
                <li>Levin et al. (1982): Visual mnemonics improved students' vocabulary scores by 29%.</li>
                <li>Mastropieri & Scruggs (1998): Students with learning difficulties improved recall performance by nearly 100% when using mnemonics.</li>
                <li><strong>Conclusion:</strong> Mnemonics are effective for all learners — especially for academic subjects that require memorization.</li>
            </ul>

            <h3><i class="fas fa-globe-asia"></i> Local & Asian Research Context</h3>
            <ul>
                <li>Although Malaysia has limited formal research on mnemonic devices, several educational projects have explored memory-based learning strategies.</li>
                <li>Findings show that mnemonics help students improve vocabulary learning and science concept retention.</li>
                <li><strong>Conclusion:</strong> Mnemonic learning aligns with Malaysia's growing focus on active, strategy-based education.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-lightbulb"></i> Tips & Best Practices</h2>
            <ul>
                <li><strong>Keep it simple:</strong> The simpler the mnemonic, the easier it is to recall.</li>
                <li><strong>Use personal associations:</strong> The more personally meaningful it is, the better it works.</li>
                <li><strong>Combine with spaced repetition:</strong> Reviewing mnemonics over time enhances retention.</li>
                <li><strong>Avoid misleading cues:</strong> Incorrect associations can create false memories.</li>
                <li><strong>Apply selectively:</strong> Mnemonics work best for memorization, not deep understanding or analysis.</li>
            </ul>
        </section>
        
        <section>
            <h2><i class="fas fa-check-circle"></i> Summary</h2>
            <p>Mnemonic Devices are creative memory tools that transform complex or abstract information into vivid, structured, and easy-to-remember patterns. Supported by decades of cognitive and educational research, mnemonics enhance memory retention, recall speed, and enjoyment in learning. They make memorization meaningful, turning "rote learning" into "smart remembering."</p>
            
            <div class="quote">
                "In a world overflowing with information, mnemonic devices offer a structured approach to organizing knowledge and making it stick in your long-term memory."
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="#how-to-use" class="btn">Create Your First Mnemonic</a>
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