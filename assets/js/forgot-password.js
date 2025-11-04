document.addEventListener('DOMContentLoaded', function() {
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const successMessage = document.getElementById('successMessage');
    const resendLink = document.getElementById('resendLink');
    
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', handleForgotPasswordSubmit);
    }
    
    if (resendLink) {
        resendLink.addEventListener('click', handleResendLink);
    }
    
    function handleForgotPasswordSubmit(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value.trim();
        const submitButton = forgotPasswordForm.querySelector('.btn-forgot-password');
        
        // Basic email validation
        if (!isValidEmail(email)) {
            showError('Please enter a valid email address.');
            return;
        }
        
        // Show loading state
        showLoadingState(submitButton);
        
        // AJAX 提交到 WordPress
        const formData = new FormData();
        formData.append('action', 'rena_forgot_password');
        formData.append('email', email);
        formData.append('security', document.getElementById('forgot-password-nonce').value);

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            resetButtonState(submitButton);

            if (result.success) {
                // Hide the form and show success message
                forgotPasswordForm.style.display = 'none';
                document.querySelector('.forgot-password-header').style.display = 'none';
                successMessage.style.display = 'block';
                
                // Store email for potential resend
                sessionStorage.setItem('resetEmail', email);
            } else {
                showError(result.data.message || 'Failed to send reset link.');
            }
        })
        .catch(error => {
            resetButtonState(submitButton);
            showError('Failed to send reset link. Please try again.');
        });
    }
    
    function handleResendLink(e) {
        e.preventDefault();
        
        const email = sessionStorage.getItem('resetEmail');
        if (!email) {
            showError('Email not found. Please try again.');
            return;
        }
        
        // Show loading state for resend
        const originalText = resendLink.textContent;
        resendLink.textContent = 'Sending...';
        resendLink.style.pointerEvents = 'none';
        
        // 重新发送
        const formData = new FormData();
        formData.append('action', 'rena_forgot_password');
        formData.append('email', email);
        formData.append('security', document.getElementById('forgot-password-nonce').value);

        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            resendLink.textContent = originalText;
            resendLink.style.pointerEvents = 'auto';
            
            if (result.success) {
                showSuccess('Reset link sent again!');
            } else {
                showError('Failed to resend link.');
            }
        })
        .catch(error => {
            resendLink.textContent = originalText;
            resendLink.style.pointerEvents = 'auto';
            showError('Failed to resend link.');
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showLoadingState(button) {
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Sending...';
        button.style.pointerEvents = 'none';
    }
    
    function resetButtonState(button) {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-translate') ? 
            getTranslation('forgot-password-button') : 'Send Reset Link';
        button.style.pointerEvents = 'auto';
    }
    
    function showError(message) {
        // Remove existing alerts
        removeExistingAlerts();
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-error';
        alert.innerHTML = `
            <i class="bi bi-exclamation-triangle"></i>
            <span>${message}</span>
        `;
        
        const container = document.querySelector('.forgot-password-container');
        container.insertBefore(alert, container.firstChild);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
    
    function showSuccess(message) {
        // Remove existing alerts
        removeExistingAlerts();
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-success';
        alert.innerHTML = `
            <i class="bi bi-check-circle"></i>
            <span>${message}</span>
        `;
        
        const container = document.querySelector('.forgot-password-container');
        container.insertBefore(alert, container.firstChild);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 3000);
    }
    
    function removeExistingAlerts() {
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
    }
    
    function getTranslation(key) {
        // This function should integrate with your existing translation system
        // For now, return English as fallback
        const translations = {
            'forgot-password-button': 'Send Reset Link'
        };
        return translations[key] || key;
    }
});

// Add CSS for alerts and loading animation
const alertStyles = `
<style>
.alert {
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
    animation: slideIn 0.3s ease-out;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

.alert-success {
    background: rgba(34, 197, 94, 0.1);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #22c55e;
}

.alert i {
    font-size: 1.1rem;
    flex-shrink: 0;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.spin {
    animation: spin 1s linear infinite;
}
</style>
`;

// Inject styles into the document head
document.head.insertAdjacentHTML('beforeend', alertStyles);
