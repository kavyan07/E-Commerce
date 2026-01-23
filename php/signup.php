<?php
$page_title = 'Signup - EasyCart';
$page_css = 'signup.css';
require_once __DIR__ . '/../includes/header.php';
?>

    <div class="container">
        <div class="signup-container">
            <div class="signup-card">
            <h1>Create Account</h1>

            <div id="message"></div>

            <form id="signupForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" required>
                </div>

                <div class="form-group">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" required>
                </div>

                <div class="terms">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">
                        I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="signup-btn">Create Account</button>
            </form>

                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // IMPORTANT: This script must run AFTER the form submission to override validation
        // localStorage-based signup with form validation
        (function() {
            const form = document.getElementById('signupForm');
            if (!form) return;
            
            // Store reference for later use
            form._hasInlineHandler = true;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const first = document.getElementById('firstname').value.trim();
                const last = document.getElementById('lastname').value.trim();
                const email = document.getElementById('email').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const password = document.getElementById('password').value;
                const confirm = document.getElementById('confirmpassword').value;
                const msgDiv = document.getElementById('message');
                msgDiv.innerHTML = "";

                // Basic validation
                if (!first || !last || !email || !phone || !password || !confirm) {
                    msgDiv.innerHTML = '<p class="error-msg">All fields are required.</p>';
                    return;
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    msgDiv.innerHTML = '<p class="error-msg">Please enter a valid email address.</p>';
                    return;
                }

                // Phone validation (exactly 10 digits, not all zeros, cannot start with 0)
                const phoneDigits = phone.replace(/\D/g, '');
                if (phoneDigits.length !== 10) {
                    msgDiv.innerHTML = '<p class="error-msg">Phone number must be exactly 10 digits.</p>';
                    return;
                }
                if (phoneDigits === '0000000000') {
                    msgDiv.innerHTML = '<p class="error-msg">Phone number cannot be all zeros.</p>';
                    return;
                }
                if (phoneDigits.startsWith('0')) {
                    msgDiv.innerHTML = '<p class="error-msg">Phone number cannot start with 0.</p>';
                    return;
                }

                // Password validation (min 8 chars, 1 uppercase, 1 lowercase, 1 number, 1 special char)
                const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                if (!passwordRegex.test(password)) {
                    msgDiv.innerHTML = '<p class="error-msg">Password must be at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character (@$!%*?&).</p>';
                    return;
                }

                if (password !== confirm) {
                    msgDiv.innerHTML = '<p class="error-msg">Passwords do not match.</p>';
                    return;
                }

                // Read current users from localStorage
                const users = JSON.parse(localStorage.getItem('easycart_users') || '[]');

                // Check if email already exists
                const exists = users.find(u => u.email === email);
                if (exists) {
                    msgDiv.innerHTML = '<p class="error-msg">User with this email already exists. Please login.</p>';
                    return;
                }

                // Add new user
                users.push({
                    firstName: first,
                    lastName: last,
                    email: email,
                    phone: phone,
                    password: password
                });

                localStorage.setItem('easycart_users', JSON.stringify(users));

                msgDiv.innerHTML = '<p class="success-msg">Account created successfully! Redirecting to login...</p>';

                // Redirect to login after delay
                setTimeout(function() {
                    window.location.href = 'login.php';
                }, 1500);
            }, { once: false });
        })();
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

