<div class="signup-container">
    <div class="signup-card">
        <h1>Create Account</h1>

        <?php if (isset($error) && $error): ?>
            <div class="error-msg"
                style="color: #ef4444; background: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; border: 1px solid #fecaca;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="signup-btn">Create Account</button>
        </form>
        <p class="auth-link">Already have an account? <a href="login">Login</a></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const firstName = document.getElementById('firstName');
        const lastName = document.getElementById('lastName');
        const email = document.getElementById('email');
        const phone = document.getElementById('phone');
        const password = document.getElementById('password');

        form.addEventListener('submit', function (e) {
            let errors = [];

            if (firstName.value.trim().length < 2) errors.push("First name is too short.");
            if (lastName.value.trim().length < 2) errors.push("Last name is too short.");

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) errors.push("Invalid email format.");

            if (phone.value.trim() && !/^\d{10}$/.test(phone.value.trim())) {
                errors.push("Phone number must be exactly 10 digits.");
            }

            if (password.value.length < 6) {
                errors.push("Password must be at least 6 characters long.");
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    });
</script>