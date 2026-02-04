<div class="login-container">
    <div class="login-card">
        <h1>Login</h1>

        <?php if (isset($error) && $error): ?>
            <div class="error-msg"
                style="color: #ef4444; background: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem; text-align: center; border: 1px solid #fecaca;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>
        <p class="auth-link">Don't have an account? <a href="signup">Sign Up</a></p>
    </div>
</div>