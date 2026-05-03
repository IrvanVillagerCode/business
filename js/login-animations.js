/* ================================
   LOGIN PAGE ANIMATED BACKGROUND
   JavaScript
   ================================ */

document.addEventListener('DOMContentLoaded', function() {
    // ================================
    // CREATE PARTICLE ANIMATION
    // ================================

    const particlesContainer = document.querySelector('.particles-container');

    function createParticles() {
        if (!particlesContainer) return;

        // Clear existing particles
        particlesContainer.innerHTML = '';

        // Create animated particles
        for (let i = 0; i < 10; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');

            // Random size
            const size = Math.random() * 60 + 10;
            particle.style.width = size + 'px';
            particle.style.height = size + 'px';

            // Random position
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';

            // Random opacity
            particle.style.opacity = Math.random() * 0.5 + 0.2;

            // Random animation duration
            const duration = Math.random() * 15 + 10;
            particle.style.animationDuration = duration + 's';

            // Random delay
            const delay = Math.random() * 5;
            particle.style.animationDelay = delay + 's';

            particlesContainer.appendChild(particle);
        }

        // Create blobs
        for (let i = 0; i < 3; i++) {
            const blob = document.createElement('div');
            blob.classList.add('blob');
            
            const size = Math.random() * 200 + 100;
            blob.style.width = size + 'px';
            blob.style.height = size + 'px';

            blob.style.left = Math.random() * 100 + '%';
            blob.style.top = Math.random() * 100 + '%';

            const duration = Math.random() * 10 + 8;
            blob.style.animationDuration = duration + 's';

            particlesContainer.appendChild(blob);
        }
    }

    // Create particles on page load
    createParticles();

    // Recreate particles on window resize
    window.addEventListener('resize', createParticles);

    // ================================
    // LOGIN FORM ANIMATION
    // ================================

    const loginForm = document.querySelector('.login-form');
    const loginBtn = document.querySelector('.login-btn');
    const usernameInput = document.querySelector('input[name="username"]');
    const passwordInput = document.querySelector('input[name="password"]');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (loginBtn) {
                // Add loading state
                loginBtn.classList.add('loading');
                loginBtn.disabled = true;
                loginBtn.textContent = '';
            }
        });
    }

    // ================================
    // INPUT FOCUS ANIMATIONS
    // ================================

    if (usernameInput) {
        usernameInput.addEventListener('focus', function() {
            const label = this.previousElementSibling;
            if (label) {
                label.style.color = '#667eea';
                label.style.transform = 'translateY(-5px)';
            }
        });

        usernameInput.addEventListener('blur', function() {
            const label = this.previousElementSibling;
            if (label) {
                label.style.color = '#333';
                label.style.transform = 'translateY(0)';
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('focus', function() {
            const label = this.previousElementSibling;
            if (label) {
                label.style.color = '#667eea';
                label.style.transform = 'translateY(-5px)';
            }
        });

        passwordInput.addEventListener('blur', function() {
            const label = this.previousElementSibling;
            if (label) {
                label.style.color = '#333';
                label.style.transform = 'translateY(0)';
            }
        });
    }

    // ================================
    // ERROR MESSAGE ANIMATION
    // ================================

    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach((msg, index) => {
        setTimeout(() => {
            msg.style.animation = 'slideInLeft 0.4s ease-out';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                msg.classList.add('hidden');
                setTimeout(() => {
                    msg.remove();
                }, 400);
            }, 5000);
        }, index * 200);
    });

    // ================================
    // SUCCESS MESSAGE ANIMATION
    // ================================

    const successMessages = document.querySelectorAll('.success-message');
    successMessages.forEach((msg, index) => {
        setTimeout(() => {
            msg.style.animation = 'slideInLeft 0.4s ease-out';
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                msg.style.animation = 'slideInLeft 0.4s ease-out reverse';
                setTimeout(() => {
                    msg.remove();
                }, 400);
            }, 3000);
        }, index * 200);
    });

    // ================================
    // INTERACTIVE BACKGROUND
    // ================================

    const loginContainer = document.querySelector('.login-container');

    if (loginContainer) {
        // Mouse movement effect
        document.addEventListener('mousemove', function(e) {
            const particles = document.querySelectorAll('.particle');
            const x = e.clientX / window.innerWidth;
            const y = e.clientY / window.innerHeight;

            particles.forEach(particle => {
                const depth = (Math.random() - 0.5) * 10;
                particle.style.transform = `translate(${x * depth}px, ${y * depth}px)`;
            });
        });

        // Tilt effect on form
        const formContainer = document.querySelector('.login-form-container');
        if (formContainer) {
            document.addEventListener('mousemove', function(e) {
                const rect = formContainer.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const angleX = (y - centerY) / 100;
                const angleY = (centerX - x) / 100;

                formContainer.style.transform = `perspective(1000px) rotateX(${angleX}deg) rotateY(${angleY}deg)`;
            });

            document.addEventListener('mouseleave', function() {
                formContainer.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
            });
        }
    }

    // ================================
    // RESPONSIVE ANIMATION ADJUSTMENT
    // ================================

    function handleResponsive() {
        if (window.innerWidth <= 768) {
            // Disable tilt effect on mobile
            const formContainer = document.querySelector('.login-form-container');
            if (formContainer) {
                formContainer.style.transform = 'none';
            }
        }
    }

    handleResponsive();
    window.addEventListener('resize', handleResponsive);

    // ================================
    // SHOW/HIDE PASSWORD
    // ================================

    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const passwordInput = this.parentElement.querySelector('input[name="password"]');
            if (passwordInput) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.add('active');
                    this.innerHTML = '👁️‍🗨️';
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('active');
                    this.innerHTML = '👁️';
                }
            }
        });
    });

    // ================================
    // FORM VALIDATION ANIMATION
    // ================================

    window.validateLoginForm = function() {
        let isValid = true;
        const username = usernameInput ? usernameInput.value.trim() : '';
        const password = passwordInput ? passwordInput.value.trim() : '';

        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(msg => msg.remove());

        if (username === '') {
            showValidationError(usernameInput, 'Username tidak boleh kosong');
            isValid = false;
        }

        if (password === '') {
            showValidationError(passwordInput, 'Password tidak boleh kosong');
            isValid = false;
        }

        return isValid;
    };

    function showValidationError(input, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.style.animation = 'slideInLeft 0.4s ease-out';
        input.parentElement.insertBefore(errorDiv, input);
    }

    // ================================
    // ACCESSIBILITY
    // ================================

    // Detect if user prefers reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    
    if (prefersReducedMotion) {
        // Disable animations for accessibility
        const styleSheet = document.createElement('style');
        styleSheet.textContent = `
            .particle, .blob, .login-form-container, 
            .login-form-container h1, .form-group, 
            .login-btn, .login-links {
                animation: none !important;
                transition: none !important;
            }
        `;
        document.head.appendChild(styleSheet);
    }
});

// ================================
// UTILITY FUNCTIONS
// ================================

window.showLoginNotification = function(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.animation = 'slideInRight 0.4s ease-out';
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideInRight 0.4s ease-out reverse';
        setTimeout(() => {
            notification.remove();
        }, 400);
    }, 5000);
};
