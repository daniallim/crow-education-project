        // Show/Hide Password Functionality
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');

            // Add animation effect
            this.style.transform = 'translateY(-50%) scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'translateY(-50%) scale(1)';
            }, 200);
        });


        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const confirmPassword = document.getElementById('confirmPassword');
        const registerBtn = document.getElementById('registerBtn');
        const matchMsg = document.getElementById('matchMsg');
        const successMessage = document.getElementById('successMessage');
        const btnText = document.querySelector('.btn-text');
        const loading = document.querySelector('.loading');

        const lengthRule = document.getElementById('lengthRule');
        const numberRule = document.getElementById('numberRule');
        const symbolRule = document.getElementById('symbolRule');

        // Input icons
        const nameIcon = document.querySelector('#name + .input-icon');
        const emailIcon = document.querySelector('#email + .input-icon');
        const passwordIcon = document.querySelector('#password + .input-icon');
        const confirmPasswordIcon = document.querySelector('#confirmPassword + .input-icon');

        function validateName() {
            const name = nameInput.value.trim();
            const isValid = name.length >= 2;

            if (name.length === 0) {
                nameInput.classList.remove('valid', 'invalid');
                nameIcon.classList.remove('valid', 'invalid');
            } else if (isValid) {
                nameInput.classList.remove('invalid');
                nameInput.classList.add('valid');
                nameIcon.classList.remove('invalid');
                nameIcon.classList.add('valid');
            } else {
                nameInput.classList.remove('valid');
                nameInput.classList.add('invalid');
                nameIcon.classList.remove('valid');
                nameIcon.classList.add('invalid');
            }

            return isValid;
        }

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
            }

            return isValid;
        }

        function validatePassword() {
            const pass = passwordInput.value;
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
                passwordIcon.classList.remove('valid', 'invalid');
            } else if (validCount === 3) {
                passwordIcon.classList.remove('invalid');
                passwordIcon.classList.add('valid');
            } else {
                passwordIcon.classList.remove('valid');
                passwordIcon.classList.add('invalid');
            }

            // Confirm password validation
            if (confirmPassword.value === pass && pass.length > 0) {
                matchMsg.style.color = 'green';
                matchMsg.innerHTML = '<i class="fas fa-check"></i> Passwords match';
                confirmPassword.classList.remove('invalid');
                confirmPassword.classList.add('valid');
                confirmPasswordIcon.classList.remove('invalid');
                confirmPasswordIcon.classList.add('valid');
            } else if (confirmPassword.value.length > 0) {
                matchMsg.style.color = 'red';
                matchMsg.innerHTML = '<i class="fas fa-times"></i> Passwords do not match';
                confirmPassword.classList.remove('valid');
                confirmPassword.classList.add('invalid');
                confirmPasswordIcon.classList.remove('valid');
                confirmPasswordIcon.classList.add('invalid');
            } else {
                matchMsg.textContent = '';
                confirmPassword.classList.remove('valid', 'invalid');
                confirmPasswordIcon.classList.remove('valid', 'invalid');
            }

            // Enable/disable register button
            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPasswordValid = validCount === 3;
            const isConfirmPasswordValid = confirmPassword.value === pass && pass.length > 0;

            registerBtn.disabled = !(isNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid);

            return validCount === 3;
        }

        // Event listeners
        nameInput.addEventListener('input', validateName);
        emailInput.addEventListener('input', validateEmail);
        passwordInput.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);

        // Get the ?role=student or ?role=teacher parameter from URL
        const params = new URLSearchParams(window.location.search);
        const role = params.get("role");

        if (role) {
            console.log("Selected category:", role);
            // Example: show it in the title or pre-fill a hidden input
            document.title = "Register (" + role.charAt(0).toUpperCase() + role.slice(1) + ") - Crow Education";
        }

// API Registration
registerBtn.addEventListener("click", async () => {
    const userData = {
        name: nameInput.value.trim(),
        email: emailInput.value.trim(),
        password: passwordInput.value.trim(),
        role: role || "student"
    };

    try {
        // Start loading animation
        btnText.style.opacity = "0";
        loading.style.display = "block";
        registerBtn.disabled = true;

        const formData = new FormData();
        formData.append('name', userData.name);
        formData.append('email', userData.email);
        formData.append('password', userData.password);

        // send data to different endpoints based on role
        const response = await fetch('', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        // Stop loading animation
        loading.style.display = "none";
        btnText.style.opacity = "1";

        if (result.success) {
            successMessage.style.display = "block";
            successMessage.innerHTML = `<i class="fas fa-check-circle"></i> ${result.success}`;


            setTimeout(() => {
                window.location.href = "../user/login.php";
            }, 2000);
        } else {
            // display error message
            alert(result.error || 'Registration failed');
            registerBtn.disabled = false;
        }
    } catch (err) {
        loading.style.display = "none";
        btnText.style.opacity = "1";
        alert('Registration failed. Please try again.');
        registerBtn.disabled = false;
        console.error('Error:', err);
    }
});