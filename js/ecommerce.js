/**
 * EasyCart - E-commerce Phase 3 Interactions
 * Complete client-side functionality with form validation, cart interactions, and dynamic updates
 */

document.addEventListener('DOMContentLoaded', function () {
    initializeApp();
});

function initializeApp() {
    // Initialize all modules
    initFormValidations();
    initCartInteractions();
    initProductGallery();
    initShippingOptions();
    initProductListingEnhancements();
}

// ============================================
// FORM VALIDATIONS WITH SUBMISSION PREVENTION
// ============================================

function initFormValidations() {
    validateLoginForm();
    validateSignupForm();
    validateCheckoutForm();
}

// ===== LOGIN FORM VALIDATION =====
function validateLoginForm() {
    const form = document.getElementById('loginForm');
    if (!form) return;

    // Skip if inline script is handling validation
    if (form._hasInlineHandler) {
        // Still add real-time validation feedback
        addLoginRealTimeValidation(form);
        return;
    }

    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');

    // Real-time validation
    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            validateEmailField(this);
        });
        emailInput.addEventListener('input', function () {
            if (this.value.trim() !== '') {
                clearInputError(this);
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            validatePasswordField(this);
        });
        passwordInput.addEventListener('input', function () {
            if (this.value !== '') {
                clearInputError(this);
            }
        });
    }

    // Prevent form submission if validation fails
    form.addEventListener('submit', function (e) {
        let isValid = true;

        if (emailInput && !validateEmailField(emailInput)) {
            isValid = false;
        }

        if (passwordInput && !validatePasswordField(passwordInput)) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            showFormError(form, 'Please fix the errors before submitting.');
        }
    });
}

function addLoginRealTimeValidation(form) {
    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            validateEmailField(this);
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            validatePasswordField(this);
        });
    }
}

// ===== SIGNUP FORM VALIDATION =====
function validateSignupForm() {
    const form = document.getElementById('signupForm');
    if (!form) return;

    // Skip if inline script is handling validation
    if (form._hasInlineHandler) {
        // Still add real-time validation feedback
        addSignupRealTimeValidation(form);
        return;
    }

    const firstnameInput = form.querySelector('#firstname');
    const lastnameInput = form.querySelector('#lastname');
    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');
    const confirmInput = form.querySelector('#confirmpassword');

    // Real-time validation for each field
    if (firstnameInput) {
        firstnameInput.addEventListener('blur', function () {
            validateNameField(this, 'First name');
        });
    }

    if (lastnameInput) {
        lastnameInput.addEventListener('blur', function () {
            validateNameField(this, 'Last name');
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            validateEmailField(this);
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            validatePasswordField(this);
        });
        passwordInput.addEventListener('input', function () {
            // Check confirm password match in real-time
            if (confirmInput && confirmInput.value !== '') {
                validatePasswordMatch(confirmInput, passwordInput);
            }
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('blur', function () {
            validatePasswordMatch(this, passwordInput);
        });
        confirmInput.addEventListener('input', function () {
            if (passwordInput && passwordInput.value !== '') {
                validatePasswordMatch(this, passwordInput);
            }
        });
    }

    // Prevent form submission if validation fails
    form.addEventListener('submit', function (e) {
        let isValid = true;

        if (firstnameInput && !validateNameField(firstnameInput, 'First name')) {
            isValid = false;
        }

        if (lastnameInput && !validateNameField(lastnameInput, 'Last name')) {
            isValid = false;
        }

        if (emailInput && !validateEmailField(emailInput)) {
            isValid = false;
        }

        if (passwordInput && !validatePasswordField(passwordInput)) {
            isValid = false;
        }

        if (confirmInput && !validatePasswordMatch(confirmInput, passwordInput)) {
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            showFormError(form, 'Please fix the errors before submitting.');
        }
    });
}

function addSignupRealTimeValidation(form) {
    const firstnameInput = form.querySelector('#firstname');
    const lastnameInput = form.querySelector('#lastname');
    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');
    const confirmInput = form.querySelector('#confirmpassword');

    if (firstnameInput) {
        firstnameInput.addEventListener('blur', function () {
            validateNameField(this, 'First name');
        });
    }

    if (lastnameInput) {
        lastnameInput.addEventListener('blur', function () {
            validateNameField(this, 'Last name');
        });
    }

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            validateEmailField(this);
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('blur', function () {
            validatePasswordField(this);
        });
    }

    if (confirmInput) {
        confirmInput.addEventListener('blur', function () {
            validatePasswordMatch(this, passwordInput);
        });
    }
}

// ===== CHECKOUT FORM VALIDATION =====
function validateCheckoutForm() {
    const form = document.querySelector('.checkout-form');
    if (!form) return;

    const requiredFields = [
        { id: 'firstName', name: 'First Name', type: 'text' },
        { id: 'lastName', name: 'Last Name', type: 'text' },
        { id: 'email', name: 'Email', type: 'email' },
        { id: 'phone', name: 'Phone Number', type: 'tel' },
        { id: 'street', name: 'Street Address', type: 'text' },
        { id: 'city', name: 'City', type: 'text' },
        { id: 'state', name: 'State', type: 'text' },
        { id: 'zip', name: 'Postal Code', type: 'text' }
    ];

    // Add real-time validation
    requiredFields.forEach(field => {
        const input = form.querySelector('#' + field.id);
        if (input) {
            input.addEventListener('blur', function () {
                validateCheckoutField(this, field.name, field.type);
            });
            input.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    clearInputError(this);
                }
            });
        }
    });

    // Prevent form submission if validation fails
    form.addEventListener('submit', function (e) {
        let isValid = true;

        requiredFields.forEach(field => {
            const input = form.querySelector('#' + field.id);
            if (input && !validateCheckoutField(input, field.name, field.type)) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            showFormError(form, 'Please fill all required fields correctly.');
        }
    });
}

// ===== VALIDATION HELPER FUNCTIONS =====
function validateEmailField(input) {
    const value = input.value.trim();
    if (!value) {
        showInputError(input, 'Email is required');
        return false;
    }
    if (!isValidEmail(value)) {
        showInputError(input, 'Please enter a valid email address');
        return false;
    }
    clearInputError(input);
    return true;
}

function validatePasswordField(input) {
    const value = input.value;
    if (!value) {
        showInputError(input, 'Password is required');
        return false;
    }
    if (value.length < 6) {
        showInputError(input, 'Password must be at least 6 characters');
        return false;
    }
    clearInputError(input);
    return true;
}

function validateNameField(input, fieldName) {
    const value = input.value.trim();
    if (!value) {
        showInputError(input, fieldName + ' is required');
        return false;
    }
    if (value.length < 2) {
        showInputError(input, fieldName + ' must be at least 2 characters');
        return false;
    }
    clearInputError(input);
    return true;
}

function validatePasswordMatch(confirmInput, passwordInput) {
    const confirmValue = confirmInput.value;
    const passwordValue = passwordInput.value;

    if (!confirmValue) {
        showInputError(confirmInput, 'Please confirm your password');
        return false;
    }
    if (confirmValue !== passwordValue) {
        showInputError(confirmInput, 'Passwords do not match');
        return false;
    }
    clearInputError(confirmInput);
    return true;
}

function validateCheckoutField(input, fieldName, fieldType) {
    const value = input.value.trim();
    
    if (!value) {
        showInputError(input, fieldName + ' is required');
        return false;
    }

    if (fieldType === 'email' && !isValidEmail(value)) {
        showInputError(input, 'Please enter a valid email address');
        return false;
    }

    if (fieldType === 'tel' && !isValidPhone(value)) {
        showInputError(input, 'Please enter a valid phone number (10-13 digits)');
        return false;
    }

    if (fieldType === 'text' && value.length < 3 && fieldName !== 'Postal Code') {
        showInputError(input, fieldName + ' must be at least 3 characters');
        return false;
    }

    if (fieldName === 'Postal Code' && !/^\d{5,6}$/.test(value)) {
        showInputError(input, 'Please enter a valid postal code (5-6 digits)');
        return false;
    }

    clearInputError(input);
    return true;
}

// ===== UTILITY FUNCTIONS =====
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPhone(phone) {
    const digits = phone.replace(/\D/g, '');
    return digits.length >= 10 && digits.length <= 13;
}

function showInputError(input, message) {
    clearInputError(input);
    input.classList.add('input-error');
    input.style.borderColor = '#d32f2f';

    const errorMsg = document.createElement('span');
    errorMsg.className = 'field-error-msg';
    errorMsg.textContent = message;
    errorMsg.style.color = '#d32f2f';
    errorMsg.style.fontSize = '0.85rem';
    errorMsg.style.display = 'block';
    errorMsg.style.marginTop = '0.4rem';

    const formGroup = input.closest('.form-group');
    if (formGroup) {
        formGroup.appendChild(errorMsg);
    } else {
        input.parentNode.insertBefore(errorMsg, input.nextSibling);
    }
}

function clearInputError(input) {
    input.classList.remove('input-error');
    input.style.borderColor = '';

    const formGroup = input.closest('.form-group');
    const errorMsg = formGroup 
        ? formGroup.querySelector('.field-error-msg')
        : input.nextElementSibling;

    if (errorMsg && errorMsg.classList.contains('field-error-msg')) {
        errorMsg.remove();
    }
}

function showFormError(form, message) {
    let errorDiv = form.querySelector('.form-error-msg');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'form-error-msg';
        errorDiv.style.color = '#d32f2f';
        errorDiv.style.backgroundColor = '#ffebee';
        errorDiv.style.padding = '1rem';
        errorDiv.style.borderRadius = '4px';
        errorDiv.style.marginBottom = '1rem';
        form.insertBefore(errorDiv, form.firstChild);
    }
    errorDiv.textContent = message;
}

// ============================================
// CART PAGE INTERACTIONS WITH DYNAMIC UPDATES
// ============================================

function initCartInteractions() {
    const cartItems = document.getElementById('cartItems');
    if (!cartItems) return;

    // Event delegation for quantity controls and remove buttons
    cartItems.addEventListener('click', function (e) {
        // Quantity decrease button
        if (e.target.classList.contains('qty-decrease')) {
            e.preventDefault();
            const form = e.target.closest('.quantity-form');
            const input = form.querySelector('input[name="quantity"]');
            const currentQty = parseInt(input.value) || 1;
            
            if (currentQty > 1) {
                const newQty = currentQty - 1;
                input.value = newQty;
                updateCartItemPrice(form, newQty);
                updateCartTotals();
                // Submit to update server-side for persistence
                form.submit();
            }
        }
        // Quantity increase button
        else if (e.target.classList.contains('qty-increase')) {
            e.preventDefault();
            const form = e.target.closest('.quantity-form');
            const input = form.querySelector('input[name="quantity"]');
            const currentQty = parseInt(input.value) || 1;
            const newQty = currentQty + 1;
            
            input.value = newQty;
            updateCartItemPrice(form, newQty);
            updateCartTotals();
            // Submit to update server-side for persistence
            form.submit();
        }
        // Remove button
        else if (e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this item from cart?')) {
                const form = e.target.closest('form');
                const cartItem = form.closest('.cart-item');
                
                // Remove from UI immediately
                cartItem.style.transition = 'opacity 0.3s ease';
                cartItem.style.opacity = '0';
                
                setTimeout(() => {
                    cartItem.remove();
                    updateCartTotals();
                    
                    // Check if cart is empty
                    const remainingItems = cartItems.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        location.reload(); // Reload to show empty cart message
                    }
                }, 300);
                
                // Submit to remove from server-side
                form.submit();
            }
        }
    });

    // Handle direct quantity input changes
    const qtyInputs = cartItems.querySelectorAll('.quantity input[name="quantity"]');
    qtyInputs.forEach(input => {
        input.addEventListener('change', function () {
            const newQty = Math.max(1, parseInt(this.value) || 1);
            this.value = newQty;
            const form = this.closest('.quantity-form');
            updateCartItemPrice(form, newQty);
            updateCartTotals();
        });
    });

    // Initialize cart totals on page load
    updateCartTotals();
}

function updateCartItemPrice(form, quantity) {
    const cartItem = form.closest('.cart-item');
    const itemPriceEl = cartItem.querySelector('.item-price');
    const itemTotalEl = cartItem.querySelector('.item-total');
    
    if (!itemPriceEl || !itemTotalEl) return;

    // Get price from data attribute or extract from text
    let pricePerItem = parseFloat(itemPriceEl.getAttribute('data-price'));
    
    if (!pricePerItem || isNaN(pricePerItem)) {
        // Fallback: extract from text
        const priceText = itemPriceEl.textContent;
        const priceMatch = priceText.match(/[\d,]+/);
        if (priceMatch) {
            pricePerItem = parseInt(priceMatch[0].replace(/,/g, ''));
        } else {
            return;
        }
    }

    const newTotal = pricePerItem * quantity;

    // Update item total display and data attribute
    const formattedTotal = formatPrice(newTotal);
    itemTotalEl.textContent = formattedTotal;
    itemTotalEl.setAttribute('data-total', newTotal);
}

function updateCartTotals() {
    const cartItems = document.querySelectorAll('.cart-item');
    let subtotal = 0;

    // Calculate subtotal from all items
    cartItems.forEach(item => {
        const itemTotalEl = item.querySelector('.item-total');
        if (itemTotalEl) {
            // Try to get from data attribute first
            let itemTotal = parseFloat(itemTotalEl.getAttribute('data-total'));
            
            if (!itemTotal || isNaN(itemTotal)) {
                // Fallback: extract from text
                const totalText = itemTotalEl.textContent;
                const totalMatch = totalText.match(/[\d,]+/);
                if (totalMatch) {
                    itemTotal = parseInt(totalMatch[0].replace(/,/g, ''));
                } else {
                    itemTotal = 0;
                }
            }
            
            subtotal += itemTotal;
        }
    });

    // Calculate tax and shipping
    const tax = subtotal * 0.18;
    const shipping = subtotal > 0 ? 100 : 0;
    const total = subtotal + tax + shipping;

    // Update summary display using IDs if available, otherwise use selectors
    const subtotalEl = document.getElementById('cartSubtotal') || 
                      document.querySelector('.summary-row:nth-of-type(1) span:last-child');
    const taxEl = document.getElementById('cartTax') || 
                  document.querySelector('.summary-row:nth-of-type(2) span:last-child');
    const shippingEl = document.getElementById('cartShipping') || 
                      document.querySelector('.summary-row:nth-of-type(3) span:last-child');
    const totalEl = document.getElementById('cartTotal') || 
                    document.querySelector('.summary-row.total span:last-child');

    if (subtotalEl) {
        subtotalEl.textContent = formatPrice(subtotal);
        if (subtotalEl.hasAttribute('data-subtotal')) {
            subtotalEl.setAttribute('data-subtotal', subtotal);
        }
    }
    if (taxEl) {
        taxEl.textContent = formatPrice(tax);
        if (taxEl.hasAttribute('data-tax')) {
            taxEl.setAttribute('data-tax', tax);
        }
    }
    if (shippingEl) {
        shippingEl.textContent = formatPrice(shipping);
        if (shippingEl.hasAttribute('data-shipping')) {
            shippingEl.setAttribute('data-shipping', shipping);
        }
    }
    if (totalEl) {
        totalEl.textContent = formatPrice(total);
        if (totalEl.hasAttribute('data-total')) {
            totalEl.setAttribute('data-total', total);
        }
    }
}

function formatPrice(amount) {
    return 'Rs. ' + amount.toLocaleString('en-IN', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}

// ============================================
// PRODUCT DETAIL PAGE - IMAGE SWITCHING
// ============================================

function initProductGallery() {
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    const mainImage = document.getElementById('productImage');

    if (!mainImage || thumbnails.length === 0) return;

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function (e) {
            e.preventDefault();
            const newImageUrl = this.getAttribute('data-image') || this.src;

            // Fade transition
            mainImage.style.opacity = '0.5';
            mainImage.style.transition = 'opacity 0.3s ease-in-out';

            setTimeout(() => {
                mainImage.src = newImageUrl;
                mainImage.style.opacity = '1';
            }, 150);

            // Update active state
            thumbnails.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
}

// ============================================
// SHIPPING OPTION SELECTION
// ============================================

function initShippingOptions() {
    const shippingRadios = document.querySelectorAll('input[name="shipping"]');

    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            // Visual highlight selected option
            document.querySelectorAll('.shipping-option').forEach(opt => {
                opt.classList.remove('selected', 'active');
            });

            const parent = this.closest('.shipping-option');
            if (parent) {
                parent.classList.add('selected', 'active');
            }

            // Update total if cost changes
            updateOrderTotal();
        });
    });

    // Set initial active state
    const checkedRadio = document.querySelector('input[name="shipping"]:checked');
    if (checkedRadio) {
        const parent = checkedRadio.closest('.shipping-option');
        if (parent) {
            parent.classList.add('selected', 'active');
        }
        updateOrderTotal();
    }
}

function updateOrderTotal() {
    const selectedRadio = document.querySelector('input[name="shipping"]:checked');
    if (!selectedRadio) return;

    const totalEl = document.getElementById('totalAmount');
    const shippingEl = document.getElementById('shippingCost');

    if (!totalEl || !shippingEl) return;

    // Get shipping cost from radio value
    const shippingCost = parseInt(selectedRadio.value) || 0;

    // Get subtotal and tax from existing summary
    const subtotalRow = document.querySelector('.summary-row');
    const taxRow = document.querySelectorAll('.summary-row')[1];
    
    let subtotal = 0;
    let tax = 0;

    if (subtotalRow) {
        const subtotalText = subtotalRow.querySelector('span:last-child').textContent;
        const subtotalMatch = subtotalText.match(/[\d,]+/);
        if (subtotalMatch) {
            subtotal = parseInt(subtotalMatch[0].replace(/,/g, ''));
        }
    }

    if (taxRow) {
        const taxText = taxRow.querySelector('span:last-child').textContent;
        const taxMatch = taxText.match(/[\d,]+/);
        if (taxMatch) {
            tax = parseInt(taxMatch[0].replace(/,/g, ''));
        }
    }

    const total = subtotal + tax + shippingCost;

    shippingEl.textContent = formatPrice(shippingCost);
    totalEl.textContent = formatPrice(total);
}

// ============================================
// PRODUCT LISTING PAGE - PRODUCT COUNT
// ============================================

function initProductListingEnhancements() {
    updateProductCount();

    // Watch for filter changes
    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const sortFilter = document.getElementById('sortFilter');
    const productGrid = document.getElementById('productGrid');

    if (!productGrid) return;

    // Observer for dynamic product visibility changes
    const observer = new MutationObserver(function () {
        updateProductCount();
    });

    observer.observe(productGrid, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style']
    });

    // Event listeners for filters
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            setTimeout(updateProductCount, 100);
        });
    }

    if (categoryFilter) {
        categoryFilter.addEventListener('change', function () {
            setTimeout(updateProductCount, 100);
        });
    }

    if (sortFilter) {
        sortFilter.addEventListener('change', function () {
            setTimeout(updateProductCount, 100);
        });
    }
}

function updateProductCount() {
    const productCountEl = document.getElementById('productCount');
    const productGrid = document.getElementById('productGrid');

    if (!productCountEl || !productGrid) return;

    // Count visible products (not hidden by display:none)
    const allProducts = productGrid.querySelectorAll('.card');
    let visibleCount = 0;

    allProducts.forEach(product => {
        const style = window.getComputedStyle(product);
        if (style.display !== 'none' && style.visibility !== 'hidden') {
            visibleCount++;
        }
    });

    productCountEl.textContent = visibleCount;
}

// ============================================
// ADD CSS TRANSITIONS
// ============================================

(function addStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .form-group input,
        .form-group select,
        .form-group textarea {
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .input-error {
            border-color: #d32f2f !important;
            background-color: #ffebee !important;
        }

        .field-error-msg {
            display: block;
            color: #d32f2f;
            font-size: 0.85rem;
            margin-top: 0.4rem;
            font-weight: 500;
        }

        .form-error-msg {
            display: block;
            color: #d32f2f;
            background-color: #ffebee;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }

        .shipping-option {
            transition: background-color 0.3s ease, border-color 0.3s ease;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        .shipping-option.selected,
        .shipping-option.active {
            background-color: rgba(247, 37, 133, 0.1);
            border-color: #f72585;
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
            border-color: #f72585;
        }

        .cart-item {
            transition: opacity 0.3s ease;
        }
    `;
    document.head.appendChild(style);
})();
