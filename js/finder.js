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
    });}

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



            // --- 1. BACKGROUND (JS) ---
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
            for (let i = 0; i < 5; i++) {
                createFloatingElement();
            }

           
           
            const searchBar = document.getElementById('search-bar');
            const teacherGrid = document.getElementById('teacher-grid');
            
            if (searchBar && teacherGrid) {
                const teacherCards = teacherGrid.querySelectorAll('.teacher-card');

                searchBar.addEventListener('input', (e) => {
                    const searchTerm = e.target.value.toLowerCase();

                    teacherCards.forEach(card => {
                        const cardText = card.textContent.toLowerCase();
                        if (cardText.includes(searchTerm)) {
                            card.style.display = 'flex'; 
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }

            /* ========================================
            3. TEACHER DETAIL MODAL (JS)
            ========================================
            */
            const teacherModal = document.getElementById('teacherModal');
            const modalClose = document.getElementById('modalClose');
            const modalAvatar = document.getElementById('modalAvatar');
            const modalName = document.getElementById('modalName');
            const modalSubject = document.getElementById('modalSubject');
            const modalExperience = document.getElementById('modalExperience');
            const modalRating = document.getElementById('modalRating');
            const modalRatingText = document.getElementById('modalRatingText');
            const modalAbout = document.getElementById('modalAbout');
            const modalSubjectsList = document.getElementById('modalSubjectsList');
            const modalTeachingStyle = document.getElementById('modalTeachingStyle');
            const modalQualifications = document.getElementById('modalQualifications');
            const modalJoinClass = document.getElementById('modalJoinClass');
            const modalContact = document.getElementById('modalContact');

            const teachersData = {
                1: {
                    name: "MR. Tokito Muichiro",
                    avatar: "img1/Tokito.png",
                    subject: "Mathematics",
                    experience: "2 years",
                    rating: 4.5,
                    about: "I am passionate about making mathematics accessible and enjoyable for all students. My approach focuses on building strong foundational skills and developing problem-solving abilities.",
                    subjects: ["Algebra", "Geometry", "Calculus", "Statistics"],
                    teachingStyle: "I use a combination of visual aids, real-world examples, and interactive activities to help students grasp complex mathematical concepts. My lessons are structured yet flexible to accommodate different learning paces.",
                    qualifications: "Master's Degree in Mathematics, Certified Math Teacher with 2 years of classroom experience.",
                    joined: false
                },
                2: {
                    name: "MR. Shinazugawa Sanemi",
                    avatar: "img1/Shinazugawa.png",
                    subject: "Chinese",
                    experience: "3 years",
                    rating: 4.7,
                    about: "As a native Chinese speaker with a background in linguistics, I specialize in teaching Chinese language and culture to students of all levels.",
                    subjects: ["Mandarin", "Chinese Literature", "Chinese Culture", "HSK Preparation"],
                    teachingStyle: "My teaching method emphasizes practical communication skills while also providing a solid understanding of Chinese grammar and characters. I incorporate cultural elements to make learning more engaging.",
                    qualifications: "Bachelor's Degree in Chinese Language and Literature, 3 years of teaching experience, HSK Level 6 certified.",
                    joined: false
                },
                3: {
                    name: "MR. Tomioka Giyu",
                    avatar: "img1/Tomioka.png",
                    subject: "Accounts Economics Business",
                    experience: "4 years",
                    rating: 4.8,
                    about: "With a background in finance and business management, I help students understand the practical applications of accounting, economics, and business principles.",
                    subjects: ["Financial Accounting", "Managerial Accounting", "Microeconomics", "Macroeconomics", "Business Management"],
                    teachingStyle: "I focus on connecting theoretical concepts to real-world business scenarios. My lessons include case studies, practical exercises, and discussions about current economic trends.",
                    qualifications: "MBA with specialization in Finance, 4 years of teaching experience, Former financial analyst at a multinational corporation.",
                    joined: false
                },
                4: {
                    name: "MR. Himejima Gyoumei",
                    avatar: "img1/Himejima.png",
                    subject: "English",
                    experience: "10 years",
                    rating: 4.9,
                    about: "With over a decade of teaching experience, I specialize in helping students master English language skills for academic, professional, and personal growth.",
                    subjects: ["English Grammar", "Writing Skills", "Reading Comprehension", "Public Speaking", "IELTS/TOEFL Preparation"],
                    teachingStyle: "My approach is student-centered, focusing on individual needs and learning styles. I create a supportive environment where students feel comfortable practicing and improving their English skills.",
                    qualifications: "Master's Degree in English Literature, TESOL Certified, 10 years of teaching experience at various educational institutions.",
                    joined: false
                },
                5: {
                    name: "MS. Kocho Shinobu",
                    avatar: "img1/Kocho.png",
                    subject: "Physics Chemistry Biology",
                    experience: "7 years",
                    rating: 4.6,
                    about: "As a science enthusiast with a multidisciplinary background, I enjoy helping students discover the wonders of physics, chemistry, and biology.",
                    subjects: ["Physics", "Chemistry", "Biology", "Environmental Science", "Laboratory Techniques"],
                    teachingStyle: "I believe in hands-on learning and use experiments, demonstrations, and interactive simulations to make complex scientific concepts more understandable and memorable.",
                    qualifications: "PhD in Biochemistry, 7 years of teaching experience, Published researcher in scientific journals.",
                    joined: false
                },
                6: {
                    name: "MR. Uzui Tengen",
                    avatar: "img1/Uzui.png",
                    subject: "Computer Science",
                    experience: "5 years",
                    rating: 4.7,
                    about: "I am passionate about technology and enjoy sharing my knowledge of computer science with the next generation of innovators and problem-solvers.",
                    subjects: ["Programming", "Web Development", "Data Structures", "Algorithms", "Database Management", "Cybersecurity"],
                    teachingStyle: "My teaching approach combines theoretical foundations with practical coding exercises. I emphasize problem-solving skills and encourage students to work on real-world projects.",
                    qualifications: "Master's Degree in Computer Science, 5 years of teaching experience, Former software engineer at a tech startup.",
                    joined: false
                }
            };

            let currentTeacherId = null;

           
            function updateJoinButtonState(teacherId) {
                const teacher = teachersData[teacherId];
                if (!teacher) return;
                
                if (teacher.joined) {
                    modalJoinClass.innerHTML = '<i class="fas fa-check"></i> Joined Successfully';
                    modalJoinClass.classList.remove('btn-success');
                    modalJoinClass.classList.add('btn-disabled');
                    modalJoinClass.disabled = true;
                } else {
                    modalJoinClass.innerHTML = '<i class="fas fa-user-plus"></i> Join the Class';
                    modalJoinClass.classList.remove('btn-disabled');
                    modalJoinClass.classList.add('btn-success');
                    modalJoinClass.disabled = false;
                }
            }

         
            function openTeacherModal(teacherId) {
                const teacher = teachersData[teacherId];
                if (!teacher) return;
                
                currentTeacherId = teacherId;
                
                modalAvatar.src = teacher.avatar;
                modalAvatar.alt = teacher.name;
                modalName.textContent = teacher.name;
                modalSubject.textContent = teacher.subject;
                modalExperience.textContent = teacher.experience;
                
              
                modalRating.innerHTML = '';
                const fullStars = Math.floor(teacher.rating);
                const hasHalfStar = teacher.rating % 1 !== 0;
                
                for (let i = 0; i < fullStars; i++) {
                    modalRating.innerHTML += '<i class="fas fa-star"></i>';
                }
                
                if (hasHalfStar) {
                    modalRating.innerHTML += '<i class="fas fa-star-half-alt"></i>';
                }
                
                const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
                for (let i = 0; i < emptyStars; i++) {
                    modalRating.innerHTML += '<i class="far fa-star"></i>';
                }
                
                modalRatingText.textContent = `${teacher.rating}/5`;
                modalAbout.textContent = teacher.about;
                
              
                modalSubjectsList.innerHTML = '';
                teacher.subjects.forEach(subject => {
                    const subjectTag = document.createElement('div');
                    subjectTag.className = 'subject-tag';
                    subjectTag.textContent = subject;
                    modalSubjectsList.appendChild(subjectTag);
                });
                
                modalTeachingStyle.textContent = teacher.teachingStyle;
                modalQualifications.textContent = teacher.qualifications;
             
                updateJoinButtonState(teacherId);
                
                teacherModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

           
            function closeTeacherModal() {
                teacherModal.classList.remove('active');
                document.body.style.overflow = '';
                currentTeacherId = null;
            }

            const teacherCards = document.querySelectorAll('.teacher-card');
            teacherCards.forEach(card => {
                card.addEventListener('click', function() {
                    const teacherId = this.getAttribute('data-teacher-id');
                    openTeacherModal(teacherId);
                });
            });

        
            modalClose.addEventListener('click', closeTeacherModal);
            
          
            teacherModal.addEventListener('click', function(e) {
                if (e.target === teacherModal) {
                    closeTeacherModal();
                }
            });
           
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && teacherModal.classList.contains('active')) {
                    closeTeacherModal();
                }
            });

           
            modalJoinClass.addEventListener('click', function() {
                if (!currentTeacherId) return;
                
                const teacher = teachersData[currentTeacherId];
                if (!teacher || teacher.joined) return;
                
         
                teacher.joined = true;
                
              
                updateJoinButtonState(currentTeacherId);
                
               
                this.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    this.style.transform = '';
                    alert(`You have successfully joined ${teacher.name}'s class!`);
                }, 500);
            });

            modalContact.addEventListener('click', function() {
                const teacherName = modalName.textContent;
                alert(`Contact form for ${teacherName} would open here!`);
            });

