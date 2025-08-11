// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {

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

    // Initialize tests section horizontal scroll
    initTestsSectionScroll();

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

        // Mouse wheel scrolling for TOC
        if (tocList) {
            tocList.addEventListener('wheel', (e) => {
                e.preventDefault();
                // Reduce scroll sensitivity for smoother movement
                const scrollAmount = e.deltaY * 3;
                tocList.scrollLeft += scrollAmount;
            });
        }

        // Sticky TOC functionality
        const toc = document.querySelector('.hero__toc');
        if (toc) {
            const tocWrapper = document.querySelector('.hero__toc-wrapper');
            const tocOffset = tocWrapper.offsetTop;

            window.addEventListener('scroll', () => {
                if (window.pageYOffset >= tocOffset) {
                    toc.classList.add('sticky');
                } else {
                    toc.classList.remove('sticky');
                }
            });
        }
    }

    initTableOfContents();


    // Native share functionality
    const shareButtons = document.querySelectorAll('.label--share');

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

    // External links collection and sources list
    function collectExternalLinks() {
        const articleContent = document.querySelector('.article__content');
        if (!articleContent) return;

        // Get current domain for comparison
        const currentDomain = window.location.hostname;
        
        // Find all links in the article content
        const links = articleContent.querySelectorAll('a[href]');
        const externalLinks = [];

        links.forEach(link => {
            const href = link.href;
            const url = new URL(href);
            
            // Check if it's an external link (different domain)
            if (url.hostname !== currentDomain && url.hostname !== '') {
                // Get link text or fallback to URL
                const linkText = link.textContent.trim() || link.href;
                
                // Check if this link is already in our list
                const existingLink = externalLinks.find(item => item.url === href);
                if (!existingLink) {
                    externalLinks.push({
                        text: linkText,
                        url: href
                    });
                }
            }
        });

        // If we found external links, populate the sources section
        if (externalLinks.length > 0) {
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
                externalLinks.forEach((link, index) => {
                    const sourceLink = document.createElement('a');
                    sourceLink.href = link.url;
                    sourceLink.textContent = `[${index + 1}] ${link.url}`;
                    sourceLink.target = '_blank';
                    sourceLink.rel = 'noopener noreferrer';
                    sourceLink.className = 'accordion__link';
                    
                    sourcesList.appendChild(sourceLink);
                });
            }
        }
    }

    // Run external links collection
    collectExternalLinks();

    // Accordion functionality
    function initAccordion() {
        console.log('Accordion - Initializing accordion functionality');

        // Find all accordion headers
        const accordionHeaders = document.querySelectorAll('.accordion__header');

        console.log('Accordion - Accordion headers found:', accordionHeaders.length);

        if (accordionHeaders.length === 0) {
            console.log('Accordion - No accordion headers found');
            // Try again after a short delay in case content loads later
            setTimeout(() => {
                console.log('Accordion - Retrying accordion initialization...');
                initAccordion();
            }, 1000);
            return;
        }

        // Add click event listeners to each accordion header
        accordionHeaders.forEach((header, index) => {
            console.log(`Accordion - Setting up accordion header ${index}:`, header);

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

    // Tests section horizontal scroll functionality
    function initTestsSectionScroll() {
        const testsSection = document.querySelector('.tests-section__content');
        
        if (!testsSection) return;
        
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;
        
        // Draggable functionality
        testsSection.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX - testsSection.offsetLeft;
            scrollLeft = testsSection.scrollLeft;
            testsSection.style.cursor = 'grabbing';
            testsSection.style.userSelect = 'none';
        });
        
        testsSection.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            
            e.preventDefault();
            const x = e.pageX - testsSection.offsetLeft;
            const walk = (x - startX) * 2; // Scroll speed multiplier
            testsSection.scrollLeft = scrollLeft - walk;
        });
        
        testsSection.addEventListener('mouseup', () => {
            isDragging = false;
            testsSection.style.cursor = 'grab';
            testsSection.style.userSelect = 'auto';
        });
        
        testsSection.addEventListener('mouseleave', () => {
            isDragging = false;
            testsSection.style.cursor = 'grab';
            testsSection.style.userSelect = 'auto';
        });
        
        // Set initial cursor style
        testsSection.style.cursor = 'grab';
    }
});