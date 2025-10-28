<?php
$page_title = "Contact Us";
require_once 'includes/header.php';


$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    $errors = [];

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }

    if (empty($subject)) {
        $errors[] = "Subject is required";
    }

    if (empty($message)) {
        $errors[] = "Message is required";
    }

    if (empty($errors)) {
        // Process the form (in a real application, you might save to database or send email)
        $success_message = "Thank you for your message, $name! We'll get back to you within 24 hours.";

        // Clear form fields
        $name = $email = $phone = $subject = $message = '';
    } else {
        $error_message = "Please fix the following errors:<br>" . implode('<br>', $errors);
    }
}
?>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="hero-content">
        <h1>Get In Touch</h1>
        <p>We're here to help you plan your perfect Sri Lankan adventure</p>
    </div>
</section>

<!-- Main Content -->
<main class="contact-container">
    <!-- Contact Info & Form Section -->
    <section class="contact-main">
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Have questions about our tours or need help planning your trip? Reach out to our friendly team.</p>

            <div class="contact-methods">
                <div class="contact-method">
                    <div class="method-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                        </svg>
                    </div>
                    <div class="method-details">
                        <h3>Email Us</h3>
                        <p>info@tourlanka.com</p>
                        <p>support@tourlanka.com</p>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="method-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                        </svg>
                    </div>
                    <div class="method-details">
                        <h3>Call Us</h3>
                        <p>+94 11 234 5678</p>
                        <p>+94 77 123 4567</p>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="method-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                        </svg>
                    </div>
                    <div class="method-details">
                        <h3>Visit Us</h3>
                        <p>123 Galle Road</p>
                        <p>Colombo 03, Sri Lanka</p>
                    </div>
                </div>

                <div class="contact-method">
                    <div class="method-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z" />
                        </svg>
                    </div>
                    <div class="method-details">
                        <h3>Business Hours</h3>
                        <p>Monday - Friday: 8:00 AM - 6:00 PM</p>
                        <p>Saturday: 9:00 AM - 4:00 PM</p>
                    </div>
                </div>
            </div>

            <div class="social-links">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="YouTube">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="contact-form-container">
            <div class="form-header">
                <h2>Send us a Message</h2>
                <p>Fill out the form below and we'll get back to you as soon as possible</p>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form class="contact-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject *</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="general" <?php echo ($subject ?? '') === 'general' ? 'selected' : ''; ?>>General Inquiry</option>
                            <option value="booking" <?php echo ($subject ?? '') === 'booking' ? 'selected' : ''; ?>>Booking Assistance</option>
                            <option value="custom" <?php echo ($subject ?? '') === 'custom' ? 'selected' : ''; ?>>Custom Tour Request</option>
                            <option value="feedback" <?php echo ($subject ?? '') === 'feedback' ? 'selected' : ''; ?>>Feedback</option>
                            <option value="complaint" <?php echo ($subject ?? '') === 'complaint' ? 'selected' : ''; ?>>Complaint</option>
                            <option value="partnership" <?php echo ($subject ?? '') === 'partnership' ? 'selected' : ''; ?>>Partnership</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message">Your Message *</label>
                    <textarea id="message" name="message" rows="6" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-full">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                    </svg>
                    Send Message
                </button>
            </form>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="section-header">
            <h2>Frequently Asked Questions</h2>
            <p>Quick answers to common questions</p>
        </div>

        <div class="faq-grid">
            <div class="faq-item">
                <h3>How far in advance should I book my tour?</h3>
                <p>We recommend booking at least 2-3 months in advance for the best availability, especially during peak season (December to April).</p>
            </div>

            <div class="faq-item">
                <h3>What is your cancellation policy?</h3>
                <p>We offer free cancellation up to 30 days before your tour. Cancellations within 14-30 days receive a 50% refund. Please see our terms for details.</p>
            </div>

            <div class="faq-item">
                <h3>Do you offer custom tour packages?</h3>
                <p>Yes! We specialize in creating personalized itineraries. Contact us with your preferences and we'll design your perfect Sri Lankan adventure.</p>
            </div>

            <div class="faq-item">
                <h3>What payment methods do you accept?</h3>
                <p>We accept credit cards (Visa, MasterCard), bank transfers, and online payment platforms. A 30% deposit is required to confirm your booking.</p>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="section-header">
            <h2>Visit Our Office</h2>
            <p>Come see us in Colombo</p>
        </div>

        <div class="map-container">
            <div class="map-placeholder">
                <div class="map-content">
                    <h3>Tour Lanka Headquarters</h3>
                    <p>123 Galle Road, Colombo 03</p>
                    <p>Sri Lanka</p>
                    <div class="map-features">
                        <div class="map-feature">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                            </svg>
                            <span>Easy to find location</span>
                        </div>
                        <div class="map-feature">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                            </svg>
                            <span>Free parking available</span>
                        </div>
                        <div class="map-feature">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                            </svg>
                            <span>Near public transport</span>
                        </div>
                    </div>
                </div>
                <div class="map-image">
                    <img src="assets/images/office.webp" alt="Colombo City View" loading="lazy">
                    <div class="map-overlay">
                        <a href="https://maps.google.com/?q=123+Galle+Road+Colombo+Sri+Lanka" target="_blank" class="btn btn-outline">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z" />
                            </svg>
                            Open in Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    .contact-hero {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 100px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .contact-hero::before {
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
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-size: 3.2rem;
        margin-bottom: 20px;
        font-weight: 800;
        line-height: 1.1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-content p {
        font-size: 1.3rem;
        opacity: 0.95;
        font-weight: 400;
        line-height: 1.5;
    }

    /* Main Container */
    .contact-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Contact Main Section */
    .contact-main {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 60px;
        margin: 60px 0;
    }

    /* Contact Info */
    .contact-info h2 {
        font-size: 2.2rem;
        color: #1e293b;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .contact-info>p {
        color: #64748b;
        margin-bottom: 40px;
        font-size: 1.1rem;
    }

    .contact-methods {
        display: flex;
        flex-direction: column;
        gap: 30px;
        margin-bottom: 40px;
    }

    .contact-method {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .method-icon {
        background: #e0f2fe;
        color: #009fc7;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .method-details h3 {
        font-size: 1.2rem;
        color: #1e293b;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .method-details p {
        color: #64748b;
        margin-bottom: 4px;
    }

    /* Social Links */
    .social-links h3 {
        font-size: 1.2rem;
        color: #1e293b;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .social-icons {
        display: flex;
        gap: 15px;
    }

    .social-link {
        background: #f1f5f9;
        color: #64748b;
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: #009fc7;
        color: white;
        transform: translateY(-2px);
    }

    /* Contact Form */
    .contact-form-container {
        background: white;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .form-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-header h2 {
        font-size: 2rem;
        color: #1e293b;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .form-header p {
        color: #64748b;
    }

    /* Alerts */
    .alert {
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 25px;
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

    /* Form Styles */
    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 14px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #009fc7;
        box-shadow: 0 0 0 3px rgba(0, 159, 199, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px 28px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 1rem;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #009fc7, #0681a0);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 159, 199, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0681a0, #009fc7);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 159, 199, 0.4);
    }

    .btn-outline {
        background: transparent;
        color: #009fc7;
        border: 1.5px solid #009fc7;
    }

    .btn-outline:hover {
        background: #009fc7;
        color: white;
        transform: translateY(-2px);
    }

    .btn-full {
        width: 100%;
    }

    /* FAQ Section */
    .faq-section {
        margin: 80px 0;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        color: #1e293b;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .section-header p {
        color: #64748b;
        font-size: 1.2rem;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .faq-item {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .faq-item h3 {
        font-size: 1.2rem;
        color: #1e293b;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .faq-item p {
        color: #64748b;
        line-height: 1.6;
    }

    /* Map Section */
    .map-section {
        margin: 80px 0;
    }

    .map-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .map-placeholder {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 400px;
    }

    .map-content {
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .map-content h3 {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .map-content p {
        color: #64748b;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .map-features {
        margin-top: 25px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .map-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #475569;
    }

    .map-feature svg {
        color: #009fc7;
    }

    .map-image {
        position: relative;
        overflow: hidden;
    }

    .map-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .map-overlay {
        position: absolute;
        bottom: 20px;
        right: 20px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .contact-main {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .map-placeholder {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 2.5rem;
        }

        .contact-hero p {
            font-size: 1.1rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .faq-grid {
            grid-template-columns: 1fr;
        }

        .contact-form-container {
            padding: 30px 25px;
        }

        .map-content {
            padding: 30px 25px;
        }
    }

    @media (max-width: 480px) {
        .contact-hero {
            padding: 80px 15px 60px;
        }

        .contact-hero h1 {
            font-size: 2rem;
        }

        .contact-container {
            padding: 0 15px;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .contact-method {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .social-icons {
            justify-content: center;
        }
    }
</style>

<script>
    // Simple form enhancement
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.querySelector('.contact-form');

        // Add real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            let isValid = true;
            let errorMessage = '';

            // Clear previous error
            clearFieldError(field);

            // Required field validation
            if (field.hasAttribute('required') && !value) {
                isValid = false;
                errorMessage = 'This field is required';
            }

            // Email validation
            if (field.type === 'email' && value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address';
                }
            }

            // Phone validation (basic)
            if (field.type === 'tel' && value) {
                const phoneRegex = /^[\+]?[0-9\s\-\(\)]+$/;
                if (!phoneRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid phone number';
                }
            }

            if (!isValid) {
                showFieldError(field, errorMessage);
            }

            return isValid;
        }

        function showFieldError(field, message) {
            field.style.borderColor = '#ef4444';

            // Create error message element
            const errorElement = document.createElement('div');
            errorElement.className = 'field-error';
            errorElement.style.color = '#ef4444';
            errorElement.style.fontSize = '0.875rem';
            errorElement.style.marginTop = '5px';
            errorElement.textContent = message;

            field.parentNode.appendChild(errorElement);
        }

        function clearFieldError(field) {
            field.style.borderColor = '#e2e8f0';

            const existingError = field.parentNode.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
        }

        // Form submission enhancement
        contactForm.addEventListener('submit', function(e) {
            let formIsValid = true;

            // Validate all required fields
            inputs.forEach(input => {
                if (!validateField(input)) {
                    formIsValid = false;
                }
            });

            if (!formIsValid) {
                e.preventDefault();

                // Scroll to first error
                const firstError = document.querySelector('.field-error');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
</script>

<?php
include 'includes/footer.php';
?>