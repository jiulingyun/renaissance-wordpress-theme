// Floating White Particles System for Login/Register Pages
class FloatingParticles {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        
        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.animationId = null;
        
        this.config = {
            particleCount: 50,
            particleSize: { min: 1, max: 4 },
            speed: { min: 0.5, max: 2 },
            opacity: { min: 0.3, max: 0.8 },
            spawnRate: 0.3, // particles per frame
            colors: [
                'rgba(255, 255, 255, 0.8)',
                'rgba(255, 255, 255, 0.6)',
                'rgba(255, 255, 255, 0.4)',
                'rgba(240, 240, 255, 0.7)',
                'rgba(250, 250, 255, 0.5)'
            ],
            twinkleSpeed: 0.02,
            driftAmount: 0.5
        };
        
        this.init();
    }
    
    init() {
        this.resize();
        this.bindEvents();
        this.animate();
    }
    
    resize() {
        const rect = this.canvas.parentElement.getBoundingClientRect();
        this.canvas.width = rect.width;
        this.canvas.height = rect.height;
    }
    
    bindEvents() {
        window.addEventListener('resize', () => {
            this.resize();
        });
    }
    
    createParticle() {
        return {
            x: Math.random() * this.canvas.width,
            y: this.canvas.height + 10, // Start below the canvas
            size: Math.random() * (this.config.particleSize.max - this.config.particleSize.min) + this.config.particleSize.min,
            speed: Math.random() * (this.config.speed.max - this.config.speed.min) + this.config.speed.min,
            opacity: Math.random() * (this.config.opacity.max - this.config.opacity.min) + this.config.opacity.min,
            color: this.config.colors[Math.floor(Math.random() * this.config.colors.length)],
            twinkle: Math.random() * Math.PI * 2,
            twinkleSpeed: this.config.twinkleSpeed + Math.random() * 0.01,
            drift: (Math.random() - 0.5) * this.config.driftAmount,
            life: 1.0,
            maxLife: this.canvas.height / this.config.speed.min + 100
        };
    }
    
    updateParticles() {
        // Add new particles
        if (Math.random() < this.config.spawnRate && this.particles.length < this.config.particleCount) {
            this.particles.push(this.createParticle());
        }
        
        // Update existing particles
        for (let i = this.particles.length - 1; i >= 0; i--) {
            const particle = this.particles[i];
            
            // Move particle upward
            particle.y -= particle.speed;
            
            // Add horizontal drift
            particle.x += particle.drift;
            
            // Update twinkle effect
            particle.twinkle += particle.twinkleSpeed;
            
            // Fade out as particle gets older or reaches top
            if (particle.y < -10 || particle.life <= 0) {
                this.particles.splice(i, 1);
                continue;
            }
            
            // Gradual fade out near the top
            if (particle.y < this.canvas.height * 0.2) {
                particle.life -= 0.01;
                particle.opacity *= 0.99;
            }
            
            // Remove particles that drift too far horizontally
            if (particle.x < -50 || particle.x > this.canvas.width + 50) {
                this.particles.splice(i, 1);
            }
        }
    }
    
    drawParticles() {
        this.particles.forEach(particle => {
            this.ctx.save();
            
            // Calculate twinkling opacity
            const twinkleOpacity = particle.opacity * (0.7 + 0.3 * Math.sin(particle.twinkle));
            
            // Set particle style
            this.ctx.globalAlpha = twinkleOpacity * particle.life;
            this.ctx.fillStyle = particle.color;
            
            // Add glow effect
            this.ctx.shadowColor = 'rgba(255, 255, 255, 0.8)';
            this.ctx.shadowBlur = particle.size * 2;
            
            // Draw main particle
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fill();
            
            // Draw inner bright core
            this.ctx.shadowBlur = 0;
            this.ctx.globalAlpha = twinkleOpacity * particle.life * 0.8;
            this.ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size * 0.4, 0, Math.PI * 2);
            this.ctx.fill();
            
            this.ctx.restore();
        });
    }
    
    drawStarField() {
        // Add some static twinkling stars in the background
        this.ctx.save();
        
        const time = Date.now() * 0.001;
        const starCount = 30;
        
        for (let i = 0; i < starCount; i++) {
            const x = (i * 137.5) % this.canvas.width; // Golden ratio distribution
            const y = (i * 73.2) % this.canvas.height;
            const twinkle = Math.sin(time * 2 + i) * 0.5 + 0.5;
            const size = 0.5 + Math.sin(time * 1.5 + i * 0.5) * 0.3;
            
            this.ctx.globalAlpha = twinkle * 0.4;
            this.ctx.fillStyle = 'rgba(255, 255, 255, 0.6)';
            this.ctx.shadowColor = 'rgba(255, 255, 255, 0.8)';
            this.ctx.shadowBlur = 2;
            
            this.ctx.beginPath();
            this.ctx.arc(x, y, size, 0, Math.PI * 2);
            this.ctx.fill();
        }
        
        this.ctx.restore();
    }
    
    animate() {
        // Clear canvas
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw background stars
        this.drawStarField();
        
        // Update and draw particles
        this.updateParticles();
        this.drawParticles();
        
        this.animationId = requestAnimationFrame(() => this.animate());
    }
    
    destroy() {
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }
        
        window.removeEventListener('resize', this.resize);
    }
}

// Initialize floating particles when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('floating-particles');
    if (canvas) {
        const floatingParticles = new FloatingParticles('floating-particles');
        
        // Store reference for cleanup if needed
        window.floatingParticles = floatingParticles;
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.floatingParticles) {
        window.floatingParticles.destroy();
    }
});
