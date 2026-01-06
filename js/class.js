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
        notificationIcon.addEventListener('click', function (e) {
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
        settingsIcon.addEventListener('click', function (e) {
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
        function showPage(pageId) {
            // 隐藏所有页面
            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });

    
            const newPage = document.getElementById(pageId);
            if (newPage) {
                newPage.classList.add('active');

                newPage.querySelectorAll('.post-card').forEach(post => {
                    post.style.display = 'block';
                });
                newPage.querySelectorAll('.nav-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                const allButton = newPage.querySelector('.nav-button[data-filter="all"]');
                if (allButton) {
                    allButton.classList.add('active');
                }
            }

            initVideoControls();
        }

        function showTeacherPage(teacher) {
            showPage(teacher + '-page');
        }


        document.addEventListener('DOMContentLoaded', () => {
            const container = document.querySelector('.floating-elements');

            function createFloatingElement() {
                const element = document.createElement('div');
                element.className = 'floating-element';
                const size = Math.random() * 150 + 50;
                element.style.width = `${size}px`;
                element.style.height = `${size}px`;

                element.style.top = `${Math.random() * 100}vh`;
                element.style.left = `${Math.random() * 100}vw`;

                element.style.animationDuration = `${Math.random() * 20 + 15}s`;
                element.style.animationDelay = `${Math.random() * -30}s`;
                container.appendChild(element);
            }

            for (let i = 0; i < 5; i++) createFloatingElement();

            initVideoControls();

            document.querySelectorAll('.nav-button').forEach(button => {
                button.addEventListener('click', filterPosts);
            });

            document.querySelectorAll('.submit-homework-button').forEach(button => {
                button.addEventListener('click', (event) => {
                    // 如果已经提交，就什么都不做
                    if (button.classList.contains('submitted')) {
                        return;
                    }

                    const submissionArea = button.parentElement.querySelector('.inline-submission-area');

                    
                    submissionArea.classList.toggle('active');
                    button.classList.toggle('active'); 

              
                    const icon = button.querySelector('.fa-chevron-down, .fa-chevron-up');
                    if (icon) {
                        if (submissionArea.classList.contains('active')) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        } else {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                });
            });

            document.querySelectorAll('.file-input-hidden-inline').forEach(fileInput => {
                fileInput.addEventListener('change', (event) => {
                   
                    const fileNameDisplay = event.target.parentElement.querySelector('.file-name-display-inline');
                    if (event.target.files.length > 0) {
                   
                        fileNameDisplay.textContent = event.target.files[0].name;
                        fileNameDisplay.style.color = '#333';
                    } else {
                        fileNameDisplay.textContent = 'No file selected';
                        fileNameDisplay.style.color = '#777';
                    }
                });
            });

            document.querySelectorAll('.modal-button-submit-inline').forEach(submitBtn => {
                submitBtn.addEventListener('click', (event) => {

         
                    const inlineArea = event.target.closest('.inline-submission-area');
        
                    const fileInput = inlineArea.querySelector('.file-input-hidden-inline');

                    if (fileInput.files.length === 0) {
                        alert('Please select a file to submit.');
                        return;
                    }

          

           
                    const submissionSection = event.target.closest('.submission-section');

              
                    const turnInButton = submissionSection.querySelector('.submit-homework-button');
            
                    const statusText = submissionSection.querySelector('.submission-status-text');

                    turnInButton.classList.add('submitted'); 
                    turnInButton.innerHTML = '<i class="fas fa-check"></i> Submitted'; 
                    turnInButton.disabled = true; 

             
                    if (statusText) {
                        statusText.textContent = 'Submitted';
                        statusText.style.color = '#2ecc71'; 
                        statusText.style.fontStyle = 'normal';
                        statusText.style.fontWeight = 'bold';
                    }

                 
                    inlineArea.classList.remove('active');
                    alert('Homework submitted successfully! (This is a simulation)');

                });
            });
    

        });





        function filterPosts(event) {
            const button = event.currentTarget;
       
            if (!button.closest('.page.active')) return;

            const filter = button.dataset.filter; 

            const activePage = button.closest('.page.active');
            if (!activePage) return;

         
            button.parentElement.querySelectorAll('.nav-button').forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');

         
            const posts = activePage.querySelectorAll('.post-card');

            posts.forEach(post => {
                if (filter === 'all') {
                    post.style.display = 'block';
                } else {
                    if (post.classList.contains('post-category-' + filter)) {
                        post.style.display = 'block';
                    } else {
                        post.style.display = 'none';
                    }
                }
            });
        }



        function initVideoControls() {
      
            const historyVideo = document.getElementById('rengokuVideo');
            const historyPlayPauseBtn = document.getElementById('historyPlayPauseBtn');
            const historyDownloadBtn = document.getElementById('historyDownloadVideoBtn');
            const historyFullscreenBtn = document.getElementById('historyFullscreenBtn');
            const historyVideoDuration = document.getElementById('historyVideoDuration');

            if (historyVideo && historyPlayPauseBtn) {
              
                if (historyPlayPauseBtn.listenerAdded) return;
                historyPlayPauseBtn.listenerAdded = true;

                historyPlayPauseBtn.addEventListener('click', function () {
                    if (historyVideo.paused) {
                        historyVideo.play();
                    } else {
                        historyVideo.pause();
                    }
                });

                historyVideo.addEventListener('play', function () {
                    historyPlayPauseBtn.classList.remove('fa-play');
                    historyPlayPauseBtn.classList.add('fa-pause');
                });

                historyVideo.addEventListener('pause', function () {
                    historyPlayPauseBtn.classList.remove('fa-pause');
                    historyPlayPauseBtn.classList.add('fa-play');
                });

                historyVideo.addEventListener('click', function () {
                    if (historyVideo.paused) {
                        historyVideo.play();
                    } else {
                        historyVideo.pause();
                    }
                });

                if (historyDownloadBtn) {
                    historyDownloadBtn.addEventListener('click', function () {
                        const videoSrc = historyVideo.querySelector('source').src;
                        const link = document.createElement('a');
                        link.href = videoSrc;
                        link.download = 'F4_Chapter5_PTN1948.mp4';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }

                if (historyFullscreenBtn) {
                    historyFullscreenBtn.addEventListener('click', function () {
                        if (historyVideo.requestFullscreen) {
                            historyVideo.requestFullscreen();
                        } else if (historyVideo.mozRequestFullScreen) {
                            historyVideo.mozRequestFullScreen();
                        } else if (historyVideo.webkitRequestFullscreen) {
                            historyVideo.webkitRequestFullscreen();
                        } else if (historyVideo.msRequestFullscreen) {
                            historyVideo.msRequestFullscreen();
                        }
                    });
                }

                if (historyVideoDuration) {
                    historyVideo.addEventListener('loadedmetadata', function () {
                        const duration = historyVideo.duration;
                        const minutes = Math.floor(duration / 60);
                        const seconds = Math.floor(duration % 60);
                        historyVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });

                    historyVideo.addEventListener('timeupdate', function () {
                        const currentTime = historyVideo.currentTime;
                        const minutes = Math.floor(currentTime / 60);
                        const seconds = Math.floor(currentTime % 60);
                        historyVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });
                }
            }

     
            const mathVideo = document.getElementById('mathVideo');
            const mathPlayPauseBtn = document.getElementById('mathPlayPauseBtn');
            const mathDownloadBtn = document.getElementById('mathDownloadVideoBtn');
            const mathFullscreenBtn = document.getElementById('mathFullscreenBtn');
            const mathVideoDuration = document.getElementById('mathVideoDuration');

            if (mathVideo && mathPlayPauseBtn) {
            
                if (mathPlayPauseBtn.listenerAdded) return;
                mathPlayPauseBtn.listenerAdded = true;

                mathPlayPauseBtn.addEventListener('click', function () {
                    if (mathVideo.paused) {
                        mathVideo.play();
                    } else {
                        mathVideo.pause();
                    }
                });

                mathVideo.addEventListener('play', function () {
                    mathPlayPauseBtn.classList.remove('fa-play');
                    mathPlayPauseBtn.classList.add('fa-pause');
                });

                mathVideo.addEventListener('pause', function () {
                    mathPlayPauseBtn.classList.remove('fa-pause');
                    mathPlayPauseBtn.classList.add('fa-play');
                });

                mathVideo.addEventListener('click', function () {
                    if (mathVideo.paused) {
                        mathVideo.play();
                    } else {
                        mathVideo.pause();
                    }
                });

                if (mathDownloadBtn) {
                    mathDownloadBtn.addEventListener('click', function () {
                        const videoSrc = mathVideo.querySelector('source').src;
                        const link = document.createElement('a');
                        link.href = videoSrc;
                        link.download = 'F4_Chapter8_Vector.mp4';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }

                if (mathFullscreenBtn) {
                    mathFullscreenBtn.addEventListener('click', function () {
                        if (mathVideo.requestFullscreen) {
                            mathVideo.requestFullscreen();
                        } else if (mathVideo.mozRequestFullScreen) {
                            mathVideo.mozRequestFullScreen();
                        } else if (mathVideo.webkitRequestFullscreen) {
                            mathVideo.webkitRequestfullscreen();
                        } else if (mathVideo.msRequestFullscreen) {
                            mathVideo.msRequestFullscreen();
                        }
                    });
                }

                if (mathVideoDuration) {
                    mathVideo.addEventListener('loadedmetadata', function () {
                        const duration = mathVideo.duration;
                        const minutes = Math.floor(duration / 60);
                        const seconds = Math.floor(duration % 60);
                        mathVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });

                    mathVideo.addEventListener('timeupdate', function () {
                        const currentTime = mathVideo.currentTime;
                        const minutes = Math.floor(currentTime / 60);
                        const seconds = Math.floor(currentTime % 60);
                        mathVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });
                }
            }

     
            const artVideo = document.getElementById('artVideo');
            const artPlayPauseBtn = document.getElementById('artPlayPauseBtn');
            const artDownloadBtn = document.getElementById('artDownloadVideoBtn');
            const artFullscreenBtn = document.getElementById('artFullscreenBtn');
            const artVideoDuration = document.getElementById('artVideoDuration');

            if (artVideo && artPlayPauseBtn) {
           
                if (artPlayPauseBtn.listenerAdded) return;
                artPlayPauseBtn.listenerAdded = true;

                artPlayPauseBtn.addEventListener('click', function () {
                    if (artVideo.paused) {
                        artVideo.play();
                    } else {
                        artVideo.pause();
                    }
                });

                artVideo.addEventListener('play', function () {
                    artPlayPauseBtn.classList.remove('fa-play');
                    artPlayPauseBtn.classList.add('fa-pause');
                });

                artVideo.addEventListener('pause', function () {
                    artPlayPauseBtn.classList.remove('fa-pause');
                    artPlayPauseBtn.classList.add('fa-play');
                });

                artVideo.addEventListener('click', function () {
                    if (artVideo.paused) {
                        artVideo.play();
                    } else {
                        artVideo.pause();
                    }
                });

                if (artDownloadBtn) {
                    artDownloadBtn.addEventListener('click', function () {
                        const videoSrc = artVideo.querySelector('source').src;
                        const link = document.createElement('a');
                        link.href = videoSrc;
                        link.download = 'Chapter_10_Lecture.mp4';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }

                if (artFullscreenBtn) {
                    artFullscreenBtn.addEventListener('click', function () {
                        if (artVideo.requestFullscreen) {
                            artVideo.requestFullscreen();
                        } else if (artVideo.mozRequestFullScreen) {
                            artVideo.mozRequestFullScreen();
                        } else if (artVideo.webkitRequestFullscreen) {
                            artVideo.webkitRequestFullscreen();
                        } else if (artVideo.msRequestFullscreen) {
                            artVideo.msRequestFullscreen();
                        }
                    });
                }

                if (artVideoDuration) {
                    artVideo.addEventListener('loadedmetadata', function () {
                        const duration = artVideo.duration;
                        const minutes = Math.floor(duration / 60);
                        const seconds = Math.floor(duration % 60);
                        artVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });

                    artVideo.addEventListener('timeupdate', function () {
                        const currentTime = artVideo.currentTime;
                        const minutes = Math.floor(currentTime / 60);
                        const seconds = Math.floor(currentTime % 60);
                        artVideoDuration.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                    });
                }
            }
        }