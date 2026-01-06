// main.js - Unified initialization across all pages

// 首先定义所有函数
function initializeThemeSystem() {
    console.log("Initializing theme system...");

    const themeToggle = document.getElementById('themeToggle');
    const themeSelector = document.getElementById('themeSelector');
    const themeOptions = document.querySelectorAll('.theme-option');
    const autoThemeToggle = document.getElementById('autoThemeToggle');

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

        localStorage.setItem('crowEducationTheme', themeId);
        console.log('Applied theme:', themeId);
    }

    // Theme option click handlers
    if (themeOptions) {
        themeOptions.forEach(option => {
            option.addEventListener('click', function () {
                const themeId = this.dataset.theme;
                applyTheme(themeId);

                if (isAutoThemeActive) {
                    toggleAutoTheme();
                }
            });
        });
    }

    // Auto theme functionality
    function toggleAutoTheme() {
        isAutoThemeActive = !isAutoThemeActive;
        if (autoThemeToggle) {
            autoThemeToggle.classList.toggle('active', isAutoThemeActive);
        }

        if (isAutoThemeActive) {
            let currentTheme = 1;
            autoThemeInterval = setInterval(() => {
                currentTheme = currentTheme % 5 + 1;
                applyTheme(currentTheme);
            }, 3000);
        } else {
            clearInterval(autoThemeInterval);
        }
        
        localStorage.setItem('crowEducationAutoTheme', isAutoThemeActive);
    }

    // Auto theme toggle
    if (autoThemeToggle) {
        autoThemeToggle.addEventListener('click', toggleAutoTheme);
    }

    // Load saved preferences
    const savedTheme = localStorage.getItem('crowEducationTheme') || '1';
    applyTheme(savedTheme);

    const savedAutoTheme = localStorage.getItem('crowEducationAutoTheme');
    if (savedAutoTheme === 'true' && autoThemeToggle) {
        toggleAutoTheme();
    }

    // Close theme selector when clicking outside
    document.addEventListener('click', function (e) {
        if (themeSelector && !e.target.closest('.theme-toggle') && !e.target.closest('.theme-selector')) {
            themeSelector.classList.remove('active');
        }
    });
}

function initializeHeaderInteractions() {
    console.log("Initializing header interactions...");

    // Notification icon
    const notificationIcon = document.getElementById('notificationIcon');
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            toggleNotificationDropdown();
        });
    }

    // Settings icon
    const settingsIcon = document.getElementById('settingsIcon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            // Navigate to settings page
            window.location.href = '#';
        });
    }

    // User profile
    const userProfile = document.getElementById('userProfile');
    if (userProfile) {
        userProfile.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            this.style.transform = 'translateY(-3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-3px) scale(1)';
            }, 150);
            toggleProfileDropdown();
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.notification-icon') && !e.target.closest('.notification-dropdown')) {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown) dropdown.classList.remove('active');
        }
        
        if (!e.target.closest('.user-profile') && !e.target.closest('.profile-dropdown')) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.remove('active');
        }
        
        if (!e.target.closest('.theme-toggle') && !e.target.closest('.theme-selector')) {
            const themeSelector = document.getElementById('themeSelector');
            if (themeSelector) themeSelector.classList.remove('active');
        }
    });
}

function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    const themeSelector = document.getElementById('themeSelector');
    
    if (dropdown) dropdown.classList.toggle('active');
    if (profileDropdown) profileDropdown.classList.remove('active');
    if (themeSelector) themeSelector.classList.remove('active');
}

function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const themeSelector = document.getElementById('themeSelector');
    
    if (dropdown) dropdown.classList.toggle('active');
    if (notificationDropdown) notificationDropdown.classList.remove('active');
    if (themeSelector) themeSelector.classList.remove('active');
}

function initializeSidebarInteractions() {
    console.log("Initializing sidebar interactions...");

    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    const sidebar = document.querySelector('.sidebar');
    
    if (navLinks) {
        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                if (navLinks) {
                    navLinks.forEach(navLink => navLink.classList.remove('active'));
                }
                this.classList.add('active');
                
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    }

    if (sidebar) {
        sidebar.addEventListener('mouseenter', function () {
            this.style.width = '280px';
        });

        sidebar.addEventListener('mouseleave', function () {
            this.style.width = '80px';
        });
    }
}

function initializePageSpecificFeatures() {
    const currentPage = document.body.dataset.page;
    
    if (!currentPage) {
        // Fallback: detect page from URL
        const path = window.location.pathname;
        if (path.includes('class.php')) {
            initializeClassFeatures();
        } else if (path.includes('dashboard.php')) {
            initializeDashboardFeatures();
        }
        return;
    }

    switch(currentPage) {
        case 'class':
            initializeClassFeatures();
            break;
        case 'dashboard':
            initializeDashboardFeatures();
            break;
        // Add more pages as needed
    }
}

// Placeholder functions for page-specific features
function initializeClassFeatures() {
    console.log("Initializing class page features...");
    // This will be called from class.js if it exists
    if (typeof initializeClassNavigation === 'function') {
        initializeClassNavigation();
    } else {
        console.log("initializeClassNavigation not found, loading class.js features");
        // 如果class.js没有正确加载，可以在这里添加一些基本功能
    }
}

function initializeDashboardFeatures() {
    console.log("Initializing dashboard features...");
    if (typeof initializeMotivationQuotes === 'function') {
        initializeMotivationQuotes();
    }
    if (typeof initializeDashboardInteractions === 'function') {
        initializeDashboardInteractions();
    }
}

// 然后在DOM加载完成后调用
document.addEventListener('DOMContentLoaded', function () {
    console.log("Initializing main application...");
    
    // Initialize all systems in correct order
    initializeThemeSystem();
    initializeHeaderInteractions();
    initializeSidebarInteractions();
    
    // Initialize page-specific features
    initializePageSpecificFeatures();
});