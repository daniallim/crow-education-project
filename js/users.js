document.addEventListener('DOMContentLoaded', function () {
            let studentsData = [
                { name: 'Alice Tan', score: 86, lastActive: 'Nov 1', status: 'Active' },
                { name: 'John Lim', score: 42, lastActive: 'Oct 25', status: 'At Risk' },
                { name: 'Lim Shang Jin', score: 100, lastActive: 'Oct 29', status: 'Active' },
                { name: 'Siti Aminah', score: 45, lastActive: 'Oct 26', status: 'At Risk' },
                { name: 'David Chen', score: 92, lastActive: 'Nov 2', status: 'Active' }
            ];

            // 模态窗口元素
            const studentFormModal = document.getElementById('studentFormModal');
            const studentForm = document.getElementById('studentForm');
            const studentFormTitle = document.getElementById('studentFormTitle');
            const closeFormBtn = document.getElementById('closeFormBtn');
            const cancelFormBtn = document.getElementById('cancelFormBtn');
            const submitFormBtn = document.getElementById('submitFormBtn');
            
            // 表单字段
            const studentNameInput = document.getElementById('studentName');
            const studentScoreInput = document.getElementById('studentScore');
            const studentLastActiveInput = document.getElementById('studentLastActive');
            const studentStatusSelect = document.getElementById('studentStatus');
            
            // 错误消息
            const nameError = document.getElementById('nameError');
            const scoreError = document.getElementById('scoreError');
            const activeError = document.getElementById('activeError');
            
            // 当前编辑的学生ID
            let currentEditStudentId = null;

            function switchPage(page) {
                document.querySelectorAll('.dashboard-content, .class-container, .homework-content, .assessment-content, .calendar-content, .messages-content, .music-content, .settings-content').forEach(el => {
                    el.classList.remove('active');
                });
                
                const targetPage = document.getElementById(page + 'Content');
                if (targetPage) {
                    targetPage.classList.add('active');
                }
                
                document.getElementById('pageTitle').textContent = pageTitles[page] || 'Teacher Dashboard';
                
                document.querySelectorAll('.menu-item').forEach(item => {
                    item.classList.remove('active');
                });
                document.querySelector(`.menu-link[data-page="${page}"]`).closest('.menu-item').classList.add('active');
            }

            const pageTitles = {
                'dashboard': 'Teacher Dashboard',
                'classes': 'Manage Classes',
                'homework': 'Manage Homeworks',
                'assessment': 'Manage Assessments',
                'calendar': 'Manage Calendar',
                'messages': 'Manage Messages',
                'music': 'Manage Music',
                'settings': 'Manage Settings'
            };

            function calculateStats() {
                const totalStudents = studentsData.length;
                const activeStudents = studentsData.filter(student => student.status === 'Active').length;
                const riskStudents = studentsData.filter(student => student.status === 'At Risk').length;
                const topStudents = studentsData.filter(student => student.score >= 90).length;
                
                return {
                    total: totalStudents,
                    active: activeStudents,
                    risk: riskStudents,
                    top: topStudents
                };
            }

            function updateStats() {
                const stats = calculateStats();
                
                document.getElementById('totalStudents').textContent = stats.total;
                document.getElementById('activeStudents').textContent = stats.active;
                document.getElementById('riskStudents').textContent = stats.risk;
                document.getElementById('topStudents').textContent = stats.top;
                
                document.getElementById('classesBadge').textContent = stats.total;
            }

            function renderStudentsTable() {
                const tableBody = document.getElementById('studentsTableBody');
                tableBody.innerHTML = '';
                
                studentsData.forEach((student, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${student.name}</td>
                        <td>${student.score}%</td>
                        <td>${student.lastActive}</td>
                        <td class="status-${student.status === 'Active' ? 'active' : 'risk'}">${student.status}</td>
                        <td class="actions">
                            <a href="#" class="action-link edit-btn" data-student-id="${index}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <a href="#" class="action-link delete-btn" data-student-name="${student.name}">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                const tableContainer = document.querySelector('.table-container');
                if (studentsData.length > 7) {
                    tableContainer.classList.add('scroll-enabled');
                } else {
                    tableContainer.classList.remove('scroll-enabled');
                }
                
                updateStats();
            }

            // 打开添加学生表单
            document.getElementById('addStudentBtn').addEventListener('click', function() {
                openStudentForm();
            });

            // 打开编辑学生表单
            document.getElementById('studentsTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.edit-btn')) {
                    e.preventDefault();
                    const studentId = parseInt(e.target.closest('.edit-btn').getAttribute('data-student-id'));
                    openStudentForm(studentId);
                }
            });

            // 打开学生表单
            function openStudentForm(studentId = null) {
                currentEditStudentId = studentId;
                
                // 重置表单
                studentForm.reset();
                hideAllErrors();
                
                if (studentId === null) {
                    // 添加学生模式
                    studentFormTitle.textContent = 'Add Student';
                    studentStatusSelect.value = 'Active';
                } else {
                    // 编辑学生模式
                    studentFormTitle.textContent = 'Edit Student';
                    const student = studentsData[studentId];
                    studentNameInput.value = student.name;
                    studentScoreInput.value = student.score;
                    studentLastActiveInput.value = student.lastActive;
                    studentStatusSelect.value = student.status;
                }
                
                // 显示模态窗口
                studentFormModal.classList.add('active');
            }

            // 关闭表单
            function closeStudentForm() {
                studentFormModal.classList.remove('active');
                currentEditStudentId = null;
            }

            // 隐藏所有错误消息
            function hideAllErrors() {
                nameError.classList.remove('active');
                scoreError.classList.remove('active');
                activeError.classList.remove('active');
            }

            // 表单验证
            function validateForm() {
                let isValid = true;
                hideAllErrors();
                
                // 验证姓名
                if (!studentNameInput.value.trim()) {
                    nameError.classList.add('active');
                    isValid = false;
                }
                
                // 验证分数
                const score = parseInt(studentScoreInput.value);
                if (isNaN(score) || score < 0 || score > 100) {
                    scoreError.classList.add('active');
                    isValid = false;
                }
                
                // 验证最后活动日期
                if (!studentLastActiveInput.value.trim()) {
                    activeError.classList.add('active');
                    isValid = false;
                }
                
                return isValid;
            }

            // 表单提交
            studentForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    return;
                }
                
                const studentData = {
                    name: studentNameInput.value.trim(),
                    score: parseInt(studentScoreInput.value),
                    lastActive: studentLastActiveInput.value.trim(),
                    status: studentStatusSelect.value
                };
                
                if (currentEditStudentId === null) {
                    // 添加新学生
                    studentsData.push(studentData);
                } else {
                    // 更新现有学生
                    studentsData[currentEditStudentId] = studentData;
                }
                
                renderStudentsTable();
                closeStudentForm();
                
                // 显示成功消息
                const message = currentEditStudentId === null ? 
                    'Student added successfully!' : 'Student updated successfully!';
                alert(message);
            });

            // 关闭表单事件
            closeFormBtn.addEventListener('click', closeStudentForm);
            cancelFormBtn.addEventListener('click', closeStudentForm);

            // 点击模态窗口外部关闭
            studentFormModal.addEventListener('click', function(e) {
                if (e.target === studentFormModal) {
                    closeStudentForm();
                }
            });

            // 删除学生
            document.getElementById('studentsTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    const studentName = e.target.closest('.delete-btn').getAttribute('data-student-name');
                    
                    if (confirm(`Are you sure you want to delete ${studentName}?`)) {
                        studentsData = studentsData.filter(student => student.name !== studentName);
                        renderStudentsTable();
                        alert('Student deleted successfully!');
                    }
                }
            });

            document.getElementById('studentsTableBody').addEventListener('click', function(e) {
                if (e.target.closest('.status-active, .status-risk')) {
                    const statusCell = e.target.closest('td');
                    const row = statusCell.closest('tr');
                    const nameCell = row.cells[0];
                    const studentName = nameCell.textContent;
                    
                    const studentIndex = studentsData.findIndex(student => student.name === studentName);
                    if (studentIndex !== -1) {
                        studentsData[studentIndex].status = 
                            studentsData[studentIndex].status === 'Active' ? 'At Risk' : 'Active';
                        
                        renderStudentsTable();
                    }
                }
            });

            document.getElementById('studentSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#studentsTableBody tr');
                
                rows.forEach(row => {
                    const nameCell = row.cells[0];
                    const studentName = nameCell.textContent.toLowerCase();
                    
                    if (studentName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('status-filter').addEventListener('change', function() {
                const selectedStatus = this.value;
                const rows = document.querySelectorAll('#studentsTableBody tr');
                
                rows.forEach(row => {
                    const statusCell = row.cells[3];
                    const studentStatus = statusCell.textContent;
                    
                    if (selectedStatus === 'All status' || studentStatus === selectedStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });

            document.getElementById('globalSearch').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                if (searchTerm) {
                    switchPage('classes');
                    
                    document.getElementById('studentSearch').value = searchTerm;
                    
                    const event = new Event('input');
                    document.getElementById('studentSearch').dispatchEvent(event);
                }
            });

            renderStudentsTable();

            document.querySelectorAll('.menu-link').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const page = this.getAttribute('data-page');
                    switchPage(page);
                });
            });

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

            const notificationIcon = document.getElementById('notificationIcon');
            const notificationPanel = document.getElementById('notificationPanel');

            notificationIcon.addEventListener('click', function (e) {
                e.stopPropagation();
                notificationPanel.classList.toggle('active');
                userMenu.classList.remove('active');
                
                this.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    this.classList.remove('animate__animated', 'animate__shakeX');
                }, 500);
            });

            const userAvatar = document.getElementById('userAvatar');
            const userMenu = document.getElementById('userMenu');

            userAvatar.addEventListener('click', function (e) {
                e.stopPropagation();
                userMenu.classList.toggle('active');
                notificationPanel.classList.remove('active');
            });

            document.addEventListener('click', function() {
                notificationPanel.classList.remove('active');
                userMenu.classList.remove('active');
            });

            document.getElementById('profileBtn').addEventListener('click', function() {
                alert('Profile page would open here');
                userMenu.classList.remove('active');
            });

            document.getElementById('settingsBtn').addEventListener('click', function() {
                switchPage('settings');
                userMenu.classList.remove('active');
            });

            document.getElementById('logoutBtn').addEventListener('click', function() {
                if (confirm('Are you sure you want to logout?')) {
                    alert('Logging out...');
                }
                userMenu.classList.remove('active');
            });

            document.getElementById('viewStatsBtn').addEventListener('click', function() {
                alert('Opening statistics dashboard...');
            });

            document.getElementById('viewActivitiesBtn').addEventListener('click', function() {
                alert('Showing recent activities...');
            });

            document.getElementById('viewEventsBtn').addEventListener('click', function() {
                switchPage('calendar');
            });

            document.getElementById('assignHomeworkBtn').addEventListener('click', function() {
                alert('Opening homework assignment form...');
            });

            document.getElementById('viewSubmissionsBtn').addEventListener('click', function() {
                alert('Showing homework submissions...');
            });

            document.getElementById('templatesBtn').addEventListener('click', function() {
                alert('Opening homework templates...');
            });

            document.getElementById('createAssessmentBtn').addEventListener('click', function() {
                alert('Opening assessment creation tool...');
            });

            document.getElementById('gradeAssessmentsBtn').addEventListener('click', function() {
                alert('Opening assessment grading interface...');
            });

            document.getElementById('viewResultsBtn').addEventListener('click', function() {
                alert('Showing assessment results and analytics...');
            });

            document.getElementById('viewCalendarBtn').addEventListener('click', function() {
                alert('Opening calendar view...');
            });

            document.getElementById('addEventBtn').addEventListener('click', function() {
                alert('Opening event creation form...');
            });

            document.getElementById('manageScheduleBtn').addEventListener('click', function() {
                alert('Opening schedule management...');
            });

            document.getElementById('openInboxBtn').addEventListener('click', function() {
                alert('Opening message inbox...');
            });

            document.getElementById('composeMessageBtn').addEventListener('click', function() {
                alert('Opening message composer...');
            });

            document.getElementById('messageTemplatesBtn').addEventListener('click', function() {
                alert('Opening message templates...');
            });

            document.getElementById('musicLibraryBtn').addEventListener('click', function() {
                alert('Opening music library...');
            });

            document.getElementById('createPlaylistBtn').addEventListener('click', function() {
                alert('Opening playlist creator...');
            });

            document.getElementById('musicResourcesBtn').addEventListener('click', function() {
                alert('Opening music resources...');
            });

            document.getElementById('accountSettingsBtn').addEventListener('click', function() {
                alert('Opening account settings...');
            });

            document.getElementById('notificationSettingsBtn').addEventListener('click', function() {
                alert('Opening notification settings...');
            });

            document.getElementById('privacySettingsBtn').addEventListener('click', function() {
                alert('Opening privacy settings...');
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

            document.getElementById('birdTeamLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Bird2 Team: Meet the talented developers behind Crow Education!');
            });

            document.getElementById('emailLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Email us at: birdbird945@gmail.com');
            });

            document.getElementById('classLink').addEventListener('click', function(e) {
                e.preventDefault();
                switchPage('classes');
                alert('Navigating to Class Management...');
            });

            document.getElementById('discussionHubLink').addEventListener('click', function(e) {
                e.preventDefault();
                switchPage('messages');
                alert('Opening Discussion Hub...');
            });

            document.getElementById('subjectFinderLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Subject Finder: Find the perfect learning materials for your subjects!');
            });

            document.getElementById('knowledgeHubLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Knowledge Hub: Access our extensive library of educational resources!');
            });

            document.getElementById('studyMethodLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Study Methods: Discover effective learning techniques and strategies!');
            });

            document.getElementById('feedbackLink').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Feedback: We value your input! Share your thoughts with us.');
            });
        });