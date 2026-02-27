/**
 * Translatio Global Theme - Main JavaScript
 * Orchestration and initialization module
 */

(function() {
    'use strict';

    /**
     * Main Theme App
     */
    class TranslatioTheme {
        constructor() {
            this.version = '1.0.0';
            this.modules = {};
            this.init();
        }

        init() {
            this.setupLazyLoading();
            this.setupAnimations();
            this.setupAccessibility();
            this.setupWhatsAppButton();
            this.setupAnalytics();
            this.setupPerformanceOptimizations();
            
            console.log(`Translatio Global Theme v${this.version} initialized`);
        }

        /**
         * Lazy Loading for Images
         */
        setupLazyLoading() {
            // Use native lazy loading if supported
            if ('loading' in HTMLImageElement.prototype) {
                const images = document.querySelectorAll('img[data-src]');
                images.forEach(img => {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                });
            } else {
                // Fallback for older browsers
                const lazyImages = document.querySelectorAll('img[data-src]');
                
                if (lazyImages.length > 0) {
                    const imageObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                const image = entry.target;
                                image.src = image.dataset.src;
                                image.removeAttribute('data-src');
                                image.classList.add('loaded');
                                observer.unobserve(image);
                            }
                        });
                    }, {
                        rootMargin: '50px 0px',
                        threshold: 0.01
                    });

                    lazyImages.forEach(img => imageObserver.observe(img));
                }
            }
        }

        /**
         * Scroll Animations
         */
        setupAnimations() {
            const animatedElements = document.querySelectorAll('[data-animate]');
            
            if (animatedElements.length === 0) return;

            const animationObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const animation = element.dataset.animate;
                        const delay = element.dataset.animateDelay || 0;
                        
                        setTimeout(() => {
                            element.classList.add('animated', animation);
                            element.dispatchEvent(new CustomEvent('animated'));
                        }, delay);
                        
                        animationObserver.unobserve(element);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            animatedElements.forEach(el => animationObserver.observe(el));

            // Add animation styles if not present
            if (!document.querySelector('#translatio-animations')) {
                const style = document.createElement('style');
                style.id = 'translatio-animations';
                style.textContent = `
                    [data-animate] { opacity: 0; }
                    [data-animate].animated { opacity: 1; }
                    .fade-in-up { animation: fadeInUp 0.6s ease forwards; }
                    .fade-in-left { animation: fadeInLeft 0.6s ease forwards; }
                    .fade-in-right { animation: fadeInRight 0.6s ease forwards; }
                    .zoom-in { animation: zoomIn 0.6s ease forwards; }
                    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
                    @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
                    @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
                    @keyframes zoomIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
                `;
                document.head.appendChild(style);
            }
        }

        /**
         * Accessibility Enhancements
         */
        setupAccessibility() {
            // Skip to content link
            const skipLink = document.querySelector('.skip-link');
            if (skipLink) {
                skipLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    const target = document.querySelector(skipLink.getAttribute('href'));
                    if (target) {
                        target.setAttribute('tabindex', '-1');
                        target.focus();
                    }
                });
            }

            // Detect keyboard vs mouse navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Tab') {
                    document.body.classList.add('keyboard-nav');
                }
            });

            document.addEventListener('mousedown', () => {
                document.body.classList.remove('keyboard-nav');
            });

            // Announce dynamic content to screen readers
            this.liveRegion = document.createElement('div');
            this.liveRegion.setAttribute('aria-live', 'polite');
            this.liveRegion.setAttribute('aria-atomic', 'true');
            this.liveRegion.className = 'sr-only';
            document.body.appendChild(this.liveRegion);
        }

        /**
         * Floating WhatsApp Button
         */
        setupWhatsAppButton() {
            const whatsappBtn = document.querySelector('.whatsapp-float');
            
            if (!whatsappBtn) return;

            // Show after scroll
            let isVisible = false;
            window.addEventListener('scroll', () => {
                const shouldShow = window.pageYOffset > 300;
                if (shouldShow !== isVisible) {
                    isVisible = shouldShow;
                    whatsappBtn.classList.toggle('visible', isVisible);
                }
            });

            // Track click
            whatsappBtn.addEventListener('click', () => {
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'click', {
                        'event_category': 'WhatsApp',
                        'event_label': 'Floating Button'
                    });
                }
            });
        }

        /**
         * Analytics Integration
         */
        setupAnalytics() {
            // Track scroll depth
            let maxScroll = 0;
            const scrollMilestones = [25, 50, 75, 100];
            const trackedMilestones = new Set();

            window.addEventListener('scroll', () => {
                const scrollTop = window.pageYOffset;
                const docHeight = document.documentElement.scrollHeight - window.innerHeight;
                const scrollPercent = Math.round((scrollTop / docHeight) * 100);

                if (scrollPercent > maxScroll) {
                    maxScroll = scrollPercent;
                }

                scrollMilestones.forEach(milestone => {
                    if (scrollPercent >= milestone && !trackedMilestones.has(milestone)) {
                        trackedMilestones.add(milestone);
                        
                        if (typeof gtag !== 'undefined') {
                            gtag('event', 'scroll_depth', {
                                'event_category': 'Scroll',
                                'event_label': `${milestone}%`,
                                'value': milestone
                            });
                        }
                    }
                });
            });

            // Track time on page
            let startTime = Date.now();
            
            window.addEventListener('beforeunload', () => {
                const timeSpent = Math.round((Date.now() - startTime) / 1000);
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'timing_complete', {
                        'name': 'time_on_page',
                        'value': timeSpent
                    });
                }
            });
        }

        /**
         * Performance Optimizations
         */
        setupPerformanceOptimizations() {
            // Defer non-critical resources
            const deferScripts = document.querySelectorAll('script[data-defer]');
            deferScripts.forEach(script => {
                setTimeout(() => {
                    const newScript = document.createElement('script');
                    newScript.src = script.dataset.defer;
                    document.body.appendChild(newScript);
                }, 2000);
            });

            // Prefetch links on hover
            document.querySelectorAll('a[href^="/"]').forEach(link => {
                link.addEventListener('mouseenter', () => {
                    const href = link.getAttribute('href');
                    if (!document.querySelector(`link[rel="prefetch"][href="${href}"]`)) {
                        const prefetch = document.createElement('link');
                        prefetch.rel = 'prefetch';
                        prefetch.href = href;
                        document.head.appendChild(prefetch);
                    }
                }, { once: true, passive: true });
            });
        }

        /**
         * Utility: Announce to screen readers
         */
        announce(message) {
            if (this.liveRegion) {
                this.liveRegion.textContent = message;
            }
        }

        /**
         * Utility: Throttle function
         */
        throttle(func, limit) {
            let inThrottle;
            return function(...args) {
                if (!inThrottle) {
                    func.apply(this, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }

        /**
         * Utility: Debounce function
         */
        debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    }

    // Initialize theme
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            window.TranslatioTheme = new TranslatioTheme();
        });
    } else {
        window.TranslatioTheme = new TranslatioTheme();
    }
})();
