/**
 * EasyCart - Phase 3: Client-Side Interactions Module
 * 
 * This module provides all client-side interactions for the E-commerce website:
 * - Form validations (Login, Signup, Checkout)
 * - Cart interactions (quantity, remove, price recalculation)
 * - Product Detail Page interactions (image switching, quantity controls)
 * - Checkout interactions (shipping option selection)
 * - Product Listing Page enhancements (product count)
 * 
 * Author: E-Commerce Team
 * Version: 1.0.0
 */

const EasyCart = (() => {
    'use strict';

    // ============================================================
    // UTILITIES
    // ============================================================

    /**
     * Clear error messages from a form group
     */
    const clearError = (element) => {
        const formGroup = element.closest('.form-group');
        if (!formGroup) return;
        const errorMsg = formGroup.querySelector('.error-msg');
        if (errorMsg) errorMsg.remove();
        element.classList.remove('input-error');
    };

    /**
     * Show error message for a form field
     */
    const showError = (element, message) => {
        clearError(element);
        const formGroup = element.closest('.form-group');
        if (!formGroup) return;

        element.classList.add('input-error');
        const errorDiv = document.createElement('span');
        errorDiv.className = 'error-msg';
        errorDiv.textContent = message;
        formGroup.appendChild(errorDiv);
    };

    /**
     * Format price with currency symbol
     */
    const formatPrice = (amount) => {
        return '₹' + amount.toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    };

    /**
     * Validate email format
     */
    const isValidEmail = (email) => {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    };

    /**
     * Validate phone number (exactly 10 digits, not all zeros, cannot start with 0)
     */
    const isValidPhone = (phone) => {
        const digits = phone.replace(/\D/g, '');
        // Must be exactly 10 digits, not all zeros, and cannot start with 0
        return digits.length === 10 && digits !== '0000000000' && !digits.startsWith('0');
    };

    /**
     * Validate password strength (min 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char)
     */
    const isValidPassword = (password) => {
        const regex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        return regex.test(password);
    };

    // ============================================================
    // LOGIN FORM VALIDATION
    // ============================================================

    const initLoginValidation = () => {
        const form = document.getElementById('loginForm');
        if (!form) return;

        // Skip if inline script is handling validation
        if (form._hasInlineHandler) return;

        // Real-time validation only
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    showError(this, 'Email is required');
                } else if (!isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });

            emailInput.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    clearError(this);
                }
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('blur', function () {
                if (this.value === '') {
                    showError(this, 'Password is required');
                } else {
                    clearError(this);
                }
            });

            passwordInput.addEventListener('input', function () {
                if (this.value !== '') {
                    clearError(this);
                }
            });
        }
    };

    // ============================================================
    // SIGNUP FORM VALIDATION
    // ============================================================

    const initSignupValidation = () => {
        const form = document.getElementById('signupForm');
        if (!form) return;

        // Skip if inline script is handling validation
        if (form._hasInlineHandler) return;

        const firstInput = document.getElementById('firstname');
        const lastInput = document.getElementById('lastname');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('confirmpassword');
        const termsInput = document.getElementById('terms');

        // Real-time validation for each field
        if (firstInput) {
            firstInput.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    showError(this, 'First name is required');
                } else if (this.value.trim().length < 2) {
                    showError(this, 'First name must be at least 2 characters');
                } else {
                    clearError(this);
                }
            });

            firstInput.addEventListener('input', function () {
                if (this.value.trim().length >= 2) {
                    clearError(this);
                }
            });
        }

        if (lastInput) {
            lastInput.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    showError(this, 'Last name is required');
                } else if (this.value.trim().length < 2) {
                    showError(this, 'Last name must be at least 2 characters');
                } else {
                    clearError(this);
                }
            });

            lastInput.addEventListener('input', function () {
                if (this.value.trim().length >= 2) {
                    clearError(this);
                }
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    showError(this, 'Email is required');
                } else if (!isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });

            emailInput.addEventListener('input', function () {
                if (this.value.trim() !== '' && isValidEmail(this.value)) {
                    clearError(this);
                }
            });
        }

        if (phoneInput) {
            phoneInput.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    showError(this, 'Phone number is required');
                } else if (!isValidPhone(this.value)) {
                    showError(this, 'Please enter a valid phone number (10-13 digits)');
                } else {
                    clearError(this);
                }
            });

            phoneInput.addEventListener('input', function () {
                if (this.value.trim() !== '' && isValidPhone(this.value)) {
                    clearError(this);
                }
            });
        }

        if (passwordInput) {
            passwordInput.addEventListener('blur', function () {
                if (this.value === '') {
                    showError(this, 'Password is required');
                } else if (this.value.length < 6) {
                    showError(this, 'Password must be at least 6 characters');
                } else if (!isValidPassword(this.value)) {
                    showError(this, 'Password must contain uppercase letter and number');
                } else {
                    clearError(this);
                }
            });

            passwordInput.addEventListener('input', function () {
                if (this.value !== '' && this.value.length >= 6 && isValidPassword(this.value)) {
                    clearError(this);
                }
                // Check confirm match in real-time
                if (confirmInput && confirmInput.value !== '') {
                    if (this.value === confirmInput.value) {
                        clearError(confirmInput);
                    }
                }
            });
        }

        if (confirmInput) {
            confirmInput.addEventListener('blur', function () {
                if (this.value === '') {
                    showError(this, 'Please confirm password');
                } else if (this.value !== passwordInput.value) {
                    showError(this, 'Passwords do not match');
                } else {
                    clearError(this);
                }
            });

            confirmInput.addEventListener('input', function () {
                if (this.value === passwordInput.value) {
                    clearError(this);
                }
            });
        }

        // Form submission - handle by inline script only
        // (inline script in signup.php handles localStorage validation)
    };

    // ============================================================
    // CHECKOUT FORM VALIDATION
    // ============================================================

    const initCheckoutValidation = () => {
        const form = document.querySelector('.checkout-form');
        if (!form) return;

        const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');

        // Real-time validation
        inputs.forEach(input => {
            input.addEventListener('blur', function () {
                validateCheckoutField(this);
            });

            input.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    clearError(this);
                }
            });
        });

        // Submit button
        const submitBtn = form.querySelector('.place-order-btn');
        if (submitBtn) {
            submitBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (validateCheckoutForm(form)) {
                    alert('Order placed successfully! (Demo mode - no actual payment)');
                    // In production, submit to payment gateway
                }
            });
        }
    };

    /**
     * Validate individual checkout field
     */
    const validateCheckoutField = (field) => {
        const name = field.getAttribute('name') || field.getAttribute('placeholder') || '';
        const value = field.value.trim();
        let message = '';

        if (value === '') {
            message = name.includes('Email') ? 'Email is required' :
                name.includes('Phone') ? 'Phone number is required' :
                    name.includes('Name') ? 'Name is required' :
                        name.includes('Street') ? 'Address is required' : 'This field is required';
            showError(field, message);
            return false;
        }

        if (name.includes('Email') && !isValidEmail(value)) {
            showError(field, 'Please enter a valid email address');
            return false;
        }

        if (name.includes('Phone') && !isValidPhone(value)) {
            showError(field, 'Please enter a valid phone number (10-13 digits)');
            return false;
        }

        if (name.includes('Name') && value.length < 3) {
            showError(field, 'Name must be at least 3 characters');
            return false;
        }

        clearError(field);
        return true;
    };

    /**
     * Validate entire checkout form
     */
    const validateCheckoutForm = (form) => {
        const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"]');
        let isValid = true;

        inputs.forEach(input => {
            if (!validateCheckoutField(input)) {
                isValid = false;
            }
        });

        return isValid;
    };

    // ============================================================
    // CART PAGE INTERACTIONS
    // ============================================================

    const initCartInteractions = () => {
        const cartItems = document.getElementById('cartItems');
        if (!cartItems) return;

        // Event delegation for quantity controls and remove buttons
        cartItems.addEventListener('click', function (e) {
            // Quantity decrease button (qty-decrease class)
            if (e.target.classList.contains('qty-decrease')) {
                e.preventDefault();
                const form = e.target.closest('form');
                const input = form.querySelector('input[name="quantity"]');
                const newQty = Math.max(1, parseInt(input.value) - 1);
                input.value = newQty;
                form.submit();
            }
            // Quantity increase button (qty-increase class)
            else if (e.target.classList.contains('qty-increase')) {
                e.preventDefault();
                const form = e.target.closest('form');
                const input = form.querySelector('input[name="quantity"]');
                const newQty = parseInt(input.value) + 1;
                input.value = newQty;
                form.submit();
            }
            // Remove button
            else if (e.target.classList.contains('remove-btn')) {
                e.preventDefault();
                if (confirm('Are you sure you want to remove this item?')) {
                    e.target.closest('form').submit();
                }
            }
        });

        // Handle quantity input change directly
        const qtyInputs = cartItems.querySelectorAll('.quantity input[name="quantity"]');
        qtyInputs.forEach(input => {
            input.addEventListener('change', function () {
                const newQty = Math.max(1, parseInt(this.value) || 1);
                this.value = newQty;
                this.closest('form').submit();
            });
        });
    };
    // ============================================================
    // PRODUCT DETAIL PAGE INTERACTIONS
    // ============================================================

    const initProductDetailInteractions = () => {
        const productImage = document.getElementById('productImage');
        if (!productImage) return;

        // Create thumbnail gallery if multiple product images exist
        const thumbnails = document.querySelectorAll('.product-thumbnail');
        if (thumbnails.length > 0) {
            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', function (e) {
                    e.preventDefault();
                    const newImageSrc = this.getAttribute('data-image') || this.src;

                    // Highlight selected thumbnail
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    // Update main image with fade effect
                    productImage.style.opacity = '0.5';
                    setTimeout(() => {
                        productImage.src = newImageSrc;
                        productImage.style.opacity = '1';
                    }, 100);
                });
            });
        }
    };

    // ============================================================
    // CHECKOUT SHIPPING OPTION SELECTION
    // ============================================================

    const initShippingSelection = () => {
        const shippingOptions = document.querySelectorAll('.shipping-option input[type="radio"]');
        if (shippingOptions.length === 0) return;

        shippingOptions.forEach(radio => {
            radio.addEventListener('change', function () {
                // Remove active class from all options
                document.querySelectorAll('.shipping-option').forEach(opt => {
                    opt.classList.remove('active');
                });

                // Add active class to selected option
                this.closest('.shipping-option').classList.add('active');

                // Update order summary if shipping cost changes
                updateOrderSummary();
            });
        });

        // Set initial active state
        const checkedRadio = document.querySelector('.shipping-option input[type="radio"]:checked');
        if (checkedRadio) {
            checkedRadio.closest('.shipping-option').classList.add('active');
        }
    };

    /**
     * Update order summary based on shipping selection
     */
    const updateOrderSummary = () => {
        const selectedShipping = document.querySelector('.shipping-option input[type="radio"]:checked');
        if (!selectedShipping) return;

        // Extract shipping cost from label
        const label = selectedShipping.nextElementSibling;
        let shippingCost = 0;
        const costMatch = label.textContent.match(/₹(\d+)/);
        if (costMatch) {
            shippingCost = parseInt(costMatch[1]);
        }

        // Update shipping display
        const shippingSpan = document.querySelector('.summary-row:has(span:contains("Shipping")) span:last-child');
        if (shippingSpan && shippingCost > 0) {
            shippingSpan.textContent = formatPrice(shippingCost);
        }
    };

    // ============================================================
    // PRODUCT LISTING PAGE ENHANCEMENTS
    // ============================================================

    const initProductListingEnhancements = () => {
        updateProductCount();

        // Observer for dynamic product visibility changes
        const productGrid = document.getElementById('productGrid');
        if (!productGrid) return;

        // Watch for filter changes
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const sortFilter = document.getElementById('sortFilter');

        [searchInput, categoryFilter, sortFilter].forEach(filter => {
            if (filter) {
                filter.addEventListener('change', updateProductCount);
                filter.addEventListener('input', updateProductCount);
            }
        });
    };

    /**
     * Update product count display
     */
    const updateProductCount = () => {
        const productCountEl = document.getElementById('productCount');
        const productGrid = document.getElementById('productGrid');

        if (!productCountEl || !productGrid) return;

        const visibleProducts = productGrid.querySelectorAll('.card:not([style*="display: none"])');
        const count = visibleProducts.length;

        productCountEl.textContent = count;
        productCountEl.style.fontWeight = 'bold';
        productCountEl.style.color = '#4f46e5';
    };

    // ============================================================
    // INITIALIZATION
    // ============================================================

    /**
     * Initialize all modules based on current page
     */
    const init = () => {
        // Add smooth transitions to inputs
        addInputTransitions();

        // Initialize page-specific features
        initLoginValidation();
        initSignupValidation();
        initCheckoutValidation();
        initCartInteractions();
        initProductDetailInteractions();
        initShippingSelection();
        initProductListingEnhancements();
    };

    /**
     * Add CSS transitions to form inputs
     */
    const addInputTransitions = () => {
        const style = document.createElement('style');
        style.textContent = `
            .form-group input,
            .form-group select,
            .form-group textarea {
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
            }

            .input-error {
                border-color: #ef4444 !important;
                background-color: #fef2f2;
            }

            .error-msg {
                display: block;
                color: #ef4444;
                font-size: 0.875rem;
                margin-top: 0.5rem;
                font-weight: 500;
            }

            .shipping-option {
                transition: background-color 0.3s ease, border-color 0.3s ease;
                padding: 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 0.5rem;
                cursor: pointer;
            }

            .shipping-option.active {
                background-color: #f0f9ff;
                border-color: #4f46e5;
            }

            .shipping-option input[type="radio"] {
                cursor: pointer;
            }

            #productImage {
                transition: opacity 0.3s ease;
            }

            .product-thumbnail {
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.2s ease, border-color 0.2s ease;
                border: 2px solid transparent;
            }

            .product-thumbnail.active,
            .product-thumbnail:hover {
                opacity: 1;
                border-color: #4f46e5;
            }
        `;
        document.head.appendChild(style);
    };

    // Public API
    return {
        init: init,
        formatPrice: formatPrice,
        validateCheckoutField: validateCheckoutField
    };
})();

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', EasyCart.init);
} else {
    EasyCart.init();
}
