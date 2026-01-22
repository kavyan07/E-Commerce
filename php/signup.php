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
        // Simple localStorage-based signup
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const first = document.getElementById('firstname').value.trim();
            const last = document.getElementById('lastname').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmpassword').value;
            const msgDiv = document.getElementById('message');
            msgDiv.innerHTML = "";

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

            // Redirect to login
            setTimeout(function() {
                window.location.href = 'login.php';
            }, 1500);
        });
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

