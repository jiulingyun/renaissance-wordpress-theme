// Register form functionality
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const firstNameInput = document.getElementById('firstName');
    const lastNameInput = document.getElementById('lastName');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const idNumberInput = document.getElementById('idNumber');
    const passwordInput = document.getElementById('regPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const termsCheckbox = document.getElementById('termsCheck');


    // Form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            const email = emailInput.value.trim();
            const phone = (document.getElementById('countryCode').value + phoneInput.value).trim();
            const idNumber = idNumberInput.value.trim();
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Validation
            if (!firstName || !lastName) {
                showMessage('Please enter your full name', 'error');
                return;
            }

            if (!email || !isValidEmail(email)) {
                showMessage('Please enter a valid email address', 'error');
                emailInput.focus();
                return;
            }

            if (!phone) {
                showMessage('Please enter your phone number', 'error');
                phoneInput.focus();
                return;
            }

            if (!idNumber) {
                showMessage('Please enter your ID number', 'error');
                idNumberInput.focus();
                return;
            }

            if (!password || password.length < 8) {
                showMessage('Password must be at least 8 characters', 'error');
                passwordInput.focus();
                return;
            }

            if (password !== confirmPassword) {
                showMessage('Passwords do not match', 'error');
                confirmPasswordInput.focus();
                return;
            }

            if (!termsCheckbox.checked) {
                showMessage('Please agree to the Terms of Service', 'error');
                return;
            }

            // Submit registration
            submitRegistration({
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone: phone,
                id_number: idNumber,
                password: password
            });
        });
    }

    function submitRegistration(data) {
        const submitBtn = document.querySelector('.btn-register');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Creating Account...';
        submitBtn.disabled = true;

        // AJAX 提交到 WordPress
        const formData = new FormData();
        formData.append('action', 'rena_register');
        formData.append('first_name', data.first_name);
        formData.append('last_name', data.last_name);
        formData.append('email', data.email);
        formData.append('phone', data.phone);
        formData.append('id_number', data.id_number);
        formData.append('password', data.password);
        formData.append('security', document.getElementById('register-nonce').value);

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;

            if (result.success) {
                showMessage(result.data.message || 'Registration successful! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = result.data.redirect || '/downloads/';
                }, 1500);
            } else {
                showMessage(result.data.message || 'Registration failed. Please try again.', 'error');
            }
        })
        .catch(error => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            showMessage('Registration failed. Please try again.', 'error');
        });
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function showMessage(message, type) {
        // Remove existing message
        const existingMessage = document.querySelector('.register-message-toast');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create toast message (fixed position at top)
        const messageDiv = document.createElement('div');
        messageDiv.className = `register-message-toast ${type}`;
        messageDiv.innerHTML = `
            <div class="toast-content">
                <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Insert to body
        document.body.appendChild(messageDiv);

        // Show with animation
        setTimeout(() => {
            messageDiv.classList.add('show');
        }, 10);

        // Auto remove after 5 seconds
        setTimeout(function() {
            messageDiv.classList.remove('show');
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 300);
        }, 5000);
        
        // Scroll to top to show message
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Real-time password confirmation validation
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword && password !== confirmPassword) {
            confirmPasswordInput.setCustomValidity('Passwords do not match');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    });
});
