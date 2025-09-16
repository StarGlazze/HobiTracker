document.addEventListener('DOMContentLoaded', function() {

    // Scroll to top on page load to fix initial scroll position issue
    window.scrollTo(0, 0);

    // ========== COUNTER ANIMATIONS ==========
    function animateCounters() {
        const counters = document.querySelectorAll('.animated-counter');

        const startCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-purecounter-end'));
            const duration = parseInt(counter.getAttribute('data-purecounter-duration')) * 1000;
            const startTime = Date.now();
            const startValue = parseInt(counter.getAttribute('data-purecounter-start'));

            const updateCounter = () => {
                const now = Date.now();
                const progress = Math.min((now - startTime) / duration, 1);
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.round(startValue + (target - startValue) * easeOutQuart);

                counter.textContent = currentValue;

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                }
            };

            requestAnimationFrame(updateCounter);
        };

        // Intersection Observer for counters
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                    entry.target.classList.add('animated');
                    startCounter(entry.target);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => {
            counterObserver.observe(counter);
        });
    }

    // ========== TYPING EFFECT ==========
    function initTypingEffect() {
        const heroTitle = document.getElementById('hero-title');
        if (!heroTitle) return;

        const originalText = heroTitle.innerHTML;
        heroTitle.innerHTML = '';
        let charIndex = 0;

        const typeCharacter = () => {
            if (charIndex < originalText.length) {
                heroTitle.innerHTML += originalText.charAt(charIndex);
                charIndex++;
                setTimeout(typeCharacter, 50);
            }
        };

        setTimeout(typeCharacter, 500);
    }

    // ========== INTERACTIVE CARDS ==========
    function initInteractiveCards() {
        const cards = document.querySelectorAll('.interactive-card');

        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.transition = 'all 0.3s ease';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
            });

            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '';
            });

            // Add click ripple effect
            card.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                ripple.classList.add('ripple-effect');
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255,255,255,0.6);
                    pointer-events: none;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    width: 100px;
                    height: 100px;
                    left: ${e.clientX - this.offsetLeft - 50}px;
                    top: ${e.clientY - this.offsetTop - 50}px;
                `;

                this.appendChild(ripple);
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
    }

    // ========== INTERACTIVE LIST ==========
    function initInteractiveList() {
        const listItems = document.querySelectorAll('.interactive-list li');

        listItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-30px)';
            item.style.transition = `all 0.5s ease ${index * 0.1}s`;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateX(0)';
                    }
                });
            });

            observer.observe(item);

            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(10px)';
                this.style.backgroundColor = 'rgba(0,0,0,0.02)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.style.backgroundColor = '';
            });
        });
    }

    // ========== INTERACTIVE BUTTONS ==========
    function initInteractiveButtons() {
        const buttons = document.querySelectorAll('.interactive-btn, .btn-get-started, .interactive-submit');

        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.transition = 'all 0.3s ease';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });

            button.addEventListener('mousedown', function() {
                this.style.transform = 'translateY(0) scale(0.98)';
            });

            button.addEventListener('mouseup', function() {
                this.style.transform = 'translateY(-2px) scale(1)';
            });
        });
    }

    // ========== INTERACTIVE FORM ==========
    function initInteractiveForm() {
        const form = document.querySelector('.interactive-form');
        const inputs = document.querySelectorAll('.interactive-input');

        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });

            // Add typing sound effect simulation
            input.addEventListener('input', function() {
                this.style.borderColor = '#007bff';
                setTimeout(() => {
                    this.style.borderColor = '';
                }, 200);
            });
        });

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = this.querySelector('.interactive-submit');
                const loading = this.querySelector('.loading');
                const successMsg = this.querySelector('.sent-message');

                // Animate submit
                submitBtn.style.width = submitBtn.offsetWidth + 'px';
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Sent!';
                    submitBtn.style.backgroundColor = '#28a745';

                    setTimeout(() => {
                        submitBtn.innerHTML = 'Send Message';
                        submitBtn.style.backgroundColor = '';
                        submitBtn.style.width = '';
                        submitBtn.disabled = false;
                        this.reset();
                    }, 2000);
                }, 1500);
            });
        }
    }

    // ========== INTERACTIVE TESTIMONIALS ==========
    function initInteractiveTestimonials() {
        const testimonials = document.querySelectorAll('.interactive-testimonial');

        testimonials.forEach(testimonial => {
            testimonial.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
                this.style.transition = 'transform 0.3s ease';
            });

            testimonial.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        const stars = document.querySelectorAll('.interactive-stars i');
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                // Animate stars on hover
                for (let i = 0; i <= index; i++) {
                    this.parentElement.children[i].style.color = '#ffc107';
                    this.parentElement.children[i].style.transform = 'scale(1.2)';
                    this.parentElement.children[i].style.transition = 'all 0.1s ease';
                }
            });

            star.parentElement.addEventListener('mouseleave', function() {
                Array.from(this.children).forEach(s => {
                    s.style.transform = 'scale(1)';
                    s.style.color = '#ffc107';
                });
            });
        });
    }

    // ========== INTERACTIVE INFO ITEMS ==========
    function initInteractiveInfo() {
        const infoItems = document.querySelectorAll('.interactive-info');

        infoItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(10px)';
                this.style.transition = 'all 0.3s ease';
                this.querySelector('i').style.transform = 'scale(1.2)';
            });

            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                this.querySelector('i').style.transform = 'scale(1)';
            });
        });
    }

    // ========== PARALLAX SCROLL EFFECT ==========
    function initParallaxEffect() {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const heroSection = document.querySelector('#hero');

            if (heroSection) {
                const rate = scrolled * -0.5;
                heroSection.style.transform = `translate3d(0, ${rate}px, 0)`;
            }
        });
    }

    // ========== SMOOTH SCROLL ==========
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);

                if (targetSection) {
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // ========== ADD CSS ANIMATIONS ==========
    function addCSSAnimations() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .interactive-image {
                transition: all 0.3s ease;
            }

            .interactive-image:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }

            .interactive-map {
                transition: all 0.3s ease;
                border-radius: 10px;
                overflow: hidden;
            }

            .interactive-map:hover {
                transform: scale(1.02);
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            }
        `;
        document.head.appendChild(style);
    }

    // ========== INITIALIZE ALL INTERACTIONS ==========
    function initializeAllInteractions() {
        animateCounters();
        initTypingEffect();
        initInteractiveCards();
        initInteractiveList();
        initInteractiveButtons();
        initInteractiveForm();
        initInteractiveTestimonials();
        initInteractiveInfo();
        initParallaxEffect();
        initSmoothScroll();
        addCSSAnimations();
    }

    // Start all interactions
    initializeAllInteractions();

    // Console message
    console.log('🚀 HobiTracker Interactive Features Loaded!');
});
