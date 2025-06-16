/******/ (() => { // webpackBootstrap
/*!**********************************************!*\
  !*** ./src/js/components/provider-single.js ***!
  \**********************************************/
document.addEventListener('DOMContentLoaded', function () {
  // Sticky Navigation
  var nav = document.querySelector('.provider-tabs');
  if (nav) {
    var handleScroll = function handleScroll() {
      if (window.scrollY >= navTop) {
        nav.classList.add('sticky');
      } else {
        nav.classList.remove('sticky');
      }
    };
    var navTop = nav.offsetTop;
    window.addEventListener('scroll', handleScroll);
  }

  // Smooth Scrolling for Anchor Links
  var tabLinks = document.querySelectorAll('.tab-menu a');
  tabLinks.forEach(function (link) {
    link.addEventListener('click', function (e) {
      e.preventDefault();

      // Remove active class from all links
      tabLinks.forEach(function (l) {
        return l.classList.remove('active');
      });
      // Add active class to clicked link
      this.classList.add('active');
      var targetId = this.getAttribute('href').substring(1);
      var targetElement = document.getElementById(targetId);
      if (targetElement) {
        var navHeight = nav ? nav.offsetHeight : 0;
        var targetPosition = targetElement.offsetTop - navHeight;
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    });
  });

  // Update Active Tab on Scroll
  var sections = document.querySelectorAll('.provider-section');
  function updateActiveTab() {
    var navHeight = nav ? nav.offsetHeight : 0;
    var fromTop = window.scrollY + navHeight + 50; // Add some offset

    sections.forEach(function (section) {
      var link = document.querySelector(".tab-menu a[href=\"#".concat(section.id, "\"]"));
      if (link) {
        var sectionTop = section.offsetTop - navHeight;
        var sectionBottom = sectionTop + section.offsetHeight;
        if (fromTop >= sectionTop && fromTop <= sectionBottom) {
          tabLinks.forEach(function (l) {
            return l.classList.remove('active');
          });
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
/******/ })()
;
//# sourceMappingURL=provider-single.js.map