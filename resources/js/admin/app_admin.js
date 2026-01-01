/**
 * Admin Products Page JavaScript
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initProductsPage();
    });

    function initProductsPage() {
        initViewToggle();
        initCheckboxes();
        initFilters();
        initProductCards();
    }

    /**
     * View Toggle (Grid/List)
     */
    function initViewToggle() {
        const viewToggle = document.getElementById('viewToggle');
        const gridView = document.getElementById('gridView');
        const listView = document.getElementById('listView');
        const viewIcons = document.querySelectorAll('.view-icon');
        
        if (!viewToggle || !gridView || !listView) return;
        
        let currentView = localStorage.getItem('productView') || 'list';
        
        function setView(view) {
            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                if (viewIcons[0]) viewIcons[0].classList.remove('hidden');
                if (viewIcons[1]) viewIcons[1].classList.add('hidden');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                if (viewIcons[0]) viewIcons[0].classList.add('hidden');
                if (viewIcons[1]) viewIcons[1].classList.remove('hidden');
            }
            localStorage.setItem('productView', view);
        }
        
        setView(currentView);
        
        viewToggle.addEventListener('click', function() {
            currentView = currentView === 'grid' ? 'list' : 'grid';
            setView(currentView);
        });
    }

    /**
     * Checkbox Selection
     */
    function initCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const headerCheckbox = document.getElementById('headerCheckbox');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const selectedCount = document.getElementById('selectedCount');
        const bulkActions = document.getElementById('bulkActions');
        
        function updateSelectedCount() {
            if (!selectedCount || !bulkActions) return;
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            if (checked > 0) {
                selectedCount.classList.remove('hidden');
                const countSpan = selectedCount.querySelector('span');
                if (countSpan) countSpan.textContent = checked;
                bulkActions.disabled = false;
            } else {
                selectedCount.classList.add('hidden');
                bulkActions.disabled = true;
            }
        }
        
        if (selectAllCheckbox && headerCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                headerCheckbox.checked = this.checked;
                rowCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedCount();
            });
            
            headerCheckbox.addEventListener('change', function() {
                selectAllCheckbox.checked = this.checked;
                rowCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectedCount();
            });
        }
        
        if (rowCheckboxes.length > 0) {
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const row = this.closest('tr');
                    if (row) {
                        if (this.checked) {
                            row.classList.add('selected');
                        } else {
                            row.classList.remove('selected');
                        }
                    }
                    updateSelectedCount();
                    const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
                    if (selectAllCheckbox) selectAllCheckbox.checked = allChecked;
                    if (headerCheckbox) headerCheckbox.checked = allChecked;
                });
            });
        }
    }

    /**
     * Filters and Search
     */
    function initFilters() {
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const brandFilter = document.getElementById('brandFilter');
        const sortFilter = document.getElementById('sortFilter');
        const activeFilters = document.getElementById('activeFilters');
        
        // Check if we're on products page
        if (!searchInput && !categoryFilter && !brandFilter) {
            return; // Not on products page, skip initialization
        }
        
        // Get route from data attribute or use default
        const routeElement = document.querySelector('[data-products-route]');
        const productsRoute = routeElement ? routeElement.dataset.productsRoute : '/admin/products';
        
        function submitFilters() {
            try {
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = productsRoute;
                
                // Always include search value (even if empty, to preserve other filters)
                if (searchInput) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'search';
                    input.value = searchInput.value || '';
                    form.appendChild(input);
                }
                
                // Category filter
                if (categoryFilter) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'category';
                    input.value = categoryFilter.value || '';
                    form.appendChild(input);
                }
                
                // Brand filter
                if (brandFilter) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'brand';
                    input.value = brandFilter.value || '';
                    form.appendChild(input);
                }
                
                // Sort filter
                if (sortFilter) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'sort';
                    input.value = sortFilter.value || 'newest';
                    form.appendChild(input);
                }
                
                document.body.appendChild(form);
                form.submit();
            } catch (error) {
                console.error('Error submitting filters:', error);
            }
        }
        
        // Search functionality with debounce
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    submitFilters();
                }, 500);
            });
            
            // Also handle Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    submitFilters();
                }
            });
        }
        
        function updateActiveFilters() {
            if (!activeFilters) return;
            
            const filters = [];
            
            // Category filter
            if (categoryFilter && categoryFilter.value) {
                const selectedOption = categoryFilter.options[categoryFilter.selectedIndex];
                if (selectedOption) {
                    // Remove prefix arrows from display text
                    let displayText = selectedOption.text.trim();
                    displayText = displayText.replace(/^\|--+>/, '').trim();
                    filters.push({type: 'category', value: displayText, id: categoryFilter.value});
                }
            }
            
            // Brand filter
            if (brandFilter && brandFilter.value) {
                const selectedOption = brandFilter.options[brandFilter.selectedIndex];
                if (selectedOption) {
                    filters.push({type: 'brand', value: selectedOption.text.trim(), id: brandFilter.value});
                }
            }
            
            if (filters.length > 0) {
                activeFilters.classList.remove('hidden');
                activeFilters.innerHTML = '<span class="text-sm text-gray-600 font-medium">Bộ lọc:</span>';
                filters.forEach(filter => {
                    const badge = document.createElement('span');
                    badge.className = 'px-3 py-1 text-xs font-medium rounded-full bg-primary-100 text-primary-800 flex items-center gap-2';
                    badge.innerHTML = `${filter.value} <button onclick="AdminProducts.removeFilter('${filter.type}')" class="ml-1 hover:text-primary-600 transition-colors"><i class="fas fa-times"></i></button>`;
                    activeFilters.appendChild(badge);
                });
            } else {
                activeFilters.classList.add('hidden');
            }
        }
        
        // Initialize active filters on page load
        updateActiveFilters();
        
        // Category filter change
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                updateActiveFilters();
                submitFilters();
            });
        }
        
        // Brand filter change
        if (brandFilter) {
            brandFilter.addEventListener('change', function() {
                updateActiveFilters();
                submitFilters();
            });
        }
        
        // Sort filter change
        if (sortFilter) {
            sortFilter.addEventListener('change', function() {
                submitFilters();
            });
        }
        
        // Store references for removeFilter function
        window.AdminProducts = window.AdminProducts || {};
        window.AdminProducts.removeFilter = function(type) {
            if (type === 'category' && categoryFilter) {
                categoryFilter.value = '';
                updateActiveFilters();
                submitFilters();
            }
            if (type === 'brand' && brandFilter) {
                brandFilter.value = '';
                updateActiveFilters();
                submitFilters();
            }
        };
        
        // Store filter elements for global access (for debugging)
        window.AdminProducts.categoryFilter = categoryFilter;
        window.AdminProducts.brandFilter = brandFilter;
        window.AdminProducts.updateActiveFilters = updateActiveFilters;
        window.AdminProducts.submitFilters = submitFilters;
    }

    /**
     * Product Cards Animation
     */
    function initProductCards() {
        const cards = document.querySelectorAll('.product-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.05}s`;
        });
    }

    /**
     * Delete Confirmation
     * Note: confirmDelete function is defined in delete-modal.blade.php component
     * This ensures the custom modal is used instead of browser's default confirm()
     */
})();

