/******/ (() => { // webpackBootstrap
/*!***********************************************!*\
  !*** ./src/js/components/provider-archive.js ***!
  \***********************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { if (r) i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n;else { var o = function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); }; o("next", 0), o("throw", 1), o("return", 2); } }, _regeneratorDefine2(e, r, n, t); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
document.addEventListener('DOMContentLoaded', function () {
  var searchForm = document.querySelector('.search-form');
  var sortSelect = document.getElementById('sort-by');
  var filterInputs = document.querySelectorAll('.filters-toolbar input, .filters-toolbar select');
  var providerGrid = document.querySelector('.provider-grid');
  var resultsCount = document.querySelector('.archive-meta');
  var currentPage = 1;
  var isLoading = false;
  var hasMorePages = true;

  // Create loading indicator
  var loadingIndicator = document.createElement('div');
  loadingIndicator.className = 'loading-indicator';
  loadingIndicator.innerHTML = '<div class="spinner"></div>';
  loadingIndicator.style.display = 'none';
  providerGrid.parentNode.insertBefore(loadingIndicator, providerGrid.nextSibling);

  // Create error message container
  var errorContainer = document.createElement('div');
  errorContainer.className = 'error-message';
  errorContainer.style.display = 'none';
  providerGrid.parentNode.insertBefore(errorContainer, providerGrid.nextSibling);

  // Function to show/hide loading indicator
  function toggleLoading(show) {
    loadingIndicator.style.display = show ? 'block' : 'none';
    isLoading = show;
  }

  // Function to show error message
  function showError(message) {
    errorContainer.textContent = message;
    errorContainer.style.display = 'block';
    setTimeout(function () {
      errorContainer.style.display = 'none';
    }, 5000);
  }

  // Function to check if a value is a default value
  function isDefaultValue(key, value) {
    // Always consider sorting parameters as default unless explicitly changed
    if (key === 'orderby' || key === 'order') return true;
    if (key === 'post_type' && value === 'provider') return true;
    if (key === 'paged' && value === 1) return true;
    return false;
  }

  // Function to update URL with current filters
  function updateURL(params) {
    var url = new URL(window.location.href);
    var baseUrl = url.pathname;

    // Get only the non-default parameters
    var activeParams = {};
    Object.keys(params).forEach(function (key) {
      var value = params[key];
      // Only add sorting parameters if they are explicitly changed
      if (key === 'orderby' && value !== 'name') {
        activeParams[key] = value;
        activeParams['order'] = value === 'rating' ? 'desc' : 'asc';
      }
      // Add other non-default parameters
      else if (value && !isDefaultValue(key, value)) {
        activeParams[key] = value;
      }
    });

    // If there are no active parameters, just use the base URL
    if (Object.keys(activeParams).length === 0) {
      window.history.pushState({}, '', baseUrl);
      return;
    }

    // Otherwise, add the active parameters to the URL
    var newUrl = new URL(baseUrl, window.location.origin);
    Object.keys(activeParams).forEach(function (key) {
      newUrl.searchParams.set(key, activeParams[key]);
    });
    window.history.pushState({}, '', newUrl);
  }

  // Function to get current filter values
  function getFilterValues() {
    var values = {
      'post_type': 'provider',
      'paged': currentPage
    };

    // Get search form values
    var searchFormData = new FormData(searchForm);
    var _iterator = _createForOfIteratorHelper(searchFormData.entries()),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var _step$value = _slicedToArray(_step.value, 2),
          key = _step$value[0],
          value = _step$value[1];
        if (value) {
          values[key] = value;
        }
      }

      // Get sort value
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    if (sortSelect.value) {
      values['orderby'] = sortSelect.value;
      values['order'] = sortSelect.value === 'rating' ? 'desc' : 'asc';
    }

    // Get filter values
    filterInputs.forEach(function (input) {
      if (input.type === 'checkbox') {
        if (input.checked) values[input.name] = input.value;
      } else if (input.value) {
        values[input.name] = input.value;
      }
    });
    return values;
  }

  // Function to update results count
  function updateResultsCount(count) {
    if (resultsCount) {
      var text = count === 1 ? 'Result' : 'Results';
      resultsCount.textContent = "".concat(count, " ").concat(text);
    }
  }

  // Debounce function
  function debounce(func, wait) {
    var timeout;
    return function executedFunction() {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      var later = function later() {
        clearTimeout(timeout);
        func.apply(void 0, args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Function to load providers
  function loadProviders() {
    return _loadProviders.apply(this, arguments);
  } // Debounced search handler
  function _loadProviders() {
    _loadProviders = _asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
      var values, response, data, _t;
      return _regenerator().w(function (_context) {
        while (1) switch (_context.n) {
          case 0:
            if (!(isLoading || !hasMorePages)) {
              _context.n = 1;
              break;
            }
            return _context.a(2);
          case 1:
            toggleLoading(true);
            values = getFilterValues();
            updateURL(values);
            _context.p = 2;
            _context.n = 3;
            return fetch(pe_mp_ajax.ajax_url, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
              },
              body: new URLSearchParams(_objectSpread({
                action: 'load_providers',
                nonce: pe_mp_ajax.nonce
              }, values))
            });
          case 3:
            response = _context.v;
            _context.n = 4;
            return response.json();
          case 4:
            data = _context.v;
            if (data.success) {
              if (currentPage === 1) {
                providerGrid.innerHTML = data.data.html;
                // Update count only on first page load
                updateResultsCount(data.data.count);
              } else {
                providerGrid.insertAdjacentHTML('beforeend', data.data.html);
              }

              // Check if we have more pages
              hasMorePages = currentPage < data.data.debug.max_num_pages;

              // Observe the last card for infinite scroll
              if (hasMorePages) {
                observeLastCard();
              }
            } else {
              showError('Failed to load providers. Please try again.');
            }
            _context.n = 6;
            break;
          case 5:
            _context.p = 5;
            _t = _context.v;
            console.error('Error loading providers:', _t);
            showError('An error occurred while loading providers. Please try again.');
          case 6:
            toggleLoading(false);
          case 7:
            return _context.a(2);
        }
      }, _callee, null, [[2, 5]]);
    }));
    return _loadProviders.apply(this, arguments);
  }
  var debouncedSearch = debounce(function () {
    currentPage = 1;
    hasMorePages = true;
    loadProviders();
  }, 500);

  // Function to update archive title
  function updateArchiveTitle(categoryName) {
    var titleElement = document.querySelector('.archive-title');
    if (titleElement) {
      if (categoryName && categoryName !== 'All Categories') {
        titleElement.textContent = categoryName;
      } else {
        titleElement.textContent = 'Providers';
      }
    }
  }

  // Event listeners
  if (searchForm) {
    // Remove the default form submission
    searchForm.addEventListener('submit', function (e) {
      e.preventDefault();
    });

    // Add input event listener for search field
    var searchInput = searchForm.querySelector('input[type="text"]');
    if (searchInput) {
      searchInput.addEventListener('input', debouncedSearch);
    }

    // Add change event listener for category select
    var categorySelect = searchForm.querySelector('select[name="provider-type"]');
    if (categorySelect) {
      categorySelect.addEventListener('change', function () {
        var selectedOption = this.options[this.selectedIndex];
        updateArchiveTitle(selectedOption.text);
        currentPage = 1;
        hasMorePages = true;
        loadProviders();
      });
    }
  }
  if (sortSelect) {
    sortSelect.addEventListener('change', function () {
      currentPage = 1;
      hasMorePages = true;
      loadProviders();
    });
  }
  filterInputs.forEach(function (input) {
    input.addEventListener('change', function () {
      currentPage = 1;
      hasMorePages = true;
      loadProviders();
    });
  });

  // Infinite scroll
  var observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting && !isLoading && hasMorePages) {
        currentPage++;
        loadProviders();
      }
    });
  }, {
    rootMargin: '100px'
  });

  // Observe the last provider card for infinite scroll
  function observeLastCard() {
    var cards = providerGrid.querySelectorAll('.provider-card');
    if (cards.length > 0) {
      observer.observe(cards[cards.length - 1]);
    }
  }

  // Initial load
  loadProviders();
});
/******/ })()
;
//# sourceMappingURL=provider-archive.js.map