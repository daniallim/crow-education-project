// dashboard.js - Dashboard page specific functionality

// Dashboard Features
function initializeDashboardFeatures() {
    console.log("Initializing dashboard features...");

    // Add staggered animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
    });
}

// Motivation Quotes
function initializeMotivationQuotes() {
    console.log("Initializing motivation quotes...");

    const quotes = [
        "\"The mind is not a vessel to be filled, but a fire to be kindled.\" - Plutarch",
        "\"Education is the most powerful weapon which you can use to change the world.\" - Nelson Mandela",
        "\"Live as if you were to die tomorrow. Learn as if you were to live forever.\" - Mahatma Gandhi",
        "\"The expert in anything was once a beginner.\" - Helen Hayes",
        "\"The only way to do great work is to love what you do.\" - Steve Jobs",
        "\"Be yourself; everyone else is already taken.\" - Oscar Wilde",
        "\"You have to work hard in the dark to shine in the light.\" - Kobe Bryant"
    ];

    const quoteElement = document.getElementById("motivation-quote");

    if (quoteElement) {
        let currentQuoteIndex = 0;

        // Set initial quote
        quoteElement.innerText = quotes[currentQuoteIndex];
        quoteElement.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

        function showNextQuote() {
            quoteElement.style.transform = 'translateY(20px)';
            quoteElement.style.opacity = '0';

            setTimeout(() => {
                currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
                quoteElement.innerText = quotes[currentQuoteIndex];
                quoteElement.style.transform = 'translateY(0)';
                quoteElement.style.opacity = '1';
            }, 400);
        }

        // Change quote every 5 seconds
        setInterval(showNextQuote, 5000);
    }
}

// Dashboard Interactions
function initializeDashboardInteractions() {
    console.log("Initializing dashboard interactions...");

    // Search bar focus effect
    const searchBar = document.getElementById('searchBar');
    const searchInput = document.querySelector('.search-bar input');

    if (searchBar && searchInput) {
        searchInput.addEventListener('focus', function () {
            searchBar.style.transform = 'translateY(-5px)';
            searchBar.style.boxShadow = '0 0 0 3px rgba(52, 152, 219, 0.3), 0 15px 30px rgba(0, 0, 0, 0.15)';
        });

        searchInput.addEventListener('blur', function () {
            searchBar.style.transform = 'translateY(0)';
            searchBar.style.boxShadow = 'var(--shadow)';
        });
    }

    // Action button functionality
    const actionButtons = document.querySelectorAll('.action-btn');
    actionButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.stopPropagation();
            const buttonText = this.textContent.trim();
            
            // Button click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);

            // Navigation logic
            handleButtonNavigation(buttonText, this);
        });
    });

    // Card click effects
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Don't trigger if clicking on action buttons
            if (!e.target.closest('.action-btn')) {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);

                const cardTitle = this.querySelector('h3')?.textContent;
                handleCardNavigation(cardTitle);
            }
        });
    });
}

// Navigation handlers
function handleButtonNavigation(buttonText, buttonElement) {
    const pageMap = {
        'View Classes': 'class.php',
        'View Courses': 'course.php',
        'View Notifications': 'notification.php',
        'View Schedule': 'schedule.php',
        'View Community': 'community.php',
        'View Chat': 'chat.php',
        'View Notes': 'notes.php',
        'View Music': 'music.php',
        'View Knowledge Hub': 'knowledge.php',
        'View Milestones': 'milestone.php',
        'Enroll Now': 'course.php',
        'Chat Now': 'chat.php',
        'Create Now': 'notes.php'
    };
    
    const targetPage = pageMap[buttonText];
    if (targetPage) {
        window.location.href = targetPage;
    }
}

function handleCardNavigation(cardTitle) {
    if (!cardTitle) return;
    
    const pageMap = {
        'Class': 'class.php',
        'Course Finder': 'course.php',
        'Notification': 'notification.php',
        'Schedule': 'schedule.php',
        'Discussion Hub': 'community.php',
        'Chat': 'chat.php',
        'Note': 'notes.php',
        'Music': 'music.php',
        'Knowledge Hub': 'knowledge.php',
        'Milestones': 'milestone.php',
        'Study Method': '#' // placeholder
    };
    
    const targetPage = pageMap[cardTitle];
    if (targetPage && targetPage !== '#') {
        window.location.href = targetPage;
    }
}

// Dynamic component loading (if needed)
function loadDashboardComponents() {
    return new Promise((resolve) => {
        const loadPromises = [];
        
        // Load header if not already present
        if (!document.querySelector('.header')) {
            const headerPromise = fetch('header.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header-container').innerHTML = data;
                });
            loadPromises.push(headerPromise);
        }
        
        // Load sidebar if not already present
        if (!document.querySelector('.sidebar')) {
            const sidebarPromise = fetch('sidebar.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('sidebar-container').innerHTML = data;
                });
            loadPromises.push(sidebarPromise);
        }
        
        // Load dashboard content if using dynamic loading
        if (document.getElementById('main-content') && !document.querySelector('.dashboard-grid')) {
            const dashboardPromise = fetch('dashboard-content.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('main-content').innerHTML = data;
                });
            loadPromises.push(dashboardPromise);
        }
        
        if (loadPromises.length > 0) {
            Promise.all(loadPromises).then(() => {
                resolve();
            });
        } else {
            resolve();
        }
    });
}

