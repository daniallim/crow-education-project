document.addEventListener('DOMContentLoaded', function () {
    // Load components first, then initialize all functionality
    loadComponents().then(() => {
        // Initialize theme system
        initializeThemeSystem();
        
        // Initialize header interactions
        initializeHeaderInteractions();
        
        // Initialize sidebar interactions
        initializeSidebarInteractions();
        
        // Initialize notification system AFTER components are loaded
        initializeNotificationSystem();

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

    // 强制设置 sidebar 背景色
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.backgroundColor = '#ffffff';
        sidebar.style.backdropFilter = 'none';
        
        // 暗色主题支持
        if (document.body.classList.contains('dark-theme')) {
            sidebar.style.backgroundColor = '#1a1a2e';
        }
    }

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
            // Notification dropdown will be handled by initializeNotificationSystem
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

// Theme System with integrated auto theme - 从 index.js 复制的正确版本
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
// Notification System - Initialize AFTER components are loaded
function initializeNotificationSystem() {
    console.log("Initializing notification system...");

    // Sample notification data
    let notifications = [
        {
            id: 1,
            title: "New Assignment Available",
            message: "Web Development Project has been added to your course.",
            time: "2 hours ago",
            category: "assignment",
            icon: "fas fa-file-alt",
            iconColor: "#3498db",
            unread: true
        },
        {
            id: 2,
            title: "Course Update",
            message: "Advanced JavaScript course updated with new content.",
            time: "5 hours ago",
            category: "course",
            icon: "fas fa-book",
            iconColor: "#2ecc71",
            unread: true
        },
        {
            id: 3,
            title: "System Maintenance",
            message: "Scheduled maintenance this Saturday from 2-4 AM.",
            time: "1 day ago",
            category: "system",
            icon: "fas fa-tools",
            iconColor: "#e67e22",
            unread: true
        },
        {
            id: 4,
            title: "Upcoming Deadline",
            message: "Machine Learning Project due in 3 days.",
            time: "2 days ago",
            category: "assignment",
            icon: "fas fa-clock",
            iconColor: "#e74c3c",
            unread: false
        },
        {
            id: 5,
            title: "New Course Available",
            message: "Introduction to Data Science is now available for enrollment.",
            time: "3 days ago",
            category: "course",
            icon: "fas fa-graduation-cap",
            iconColor: "#9b59b6",
            unread: false
        }
    ];

    // DOM elements - get them AFTER components are loaded
    const notificationList = document.getElementById('notificationList');
    const markAllAsReadBtn = document.getElementById('markAllAsRead');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const settingsModal = document.getElementById('settingsModal');
    const closeSettings = document.getElementById('closeSettings');
    const notificationSettingsBtn = document.getElementById('notificationSettings');
    const deleteModal = document.getElementById('deleteModal');
    const deleteModalMessage = document.getElementById('deleteModalMessage');
    const cancelDeleteBtn = document.getElementById('cancelDelete');
    const confirmDeleteBtn = document.getElementById('confirmDelete');

    // Check if notification elements exist
    if (!notificationList) {
        console.warn('Notification elements not found - notification system disabled');
        return;
    }

    // Current filter
    let currentFilter = 'all';
    let notificationToDelete = null;

    // Render notifications based on filter
    function renderNotifications(filter = 'all') {
        notificationList.innerHTML = '';

        const filteredNotifications = filter === 'all'
            ? notifications
            : notifications.filter(notification => {
                if (filter === 'unread') return notification.unread;
                if (filter === 'system') return notification.category === 'system';
                if (filter === 'assignment') return notification.category === 'assignment';
                if (filter === 'course') return notification.category === 'course';
                return true;
            });

        if (filteredNotifications.length === 0) {
            notificationList.innerHTML = `
                <div class="notification-empty">
                    <i class="far fa-bell-slash"></i>
                    <h3>No notifications</h3>
                    <p>You're all caught up! Check back later for new notifications.</p>
                </div>
            `;
            return;
        }

        filteredNotifications.forEach(notification => {
            const notificationItem = document.createElement('div');
            notificationItem.className = `notification-item ${notification.unread ? 'unread' : ''}`;
            notificationItem.innerHTML = `
                ${notification.unread ? '<div class="notification-dot"></div>' : ''}
                <div class="notification-icon-container" style="background: ${notification.iconColor}20; color: ${notification.iconColor}">
                    <i class="${notification.icon}"></i>
                </div>
                <div class="notification-info">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-meta">
                        <div class="notification-time">
                            <i class="far fa-clock"></i> ${notification.time}
                        </div>
                        <div class="notification-category">${notification.category}</div>
                    </div>
                </div>
                <div class="notification-actions-container">
                    <div class="notification-action" data-id="${notification.id}" data-action="toggle-read">
                        <i class="fas ${notification.unread ? 'fa-envelope-open' : 'fa-envelope'}"></i>
                    </div>
                    <div class="notification-action delete" data-id="${notification.id}" data-action="delete">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            `;
            notificationList.appendChild(notificationItem);
        });

        // Add event listeners to toggle read status and delete
        document.querySelectorAll('.notification-action[data-id]').forEach(button => {
            button.addEventListener('click', function () {
                const id = parseInt(this.getAttribute('data-id'));
                const action = this.getAttribute('data-action');

                if (action === 'toggle-read') {
                    toggleReadStatus(id);
                } else if (action === 'delete') {
                    showDeleteConfirmation(id);
                }
            });
        });
    }

    // Toggle read status
    function toggleReadStatus(id) {
        const notification = notifications.find(n => n.id === id);
        if (notification) {
            notification.unread = !notification.unread;
            renderNotifications(currentFilter);
        }
    }

    // Show delete confirmation modal
    function showDeleteConfirmation(id) {
        const notification = notifications.find(n => n.id === id);
        if (notification) {
            notificationToDelete = id;
            deleteModalMessage.textContent = `Are you sure you want to delete the notification "${notification.title}"? This action cannot be undone.`;
            deleteModal.classList.add('active');
        }
    }

    // Delete notification
    function deleteNotification() {
        if (notificationToDelete) {
            notifications = notifications.filter(n => n.id !== notificationToDelete);
            renderNotifications(currentFilter);
            notificationToDelete = null;
            deleteModal.classList.remove('active');
        }
    }

    // Mark all as read
    function markAllAsRead() {
        notifications.forEach(notification => {
            notification.unread = false;
        });
        renderNotifications(currentFilter);
    }

    // Event listeners setup
    function setupEventListeners() {
        // Filter buttons
        if (filterButtons.length > 0) {
            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.getAttribute('data-filter');
                    renderNotifications(currentFilter);
                });
            });
        }

        // Mark all as read button
        if (markAllAsReadBtn) {
            markAllAsReadBtn.addEventListener('click', markAllAsRead);
        }

        // Settings modal functionality
        if (notificationSettingsBtn && settingsModal && closeSettings) {
            notificationSettingsBtn.addEventListener('click', function () {
                settingsModal.classList.add('active');
            });

            closeSettings.addEventListener('click', function () {
                settingsModal.classList.remove('active');
            });

            settingsModal.addEventListener('click', function (e) {
                if (e.target === settingsModal) {
                    settingsModal.classList.remove('active');
                }
            });
        }

        // Delete modal functionality
        if (deleteModal && cancelDeleteBtn && confirmDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', function () {
                deleteModal.classList.remove('active');
                notificationToDelete = null;
            });

            confirmDeleteBtn.addEventListener('click', deleteNotification);

            deleteModal.addEventListener('click', function (e) {
                if (e.target === deleteModal) {
                    deleteModal.classList.remove('active');
                    notificationToDelete = null;
                }
            });
        }

        // Settings toggles functionality
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                console.log(`${this.id} is now ${this.checked ? 'enabled' : 'disabled'}`);
            });
        });

        // Select settings functionality
        document.querySelectorAll('.select-setting').forEach(select => {
            select.addEventListener('change', function() {
                console.log(`${this.id} changed to ${this.value}`);
            });
        });
    }

    // Initialize notification system
    setupEventListeners();
    renderNotifications();
}