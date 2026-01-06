        let selectedCategory = null;

        function selectCategory(category) {
            // Remove selected class from both buttons
            document.getElementById('student-btn').classList.remove('selected');
            document.getElementById('teacher-btn').classList.remove('selected');

            // Add selected class to clicked button
            document.getElementById(`${category}-btn`).classList.add('selected');

            // Store selected category
            selectedCategory = category;

            // Enable continue button
            document.getElementById('continueBtn').disabled = false;

            // Add animation effect
            const btn = document.getElementById(`${category}-btn`);
            btn.style.transform = 'translateY(-8px) scale(1.05)';
            setTimeout(() => {
                btn.style.transform = 'translateY(-5px) scale(1)';
            }, 200);
        }

        function continueNext() {
            if (!selectedCategory) {
                alert('Please select a category to continue.');
                return;
            }

            // Add loading animation
            const continueBtn = document.getElementById('continueBtn');
            continueBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirecting...';
            continueBtn.disabled = true;

            // Simulate API call or processing
            setTimeout(() => {
                // Redirect to register page with selected category
                window.location.href = `register.php?role=${selectedCategory}`;
            }, 1000);
        }

        // Add keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === '1') {
                selectCategory('student');
            } else if (e.key === '2') {
                selectCategory('teacher');
            } else if (e.key === 'Enter' && selectedCategory) {
                continueNext();
            }
        });

        // Add hover effects with animation
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-8px)';
            });

            btn.addEventListener('mouseleave', function () {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(0)';
                } else {
                    this.style.transform = 'translateY(-5px)';
                }
            });
        });