/******/ (() => { // webpackBootstrap
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { if (r) i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n;else { var o = function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); }; o("next", 0), o("throw", 1), o("return", 2); } }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
// Main JavaScript file
document.addEventListener('DOMContentLoaded', function () {
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
  var tocList = document.querySelector('.hero__toc-list');
  var articleContent = document.querySelector('.article__content');
  if (tocList && articleContent) {
    // Define updateCurrentHeading function
    var updateCurrentHeading = function updateCurrentHeading() {
      var newCurrentHeading = null;

      // Check if we're at the top (Overview)
      if (h2Headings.length === 0) {
        newCurrentHeading = 'overview';
      } else {
        var firstHeading = h2Headings[0];
        var firstChild = articleContent.firstElementChild;

        // Check if we're at the top (Overview)
        if (firstChild) {
          var rect = firstChild.getBoundingClientRect();
          var firstChildTopPosition = rect.top;

          // If first child is above the offset position, we're in overview
          if (firstChildTopPosition > 120) {
            newCurrentHeading = 'overview';
          } else {
            // Find the current heading by checking which one is at the top position
            var foundHeading = false;
            for (var i = h2Headings.length - 1; i >= 0; i--) {
              var heading = h2Headings[i];
              // Skip newsletter headings
              if (heading.textContent.toLowerCase().includes('newsletter')) {
                continue;
              }
              var headingRect = heading.getBoundingClientRect();
              var headingTopPosition = headingRect.top;

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
        tocLinks.forEach(function (link) {
          return link.classList.remove('current');
        });

        // Add current class to the new active heading
        if (newCurrentHeading === 'overview') {
          var _overviewLink = tocList.querySelector('a[href="#article-overview"]');
          if (_overviewLink) {
            _overviewLink.classList.add('current');
            // Scroll TOC to show the overview link
            _overviewLink.scrollIntoView({
              behavior: 'smooth',
              block: 'nearest',
              inline: 'center'
            });
          }
        } else if (newCurrentHeading) {
          var currentLink = tocList.querySelector("a[href=\"#".concat(newCurrentHeading.id, "\"]"));
          if (currentLink) {
            currentLink.classList.add('current');
            // Scroll TOC to show the current link
            currentLink.scrollIntoView({
              behavior: 'smooth',
              block: 'nearest',
              inline: 'center'
            });
          }
        }

        // Update the current active heading
        currentActiveHeading = newCurrentHeading;
      }
    }; // Define throttledScrollHandler function
    var throttledScrollHandler = function throttledScrollHandler() {
      if (scrollTimeout) {
        clearTimeout(scrollTimeout);
      }
      scrollTimeout = setTimeout(updateCurrentHeading, 50);
    }; // Clear existing placeholder content
    var h2Headings = articleContent.querySelectorAll('h2');
    var tocLinks = tocList.querySelectorAll('a');
    var currentActiveHeading = null;
    var scrollTimeout = null;
    tocList.innerHTML = '';

    // Add "Overview" link
    var overviewLink = document.createElement('a');
    overviewLink.href = '#article-overview';
    overviewLink.textContent = 'Overview';
    overviewLink.addEventListener('click', function (e) {
      e.preventDefault();

      // Find the first child element of article content
      var firstChild = articleContent.firstElementChild;
      if (firstChild) {
        // Use scrollIntoView with offset
        firstChild.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });

        // Temporarily disable scroll detection to prevent interference
        window.removeEventListener('scroll', throttledScrollHandler);

        // Re-enable after scroll animation completes
        setTimeout(function () {
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

          // Temporarily disable scroll detection to prevent interference
          window.removeEventListener('scroll', throttledScrollHandler);

          // Re-enable after scroll animation completes
          setTimeout(function () {
            window.addEventListener('scroll', throttledScrollHandler);
            updateCurrentHeading();
          }, 800);
          history.pushState(null, null, "#".concat(heading.id));
        });
        tocList.appendChild(tocLink);
      });
    }

    // Update on scroll with throttling
    window.addEventListener('scroll', throttledScrollHandler);

    // Update tocLinks reference after all links are created
    tocLinks = tocList.querySelectorAll('a');

    // Initial update - make Overview current by default
    setTimeout(function () {
      updateCurrentHeading();
      // Force Overview to be current on page load
      var overviewLink = tocList.querySelector('a[href="#article-overview"]');
      if (overviewLink) {
        tocLinks.forEach(function (link) {
          return link.classList.remove('current');
        });
        overviewLink.classList.add('current');
      }
    }, 100);
  }

  // Mouse wheel scrolling for TOC
  if (tocList) {
    tocList.addEventListener('wheel', function (e) {
      e.preventDefault();
      // Reduce scroll sensitivity for smoother movement
      var scrollAmount = e.deltaY * 3;
      tocList.scrollLeft += scrollAmount;
    });
  }

  // Sticky TOC functionality
  var toc = document.querySelector('.hero__toc');
  if (toc) {
    var tocWrapper = document.querySelector('.hero__toc-wrapper');
    var tocOffset = tocWrapper.offsetTop;
    window.addEventListener('scroll', function () {
      if (window.pageYOffset >= tocOffset) {
        toc.classList.add('sticky');
      } else {
        toc.classList.remove('sticky');
      }
    });
  }

  // Native share functionality
  var shareButtons = document.querySelectorAll('.label--share');
  shareButtons.forEach(function (button) {
    button.addEventListener('click', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
      var _document$querySelect, shareData, _t, _t2;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
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
    var articleContent = document.querySelector('.article__content');
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

    // If we found external links, create the sources section
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
      // Create sources section
      var sourcesSection = document.createElement('section');
      sourcesSection.className = 'article__sources';
      sourcesSection.id = 'article-sources';
      var sourcesTitle = document.createElement('h2');
      sourcesTitle.textContent = 'Sources';
      sourcesSection.appendChild(sourcesTitle);
      var sourcesList = document.createElement('nav');
      externalLinks.forEach(function (link, index) {
        var sourceLink = document.createElement('a');
        sourceLink.href = link.url;
        sourceLink.textContent = "[".concat(index + 1, "] ").concat(link.url);
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
/******/ })()
;
//# sourceMappingURL=scripts.js.map