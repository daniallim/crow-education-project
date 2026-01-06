// =====================
// Show / Hide Password
// =====================
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    // Toggle icons
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');

    // Small animation
    this.style.transform = 'translateY(-50%) scale(1.2)';
    setTimeout(() => {
        this.style.transform = 'translateY(-50%) scale(1)';
    }, 200);
});

// =====================
// Login Logic - 使用真实的 PHP 后端
// =====================
const emailInput = document.getElementById('email');
const loginBtn = document.getElementById('loginBtn');
const errorMessage = document.getElementById('errorMessage');
const successMessage = document.getElementById('successMessage');

async function handleLogin() {
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    // Hide previous messages
    errorMessage.style.display = "none";
    successMessage.style.display = "none";

    if (!email || !password) {
        errorMessage.style.display = "block";
        errorMessage.innerHTML = "<i class='fas fa-exclamation-circle'></i> Please enter email and password.";
        return;
    }

    // Disable button during process
    loginBtn.disabled = true;
    loginBtn.innerHTML = "<i class='fas fa-spinner fa-spin'></i> Signing in...";

    try {
        console.log("发送登录请求到 PHP 后端");

        const formData = new FormData();
        formData.append('email', email);
        formData.append('password', password);

        const response = await fetch('', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        console.log("PHP 响应:", result);

        if (result.success) {
            successMessage.style.display = 'block';
            successMessage.innerHTML = "<i class='fas fa-check-circle'></i> " + result.success;


            setTimeout(() => {
                window.location.href = "index.php";
            }, 2000);

        } else {
            errorMessage.style.display = 'block';
            errorMessage.innerHTML = "<i class='fas fa-exclamation-circle'></i> " + (result.error || 'Login failed');
            passwordInput.value = '';
        }

    } catch (error) {
        console.error('登录错误:', error);
        errorMessage.style.display = 'block';
        errorMessage.innerHTML = "<i class='fas fa-exclamation-circle'></i> Login failed. Please try again.";
        passwordInput.value = '';
    } finally {
        loginBtn.disabled = false;
        loginBtn.innerHTML = "Sign In";
    }
}

// =====================
// Event Listeners
// =====================
loginBtn.addEventListener('click', handleLogin);
document.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !loginBtn.disabled) handleLogin();
});