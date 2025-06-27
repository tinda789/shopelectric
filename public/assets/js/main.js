/**
 * Main JavaScript for ShopElectrics
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Back to top button
    var backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Product quantity input
    document.querySelectorAll('.quantity-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) || 0;
            
            if (this.classList.contains('decrease')) {
                if (value > 1) {
                    input.value = value - 1;
                }
            } else {
                input.value = value + 1;
            }
            
            // Trigger change event
            const event = new Event('change');
            input.dispatchEvent(event);
        });
    });
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantity = document.querySelector(`#quantity-${productId}`)?.value || 1;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang thêm...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Reset button state
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Show success message
                toastr.success('Đã thêm sản phẩm vào giỏ hàng');
                
                // Update cart count
                updateCartCount(1);
                
            }, 800);
        });
    });
    
    // Add to wishlist functionality
    document.querySelectorAll('.add-to-wishlist').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const isActive = this.classList.toggle('active');
            
            if (isActive) {
                this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                toastr.success('Đã thêm vào yêu thích');
            } else {
                this.innerHTML = '<i class="bi bi-heart"></i>';
                toastr.info('Đã xóa khỏi yêu thích');
            }
        });
    });
    
    // Quick view modal
    document.querySelectorAll('.quick-view').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
            
            // Here you would typically fetch product details via AJAX
            // For now, we'll just show the modal
            modal.show();
        });
    });
    
    // Initialize image zoom on product detail page
    if (document.querySelector('.product-image-zoom')) {
        // This would be implemented with a library like Drift or similar
        console.log('Image zoom initialized');
    }
    
    // Initialize product image gallery
    initProductGallery();
    
    // Initialize filters toggle on mobile
    const filterToggle = document.querySelector('.filter-toggle');
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            document.querySelector('.filters-sidebar').classList.toggle('show');
        });
    }
});

/**
 * Update cart count in the header
 * @param {number} change - The change in cart item count
 */
function updateCartCount(change) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(function(element) {
        const currentCount = parseInt(element.textContent) || 0;
        element.textContent = currentCount + change;
        element.style.display = 'flex';
    });
}

/**
 * Initialize product image gallery with thumbnail navigation
 */
function initProductGallery() {
    const mainImage = document.querySelector('.product-main-image');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(function(thumbnail) {
            thumbnail.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Update main image
                mainImage.src = this.href;
                mainImage.alt = this.title;
                
                // Update active thumbnail
                document.querySelector('.product-thumbnail.active')?.classList.remove('active');
                this.classList.add('active');
            });
        });
    }
}

/**
 * Format price with thousand separators
 * @param {number} price - The price to format
 * @returns {string} Formatted price
 */
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(price);
}

/**
 * Initialize range slider for price filter
 */
function initPriceRangeSlider() {
    const rangeSlider = document.getElementById('priceRange');
    if (rangeSlider) {
        noUiSlider.create(rangeSlider, {
            start: [0, 1000000],
            connect: true,
            range: {
                'min': 0,
                'max': 1000000
            },
            tooltips: [true, true],
            format: {
                to: function(value) {
                    return Math.round(value);
                },
                from: function(value) {
                    return Number(value);
                }
            }
        });
        
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        
        rangeSlider.noUiSlider.on('update', function(values, handle) {
            const value = values[handle];
            if (handle) {
                maxPriceInput.value = value;
            } else {
                minPriceInput.value = value;
            }
        });
    }
}

// Initialize price range slider when DOM is loaded
document.addEventListener('DOMContentLoaded', initPriceRangeSlider);
