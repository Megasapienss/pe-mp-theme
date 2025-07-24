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

    // Table of Contents functionality
    const tocList = document.querySelector('.hero__toc-list');
    const articleContent = document.querySelector('.article__content');

    if (tocList && articleContent) {
        const h2Headings = articleContent.querySelectorAll('h2');
        let tocLinks = tocList.querySelectorAll('a');
        let currentActiveHeading = null;
        let scrollTimeout = null;

        // Define updateCurrentHeading function
        function updateCurrentHeading() {
            let newCurrentHeading = null;

            // Check if we're at the top (Overview)
            if (h2Headings.length === 0) {
                newCurrentHeading = 'overview';
            } else {
                const firstHeading = h2Headings[0];
                const firstChild = articleContent.firstElementChild;
                
                // Check if we're at the top (Overview)
                if (firstChild) {
                    const rect = firstChild.getBoundingClientRect();
                    const firstChildTopPosition = rect.top;
                    
                    // If first child is above the offset position, we're in overview
                    if (firstChildTopPosition > 120) {
                        newCurrentHeading = 'overview';
                    } else {
                        // Find the current heading by checking which one is at the top position
                        let foundHeading = false;
                        for (let i = h2Headings.length - 1; i >= 0; i--) {
                            const heading = h2Headings[i];
                            // Skip newsletter headings
                            if (heading.textContent.toLowerCase().includes('newsletter')) {
                                continue;
                            }
                            
                            const headingRect = heading.getBoundingClientRect();
                            const headingTopPosition = headingRect.top;
                            
                            // Check if this heading is at the top position (120px from top)
                            if (headingTopPosition <= 120) {
                                newCurrentHeading = heading;
                                foundHeading = true;
                                break;
                            }
                        }
                        
                        // If no heading was found, we're in overview
                        if (!foundHeading) {
                            newCurrentHeading = 'overview';
                        }
                    }
                } else {
                    newCurrentHeading = 'overview';
                }
            }

            // Only update if the current heading has changed
            if (newCurrentHeading !== currentActiveHeading) {
                // Remove current class from all links
                tocLinks.forEach(link => link.classList.remove('current'));

                // Add current class to the new active heading
                if (newCurrentHeading === 'overview') {
                    const overviewLink = tocList.querySelector('a[href="#article-overview"]');
                    if (overviewLink) {
                        overviewLink.classList.add('current');
                    }
                } else if (newCurrentHeading) {
                    const currentLink = tocList.querySelector(`a[href="#${newCurrentHeading.id}"]`);
                    if (currentLink) {
                        currentLink.classList.add('current');
                    }
                }

                // Update the current active heading
                currentActiveHeading = newCurrentHeading;
            }
        }

        // Define throttledScrollHandler function
        function throttledScrollHandler() {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(updateCurrentHeading, 50);
        }

        // Clear existing placeholder content
        tocList.innerHTML = '';

        // Add "Overview" link
        const overviewLink = document.createElement('a');
        overviewLink.href = '#article-overview';
        overviewLink.textContent = 'Overview';
        overviewLink.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Find the first child element of article content
            const firstChild = articleContent.firstElementChild;
            if (firstChild) {
                // Use scrollIntoView with offset
                firstChild.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Temporarily disable scroll detection to prevent interference
                window.removeEventListener('scroll', throttledScrollHandler);
                
                // Re-enable after scroll animation completes
                setTimeout(() => {
                    window.addEventListener('scroll', throttledScrollHandler);
                    updateCurrentHeading();
                }, 800);
                
                history.pushState(null, null, '#article-overview');
            }
        });

        tocList.appendChild(overviewLink);

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
                    
                    // Temporarily disable scroll detection to prevent interference
                    window.removeEventListener('scroll', throttledScrollHandler);
                    
                    // Re-enable after scroll animation completes
                    setTimeout(() => {
                        window.addEventListener('scroll', throttledScrollHandler);
                        updateCurrentHeading();
                    }, 800);
                    
                    history.pushState(null, null, `#${heading.id}`);
                });

                tocList.appendChild(tocLink);
            });
        }

        // Update on scroll with throttling
        window.addEventListener('scroll', throttledScrollHandler);

        // Update tocLinks reference after all links are created
        tocLinks = tocList.querySelectorAll('a');

        // Initial update - make Overview current by default
        setTimeout(() => {
            updateCurrentHeading();
            // Force Overview to be current on page load
            const overviewLink = tocList.querySelector('a[href="#article-overview"]');
            if (overviewLink) {
                tocLinks.forEach(link => link.classList.remove('current'));
                overviewLink.classList.add('current');
            }
        }, 100);
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

        // If we found external links, create the sources section and add superscript numbers
        if (externalLinks.length > 0) {
            // Replace external links in the text with anchor links to sources
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

            // Create sources section
            const sourcesSection = document.createElement('section');
            sourcesSection.className = 'article__sources';
            sourcesSection.id = 'article-sources';
            
            const sourcesTitle = document.createElement('h2');
            sourcesTitle.textContent = 'Sources';
            sourcesSection.appendChild(sourcesTitle);
            
            const sourcesList = document.createElement('nav');
            
            externalLinks.forEach((link, index) => {
                
                const sourceLink = document.createElement('a');
                sourceLink.href = link.url;
                sourceLink.textContent = `[${index + 1}] ${link.url}`;
                sourceLink.target = '_blank';
                sourceLink.rel = 'noopener noreferrer';
                
                sourcesList.appendChild(sourceLink);
            });
            
            sourcesSection.appendChild(sourcesList);
            
            // Append to the end of article content
            articleContent.appendChild(sourcesSection);
        }
    }

    // Run external links collection
    collectExternalLinks();
});