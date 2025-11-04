// 返回顶部按钮功能
document.addEventListener('DOMContentLoaded', function() {
    // 创建返回顶部按钮
    const backToTopBtn = document.createElement('button');
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
    backToTopBtn.setAttribute('aria-label', '返回顶部');
    backToTopBtn.setAttribute('title', '返回顶部');
    
    // 添加到页面
    document.body.appendChild(backToTopBtn);
    
    // 滚动监听
    let isScrolling = false;
    
    function handleScroll() {
        if (!isScrolling) {
            window.requestAnimationFrame(() => {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                if (scrollTop > 300) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
                
                isScrolling = false;
            });
            isScrolling = true;
        }
    }
    
    // 监听滚动事件
    window.addEventListener('scroll', handleScroll, { passive: true });
    
    // 点击返回顶部
    backToTopBtn.addEventListener('click', function() {
        // 平滑滚动到顶部
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
        
        // 添加点击动画效果
        this.style.transform = 'translateY(-1px) scale(1.05)';
        setTimeout(() => {
            this.style.transform = '';
        }, 150);
    });
    
    // 键盘支持
    backToTopBtn.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });
    
    // 触摸设备优化
    let touchStartY = 0;
    
    backToTopBtn.addEventListener('touchstart', function(e) {
        touchStartY = e.touches[0].clientY;
    }, { passive: true });
    
    backToTopBtn.addEventListener('touchend', function(e) {
        const touchEndY = e.changedTouches[0].clientY;
        const touchDiff = Math.abs(touchStartY - touchEndY);
        
        // 如果触摸移动距离小于10px，认为是点击
        if (touchDiff < 10) {
            this.click();
        }
    }, { passive: true });
});
