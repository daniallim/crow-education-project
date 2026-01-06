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

            // Schedule Module Functionality
            const scheduleModule = document.getElementById('scheduleModule');
            const monthView = document.getElementById('monthView');
            const weekView = document.getElementById('weekView');
            const dayView = document.getElementById('dayView');
            const weekGrid = document.getElementById('weekGrid');
            const dayGrid = document.getElementById('dayGrid');
            const currentPeriodElement = document.getElementById('currentPeriod');
            const dayTitleElement = document.getElementById('dayTitle');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const viewButtons = document.querySelectorAll('.view-btn');
            const createEventBtn = document.getElementById('createEventBtn');
            const todayBtn = document.getElementById('todayBtn');
            const eventModal = document.getElementById('eventModal');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelEventBtn = document.getElementById('cancelEvent');
            const saveEventBtn = document.getElementById('saveEvent');
            const deleteEventBtn = document.getElementById('deleteEvent');
            const modalTitle = document.getElementById('modalTitle');
            const eventTitle = document.getElementById('eventTitle');
            const eventDate = document.getElementById('eventDate');
            const eventTime = document.getElementById('eventTime');
            const eventDescription = document.getElementById('eventDescription');
            const colorOptions = document.querySelectorAll('.color-option');

            // Calendar state
            let currentDate = new Date();
            let currentView = 'month';
            let events = JSON.parse(localStorage.getItem('scheduleEvents')) || [];
            let selectedEvent = null;
            let selectedDate = null;

            // Helper function to format date as YYYY-MM-DD
            function getFormattedDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Initialize the schedule module
            function initScheduleModule() {
                scheduleModule.style.display = 'block';
                renderCurrentView();
            }

            // Render the current view (month, week, or day)
            function renderCurrentView() {
                switch (currentView) {
                    case 'month':
                        renderMonthView();
                        break;
                    case 'week':
                        renderWeekView();
                        break;
                    case 'day':
                        renderDayView();
                        break;
                }
            }

            // Render month view
            function renderMonthView() {
                const year = currentDate.getFullYear();
                const month = currentDate.getMonth();

                // Update period display
                currentPeriodElement.textContent = currentDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });

                // Show month view, hide others
                monthView.style.display = 'grid';
                weekView.style.display = 'none';
                dayView.style.display = 'none';

                // Clear the calendar grid
                monthView.innerHTML = '';

                // Add day headers
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                days.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'calendar-day-header';
                    dayHeader.textContent = day;
                    monthView.appendChild(dayHeader);
                });

                // Get first day of month and last day of month
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);

                // Calculate days from previous month to show
                const startDay = firstDay.getDay();

                // Calculate days from next month to show
                const daysInMonth = lastDay.getDate();
                const totalCells = Math.ceil((startDay + daysInMonth) / 7) * 7;

                // Create calendar days
                for (let i = 0; i < totalCells; i++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';

                    // Calculate the actual date for this cell
                    const dayNumber = i - startDay + 1;
                    let cellDate;

                    // Check if this day is in the current month
                    if (i < startDay) {
                        // Previous month
                        dayElement.classList.add('other-month');
                        const prevMonth = new Date(year, month, 0);
                        cellDate = new Date(year, month - 1, prevMonth.getDate() - startDay + i + 1);
                    } else if (dayNumber > daysInMonth) {
                        // Next month
                        dayElement.classList.add('other-month');
                        cellDate = new Date(year, month + 1, dayNumber - daysInMonth);
                    } else {
                        // Current month
                        cellDate = new Date(year, month, dayNumber);

                        // Check if this is today
                        const today = new Date();
                        if (cellDate.toDateString() === today.toDateString()) {
                            dayElement.classList.add('today');
                        }
                    }

                    const dateStr = getFormattedDate(cellDate);
                    dayElement.setAttribute('data-date', dateStr);

                    // Add day number
                    const dayNumberElement = document.createElement('div');
                    dayNumberElement.className = 'day-number';
                    dayNumberElement.textContent = cellDate.getDate();
                    dayElement.appendChild(dayNumberElement);

                    // Add events for this day
                    const dayEvents = events.filter(event => event.date === dateStr);
                    dayEvents.forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = `event ${event.type}`;
                        eventElement.textContent = event.title;
                        eventElement.setAttribute('data-id', event.id);
                        dayElement.appendChild(eventElement);

                        // Add click event to edit
                        eventElement.addEventListener('click', function (e) {
                            e.stopPropagation();
                            openEventModal(event);
                        });
                    });

                    // Add click event to create new event
                    dayElement.addEventListener('click', function () {
                        const date = this.getAttribute('data-date');
                        openEventModal(null, date);
                    });

                    // Add quick add button
                    const addButton = document.createElement('div');
                    addButton.className = 'add-event-btn';
                    addButton.innerHTML = '<i class="fas fa-plus"></i>';
                    addButton.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const date = dayElement.getAttribute('data-date');
                        openEventModal(null, date);
                    });
                    dayElement.appendChild(addButton);

                    monthView.appendChild(dayElement);
                }
            }

            // Render week view
            function renderWeekView() {
                // Update period display
                const weekStart = new Date(currentDate);
                weekStart.setDate(currentDate.getDate() - currentDate.getDay());
                const weekEnd = new Date(weekStart);
                weekEnd.setDate(weekStart.getDate() + 6);

                currentPeriodElement.textContent =
                    `${weekStart.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ` +
                    `${weekEnd.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;

                // Show week view, hide others
                monthView.style.display = 'none';
                weekView.style.display = 'block';
                dayView.style.display = 'none';

                // Clear week view
                const weekHeader = document.querySelector('.week-header');
                while (weekHeader.children.length > 1) {
                    weekHeader.removeChild(weekHeader.lastChild);
                }
                weekGrid.innerHTML = '';

                // Add day headers
                const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                for (let i = 0; i < 7; i++) {
                    const dayDate = new Date(weekStart);
                    dayDate.setDate(weekStart.getDate() + i);

                    const dayHeader = document.createElement('div');
                    dayHeader.className = 'week-day-header';
                    dayHeader.innerHTML = `
                        <div>${days[i]}</div>
                        <div>${dayDate.getDate()}</div>
                    `;
                    weekHeader.appendChild(dayHeader);
                }

                // Add time slots (8 AM to 8 PM)
                for (let hour = 8; hour <= 20; hour++) {
                    // Time label
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'week-time-slot';
                    timeSlot.textContent = `${hour % 12 === 0 ? 12 : hour % 12} ${hour < 12 ? 'AM' : 'PM'}`;
                    weekGrid.appendChild(timeSlot);

                    // Day slots
                    for (let i = 0; i < 7; i++) {
                        const dayDate = new Date(weekStart);
                        dayDate.setDate(weekStart.getDate() + i);
                        const dateStr = getFormattedDate(dayDate);

                        const daySlot = document.createElement('div');
                        daySlot.className = 'week-day-slot';
                        daySlot.setAttribute('data-date', dateStr);
                        daySlot.setAttribute('data-hour', hour);

                        // Add click event to create new event
                        daySlot.addEventListener('click', function () {
                            const date = this.getAttribute('data-date');
                            const hour = this.getAttribute('data-hour');
                            openEventModal(null, date, `${hour}:00`);
                        });

                        // Add events for this time slot
                        const slotEvents = events.filter(event => {
                            if (event.date !== dateStr) return false;
                            if (!event.time) return false;
                            const eventHour = parseInt(event.time.split(':')[0]);
                            return eventHour === hour;
                        });

                        slotEvents.forEach(event => {
                            const eventElement = document.createElement('div');
                            eventElement.className = `week-event ${event.type}`;
                            eventElement.textContent = event.title;
                            eventElement.setAttribute('data-id', event.id);
                            eventElement.addEventListener('click', function (e) {
                                e.stopPropagation();
                                openEventModal(event);
                            });
                            daySlot.appendChild(eventElement);
                        });

                        weekGrid.appendChild(daySlot);
                    }
                }
            }

            // Render day view
            function renderDayView() {
                // Update period display
                currentPeriodElement.textContent = currentDate.toLocaleDateString('en-US', {
                    month: 'long',
                    year: 'numeric'
                });

                // Update day title
                dayTitleElement.textContent = currentDate.toLocaleDateString('en-US', {
                    weekday: 'long',
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });

                // Show day view, hide others
                monthView.style.display = 'none';
                weekView.style.display = 'none';
                dayView.style.display = 'block';

                // Clear day grid
                dayGrid.innerHTML = '';

                const dateStr = getFormattedDate(currentDate);

                // Add time slots (8 AM to 8 PM)
                for (let hour = 8; hour <= 20; hour++) {
                    const timeSlot = document.createElement('div');
                    timeSlot.className = 'day-time-slot';

                    const timeLabel = document.createElement('div');
                    timeLabel.className = 'day-time-label';
                    timeLabel.textContent = `${hour % 12 === 0 ? 12 : hour % 12} ${hour < 12 ? 'AM' : 'PM'}`;

                    const slotContent = document.createElement('div');
                    slotContent.className = 'day-slot-content';
                    slotContent.setAttribute('data-date', dateStr);
                    slotContent.setAttribute('data-hour', hour);

                    // Add click event to create new event
                    slotContent.addEventListener('click', function () {
                        const date = this.getAttribute('data-date');
                        const hour = this.getAttribute('data-hour');
                        openEventModal(null, date, `${hour}:00`);
                    });

                    // Add events for this time slot
                    const slotEvents = events.filter(event => {
                        if (event.date !== dateStr) return false;
                        if (!event.time) return false;
                        const eventHour = parseInt(event.time.split(':')[0]);
                        return eventHour === hour;
                    });

                    slotEvents.forEach(event => {
                        const eventElement = document.createElement('div');
                        eventElement.className = `day-event ${event.type}`;
                        eventElement.innerHTML = `
                            <div class="event-details">
                                <div class="event-title">${event.title}</div>
                                <div class="event-time">${event.time}</div>
                            </div>
                            ${event.description ? `<div class="event-desc">${event.description}</div>` : ''}
                        `;
                        eventElement.addEventListener('click', function (e) {
                            e.stopPropagation();
                            openEventModal(event);
                        });
                        slotContent.appendChild(eventElement);
                    });

                    timeSlot.appendChild(timeLabel);
                    timeSlot.appendChild(slotContent);
                    dayGrid.appendChild(timeSlot);
                }
            }

            // Navigate to today
            function goToToday() {
                currentDate = new Date();
                renderCurrentView();
            }

            // Change period (month, week, or day)
            function changePeriod(direction) {
                switch (currentView) {
                    case 'month':
                        currentDate.setMonth(currentDate.getMonth() + direction);
                        break;
                    case 'week':
                        currentDate.setDate(currentDate.getDate() + (direction * 7));
                        break;
                    case 'day':
                        currentDate.setDate(currentDate.getDate() + direction);
                        break;
                }
                renderCurrentView();
            }

            // Change view (month, week, or day)
            function changeView(view) {
                currentView = view;
                viewButtons.forEach(btn => {
                    btn.classList.toggle('active', btn.getAttribute('data-view') === view);
                });
                renderCurrentView();
            }

            // Open event modal
            function openEventModal(event = null, date = null, time = null) {
                selectedEvent = event;
                selectedDate = date;

                if (event) {
                    // Editing existing event
                    modalTitle.textContent = 'Edit Event';
                    eventTitle.value = event.title;
                    eventDate.value = event.date;
                    eventTime.value = event.time;
                    eventDescription.value = event.description || '';

                    // Set event type
                    colorOptions.forEach(option => {
                        option.classList.remove('active');
                        if (option.dataset.type === event.type) {
                            option.classList.add('active');
                        }
                    });

                    // Show delete button
                    deleteEventBtn.style.display = 'block';
                } else {
                    // Creating new event
                    modalTitle.textContent = 'Create New Event';
                    eventTitle.value = '';
                    eventDate.value = date || getFormattedDate(new Date());
                    eventTime.value = time || '09:00';
                    eventDescription.value = '';

                    // Reset to default type
                    colorOptions.forEach(option => {
                        option.classList.remove('active');
                        if (option.dataset.type === 'default') {
                            option.classList.add('active');
                        }
                    });

                    // Hide delete button
                    deleteEventBtn.style.display = 'none';
                }

                // Show modal
                eventModal.classList.add('active');
            }

            // Close event modal
            function closeEventModal() {
                eventModal.classList.remove('active');
                selectedEvent = null;
                selectedDate = null;
            }

            // Save event
            function saveEvent() {
                const title = eventTitle.value.trim();
                const date = eventDate.value;
                const time = eventTime.value;
                const description = eventDescription.value.trim();

                // Get selected event type
                let eventType = 'default';
                colorOptions.forEach(option => {
                    if (option.classList.contains('active')) {
                        eventType = option.dataset.type;
                    }
                });

                if (!title) {
                    alert('Please enter an event title');
                    return;
                }

                if (selectedEvent) {
                    // Update existing event
                    selectedEvent.title = title;
                    selectedEvent.date = date;
                    selectedEvent.time = time;
                    selectedEvent.description = description;
                    selectedEvent.type = eventType;
                } else {
                    // Create new event
                    const newEvent = {
                        id: Date.now().toString(),
                        title,
                        date,
                        time,
                        description,
                        type: eventType
                    };
                    events.push(newEvent);
                }

                // Save to localStorage
                localStorage.setItem('scheduleEvents', JSON.stringify(events));

                // Refresh calendar
                renderCurrentView();

                // Close modal
                closeEventModal();
            }

            // Delete event
            function deleteEvent() {
                if (selectedEvent && confirm('Are you sure you want to delete this event?')) {
                    events = events.filter(event => event.id !== selectedEvent.id);
                    localStorage.setItem('scheduleEvents', JSON.stringify(events));
                    renderCurrentView();
                    closeEventModal();
                }
            }

            // Set up event listeners for schedule module
            function setupScheduleEventListeners() {
                // Navigation
                prevBtn.addEventListener('click', () => changePeriod(-1));
                nextBtn.addEventListener('click', () => changePeriod(1));
                todayBtn.addEventListener('click', goToToday);

                // View buttons
                viewButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        changeView(btn.getAttribute('data-view'));
                    });
                });

                // Create event button
                createEventBtn.addEventListener('click', () => {
                    openEventModal();
                });

                // Event modal
                closeModalBtn.addEventListener('click', closeEventModal);
                cancelEventBtn.addEventListener('click', closeEventModal);
                saveEventBtn.addEventListener('click', saveEvent);
                deleteEventBtn.addEventListener('click', deleteEvent);

                // Color options
                colorOptions.forEach(option => {
                    option.addEventListener('click', function () {
                        colorOptions.forEach(opt => opt.classList.remove('active'));
                        this.classList.add('active');
                    });
                });

                // Close modal when clicking outside
                eventModal.addEventListener('click', function (e) {
                    if (e.target === this) {
                        closeEventModal();
                    }
                });
            }

            // Initialize the schedule
            initScheduleModule();
            setupScheduleEventListeners();
    