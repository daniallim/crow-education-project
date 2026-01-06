    // Load components first, then initialize all functionality
    loadComponents().then(() => {
        // Initialize theme system
        initializeThemeSystem();
        
        // Initialize header interactions
        initializeHeaderInteractions();
        
        // Initialize sidebar interactions
        initializeSidebarInteractions();

        // Initialize footer interactions
        initializeFooterInteractions();
        
    });


// Load header, sidebar, and dashboard content with Promise
function loadComponents() {
    return new Promise((resolve) => {
        const loadPromises = [];
        
        // Load header
        const headerPromise = fetch('../user/header.php')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load header');
                return response.text();
            })
            .then(data => {
                document.getElementById('header-container').innerHTML = data;
                console.log('Header loaded successfully');
            })
            .catch(error => {
                console.error('Error loading header:', error);
                document.getElementById('header-container').innerHTML = '<div>Header failed to load</div>';
            });
        loadPromises.push(headerPromise);
        
        // Load sidebar
        const sidebarPromise = fetch('../user/sidebar.php')
            .then(response => {
                if (!response.ok) throw new Error('Failed to load sidebar');
                return response.text();
            })
            .then(data => {
                document.getElementById('sidebar-container').innerHTML = data;
                console.log('Sidebar loaded successfully');
            })
            .catch(error => {
                console.error('Error loading sidebar:', error);
                document.getElementById('sidebar-container').innerHTML = '<div>Sidebar failed to load</div>';
            });
        loadPromises.push(sidebarPromise);

        // Load footer 
        const footerPromise = fetch('footer.html')  
            .then(response => {
                if (!response.ok) throw new Error('Failed to load footer');
                return response.text();
            })
            .then(data => {
                document.getElementById('footer-container').innerHTML = data;
                console.log('Footer loaded successfully');
                initializeFooterInteractions();
            })
            .catch(error => {
                console.error('Error loading footer:', error);
                document.getElementById('footer-container').innerHTML = '<div>Footer failed to load</div>';
            });
        loadPromises.push(footerPromise);
        
        // Wait for all components to load
        Promise.all(loadPromises).then(() => {
            console.log('All components loaded');
            resolve();
        });
    });
}


function initializeFooterInteractions() {
    console.log("Initializing footer interactions...");
    
    const toggles = document.querySelectorAll(".footer-toggle");
    console.log(`Found ${toggles.length} footer toggles`);
    
    toggles.forEach(toggle => {

        toggle.replaceWith(toggle.cloneNode(true));
    });


    const newToggles = document.querySelectorAll(".footer-toggle");
    
    newToggles.forEach(toggle => {
        toggle.addEventListener("click", function() {
            console.log("Footer toggle clicked");
            const isExpanded = !this.classList.contains("active");
            this.classList.toggle("active", isExpanded);
            this.setAttribute("aria-expanded", isExpanded);
            
            const panel = this.nextElementSibling;
            if (panel) {
                if (isExpanded) {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                } else {
                    panel.style.maxHeight = null;
                }
            }
        });
    });
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

    // Mobile sidebar toggle (if needed)
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
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
            // Add notification dropdown logic here
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

    // User menu dropdown
    const userMenu = document.getElementById('userMenu');
    if (userMenu) {
        userMenu.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    }
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
        if (themeOptions) {
            themeOptions.forEach(option => {
                option.classList.remove('active');
                if (option.dataset.theme == themeId) {
                    option.classList.add('active');
                }
            });
        }

        // Save theme preference
        localStorage.setItem('crowEducationTheme', themeId);
        console.log('Applied theme:', themeId);
    }

    // Theme option click handlers
    if (themeOptions) {
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
    }

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
        
        // Save auto theme state
        localStorage.setItem('crowEducationAutoTheme', isAutoThemeActive);
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
}