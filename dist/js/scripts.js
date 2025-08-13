/******/ (() => { // webpackBootstrap
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {
  // Initialize all swipers with delay to ensure DOM is ready
  setTimeout(function () {
    initAllSwipers();
  }, 100);

  // Off-canvas menu toggle
  var offCanvasToggleButtons = document.querySelectorAll('.toggle--off-canvas--menu');
  var offCanvas = document.querySelector('.off-canvas--menu');
  if (offCanvasToggleButtons.length > 0 && offCanvas) {
    offCanvasToggleButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        offCanvas.classList.toggle('off-canvas--visible');
      });
    });
  }

  // Table of Contents functionality
  function initTableOfContents() {
    var tocList = document.querySelector('.article-v2__toc-list');
    var articleContent = document.querySelector('.article-v2__content');
    if (tocList && articleContent) {
      var h2Headings = articleContent.querySelectorAll('h2');
      var tocLinks = tocList.querySelectorAll('a');
      var currentActiveHeading = null;
      var scrollTimeout = null;

      // Clear existing placeholder content
      tocList.innerHTML = '';

      // Add ID to article content
      if (!articleContent.id) {
        articleContent.id = 'article-content';
      }

      // Create TOC links for headings
      if (h2Headings.length > 0) {
        h2Headings.forEach(function (heading, index) {
          // Skip newsletter headings
          if (heading.textContent.toLowerCase().includes('newsletter')) {
            return;
          }

          // Create ID for heading
          if (!heading.id) {
            heading.id = "heading-".concat(index + 1);
          }

          // Create TOC link
          var tocLink = document.createElement('a');
          tocLink.href = "#".concat(heading.id);
          tocLink.textContent = heading.textContent;
          tocLink.addEventListener('click', function (e) {
            e.preventDefault();

            // Use scrollIntoView with offset
            heading.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
            history.pushState(null, null, "#".concat(heading.id));
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
  var shareButtons = document.querySelectorAll('.share-trigger');
  shareButtons.forEach(function (button) {
    button.addEventListener('click', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
      var _document$querySelect, shareData, _t, _t2;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.p = _context.n) {
          case 0:
            if (!navigator.share) {
              _context.n = 5;
              break;
            }
            _context.p = 1;
            shareData = {
              title: document.title,
              text: ((_document$querySelect = document.querySelector('meta[name="description"]')) === null || _document$querySelect === void 0 ? void 0 : _document$querySelect.content) || 'Check out this article',
              url: window.location.href
            };
            _context.n = 2;
            return navigator.share(shareData);
          case 2:
            _context.n = 4;
            break;
          case 3:
            _context.p = 3;
            _t = _context.v;
            console.log('Share cancelled or failed:', _t);
          case 4:
            _context.n = 8;
            break;
          case 5:
            _context.p = 5;
            _context.n = 6;
            return navigator.clipboard.writeText(window.location.href);
          case 6:
            alert('Link copied to clipboard!');
            _context.n = 8;
            break;
          case 7:
            _context.p = 7;
            _t2 = _context.v;
            console.log('Failed to copy to clipboard:', _t2);
            // Fallback: prompt user to copy manually
            prompt('Copy this link:', window.location.href);
          case 8:
            return _context.a(2);
        }
      }, _callee, null, [[5, 7], [1, 3]]);
    })));
  });

  // External links collection and sources list
  function collectExternalLinks() {
    var articleContent = document.querySelector('.article-v2__content');
    if (!articleContent) return;

    // Get current domain for comparison
    var currentDomain = window.location.hostname;

    // Find all links in the article content
    var links = articleContent.querySelectorAll('a[href]');
    var externalLinks = [];
    links.forEach(function (link) {
      var href = link.href;
      var url = new URL(href);

      // Check if it's an external link (different domain)
      if (url.hostname !== currentDomain && url.hostname !== '') {
        // Get link text or fallback to URL
        var linkText = link.textContent.trim() || link.href;

        // Check if this link is already in our list
        var existingLink = externalLinks.find(function (item) {
          return item.url === href;
        });
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
      var addSuperscriptNumbersToLinks = function addSuperscriptNumbersToLinks() {
        links.forEach(function (link) {
          var href = link.href;
          var url = new URL(href);

          // Check if it's an external link (different domain)
          if (url.hostname !== currentDomain && url.hostname !== '') {
            // Find the index of this link in our external links array
            var linkIndex = externalLinks.findIndex(function (item) {
              return item.url === href;
            });
            if (linkIndex !== -1) {
              // Store the original link text
              var originalText = link.textContent;

              // Create new anchor link
              var newLink = document.createElement('a');
              newLink.href = '#article-sources';
              newLink.textContent = originalText;

              // Add smooth scroll behavior
              newLink.addEventListener('click', function (e) {
                e.preventDefault();
                var sourcesSection = document.getElementById('article-sources');
                if (sourcesSection) {
                  sourcesSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                  });
                }
              });

              // Create superscript element
              var superscript = document.createElement('sup');
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
      }; // Uncomment the line below to enable superscript numbers on external links
      // addSuperscriptNumbersToLinks();
      // Populate existing sources section
      var sourcesList = document.querySelector('.sources-list');
      if (sourcesList) {
        externalLinks.forEach(function (link, index) {
          var sourceLink = document.createElement('a');
          sourceLink.href = link.url;
          sourceLink.textContent = "[".concat(index + 1, "] ").concat(link.url);
          sourceLink.target = '_blank';
          sourceLink.rel = 'noopener noreferrer';
          sourcesList.appendChild(sourceLink);
        });
      }
    } else {
      // Hide the sources tab if no external links are found
      var sourcesTab = document.querySelector('[data-tab="sources"]');
      if (sourcesTab) {
        sourcesTab.style.display = 'none';
      }

      // Also hide the sources tab button if it exists
      var sourcesTabButton = document.querySelector('.tabs__button[data-tab="sources"]');
      if (sourcesTabButton) {
        sourcesTabButton.style.display = 'none';
      }
    }
  }

  // Run external links collection
  collectExternalLinks();

  // Accordion functionality
  function initAccordion() {
    console.log('Accordion - Initializing accordion functionality');

    // Find all accordion headers
    var accordionHeaders = document.querySelectorAll('.accordion__header');
    console.log('Accordion - Accordion headers found:', accordionHeaders.length);

    // Add click event listeners to each accordion header
    accordionHeaders.forEach(function (header, index) {
      console.log("Accordion - Setting up accordion header ".concat(index, ":"), header);
      header.addEventListener('click', function (e) {
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
    var accordion = header.closest('.accordion');
    var body = accordion.querySelector('.accordion__body');
    var arrow = header.querySelector('img');
    if (!accordion || !body) {
      console.log('Accordion - Accordion body not found');
      return;
    }

    // Toggle the open class
    var isOpen = body.classList.contains('accordion__body--open');
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
    var tabContainers = document.querySelectorAll('.tabs');
    tabContainers.forEach(function (container) {
      var tabButtons = container.querySelectorAll('.tabs__button');
      var tabContents = container.querySelectorAll('.tabs__content[data-tab]');
      tabButtons.forEach(function (button) {
        button.addEventListener('click', function () {
          var targetTab = button.getAttribute('data-tab');

          // Remove active class from all buttons and contents
          tabButtons.forEach(function (btn) {
            return btn.classList.remove('tabs__button--active');
          });
          tabContents.forEach(function (content) {
            return content.classList.remove('tabs__content--active');
          });

          // Add active class to clicked button and corresponding content
          button.classList.add('tabs__button--active');
          var targetContent = container.querySelector(".tabs__content[data-tab=\"".concat(targetTab, "\"]"));
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
    var gallerySwiper = document.querySelector('.gallery-swiper');
    var galleryThumbs = document.querySelector('.gallery-thumbs');
    var testsSwiper = document.querySelector('.tests-swiper');

    // Check if any swipers exist
    if (!gallerySwiper && !testsSwiper) {
      console.log('No swipers found, skipping initialization');
      return;
    }

    // Check if Swiper is already loaded
    if (typeof Swiper !== 'undefined') {
      console.log('Swiper already loaded, initializing directly');
      initializeAllSwiperInstances();
    } else {
      console.log('Loading Swiper from CDN...');
      // Load Swiper dynamically
      var script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
      script.onload = function () {
        console.log('Swiper JS loaded successfully');
        // Load Swiper CSS
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
        document.head.appendChild(link);

        // Initialize after CSS is loaded
        setTimeout(function () {
          console.log('Initializing all Swiper instances...');
          initializeAllSwiperInstances();
        }, 100);
      };
      script.onerror = function () {
        console.error('Failed to load Swiper from CDN');
      };
      document.head.appendChild(script);
    }
    function initializeAllSwiperInstances() {
      try {
        // Initialize gallery swiper if it exists
        if (gallerySwiper && galleryThumbs) {
          console.log('Initializing gallery swiper...');

          // Check for images
          var images = gallerySwiper.querySelectorAll('img');
          if (images.length > 0) {
            // Initialize thumbnail swiper
            var thumbsSwiper = new Swiper(galleryThumbs, {
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
            var mainSwiper = new Swiper(gallerySwiper, {
              spaceBetween: 10,
              navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
              },
              thumbs: {
                swiper: thumbsSwiper
              },
              keyboard: {
                enabled: true
              },
              loop: false,
              autoplay: false,
              effect: 'slide',
              speed: 300
            });
            console.log('Gallery Swiper initialized successfully:', {
              mainSwiper: mainSwiper,
              thumbsSwiper: thumbsSwiper
            });

            // Handle grabbing state for better UX
            thumbsSwiper.on('touchStart', function () {
              galleryThumbs.classList.add('swiper-grabbing');
            });
            thumbsSwiper.on('touchEnd', function () {
              galleryThumbs.classList.remove('swiper-grabbing');
            });
          }
        }

        // Initialize tests swiper if it exists
        if (testsSwiper) {
          console.log('Initializing tests swiper...');

          // Calculate dynamic offset based on screen width and container width (including padding)
          var calculateSlidesOffsetAfter = function calculateSlidesOffsetAfter() {
            var screenWidth = window.innerWidth;
            var container = document.querySelector('.tests-section > .container');
            var containerWidth = container.offsetWidth;
            var containerStyle = window.getComputedStyle(container);
            var containerPaddingLeft = parseFloat(containerStyle.paddingLeft);
            var containerPaddingRight = parseFloat(containerStyle.paddingRight);
            var totalContainerWidth = containerWidth - (containerPaddingLeft + containerPaddingRight);
            var offset = (screenWidth - totalContainerWidth) / 2;
            return offset;
          };
          var testsSwiperInstance = new Swiper(testsSwiper, {
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
              releaseOnEdges: true
            },
            // Free mode for more natural dragging
            freeMode: {
              enabled: true,
              sticky: false,
              momentum: true,
              momentumRatio: 0.25,
              momentumVelocityRatio: 0.25
            },
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev'
            },
            keyboard: {
              enabled: true
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
          window.addEventListener('resize', function () {
            testsSwiperInstance.params.slidesOffsetAfter = calculateSlidesOffsetAfter();
            testsSwiperInstance.update();
          });
          console.log('Tests Swiper initialized successfully:', testsSwiperInstance);
        }
      } catch (error) {
        console.error('Error initializing Swipers:', error);
      }
    }
  }
});
/******/ })()
;
//# sourceMappingURL=scripts.js.map