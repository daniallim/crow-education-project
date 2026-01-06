        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            
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
        1: { 
            primary: '#3498db', 
            secondary: '#2980b9',
            accent: '#e74c3c',
            success: '#2ecc71',
            light: '#ffffff',
            dark: '#2c3e50',
            gray: '#7f8c8d',
            lightGray: '#ecf0f1'
        },
        2: { 
            primary: '#0984e3', 
            secondary: '#6c5ce7',
            accent: '#e74c3c',
            success: '#2ecc71',
            light: '#ffffff',
            dark: '#2c3e50',
            gray: '#7f8c8d',
            lightGray: '#ecf0f1'
        },
        3: { 
            primary: '#00cec9', 
            secondary: '#00b894',
            accent: '#e74c3c',
            success: '#2ecc71',
            light: '#ffffff',
            dark: '#2c3e50',
            gray: '#7f8c8d',
            lightGray: '#ecf0f1'
        },
        4: { 
            primary: '#74b9ff', 
            secondary: '#a29bfe',
            accent: '#e74c3c',
            success: '#2ecc71',
            light: '#ffffff',
            dark: '#2c3e50',
            gray: '#7f8c8d',
            lightGray: '#ecf0f1'
        },
        5: { 
            primary: '#487eb0', 
            secondary: '#40739e',
            accent: '#e74c3c',
            success: '#2ecc71',
            light: '#ffffff',
            dark: '#2c3e50',
            gray: '#7f8c8d',
            lightGray: '#ecf0f1'
        }
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

        // Apply all CSS variables
        document.documentElement.style.setProperty('--primary', theme.primary);
        document.documentElement.style.setProperty('--secondary', theme.secondary);
        document.documentElement.style.setProperty('--accent', theme.accent);
        document.documentElement.style.setProperty('--success', theme.success);
        document.documentElement.style.setProperty('--light', theme.light);
        document.documentElement.style.setProperty('--dark', theme.dark);
        document.documentElement.style.setProperty('--gray', theme.gray);
        document.documentElement.style.setProperty('--light-gray', theme.lightGray);

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

            initializeApp();
    

        function initializeApp() {
            // Load completed tasks from localStorage
            loadCompletedTasks();
            
            // Add click event listeners to all status indicators
            const statusIndicators = document.querySelectorAll('.status-indicator');
            statusIndicators.forEach(indicator => {
                indicator.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event from bubbling to parent
                    toggleTask(this);
                });
            });

            // Add click event listeners to boxes for activation
            const boxes = document.querySelectorAll('.box');
            boxes.forEach(box => {
                box.addEventListener('click', function() {
                    activateBox(this);
                });
            });

            // Update progress display
            updateProgress();
        }

        function loadCompletedTasks() {
            const saved = localStorage.getItem('completedTasks');
            if (saved) {
                const completedTasks = JSON.parse(saved);
                completedTasks.forEach(taskName => {
                    const indicator = document.querySelector(`[data-task="${taskName}"]`);
                    if (indicator) {
                        indicator.classList.add('completed');
                    }
                });
            }
        }

        function saveCompletedTasks(completedTasks) {
            localStorage.setItem('completedTasks', JSON.stringify(completedTasks));
        }

        function activateBox(box) {
            // Remove active class from all boxes
            document.querySelectorAll('.box').forEach(b => {
                b.classList.remove('active');
            });
            // Add active class to clicked box
            box.classList.add('active');
        }

        function toggleTask(indicator) {
            const taskName = indicator.getAttribute('data-task');
            const isCompleted = indicator.classList.contains('completed');
            
            if (!isCompleted) {
                // Mark as completed
                indicator.classList.add('completed');
                
                // Get current completed tasks
                const saved = localStorage.getItem('completedTasks');
                const completedTasks = saved ? JSON.parse(saved) : [];
                
                // Add this task if not already there
                if (!completedTasks.includes(taskName)) {
                    completedTasks.push(taskName);
                    saveCompletedTasks(completedTasks);
                }
                
                // Show celebration
                showCompletionCelebration();
            } else {
                // Mark as not completed
                indicator.classList.remove('completed');
                
                // Get current completed tasks
                const saved = localStorage.getItem('completedTasks');
                const completedTasks = saved ? JSON.parse(saved) : [];
                
                // Remove this task
                const updatedTasks = completedTasks.filter(task => task !== taskName);
                saveCompletedTasks(updatedTasks);
            }
            
            // Update progress
            updateProgress();
        }

        function updateProgress() {
            const totalTasks = document.querySelectorAll('.status-indicator').length;
            const completedTasks = document.querySelectorAll('.status-indicator.completed').length;
            const percentage = Math.round((completedTasks / totalTasks) * 100);
            
            const progressFill = document.getElementById('progressFill');
            const progressPercentage = document.getElementById('progressPercentage');
            
            progressFill.style.width = percentage + '%';
            progressPercentage.textContent = percentage + '%';
            
            // Update progress bar color based on percentage
            if (percentage >= 80) {
                progressFill.style.background = 'linear-gradient(90deg, #2ecc71, #27ae60)';
            } else if (percentage >= 50) {
                progressFill.style.background = 'linear-gradient(90deg, #3498db, #2ecc71)';
            } else {
                progressFill.style.background = 'linear-gradient(90deg, #3498db, #2980b9)';
            }
        }

        function showCompletionCelebration() {
            const celebration = document.createElement('div');
            celebration.className = 'completion-animation';
            celebration.innerHTML = `
                <div style="text-align: center;">
                    <i class="fas fa-check-circle" style="font-size: 48px; margin-bottom: 10px;"></i>
                    <div>Task Completed!</div>
                </div>
            `;
            document.body.appendChild(celebration);
            
            // Remove after animation
            setTimeout(() => {
                celebration.remove();
            }, 1500);
        }