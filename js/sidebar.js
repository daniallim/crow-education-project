// Sidebar Interactions
function initializeSidebarInteractions() {
    console.log("Initializing sidebar interactions...");

    // Sidebar navigation link interactions
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Remove active class from all links
            navLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Get the target page from data attribute or text content
            const targetPage = this.getAttribute('data-target') || this.querySelector('.item-text')?.textContent.trim();
            if (targetPage) {
                navigateToPage(targetPage);
            }
        });
    });

    // Sidebar hover effects
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.addEventListener('mouseenter', function () {
            this.style.width = '280px';
        });

        sidebar.addEventListener('mouseleave', function () {
            this.style.width = '80px';
        });
    }
}

// Navigation function for sidebar
function navigateToPage(pageName) {
    const pageMap = {
        'Dashboard': 'dashboard.php',
        'Classes': 'class.php',
        'Courses': 'course.php',
        'Schedule': 'schedule.php',
        'Notes': 'notes.php',
        'Community': 'community.php',
        'Chat': 'chat.php',
        'Music': 'music.php',
        'method':'method.php',
        'Knowledge': 'knowledge.php',
        'Milestones': 'milestone.php',
        'Notifications': 'notification.php',
        'aboutus': 'aboutus.php'
    };
    
    const targetPage = pageMap[pageName];
    if (targetPage) {
        console.log(`Navigating to: ${targetPage}`);
        // You can use window.location.href = targetPage; for actual navigation
        // Or load content dynamically
        loadPageContent(targetPage);
    }
}

// Dynamic page content loading
function loadPageContent(pageUrl) {
    fetch(pageUrl)
        .then(response => response.text())
        .then(data => {
            document.getElementById('main-content').innerHTML = data;
            // Re-initialize any page-specific functionality
            initializePageSpecificFeatures(pageUrl);
        })
        .catch(error => {
            console.error('Error loading page:', error);
        });
}

function initializePageSpecificFeatures(pageUrl) {
    // 完整的参数检查
    if (!pageUrl || typeof pageUrl !== 'string') {
        console.warn('Invalid pageUrl:', pageUrl);
        return;
    }
    
    try {
        // 安全地处理URL
        const parts = pageUrl.split('/');
        const fileName = parts.pop() || ''; // 确保pop()不会返回undefined
        const pageName = fileName.split('.')[0] || 'unknown';
        
        console.log(`Initializing features for page: ${pageName}`);
        
        switch(pageName) {
            case 'dashboard':
                initializeDashboardFeatures();
                initializeMotivationQuotes();
                break;
            case 'chat':
                initializeChatFeatures();
                break;
            case 'notes':
                initializeNotesFeatures();
                break;
            default:
                console.log(`No specific features for page: ${pageName}`);
        }
    } catch (error) {
        console.error('Error in initializePageSpecificFeatures:', error);
    }
}