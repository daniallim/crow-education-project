document.addEventListener('DOMContentLoaded', function() {
    
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

            darkModeToggle.addEventListener('click', function() {
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
                item.addEventListener('click', function() {
                    menuItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            const notificationIcon = document.querySelector('.notification-icon');
            notificationIcon.addEventListener('click', function() {
                alert('You have 5 new notifications');
            });
            document.querySelector('.user-avatar').addEventListener('click', function() {
                alert('Teacher Profile: Yee Ching');
            });

            const headerSearch = document.querySelector('.search-box input');
            headerSearch.addEventListener('input', function() {
                if (this.value.length > 2) {
                    console.log('Searching for:', this.value);
                }
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

            
            const filterTabs = document.querySelectorAll('.filter-tab');
            const lessonItems = document.querySelectorAll('.lesson-item');
            const searchInput = document.getElementById('searchInput');

        
            filterTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    filterTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.dataset.filter;
                    
                    lessonItems.forEach(item => {
                        if (filter === 'all' || item.dataset.status === filter) {
                            item.style.display = 'block';
                            item.classList.add('fade-in');
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });


            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                lessonItems.forEach(item => {
                    const title = item.querySelector('.lesson-title').textContent.toLowerCase();
                    const subject = item.querySelector('.lesson-subject').textContent.toLowerCase();
                    
                    if (title.includes(searchTerm) || subject.includes(searchTerm)) {
                        item.style.display = 'block';
                        item.classList.add('fade-in');
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lessonItem = this.closest('.lesson-item');
                    const title = lessonItem.querySelector('.lesson-title');
                    const newTitle = prompt('Edit lesson title:', title.textContent);
                    
                    if (newTitle) {
                        title.textContent = newTitle;
                        this.innerHTML = '<i class="fas fa-check"></i> Saved';
                        setTimeout(() => {
                            this.innerHTML = '<i class="fas fa-edit"></i> Edit';
                        }, 2000);
                    }
                });
            });

            document.querySelectorAll('.preview-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lessonItem = this.closest('.lesson-item');
                    const title = lessonItem.querySelector('.lesson-title').textContent;
                    alert('Previewing: ' + title);
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lessonItem = this.closest('.lesson-item');
                    const title = lessonItem.querySelector('.lesson-title').textContent;
                    
                    if (confirm('Delete "' + title + '"?')) {
                        lessonItem.style.opacity = '0.5';
                        setTimeout(() => {
                            lessonItem.remove();
                        }, 500);
                    }
                });
            });

            document.querySelectorAll('.publish-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const lessonItem = this.closest('.lesson-item');
                    const title = lessonItem.querySelector('.lesson-title').textContent;
                    const badge = lessonItem.querySelector('.status-badge');
                    const dateValue = lessonItem.querySelector('.date-value');
                    
                    if (confirm('Publish "' + title + '" to students?')) {
                        
                        lessonItem.dataset.status = 'published';
                        badge.textContent = 'Published';
                        badge.className = 'status-badge status-published';
            
                        const now = new Date();
                        const options = { year: 'numeric', month: 'short', day: 'numeric' };
                        const formattedDate = now.toLocaleDateString('en-US', options);
            
                        dateValue.textContent = formattedDate;
                        dateValue.classList.add('date-update');
                
                        this.remove();
            
                        alert('Lesson published successfully!');
                        
                        setTimeout(() => {
                            dateValue.classList.remove('date-update');
                        }, 500);
                    }
                });
            });

            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const selectFilesBtn = document.getElementById('selectFilesBtn');
            const fileList = document.getElementById('fileList');
            const confirmUploadBtn = document.getElementById('confirmUploadBtn');

            uploadArea.addEventListener('click', function(e) {
                if (e.target !== selectFilesBtn) {
                    fileInput.click();
                }
            });

            selectFilesBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });

            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.style.borderColor = 'var(--primary)';
                uploadArea.style.backgroundColor = 'rgba(58, 134, 255, 0.1)';
            });

            uploadArea.addEventListener('dragleave', function() {
                uploadArea.style.borderColor = 'var(--border-color)';
                uploadArea.style.backgroundColor = 'var(--light)';
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.style.borderColor = 'var(--border-color)';
                uploadArea.style.backgroundColor = 'var(--light)';
                
                if (e.dataTransfer.files.length) {
                    handleFiles(e.dataTransfer.files);
                }
            });

            function handleFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    addFileToList(file);
                }
                if (files.length > 0) {
                    confirmUploadBtn.style.display = 'inline-flex';
                }
            }

            function addFileToList(file) {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                
                const fileSize = formatFileSize(file.size);
                
                fileItem.innerHTML = `
                    <div class="file-info">
                        <div>
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${fileSize}</div>
                        </div>
                    </div>
                    <div class="file-remove">
                        <i class="fas fa-times"></i>
                    </div>
                `;
                
                fileList.appendChild(fileItem);
                
                const removeBtn = fileItem.querySelector('.file-remove');
                removeBtn.addEventListener('click', function() {
                    fileItem.remove();
                    if (fileList.children.length === 0) {
                        confirmUploadBtn.style.display = 'none';
                    }
                });
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            confirmUploadBtn.addEventListener('click', function() {
                if (fileList.children.length === 0) {
                    alert('Please select files first!');
                    return;
                }
                
                const fileCount = fileList.children.length;
                alert(`Successfully uploaded ${fileCount} file(s)!`);
                
                fileList.innerHTML = '';
                fileInput.value = '';
                this.style.display = 'none';
            });
        });