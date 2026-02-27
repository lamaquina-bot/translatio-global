/**
 * Navigation Module - Translatio Global Theme
 * Handles mobile menu, smooth scroll, and navigation interactions
 */

(function() {
    'use strict';

    class Navigation {
        constructor() {
            this.header = document.querySelector('.header');
            this.menuToggle = document.querySelector('.menu-toggle');
            this.mobileMenu = document.querySelector('.mobile-menu');
            this.menuLinks = document.querySelectorAll('.header__menu a, .mobile-menu a');
            this.lastScrollTop = 0;
            this.scrollThreshold = 100;
            
            this.init();
        }

        init() {
            this.setupMobileMenu();
            this.setupSmoothScroll();
            this.setupHeaderScroll();
            this.setupActiveLink();
            this.setupKeyboardNav();
        }

        /**
         * Mobile Menu Toggle
         */
        setupMobileMenu() {
            if (!this.menuToggle) return;

            this.menuToggle.addEventListener('click', () => {
                this.toggleMobileMenu();
            });

            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (this.mobileMenu && this.mobileMenu.classList.contains('active')) {
                    if (!this.mobileMenu.contains(e.target) && !this.menuToggle.contains(e.target)) {
                        this.closeMobileMenu();
                    }
                }
            });

            // Close menu on link click
            if (this.mobileMenu) {
                this.mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', () => {
                        this.closeMobileMenu();
                    });
                });
            }
        }

        toggleMobileMenu() {
            if (!this.mobileMenu) return;

            const isActive = this.mobileMenu.classList.toggle('active');
            this.menuToggle.classList.toggle('active');
            
            // Accessibility
            this.menuToggle.setAttribute('aria-expanded', isActive);
            this.mobileMenu.setAttribute('aria-hidden', !isActive);
            
            // Prevent body scroll when menu is open
            document.body.style.overflow = isActive ? 'hidden' : '';
            
            // Animate menu items
            if (isActive) {
                this.animateMenuItems();
            }
        }

        closeMobileMenu() {
            if (!this.mobileMenu) return;

            this.mobileMenu.classList.remove('active');
            this.menuToggle.classList.remove('active');
            this.menuToggle.setAttribute('aria-expanded', 'false');
            this.mobileMenu.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        animateMenuItems() {
            const items = this.mobileMenu.querySelectorAll('li');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'all 0.3s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 100);
            });
        }

        /**
         * Smooth Scroll for Anchor Links
         */
        setupSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', (e) => {
                    const targetId = anchor.getAttribute('href');
                    
                    if (targetId === '#' || targetId === '#0') return;
                    
                    const targetElement = document.querySelector(targetId);
                    
                    if (targetElement) {
                        e.preventDefault();
                        
                        const headerHeight = this.header ? this.header.offsetHeight : 0;
                        const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight;
                        
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                        
                        // Update URL without jumping
                        history.pushState(null, null, targetId);
                    }
                });
            });
        }

        /**
         * Header Scroll Effects
         */
        setupHeaderScroll() {
            if (!this.header) return;

            let ticking = false;

            window.addEventListener('scroll', () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        this.handleHeaderScroll();
                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }

        handleHeaderScroll() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Add/remove scrolled class
            if (scrollTop > this.scrollThreshold) {
                this.header.classList.add('scrolled');
            } else {
                this.header.classList.remove('scrolled');
            }

            // Hide/show header on scroll (optional)
            // Uncomment to enable hide-on-scroll behavior
            /*
            if (scrollTop > this.lastScrollTop && scrollTop > 200) {
                this.header.style.transform = 'translateY(-100%)';
            } else {
                this.header.style.transform = 'translateY(0)';
            }
            */

            this.lastScrollTop = scrollTop;
        }

        /**
         * Active Link Highlighting
         */
        setupActiveLink() {
            const sections = document.querySelectorAll('section[id]');
            
            if (sections.length === 0) return;

            window.addEventListener('scroll', () => {
                let current = '';
                const headerHeight = this.header ? this.header.offsetHeight : 0;

                sections.forEach(section => {
                    const sectionTop = section.offsetTop - headerHeight - 100;
                    const sectionBottom = sectionTop + section.offsetHeight;

                    if (window.pageYOffset >= sectionTop && window.pageYOffset < sectionBottom) {
                        current = section.getAttribute('id');
                    }
                });

                this.menuLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('active');
                    }
                });
            });
        }

        /**
         * Keyboard Navigation
         */
        setupKeyboardNav() {
            document.addEventListener('keydown', (e) => {
                // ESC to close mobile menu
                if (e.key === 'Escape' && this.mobileMenu && this.mobileMenu.classList.contains('active')) {
                    this.closeMobileMenu();
                    this.menuToggle.focus();
                }
            });

            // Trap focus in mobile menu when open
            if (this.mobileMenu) {
                const focusableElements = this.mobileMenu.querySelectorAll('a, button, input, select, textarea');
                const firstFocusable = focusableElements[0];
                const lastFocusable = focusableElements[focusableElements.length - 1];

                this.mobileMenu.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusable) {
                                e.preventDefault();
                                lastFocusable.focus();
                            }
                        } else {
                            if (document.activeElement === lastFocusable) {
                                e.preventDefault();
                                firstFocusable.focus();
                            }
                        }
                    }
                });
            }
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => new Navigation());
    } else {
        new Navigation();
    }
})();
