/* ================================
   SCROLL-TRIGGERED ANIMATIONS
   ================================ */

document.addEventListener('DOMContentLoaded', function() {
    // Intersection Observer untuk animasi saat scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Tambah class untuk trigger animasi
                entry.target.classList.add('animate-on-scroll');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe semua elemen dengan class yang bisa di-animasi
    const animateElements = document.querySelectorAll(
        '.product-card, .stat-card, .content-section, ' +
        '.promo-card, table tbody tr, .form-group, ' +
        '.feature-box, .testimonial, .team-member'
    );

    animateElements.forEach(el => {
        observer.observe(el);
    });

    // ================================
    // HOVER ANIMATIONS
    // ================================

    // Product Card Hover Effect
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.2)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
        });
    });

    // Button Hover Effect
    const buttons = document.querySelectorAll('.btn-view, .btn-cart, .btn-primary, .btn-secondary');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });

    // Navbar Link Hover
    const navLinks = document.querySelectorAll('nav a, .navbar-item');
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.style.color = '#0071CE';
            this.style.borderBottom = '2px solid #0071CE';
        });

        link.addEventListener('mouseleave', function() {
            this.style.borderBottom = 'none';
        });
    });

    // ================================
    // SMOOTH SCROLL ANIMATIONS
    // ================================

    // Smooth scroll untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // ================================
    // DROPDOWN & MODAL ANIMATIONS
    // ================================

    // Dropdown animation
    const dropdownButtons = document.querySelectorAll('[data-toggle="dropdown"]');
    dropdownButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            if (dropdown && dropdown.classList.contains('dropdown-menu')) {
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                dropdown.style.animation = 'slideDown 0.3s ease-out';
            }
        });
    });

    // Modal animation
    const modalButtons = document.querySelectorAll('[data-toggle="modal"]');
    modalButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-target');
            const modal = document.querySelector(modalId);
            if (modal) {
                modal.style.display = 'block';
                modal.style.animation = 'fadeIn 0.3s ease-out';
            }
        });
    });

    // Close modal
    const closeButtons = document.querySelectorAll('.close-modal, .close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    modal.style.display = 'none';
                }, 300);
            }
        });
    });

    // ================================
    // FORM ANIMATIONS
    // ================================

    // Form input focus animation
    const formInputs = document.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.boxShadow = '0 0 10px rgba(0, 113, 206, 0.5)';
            this.style.borderColor = '#0071CE';
        });

        input.addEventListener('blur', function() {
            this.style.boxShadow = 'none';
            this.style.borderColor = '#ddd';
        });
    });

    // ================================
    // SCROLL PROGRESS BAR
    // ================================

    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        window.addEventListener('scroll', function() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (scrollTop / docHeight) * 100;
            progressBar.style.width = scrollPercent + '%';
        });
    }

    // ================================
    // NUMBER COUNTER ANIMATION
    // ================================

    const counterElements = document.querySelectorAll('[data-count]');
    counterElements.forEach(element => {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000; // 2 detik
        const increment = target / (duration / 16); // 60fps

        let current = 0;
        const counter = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(counter);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    });

    // ================================
    // LOADING STATE ANIMATION
    // ================================

    // Show loading spinner
    window.showLoadingSpinner = function(show = true) {
        let spinner = document.querySelector('.loading-spinner');
        if (!spinner) {
            spinner = document.createElement('div');
            spinner.className = 'loading-spinner';
            spinner.innerHTML = '<div class="spinner"></div>';
            document.body.appendChild(spinner);
        }
        spinner.style.display = show ? 'flex' : 'none';
    };

    // ================================
    // PAGE LOAD ANIMATIONS
    // ================================

    // Fade in content saat halaman load
    window.addEventListener('load', function() {
        const mainContent = document.querySelector('.main-content') || 
                          document.querySelector('main') || 
                          document.body;
        mainContent.style.animation = 'fadeIn 0.8s ease-out';
    });

    // ================================
    // PARALLAX EFFECT
    // ================================

    window.addEventListener('scroll', function() {
        const parallaxElements = document.querySelectorAll('[data-parallax]');
        parallaxElements.forEach(element => {
            const speed = element.getAttribute('data-parallax') || 0.5;
            element.style.transform = `translateY(${window.scrollY * speed}px)`;
        });
    });

    // ================================
    // TOOLTIP ANIMATIONS
    // ================================

    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            tooltip.style.animation = 'slideInTop 0.3s ease-out';
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.position = 'fixed';
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';

            this.tooltip = tooltip;
        });

        element.addEventListener('mouseleave', function() {
            if (this.tooltip) {
                this.tooltip.style.animation = 'fadeOut 0.3s ease-out';
                setTimeout(() => {
                    this.tooltip.remove();
                    this.tooltip = null;
                }, 300);
            }
        });
    });

    // ================================
    // NOTIFICATION ANIMATIONS
    // ================================

    window.showNotification = function(message, type = 'success', duration = 3000) {
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
        }, duration);
    };

    // ================================
    // LAZY LOAD IMAGES
    // ================================

    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                img.style.animation = 'fadeIn 0.5s ease-out';
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));
});

// ================================
// FADEOUT ANIMATION
// ================================

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}

/* CSS untuk Notification */
const notificationStyles = `
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        max-width: 300px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .notification-success {
        background-color: #4CAF50;
    }

    .notification-error {
        background-color: #f44336;
    }

    .notification-warning {
        background-color: #ff9800;
    }

    .notification-info {
        background-color: #2196F3;
    }

    .loading-spinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid rgba(255, 255, 255, 0.3);
        border-top: 5px solid white;
        border-radius: 50%;
        animation: rotate 1s linear infinite;
    }

    .tooltip {
        background-color: #333;
        color: white;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 1000;
        white-space: nowrap;
    }

    .animate-on-scroll {
        opacity: 1 !important;
        animation: fadeIn 0.8s ease-out !important;
    }

    @media (prefers-reduced-motion: reduce) {
        * {
            animation: none !important;
            transition: none !important;
        }
    }
`;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
