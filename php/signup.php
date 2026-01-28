<?php
session_start();
$page_title = 'Signup - EasyCart';
$page_css = 'signup.css';

// Simple in-memory user store using PHP session (no database)
if (!isset($_SESSION['users']) || !is_array($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

$signup_error = '';
$signup_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first    = trim((string)($_POST['firstname'] ?? ''));
    $last     = trim((string)($_POST['lastname'] ?? ''));
    $email    = trim((string)($_POST['email'] ?? ''));
    $phone    = trim((string)($_POST['phone'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm  = (string)($_POST['confirmpassword'] ?? '');

    // Basic required-field validation
    if ($first === '' || $last === '' || $email === '' || $phone === '' || $password === '' || $confirm === '') {
        $signup_error = 'All fields are required.';
    } elseif (!preg_match('/^[A-Za-z ]+$/', $first) || !preg_match('/^[A-Za-z ]+$/', $last)) {
        $signup_error = 'Special characters are not allowed in name.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = 'Please enter a valid email address.';
    } else {
        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) !== 10) {
            $signup_error = 'Phone number must be exactly 10 digits.';
        } elseif ($digits === '0000000000' || $digits[0] === '0') {
            $signup_error = 'Please enter a valid phone number.';
        } elseif ($password !== $confirm) {
            $signup_error = 'Passwords do not match.';
        }
    }

    // If still no error, check if user exists and then store
    if ($signup_error === '') {
        if (isset($_SESSION['users'][$email])) {
            $signup_error = 'User with this email already exists. Please login.';
        } else {
            $_SESSION['users'][$email] = [
                'firstName' => $first,
                'lastName'  => $last,
                'email'     => $email,
                'phone'     => $digits,
                // Demo only – in real apps, hash passwords!
                'password'  => $password,
            ];
            // Set success flash message
            $_SESSION['flash_message'] = [
                'text' => 'Account created successfully. Please log in.',
                'type' => 'success'
            ];
            // Redirect to login after successful signup
            header('Location: login.php');
            exit;
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

    <div class="container">
        <div class="signup-container">
            <div class="signup-card">
            <h1>Create Account</h1>

            <?php if ($signup_error): ?>
                <div class="error-msg" style="margin-bottom:1rem;"><?php echo htmlspecialchars($signup_error); ?></div>
            <?php endif; ?>

            <form id="signupForm" method="POST" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" maxlength="10" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <small id="passwordStrength" class="password-strength"></small>
                    <small id="passwordHint" class="password-hint">
                        Add uppercase, number, and special character to make password strong.
                    </small>
                </div>

                <div class="form-group">
                    <label for="confirmpassword">Confirm Password</label>
                    <input type="password" id="confirmpassword" name="confirmpassword" required>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>