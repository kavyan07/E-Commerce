<div class="signup-container">
    <div class="signup-card">
        <h1>Create Account</h1>

        <?php if (isset($error) && $error): ?>
            <div class="error-msg"
                style="color: #ef4444; background: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; border: 1px solid #fecaca;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form id="signupForm" method="POST">
            <div class="form-row">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastName" required>
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
                <div id="passwordStrength" style="font-size: 0.8rem; margin-top: 0.2rem; font-weight: 600;"></div>
                <div id="passwordHint" style="font-size: 0.75rem; color: #64748b; margin-top: 0.1rem;"></div>
            </div>
            <button type="submit" class="signup-btn">Create Account</button>
        </form>
        <p class="auth-link">Already have an account? <a href="login">Login</a></p>
    </div>
</div>