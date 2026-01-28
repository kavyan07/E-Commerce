<?php
// Common footer include
?>
    <footer>
        <div class="footer-content">
            <div class="footer-brand">
                <div class="footer-logo">EasyCart</div>
                <p>Your one-stop shop for everything you love.</p>
            </div>
            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="index.php">Home</a>
                <a href="product-listing.php">Products</a>
                <a href="cart.php">Cart</a>
            </div>
            <div class="footer-links">
                <h4>Account</h4>
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
                <a href="my-orders.php">My Orders</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> EasyCart - All Rights Reserved</p>
        </div>
    </footer>
    
    <!-- Phase 3: Client-Side Interactions JavaScript -->
    <script src="../js/ecommerce.js"></script>
    
    <!-- Toast Notification System -->
    <script>
        // Toast Notification Functions
        function showToast(message, type = 'success', duration = 4000) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
            
            toast.innerHTML = `
                <span class="toast-icon">${icon}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="closeToast(this)">×</button>
            `;
            
            container.appendChild(toast);
            
            // Auto-remove after duration
            setTimeout(() => {
                closeToast(toast.querySelector('.toast-close'));
            }, duration);
        }
        
        function closeToast(button) {
            const toast = button.closest('.toast');
            if (toast) {
                toast.classList.add('hiding');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }
        
        // Check for flash messages from PHP session
        <?php if (isset($_SESSION['flash_message'])): ?>
            showToast('<?php echo htmlspecialchars($_SESSION['flash_message']['text']); ?>', '<?php echo htmlspecialchars($_SESSION['flash_message']['type'] ?? 'success'); ?>');
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
