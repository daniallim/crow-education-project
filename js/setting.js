 document.addEventListener('DOMContentLoaded', function () {
    
            const darkModeToggle = document.getElementById('darkModeToggle');
            const toggleIcon = darkModeToggle.querySelector('.toggle-icon');
            const toggleText = darkModeToggle.querySelector('.toggle-text');

            const isDarkMode = localStorage.getItem('darkMode') === 'true';

            if (isDarkMode) {
                document.body.classList.add('dark-mode');
                toggleIcon.classList.remove('fa-moon');
                toggleIcon.classList.add('fa-sun');
                toggleText.textContent = 'Light Mode';
            }

            darkModeToggle.addEventListener('click', function () {
                document.body.classList.toggle('dark-mode');

                if (document.body.classList.contains('dark-mode')) {
                    toggleIcon.classList.remove('fa-moon');
                    toggleIcon.classList.add('fa-sun');
                    toggleText.textContent = 'Light Mode';
                    localStorage.setItem('darkMode', 'true');
                } else {
                    toggleIcon.classList.remove('fa-sun');
                    toggleIcon.classList.add('fa-moon');
                    toggleText.textContent = 'Dark Mode';
                    localStorage.setItem('darkMode', 'false');
                }
            });

            const menuItems = document.querySelectorAll('.menu-item');
            menuItems.forEach(item => {
                item.addEventListener('click', function () {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const notificationIcon = document.querySelector('.notification-icon');
            notificationIcon.addEventListener('click', function () {
                this.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    this.classList.remove('animate__animated', 'animate__shakeX');
                }, 500);
            });

            const settingsNavLinks = document.querySelectorAll('.settings-nav-link');
            const settingsPanels = document.querySelectorAll('.settings-panel');
            
            settingsNavLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    settingsNavLinks.forEach(l => l.classList.remove('active'));
                    settingsPanels.forEach(p => p.classList.remove('active'));
                    
                    this.classList.add('active');
                    
                    const panelId = this.getAttribute('data-panel') + '-panel';
                    document.getElementById(panelId).classList.add('active');
                });
            });

            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(o => o.classList.remove('active'));
        
                    this.classList.add('active');
                    
                    const color = this.getAttribute('data-color');
                
                    document.documentElement.style.setProperty('--primary', color);
                    
                    showToast('Theme color updated!');
                });
            });

            const saveButtons = document.querySelectorAll('.primary-btn');
            saveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    showToast('Settings saved successfully!');
                });
            });

            const toggles = document.querySelectorAll(".footer-toggle");

            toggles.forEach(toggle => {
                toggle.addEventListener("click", () => {
                    const isExpanded = !toggle.classList.contains("active");
                    
                    toggle.classList.toggle("active", isExpanded);
                    
                    toggle.setAttribute("aria-expanded", isExpanded);
                    
                    const panel = toggle.nextElementSibling;
                    if (panel) { 
                        if (isExpanded) {
                            panel.style.maxHeight = panel.scrollHeight + "px";
                        } else {
                            panel.style.maxHeight = null;
                        }
                    }
                });
            });

            function showToast(message) {
                const toast = document.getElementById('toast');
                const toastText = toast.querySelector('span');
                
                toastText.textContent = message;
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        });