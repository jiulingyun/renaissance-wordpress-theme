// Newsletter subscription functionality
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.querySelector('.newsletter-form');
    const emailInput = document.querySelector('.newsletter-input');
    const subscribeBtn = document.querySelector('.btn-subscribe');

    if (!newsletterForm || !emailInput || !subscribeBtn) {
        console.warn('Newsletter form elements not found');
        return;
    }

    // Create modal HTML structure
    function createModal() {
        const modalHTML = `
            <div class="newsletter-modal-overlay" id="newsletterModal">
                <div class="newsletter-modal">
                    <div class="newsletter-modal-content">
                        <div class="newsletter-modal-icon">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="12" cy="12" r="10" fill="#7c3aed"/>
                                <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3 class="newsletter-modal-title">Subscription Successful!</h3>
                        <p class="newsletter-modal-message">
                            Thank you for subscribing to our newsletter. You'll receive the latest insights on AI-powered financial technologies and market analysis.
                        </p>
                        <button class="newsletter-modal-close" id="closeModal">
                            Got it
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Insert modal into body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Add event listener to close button
        const closeBtn = document.getElementById('closeModal');
        const modal = document.getElementById('newsletterModal');
        
        closeBtn.addEventListener('click', () => {
            hideModal();
        });
        
        // Close modal when clicking overlay
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                hideModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('show')) {
                hideModal();
            }
        });
    }

    // Show modal
    function showModal() {
        const modal = document.getElementById('newsletterModal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    // Hide modal
    function hideModal() {
        const modal = document.getElementById('newsletterModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
            
            // Remove modal from DOM after animation
            setTimeout(() => {
                modal.remove();
            }, 300);
        }
    }

    // Validate email format
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Handle form submission
    function handleSubmit(e) {
        e.preventDefault();
        
        const email = emailInput.value.trim();
        
        // Validate email
        if (!email) {
            showError('Please enter your email address');
            return;
        }
        
        if (!isValidEmail(email)) {
            showError('Please enter a valid email address');
            return;
        }
        
        // 检查 AJAX 配置是否存在
        if (!window.renaNewsletter) {
            showError('Error: Configuration not found. Please refresh the page.');
            return;
        }
        
        // Show loading state
        const originalText = subscribeBtn.textContent;
        subscribeBtn.textContent = 'Subscribing...';
        subscribeBtn.disabled = true;
        
        // 准备提交数据
        const formData = new FormData();
        formData.append('action', 'rena_newsletter_subscription');
        formData.append('nonce', window.renaNewsletter.nonce);
        formData.append('email', email);
        formData.append('page_id', window.renaNewsletter.pageId);
        
        // 提交到自定义 AJAX 处理器
        fetch(window.renaNewsletter.ajaxurl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            // Reset button state
            subscribeBtn.textContent = originalText;
            subscribeBtn.disabled = false;
            
            if (result.success) {
                // 成功 - 清空输入并显示模态框
                emailInput.value = '';
                
                // Create and show modal
                createModal();
                showModal();
            } else {
                // 失败 - 显示错误消息
                showError(result.data.message || 'Sorry, there was an error. Please try again.');
            }
        })
        .catch(error => {
            // 网络错误
            subscribeBtn.textContent = originalText;
            subscribeBtn.disabled = false;
            
            showError('Sorry, there was an error. Please try again.');
        });
    }

    // Show error message
    function showError(message) {
        // Remove existing error
        const existingError = document.querySelector('.newsletter-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create error element
        const errorElement = document.createElement('div');
        errorElement.className = 'newsletter-error';
        errorElement.textContent = message;
        
        // Insert error after form
        newsletterForm.parentNode.insertBefore(errorElement, newsletterForm.nextSibling);
        
        // Remove error after 3 seconds
        setTimeout(() => {
            errorElement.remove();
        }, 3000);
        
        // Focus on input
        emailInput.focus();
    }

    // Add form event listener
    newsletterForm.addEventListener('submit', handleSubmit);
    
    // Add input event listener to clear errors
    emailInput.addEventListener('input', () => {
        const existingError = document.querySelector('.newsletter-error');
        if (existingError) {
            existingError.remove();
        }
    });
});
