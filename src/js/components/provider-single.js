document.addEventListener('DOMContentLoaded', function() {
    // Sticky Navigation
    const nav = document.querySelector('.provider-tabs');
    if (nav) {
        const navTop = nav.offsetTop;
        
        function handleScroll() {
            if (window.scrollY >= navTop) {
                nav.classList.add('sticky');
            } else {
                nav.classList.remove('sticky');
            }
        }

        window.addEventListener('scroll', handleScroll);
    }

    // Smooth Scrolling for Anchor Links
    const tabLinks = document.querySelectorAll('.tab-menu a');
    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            tabLinks.forEach(l => l.classList.remove('active'));
            // Add active class to clicked link
            this.classList.add('active');

            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                const navHeight = nav ? nav.offsetHeight : 0;
                const targetPosition = targetElement.offsetTop - navHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Update Active Tab on Scroll
    const sections = document.querySelectorAll('.provider-section');
    
    function updateActiveTab() {
        const navHeight = nav ? nav.offsetHeight : 0;
        const fromTop = window.scrollY + navHeight + 50; // Add some offset

        sections.forEach(section => {
            const link = document.querySelector(`.tab-menu a[href="#${section.id}"]`);
            
            if (link) {
                const sectionTop = section.offsetTop - navHeight;
                const sectionBottom = sectionTop + section.offsetHeight;

                if (fromTop >= sectionTop && fromTop <= sectionBottom) {
                    tabLinks.forEach(l => l.classList.remove('active'));
                    link.classList.add('active');
                }
            }
        });
    }

    window.addEventListener('scroll', updateActiveTab);
    window.addEventListener('resize', updateActiveTab);

    // Initialize active tab
    updateActiveTab();
}); 