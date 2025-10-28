<?php
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $page_title = "Error";
    echo '<h1 style="color: red; margin-top: 40px;">❌ Error: No tour package selected.</h1>';
    echo '<p><a href="tours.php">Go back to Tours List</a></p>';
    echo '</main>';
    include 'includes/footer.php';
    exit();
}

$tour_id = intval($_GET['id']);
$tour = null;
$stmt = $conn->prepare("SELECT tour_id, tour_name, description, price, image FROM tours WHERE tour_id = ?");
$stmt->bind_param("i", $tour_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tour = $result->fetch_assoc();
    $page_title = $tour['tour_name'];

    $local_placeholder_image = 'assets/images/placeholder_tour.jpg';
    $db_image_path = 'assets/' . $tour['image'];

    // Check if the database image path is valid and exists locally.
    if (!empty($tour['image']) && file_exists($db_image_path)) {
        $image_path = $db_image_path;
    } else {
        $image_path = $local_placeholder_image;
    }
} else {
    // Tour not found
    $page_title = "Not Found";
    $tour = null;
}
?>

<style>
    /* Consistent with tours.php styling */
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

    /* Hero Section - Matches tours.php */
    .tour-hero {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 80px 20px 60px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .tour-hero::before {
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
        max-width: 1200px;
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

    .tour-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        margin-bottom: 30px;
        max-width: 600px;
        font-weight: 400;
        line-height: 1.5;
    }

    .tour-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .meta-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
    }

    .meta-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        opacity: 0.9;
    }

    .meta-label {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 1.3rem;
        font-weight: 700;
    }

    .price-badge {
        background: linear-gradient(135deg, #e63946, #d62839);
        color: white;
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 800;
        font-size: 1.8rem;
        display: inline-block;
        box-shadow: 0 4px 15px rgba(230, 57, 70, 0.4);
    }

    /* Main Content */
    .tour-details-container {
        max-width: 1200px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }

    .tour-content-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
        margin-bottom: 50px;
    }

    /* Image Gallery */
    .image-gallery {
        margin-bottom: 30px;
    }

    .main-image {
        width: 100%;
        height: 400px;
        border-radius: 16px;
        object-fit: cover;
        margin-bottom: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .image-thumbnails {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .thumbnail {
        width: 100%;
        height: 80px;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #009fc7;
        transform: scale(1.05);
    }

    /* Content Sections */
    .content-section {
        background: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .section-title {
        font-size: 1.5rem;
        color: #1e293b;
        margin-bottom: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #009fc7;
        padding-bottom: 10px;
    }

    .section-title svg {
        color: #009fc7;
    }

    .description {
        line-height: 1.7;
        color: #475569;
        font-size: 1.05rem;
    }

    .description p {
        margin-bottom: 15px;
    }

    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid #009fc7;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #009fc7, #0681a0);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .feature-text {
        color: #334155;
        font-weight: 500;
    }

    /* Itinerary */
    .itinerary-item {
        display: flex;
        gap: 20px;
        padding: 25px;
        border-left: 3px solid #e2e8f0;
        margin-bottom: 20px;
        background: #f8fafc;
        border-radius: 0 12px 12px 0;
        transition: all 0.3s ease;
    }

    .itinerary-item:hover {
        border-left-color: #009fc7;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .day-number {
        background: linear-gradient(135deg, #009fc7, #0681a0);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .itinerary-content h4 {
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .itinerary-content p {
        color: #64748b;
        line-height: 1.6;
    }

    /* Booking Sidebar */
    .booking-sidebar {
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .booking-card {
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        border: 1px solid #f1f5f9;
        text-align: center;
    }

    .price-display {
        margin-bottom: 25px;
    }

    .price-amount {
        font-size: 3rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .price-period {
        color: #64748b;
        font-size: 1rem;
    }

    .booking-features {
        margin: 25px 0;
    }

    .booking-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: #475569;
        font-size: 0.95rem;
    }

    .booking-feature svg {
        color: #009fc7;
        flex-shrink: 0;
    }

    /* Buttons - Consistent with tours.php */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 15px 25px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 1rem;
        border: none;
        cursor: pointer;
        width: 100%;
        margin-bottom: 15px;
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
        border: 2px solid #009fc7;
    }

    .btn-outline:hover {
        background: #009fc7;
        color: white;
        transform: translateY(-2px);
    }

    .wishlist-btn {
        background: #f8fafc;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .wishlist-btn:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }

    /* Reviews Section */
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .review-card {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border-left: 4px solid #009fc7;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .reviewer-name {
        font-weight: 600;
        color: #1e293b;
    }

    .review-date {
        color: #64748b;
        font-size: 0.9rem;
    }

    .review-rating {
        color: #f59e0b;
        margin: 10px 0;
    }

    .review-text {
        color: #475569;
        line-height: 1.6;
        font-style: italic;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #1e293b, #334155);
        color: white;
        padding: 60px 40px;
        border-radius: 20px;
        text-align: center;
        margin-top: 50px;
    }

    .cta-title {
        font-size: 2.2rem;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .cta-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-buttons .btn {
        width: auto;
        min-width: 200px;
        margin-bottom: 0;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .tour-content-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .booking-sidebar {
            position: static;
        }

        .tour-meta-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .tour-hero {
            padding: 60px 15px 40px;
        }

        .hero-content h1 {
            font-size: 2.2rem;
        }

        .tour-subtitle {
            font-size: 1.1rem;
        }

        .tour-meta-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .content-section {
            padding: 20px;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .itinerary-item {
            flex-direction: column;
            gap: 15px;
        }

        .cta-section {
            padding: 40px 20px;
        }

        .cta-title {
            font-size: 1.8rem;
        }

        .image-thumbnails {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .tour-details-container {
            padding: 0 15px;
        }

        .price-badge {
            font-size: 1.4rem;
            padding: 10px 20px;
        }

        .price-amount {
            font-size: 2.5rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .cta-buttons .btn {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

<?php if ($tour): ?>
    <!-- Hero Section - Matches tours.php style -->
    <section class="tour-hero">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($tour['tour_name']); ?></h1>
            <p class="tour-subtitle">Experience the adventure of a lifetime with our expertly crafted tour package</p>

            <div class="tour-meta-grid">
                <!-- Default meta information since additional columns don't exist -->
                <div class="meta-card">
                    <div class="meta-icon">⏱️</div>
                    <div class="meta-label">Duration</div>
                    <div class="meta-value">5 Days</div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">📍</div>
                    <div class="meta-label">Location</div>
                    <div class="meta-value">Multiple Destinations</div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">🏔️</div>
                    <div class="meta-label">Difficulty</div>
                    <div class="meta-value">Moderate</div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">⭐</div>
                    <div class="meta-label">Rating</div>
                    <div class="meta-value">4.8/5</div>
                </div>
            </div>

            <div class="price-badge">
                $<?php echo number_format($tour['price'], 0); ?>
            </div>
        </div>
    </section>

    <main class="tour-details-container">
        <div class="tour-content-grid">
            <!-- Main Content -->
            <div class="tour-main-content">

                <!-- Tour Description -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" />
                            <path d="M14 2v6h6" />
                        </svg>
                        Tour Overview
                    </h3>
                    <div class="description">
                        <?php echo nl2br(htmlspecialchars($tour['description'])); ?>
                    </div>
                </div>

                <!-- Included Features -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        What's Included
                    </h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">🏨</div>
                            <div class="feature-text">Luxury Accommodation</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🍽️</div>
                            <div class="feature-text">All Meals Included</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🚐</div>
                            <div class="feature-text">Private Transportation</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">👨‍🏫</div>
                            <div class="feature-text">Expert Tour Guide</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🎟️</div>
                            <div class="feature-text">All Entrance Fees</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🛡️</div>
                            <div class="feature-text">Travel Insurance</div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Daily Itinerary
                    </h3>
                    <div class="itinerary">
                        <div class="itinerary-item">
                            <div class="day-number">1</div>
                            <div class="itinerary-content">
                                <h4>Arrival & Welcome</h4>
                                <p>Arrive at your destination, meet your tour guide, and settle into your luxury accommodation. Evening welcome dinner with the group.</p>
                            </div>
                        </div>
                        <div class="itinerary-item">
                            <div class="day-number">2</div>
                            <div class="itinerary-content">
                                <h4>City Exploration</h4>
                                <p>Full day exploring the city's main attractions, historical sites, and local markets. Lunch at a traditional restaurant.</p>
                            </div>
                        </div>
                        <div class="itinerary-item">
                            <div class="day-number">3</div>
                            <div class="itinerary-content">
                                <h4>Nature Adventure</h4>
                                <p>Journey into the surrounding natural landscapes. Hiking, wildlife spotting, and breathtaking views. Picnic lunch included.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                        Traveler Reviews
                    </h3>
                    <div class="reviews-grid">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name">Sarah Johnson</div>
                                <div class="review-date">March 2024</div>
                            </div>
                            <div class="review-rating">★★★★★</div>
                            <div class="review-text">"Absolutely incredible experience! The tour guides were knowledgeable and the itinerary was perfectly planned."</div>
                        </div>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name">Mike Chen</div>
                                <div class="review-date">February 2024</div>
                            </div>
                            <div class="review-rating">★★★★☆</div>
                            <div class="review-text">"Great value for money. The accommodations were excellent and the food was amazing throughout the trip."</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="booking-sidebar">
                <div class="booking-card">
                    <div class="price-display">
                        <div class="price-amount">$<?php echo number_format($tour['price'], 0); ?></div>
                        <div class="price-period">per person</div>
                    </div>

                    <div class="booking-features">
                        <div class="booking-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Free cancellation up to 24 hours
                        </div>
                        <div class="booking-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Best price guarantee
                        </div>
                        <div class="booking-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Instant confirmation
                        </div>
                    </div>

                    <a href="booking.php?tour_id=<?php echo $tour['tour_id']; ?>" class="btn btn-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z" />
                        </svg>
                        Book Now
                    </a>

                    <button class="btn wishlist-btn" id="wishlistBtn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        Add to Wishlist
                    </button>

                    <div class="tour-reference">
                        <p style="text-align: center; color: #64748b; font-size: 0.9rem; margin-top: 20px;">
                            Reference: <strong>#TOUR<?php echo $tour['tour_id']; ?></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2 class="cta-title">Ready for Your Adventure?</h2>
            <p class="cta-subtitle">Join thousands of satisfied travelers who have experienced this incredible journey</p>
            <div class="cta-buttons">
                <a href="booking.php?tour_id=<?php echo $tour['tour_id']; ?>" class="btn btn-primary">
                    Book This Tour
                </a>
                <a href="contact.php" class="btn btn-outline">
                    Contact Us
                </a>
            </div>
        </div>
    </main>

<?php else: ?>
    <!-- Not Found State - Matches tours.php style -->
    <section class="tour-hero">
        <div class="hero-content" style="text-align: center;">
            <h1>Tour Not Found</h1>
            <p class="tour-subtitle">The tour package you're looking for doesn't exist in our system</p>
        </div>
    </section>

    <main class="tour-details-container">
        <div class="no-tours" style="text-align: center; padding: 80px 20px;">
            <div class="no-tours-icon" style="font-size: 4rem; margin-bottom: 24px;">🗺️</div>
            <h3 style="font-size: 1.8rem; color: #1e293b; margin-bottom: 12px; font-weight: 700;">Tour Package Not Available</h3>
            <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 30px;">The tour package ID you requested does not exist or may have been removed.</p>
            <a href="tours.php" class="btn btn-primary" style="display: inline-flex; width: auto;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 12H5m7 7l-7-7 7-7" />
                </svg>
                Back to Tours
            </a>
        </div>
    </main>
<?php endif; ?>

<script>
    // Image gallery functionality
    function changeImage(src) {
        document.getElementById('mainImage').src = src;

        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        event.target.classList.add('active');
    }

    // Wishlist functionality
    document.getElementById('wishlistBtn').addEventListener('click', function() {
        const btn = this;
        btn.classList.toggle('active');

        if (btn.classList.contains('active')) {
            btn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            Added to Wishlist
        `;
            btn.style.background = '#fef2f2';
            btn.style.borderColor = '#fecaca';
            btn.style.color = '#dc2626';
        } else {
            btn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            Add to Wishlist
        `;
            btn.style.background = '';
            btn.style.borderColor = '';
            btn.style.color = '';
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

<?php
// Close the main container started in header.php
echo '</main>';

// Include the footer
include 'includes/footer.php';
?>