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
    
    // Chat functionality
    const chatItems = document.querySelectorAll('.chat-item');
    const sendBtn = document.getElementById('sendBtn');
    const chatInput = document.getElementById('chatInput');
    const chatMessages = document.querySelector('.chat-messages');
    const chatSearch = document.getElementById('chatSearch');
    const searchResults = document.getElementById('searchResults');
    const chatList = document.getElementById('chatList');
    const fileUploadBtn = document.getElementById('fileUploadBtn');
    const fileUploadInput = document.getElementById('fileUploadInput');
    const emojiBtn = document.getElementById('emojiBtn');
    const emojiPicker = document.getElementById('emojiPicker');

    // New Chat Modal elements
    const newChatBtn = document.getElementById('newChatBtn');
    const newChatModal = document.getElementById('newChatModal');
    const closeModal = document.getElementById('closeModal');
    const cancelNewChat = document.getElementById('cancelNewChat');
    const userSearch = document.getElementById('userSearch');
    const userList = document.getElementById('userList');
    const startNewChat = document.getElementById('startNewChat');

    // Delete Chat Modal elements
    const deleteChatModal = document.getElementById('deleteChatModal');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    const cancelDeleteChat = document.getElementById('cancelDeleteChat');
    const confirmDeleteChat = document.getElementById('confirmDeleteChat');
    const deleteChatName = document.getElementById('deleteChatName');

    // Chat Settings Menu elements
    const chatSettingsBtn = document.getElementById('chatSettingsBtn');
    const chatSettingsMenu = document.getElementById('chatSettingsMenu');
    const deleteChatBtn = document.getElementById('deleteChatBtn');

    // User Profile Modal elements
    const userProfileModal = document.getElementById('userProfileModal');
    const closeProfileModal = document.getElementById('closeProfileModal');

    // Resize functionality elements
    const chatSidebar = document.getElementById('chatSidebar');
    const chatMain = document.getElementById('chatMain');
    const resizeHandle = document.getElementById('resizeHandle');
    const expandSidebarBtn = document.getElementById('expandSidebar');
    const collapseSidebarBtn = document.getElementById('collapseSidebar');
    const resetLayoutBtn = document.getElementById('resetLayout');

    // Sample users for search
    const allUsers = [
        { id: 1, name: "Sarah Johnson", email: "sarah.johnson@example.com", avatar: "SJ" },
        { id: 2, name: "Alex Chen", email: "alex.chen@example.com", avatar: "AC" },
        { id: 3, name: "Emma Wilson", email: "emma.wilson@example.com", avatar: "EW" },
        { id: 4, name: "Mike Rodriguez", email: "mike.rodriguez@example.com", avatar: "MR" },
        { id: 5, name: "David Kim", email: "david.kim@example.com", avatar: "DK" },
        { id: 6, name: "Lisa Thompson", email: "lisa.thompson@example.com", avatar: "LT" },
        { id: 7, name: "John Smith", email: "john.smith@example.com", avatar: "JS" },
        { id: 8, name: "Anna Garcia", email: "anna.garcia@example.com", avatar: "AG" },
        { id: 9, name: "Carlos Martinez", email: "carlos.martinez@example.com", avatar: "CM" }
    ];

    // User profiles data
    const userProfiles = {
        1: {
            name: "Sarah Johnson",
            email: "sarah.johnson@example.com",
            avatar: "SJ",
            messages: 24,
            files: 3,
            joined: "15d",
            major: "Computer Science Major",
            university: "University of Technology",
            location: "New York, NY",
            lastSeen: "Today at 10:30 AM"
        },
        2: {
            name: "Alex Chen",
            email: "alex.chen@example.com",
            avatar: "AC",
            messages: 18,
            files: 2,
            joined: "22d",
            major: "Mathematics Major",
            university: "State University",
            location: "San Francisco, CA",
            lastSeen: "Yesterday at 3:45 PM"
        },
        3: {
            name: "Emma Wilson",
            email: "emma.wilson@example.com",
            avatar: "EW",
            messages: 12,
            files: 1,
            joined: "8d",
            major: "Physics Major",
            university: "Tech Institute",
            location: "Boston, MA",
            lastSeen: "2 hours ago"
        },
        4: {
            name: "Mike Rodriguez",
            email: "mike.rodriguez@example.com",
            avatar: "MR",
            messages: 8,
            files: 0,
            joined: "5d",
            major: "Engineering Major",
            university: "City College",
            location: "Chicago, IL",
            lastSeen: "Online"
        },
        5: {
            name: "David Kim",
            email: "david.kim@example.com",
            avatar: "DK",
            messages: 15,
            files: 4,
            joined: "30d",
            major: "Business Administration",
            university: "Business School",
            location: "Seattle, WA",
            lastSeen: "30 minutes ago"
        }
    };

    // Selected user for new chat
    let selectedUser = null;
    // Currently active chat
    let activeChatId = 1;
    // Resize state
    let isResizing = false;
    // Store uploaded files
    const uploadedFiles = new Map();

    // ===== RESIZE FUNCTIONALITY =====
    
    // Mouse down event for resize handle
    resizeHandle.addEventListener('mousedown', function(e) {
        isResizing = true;
        resizeHandle.classList.add('active');
        document.body.style.cursor = 'col-resize';
        document.body.style.userSelect = 'none';
        
        // Prevent text selection during resize
        e.preventDefault();
    });

    // Mouse move event for resizing
    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;
        
        const containerRect = chatSidebar.parentElement.getBoundingClientRect();
        const newWidth = e.clientX - containerRect.left;
        
        // Apply constraints
        const minWidth = 280;
        const maxWidth = 500;
        
        if (newWidth >= minWidth && newWidth <= maxWidth) {
            chatSidebar.style.width = newWidth + 'px';
        }
    });

    // Mouse up event to stop resizing
    document.addEventListener('mouseup', function() {
        if (isResizing) {
            isResizing = false;
            resizeHandle.classList.remove('active');
            document.body.style.cursor = '';
            document.body.style.userSelect = '';
            
            // Save the sidebar width
            localStorage.setItem('chatSidebarWidth', chatSidebar.style.width);
        }
    });

    // Expand sidebar
    expandSidebarBtn.addEventListener('click', function() {
        chatSidebar.style.width = '500px';
        localStorage.setItem('chatSidebarWidth', '500px');
    });

    // Collapse sidebar
    collapseSidebarBtn.addEventListener('click', function() {
        chatSidebar.style.width = '280px';
        localStorage.setItem('chatSidebarWidth', '280px');
    });

    // Reset layout
    resetLayoutBtn.addEventListener('click', function() {
        chatSidebar.style.width = '350px';
        localStorage.setItem('chatSidebarWidth', '350px');
    });

    // Load saved sidebar width
    window.addEventListener('load', function() {
        const savedWidth = localStorage.getItem('chatSidebarWidth');
        if (savedWidth) {
            chatSidebar.style.width = savedWidth;
        }
    });

    // Touch events for mobile devices
    resizeHandle.addEventListener('touchstart', function(e) {
        isResizing = true;
        resizeHandle.classList.add('active');
        document.body.style.userSelect = 'none';
        e.preventDefault();
    });

    document.addEventListener('touchmove', function(e) {
        if (!isResizing) return;
        
        const containerRect = chatSidebar.parentElement.getBoundingClientRect();
        const newWidth = e.touches[0].clientX - containerRect.left;
        
        // Apply constraints
        const minWidth = 280;
        const maxWidth = 500;
        
        if (newWidth >= minWidth && newWidth <= maxWidth) {
            chatSidebar.style.width = newWidth + 'px';
        }
        
        e.preventDefault();
    });

    document.addEventListener('touchend', function() {
        if (isResizing) {
            isResizing = false;
            resizeHandle.classList.remove('active');
            document.body.style.userSelect = '';
            
            // Save the sidebar width
            localStorage.setItem('chatSidebarWidth', chatSidebar.style.width);
        }
    });

    // ===== USER PROFILE FUNCTIONALITY =====

    // View User Profile Function
    function viewUserProfile(userId) {
        const profile = userProfiles[userId];
        if (!profile) {
            showNotification('User profile not found.');
            return;
        }

        // Populate profile data
        document.getElementById('profileAvatar').textContent = profile.avatar;
        document.getElementById('profileName').textContent = profile.name;
        document.getElementById('profileEmail').textContent = profile.email;
        document.getElementById('profileMessages').textContent = profile.messages;
        document.getElementById('profileFiles').textContent = profile.files;
        document.getElementById('profileJoined').textContent = profile.joined;

        // Update profile details
        const detailItems = document.querySelectorAll('.detail-item');
        detailItems[0].querySelector('span').textContent = profile.major;
        detailItems[1].querySelector('span').textContent = profile.university;
        detailItems[2].querySelector('span').textContent = profile.location;
        detailItems[3].querySelector('span').textContent = `Last seen: ${profile.lastSeen}`;

        // Set up action buttons
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const viewSharedFilesBtn = document.getElementById('viewSharedFilesBtn');

        sendMessageBtn.onclick = function() {
            closeUserProfileModal();
            // Find and select the chat with this user, or create a new one
            selectUser(userId);
        };

        viewSharedFilesBtn.onclick = function() {
            showNotification(`Opening shared files with ${profile.name}...`);
            // In a real app, this would open a file browser
            setTimeout(() => {
                showNotification(`No shared files found with ${profile.name}.`);
            }, 1000);
        };

        // Show the modal
        userProfileModal.classList.add('active');
    }

    // Close user profile modal
    function closeUserProfileModal() {
        userProfileModal.classList.remove('active');
    }

    // Add event listeners for the profile modal
    closeProfileModal.addEventListener('click', closeUserProfileModal);

    // Close modal when clicking outside
    userProfileModal.addEventListener('click', function(e) {
        if (e.target === userProfileModal) {
            closeUserProfileModal();
        }
    });

    // ===== CHAT FUNCTIONALITY =====

    // Open new chat modal
    newChatBtn.addEventListener('click', function () {
        newChatModal.classList.add('active');
        populateUserList();
    });

    // Close new chat modal
    function closeNewChatModal() {
        newChatModal.classList.remove('active');
        selectedUser = null;
        startNewChat.disabled = true;
        userSearch.value = '';
    }

    closeModal.addEventListener('click', closeNewChatModal);
    cancelNewChat.addEventListener('click', closeNewChatModal);

    // Close delete chat modal
    function closeDeleteChatModal() {
        deleteChatModal.classList.remove('active');
    }

    closeDeleteModal.addEventListener('click', closeDeleteChatModal);
    cancelDeleteChat.addEventListener('click', closeDeleteChatModal);

    // Toggle chat settings menu
    chatSettingsBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        chatSettingsMenu.classList.toggle('active');
    });

    // Close chat settings menu when clicking outside
    document.addEventListener('click', function () {
        chatSettingsMenu.classList.remove('active');
    });

    // Populate user list
    function populateUserList(searchTerm = '') {
        userList.innerHTML = '';

        const filteredUsers = allUsers.filter(user =>
            user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            user.email.toLowerCase().includes(searchTerm.toLowerCase())
        );

        if (filteredUsers.length === 0) {
            userList.innerHTML = '<div class="user-item">No users found</div>';
            return;
        }

        filteredUsers.forEach(user => {
            const userItem = document.createElement('div');
            userItem.className = 'user-item';
            userItem.innerHTML = `
                <div class="user-avatar-modal">${user.avatar}</div>
                <div class="user-info-modal">
                    <div class="user-name-modal">${user.name}</div>
                    <div class="user-email-modal">${user.email}</div>
                </div>
            `;

            userItem.addEventListener('click', function () {
                // Remove selected class from all items
                document.querySelectorAll('.user-item').forEach(item => {
                    item.classList.remove('selected');
                });

                // Add selected class to clicked item
                this.classList.add('selected');

                // Enable start chat button
                selectedUser = user;
                startNewChat.disabled = false;
            });

            userList.appendChild(userItem);
        });
    }

    // Search users in modal
    userSearch.addEventListener('input', function () {
        populateUserList(this.value);
    });

    // Start new chat
    startNewChat.addEventListener('click', function () {
        if (!selectedUser) return;

        // Create a new chat item
        const newChat = {
            id: selectedUser.id, // Use actual user ID
            name: selectedUser.name,
            avatar: selectedUser.avatar,
            preview: "You started a new conversation",
            time: "Just now",
            unread: 0
        };

        // Add to chat list
        const chatItem = document.createElement('div');
        chatItem.className = 'chat-item active';
        chatItem.setAttribute('data-chat-id', newChat.id);
        chatItem.innerHTML = `
            <div class="chat-avatar">${newChat.avatar}</div>
            <div class="chat-info">
                <div class="chat-name">${newChat.name}</div>
                <div class="chat-preview">${newChat.preview}</div>
                <div class="chat-time">${newChat.time}</div>
            </div>
            <button class="chat-item-delete" title="Delete chat">
                <i class="fas fa-trash"></i>
            </button>
        `;

        // Add event listeners to the new chat item
        chatItem.addEventListener('click', function () {
            selectChatItem(this);
        });

        // Add double click for profile viewing
        chatItem.addEventListener('dblclick', function() {
            viewUserProfile(newChat.id);
        });

        // Add delete functionality to the new chat item
        const deleteBtn = chatItem.querySelector('.chat-item-delete');
        deleteBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            openDeleteChatModal(newChat.id, newChat.name);
        });

        // Remove active class from all items
        document.querySelectorAll('.chat-item').forEach(item => {
            item.classList.remove('active');
        });

        // Add to the top of the chat list
        chatList.insertBefore(chatItem, chatList.firstChild);

        // Load the conversation
        activeChatId = newChat.id;
        loadConversation(newChat.name, true);

        // Close modal
        closeNewChatModal();
    });

    // File upload functionality
    fileUploadBtn.addEventListener('click', function () {
        fileUploadInput.click();
    });

    fileUploadInput.addEventListener('change', function (e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            sendFileMessage(file);
            // Reset the input
            fileUploadInput.value = '';
        }
    });

    // Emoji picker functionality
    emojiBtn.addEventListener('click', function () {
        emojiPicker.classList.toggle('active');
    });

    // Close emoji picker when clicking outside
    document.addEventListener('click', function (e) {
        if (!emojiBtn.contains(e.target) && !emojiPicker.contains(e.target)) {
            emojiPicker.classList.remove('active');
        }
    });

    // Add emoji to input
    const emojiOptions = document.querySelectorAll('.emoji-option');
    emojiOptions.forEach(emoji => {
        emoji.addEventListener('click', function () {
            chatInput.value += this.textContent;
            chatInput.focus();
        });
    });

    // Search functionality in sidebar
    chatSearch.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase().trim();

        if (searchTerm === '') {
            searchResults.style.display = 'none';
            return;
        }

        // Filter users based on search term
        const filteredUsers = allUsers.filter(user =>
            user.name.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm)
        );

        // Display search results
        if (filteredUsers.length > 0) {
            searchResults.innerHTML = '';
            filteredUsers.forEach(user => {
                const resultItem = document.createElement('div');
                resultItem.className = 'search-result-item';
                resultItem.innerHTML = `
                    <div class="search-result-avatar">${user.avatar}</div>
                    <div class="search-result-info">
                        <div class="search-result-name">${user.name}</div>
                        <div class="search-result-preview">${user.email}</div>
                    </div>
                `;
                resultItem.addEventListener('click', function () {
                    // Select this user
                    selectUser(user.id);
                    chatSearch.value = '';
                    searchResults.style.display = 'none';
                });
                searchResults.appendChild(resultItem);
            });
            searchResults.style.display = 'block';
        } else {
            searchResults.innerHTML = '<div class="search-result-item">No users found</div>';
            searchResults.style.display = 'block';
        }
    });

    // Close search results when clicking outside
    document.addEventListener('click', function (e) {
        if (!chatSearch.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Select user by ID
    function selectUser(userId) {
        const user = allUsers.find(u => u.id === userId);
        if (!user) return;

        // Update active state
        chatItems.forEach(item => item.classList.remove('active'));

        // Create a new active chat item if needed
        const existingItem = Array.from(chatItems).find(item =>
            item.querySelector('.chat-name').textContent === user.name
        );

        if (existingItem) {
            existingItem.classList.add('active');
        } else {
            // Create new chat item
            const newChatItem = document.createElement('div');
            newChatItem.className = 'chat-item active';
            newChatItem.setAttribute('data-chat-id', userId); // Use actual user ID
            newChatItem.innerHTML = `
                <div class="chat-avatar">${user.avatar}</div>
                <div class="chat-info">
                    <div class="chat-name">${user.name}</div>
                    <div class="chat-preview">You started a new conversation</div>
                    <div class="chat-time">Just now</div>
                </div>
                <button class="chat-item-delete" title="Delete chat">
                    <i class="fas fa-trash"></i>
                </button>
            `;

            // Add event listeners to the new chat item
            newChatItem.addEventListener('click', function() {
                selectChatItem(this);
            });

            // Add double click for profile viewing
            newChatItem.addEventListener('dblclick', function() {
                viewUserProfile(userId);
            });

            // Add delete functionality to the new chat item
            const deleteBtn = newChatItem.querySelector('.chat-item-delete');
            deleteBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                openDeleteChatModal(newChatItem.getAttribute('data-chat-id'), user.name);
            });

            chatList.insertBefore(newChatItem, chatList.firstChild);
        }

        // Load the conversation
        loadConversation(user.name);
    }

    // Select chat item
    function selectChatItem(chatItem) {
        chatItems.forEach(i => i.classList.remove('active'));
        chatItem.classList.add('active');

        // Update active chat ID
        activeChatId = chatItem.getAttribute('data-chat-id');

        // In a real app, this would load the conversation
        // For demo, we'll just show a loading message
        const messagesContainer = document.querySelector('.chat-messages');
        messagesContainer.innerHTML = `
            <div class="message received">
                <div class="message-text">Loading conversation...</div>
                <div class="message-time">System • Just now</div>
            </div>
        `;

        // Simulate loading delay
        setTimeout(() => {
            // Load the actual conversation (in a real app, this would come from a server)
            const chatName = chatItem.querySelector('.chat-name').textContent;
            loadConversation(chatName);
        }, 800);
    }

    // Add profile viewing functionality to various elements

    // 1. Click on chat header to view current chat user's profile
    document.querySelector('.chat-main-info').addEventListener('click', function() {
        const activeChat = document.querySelector('.chat-item.active');
        if (activeChat) {
            const chatId = activeChat.getAttribute('data-chat-id');
            viewUserProfile(parseInt(chatId));
        }
    });

    // 2. Add profile viewing to chat items (double-click)
    chatItems.forEach(item => {
        // Double click to view profile
        item.addEventListener('dblclick', function() {
            const chatId = this.getAttribute('data-chat-id');
            viewUserProfile(parseInt(chatId));
        });
    });

    // 3. Add profile viewing to search results
    document.addEventListener('click', function(e) {
        const searchResult = e.target.closest('.search-result-item');
        if (searchResult) {
            const userName = searchResult.querySelector('.search-result-name').textContent;
            const user = allUsers.find(u => u.name === userName);
            if (user) {
                viewUserProfile(user.id);
            }
        }
    });

    // 4. Add profile viewing to user list in new chat modal
    userList.addEventListener('click', function(e) {
        const userItem = e.target.closest('.user-item');
        if (userItem && !userItem.classList.contains('selected')) {
            const userName = userItem.querySelector('.user-name-modal').textContent;
            const user = allUsers.find(u => u.name === userName);
            if (user) {
                viewUserProfile(user.id);
            }
        }
    });

    // 5. Add keyboard shortcut (Ctrl+P) to view profile
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            const activeChat = document.querySelector('.chat-item.active');
            if (activeChat) {
                const chatId = activeChat.getAttribute('data-chat-id');
                viewUserProfile(parseInt(chatId));
            }
        }
    });

    // Select chat conversation
    chatItems.forEach(item => {
        item.addEventListener('click', function () {
            selectChatItem(this);
        });

        // Add delete functionality to existing chat items
        const deleteBtn = item.querySelector('.chat-item-delete');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const chatId = item.getAttribute('data-chat-id');
                const chatName = item.querySelector('.chat-name').textContent;
                openDeleteChatModal(chatId, chatName);
            });
        }
    });

    // Open delete chat modal
    function openDeleteChatModal(chatId, chatName) {
        deleteChatName.textContent = chatName;
        deleteChatModal.classList.add('active');

        // Set up confirm delete button
        confirmDeleteChat.onclick = function () {
            deleteChat(chatId);
            closeDeleteChatModal();
        };
    }

    // Delete chat functionality
    function deleteChat(chatId) {
        // Find the chat item to delete
        const chatItem = document.querySelector(`.chat-item[data-chat-id="${chatId}"]`);

        if (chatItem) {
            // If this is the active chat, clear the chat area
            if (chatItem.classList.contains('active')) {
                showNoChatSelected();
            }

            // Remove the chat item from the list
            chatItem.remove();

            // Show a confirmation message (in a real app, this would be a server call)
            showNotification(`Chat with ${chatItem.querySelector('.chat-name').textContent} has been deleted.`);
        }
    }

    // Show no chat selected screen
    function showNoChatSelected() {
        const messagesContainer = document.querySelector('.chat-messages');
        messagesContainer.innerHTML = `
            <div class="no-chat-selected">
                <div class="no-chat-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="no-chat-text">
                    <h3>No Chat Selected</h3>
                    <p>Select a conversation from the sidebar or start a new chat to begin messaging.</p>
                    <button class="start-chat-btn" id="startNewChatBtn">Start New Chat</button>
                </div>
            </div>
        `;

        // Add event listener to the new chat button
        document.getElementById('startNewChatBtn').addEventListener('click', function () {
            newChatModal.classList.add('active');
            populateUserList();
        });
    }

    // Show notification
    function showNotification(message) {
        // Create a notification element
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
        `;

        // Style the notification
        notification.style.position = 'fixed';
        notification.style.bottom = '20px';
        notification.style.right = '20px';
        notification.style.background = 'var(--success)';
        notification.style.color = 'white';
        notification.style.padding = '15px 20px';
        notification.style.borderRadius = '10px';
        notification.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
        notification.style.zIndex = '1000';
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        notification.style.transition = 'all 0.3s ease';

        document.body.appendChild(notification);

        // Animate the notification in
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 100);

        // Remove the notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(20px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Delete chat from settings menu
    deleteChatBtn.addEventListener('click', function () {
        const activeChat = document.querySelector('.chat-item.active');
        if (activeChat) {
            const chatId = activeChat.getAttribute('data-chat-id');
            const chatName = activeChat.querySelector('.chat-name').textContent;
            openDeleteChatModal(chatId, chatName);
        }
        chatSettingsMenu.classList.remove('active');
    });

    // Send message
    function sendMessage() {
        const messageText = chatInput.value.trim();
        if (messageText) {
            // Create new message element
            const messageElement = document.createElement('div');
            messageElement.className = 'message sent';
            messageElement.innerHTML = `
                <div class="message-text">${messageText}</div>
                <div class="message-time">You • Just now</div>
                <div class="message-status"><i class="fas fa-check"></i></div>
            `;

            // Add to messages container
            chatMessages.appendChild(messageElement);

            // Clear input
            chatInput.value = '';

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Update message status after a delay
            setTimeout(() => {
                messageElement.querySelector('.message-status').innerHTML = '<i class="fas fa-check-double"></i>';
            }, 1000);
        }
    }

    // Send file message with view/download functionality
    function sendFileMessage(file) {
        const fileSize = formatFileSize(file.size);
        const fileId = 'file_' + Date.now();
        
        // Store the file for download/view
        uploadedFiles.set(fileId, file);

        // Get appropriate file icon
        const fileIcon = getFileIcon(file.name);

        // Check if file is viewable (images, PDFs, text files)
        const isViewable = isFileViewable(file.name);

        // Create file preview element with view/download functionality
        const fileElement = document.createElement('div');
        fileElement.className = 'file-preview';
        
        if (isViewable) {
            // Make the entire file preview clickable for viewing
            fileElement.style.cursor = 'pointer';
            fileElement.addEventListener('click', function() {
                viewFile(fileId, file.name);
            });
            
            fileElement.innerHTML = `
                <div class="file-icon">
                    ${fileIcon}
                </div>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize}</div>
                    <div class="file-hint">Click to view • </div>
                </div>
                <button class="download-btn" data-file-id="${fileId}">
                    <i class="fas fa-download"></i>
                </button>
                <div class="message-time">You • Just now</div>
            `;
        } else {
            // For non-viewable files, just show download
            fileElement.innerHTML = `
                <div class="file-icon">
                    ${fileIcon}
                </div>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize}</div>
                </div>
                <button class="download-btn" data-file-id="${fileId}">
                    <i class="fas fa-download"></i>
                </button>
                <div class="message-time">You • Just now</div>
            `;
        }

        // Add download functionality
        const downloadBtn = fileElement.querySelector('.download-btn');
        downloadBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent triggering the view when clicking download
            downloadFile(fileId, file.name);
        });

        // Add to messages container
        chatMessages.appendChild(fileElement);

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Check if file is viewable in browser
    function isFileViewable(fileName) {
        const extension = fileName.split('.').pop().toLowerCase();
        const viewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'webp', 'bmp'];
        return viewableExtensions.includes(extension);
    }

    // View file function
    function viewFile(fileId, fileName) {
        const file = uploadedFiles.get(fileId);
        if (!file) {
            showNotification('File not found or has been deleted.');
            return;
        }

        const extension = fileName.split('.').pop().toLowerCase();
        const fileUrl = URL.createObjectURL(file);

        // Create modal for viewing files
        const viewModal = document.createElement('div');
        viewModal.className = 'modal-overlay active';
        
        if (extension === 'pdf') {
            // PDF viewer
            viewModal.innerHTML = `
                <div class="file-viewer-modal">
                    <div class="file-viewer-header">
                        <h3>${fileName}</h3>
                        <div class="file-viewer-actions">
                            <button class="viewer-btn" id="downloadFromViewer">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="viewer-btn" id="closeViewer">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="file-viewer-content">
                        <embed src="${fileUrl}" type="application/pdf" width="100%" height="100%">
                    </div>
                </div>
            `;
        } else if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(extension)) {
            // Image viewer
            viewModal.innerHTML = `
                <div class="file-viewer-modal image-viewer">
                    <div class="file-viewer-header">
                        <h3>${fileName}</h3>
                        <div class="file-viewer-actions">
                            <button class="viewer-btn" id="downloadFromViewer">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="viewer-btn" id="closeViewer">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="file-viewer-content">
                        <img src="${fileUrl}" alt="${fileName}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                </div>
            `;
        } else if (extension === 'txt') {
            // Text file viewer
            const reader = new FileReader();
            reader.onload = function(e) {
                viewModal.innerHTML = `
                    <div class="file-viewer-modal text-viewer">
                        <div class="file-viewer-header">
                            <h3>${fileName}</h3>
                            <div class="file-viewer-actions">
                                <button class="viewer-btn" id="downloadFromViewer">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="viewer-btn" id="closeViewer">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="file-viewer-content">
                            <pre>${e.target.result}</pre>
                        </div>
                    </div>
                `;
                setupViewerModal(viewModal, fileUrl, fileName);
            };
            reader.readAsText(file);
            document.body.appendChild(viewModal);
            return;
        }

        document.body.appendChild(viewModal);
        setupViewerModal(viewModal, fileUrl, fileName);
    }

    // Setup viewer modal event listeners
    function setupViewerModal(modal, fileUrl, fileName) {
        // Close viewer
        const closeBtn = modal.querySelector('#closeViewer');
        closeBtn.addEventListener('click', function() {
            document.body.removeChild(modal);
            URL.revokeObjectURL(fileUrl);
        });

        // Download from viewer
        const downloadBtn = modal.querySelector('#downloadFromViewer');
        downloadBtn.addEventListener('click', function() {
            const a = document.createElement('a');
            a.href = fileUrl;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });

        // Close when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                document.body.removeChild(modal);
                URL.revokeObjectURL(fileUrl);
            }
        });

        // Close with Escape key
        document.addEventListener('keydown', function closeOnEscape(e) {
            if (e.key === 'Escape') {
                document.body.removeChild(modal);
                URL.revokeObjectURL(fileUrl);
                document.removeEventListener('keydown', closeOnEscape);
            }
        });
    }

    // Download file function
    function downloadFile(fileId, fileName) {
        const file = uploadedFiles.get(fileId);
        if (!file) {
            showNotification('File not found or has been deleted.');
            return;
        }

        // Create a download link
        const url = URL.createObjectURL(file);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        showNotification(`Downloading ${fileName}...`);
    }

    // Get appropriate file icon based on file type
    function getFileIcon(fileName) {
        const extension = fileName.split('.').pop().toLowerCase();
        const iconMap = {
            'pdf': '<i class="fas fa-file-pdf" style="color: #e74c3c;"></i>',
            'doc': '<i class="fas fa-file-word" style="color: #2b579a;"></i>',
            'docx': '<i class="fas fa-file-word" style="color: #2b579a;"></i>',
            'jpg': '<i class="fas fa-file-image" style="color: #e67e22;"></i>',
            'jpeg': '<i class="fas fa-file-image" style="color: #e67e22;"></i>',
            'png': '<i class="fas fa-file-image" style="color: #3498db;"></i>',
            'gif': '<i class="fas fa-file-image" style="color: #9b59b6;"></i>',
            'zip': '<i class="fas fa-file-archive" style="color: #f39c12;"></i>',
            'rar': '<i class="fas fa-file-archive" style="color: #f39c12;"></i>',
            'txt': '<i class="fas fa-file-alt" style="color: #7f8c8d;"></i>',
            'xls': '<i class="fas fa-file-excel" style="color: #27ae60;"></i>',
            'xlsx': '<i class="fas fa-file-excel" style="color: #27ae60;"></i>',
            'ppt': '<i class="fas fa-file-powerpoint" style="color: #d35400;"></i>',
            'pptx': '<i class="fas fa-file-powerpoint" style="color: #d35400;"></i>'
        };
        
        return iconMap[extension] || '<i class="fas fa-file" style="color: var(--primary);"></i>';
    }

    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Send message on button click
    sendBtn.addEventListener('click', sendMessage);

    // Send message on Enter key (but allow Shift+Enter for new line)
    chatInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Auto-resize textarea
    chatInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Load conversation based on selected chat
    function loadConversation(chatName, isNew = false) {
        // In a real app, this would fetch messages from a server
        // For demo, we'll just show some placeholder messages
        const messages = {
            "Sarah Johnson": [
                { type: "received", user: "Sarah", time: "10:15 AM", text: "Hey, are you working on the math assignment?" },
                { type: "sent", user: "You", time: "10:18 AM", text: "Yes, I'm stuck on problem 3. It's tricky!" },
                { type: "received", user: "Sarah", time: "10:20 AM", text: "I finished that one! It requires the quadratic formula." }
            ],
            "Alex Chen": [
                { type: "received", user: "Alex", time: "Yesterday", text: "I found a great resource for calculus problems." },
                { type: "sent", user: "You", time: "Yesterday", text: "Can you share the link?" },
                { type: "received", user: "Alex", time: "Yesterday", text: "Sure, it's calculushelp.com. They have step-by-step solutions." }
            ],
            "Emma Wilson": [
                { type: "received", user: "Emma", time: "2 hours ago", text: "Hi there! How's the project going?" },
                { type: "sent", user: "You", time: "1 hour ago", text: "Going well! Just finished the research phase." }
            ]
        };

        const conversation = messages[chatName] || (isNew ? [] : [
            { type: "received", user: "System", time: "Just now", text: "This conversation is empty. Start a new conversation!" }
        ]);

        // Clear current messages
        chatMessages.innerHTML = '';

        // Add messages to the chat
        conversation.forEach(msg => {
            const messageElement = document.createElement('div');
            messageElement.className = `message ${msg.type}`;
            messageElement.innerHTML = `
                <div class="message-text">${msg.text}</div>
                <div class="message-time">${msg.user} • ${msg.time}</div>
                ${msg.type === 'sent' ? '<div class="message-status"><i class="fas fa-check-double"></i></div>' : ''}
            `;
            chatMessages.appendChild(messageElement);
        });

        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Initialize with the active conversation
    const activeChat = document.querySelector('.chat-item.active .chat-name').textContent;
    loadConversation(activeChat);
