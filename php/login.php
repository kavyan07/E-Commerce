<?php
$page_title = 'Login - EasyCart';
$page_css = 'login.css';
require_once __DIR__ . '/../includes/header.php';
?>

    <div class="container">
        <div class="login-container">
            <div class="login-card">
            <h1>🔐 Login</h1>

            <div id="message"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" required>
                </div>

                <div class="remember-me">
                    <input type="checkbox" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>


            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>

                <div class="signup-link">
                    Don't have an account? <a href="signup.php">Sign up here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // localStorage-based login with form validation
        (function() {
            const form = document.getElementById('loginForm');
            if (!form) return;
            
            // Store reference for later use
            form._hasInlineHandler = true;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const msgDiv = document.getElementById('message');
                msgDiv.innerHTML = "";

                // Basic validation
                if (!email || !password) {
                    msgDiv.innerHTML = '<p class="error-msg">Email and password are required.</p>';
                    return;
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    msgDiv.innerHTML = '<p class="error-msg">Please enter a valid email address.</p>';
                    return;
                }

                // Password validation
                if (!password || password.length === 0) {
                    msgDiv.innerHTML = '<p class="error-msg">Password is required.</p>';
                    return;
                }

                const users = JSON.parse(localStorage.getItem('easycart_users') || '[]');
                const user = users.find(u => u.email === email && u.password === password);

                if (!user) {
                    msgDiv.innerHTML = '<p class="error-msg">Invalid email or password.</p>';
                    return;
                }

                // Store login info
                localStorage.setItem('easycart_loggedInUser', JSON.stringify(user));
                msgDiv.innerHTML = '<p class="success-msg">Login successful! Redirecting to home...</p>';

                // Redirect to home after delay
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 1000);
            }, { once: false });
        })();
    </script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
