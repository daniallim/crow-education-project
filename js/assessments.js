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

            const ctx = document.getElementById('performanceChart').getContext('2d');
            
            let performanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mathematics', 'Science', 'English', 'History', 'Geography'],
                    datasets: [{
                        label: 'Average Score (%)',
                        data: [82, 91, 79, 85, 88],
                        backgroundColor: [
                            'rgba(67, 97, 238, 0.7)',
                            'rgba(76, 201, 240, 0.7)',
                            'rgba(247, 37, 133, 0.7)',
                            'rgba(255, 159, 64, 0.7)',
                            'rgba(75, 192, 192, 0.7)'
                        ],
                        borderColor: [
                            'rgba(67, 97, 238, 1)',
                            'rgba(76, 201, 240, 1)',
                            'rgba(247, 37, 133, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Average Score: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });

            const chartControls = document.querySelectorAll('.chart-control-btn');
            chartControls.forEach(control => {
                control.addEventListener('click', function() {
                    chartControls.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    const chartType = this.getAttribute('data-chart');
                    updateChart(chartType);
                });
            });

            function updateChart(type) {
                if (type === 'subject') {
                    performanceChart.data.labels = ['Mathematics', 'Science', 'English', 'History', 'Geography'];
                    performanceChart.data.datasets[0].data = [82, 91, 79, 85, 88];
                } else if (type === 'time') {
                    performanceChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'];
                    performanceChart.data.datasets[0].data = [75, 80, 82, 85, 88];
                }
                
                performanceChart.update();
            }
            
            document.getElementById('exportChartBtn').addEventListener('click', function() {
                const link = document.createElement('a');
                link.download = 'student-performance-chart.png';
                link.href = performanceChart.toBase64Image();
                link.click();
                
                this.innerHTML = '<i class="fas fa-check"></i> Exported';
                this.style.backgroundColor = '#38b000';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-download"></i> Export';
                    this.style.backgroundColor = '';
                }, 2000);
            });

            const newAssessmentBtn = document.getElementById('newAssessmentBtn');
            const newAssessmentModal = document.getElementById('newAssessmentModal');
            const closeNewAssessmentModal = document.getElementById('closeNewAssessmentModal');
            const cancelNewAssessment = document.getElementById('cancelNewAssessment');
            const newAssessmentForm = document.getElementById('newAssessmentForm');

            newAssessmentBtn.addEventListener('click', function() {
                newAssessmentModal.style.display = 'flex';
            });

            closeNewAssessmentModal.addEventListener('click', function() {
                newAssessmentModal.style.display = 'none';
            });

            cancelNewAssessment.addEventListener('click', function() {
                newAssessmentModal.style.display = 'none';
            });

            newAssessmentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const title = document.getElementById('assessmentTitle').value;
                const subject = document.getElementById('assessmentSubject').value;
                const type = document.getElementById('assessmentType').value;
                const date = document.getElementById('assessmentDate').value;
                const duration = document.getElementById('assessmentDuration').value;
                
                const assessmentList = document.getElementById('assessmentList');
                const newAssessmentItem = document.createElement('div');
                newAssessmentItem.className = 'assessment-item';
                newAssessmentItem.setAttribute('data-title', title);
                newAssessmentItem.setAttribute('data-topic', `Assessment Type: ${type}`);
                newAssessmentItem.setAttribute('data-viewer', '0');
                newAssessmentItem.setAttribute('data-avg', '0');
                newAssessmentItem.setAttribute('data-high', '0');
                newAssessmentItem.setAttribute('data-low', '0');
                
                newAssessmentItem.innerHTML = `
                    <h3 class="assessment-title">${title}</h3>
                    <p class="assessment-topic">Assessment Type: ${type}</p>
                    <div class="assessment-details">
                        <div class="assessment-stats">
                            <div class="stat">
                                <span>Viewer:</span>
                                <span class="stat-value">0</span>
                            </div>
                            <div class="stat">
                                <span>Avg Score:</span>
                                <span class="stat-value">0%</span>
                            </div>
                            <div class="stat">
                                <span>Highest:</span>
                                <span class="stat-value">0%</span>
                            </div>
                            <div class="stat">
                                <span>Lowest:</span>
                                <span class="stat-value">0%</span>
                            </div>
                        </div>
                    </div>
                    <div class="assessment-actions">
                        <button class="btn btn-primary results-btn">View Results</button>
                        <button class="btn btn-outline edit-btn">Edit</button>
                        <button class="btn btn-danger delete-btn">Delete</button>
                    </div>
                `;
                
                addAssessmentEventListeners(newAssessmentItem);
                
                assessmentList.prepend(newAssessmentItem);
                
                newAssessmentForm.reset();
                newAssessmentModal.style.display = 'none';
                
                alert(`New assessment "${title}" created successfully!`);
            });

            const viewResultsModal = document.getElementById('viewResultsModal');
            const closeResultsModal = document.getElementById('closeResultsModal');
            const closeResultsModalBtn = document.getElementById('closeResultsModalBtn');
            const resultsModalTitle = document.getElementById('resultsModalTitle');
            const resultsTableBody = document.getElementById('resultsTableBody');
            const exportResultsBtn = document.getElementById('exportResultsBtn');
            let resultsChart;

            function openResultsModal(assessmentItem) {
                const title = assessmentItem.querySelector('.assessment-title').textContent;
                const avgScore = assessmentItem.getAttribute('data-avg');
                
                resultsModalTitle.textContent = `Results: ${title}`;
                
                const studentData = generateStudentData(parseInt(assessmentItem.getAttribute('data-viewer')));
                
                resultsTableBody.innerHTML = '';
                studentData.forEach(student => {
                    const row = document.createElement('tr');
                    let gradeClass = '';
                    
                    if (student.score >= 90) gradeClass = 'grade-excellent';
                    else if (student.score >= 80) gradeClass = 'grade-good';
                    else if (student.score >= 70) gradeClass = 'grade-average';
                    else gradeClass = 'grade-poor';
                    
                    row.innerHTML = `
                        <td>${student.name}</td>
                        <td>${student.score}%</td>
                        <td class="${gradeClass}">${student.grade}</td>
                        <td>${student.status}</td>
                    `;
                    resultsTableBody.appendChild(row);
                });
                
                const resultsCtx = document.getElementById('resultsChart').getContext('2d');
                
                if (resultsChart) {
                    resultsChart.destroy();
                }
                
                resultsChart = new Chart(resultsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Excellent (90-100%)', 'Good (80-89%)', 'Average (70-79%)', 'Poor (<70%)'],
                        datasets: [{
                            data: [
                                studentData.filter(s => s.score >= 90).length,
                                studentData.filter(s => s.score >= 80 && s.score < 90).length,
                                studentData.filter(s => s.score >= 70 && s.score < 80).length,
                                studentData.filter(s => s.score < 70).length
                            ],
                            backgroundColor: [
                                'rgba(56, 176, 0, 0.7)',
                                'rgba(58, 134, 255, 0.7)',
                                'rgba(255, 158, 0, 0.7)',
                                'rgba(230, 57, 70, 0.7)'
                            ],
                            borderColor: [
                                'rgba(56, 176, 0, 1)',
                                'rgba(58, 134, 255, 1)',
                                'rgba(255, 158, 0, 1)',
                                'rgba(230, 57, 70, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
                
                viewResultsModal.style.display = 'flex';
            }

            function generateStudentData(count) {
                const names = [
                    'Alex Johnson', 'Brianna Smith', 'Cameron Lee', 'Dylan Brown', 'Emma Wilson',
                    'Fiona Davis', 'George Miller', 'Hannah Taylor', 'Ian Anderson', 'Jessica Thomas',
                    'Kevin Martinez', 'Lily Garcia', 'Michael Robinson', 'Natalie Clark', 'Oliver Rodriguez',
                    'Paula Lewis', 'Quinn Walker', 'Rachel Hall', 'Samuel Young', 'Tiffany King'
                ];
                
                const data = [];
                for (let i = 0; i < count; i++) {
                    const score = Math.floor(Math.random() * 41) + 60;
                    let grade, status;
                    
                    if (score >= 90) {
                        grade = 'A';
                        status = 'Excellent';
                    } else if (score >= 80) {
                        grade = 'B';
                        status = 'Good';
                    } else if (score >= 70) {
                        grade = 'C';
                        status = 'Average';
                    } else {
                        grade = 'D';
                        status = 'Needs Improvement';
                    }
                    
                    data.push({
                        name: names[i % names.length],
                        score: score,
                        grade: grade,
                        status: status
                    });
                }
                
                return data;
            }

            closeResultsModal.addEventListener('click', function() {
                viewResultsModal.style.display = 'none';
            });

            closeResultsModalBtn.addEventListener('click', function() {
                viewResultsModal.style.display = 'none';
            });

            exportResultsBtn.addEventListener('click', function() {
                alert('Results exported successfully! This would download a CSV file with all student results.');
                
                this.innerHTML = '<i class="fas fa-check"></i> Exported';
                this.style.backgroundColor = '#38b000';
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-download"></i> Export Results';
                    this.style.backgroundColor = '';
                }, 2000);
            });

            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const assessmentItems = document.querySelectorAll('.assessment-item');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                
                if (searchTerm.length === 0) {
                    searchResults.style.display = 'none';
                    return;
                }
                
                const filteredItems = Array.from(assessmentItems).filter(item => {
                    const title = item.querySelector('.assessment-title').textContent.toLowerCase();
                    const topic = item.querySelector('.assessment-topic').textContent.toLowerCase();
                    return title.includes(searchTerm) || topic.includes(searchTerm);
                });
                
                searchResults.innerHTML = '';
                
                if (filteredItems.length === 0) {
                    searchResults.innerHTML = '<div class="no-results">No assessments found</div>';
                } else {
                    filteredItems.forEach(item => {
                        const resultItem = document.createElement('div');
                        resultItem.className = 'search-result-item';
                        resultItem.innerHTML = `
                            <div class="assessment-title">${item.querySelector('.assessment-title').textContent}</div>
                            <div class="assessment-topic">${item.querySelector('.assessment-topic').textContent}</div>
                        `;
                        
                        resultItem.addEventListener('click', function() {
                            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            
                            item.style.backgroundColor = 'rgba(58, 134, 255, 0.1)';
                            item.style.boxShadow = '0 0 0 2px rgba(58, 134, 255, 0.5)';
                            
                            setTimeout(() => {
                                item.style.backgroundColor = '';
                                item.style.boxShadow = '';
                            }, 2000);
                            
                            searchInput.value = '';
                            searchResults.style.display = 'none';
                        });
                        
                        searchResults.appendChild(resultItem);
                    });
                }
                
                searchResults.style.display = 'block';
            });

            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.style.display = 'none';
                }
            });

            function addAssessmentEventListeners(assessmentItem) {
                assessmentItem.querySelector('.results-btn').addEventListener('click', function() {
                    openResultsModal(assessmentItem);
                });
                
                assessmentItem.querySelector('.edit-btn').addEventListener('click', function() {
                    const assessmentTitle = assessmentItem.querySelector('.assessment-title');
                    const originalTitle = assessmentTitle.textContent;
                    
                    const newTitle = prompt('Edit assessment title:', originalTitle);
                    if (newTitle && newTitle !== originalTitle) {
                        assessmentTitle.textContent = newTitle;
                        assessmentItem.setAttribute('data-title', newTitle);
                        
                        this.innerHTML = '<i class="fas fa-check"></i> Saved';
                        this.style.backgroundColor = '#38b000';
                        this.style.borderColor = '#38b000';
                        this.style.color = 'white';
                        setTimeout(() => {
                            this.innerHTML = 'Edit';
                            this.style.backgroundColor = '';
                            this.style.borderColor = '';
                            this.style.color = '';
                        }, 2000);
                    }
                });
                
                assessmentItem.querySelector('.delete-btn').addEventListener('click', function() {
                    const assessmentItem = this.closest('.assessment-item');
                    const assessmentTitle = assessmentItem.querySelector('.assessment-title').textContent;
                    
                    if (confirm(`Are you sure you want to delete "${assessmentTitle}"?`)) {
                        assessmentItem.style.opacity = '0.5';
                        assessmentItem.style.transform = 'translateX(-100px)';
                        this.innerHTML = '<i class="fas fa-trash"></i> Deleting...';
                        
                        setTimeout(() => {
                            assessmentItem.remove();
                            alert(`"${assessmentTitle}" has been deleted successfully!`);
                        }, 1000);
                    }
                });
            }

            document.querySelectorAll('.assessment-item').forEach(item => {
                addAssessmentEventListeners(item);
            });

            document.querySelector('.user-avatar').addEventListener('click', function() {
                alert('Teacher Profile:\nName: Yee Ching\nRole: Teacher\nEmail: yeeching@crowedu.com\nLast Login: Today');
            });

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

            document.querySelectorAll('.footer-column a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    alert(`Navigating to: ${this.textContent} page`);
                });
            });

            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);
        });