// Login form functionality
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const rememberMeCheckbox = document.getElementById('rememberMe');

    // Load saved email if remember me was checked
    loadSavedCredentials();

    // Form submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const username = usernameInput.value.trim();
            const password = passwordInput.value;
            const remember = rememberMeCheckbox.checked;

            // Validation
            if (!username) {
                showMessage('Please enter your username or email', 'error');
                usernameInput.focus();
                return;
            }

            if (!password) {
                showMessage('Please enter your password', 'error');
                passwordInput.focus();
                return;
            }

            // Save credentials if remember me is checked
            if (remember) {
                localStorage.setItem('rememberedUsername', username);
            } else {
                localStorage.removeItem('rememberedUsername');
            }

            // Submit login via AJAX
            submitLogin({
                username: username,
                password: password,
                remember: remember
            });
        });
    }

    function submitLogin(data) {
        const submitBtn = document.querySelector('.btn-login');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Logging in...';
        submitBtn.disabled = true;

        // AJAX 提交到 WordPress
        const formData = new FormData();
        formData.append('action', 'rena_login');
        formData.append('username', data.username);
        formData.append('password', data.password);
        formData.append('remember', data.remember);
        formData.append('security', document.getElementById('login-nonce').value);

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                showMessage(result.data.message || 'Login successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = result.data.redirect || '/downloads/';
                }, 1000);
            } else {
                showMessage(result.data.message || 'Invalid username or password.', 'error');
            }
        })
        .catch(error => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            showMessage('Login failed. Please try again.', 'error');
        });
    }

    function loadSavedCredentials() {
        const savedUsername = localStorage.getItem('rememberedUsername');
        if (savedUsername && usernameInput) {
            usernameInput.value = savedUsername;
            if (rememberMeCheckbox) {
                rememberMeCheckbox.checked = true;
            }
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showMessage(message, type) {
        // Remove existing message
        const existingMessage = document.querySelector('.login-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create new message
        const messageDiv = document.createElement('div');
        messageDiv.className = `login-message ${type}`;
        messageDiv.textContent = message;

        // Insert message
        const messageContainer = document.getElementById('login-message');
        if (messageContainer) {
            messageContainer.innerHTML = '';
            messageContainer.appendChild(messageDiv);
        }

        // Auto remove after 5 seconds
        setTimeout(function() {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }

});
