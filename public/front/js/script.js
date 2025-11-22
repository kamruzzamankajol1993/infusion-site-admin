// --- NEW: Our Solutions Section Animations ---
document.addEventListener('DOMContentLoaded', () => {
    const solutionsSection = document.getElementById('our-solutions');
    const solutionsTitle = solutionsSection ? solutionsSection.querySelector('.section-title h2') : null;
    const solutionsSubtitle = solutionsSection ? solutionsSection.querySelector('.subtitle') : null;
    const solutionsDescription = solutionsSection ? solutionsSection.querySelector('.section-description') : null;
    const solutionsItems = solutionsSection ? solutionsSection.querySelectorAll('.solutions-item') : null;

    if (solutionsSection) {
        const solutionsObserverOptions = {
            root: null, // viewport
            rootMargin: '0px',
            threshold: 0.2 // Trigger when 20% of the section is visible
        };

        const solutionsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    solutionsSection.classList.add('is-visible'); // Add class to trigger CSS animations for titles/description/cards
                    observer.unobserve(entry.target); // Stop observing once animated
                }
            });
        }, solutionsObserverOptions);

        solutionsObserver.observe(solutionsSection);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Other existing JavaScript code should go here if you have any.
    // ...

    // --- At a Glance Number Animation ---
    const glanceSection = document.getElementById('at-a-glance');
    if (glanceSection) {
        const observerOptions = {
            root: null, // Use the viewport as the root
            rootMargin: '0px',
            threshold: 0.5 // Trigger when 50% of the section is visible
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    glanceSection.classList.add('is-visible');
                    // Start animation when section is in view
                    animateNumbers();
                    observer.unobserve(entry.target); // Stop observing once animated
                }
            });
        }, observerOptions);

        observer.observe(glanceSection); // Start observing the section
    }

    function animateNumbers() {
        document.querySelectorAll('.glance-number').forEach(numberElement => {
            const target = parseInt(numberElement.getAttribute('data-target'));
            let current = 0;
            const duration = 2000; // 2 seconds
            const increment = target / (duration / 16); // ~60 frames per second

            const updateNumber = () => {
                current += increment;
                if (current < target) {
                    // Add K or + for larger numbers, or round for integers
                    if (target >= 1000) {
                        numberElement.textContent = Math.round(current / 1000) + 'K+';
                    } else if (target > 0 && target < 1000 && numberElement.textContent.endsWith('Yrs Experienced')) {
                        numberElement.textContent = Math.round(current) + '+';
                    }
                    else {
                         numberElement.textContent = Math.round(current);
                    }
                    requestAnimationFrame(updateNumber);
                } else {
                    // Ensure the final number is exact and includes + or K+
                    if (target >= 1000) {
                        numberElement.textContent = (target / 1000) + 'K+';
                    } else if (target > 0 && target < 1000) { // Specific for 9+ Yrs Experienced or similar
                        numberElement.textContent = target + '+';
                    } else {
                        numberElement.textContent = target;
                    }
                }
            };
            requestAnimationFrame(updateNumber);
        });
    }

    // --- END At a Glance Number Animation ---

    // --- NEW: Client Logo Swiper Initialization ---
    var clientSwiper = new Swiper('.client-logo-swiper', {
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            // Mobile: 2 slides
            320: {
                slidesPerView: 2,
                spaceBetween: 15
            },
            // Tablet: 3 slides
            576: {
                slidesPerView: 3,
                spaceBetween: 20
            },
            // Desktop: 5 slides
            992: {
                slidesPerView: 5,
                spaceBetween: 30
            }
        }
    });

    // --- NEW: Global Clients Section Animation Observer ---
    const clientsSection = document.getElementById('global-clients');
    if (clientsSection) {
        const clientsObserverOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2 
        };

        const clientsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    clientsSection.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, clientsObserverOptions);

        clientsObserver.observe(clientsSection);
    }

    // --- Digital Care Benefits Section Animation Observer ---
    const whyUsSection = document.getElementById('why-us');
    if (whyUsSection) {
        const whyUsObserverOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1 // Trigger when 10% is visible
        };

        const whyUsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    whyUsSection.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, whyUsObserverOptions);

        whyUsObserver.observe(whyUsSection);
    }
});

// --- NEW: Generic "Fade In On Scroll" Observer ---
// This will animate any element with the class "fade-in-section"
document.addEventListener('DOMContentLoaded', () => {
    const animatedSections = document.querySelectorAll('.fade-in-section');
    
    if (animatedSections.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1 // Trigger when 10% of the element is visible
        };
    
        const animationObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Stop observing once animated
                }
            });
        }, observerOptions);
    
        animatedSections.forEach(section => {
            animationObserver.observe(section);
        });
    }

    // --- "Marketing Solutions" Section Animation (services.html) ---
    const marketingSolutionsSection = document.getElementById('marketing-solutions'); 
    if (marketingSolutionsSection) { 
        const solutionsObserverOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.2 // Trigger when 20% is visible
        };
        const solutionsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    marketingSolutionsSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, solutionsObserverOptions);
        solutionsObserver.observe(marketingSolutionsSection); 
    }

    // --- "Service Includes" Section Animation (web-development.html) ---
    const includesSection = document.getElementById('service-includes'); 
    if (includesSection) { 
        const includesObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.2 
        };
        const includesObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    includesSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, includesObserverOptions);
        includesObserver.observe(includesSection); 
    }

    // --- "Service Providing" Section Animation (web-development.html) ---
    const providingSection = document.getElementById('service-providing'); 
    if (providingSection) { 
        const providingObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.2 
        };
        const providingObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    providingSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, providingObserverOptions);
        providingObserver.observe(providingSection); 
    }

    // --- "Previous Work" Section Animation (web-development.html) ---
    const prevWorkSection = document.getElementById('previous-work'); 
    if (prevWorkSection) { 
        const prevWorkObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.1 // Low threshold to trigger early
        };
        const prevWorkObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    prevWorkSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, prevWorkObserverOptions);
        prevWorkObserver.observe(prevWorkSection); 
    }

    // --- "CTA Banner" Section Animation (web-development.html) ---
    const ctaBannerSection = document.getElementById('cta-banner'); 
    if (ctaBannerSection) { 
        const ctaObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.2 
        };
        const ctaObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    ctaBannerSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, ctaObserverOptions);
        ctaObserver.observe(ctaBannerSection); 
    }

    // --- "Website Care" Section Animation (web-development.html) ---
    const careSection = document.getElementById('website-care'); 
    if (careSection) { 
        const careObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.1 // Low threshold
        };
        const careObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    careSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, careObserverOptions);
        careObserver.observe(careSection); 
    }

    // --- "FB Page Pricing" Section Animation (facebook-page-setup.html) ---
    const fbPricingSection = document.getElementById('fb-page-pricing'); 
    if (fbPricingSection) { 
        const fbPricingObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.1 
        };
        const fbPricingObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    fbPricingSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, fbPricingObserverOptions);
        fbPricingObserver.observe(fbPricingSection); 
    }

    // --- "More FB Services" Section Animation (facebook-page-setup.html) ---
    const moreFbSection = document.getElementById('more-fb-services'); 
    if (moreFbSection) { 
        const moreFbObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.1 
        };
        const moreFbObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    moreFbSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, moreFbObserverOptions);
        moreFbObserver.observe(moreFbSection); 
    }

    // --- "UK Pricing" Section Animation (uk-company-registration.html) ---
   // --- "UK Pricing" Section Animation (uk-company-registration.html) ---
    const ukPricingSection = document.getElementById('uk-pricing'); 
    if (ukPricingSection) { 
        const ukPricingObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.1 
        };
        const ukPricingObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    ukPricingSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, ukPricingObserverOptions);
        ukPricingObserver.observe(ukPricingSection); 
    }

    // --- "Customer Reviews" Section Animation ---
    const reviewsSection = document.getElementById('customer-reviews'); 
    if (reviewsSection) { 
        const reviewsObserverOptions = {
            root: null, rootMargin: '0px', threshold: 0.2 
        };
        const reviewsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    reviewsSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, reviewsObserverOptions);
        reviewsObserver.observe(reviewsSection); 
    }
    // --- "VPS Pricing" Section Animations (vps.html) ---
    const vpsBrowserSection = document.getElementById('vps-pricing-browser'); 
    if (vpsBrowserSection) { 
        const vpsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    vpsBrowserSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        vpsObserver.observe(vpsBrowserSection); 
    }

    const vpsStarterSection = document.getElementById('vps-pricing-starter'); 
    if (vpsStarterSection) { 
        const vpsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    vpsStarterSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        vpsObserver.observe(vpsStarterSection); 
    }

    const vpsPrivateSection = document.getElementById('vps-pricing-private'); 
    if (vpsPrivateSection) { 
        const vpsObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    vpsPrivateSection.classList.add('is-visible'); 
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        vpsObserver.observe(vpsPrivateSection); 
    }
});
// --- END Generic Observer ---