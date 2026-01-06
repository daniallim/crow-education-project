    document.addEventListener('DOMContentLoaded', function () {

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


        // Create floating background elements
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

        // Create multiple floating elements
        for (let i = 0; i < 5; i++) {
            createFloatingElement();
        }

        // Simple scroll animation
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

        // Initialize components
        initializeHeaderInteractions();

        // Initialize bookmark manager
        const bookmarkManager = new BookmarkManager();

    }}); // DOMContentLoaded结束

    // Bookmark Manager类定义移到外面
    class BookmarkManager {
        constructor() {
            this.bookmarks = JSON.parse(localStorage.getItem('studyMethodBookmarks')) || [];
            this.currentTab = 'all';
            this.currentCategory = 'all';
            this.initBookmarkButtons();
            this.initTabNavigation();
            this.initCategoryNavigation();
        }

        initBookmarkButtons() {
            const bookmarkButtons = document.querySelectorAll('.bookmark-btn');

            bookmarkButtons.forEach(btn => {
                const methodId = btn.getAttribute('data-method-id');
                const methodName = btn.getAttribute('data-method-name');

                // Check if already bookmarked
                if (this.isBookmarked(methodId)) {
                    this.updateButtonState(btn, true);
                }

                // Add click event
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleBookmark(methodId, methodName, btn);
                });
            });
        }

        initTabNavigation() {
            const tabButtons = document.querySelectorAll('.method-nav-btn[data-tab]');

            tabButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const tab = btn.getAttribute('data-tab');
                    this.switchTab(tab);

                    // Update active tab style
                    tabButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        }

        initCategoryNavigation() {
            const categoryButtons = document.querySelectorAll('.category-nav-btn[data-category]');

            categoryButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const category = btn.getAttribute('data-category');
                    this.switchCategory(category);

                    // Update active category style
                    categoryButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                });
            });
        }

        switchTab(tab) {
            this.currentTab = tab;
            this.filterCards();
        }

        switchCategory(category) {
            this.currentCategory = category;
            this.filterCards();
        }

        filterCards() {
            const cards = document.querySelectorAll('.method-card');
            let hasVisibleCards = false;

            cards.forEach(card => {
                const methodId = card.getAttribute('data-method-id');
                const category = card.getAttribute('data-category');

                let shouldShow = true;

                // Filter by category
                if (this.currentCategory !== 'all' && category !== this.currentCategory) {
                    shouldShow = false;
                }

                // Filter by tab
                if (shouldShow && this.currentTab === 'bookmark' && !this.isBookmarked(methodId)) {
                    shouldShow = false;
                }

                if (shouldShow) {
                    card.classList.remove('hidden');
                    hasVisibleCards = true;
                } else {
                    card.classList.add('hidden');
                }
            });

            // Show/hide empty state message
            this.toggleEmptyState(!hasVisibleCards);
        }

        toggleEmptyState(show) {
            // Remove existing empty state message
            const existingEmptyState = document.querySelector('.empty-state');
            if (existingEmptyState) {
                existingEmptyState.remove();
            }

            if (show) {
                const emptyState = document.createElement('div');
                emptyState.className = 'empty-state';

                if (this.currentTab === 'bookmark') {
                    emptyState.innerHTML = `
                        <i class="far fa-bookmark"></i>
                        <p>No bookmarked study methods yet</p>
                        <small>Click the bookmark button on study method cards to add them</small>
                    `;
                } else if (this.currentCategory !== 'all') {
                    emptyState.innerHTML = `
                        <i class="far fa-folder-open"></i>
                        <p>No methods found in this category</p>
                    `;
                } else {
                    emptyState.innerHTML = `
                        <i class="far fa-folder-open"></i>
                        <p>No content found</p>
                    `;
                }

                document.querySelector('.method-cards-container').appendChild(emptyState);
            }
        }

        isBookmarked(methodId) {
            return this.bookmarks.some(bookmark => bookmark.id === methodId);
        }

        toggleBookmark(methodId, methodName, button) {
            if (this.isBookmarked(methodId)) {
                this.removeBookmark(methodId, button);
            } else {
                this.addBookmark(methodId, methodName, button);
            }

            // If currently on bookmark tab, update display
            if (this.currentTab === 'bookmark') {
                this.filterCards();
            }
        }

        addBookmark(methodId, methodName, button) {
            this.bookmarks.push({
                id: methodId,
                name: methodName,
                date: new Date().toISOString()
            });

            this.saveBookmarks();
            this.updateButtonState(button, true);
            this.showNotification('Added to bookmarks', 'success');
        }

        removeBookmark(methodId, button) {
            this.bookmarks = this.bookmarks.filter(bookmark => bookmark.id !== methodId);
            this.saveBookmarks();
            this.updateButtonState(button, false);
            this.showNotification('Removed from bookmarks', 'info');
        }

        updateButtonState(button, isBookmarked) {
            const icon = button.querySelector('.bookmark-icon');

            if (isBookmarked) {
                button.classList.add('bookmarked');
                icon.classList.remove('far', 'fa-bookmark');
                icon.classList.add('fas', 'fa-bookmark');
            } else {
                button.classList.remove('bookmarked');
                icon.classList.remove('fas', 'fa-bookmark');
                icon.classList.add('far', 'fa-bookmark');
            }
        }

        saveBookmarks() {
            localStorage.setItem('studyMethodBookmarks', JSON.stringify(this.bookmarks));
        }

        showNotification(message, type) {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                background: ${type === 'success' ? '#4CAF50' : '#2196F3'};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                transform: translateX(100%);
                transition: transform 0.3s ease;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        getBookmarks() {
            return this.bookmarks;
        }
    }

    // 全局函数定义
function showPage(pageId) {
    // 如果pageId是链接，直接跳转
    if (pageId.includes('.php') || pageId.includes('.html')) {
        window.location.href = pageId;
        return;
    }
    
    // 原来的页面切换逻辑
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    document.getElementById(pageId).classList.add('active');

    const backButton = document.querySelector('.back-button');
    if (pageId === 'home-page') {
        backButton.style.display = 'none';
    } else {
        backButton.style.display = 'flex';
    }

    window.scrollTo(0, 0);
}
    function openMethod(methodName) {
        // Check if we have the method page in our current document
        const methodPage = document.getElementById(`${methodName}-page`);

        if (methodPage) {
            // If the page exists in our main document, show it
            showPage(`${methodName}-page`);
        } else {
            // Otherwise, open the external HTML file
           window.location.href = `${methodName}.php`;
        }
    }

    function goBack() {
        const currentPage = document.querySelector('.page.active').id;

        if (currentPage === 'methods-page') {
            showPage('home-page');
        } else if (currentPage.endsWith('-page') && currentPage !== 'home-page' && currentPage !== 'methods-page') {
            showPage('home-page');
        }
    }

