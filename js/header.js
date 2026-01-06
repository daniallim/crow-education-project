document.addEventListener('DOMContentLoaded', function() {
    initializeHeaderInteractions();
    initializeThemeSystem();
});

// Header Interactions - 统一的版本
function initializeHeaderInteractions() {
    console.log("Initializing header interactions...");

    // Notification icon click effect
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

    // Settings icon click effect
    const settingsIcon = document.getElementById('settingsIcon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // 跳转到设置页面
            window.location.href = '../user/settings.php';
        });
    }

    // Profile dropdown effect - 修复版本
    const userProfile = document.getElementById('userProfile');
    if (userProfile) {
        userProfile.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            
            this.style.transform = 'translateY(-3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-3px) scale(1)';
            }, 150);
            
            // 直接跳转到profile页面，不显示下拉菜单
            goToProfile();
        });
    }

    // Logo click to go home
    const logoContainer = document.querySelector('.logo-container');
    if (logoContainer) {
        logoContainer.addEventListener('click', function () {
            window.location.href = 'dashboard.php';
        });
    }

    // 点击页面其他地方关闭下拉菜单
    document.addEventListener('click', function(e) {
        const profileDropdown = document.getElementById('profileDropdown');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        if (profileDropdown && profileDropdown.classList.contains('active')) {
            if (!e.target.closest('#userProfile') && !e.target.closest('#profileDropdown')) {
                profileDropdown.classList.remove('active');
            }
        }
        
        if (notificationDropdown && notificationDropdown.classList.contains('active')) {
            if (!e.target.closest('#notificationIcon') && !e.target.closest('#notificationDropdown')) {
                notificationDropdown.classList.remove('active');
            }
        }
    });
}

// Profile dropdown functionality
function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    const notificationDropdown = document.getElementById('notificationDropdown');
    
    if (dropdown) {
        // 关闭通知下拉菜单
        if (notificationDropdown) {
            notificationDropdown.classList.remove('active');
        }
        
        // 切换个人资料下拉菜单
        dropdown.classList.toggle('active');
    }
}

// Notification dropdown functionality
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (dropdown) {
        // 关闭个人资料下拉菜单
        if (profileDropdown) {
            profileDropdown.classList.remove('active');
        }
        
        // 切换通知下拉菜单
        dropdown.classList.toggle('active');
    } else {
        // 如果没有下拉菜单，跳转到通知页面
        window.location.href = 'notification.php';
    }
}

// 跳转到用户profile页面
function goToProfile() {
    console.log('Going to profile page...');
    window.location.href = '../user/profile.php';
}

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