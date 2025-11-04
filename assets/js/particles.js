// 粒子飘散效果
document.addEventListener('DOMContentLoaded', function() {
    const particlesContainer = document.querySelector('.particles-container');
    
    if (!particlesContainer) return;
    
    function createParticle() {
        const particle = document.createElement('div');
        particle.className = 'particle';
        
        const containerRect = particlesContainer.getBoundingClientRect();
        const containerWidth = containerRect.width;
        const containerHeight = containerRect.height;
        
        // 随机轨道半径（从中心向外，使用容器宽度的25%-50%）
        const minRadius = Math.min(containerWidth, containerHeight) * 0.25;
        const maxRadius = Math.min(containerWidth, containerHeight) * 0.5;
        const orbitRadius = minRadius + Math.random() * (maxRadius - minRadius);
        
        // 随机起始角度（0-360度）
        const startAngle = Math.random() * 360;
        
        // 反方向旋转半圈（-180度）
        const rotationDegrees = -180;
        
        particle.style.setProperty('--orbit-radius', orbitRadius + 'px');
        particle.style.setProperty('--start-angle', startAngle + 'deg');
        particle.style.setProperty('--rotation-degrees', rotationDegrees + 'deg');
        
        // 持续时间减少（3-6秒）- 聚拢更快
        const duration = 3 + Math.random() * 3;
        particle.style.animationDuration = duration + 's';
        
        // 随机延迟
        const delay = Math.random() * 1;
        particle.style.animationDelay = delay + 's';
        
        // 随机大小（1-3px，更小）
        const size = 1 + Math.random() * 2;
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        
        particlesContainer.appendChild(particle);
        
        // 动画结束后移除粒子
        particle.addEventListener('animationend', () => {
            particle.remove();
        });
    }
    
    // 初始生成一批粒子
    for (let i = 0; i < 70; i++) {
        setTimeout(() => createParticle(), i * 60);
    }
    
    // 持续生成新粒子（更频繁）
    setInterval(() => {
        createParticle();
    }, 180);
});

