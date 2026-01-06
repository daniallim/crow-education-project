const materialsData = {
            "History": {
                pdf: [
                    { name: "World War II Overview", desc: "Comprehensive notes on WWII events", file: "history_wwii.pdf" },
                    { name: "Ancient Civilizations", desc: "Study guide for ancient societies", file: "history_ancient.pdf" },
                    { name: "Industrial Revolution", desc: "Impact and key developments", file: "history_industrial.pdf" }
                ],
                video: [
                    { name: "WWII Documentary", desc: "45-minute historical documentary", file: "history_wwii_video.mp4" },
                    { name: "Ancient Egypt", desc: "Visual exploration of pyramids", file: "history_egypt_video.mp4" }
                ],
                audio: [
                    { name: "Historical Speeches", desc: "Famous speeches collection", file: "history_speeches.mp3" },
                    { name: "History Podcast", desc: "Weekly history discussions", file: "history_podcast.mp3" }
                ],
                interactive: [
                    { name: "Timeline Quiz", desc: "Test your historical knowledge", file: "history_timeline_quiz.html" },
                    { name: "Map Exploration", desc: "Interactive historical maps", file: "history_maps.html" }
                ]
            },
            "Add.Math": {
                pdf: [
                    { name: "Calculus Fundamentals", desc: "Introduction to calculus concepts", file: "addmath_calculus.pdf" },
                    { name: "Algebra Review", desc: "Advanced algebraic techniques", file: "addmath_algebra.pdf" },
                    { name: "Trigonometry Guide", desc: "Trigonometric functions and identities", file: "addmath_trigonometry.pdf" }
                ],
                video: [
                    { name: "Calculus Explained", desc: "Step-by-step calculus problems", file: "addmath_calculus_video.mp4" },
                    { name: "Algebra Techniques", desc: "Solving complex equations", file: "addmath_algebra_video.mp4" }
                ],
                audio: [
                    { name: "Math Concepts", desc: "Audio explanations of key concepts", file: "addmath_concepts.mp3" },
                    { name: "Problem Solving", desc: "Step-by-step audio guides", file: "addmath_problems.mp3" }
                ],
                interactive: [
                    { name: "Equation Solver", desc: "Interactive equation practice", file: "addmath_equation_solver.html" },
                    { name: "Graphing Tool", desc: "Visualize mathematical functions", file: "addmath_graphing.html" }
                ]
            },
            "Art": {
                pdf: [
                    { name: "Color Theory", desc: "Understanding color relationships", file: "art_color_theory.pdf" },
                    { name: "Perspective Drawing", desc: "Techniques for spatial representation", file: "art_perspective.pdf" },
                    { name: "Art History Timeline", desc: "Major art movements overview", file: "art_history.pdf" }
                ],
                video: [
                    { name: "Watercolor Techniques", desc: "Step-by-step painting tutorial", file: "art_watercolor_video.mp4" },
                    { name: "Portrait Drawing", desc: "Facial proportions and shading", file: "art_portrait_video.mp4" }
                ],
                audio: [
                    { name: "Art Appreciation", desc: "Discussion on famous artworks", file: "art_appreciation.mp3" },
                    { name: "Creative Process", desc: "Insights from professional artists", file: "art_creative_process.mp3" }
                ],
                interactive: [
                    { name: "Virtual Gallery", desc: "Explore famous artworks", file: "art_gallery.html" },
                    { name: "Color Mixing", desc: "Interactive color experimentation", file: "art_color_mixing.html" }
                ]
            },
            "Science": {
                pdf: [
                    { name: "Biology Basics", desc: "Cell structure and functions", file: "science_biology.pdf" },
                    { name: "Chemistry Reactions", desc: "Chemical equations and balancing", file: "science_chemistry.pdf" },
                    { name: "Physics Principles", desc: "Laws of motion and energy", file: "science_physics.pdf" }
                ],
                video: [
                    { name: "Chemistry Experiments", desc: "Safe lab demonstrations", file: "science_chemistry_video.mp4" },
                    { name: "Physics in Action", desc: "Real-world applications", file: "science_physics_video.mp4" }
                ],
                audio: [
                    { name: "Science News", desc: "Latest scientific discoveries", file: "science_news.mp3" },
                    { name: "Biology Podcast", desc: "Discussions on living organisms", file: "science_biology_podcast.mp3" }
                ],
                interactive: [
                    { name: "Virtual Lab", desc: "Simulate science experiments", file: "science_lab.html" },
                    { name: "Periodic Table", desc: "Interactive element exploration", file: "science_periodic.html" }
                ]
            },
            "English": {
                pdf: [
                    { name: "Grammar Rules", desc: "Comprehensive grammar guide", file: "english_grammar.pdf" },
                    { name: "Literary Analysis", desc: "Techniques for analyzing texts", file: "english_literature.pdf" },
                    { name: "Writing Skills", desc: "Essay and composition tips", file: "english_writing.pdf" }
                ],
                video: [
                    { name: "Public Speaking", desc: "Effective presentation techniques", file: "english_speaking_video.mp4" },
                    { name: "Shakespeare Analysis", desc: "Understanding classic literature", file: "english_shakespeare_video.mp4" }
                ],
                audio: [
                    { name: "Pronunciation Guide", desc: "Improve your English accent", file: "english_pronunciation.mp3" },
                    { name: "Literature Readings", desc: "Classic passages read aloud", file: "english_readings.mp3" }
                ],
                interactive: [
                    { name: "Grammar Quiz", desc: "Test your grammar knowledge", file: "english_grammar_quiz.html" },
                    { name: "Vocabulary Builder", desc: "Expand your word knowledge", file: "english_vocabulary.html" }
                ]
            },
            "Geography": {
                pdf: [
                    { name: "World Maps", desc: "Political and physical maps", file: "geography_maps.pdf" },
                    { name: "Climate Zones", desc: "Global climate patterns", file: "geography_climate.pdf" },
                    { name: "Population Studies", desc: "Demographics and migration", file: "geography_population.pdf" }
                ],
                video: [
                    { name: "Natural Disasters", desc: "Earthquakes, volcanoes, and more", file: "geography_disasters_video.mp4" },
                    { name: "Cultural Geography", desc: "Human impact on landscapes", file: "geography_cultural_video.mp4" }
                ],
                audio: [
                    { name: "Geographic Podcast", desc: "Discussions on places and spaces", file: "geography_podcast.mp3" },
                    { name: "Climate Change", desc: "Audio documentary on global warming", file: "geography_climate.mp3" }
                ],
                interactive: [
                    { name: "Map Quiz", desc: "Test your geography knowledge", file: "geography_map_quiz.html" },
                    { name: "Virtual Field Trip", desc: "Explore world landmarks", file: "geography_field_trip.html" }
                ]
            }
        };

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

            const classCards = document.querySelectorAll('.class-card');
            classCards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-5px)';
                });

                card.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            const actionButtons = document.querySelectorAll('.action-btn');
            actionButtons.forEach(button => {
                button.addEventListener('mouseenter', function () {
                    this.style.transform = 'translateY(-3px)';
                });

                button.addEventListener('mouseleave', function () {
                    this.style.transform = 'translateY(0)';
                });
            });

            const progressFills = document.querySelectorAll('.progress-fill');
            progressFills.forEach(fill => {
                const width = fill.style.width;
                fill.style.width = '0';
                setTimeout(() => {
                    fill.style.width = width;
                }, 500);
            });

            const classTabs = document.querySelectorAll('.class-tab');
            classTabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    classTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.getAttribute('data-filter');
                    filterClasses(filter);
                });
            });

            function filterClasses(status) {
                const classCards = document.querySelectorAll('.class-card');
                classCards.forEach(card => {
                    if (status === 'all' || card.getAttribute('data-status') === status) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            const footerToggles = document.querySelectorAll(".footer-toggle");

            footerToggles.forEach(toggle => {
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

            const viewMaterialsBtns = document.querySelectorAll('.view-materials-btn');
            const materialsModal = document.getElementById('materialsModal');
            const closeModal = document.getElementById('closeModal');
            const modalSubjectTitle = document.getElementById('modalSubjectTitle');
            const pdfMaterials = document.getElementById('pdfMaterials');
            const videoMaterials = document.getElementById('videoMaterials');
            const audioMaterials = document.getElementById('audioMaterials');
            const interactiveMaterials = document.getElementById('interactiveMaterials');
            const previewPlaceholder = document.getElementById('previewPlaceholder');
            const previewContent = document.getElementById('previewContent');
            const downloadBtn = document.getElementById('downloadBtn');
            const openBtn = document.getElementById('openBtn');

            let currentMaterial = null;

            viewMaterialsBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const subject = this.getAttribute('data-subject');
                    openMaterialsModal(subject);
                });
            });

            closeModal.addEventListener('click', function() {
                materialsModal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === materialsModal) {
                    materialsModal.style.display = 'none';
                }
            });

            function openMaterialsModal(subject) {
                modalSubjectTitle.textContent = `${subject} Materials`;
                
                pdfMaterials.innerHTML = '';
                videoMaterials.innerHTML = '';
                audioMaterials.innerHTML = '';
                interactiveMaterials.innerHTML = '';
                
                previewPlaceholder.style.display = 'block';
                previewContent.style.display = 'none';
                downloadBtn.disabled = true;
                openBtn.disabled = true;
                
                const subjectMaterials = materialsData[subject];
                
                if (subjectMaterials.pdf) {
                    subjectMaterials.pdf.forEach(material => {
                        const materialItem = createMaterialItem(material, 'pdf');
                        pdfMaterials.appendChild(materialItem);
                    });
                }
                
                if (subjectMaterials.video) {
                    subjectMaterials.video.forEach(material => {
                        const materialItem = createMaterialItem(material, 'video');
                        videoMaterials.appendChild(materialItem);
                    });
                }
                
                if (subjectMaterials.audio) {
                    subjectMaterials.audio.forEach(material => {
                        const materialItem = createMaterialItem(material, 'audio');
                        audioMaterials.appendChild(materialItem);
                    });
                }
                
                if (subjectMaterials.interactive) {
                    subjectMaterials.interactive.forEach(material => {
                        const materialItem = createMaterialItem(material, 'interactive');
                        interactiveMaterials.appendChild(materialItem);
                    });
                }
                
                materialsModal.style.display = 'block';
            }

            function createMaterialItem(material, type) {
                const item = document.createElement('div');
                item.className = 'material-item';
                item.setAttribute('data-file', material.file);
                item.setAttribute('data-type', type);
                
                const iconClass = {
                    'pdf': 'fas fa-file-pdf',
                    'video': 'fas fa-video',
                    'audio': 'fas fa-volume-up',
                    'interactive': 'fas fa-gamepad'
                };
                
                item.innerHTML = `
                    <div class="material-icon ${type}">
                        <i class="${iconClass[type]}"></i>
                    </div>
                    <div class="material-name">${material.name}</div>
                    <div class="material-desc">${material.desc}</div>
                `;
                
                item.addEventListener('click', function() {
                    document.querySelectorAll('.material-item').forEach(el => {
                        el.style.border = '1px solid var(--border-color)';
                    });
                    
                    this.style.border = '2px solid var(--primary)';
                    
                    currentMaterial = {
                        name: material.name,
                        file: material.file,
                        type: type
                    };
                    
                    updatePreview(material, type);
                    
                    downloadBtn.disabled = false;
                    openBtn.disabled = false;
                });
                
                return item;
            }

            function updatePreview(material, type) {
                previewPlaceholder.style.display = 'none';
                previewContent.style.display = 'block';
                
                let previewHTML = '';
                
                switch(type) {
                    case 'pdf':
                        previewHTML = `
                            <div style="text-align: center;">
                                <i class="fas fa-file-pdf" style="font-size: 48px; color: #e63946; margin-bottom: 15px;"></i>
                                <h4>${material.name}</h4>
                                <p>${material.desc}</p>
                                <p><small>PDF Document</small></p>
                            </div>
                        `;
                        break;
                    case 'video':
                        previewHTML = `
                            <div style="text-align: center;">
                                <i class="fas fa-video" style="font-size: 48px; color: #3a86ff; margin-bottom: 15px;"></i>
                                <h4>${material.name}</h4>
                                <p>${material.desc}</p>
                                <p><small>Video Lesson</small></p>
                            </div>
                        `;
                        break;
                    case 'audio':
                        previewHTML = `
                            <div style="text-align: center;">
                                <i class="fas fa-volume-up" style="font-size: 48px; color: #8338ec; margin-bottom: 15px;"></i>
                                <h4>${material.name}</h4>
                                <p>${material.desc}</p>
                                <p><small>Audio Resource</small></p>
                            </div>
                        `;
                        break;
                    case 'interactive':
                        previewHTML = `
                            <div style="text-align: center;">
                                <i class="fas fa-gamepad" style="font-size: 48px; color: #38b000; margin-bottom: 15px;"></i>
                                <h4>${material.name}</h4>
                                <p>${material.desc}</p>
                                <p><small>Interactive Activity</small></p>
                            </div>
                        `;
                        break;
                }
                
                previewContent.innerHTML = previewHTML;
            }

            const createLessonBtn = document.getElementById('createLessonBtn');
            const createLessonModal = document.getElementById('createLessonModal');
            const closeCreateLessonModal = document.getElementById('closeCreateLessonModal');
            const cancelCreateLesson = document.getElementById('cancelCreateLesson');
            const createLessonForm = document.getElementById('createLessonForm');
            const successMessage = document.getElementById('successMessage');
            const successText = document.getElementById('successText');
            const downloadIndicator = document.getElementById('downloadIndicator');
            const downloadText = document.getElementById('downloadText');
            const downloadProgressBar = document.getElementById('downloadProgressBar');

            createLessonBtn.addEventListener('click', function() {
                createLessonModal.style.display = 'block';
            });

            closeCreateLessonModal.addEventListener('click', function() {
                createLessonModal.style.display = 'none';
            });

            cancelCreateLesson.addEventListener('click', function() {
                createLessonModal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target === createLessonModal) {
                    createLessonModal.style.display = 'none';
                }
            });

            createLessonForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const title = document.getElementById('lessonTitle').value;
                const subject = document.getElementById('lessonSubject').value;
                const form = document.getElementById('lessonForm').value;
                const description = document.getElementById('lessonDescription').value;
                
                const submitBtn = createLessonForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
                submitBtn.disabled = true;
                
                setTimeout(function() {
                    createNewLessonCard(title, subject, form, description);
                    
                    createLessonForm.reset();
                    
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    createLessonModal.style.display = 'none';
                    
                    successText.textContent = `"${title}" lesson created successfully!`;
                    successMessage.classList.add('show');
                    
                    setTimeout(function() {
                        successMessage.classList.remove('show');
                    }, 3000);
                    
                }, 1500);
            });

            function createNewLessonCard(title, subject, form, description) {
                const classesGrid = document.querySelector('.classes-grid');
                
                const newCard = document.createElement('div');
                newCard.className = 'class-card onprogress';
                newCard.setAttribute('data-status', 'onprogress');
                
                newCard.innerHTML = `
                    <div class="class-status">
                        <div class="status-indicator onprogress"></div>
                        <span>On Progress</span>
                    </div>
                    <div class="class-subject">${subject}</div>
                    <div class="class-details">
                        <span>${form}</span>
                    </div>
                    <div class="class-progress">
                        <div class="progress-info">
                            <span>Viewer</span>
                            <span>0%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="class-actions">
                        <span class="status-badge status-onprogress">NEW</span>
                        <button class="btn btn-outline view-materials-btn" data-subject="${subject}">
                            <span>View Materials</span>
                        </button>
                        <button class="btn btn-danger delete-lesson-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                classesGrid.insertBefore(newCard, classesGrid.firstChild);
                
                const newViewMaterialsBtn = newCard.querySelector('.view-materials-btn');
                newViewMaterialsBtn.addEventListener('click', function() {
                    const subject = this.getAttribute('data-subject');
                    openMaterialsModal(subject);
                });
                
                const newDeleteBtn = newCard.querySelector('.delete-lesson-btn');
                newDeleteBtn.addEventListener('click', function() {
                    const card = this.closest('.class-card');
                    const subject = card.querySelector('.class-subject').textContent;
                    const form = card.querySelector('.class-details span').textContent;
                    showDeleteConfirmation(card, subject, form);
                });
                
                updateTabCounts();
                
                newCard.style.opacity = '0';
                newCard.style.transform = 'translateY(20px)';
                
                setTimeout(function() {
                    newCard.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    newCard.style.opacity = '1';
                    newCard.style.transform = 'translateY(0)';
                }, 100);
                
                setTimeout(function() {
                    const progressFill = newCard.querySelector('.progress-fill');
                    const progressInfo = newCard.querySelector('.progress-info span:last-child');
                    
                    let progress = 0;
                    const interval = setInterval(function() {
                        progress += Math.random() * 5;
                        if (progress >= 60) {
                            progress = 60;
                            clearInterval(interval);
                        }
                        
                        progressFill.style.width = `${progress}%`;
                        progressInfo.textContent = `${Math.round(progress)}%`;
                    }, 100);
                }, 2000);
            }

            function updateTabCounts() {
                const allLessons = document.querySelectorAll('.class-card').length;
                const onProgressLessons = document.querySelectorAll('.class-card[data-status="onprogress"]').length;
                const completedLessons = document.querySelectorAll('.class-card[data-status="completed"]').length;
                
                document.querySelector('.class-tab[data-filter="all"] .class-tab-badge').textContent = allLessons;
                document.querySelector('.class-tab[data-filter="onprogress"] .class-tab-badge').textContent = onProgressLessons;
                document.querySelector('.class-tab[data-filter="completed"] .class-tab-badge').textContent = completedLessons;
            }

            downloadBtn.addEventListener('click', function() {
                if (currentMaterial) {
                    downloadText.textContent = `Downloading ${currentMaterial.name}...`;
                    downloadIndicator.classList.add('show');
                    
                    let progress = 0;
                    const interval = setInterval(function() {
                        progress += Math.random() * 15;
                        if (progress >= 100) {
                            progress = 100;
                            clearInterval(interval);
                            
                            setTimeout(function() {
                                downloadText.textContent = 'Download complete!';
                                
                                setTimeout(function() {
                                    downloadIndicator.classList.remove('show');
                                    downloadProgressBar.style.width = '0%';
                                }, 2000);
                            }, 500);
                        }
                        
                        downloadProgressBar.style.width = `${progress}%`;
                    }, 200);
                    
                    console.log(`Downloading: ${currentMaterial.name} (${currentMaterial.file})`);
                }
            });

            openBtn.addEventListener('click', function() {
                if (currentMaterial) {
                    alert(`Opening: ${currentMaterial.name}`);
                }
            });

            const deleteLessonBtns = document.querySelectorAll('.delete-lesson-btn');
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const closeDeleteConfirmation = document.getElementById('closeDeleteConfirmation');
            const cancelDelete = document.getElementById('cancelDelete');
            const confirmDelete = document.getElementById('confirmDelete');
            const deleteConfirmationText = document.getElementById('deleteConfirmationText');
            const deleteMessage = document.getElementById('deleteMessage');
            const deleteText = document.getElementById('deleteText');

            let lessonToDelete = null;

            deleteLessonBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const card = this.closest('.class-card');
                    const subject = card.querySelector('.class-subject').textContent;
                    const form = card.querySelector('.class-details span').textContent;
                    showDeleteConfirmation(card, subject, form);
                });
            });

            function showDeleteConfirmation(card, subject, form) {
                lessonToDelete = card;
                deleteConfirmationText.textContent = `Are you sure you want to delete the "${subject}" lesson for ${form}? This action cannot be undone.`;
                deleteConfirmationModal.style.display = 'block';
            }

            closeDeleteConfirmation.addEventListener('click', function() {
                deleteConfirmationModal.style.display = 'none';
                lessonToDelete = null;
            });

            cancelDelete.addEventListener('click', function() {
                deleteConfirmationModal.style.display = 'none';
                lessonToDelete = null;
            });

            window.addEventListener('click', function(event) {
                if (event.target === deleteConfirmationModal) {
                    deleteConfirmationModal.style.display = 'none';
                    lessonToDelete = null;
                }
            });

            confirmDelete.addEventListener('click', function() {
                if (lessonToDelete) {
                    const subject = lessonToDelete.querySelector('.class-subject').textContent;
                    const form = lessonToDelete.querySelector('.class-details span').textContent;
                    
                    lessonToDelete.style.transform = 'scale(0.9)';
                    lessonToDelete.style.opacity = '0';
                    
                    setTimeout(function() {
                        lessonToDelete.remove();
                        deleteConfirmationModal.style.display = 'none';
                        
                        deleteText.textContent = `"${subject}" lesson for ${form} deleted successfully!`;
                        deleteMessage.classList.add('show');
                        
                        setTimeout(function() {
                            deleteMessage.classList.remove('show');
                        }, 3000);
                        
                        updateTabCounts();
                        lessonToDelete = null;
                    }, 300);
                }
            });
        });