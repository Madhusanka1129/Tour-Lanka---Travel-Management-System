<?php
$page_title = "Register";
require_once 'includes/header.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];

    if (empty($name)) {
        $errors[] = "Full name is required.";
    } elseif (strlen($name) < 2) {
        $errors[] = "Full name must be at least 2 characters long.";
    }

    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (!empty($phone) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
        $errors[] = "Please enter a valid phone number.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "This email address is already registered.";
            $message_type = 'error';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssss", $name, $email, $phone, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! You can now log in.";
                $message_type = 'success';

                $name = $email = $phone = '';
            } else {
                $message = "Error: " . $conn->error;
                $message_type = 'error';
            }
            $stmt->close();
        }
        $check_stmt->close();
    } else {
        $message = "❌ " . implode("<br>", $errors);
        $message_type = 'error';
    }
}
?>

<!-- Hero Section -->
<section class="register-hero">
    <div class="hero-content">
        <h1>Join Tour Lanka</h1>
        <p>Create your account and start your Sri Lankan adventure</p>
    </div>
</section>

<!-- Main Content -->
<main class="register-container">
    <div class="register-wrapper">
        <div class="register-card">
            <div class="card-header">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                    </svg>
                    <h2>Tour Lanka</h2>
                </div>
                <h3>Create Your Account</h3>
                <p>Join thousands of travelers exploring Sri Lanka</p>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <div class="alert-icon">
                        <?php if ($message_type === 'success'): ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                            </svg>
                        <?php else: ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                            </svg>
                        <?php endif; ?>
                    </div>
                    <span><?php echo $message; ?></span>
                </div>
            <?php endif; ?>

            <form class="register-form" action="register.php" method="POST" id="registrationForm">
                <div class="form-group">
                    <label for="name" class="form-label">
                        Full Name
                        <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                        </span>
                        <input type="text" id="name" name="name" class="form-input" placeholder="Enter your full name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                    </div>
                    <div class="form-hint">Your name as it appears on official documents</div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">
                        Email Address
                        <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-hint">We'll send your booking confirmations to this email</div>
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">
                        Phone Number
                        <span class="optional">(Optional)</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                            </svg>
                        </span>
                        <input type="tel" id="phone" name="phone" class="form-input" placeholder="Enter your phone number" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                    <div class="form-hint">For important updates about your bookings</div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        Password
                        <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" class="form-input" placeholder="Create a strong password" required>
                        <button type="button" class="password-toggle" id="passwordToggle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="eye-icon">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                            </svg>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="eye-off-icon" style="display: none;">
                                <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                            </svg>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-bar"></div>
                        </div>
                        <div class="strength-text">Password strength: <span id="strengthValue">None</span></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" data-requirement="length">At least 8 characters</div>
                        <div class="requirement" data-requirement="uppercase">One uppercase letter</div>
                        <div class="requirement" data-requirement="lowercase">One lowercase letter</div>
                        <div class="requirement" data-requirement="number">One number</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        Confirm Password
                        <span class="required">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM12 17c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                            </svg>
                        </span>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm your password" required>
                        <button type="button" class="password-toggle" id="confirmPasswordToggle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="eye-icon">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" />
                            </svg>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" class="eye-off-icon" style="display: none;">
                                <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" />
                            </svg>
                        </button>
                    </div>
                    <div id="confirmMessage" class="form-hint"></div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="terms.php" target="_blank">Terms of Service</a> and <a href="privacy.php" target="_blank">Privacy Policy</a>
                        <span class="required">*</span>
                    </label>

                    <label class="checkbox-label">
                        <input type="checkbox" name="newsletter" id="newsletter" checked>
                        <span class="checkmark"></span>
                        Send me travel deals and updates (optional)
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                    <span class="btn-text">Create Account</span>
                    <div class="btn-loader" style="display: none;">
                        <div class="loader"></div>
                    </div>
                </button>

                <div class="divider">
                    <span>Or sign up with</span>
                </div>

                <div class="social-login">
                    <button type="button" class="btn btn-google">
                        <svg width="18" height="18" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                        </svg>
                        Continue with Google
                    </button>
                    <button type="button" class="btn btn-facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                        Continue with Facebook
                    </button>
                </div>
            </form>

            <div class="card-footer">
                <p>Already have an account?
                    <a href="login.php" class="login-link">Sign in here</a>
                </p>
            </div>
        </div>

        <div class="register-features">
            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                </div>
                <h4>Secure Account</h4>
                <p>Bank-level security to protect your personal information and bookings</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                    </svg>
                </div>
                <h4>Easy Booking Management</h4>
                <p>Access and manage all your bookings from one convenient dashboard</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                    </svg>
                </div>
                <h4>Exclusive Deals</h4>
                <p>Get access to member-only discounts and early booking offers</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <h4>Personalized Experience</h4>
                <p>Receive tailored recommendations based on your travel preferences</p>
            </div>
        </div>
    </div>
</main>

<style>
    /* Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8fafc;
    }

    /* Hero Section */
    .register-hero {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 80px 20px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .register-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><polygon points="1000,100 1000,0 0,100"/></svg>');
        background-size: cover;
    }

    .hero-content {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-size: 3rem;
        margin-bottom: 16px;
        font-weight: 800;
        line-height: 1.1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-content p {
        font-size: 1.2rem;
        opacity: 0.95;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Main Container */
    .register-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .register-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
    }

    /* Register Card */
    .register-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid #f1f5f9;
    }

    .card-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-bottom: 20px;
        color: #009fc7;
    }

    .logo h2 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .card-header h3 {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .card-header p {
        color: #64748b;
        font-size: 1rem;
    }

    /* Alerts */
    .alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-weight: 500;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .alert-icon {
        display: flex;
        align-items: center;
    }

    /* Form Styles */
    .register-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .required {
        color: #ef4444;
    }

    .optional {
        color: #6b7280;
        font-size: 0.8rem;
        font-weight: 400;
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 16px;
        color: #64748b;
        z-index: 2;
    }

    .form-input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #009fc7;
        box-shadow: 0 0 0 3px rgba(0, 159, 199, 0.1);
    }

    .form-hint {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Password Strength */
    .password-strength {
        margin-top: 8px;
    }

    .strength-meter {
        height: 6px;
        background: #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .strength-bar {
        height: 100%;
        width: 0%;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    .strength-text {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .password-requirements {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 8px;
    }

    .requirement {
        font-size: 0.75rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .requirement::before {
        content: '✕';
        font-size: 0.875rem;
    }

    .requirement.valid {
        color: #10b981;
    }

    .requirement.valid::before {
        content: '✓';
    }

    .password-toggle {
        position: absolute;
        right: 16px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #009fc7;
    }

    /* Form Options */
    .form-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 8px 0;
    }

    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        color: #374151;
        line-height: 1.4;
    }

    .checkbox-label input {
        display: none;
    }

    .checkmark {
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .checkbox-label input:checked+.checkmark {
        background: #009fc7;
        border-color: #009fc7;
    }

    .checkbox-label input:checked+.checkmark::after {
        content: '✓';
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .checkbox-label a {
        color: #009fc7;
        text-decoration: none;
        font-weight: 500;
    }

    .checkbox-label a:hover {
        text-decoration: underline;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        position: relative;
    }

    .btn-primary {
        background: linear-gradient(135deg, #009fc7, #0681a0);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 159, 199, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #0681a0, #009fc7);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 159, 199, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-full {
        width: 100%;
    }

    .btn-google,
    .btn-facebook {
        background: white;
        color: #374151;
        border: 1.5px solid #e2e8f0;
        flex: 1;
    }

    .btn-google:hover,
    .btn-facebook:hover {
        background: #f8fafc;
        border-color: #009fc7;
        transform: translateY(-1px);
    }

    .btn-text {
        transition: opacity 0.3s ease;
    }

    .btn-loader {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .loader {
        width: 20px;
        height: 20px;
        border: 2px solid transparent;
        border-top: 2px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        color: #64748b;
        font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .divider span {
        padding: 0 16px;
    }

    /* Social Login */
    .social-login {
        display: flex;
        gap: 12px;
    }

    /* Card Footer */
    .card-footer {
        text-align: center;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #f1f5f9;
        color: #64748b;
    }

    .login-link {
        color: #009fc7;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login-link:hover {
        color: #0681a0;
        text-decoration: underline;
    }

    /* Features Section */
    .register-features {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .feature-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
        text-align: center;
    }

    .feature-icon {
        color: #009fc7;
        margin-bottom: 16px;
    }

    .feature-card h4 {
        font-size: 1.2rem;
        color: #1e293b;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .feature-card p {
        color: #64748b;
        line-height: 1.5;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .register-wrapper {
            grid-template-columns: 1fr;
            gap: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        .register-features {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 768px) {
        .register-hero {
            padding: 60px 20px 40px;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1.1rem;
        }

        .register-card {
            padding: 30px 25px;
        }

        .social-login {
            flex-direction: column;
        }

        .password-requirements {
            grid-template-columns: 1fr;
        }

        .register-features {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .register-container {
            padding: 20px 15px;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .register-card {
            padding: 25px 20px;
        }

        .feature-card {
            padding: 20px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
        const passwordToggle = document.getElementById('passwordToggle');
        const confirmPasswordToggle = document.getElementById('confirmPasswordToggle');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');

        function setupPasswordToggle(toggle, input) {
            const eyeIcon = toggle.querySelector('.eye-icon');
            const eyeOffIcon = toggle.querySelector('.eye-off-icon');

            toggle.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                if (type === 'text') {
                    eyeIcon.style.display = 'none';
                    eyeOffIcon.style.display = 'block';
                } else {
                    eyeIcon.style.display = 'block';
                    eyeOffIcon.style.display = 'none';
                }
            });
        }

        if (passwordToggle) setupPasswordToggle(passwordToggle, passwordInput);
        if (confirmPasswordToggle) setupPasswordToggle(confirmPasswordToggle, confirmPasswordInput);

        // Password strength meter
        const strengthBar = document.querySelector('.strength-bar');
        const strengthValue = document.getElementById('strengthValue');
        const requirements = document.querySelectorAll('.requirement');

        function checkPasswordStrength(password) {
            let strength = 0;
            const requirementsMet = {
                length: false,
                uppercase: false,
                lowercase: false,
                number: false
            };

            // Length requirement
            if (password.length >= 8) {
                strength += 25;
                requirementsMet.length = true;
            }

            // Uppercase requirement
            if (/[A-Z]/.test(password)) {
                strength += 25;
                requirementsMet.uppercase = true;
            }

            // Lowercase requirement
            if (/[a-z]/.test(password)) {
                strength += 25;
                requirementsMet.lowercase = true;
            }

            // Number requirement
            if (/[0-9]/.test(password)) {
                strength += 25;
                requirementsMet.number = true;
            }

            // Update requirements display
            requirements.forEach(req => {
                const type = req.getAttribute('data-requirement');
                if (requirementsMet[type]) {
                    req.classList.add('valid');
                } else {
                    req.classList.remove('valid');
                }
            });

            // Update strength bar and text
            strengthBar.style.width = strength + '%';

            if (strength === 0) {
                strengthBar.style.backgroundColor = '#e5e7eb';
                strengthValue.textContent = 'None';
            } else if (strength <= 25) {
                strengthBar.style.backgroundColor = '#ef4444';
                strengthValue.textContent = 'Weak';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#f59e0b';
                strengthValue.textContent = 'Fair';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#eab308';
                strengthValue.textContent = 'Good';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
                strengthValue.textContent = 'Strong';
            }

            return strength;
        }

        // Password confirmation check
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const confirmMessage = document.getElementById('confirmMessage');

            if (confirmPassword === '') {
                confirmMessage.textContent = '';
                confirmMessage.style.color = '#6b7280';
            } else if (password === confirmPassword) {
                confirmMessage.textContent = '✓ Passwords match';
                confirmMessage.style.color = '#10b981';
            } else {
                confirmMessage.textContent = '✕ Passwords do not match';
                confirmMessage.style.color = '#ef4444';
            }
        }

        // Event listeners
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });

        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form submission
        const registrationForm = document.getElementById('registrationForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoader = submitBtn.querySelector('.btn-loader');

        registrationForm.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            const terms = document.getElementById('terms').checked;

            // Final validation
            if (!terms) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy');
                return;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Please make sure your passwords match');
                return;
            }

            const strength = checkPasswordStrength(password);
            if (strength < 50) {
                e.preventDefault();
                alert('Please choose a stronger password');
                return;
            }

            // Show loading state
            btnText.style.opacity = '0';
            btnLoader.style.display = 'block';
            submitBtn.disabled = true;
        });

        // Social login buttons (placeholder functionality)
        const socialButtons = document.querySelectorAll('.btn-google, .btn-facebook');
        socialButtons.forEach(button => {
            button.addEventListener('click', function() {
                alert('Social registration functionality would be implemented here. This is a demo.');
            });
        });

        // Auto-focus name field
        const nameInput = document.getElementById('name');
        if (nameInput && !nameInput.value) {
            nameInput.focus();
        }
    });
</script>

<?php
echo '</main>';
include 'includes/footer.php';
?>