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

    console.log('DOM loaded - initializing notes app');

    // Notes Module Functionality - MOVE THIS TO THE TOP
    const notesList = document.getElementById('notesList');
    const noteEditor = document.getElementById('noteEditor');
    const editorContent = document.getElementById('editorContent');
    const emptyState = document.getElementById('emptyState');
    const createNoteBtn = document.getElementById('createNoteBtn');
    const saveNoteBtn = document.getElementById('saveNoteBtn');
    const deleteNoteBtn = document.getElementById('deleteNoteBtn');
    const noteTitle = document.getElementById('noteTitle');
    const noteContent = document.getElementById('noteContent');
    const tagInput = document.getElementById('tagInput');
    const noteTags = document.getElementById('noteTags');
    const searchNotes = document.getElementById('searchNotes');
    const toolbarBtns = document.querySelectorAll('.toolbar-btn');

    let notes = JSON.parse(localStorage.getItem('crowEducationNotes')) || [];
    let currentNoteId = null;

    // Track active formatting states
    let activeFormatting = {
        bold: false,
        italic: false,
        underline: false
    };

    // Initialize notes display
    renderNotesList();

    // Create new note
    function createNewNote() {
        console.log('Creating new note');
        const newNote = {
            id: Date.now().toString(),
            title: 'Untitled Note',
            content: '',
            tags: [],
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        };

        notes.unshift(newNote);
        saveNotes();
        renderNotesList();
        openNote(newNote.id);
    }

    // Open note for editing
    function openNote(noteId) {
        const note = notes.find(n => n.id === noteId);
        if (!note) return;

        currentNoteId = noteId;
        noteTitle.value = note.title;
        noteContent.innerHTML = note.content;

        // Render tags
        renderTags(note.tags);

        // Show editor, hide empty state
        emptyState.style.display = 'none';
        editorContent.style.display = 'block';

        // Update active state in list
        document.querySelectorAll('.note-item').forEach(item => {
            item.classList.remove('active');
            if (item.dataset.id === noteId) {
                item.classList.add('active');
            }
        });

        // Update toolbar button states based on current formatting
        updateToolbarStates();
    }

    // Update toolbar button states based on current selection
    function updateToolbarStates() {
        // Reset all formatting states
        activeFormatting = {
            bold: false,
            italic: false,
            underline: false
        };

        // Remove active class from all buttons
        toolbarBtns.forEach(btn => btn.classList.remove('active'));

        // Check current formatting state
        if (document.queryCommandState('bold')) {
            activeFormatting.bold = true;
            const boldBtn = document.querySelector('[data-command="bold"]');
            if (boldBtn) boldBtn.classList.add('active');
        }
        if (document.queryCommandState('italic')) {
            activeFormatting.italic = true;
            const italicBtn = document.querySelector('[data-command="italic"]');
            if (italicBtn) italicBtn.classList.add('active');
        }
        if (document.queryCommandState('underline')) {
            activeFormatting.underline = true;
            const underlineBtn = document.querySelector('[data-command="underline"]');
            if (underlineBtn) underlineBtn.classList.add('active');
        }
    }

    // Save current note
    function saveCurrentNote() {
        if (!currentNoteId) return;

        const noteIndex = notes.findIndex(n => n.id === currentNoteId);
        if (noteIndex === -1) return;

        notes[noteIndex].title = noteTitle.value;
        notes[noteIndex].content = noteContent.innerHTML;
        notes[noteIndex].updatedAt = new Date().toISOString();

        saveNotes();
        renderNotesList();

        // Show save confirmation
        const originalText = saveNoteBtn.innerHTML;
        saveNoteBtn.innerHTML = '<i class="fas fa-check"></i> Saved!';
        setTimeout(() => {
            saveNoteBtn.innerHTML = originalText;
        }, 2000);
    }

    // Delete current note
    function deleteCurrentNote() {
        if (!currentNoteId) return;

        if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
            notes = notes.filter(n => n.id !== currentNoteId);
            saveNotes();
            renderNotesList();

            // Reset editor
            currentNoteId = null;
            editorContent.style.display = 'none';
            emptyState.style.display = 'block';
        }
    }

    // Delete note by ID
    function deleteNoteById(noteId) {
        if (confirm('Are you sure you want to delete this note? This action cannot be undone.')) {
            notes = notes.filter(n => n.id !== noteId);
            saveNotes();
            renderNotesList();

            // If the deleted note was currently open, reset the editor
            if (currentNoteId === noteId) {
                currentNoteId = null;
                editorContent.style.display = 'none';
                emptyState.style.display = 'block';
            }
        }
    }

    // Render notes list
    function renderNotesList(filter = '') {
        if (!notesList) {
            console.error('Notes list container not found');
            return;
        }

        notesList.innerHTML = '';

        const filteredNotes = filter ?
            notes.filter(note =>
                note.title.toLowerCase().includes(filter.toLowerCase()) ||
                note.content.toLowerCase().includes(filter.toLowerCase()) ||
                note.tags.some(tag => tag.toLowerCase().includes(filter.toLowerCase()))
            ) :
            notes;

        if (filteredNotes.length === 0) {
            notesList.innerHTML = `
                <div class="empty-state" style="padding: 20px;">
                    <i class="fas fa-search" style="font-size: 40px;"></i>
                    <p>No notes found</p>
                </div>
            `;
            return;
        }

        filteredNotes.forEach(note => {
            const noteItem = document.createElement('div');
            noteItem.className = 'note-item';
            noteItem.dataset.id = note.id;

            // Create preview text (strip HTML tags)
            const preview = note.content.replace(/<[^>]*>/g, '').substring(0, 100) + '...';

            // Format date
            const date = new Date(note.updatedAt);
            const formattedDate = date.toLocaleDateString();

            noteItem.innerHTML = `
                <div class="note-content-wrapper">
                    <div class="note-title">${note.title}</div>
                    <div class="note-preview">${preview}</div>
                    <div class="note-meta">
                        <span>${formattedDate}</span>
                        <span>${note.tags.length} tags</span>
                    </div>
                </div>
                <div class="note-actions">
                    <button class="delete-note-btn" title="Delete Note">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            // Add click event to open note
            noteItem.querySelector('.note-content-wrapper').addEventListener('click', () => openNote(note.id));

            // Add click event to delete button
            const deleteBtn = noteItem.querySelector('.delete-note-btn');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent opening the note when clicking delete
                    deleteNoteById(note.id);
                });
            }

            notesList.appendChild(noteItem);

            // Set active state if this is the current note
            if (note.id === currentNoteId) {
                noteItem.classList.add('active');
            }
        });
    }

    // Render tags
    function renderTags(tags) {
        if (!noteTags) return;
        noteTags.innerHTML = '';
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.className = 'note-tag';
            tagElement.textContent = tag;
            noteTags.appendChild(tagElement);
        });
    }

    // Add tag from input
    function addTags() {
        if (!currentNoteId) return;

        const tagText = tagInput.value.trim();
        if (!tagText) return;

        const newTags = tagText.split(',').map(tag => tag.trim()).filter(tag => tag);

        const noteIndex = notes.findIndex(n => n.id === currentNoteId);
        if (noteIndex === -1) return;

        newTags.forEach(tag => {
            if (!notes[noteIndex].tags.includes(tag)) {
                notes[noteIndex].tags.push(tag);
            }
        });

        tagInput.value = '';
        renderTags(notes[noteIndex].tags);
        saveNotes();
        renderNotesList();
    }

    // Save notes to localStorage
    function saveNotes() {
        localStorage.setItem('crowEducationNotes', JSON.stringify(notes));
    }

    // Toolbar button functionality with persistent highlighting
    toolbarBtns.forEach(button => {
        button.addEventListener('click', function () {
            const command = this.dataset.command;

            if (command === 'createLink') {
                const url = prompt('Enter the URL:');
                if (url) {
                    // Create a proper link with visible URL
                    const selection = window.getSelection();
                    if (selection.rangeCount > 0) {
                        const range = selection.getRangeAt(0);
                        const linkElement = document.createElement('a');
                        linkElement.href = url;
                        linkElement.textContent = url;
                        linkElement.target = '_blank';
                        linkElement.style.color = 'var(--primary)';
                        linkElement.style.textDecoration = 'underline';
                        range.deleteContents();
                        range.insertNode(linkElement);

                        // Move cursor after the link
                        const newRange = document.createRange();
                        newRange.setStartAfter(linkElement);
                        newRange.collapse(true);
                        selection.removeAllRanges();
                        selection.addRange(newRange);
                    }
                }
            } else {
                // Toggle the formatting command
                document.execCommand(command, false, null);

                // Update the button state for formatting commands
                if (['bold', 'italic', 'underline'].includes(command)) {
                    const isActive = document.queryCommandState(command);
                    if (isActive) {
                        this.classList.add('active');
                        activeFormatting[command] = true;
                    } else {
                        this.classList.remove('active');
                        activeFormatting[command] = false;
                    }
                }
            }

            noteContent.focus();
        });
    });

    // Update toolbar states when selection changes
    if (noteContent) {
        noteContent.addEventListener('input', function () {
            updateToolbarStates();
        });

        noteContent.addEventListener('click', function () {
            updateToolbarStates();
        });

        noteContent.addEventListener('keyup', function () {
            updateToolbarStates();
        });
    }

    // Event listeners for notes functionality
    if (createNoteBtn) {
        createNoteBtn.addEventListener('click', createNewNote);
    } else {
        console.error('Create note button not found');
    }

    if (saveNoteBtn) {
        saveNoteBtn.addEventListener('click', saveCurrentNote);
    }

    if (deleteNoteBtn) {
        deleteNoteBtn.addEventListener('click', deleteCurrentNote);
    }

    if (tagInput) {
        tagInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                addTags();
            }
        });
    }

    if (searchNotes) {
        searchNotes.addEventListener('input', function () {
            renderNotesList(this.value);
        });
    }

    // Auto-save when typing (with debounce)
    let saveTimeout;
    if (noteTitle) {
        noteTitle.addEventListener('input', function () {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveCurrentNote, 1000);
        });
    }

    if (noteContent) {
        noteContent.addEventListener('input', function () {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveCurrentNote, 1000);
        });
    }

    // Add sample notes if none exist
    if (notes.length === 0) {
        console.log('Adding sample notes');
        notes = [
            {
                id: '1',
                title: 'Welcome to Notes',
                content: '<p>This is your first note! You can edit this text, add formatting, and organize your thoughts.</p><p>Try creating a new note or editing this one to get started.</p>',
                tags: ['welcome', 'getting-started'],
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            },
            {
                id: '2',
                title: 'Study Tips',
                content: '<p>Here are some effective study techniques:</p><ul><li>Pomodoro Technique - 25 min focus, 5 min break</li><li>Spaced Repetition for memorization</li><li>Active recall instead of passive reading</li></ul>',
                tags: ['study', 'tips', 'productivity'],
                createdAt: new Date().toISOString(),
                updatedAt: new Date().toISOString()
            }
        ];
        saveNotes();
        renderNotesList();
    }

    // Theme switching functionality (keep this at the end)
    const themeToggle = document.getElementById('themeToggle');
    const themeSelector = document.getElementById('themeSelector');
    const themeOptions = document.querySelectorAll('.theme-option');
    const autoThemeToggle = document.getElementById('autoThemeToggle');

    // Define blue themes
    const themes = {
        1: {
            primary: '#3498db',
            secondary: '#2980b9'
        },
        2: {
            primary: '#0984e3',
            secondary: '#6c5ce7'
        },
        3: {
            primary: '#00cec9',
            secondary: '#00b894'
        },
        4: {
            primary: '#74b9ff',
            secondary: '#a29bfe'
        },
        5: {
            primary: '#487eb0',
            secondary: '#40739e'
        }
    };

    let autoThemeInterval;
    let isAutoThemeActive = false;

    // Toggle theme selector
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            if (themeSelector) {
                themeSelector.classList.toggle('active');
                this.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }
        });
    }

    // Apply theme
    function applyTheme(themeId) {
        const theme = themes[themeId];
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
        if (autoThemeToggle) {
            autoThemeToggle.classList.toggle('active', isAutoThemeActive);
        }

        if (isAutoThemeActive) {
            // Start auto theme cycling
            let currentTheme = 1;
            autoThemeInterval = setInterval(() => {
                currentTheme = currentTheme % 5 + 1;
                applyTheme(currentTheme);
            }, 3000); // Change theme every 3 seconds
        } else {
            // Stop auto theme cycling
            clearInterval(autoThemeInterval);
        }
    }

    // Auto theme toggle click handler
    if (autoThemeToggle) {
        autoThemeToggle.addEventListener('click', toggleAutoTheme);
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('crowEducationTheme') || '1';
    applyTheme(savedTheme);

    // Dark/Light mode toggle
    const darkModeToggle = document.querySelector('.theme-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function () {
            document.body.classList.toggle('dark-theme');
            const isDark = document.body.classList.contains('dark-theme');
            this.innerHTML = isDark ? '<i class="fas fa-sun"></i>' : '<i class="fas fa-moon"></i>';
            localStorage.setItem('crowEducationDarkMode', isDark);
        });

        // Load dark mode preference
        const darkModePreference = localStorage.getItem('crowEducationDarkMode');
        if (darkModePreference === 'true') {
            document.body.classList.add('dark-theme');
            darkModeToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
    }

    // Header icon interactions
    const notificationIcon = document.querySelector('.notification-icon');
    if (notificationIcon) {
        notificationIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            alert('You have 5 new notifications!');
        });
    }

    const settingsIcon = document.querySelector('.settings-icon');
    if (settingsIcon) {
        settingsIcon.addEventListener('click', function () {
            this.style.transform = 'scale(0.9)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            alert('Settings panel would open here!');
        });
    }

    const userProfile = document.querySelector('.user-profile');
    if (userProfile) {
        userProfile.addEventListener('click', function () {
            this.style.transform = 'translateY(-3px) scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'translateY(-3px) scale(1)';
            }, 150);
            alert('Profile menu would open here!');
        });
    }

    // Add floating elements dynamically
    const floatingContainer = document.querySelector('.floating-elements');
    if (floatingContainer) {
        const colors = [
            'rgba(52, 152, 219, 0.1)',
            'rgba(41, 128, 185, 0.1)',
            'rgba(142, 68, 173, 0.1)',
            'rgba(52, 73, 94, 0.1)'
        ];

        for (let i = 0; i < 12; i++) {
            const element = document.createElement('div');
            element.classList.add('floating-element');

            const size = Math.random() * 80 + 20;
            const top = Math.random() * 100;
            const left = Math.random() * 100;
            const color = colors[Math.floor(Math.random() * colors.length)];
            const delay = Math.random() * 10;
            const duration = Math.random() * 20 + 15;

            element.style.width = `${size}px`;
            element.style.height = `${size}px`;
            element.style.top = `${top}%`;
            element.style.left = `${left}%`;
            element.style.background = color;
            element.style.animationDelay = `${delay}s`;
            element.style.animationDuration = `${duration}s`;

            floatingContainer.appendChild(element);
        }
    }