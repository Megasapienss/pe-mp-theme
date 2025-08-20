// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {

    // Initialize all swipers with delay to ensure DOM is ready
    setTimeout(() => {
        initAllSwipers();
    }, 500);

    // Off-canvas menu toggle
    const offCanvasToggleButtons = document.querySelectorAll('.toggle--off-canvas--menu');
    const offCanvas = document.querySelector('.off-canvas--menu');

    if (offCanvasToggleButtons.length > 0 && offCanvas) {
        offCanvasToggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                offCanvas.classList.toggle('off-canvas--visible');
            });
        });
    }

    // Table of Contents functionality
    function initTableOfContents() {
        const tocList = document.querySelector('.article-v2__toc-list');
        const articleContent = document.querySelector('.article-v2__content');

        if (tocList && articleContent) {
            const h2Headings = articleContent.querySelectorAll('h2');
            let tocLinks = tocList.querySelectorAll('a');
            let currentActiveHeading = null;
            let scrollTimeout = null;

            // Clear existing placeholder content
            tocList.innerHTML = '';

            // Add ID to article content
            if (!articleContent.id) {
                articleContent.id = 'article-content';
            }

            // Create TOC links for headings
            if (h2Headings.length > 0) {
                h2Headings.forEach((heading, index) => {
                    // Skip newsletter headings
                    if (heading.textContent.toLowerCase().includes('newsletter')) {
                        return;
                    }

                    // Create ID for heading
                    if (!heading.id) {
                        heading.id = `heading-${index + 1}`;
                    }

                    // Create TOC link
                    const tocLink = document.createElement('a');
                    tocLink.href = `#${heading.id}`;
                    tocLink.textContent = heading.textContent;
                    tocLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        
                        // Use scrollIntoView with offset
                        heading.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        
                        history.pushState(null, null, `#${heading.id}`);
                    });

                    tocList.appendChild(tocLink);
                });
            }

            // Update tocLinks reference after all links are created
            tocLinks = tocList.querySelectorAll('a');
        }
    }

    initTableOfContents();

    // Native share functionality
    const shareButtons = document.querySelectorAll('.share-trigger');

    shareButtons.forEach(button => {
        button.addEventListener('click', async () => {
            // Check if Web Share API is supported
            if (navigator.share) {
                try {
                    const shareData = {
                        title: document.title,
                        text: document.querySelector('meta[name="description"]')?.content || 'Check out this article',
                        url: window.location.href
                    };

                    await navigator.share(shareData);
                } catch (error) {
                    console.log('Share cancelled or failed:', error);
                }
            } else {
                // Fallback for browsers that don't support Web Share API
                // Copy URL to clipboard
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    alert('Link copied to clipboard!');
                } catch (error) {
                    console.log('Failed to copy to clipboard:', error);
                    // Fallback: prompt user to copy manually
                    prompt('Copy this link:', window.location.href);
                }
            }
        });
    });

    // External and internal links collection and sources list
    function collectExternalLinks() {
        const articleContent = document.querySelector('.article-v2__content');
        if (!articleContent) return;

        // Get current domain for comparison
        const currentDomain = window.location.hostname;
        
        // Find all links in the article content
        const links = articleContent.querySelectorAll('a[href]');
        const externalLinks = [];
        const internalLinks = [];

        links.forEach(link => {
            const href = link.href;
            const url = new URL(href);
            
            // Get link text or fallback to URL
            const linkText = link.textContent.trim() || link.href;
            
            // Check if it's an external link (different domain)
            if (url.hostname !== currentDomain && url.hostname !== '') {
                // Check if this link is already in our list
                const existingLink = externalLinks.find(item => item.url === href);
                if (!existingLink) {
                    externalLinks.push({
                        text: linkText,
                        url: href
                    });
                }
            } else {
                // Check if it's an internal link (same domain) and not just a fragment/anchor
                if (url.hostname === currentDomain && url.pathname !== window.location.pathname) {
                    // Check if this link is already in our list
                    const existingLink = internalLinks.find(item => item.url === href);
                    if (!existingLink) {
                        internalLinks.push({
                            text: linkText,
                            url: href
                        });
                    }
                }
            }
        });

        // If we found any links, populate the sources section
        if (externalLinks.length > 0 || internalLinks.length > 0) {
            // Function to add superscript numbers to external links in text (currently disabled)
            function addSuperscriptNumbersToLinks() {
                links.forEach(link => {
                    const href = link.href;
                    const url = new URL(href);
                    
                    // Check if it's an external link (different domain)
                    if (url.hostname !== currentDomain && url.hostname !== '') {
                        // Find the index of this link in our external links array
                        const linkIndex = externalLinks.findIndex(item => item.url === href);
                        if (linkIndex !== -1) {
                            // Store the original link text
                            const originalText = link.textContent;
                            
                            // Create new anchor link
                            const newLink = document.createElement('a');
                            newLink.href = '#article-sources';
                            newLink.textContent = originalText;
                            
                            // Add smooth scroll behavior
                            newLink.addEventListener('click', (e) => {
                                e.preventDefault();
                                const sourcesSection = document.getElementById('article-sources');
                                if (sourcesSection) {
                                    sourcesSection.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'start'
                                    });
                                }
                            });
                            
                            // Create superscript element
                            const superscript = document.createElement('sup');
                            superscript.textContent = '[' + (linkIndex + 1) + ']';
                            superscript.style.fontSize = '0.75em';
                            superscript.style.verticalAlign = 'super';
                            superscript.style.paddingLeft = '2px';
                            
                            // Append superscript to the new link
                            newLink.appendChild(superscript);
                            
                            // Replace the original link with the new one
                            link.parentNode.replaceChild(newLink, link);
                        }
                    }
                });
            }
            
            // Uncomment the line below to enable superscript numbers on external links
            // addSuperscriptNumbersToLinks();
            
            // Populate existing sources section
            const sourcesList = document.querySelector('.sources-list');
            if (sourcesList) {
                // Clear existing content
                sourcesList.innerHTML = '';
                
                // Add external sources first
                if (externalLinks.length > 0) {
                    const externalHeading = document.createElement('h4');
                    externalHeading.className = 'sources-list__heading';
                    externalHeading.textContent = 'External';
                    sourcesList.appendChild(externalHeading);

                    externalLinks.forEach((link, index) => {
                        const sourceLink = document.createElement('a');
                        sourceLink.href = link.url;
                        sourceLink.innerHTML = `<span>${index + 1}.</span> <span>${link.url}</span>`;
                        sourceLink.target = '_blank';
                        sourceLink.rel = 'noopener noreferrer';
                        
                        sourcesList.appendChild(sourceLink);
                    });
                }
                
                // Add internal sources section if there are internal links
                if (internalLinks.length > 0) {
                    // Add separator and heading
                    
                    const internalHeading = document.createElement('h4');
                    internalHeading.className = 'sources-list__heading';
                    internalHeading.textContent = 'Internal';
                    sourcesList.appendChild(internalHeading);
                    
                    // Add internal links
                    internalLinks.forEach((link, index) => {
                        const sourceLink = document.createElement('a');
                        sourceLink.href = link.url;
                        sourceLink.innerHTML = `<span>${index + 1}.</span> <span>${link.url}</span>`;
                        
                        sourcesList.appendChild(sourceLink);
                    });
                }
            }
        } else {
            // Hide the sources tab if no links are found
            const sourcesTab = document.querySelector('[data-tab="sources"]');
            if (sourcesTab) {
                sourcesTab.style.display = 'none';
            }
            
            // Also hide the sources tab button if it exists
            const sourcesTabButton = document.querySelector('.tabs__button[data-tab="sources"]');
            if (sourcesTabButton) {
                sourcesTabButton.style.display = 'none';
            }
        }
    }

    // Run external links collection
    collectExternalLinks();

    // Accordion functionality
    function initAccordion() {
        // console.log('Accordion - Initializing accordion functionality');

        // Find all accordion headers
        const accordionHeaders = document.querySelectorAll('.accordion__header');

        // console.log('Accordion - Accordion headers found:', accordionHeaders.length);

        // Add click event listeners to each accordion header
        accordionHeaders.forEach((header, index) => {
            // console.log(`Accordion - Setting up accordion header ${index}:`, header);

            header.addEventListener('click', (e) => {
                console.log('Accordion - Accordion header clicked:', header);
                e.preventDefault();
                toggleAccordion(header);
            });

            // Add cursor pointer style
            header.style.cursor = 'pointer';
        });
    }

    function toggleAccordion(header) {
        console.log('Accordion - toggleAccordion called with header:', header);

        const accordion = header.closest('.accordion');
        const body = accordion.querySelector('.accordion__body');
        const arrow = header.querySelector('img');

        if (!accordion || !body) {
            console.log('Accordion - Accordion body not found');
            return;
        }

        // Toggle the open class
        const isOpen = body.classList.contains('accordion__body--open');

        if (isOpen) {
            // Close accordion
            body.classList.remove('accordion__body--open');
            if (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            }
        } else {
            // Open accordion
            body.classList.add('accordion__body--open');
            if (arrow) {
                arrow.style.transform = 'rotate(180deg)';
            }
        }
    }

    // Initialize accordion functionality
    initAccordion();

    // Tabs functionality
    function initTabs() {
        const tabContainers = document.querySelectorAll('.tabs');
        
        tabContainers.forEach(container => {
            const tabButtons = container.querySelectorAll('.tabs__button');
            const tabContents = container.querySelectorAll('.tabs__content[data-tab]');
            
            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const targetTab = button.getAttribute('data-tab');
                    
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('tabs__button--active'));
                    tabContents.forEach(content => content.classList.remove('tabs__content--active'));
                    
                    // Add active class to clicked button and corresponding content
                    button.classList.add('tabs__button--active');
                    const targetContent = container.querySelector(`.tabs__content[data-tab="${targetTab}"]`);
                    if (targetContent) {
                        targetContent.classList.add('tabs__content--active');
                    }
                });
            });
            
            // Set first tab as active by default
            if (tabButtons.length > 0) {
                tabButtons[0].click();
            }
        });
    }

    // Initialize tabs functionality
    initTabs();

    // Unified Swiper initialization
    function initAllSwipers() {
        const gallerySwiper = document.querySelector('.gallery-swiper');
        const galleryThumbs = document.querySelector('.gallery-thumbs');
        const testsSwiper = document.querySelector('.tests-swiper');
        
        // Check if any swipers exist
        if (!gallerySwiper && !testsSwiper) {
            // console.log('No swipers found, skipping initialization');
            return;
        }
        
        // Check if Swiper is already loaded
        if (typeof Swiper !== 'undefined') {
            // console.log('Swiper already loaded, initializing directly');
            initializeAllSwiperInstances();
        } else {
            // console.log('Loading Swiper from CDN...');
            // Load Swiper dynamically
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
            script.onload = function() {
                // console.log('Swiper JS loaded successfully');
                // Load Swiper CSS
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
                document.head.appendChild(link);
                
                // Initialize after CSS is loaded
                setTimeout(() => {
                    // console.log('Initializing all Swiper instances...');
                    initializeAllSwiperInstances();
                }, 100);
            };
            script.onerror = function() {
                console.error('Failed to load Swiper from CDN');
            };
            document.head.appendChild(script);
        }
        
        function initializeAllSwiperInstances() {
            try {
                // Check if swiper is already initialized
                if (window.galleryMainSwiper && window.galleryThumbsSwiper) {
                    // console.log('Gallery swiper already initialized, skipping...');
                    return;
                }
                
                // Initialize gallery swiper if it exists
                if (gallerySwiper && galleryThumbs) {
                    // console.log('Initializing gallery swiper...');
                    
                    // Check for images
                    const images = gallerySwiper.querySelectorAll('img');
                    if (images.length > 0) {
                        // Initialize thumbnail swiper
                        const thumbsSwiper = new Swiper(galleryThumbs, {
                            spaceBetween: 10,
                            slidesPerView: 3,
                            freeMode: false,
                            watchSlidesProgress: true,
                            grabCursor: true,
                            allowTouchMove: true,
                            touchRatio: 1,
                            touchAngle: 45,
                            simulateTouch: true,
                            shortSwipes: true,
                            longSwipes: true,
                            longSwipesRatio: 0.5,
                            longSwipesMs: 300,
                            followFinger: true,
                            threshold: 0,
                            touchMoveStopPropagation: false,
                            breakpoints: {
                                320: {
                                    slidesPerView: 3,
                                    spaceBetween: 5
                                },
                                768: {
                                    slidesPerView: 3,
                                    spaceBetween: 10
                                },
                                1024: {
                                    slidesPerView: 3,
                                    spaceBetween: 10
                                }
                            }
                        });

                        // Initialize main gallery swiper
                        const mainSwiper = new Swiper(gallerySwiper, {
                            spaceBetween: 10,
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            thumbs: {
                                swiper: thumbsSwiper,
                            },
                            keyboard: {
                                enabled: true,
                            },
                            loop: false,
                            autoplay: false,
                            effect: 'slide',
                            speed: 300,
                            allowTouchMove: true,
                            touchRatio: 1,
                            touchAngle: 45,
                            simulateTouch: true,
                            shortSwipes: true,
                            longSwipes: true,
                            longSwipesRatio: 0.5,
                            longSwipesMs: 300,
                            followFinger: true,
                            threshold: 0,
                            touchMoveStopPropagation: false,
                            // Prevent swiper from being destroyed
                            observer: true,
                            observeParents: true,
                            // Ensure navigation works properly
                            watchSlidesProgress: true,
                            watchSlidesVisibility: true
                        });

                        // console.log('Gallery Swiper initialized successfully:', { mainSwiper, thumbsSwiper });
                        
                        // Store swiper instances globally for debugging
                        window.galleryMainSwiper = mainSwiper;
                        window.galleryThumbsSwiper = thumbsSwiper;
                        
                        // Handle grabbing state for better UX
                        thumbsSwiper.on('touchStart', () => {
                            galleryThumbs.classList.add('swiper-grabbing');
                        });
                        
                        thumbsSwiper.on('touchEnd', () => {
                            galleryThumbs.classList.remove('swiper-grabbing');
                        });
                        
                        // Add navigation event listeners for debugging
                        mainSwiper.on('slideChange', () => {
                            console.log('Gallery slide changed to:', mainSwiper.activeIndex);
                        });
                    }
                }
                
                // Initialize tests swiper if it exists
                if (testsSwiper) {
                    // console.log('Initializing tests swiper...');
                    
                    // Calculate dynamic offset based on screen width and container width (including padding)
                    const calculateSlidesOffsetAfter = () => {
                        const screenWidth = window.innerWidth;
                        const container = document.querySelector('.tests-section > .container');
                        const containerWidth = container.offsetWidth;
                        const containerStyle = window.getComputedStyle(container);
                        const containerPaddingLeft = parseFloat(containerStyle.paddingLeft);
                        const containerPaddingRight = parseFloat(containerStyle.paddingRight);
                        const totalContainerWidth = containerWidth - (containerPaddingLeft + containerPaddingRight);
                        const offset = (screenWidth - totalContainerWidth) / 2;
                        return offset;
                    };
                    
                    const testsSwiperInstance = new Swiper(testsSwiper, {
                        spaceBetween: 20,
                        slidesPerView: 'auto',
                        grabCursor: true,
                        allowTouchMove: true,
                        touchRatio: 1,
                        touchAngle: 45,
                        simulateTouch: true,
                        shortSwipes: true,
                        longSwipes: true,
                        longSwipesRatio: 0.5,
                        longSwipesMs: 300,
                        followFinger: true,
                        threshold: 0,
                        touchMoveStopPropagation: false,
                        // Enhanced drag settings
                        resistance: true,
                        resistanceRatio: 0.85,
                        watchSlidesProgress: true,
                        watchSlidesVisibility: true,
                        // Mouse drag settings
                        mousewheel: {
                            forceToAxis: true,
                            sensitivity: 1,
                            releaseOnEdges: true,
                        },
                        // Free mode for more natural dragging
                        freeMode: {
                            enabled: true,
                            sticky: false,
                            momentum: true,
                            momentumRatio: 0.25,
                            momentumVelocityRatio: 0.25,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        keyboard: {
                            enabled: true,
                        },
                        loop: false,
                        autoplay: false,
                        effect: 'slide',
                        speed: 300,
                        // Edge-to-edge configuration
                        slidesOffsetBefore: calculateSlidesOffsetAfter(),
                        slidesOffsetAfter: calculateSlidesOffsetAfter()
                    });
                    
                    // Update offset on window resize
                    window.addEventListener('resize', () => {
                        testsSwiperInstance.params.slidesOffsetAfter = calculateSlidesOffsetAfter();
                        testsSwiperInstance.update();
                    });

                    // console.log('Tests Swiper initialized successfully:', testsSwiperInstance);
                }
                
            } catch (error) {
                console.error('Error initializing Swipers:', error);
            }
        }
    }

    // Provider condition filter
    function initProviderFilter() {
        const conditionFilter = document.getElementById('condition-filter');
        if (conditionFilter) {
            // Check for condition parameter in URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            const urlCondition = urlParams.get('condition');
            
            if (urlCondition) {
                // Set the select value to match URL parameter
                conditionFilter.value = urlCondition;
                
                // Add a small delay to ensure everything is ready, then trigger the filter
                setTimeout(() => {
                    conditionFilter.dispatchEvent(new Event('change'));
                }, 100);
            }
            
            conditionFilter.addEventListener('change', function() {
                const condition = this.value;
                const resultsContainer = document.getElementById('provider-results');
                
                if (!resultsContainer) return;
                
                // Show loading state
                resultsContainer.innerHTML = '<p class="text-center">Loading...</p>';
                
                // Prepare AJAX data
                const formData = new FormData();
                formData.append('action', 'filter_providers');
                formData.append('condition', condition);
                formData.append('nonce', pe_mp_ajax.nonce);
                
                // Make AJAX request
                fetch(pe_mp_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultsContainer.innerHTML = data.data.html;
                        
                        // Update URL without page reload
                        const url = new URL(window.location);
                        if (condition) {
                            url.searchParams.set('condition', condition);
                        } else {
                            url.searchParams.delete('condition');
                        }
                        window.history.pushState({}, '', url);
                    } else {
                        resultsContainer.innerHTML = '<p class="text-center">Error loading results.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsContainer.innerHTML = '<p class="text-center">Error loading results.</p>';
                });
            });
        }
    }

    // Initialize provider filter with delay to ensure DOM is ready
    setTimeout(initProviderFilter, 500);
});