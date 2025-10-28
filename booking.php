<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Check  logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    $_SESSION['message'] = "Please log in to make a booking.";
    header("Location: login.php");
    exit();
}

$message = '';
$message_type = '';
$item_type = '';
$item_name = '';
$item_id = 0;
$item_details = [];
$item_price = 0;

// Determine tour or hotel booking
if (isset($_GET['tour_id'])) {
    $item_id = intval($_GET['tour_id']);
    $item_type = 'tour';
    $stmt = $conn->prepare("SELECT tour_id, tour_name, price, description, location FROM tours WHERE tour_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item_details = $result->fetch_assoc();
        $item_name = $item_details['tour_name'];
        $item_price = $item_details['price'];
    } else {
        $message = "Error: Tour not found.";
        $message_type = 'error';
    }
    $stmt->close();
} elseif (isset($_GET['hotel_id'])) {
    $item_id = intval($_GET['hotel_id']);
    $item_type = 'hotel';
    $stmt = $conn->prepare("SELECT hotel_id, hotel_name, price_per_night, location FROM hotels WHERE hotel_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $item_details = $result->fetch_assoc();
        $item_name = $item_details['hotel_name'];
        $item_price = $item_details['price_per_night'];
    } else {
        $message = "Error: Hotel not found.";
        $message_type = 'error';
    }
    $stmt->close();
} else {
    $message = "Please select a tour or hotel to book.";
    $message_type = 'error';
}

// Handle Form
if ($_SERVER["REQUEST_METHOD"] == "POST" && $item_id > 0) {
    $user_id = $_SESSION['user_id'];
    $booking_date = $_POST['booking_date'];
    $duration = $_POST['duration'] ?? 1;
    $guests = $_POST['guests'] ?? 1;
    $special_requests = trim($_POST['special_requests'] ?? '');

    $total_price = $item_price * $duration * $guests;

    $tour_id = ($item_type == 'tour') ? $item_id : NULL;
    $hotel_id = ($item_type == 'hotel') ? $item_id : NULL;

    $sql = "INSERT INTO bookings (user_id, tour_id, hotel_id, booking_date, duration, guests, total_price, special_requests, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("iiisiiis", $user_id, $tour_id, $hotel_id, $booking_date, $duration, $guests, $total_price, $special_requests);

        if ($stmt->execute()) {
            $message = "Success! Your booking for <strong>" . htmlspecialchars($item_name) . "</strong> has been submitted. Status: <strong>Pending</strong> confirmation.";
            $message_type = 'success';


            $_POST = [];
        } else {
            $message = "Error: Could not process booking. " . $conn->error;
            $message_type = 'error';
        }
        $stmt->close();
    } else {
        $message = "Error: Database preparation failed.";
        $message_type = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Now - Travel Explorer</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/booking.css">
</head>

<body>
    <!-- Hero Section -->
    <section class="booking-hero">
        <div class="hero-content">
            <h1>Complete Your Booking</h1>
            <p>Finalize your reservation and get ready for an amazing experience</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="booking-container">
        <div class="booking-wrapper">
            <div class="booking-card">
                <div class="card-header">
                    <div class="booking-badge">
                        <i class="fas fa-<?php echo $item_type === 'tour' ? 'route' : 'hotel'; ?>"></i>
                        <?php echo ucfirst($item_type); ?> Booking
                    </div>
                    <h2>Book: <?php echo htmlspecialchars($item_name); ?></h2>
                    <p>Please fill in your booking details below</p>
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

                <?php if ($item_id > 0 && $message_type !== 'success'): ?>
                    <div class="booking-content">
                        <div class="item-summary">
                            <div class="item-image">
                                <img src="<?php echo $item_type === 'tour' ? 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400' : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400'; ?>" alt="<?php echo htmlspecialchars($item_name); ?>" loading="lazy">
                            </div>
                            <div class="item-details">
                                <h3><?php echo htmlspecialchars($item_name); ?></h3>
                                <p class="item-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($item_details['location'] ?? 'Sri Lanka'); ?>
                                </p>
                                <div class="price-section">
                                    <span class="price-label"><?php echo $item_type === 'tour' ? 'Tour Price' : 'Price per night'; ?>:</span>
                                    <span class="price">$<?php echo number_format($item_price, 2); ?></span>
                                </div>
                            </div>
                        </div>

                        <form class="booking-form" action="booking.php?<?php echo $item_type; ?>_id=<?php echo $item_id; ?>" method="POST" id="bookingForm">
                            <div class="form-section">
                                <h3><i class="fas fa-user"></i> Personal Information</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name" class="form-label">
                                            Full Name
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" id="name" name="name" class="form-input" value="<?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Guest User'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            Email Address
                                            <span class="required">*</span>
                                        </label>
                                        <input type="email" id="email" name="email" class="form-input" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? 'user@example.com'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3><i class="fas fa-calendar-alt"></i> Booking Details</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="booking_date" class="form-label">
                                            <?php echo $item_type === 'tour' ? 'Tour Start Date' : 'Check-in Date'; ?>
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" id="booking_date" name="booking_date" class="form-input" required
                                            value="<?php echo htmlspecialchars($_POST['booking_date'] ?? ''); ?>"
                                            min="<?php echo date('Y-m-d'); ?>">
                                        <div class="form-hint">Select your preferred start date</div>
                                        <span id="date-error" class="error-message"></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="duration" class="form-label">
                                            <?php echo $item_type === 'tour' ? 'Tour Duration (days)' : 'Nights Stay'; ?>
                                            <span class="required">*</span>
                                        </label>
                                        <select id="duration" name="duration" class="form-input" required>
                                            <?php for ($i = 1; $i <= ($item_type === 'tour' ? 14 : 30); $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo ($_POST['duration'] ?? 1) == $i ? 'selected' : ''; ?>>
                                                    <?php echo $i . ' ' . ($item_type === 'tour' ? 'day' . ($i > 1 ? 's' : '') : 'night' . ($i > 1 ? 's' : '')); ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="guests" class="form-label">
                                        Number of Guests
                                        <span class="required">*</span>
                                    </label>
                                    <select id="guests" name="guests" class="form-input" required>
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                            <option value="<?php echo $i; ?>" <?php echo ($_POST['guests'] ?? 1) == $i ? 'selected' : ''; ?>>
                                                <?php echo $i . ' guest' . ($i > 1 ? 's' : ''); ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3><i class="fas fa-comment-alt"></i> Additional Information</h3>
                                <div class="form-group">
                                    <label for="special_requests" class="form-label">
                                        Special Requests
                                        <span class="optional">(Optional)</span>
                                    </label>
                                    <textarea id="special_requests" name="special_requests" class="form-input" rows="4" placeholder="Any special requirements or preferences..."><?php echo htmlspecialchars($_POST['special_requests'] ?? ''); ?></textarea>
                                    <div class="form-hint">Dietary requirements, accessibility needs, etc.</div>
                                </div>
                            </div>

                            <div class="price-summary">
                                <h3><i class="fas fa-receipt"></i> Price Summary</h3>
                                <div class="summary-row">
                                    <span>Base Price:</span>
                                    <span>$<span id="basePrice"><?php echo number_format($item_price, 2); ?></span></span>
                                </div>
                                <div class="summary-row">
                                    <span>Duration:</span>
                                    <span><span id="durationDisplay">1</span> <?php echo $item_type === 'tour' ? 'day' : 'night'; ?>(s)</span>
                                </div>
                                <div class="summary-row">
                                    <span>Guests:</span>
                                    <span><span id="guestsDisplay">1</span> guest(s)</span>
                                </div>
                                <div class="summary-total">
                                    <span>Total Amount:</span>
                                    <span class="total-price">$<span id="totalPrice"><?php echo number_format($item_price, 2); ?></span></span>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-full">
                                    <i class="fas fa-lock"></i>
                                    <span class="btn-text">Confirm Booking</span>
                                    <div class="btn-loader" style="display: none;">
                                        <div class="loader"></div>
                                    </div>
                                </button>
                                <a href="<?php echo $item_type === 'tour' ? 'tour_details.php?id=' . $item_id : 'hotel_details.php?id=' . $item_id; ?>" class="btn btn-outline">
                                    <i class="fas fa-arrow-left"></i>
                                    Back to Details
                                </a>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingForm = document.getElementById('bookingForm');
            const durationInput = document.getElementById('duration');
            const guestsInput = document.getElementById('guests');
            const bookingDateInput = document.getElementById('booking_date');
            const basePrice = <?php echo $item_price; ?>;

            // Update price summary 
            function updatePriceSummary() {
                const duration = parseInt(durationInput.value);
                const guests = parseInt(guestsInput.value);
                const totalPrice = basePrice * duration * guests;

                document.getElementById('durationDisplay').textContent = duration;
                document.getElementById('guestsDisplay').textContent = guests;
                document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
            }


            updatePriceSummary();


            durationInput.addEventListener('change', updatePriceSummary);
            guestsInput.addEventListener('change', updatePriceSummary);

            // Form submission handling
            bookingForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('.btn-primary');
                const btnText = submitBtn.querySelector('.btn-text');
                const btnLoader = submitBtn.querySelector('.btn-loader');

                // Validate date
                const today = new Date().toISOString().split('T')[0];
                if (bookingDateInput.value < today) {
                    e.preventDefault();
                    document.getElementById('date-error').textContent = "Booking date cannot be in the past.";
                    return;
                }

                // Show loading state
                btnText.style.opacity = '0';
                btnLoader.style.display = 'block';
                submitBtn.disabled = true;
            });

            // Date validation
            bookingDateInput.addEventListener('change', function() {
                const today = new Date().toISOString().split('T')[0];
                const dateError = document.getElementById('date-error');

                if (this.value < today) {
                    dateError.textContent = "Booking date cannot be in the past.";
                } else {
                    dateError.textContent = "";
                }
            });

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            bookingDateInput.min = today;

            // If no date is selected, set it to tomorrow as default
            if (!bookingDateInput.value) {
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                bookingDateInput.value = tomorrow.toISOString().split('T')[0];
            }
        });
    </script>
</body>

</html>