document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.search-form');
    const sortSelect = document.getElementById('sort-by');
    const filterInputs = document.querySelectorAll('.filters-toolbar input, .filters-toolbar select');
    const providerGrid = document.querySelector('.provider-grid');
    const resultsCount = document.querySelector('.archive-meta');
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;

    // Create loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'loading-indicator';
    loadingIndicator.innerHTML = '<div class="spinner"></div>';
    loadingIndicator.style.display = 'none';
    providerGrid.parentNode.insertBefore(loadingIndicator, providerGrid.nextSibling);

    // Create error message container
    const errorContainer = document.createElement('div');
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
        setTimeout(() => {
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
        const url = new URL(window.location.href);
        const baseUrl = url.pathname;
        
        // Get only the non-default parameters
        const activeParams = {};
        Object.keys(params).forEach(key => {
            const value = params[key];
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
        const newUrl = new URL(baseUrl, window.location.origin);
        Object.keys(activeParams).forEach(key => {
            newUrl.searchParams.set(key, activeParams[key]);
        });

        window.history.pushState({}, '', newUrl);
    }

    // Function to get current filter values
    function getFilterValues() {
        const values = {
            'post_type': 'provider',
            'paged': currentPage
        };

        // Get search form values
        const searchFormData = new FormData(searchForm);
        for (let [key, value] of searchFormData.entries()) {
            if (value) {
                values[key] = value;
            }
        }

        // Get sort value
        if (sortSelect.value) {
            values['orderby'] = sortSelect.value;
            values['order'] = sortSelect.value === 'rating' ? 'desc' : 'asc';
        }

        // Get filter values
        filterInputs.forEach(input => {
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
            const text = count === 1 ? 'Result' : 'Results';
            resultsCount.textContent = `${count} ${text}`;
        }
    }

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Function to load providers
    async function loadProviders() {
        if (isLoading || !hasMorePages) return;
        toggleLoading(true);

        const values = getFilterValues();
        updateURL(values);

        try {
            const response = await fetch(pe_mp_ajax.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'load_providers',
                    nonce: pe_mp_ajax.nonce,
                    ...values
                })
            });

            const data = await response.json();

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
        } catch (error) {
            console.error('Error loading providers:', error);
            showError('An error occurred while loading providers. Please try again.');
        }

        toggleLoading(false);
    }

    // Debounced search handler
    const debouncedSearch = debounce(() => {
        currentPage = 1;
        hasMorePages = true;
        loadProviders();
    }, 500);

    // Function to update archive title
    function updateArchiveTitle(categoryName) {
        const titleElement = document.querySelector('.archive-title');
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
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
        });

        // Add input event listener for search field
        const searchInput = searchForm.querySelector('input[type="text"]');
        if (searchInput) {
            searchInput.addEventListener('input', debouncedSearch);
        }

        // Add change event listener for category select
        const categorySelect = searchForm.querySelector('select[name="provider-type"]');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                updateArchiveTitle(selectedOption.text);
                currentPage = 1;
                hasMorePages = true;
                loadProviders();
            });
        }
    }

    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentPage = 1;
            hasMorePages = true;
            loadProviders();
        });
    }

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            currentPage = 1;
            hasMorePages = true;
            loadProviders();
        });
    });

    // Infinite scroll
    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
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
        const cards = providerGrid.querySelectorAll('.provider-card');
        if (cards.length > 0) {
            observer.observe(cards[cards.length - 1]);
        }
    }

    // Initial load
    loadProviders();
}); 