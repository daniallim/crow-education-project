
        // DOM Elements
        const emailInput = document.getElementById('email');
        const submitBtn = document.getElementById('submitBtn');
        const previousBtn = document.getElementById('previousBtn');
        const successMessage = document.getElementById('successMessage');
        const btnText = document.querySelector('.btn-text');
        const loading = document.querySelector('.loading');
        const emailStep = document.getElementById('emailStep');
        const captchaStep = document.getElementById('captchaStep');
        const passwordStep = document.getElementById('passwordStep');
        const captchaError = document.getElementById('captchaError');
        const newPassword = document.getElementById('newPassword');
        const confirmNewPassword = document.getElementById('confirmNewPassword');
        const matchMsg = document.getElementById('matchMsg');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');

        // CAPTCHA elements
        const dragContainer = document.getElementById('dragContainer');
        const dropZone = document.getElementById('dropZone');
        const captchaFeedback = document.getElementById('captchaFeedback');
        const refreshCaptcha = document.getElementById('refreshCaptcha');
        const instructionText = document.getElementById('instructionText');
        const dropZoneText = document.getElementById('dropZoneText');

        // Password validation elements
        const lengthRule = document.getElementById('lengthRule');
        const numberRule = document.getElementById('numberRule');
        const symbolRule = document.getElementById('symbolRule');

        // Input icons
        const emailIcon = document.querySelector('#email + .input-icon');
        const newPasswordIcon = document.querySelector('#newPassword + .input-icon');
        const confirmNewPasswordIcon = document.querySelector('#confirmNewPassword + .input-icon');

        // Current step tracking
        let currentStep = 1;
        let resetToken = '';
        let correctItem = ''; // Will be set dynamically
        let draggedItem = null;
        let isVerified = false;

        // Available verification challenges
        const challenges = [
            {
                correctItem: 'book',
                instruction: 'Drag the <strong>book icon</strong> into the drop zone to verify you\'re human',
                dropZoneText: 'Drag the book here'
            },
            {
                correctItem: 'car',
                instruction: 'Drag the <strong>car icon</strong> into the drop zone to verify you\'re human',
                dropZoneText: 'Drag the car here'
            },
            {
                correctItem: 'key',
                instruction: 'Drag the <strong>key icon</strong> into the drop zone to verify you\'re human',
                dropZoneText: 'Drag the key here'
            },
            {
                correctItem: 'star',
                instruction: 'Drag the <strong>star icon</strong> into the drop zone to verify you\'re human',
                dropZoneText: 'Drag the star here'
            },
            {
                correctItem: 'tree',
                instruction: 'Drag the <strong>tree icon</strong> into the drop zone to verify you\'re human',
                dropZoneText: 'Drag the tree here'
            }
        ];

        // Create floating particles for background
        function createParticles() {
            const particlesContainer = document.getElementById('particlesContainer');
            const particleCount = 30;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');

                // Random properties
                const size = Math.floor(Math.random() * 10) + 5;
                const left = Math.floor(Math.random() * 100);
                const delay = Math.random() * 20;
                const duration = Math.floor(Math.random() * 10) + 15;

                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${left}%`;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;

                particlesContainer.appendChild(particle);
            }
        }

        // Generate CAPTCHA items with random challenge
        function generateCaptchaItems() {
            dragContainer.innerHTML = '';
            isVerified = false;
            submitBtn.disabled = true;
            captchaFeedback.textContent = '';
            dropZone.classList.remove('success', 'error');
            dropZone.classList.add('hover');

            // Select a random challenge
            const randomChallenge = challenges[Math.floor(Math.random() * challenges.length)];
            correctItem = randomChallenge.correctItem;

            // Update instructions
            instructionText.innerHTML = randomChallenge.instruction;
            dropZoneText.textContent = randomChallenge.dropZoneText;

            // Reset drop zone content
            dropZone.innerHTML = `
                <div class="drop-zone-content">
                    <i class="fas fa-arrow-down"></i>
                    <p>${randomChallenge.dropZoneText}</p>
                </div>
            `;

            // Define possible items
            const items = [
                { type: 'book', icon: 'fas fa-book', color: '#4e73df' },
                { type: 'car', icon: 'fas fa-car', color: '#1cc88a' },
                { type: 'tree', icon: 'fas fa-tree', color: '#36b9cc' },
                { type: 'star', icon: 'fas fa-star', color: '#f6c23e' },
                { type: 'key', icon: 'fas fa-key', color: '#e74a3b' },
                { type: 'home', icon: 'fas fa-home', color: '#858796' },
                { type: 'heart', icon: 'fas fa-heart', color: '#e83e8c' },
                { type: 'flag', icon: 'fas fa-flag', color: '#6f42c1' }
            ];

            // Shuffle items and ensure the correct one is included
            let shuffledItems = [...items].sort(() => Math.random() - 0.5);

            // Make sure the correct item is in the selection
            if (!shuffledItems.slice(0, 4).some(item => item.type === correctItem)) {
                // Replace a random item with the correct one
                const randomIndex = Math.floor(Math.random() * 4);
                shuffledItems[randomIndex] = items.find(item => item.type === correctItem);
            }

            // Create draggable items
            shuffledItems.slice(0, 4).forEach(item => {
                const draggable = document.createElement('div');
                draggable.className = 'draggable-item';
                draggable.setAttribute('draggable', 'true');
                draggable.setAttribute('data-type', item.type);
                draggable.style.backgroundColor = item.color;

                draggable.innerHTML = `<i class="${item.icon}" style="font-size: 2rem; color: white;"></i>`;

                // Add event listeners for drag and drop
                draggable.addEventListener('dragstart', handleDragStart);
                draggable.addEventListener('dragend', handleDragEnd);

                dragContainer.appendChild(draggable);
            });
        }

        // Handle drag start
        function handleDragStart(e) {
            draggedItem = this;
            this.classList.add('dragging');
            e.dataTransfer.setData('text/plain', this.getAttribute('data-type'));

            // Visual feedback for drop zone
            dropZone.classList.add('hover');
        }

        // Handle drag end
        function handleDragEnd() {
            this.classList.remove('dragging');
            dropZone.classList.remove('hover');
        }

        // Handle drop zone events
        dropZone.addEventListener('dragover', function (e) {
            e.preventDefault();
            this.classList.add('hover');
        });

        dropZone.addEventListener('dragleave', function () {
            this.classList.remove('hover');
        });

        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            this.classList.remove('hover');

            const itemType = e.dataTransfer.getData('text/plain');

            // Check if correct item was dropped
            if (itemType === correctItem) {
                this.classList.add('success');
                this.classList.remove('error');
                this.innerHTML = `
                    <div class="drop-zone-content">
                        <i class="fas fa-check-circle" style="color: #28a745;"></i>
                        <p>Verification successful!</p>
                    </div>
                `;

                captchaFeedback.textContent = '✓ Human verification successful!';
                captchaFeedback.className = 'captcha-feedback valid';

                isVerified = true;
                submitBtn.disabled = false;

                // Mark the correct item
                document.querySelectorAll('.draggable-item').forEach(item => {
                    if (item.getAttribute('data-type') === correctItem) {
                        item.classList.add('correct');
                    }
                });
            } else {
                this.classList.add('error');
                this.classList.remove('success');
                this.innerHTML = `
                    <div class="drop-zone-content">
                        <i class="fas fa-times-circle" style="color: #dc3545;"></i>
                        <p>Incorrect item. Try again.</p>
                    </div>
                `;

                captchaFeedback.textContent = `✗ Please drag the ${getItemName(correctItem)} icon`;
                captchaFeedback.className = 'captcha-feedback invalid';

                isVerified = false;
                submitBtn.disabled = true;

                // Mark the incorrect item
                if (draggedItem) {
                    draggedItem.classList.add('incorrect');
                    setTimeout(() => {
                        draggedItem.classList.remove('incorrect');
                    }, 1000);
                }
            }
        });

        // Helper function to get item name for feedback
        function getItemName(itemType) {
            const itemNames = {
                'book': 'book',
                'car': 'car',
                'key': 'key',
                'star': 'star',
                'tree': 'tree',
                'home': 'house',
                'heart': 'heart',
                'flag': 'flag'
            };
            return itemNames[itemType] || itemType;
        }

        // Update step indicator
        function updateStepIndicator() {
            step1.classList.remove('active', 'completed');
            step2.classList.remove('active', 'completed');
            step3.classList.remove('active', 'completed');
            line1.classList.remove('completed');
            line2.classList.remove('completed');

            if (currentStep === 1) {
                step1.classList.add('active');
                previousBtn.style.display = 'none';
            } else if (currentStep === 2) {
                step1.classList.add('completed');
                step2.classList.add('active');
                line1.classList.add('completed');
                previousBtn.style.display = 'flex';
            } else if (currentStep === 3) {
                step1.classList.add('completed');
                step2.classList.add('completed');
                step3.classList.add('active');
                line1.classList.add('completed');
                line2.classList.add('completed');
                previousBtn.style.display = 'flex';
            }
        }

        // Validate email format
        function validateEmail() {
            const email = emailInput.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isValid = emailRegex.test(email);

            if (email.length === 0) {
                emailInput.classList.remove('valid', 'invalid');
                emailIcon.classList.remove('valid', 'invalid');
            } else if (isValid) {
                emailInput.classList.remove('invalid');
                emailInput.classList.add('valid');
                emailIcon.classList.remove('invalid');
                emailIcon.classList.add('valid');
            } else {
                emailInput.classList.remove('valid');
                emailInput.classList.add('invalid');
                emailIcon.classList.remove('valid');
                emailIcon.classList.add('invalid');
                emailInput.classList.add('shake');
                setTimeout(() => emailInput.classList.remove('shake'), 500);
            }

            return isValid;
        }

        // Validate password
function validatePassword() {
    const pass = newPassword.value;
    let validCount = 0;

    // Length validation
    if (pass.length >= 8 && pass.length <= 12) {
        lengthRule.classList.add('valid');
        lengthRule.textContent = '✅ 8–12 characters';
        validCount++;
    } else {
        lengthRule.classList.remove('valid');
        lengthRule.textContent = '❌ 8–12 characters';
    }

    // Number validation
    if (/\d/.test(pass)) {
        numberRule.classList.add('valid');
        numberRule.textContent = '✅ At least one number (0–9)';
        validCount++;
    } else {
        numberRule.classList.remove('valid');
        numberRule.textContent = '❌ At least one number (0–9)';
    }

    // Symbol validation
    if (/[!@#$%^&*(),.?":{}|<>]/.test(pass)) {
        symbolRule.classList.add('valid');
        symbolRule.textContent = '✅ At least one special symbol (e.g. @, #, $)';
        validCount++;
    } else {
        symbolRule.classList.remove('valid');
        symbolRule.textContent = '❌ At least one special symbol (e.g. @, #, $)';
    }

    // Password icon
    if (pass.length === 0) {
        newPasswordIcon.classList.remove('valid', 'invalid');
    } else if (validCount === 3) {
        newPasswordIcon.classList.remove('invalid');
        newPasswordIcon.classList.add('valid');
    } else {
        newPasswordIcon.classList.remove('valid');
        newPasswordIcon.classList.add('invalid');
        newPassword.classList.add('shake');
        setTimeout(() => newPassword.classList.remove('shake'), 500);
    }

    // Confirm password validation
    if (confirmNewPassword.value === pass && pass.length > 0) {
        matchMsg.style.color = 'green';
        matchMsg.innerHTML = '<i class="fas fa-check"></i> Passwords match';
        confirmNewPassword.classList.remove('invalid');
        confirmNewPassword.classList.add('valid');
        confirmNewPasswordIcon.classList.remove('invalid');
        confirmNewPasswordIcon.classList.add('valid');
    } else if (confirmNewPassword.value.length > 0) {
        matchMsg.style.color = 'red';
        matchMsg.innerHTML = '<i class="fas fa-times"></i> Passwords do not match';
        confirmNewPassword.classList.remove('valid');
        confirmNewPassword.classList.add('invalid');
        confirmNewPasswordIcon.classList.remove('valid');
        confirmNewPasswordIcon.classList.add('invalid');
        confirmNewPassword.classList.add('shake');
        setTimeout(() => confirmNewPassword.classList.remove('shake'), 500);
    } else {
        matchMsg.textContent = '';
        confirmNewPassword.classList.remove('valid', 'invalid');
        confirmNewPasswordIcon.classList.remove('valid', 'invalid');
    }

  const isPasswordValid = validCount === 3;
    const isConfirmPasswordValid = confirmNewPassword.value === pass && pass.length > 0;
    const isAllValid = isPasswordValid && isConfirmPasswordValid;
    
    console.log("Password reset result:", {
        isPasswordValid,
        isConfirmPasswordValid, 
        isAllValid,
        currentStep
    });
    
    // Only enable submit button on step 3 when all validations pass
      if (currentStep === 3) {
        console.log("🔄 Update button:", !isAllValid);
        submitBtn.disabled = !isAllValid;
    } else {
        // Other steps do not affect the button state here
        submitBtn.disabled = !isAllValid;
    }

    return isAllValid;
}

// Go to previous step
function goToPreviousStep() {
    console.log("🔙 Move back previous step:", currentStep);
    
    if (currentStep === 2) {
        // Step 2 to Step 1
        captchaStep.style.display = 'none';
        emailStep.style.display = 'block';
        currentStep = 1;
        btnText.textContent = 'Verify Email';
        updateStepIndicator();
        submitBtn.disabled = !validateEmail();
        
        //  Send request to backend to update step status
        updateStepOnServer(1);
        
    } else if (currentStep === 3) {
        // Step 3 to Step 2
        passwordStep.style.display = 'none';
        captchaStep.style.display = 'block';
        currentStep = 2;
        btnText.textContent = 'Verify';
        updateStepIndicator();
        submitBtn.disabled = !isVerified;
        
        // Regenenerate CAPTCHA items
        generateCaptchaItems();
        
        // After sending back to step 2, inform server
        updateStepOnServer(2);
    }
}

// Update server about current step
async function updateStepOnServer(targetStep) {
    try {
        const formData = new FormData();
        formData.append('action', 'go_back');
        formData.append('step', targetStep);

        const response = await fetch('', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log("Step updated respond:", result);
        
        if (!result.success) {
            console.error("Step updated failed:", result.error);
        }
    } catch (error) {
        console.error("Step updated error:", error);
    }
}



// Move to next step 
async function goToNextStep() {
    if (currentStep === 1) {
        // Validate email and show CAPTCHA
        if (validateEmail()) {
            try {
                btnText.style.opacity = '0';
                loading.style.display = 'block';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('action', 'request_reset');
                formData.append('email', emailInput.value.trim());

                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log("PHP responded:", result);

                loading.style.display = 'none';
                btnText.style.opacity = '1';

                if (result.success) {
                    emailStep.style.display = 'none';
                    captchaStep.style.display = 'block';
                    currentStep = 2;
                    btnText.textContent = 'Verify';
                    updateStepIndicator();
                    submitBtn.disabled = true;

                    generateCaptchaItems();
                } else {
                    alert(result.error || 'Failed to proceed. Please try again.');
                }

            } catch (error) {
                loading.style.display = 'none';
                btnText.style.opacity = '1';
                alert('Failed to proceed. Please try again.');
            }
        }
    } else if (currentStep === 2) {
        // Verify CAPTCHA and show password step
        if (isVerified) {
            try {
                btnText.style.opacity = '0';
                loading.style.display = 'block';
                submitBtn.disabled = true;

                const formData = new FormData();
                formData.append('action', 'verify_captcha');
                formData.append('email', emailInput.value.trim());

                const response = await fetch('', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                console.log("CAPTCHA verify respond:", result);

                loading.style.display = 'none';
                btnText.style.opacity = '1';

                if (result.success) {
                    resetToken = result.token;
                    captchaStep.style.display = 'none';
                    passwordStep.style.display = 'block';
                    currentStep = 3;
                    btnText.textContent = 'Reset Password';
                    captchaError.style.display = 'none';
                    updateStepIndicator();
                    
                    // Entering step 3, disable button until password is valid
                    setTimeout(() => {
                        validatePassword();
                    }, 100);
                } else {
                    captchaError.textContent = result.error || 'Verification failed. Please try again.';
                    captchaError.style.display = 'block';
                    generateCaptchaItems();
                }

            } catch (error) {
                loading.style.display = 'none';
                btnText.style.opacity = '1';
                captchaError.textContent = 'Verification failed. Please try again.';
                captchaError.style.display = 'block';
                generateCaptchaItems();
            }
        }
    } else if (currentStep === 3) {
        // Validate and reset password
        if (validatePassword()) {
            handlePasswordReset();
        }
    }
}

// reset password 
async function handlePasswordReset() {
    console.log("🔍 send reset password premission:", {
        email: emailInput.value.trim(),
        newPassword: newPassword.value,
        confirmPassword: confirmNewPassword.value,
        token: resetToken
    });

    btnText.style.opacity = '0';
    loading.style.display = 'block';
    submitBtn.disabled = true;
    previousBtn.disabled = true;

    try {

        const formData = new FormData();
        formData.append('action', 'reset_password');
        formData.append('email', emailInput.value.trim());
        formData.append('new_password', newPassword.value);
        formData.append('confirm_password', confirmNewPassword.value);
        formData.append('token', resetToken);

        const response = await fetch('', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log("reset password respond :", result);

        loading.style.display = 'none';
        btnText.style.opacity = '1';

        if (result.success) {
            successMessage.style.display = 'block';
            successMessage.innerHTML = `<i class="fas fa-check-circle"></i> ${result.message}`;
            passwordStep.style.display = 'none';
            submitBtn.style.display = 'none';
            previousBtn.style.display = 'none';

            // Redirect to login after short delay
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 3000);
        } else {
            alert(result.error || 'Password reset failed. Please try again.');
            submitBtn.disabled = false;
            previousBtn.disabled = false;
        }

    } catch (error) {
        console.error('reset password error:', error);
        loading.style.display = 'none';
        btnText.style.opacity = '1';
        submitBtn.disabled = false;
        previousBtn.disabled = false;
        alert('Password reset failed. Please try again.');
    }
}


        // Event listeners
        emailInput.addEventListener('input', () => {
            submitBtn.disabled = !validateEmail();
        });

        newPassword.addEventListener('input', validatePassword);
        confirmNewPassword.addEventListener('input', validatePassword);

        submitBtn.addEventListener('click', goToNextStep);
        previousBtn.addEventListener('click', goToPreviousStep);

        // Refresh CAPTCHA
        refreshCaptcha.addEventListener('click', generateCaptchaItems);

        // Initialize
        submitBtn.disabled = true;
        updateStepIndicator();
        createParticles();