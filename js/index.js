document.addEventListener('DOMContentLoaded', function () {
    // Load components first, then initialize functionality
    loadComponents().then(() => {
        initializeThemeSystem();
        initializeDashboardFeatures();
        initializeMotivationQuotes();
        initializeHeaderInteractions();
        initializeSidebarInteractions();
        initializeDashboardInteractions();
    });
});

// Load header, sidebar, and dashboard content with Promise
function loadComponents() {
    return new Promise((resolve) => {
        const loadPromises = [];
        
        // Load header
        const headerPromise = fetch('header.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
            });
        loadPromises.push(headerPromise);
        
        // Load sidebar
        const sidebarPromise = fetch('sidebar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
            });
        loadPromises.push(sidebarPromise);
        
        // Load dashboard content
        const dashboardPromise = fetch('dashboard.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('main-content').innerHTML = data;
            });
        loadPromises.push(dashboardPromise);

        // Load footer content
        const footerPromise = fetch('footer.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
            });
        loadPromises.push(footerPromise);
        

        // Wait for all components to load
        Promise.all(loadPromises).then(() => {
            resolve();
        });
    });
}

// Theme System with integrated auto theme
function initializeThemeSystem() {
    console.log("Initializing theme system...");

    // Theme selector functionality
    const themeToggle = document.getElementById('themeToggle');
    const themeSelector = document.getElementById('themeSelector');
    const themeOptions = document.querySelectorAll('.theme-option');
    const autoThemeSection = document.getElementById('autoThemeSection');

    const themes = {
        1: { primary: '#3498db', secondary: '#2980b9' },
        2: { primary: '#0984e3', secondary: '#6c5ce7' },
        3: { primary: '#00cec9', secondary: '#00b894' },
        4: { primary: '#74b9ff', secondary: '#a29bfe' },
        5: { primary: '#487eb0', secondary: '#40739e' }
    };

    let autoThemeInterval;
    let isAutoThemeActive = false;

    // Toggle theme selector
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            themeSelector.classList.toggle('active');
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }

    // Apply theme function
    function applyTheme(themeId) {
        const theme = themes[themeId];
        if (!theme) return;

        document.documentElement.style.setProperty('--primary', theme.primary);
        document.documentElement.style.setProperty('--secondary', theme.secondary);

        // Update active theme indicator
        themeOptions.forEach(option => {
            option.classList.remove('active');
            if (option.dataset.theme == themeId) {
                option.classList.add('active');
            }
        });

        // Save theme preference
        localStorage.setItem('crowEducationTheme', themeId);
        console.log('Applied theme:', themeId);
    }

    // Theme option click handlers
    themeOptions.forEach(option => {
        option.addEventListener('click', function () {
            const themeId = this.dataset.theme;
            applyTheme(themeId);

            // Stop auto theme if manually selecting a theme
            if (isAutoThemeActive) {
                toggleAutoTheme();
            }
        });
    });

    // Auto theme functionality
    function toggleAutoTheme() {
        isAutoThemeActive = !isAutoThemeActive;
        if (autoThemeSection) {
            autoThemeSection.classList.toggle('active', isAutoThemeActive);
        }

        if (isAutoThemeActive) {
            // Start auto theme cycling
            let currentTheme = 1;
            autoThemeInterval = setInterval(() => {
                currentTheme = currentTheme % 5 + 1;
                applyTheme(currentTheme);
            }, 3000);
        } else {
            // Stop auto theme cycling
            clearInterval(autoThemeInterval);
        }
    }

    // Auto theme section click handler
    if (autoThemeSection) {
        autoThemeSection.addEventListener('click', toggleAutoTheme);
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('crowEducationTheme') || '1';
    applyTheme(savedTheme);

    // Load auto theme state
    const savedAutoTheme = localStorage.getItem('crowEducationAutoTheme');
    if (savedAutoTheme === 'true' && autoThemeSection) {
        toggleAutoTheme();
    }

    // Save auto theme state when toggled
    if (autoThemeSection) {
        autoThemeSection.addEventListener('click', function () {
            setTimeout(() => {
                localStorage.setItem('crowEducationAutoTheme', isAutoThemeActive);
            }, 100);
        });
    }
}

// Dashboard Features
function initializeDashboardFeatures() {
    console.log("Initializing dashboard features...");

    // Add staggered animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const delay = card.getAttribute('data-delay');
        if (delay) {
            card.style.animationDelay = delay + 's';
        }
    });
}

// Header Interactions
function initializeHeaderInteractions() {
    console.log("Initializing header interactions...");

    // Notification icon click effect
    const notificationIcon = document.getElementById('notificationIcon');
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }

    // Settings icon click effect
    const settingsIcon = document.getElementById('settingsIcon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            alert('Settings panel would open here!');
        });
    }

    // Profile dropdown effect
    const userProfile = document.getElementById('userProfile');
    if (userProfile) {
        userProfile.addEventListener('click', function () {
            this.style.transform = 'translateY(-3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-3px) scale(1)';
            }, 150);
        });
    }
}

// Sidebar Interactions
function initializeSidebarInteractions() {
    console.log("Initializing sidebar interactions...");

    // Add active state to sidebar links
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
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

            if (buttonText.includes('View')) {
                alert('Opening detailed view...');
            } else if (buttonText.includes('Enroll Now')) {
                alert('Starting enrollment process...');
            } else if (buttonText.includes('Chat Now')) {
                alert('Opening chat interface...');
            } else if (buttonText.includes('Create Now')) {
                alert('Creating new note...');
            }

            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
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
                if (cardTitle) {
                    alert(`Opening ${cardTitle}...`);
                }
            }
        });
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
