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
    initAddToCartAjax();
    initProductGallery();
    initShippingOptions();
    initProductListingEnhancements();
}

// ============================================
// AJAX HELPERS (SESSION-BASED)
// ============================================

async function postJson(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    const json = await res.json().catch(() => null);
    if (!res.ok || !json) {
        throw new Error((json && json.message) ? json.message : 'Server error');
    }
    return json;
}

function setCartBadge(count) {
    const badge = document.getElementById('cartCount');
    if (!badge) return;
    const n = parseInt(count, 10) || 0;
    if (n <= 0) {
        badge.remove();
        return;
    }
    badge.textContent = String(n);
}

function formatPriceFromInt(amount) {
    const n = parseInt(amount, 10) || 0;
    return formatPrice(n);
}

function setLoading(el, isLoading) {
    if (!el) return;
    if (isLoading) {
        el.dataset._oldText = el.textContent;
        el.textContent = '...';
        el.disabled = true;
    } else {
        if (el.dataset._oldText) el.textContent = el.dataset._oldText;
        el.disabled = false;
    }
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
    const phoneInput = form.querySelector('#phone');
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

    if (phoneInput) {
        // Restrict to digits and max 10 on input
        phoneInput.addEventListener('input', function () {
            let digits = this.value.replace(/\D/g, '');
            if (digits.length > 10) {
                digits = digits.slice(0, 10);
            }
            this.value = digits;
        });

        phoneInput.addEventListener('blur', function () {
            validatePhoneField(this);
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

        if (phoneInput && !validatePhoneField(phoneInput)) {
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

    // Password strength indicator for signup form
    const strengthEl = document.getElementById('passwordStrength');
    const hintEl = document.getElementById('passwordHint');
    if (strengthEl) {
        const hasUpper = /[A-Z]/.test(value);
        const hasNumber = /\d/.test(value);
        const hasSpecial = /[@$!%*?&]/.test(value);
        let score = 0;
        if (hasUpper) score++;
        if (hasNumber) score++;
        if (hasSpecial) score++;

        let strengthText = 'Weak';
        let strengthColor = '#ef4444';

        if (value.length >= 8 && score === 3) {
            strengthText = 'Strong';
            strengthColor = '#22c55e';
        } else if (value.length >= 6 && score >= 2) {
            strengthText = 'Medium';
            strengthColor = '#f97316';
        }

        strengthEl.textContent = 'Password strength: ' + strengthText;
        strengthEl.style.color = strengthColor;

        if (hintEl) {
            hintEl.textContent = 'Add uppercase, number, and special character to make password strong';
        }
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
    if (!/^[A-Za-z ]+$/.test(value)) {
        showInputError(input, 'Special characters are not allowed in name');
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

// Generic phone validator (used on checkout)
function isValidPhone(phone) {
    const digits = phone.replace(/\D/g, '');
    // For checkout we allow 10–13 digits
    return digits.length >= 10 && digits.length <= 13;
}

// Strict 10-digit validator for signup form
function validatePhoneField(input) {
    const digits = input.value.replace(/\D/g, '');
    if (digits.length !== 10) {
        showInputError(input, 'Phone number must be exactly 10 digits');
        return false;
    }
    clearInputError(input);
    return true;
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

    const cartApiUrl = 'ajax-cart.php';

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

                const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10) || 0;
                setLoading(e.target, true);
                postJson(cartApiUrl, { action: 'update', product_id: productId, quantity: newQty })
                    .then((json) => {
                        applyCartSummary(json.summary);
                        setCartBadge(json.summary.cartCount);
                    })
                    .catch((err) => {
                        showToast(err.message || 'Failed to update cart', 'error');
                    })
                    .finally(() => setLoading(e.target, false));
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

            const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10) || 0;
            setLoading(e.target, true);
            postJson(cartApiUrl, { action: 'update', product_id: productId, quantity: newQty })
                .then((json) => {
                    applyCartSummary(json.summary);
                    setCartBadge(json.summary.cartCount);
                })
                .catch((err) => {
                    showToast(err.message || 'Failed to update cart', 'error');
                })
                .finally(() => setLoading(e.target, false));
        }
        // Remove button
        else if (e.target.classList.contains('remove-btn')) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this item from cart?')) {
                const form = e.target.closest('form');
                const cartItem = form.closest('.cart-item');
                const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10) || 0;

                // Remove from UI immediately
                cartItem.style.transition = 'opacity 0.3s ease';
                cartItem.style.opacity = '0';

                setTimeout(() => {
                    cartItem.remove();
                    updateCartTotals();

                    // Check if cart is empty
                    const remainingItems = cartItems.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        renderEmptyCartState();
                    }
                }, 300);

                setLoading(e.target, true);
                postJson(cartApiUrl, { action: 'remove', product_id: productId })
                    .then((json) => {
                        applyCartSummary(json.summary);
                        setCartBadge(json.summary.cartCount);
                    })
                    .catch((err) => {
                        showToast(err.message || 'Failed to remove item', 'error');
                    })
                    .finally(() => setLoading(e.target, false));
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

function applyCartSummary(summary) {
    if (!summary) return;
    const subtotalEl = document.getElementById('cartSubtotal');
    const taxEl = document.getElementById('cartTax');
    const shippingEl = document.getElementById('cartShipping');
    const totalEl = document.getElementById('cartTotal');

    if (subtotalEl) subtotalEl.textContent = formatPriceFromInt(summary.subtotal);
    if (taxEl) taxEl.textContent = formatPriceFromInt(summary.tax);
    if (shippingEl) shippingEl.textContent = formatPriceFromInt(summary.shipping);
    if (totalEl) totalEl.textContent = formatPriceFromInt(summary.total);
}

function renderEmptyCartState() {
    const container = document.querySelector('.cart-section');
    const summaryWrapper = document.querySelector('.cart-summary-wrapper');
    if (summaryWrapper) summaryWrapper.remove();
    if (!container) return;
    container.innerHTML = `
        <div class="empty-cart">
            <h2>Your cart is empty</h2>
            <p>Start shopping to add items to your cart</p>
            <a href="product-listing.php" class="checkout-btn browse-btn">Browse Products</a>
        </div>
    `;
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

    // Calculate tax and shipping (cart page uses default Standard Shipping cost)
    const tax = subtotal * 0.18;
    const shipping = subtotal > 0 ? 350 : 0;
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
// ADD TO CART (AJAX) – PDP/PLP FORMS
// ============================================

function initAddToCartAjax() {
    const forms = document.querySelectorAll('form.add-to-cart-form');
    if (!forms || forms.length === 0) return;

    const cartApiUrl = 'ajax-cart.php';

    forms.forEach((form) => {
        form.addEventListener('submit', function (e) {
            // allow fallback if fetch not available
            if (!window.fetch) return;
            e.preventDefault();

            const productId = parseInt(form.querySelector('input[name="product_id"]').value, 10) || 0;
            const qtyInput = form.querySelector('input[name="quantity"]');
            const qty = qtyInput ? (parseInt(qtyInput.value, 10) || 1) : 1;
            const btn = form.querySelector('button[type="submit"]');

            setLoading(btn, true);
            postJson(cartApiUrl, { action: 'add', product_id: productId, quantity: qty })
                .then((json) => {
                    setCartBadge(json.summary.cartCount);
                    showToast('Added to cart', 'success');
                })
                .catch((err) => {
                    showToast(err.message || 'Failed to add to cart', 'error');
                })
                .finally(() => setLoading(btn, false));
        });
    });
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

    console.log('Initializing shipping options, found:', shippingRadios.length, 'radio buttons');

    shippingRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            console.log('Shipping option changed to:', this.value);

            // Visual highlight selected option
            document.querySelectorAll('.shipping-option').forEach(opt => {
                opt.classList.remove('selected', 'active');
            });

            const parent = this.closest('.shipping-option');
            if (parent) {
                parent.classList.add('selected', 'active');
            }

            // Update totals dynamically via server (AJAX)
            updateOrderTotalAjax();
        });
    });

    // Set initial active state
    const checkedRadio = document.querySelector('input[name="shipping"]:checked');
    if (checkedRadio) {
        console.log('Initial shipping option checked:', checkedRadio.value);
        const parent = checkedRadio.closest('.shipping-option');
        if (parent) {
            parent.classList.add('selected', 'active');
        }
        updateOrderTotalAjax();
    } else {
        console.log('No shipping option pre-selected');
    }
}

function updateOrderTotalAjax() {
    // Only runs on checkout page (needs these ids)
    const totalEl = document.getElementById('totalAmount');
    const shippingEl = document.getElementById('shippingCost');
    const taxEl = document.getElementById('taxAmount');
    if (!totalEl || !shippingEl) {
        console.log('Checkout elements not found');
        return;
    }

    const selectedRadio = document.querySelector('input[name="shipping"]:checked');
    if (!selectedRadio) {
        console.log('No shipping option selected');
        return;
    }
    const method = selectedRadio.value || 'standard';

    console.log('Updating shipping method to:', method);

    postJson('ajax-checkout.php', { shipping: method })
        .then((json) => {
            console.log('Received shipping update response:', json);
            const s = json.summary;
            shippingEl.textContent = formatPriceFromInt(s.shipping);
            if (taxEl) taxEl.textContent = formatPriceFromInt(s.tax);
            totalEl.textContent = formatPriceFromInt(s.total);
        })
        .catch((err) => {
            console.error('AJAX Error updating shipping:', err);
            // Fallback if showToast is not available
            if (typeof showToast === 'function') {
                showToast(err.message || 'Failed to update shipping', 'error');
            } else {
                alert(err.message || 'Failed to update shipping');
            }
        });
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
