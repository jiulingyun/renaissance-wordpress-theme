// Contact form functionality
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value.trim(),
                email: document.getElementById('email').value.trim(),
                message: document.getElementById('message').value.trim()
            };
            
            // Validation
            if (!validateForm(formData)) {
                return;
            }
            
            // Submit form
            submitContactForm(formData);
        });
    }
    
    function validateForm(data) {
        // Clear previous messages
        clearMessages();
        
        // Required field validation
        if (!data.name) {
            showMessage('Please enter your name', 'error');
            document.getElementById('name').focus();
            return false;
        }
        
        if (!data.email) {
            showMessage('Please enter your email address', 'error');
            document.getElementById('email').focus();
            return false;
        }
        
        if (!isValidEmail(data.email)) {
            showMessage('Please enter a valid email address', 'error');
            document.getElementById('email').focus();
            return false;
        }
        
        
        if (!data.message) {
            showMessage('Please enter your message', 'error');
            document.getElementById('message').focus();
            return false;
        }
        
        if (data.message.length < 10) {
            showMessage('Message must be at least 10 characters long', 'error');
            document.getElementById('message').focus();
            return false;
        }
        
        
        return true;
    }
    
    function submitContactForm(data) {
        const submitBtn = document.querySelector('.btn-contact');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;
        
        // 获取页面 ID
        const pageId = window.renaPageId;
        
        if (!pageId) {
            showMessage('Error: Unable to submit form. Please contact us via email.', 'error');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            return;
        }
        
        // 准备提交数据
        const formData = new FormData();
        formData.append('action', 'rena_contact_form');
        formData.append('nonce', renaAjax.nonce);
        formData.append('name', data.name);
        formData.append('email', data.email);
        formData.append('message', data.message);
        formData.append('post_id', pageId);
        
        // 提交到自定义 AJAX 处理器
        fetch(renaAjax.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (result.success) {
                // 成功
                showMessage(result.data.message, 'success');
                
                // Reset form
                contactForm.reset();
                
                // Scroll to top of form to show success message
                const contactFormSection = document.querySelector('.contact-form-section') || document.querySelector('.contact-section');
                if (contactFormSection) {
                    contactFormSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            } else {
                // 失败
                showMessage(result.data.message || 'Sorry, there was an error sending your message. Please try again or contact us via email.', 'error');
            }
        })
        .catch(error => {
            // 网络错误
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            showMessage('Sorry, there was an error sending your message. Please try again or contact us via email.', 'error');
        });
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function showMessage(message, type) {
        clearMessages();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `contact-message ${type}`;
        messageDiv.innerHTML = `
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        // Insert message at the top of the form
        const contactForm = document.querySelector('.contact-form');
        contactForm.insertBefore(messageDiv, contactForm.firstChild);
        
        // Auto remove after 8 seconds
        setTimeout(function() {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 8000);
    }
    
    function clearMessages() {
        const existingMessages = document.querySelectorAll('.contact-message');
        existingMessages.forEach(message => message.remove());
    }
    
    
    // Character counter for message field
    const messageField = document.getElementById('message');
    if (messageField) {
        const maxLength = 1000;
        
        // Create character counter
        const counterDiv = document.createElement('div');
        counterDiv.className = 'character-counter';
        counterDiv.innerHTML = `<small>0 / ${maxLength} characters</small>`;
        messageField.parentNode.appendChild(counterDiv);
        
        messageField.addEventListener('input', function(e) {
            const currentLength = e.target.value.length;
            const counter = counterDiv.querySelector('small');
            
            counter.textContent = `${currentLength} / ${maxLength} characters`;
            
            if (currentLength > maxLength * 0.9) {
                counter.style.color = '#f59e0b';
            } else if (currentLength > maxLength * 0.8) {
                counter.style.color = '#a855f7';
            } else {
                counter.style.color = 'rgba(255, 255, 255, 0.6)';
            }
            
            // Limit input
            if (currentLength > maxLength) {
                e.target.value = e.target.value.substring(0, maxLength);
                counter.textContent = `${maxLength} / ${maxLength} characters`;
                counter.style.color = '#ef4444';
            }
        });
    }
});
