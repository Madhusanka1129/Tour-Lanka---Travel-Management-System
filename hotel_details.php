<?php
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $page_title = "Error";
    echo '<h1 style="color: red; margin-top: 40px;"> Error: No hotel selected.</h1>';
    echo '<p><a href="hotels.php">Go back to Hotels List</a></p>';
    echo '</main>';
    include 'includes/footer.php';
    exit();
}

$hotel_id = intval($_GET['id']);
$hotel = null;
$error_message = '';

try {
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    $stmt = $conn->prepare("SELECT hotel_id, hotel_name, location, price_per_night, image, description FROM hotels WHERE hotel_id = ?");

    if ($stmt === false) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $bind_result = $stmt->bind_param("i", $hotel_id);
    if ($bind_result === false) {
        throw new Exception("Bind failed: " . $stmt->error);
    }

    $execute_result = $stmt->execute();
    if ($execute_result === false) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hotel = $result->fetch_assoc();
        $page_title = $hotel['hotel_name'];

        $local_placeholder_image = 'assets/images/H1.jpg';
        $db_image_path = 'assets/images/' . $hotel['image'];

        if (!empty($hotel['image']) && file_exists($db_image_path)) {
            $image_path = $db_image_path;
        } else {
            $image_path = $local_placeholder_image;
            if (!file_exists($local_placeholder_image)) {
                $image_path = 'https://picsum.photos/1200/800?random=' . $hotel['hotel_id'];
            }
        }
    } else {
        $page_title = "Not Found";
        $hotel = null;
    }

    $stmt->close();
} catch (Exception $e) {
    error_log("Hotel details error: " . $e->getMessage());
    $page_title = "Database Error";
    $hotel = null;
    $error_message = "Unable to load hotel details. Please try again later.";
}
?>

<style>
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

    .hotel-hero {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 80px 20px 60px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .hotel-hero::before {
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

    .hotel-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        margin-bottom: 30px;
        max-width: 600px;
        font-weight: 400;
        line-height: 1.5;
    }

    .hotel-meta-grid {
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
    .hotel-details-container {
        max-width: 1200px;
        margin: 0 auto 60px;
        padding: 0 20px;
    }

    .hotel-content-grid {
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

    /* Room Types */
    .room-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }

    .room-card {
        background: #f8fafc;
        padding: 25px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .room-card:hover {
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .room-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .room-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
    }

    .room-price {
        font-size: 1.3rem;
        font-weight: 800;
        color: #009fc7;
    }

    .room-features {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 15px 0;
    }

    .room-feature {
        background: #e0f2fe;
        color: #009fc7;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
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

    /* Buttons - Consistent with hotels.php */
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

    .wishlist-btn.active {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
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

    /* Error States */
    .no-hotels {
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .no-hotels-icon {
        font-size: 4rem;
        margin-bottom: 24px;
    }

    .no-hotels h3 {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .no-hotels p {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .hotel-content-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .booking-sidebar {
            position: static;
        }

        .hotel-meta-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hotel-hero {
            padding: 60px 15px 40px;
        }

        .hero-content h1 {
            font-size: 2.2rem;
        }

        .hotel-subtitle {
            font-size: 1.1rem;
        }

        .hotel-meta-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .content-section {
            padding: 20px;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .room-grid {
            grid-template-columns: 1fr;
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
        .hotel-details-container {
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

<?php if ($hotel): ?>
    <section class="hotel-hero">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hotel['hotel_name']); ?></h1>
            <p class="hotel-subtitle">Experience luxury and authentic Sri Lankan hospitality at this beautiful property</p>

            <div class="hotel-meta-grid">
                <div class="meta-card">
                    <div class="meta-icon">⭐</div>
                    <div class="meta-label">Rating</div>
                    <div class="meta-value">4.8/5</div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">📍</div>
                    <div class="meta-label">Location</div>
                    <div class="meta-value"><?php echo htmlspecialchars($hotel['location']); ?></div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">🏨</div>
                    <div class="meta-label">Type</div>
                    <div class="meta-value">Luxury Hotel</div>
                </div>

                <div class="meta-card">
                    <div class="meta-icon">🛏️</div>
                    <div class="meta-label">Rooms</div>
                    <div class="meta-value">25+</div>
                </div>
            </div>

            <div class="price-badge">
                $<?php echo number_format($hotel['price_per_night'], 0); ?>/night
            </div>
        </div>
    </section>

    <main class="hotel-details-container">
        <div class="hotel-content-grid">
            <!-- Main Content -->
            <div class="hotel-main-content">
                <!-- Image Gallery -->
                <div class="image-gallery">
                    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($hotel['hotel_name']); ?>" class="main-image" id="mainImage">
                    <div class="image-thumbnails">
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="Thumbnail 1" class="thumbnail active" onclick="changeImage(this.src)">
                        <img src="https://picsum.photos/400/300?random=1" alt="Thumbnail 2" class="thumbnail" onclick="changeImage(this.src)">
                        <img src="https://picsum.photos/400/300?random=2" alt="Thumbnail 3" class="thumbnail" onclick="changeImage(this.src)">
                        <img src="https://picsum.photos/400/300?random=3" alt="Thumbnail 4" class="thumbnail" onclick="changeImage(this.src)">
                    </div>
                </div>

                <!-- Hotel Description -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" />
                            <path d="M14 2v6h6" />
                        </svg>
                        About This Property
                    </h3>
                    <div class="description">
                        <?php echo nl2br(htmlspecialchars($hotel['description'] ?? 'Experience comfort and authentic Sri Lankan hospitality at this beautiful property. Our hotel offers premium accommodations with modern amenities, exceptional service, and a perfect blend of traditional charm and contemporary luxury.')); ?>
                    </div>
                </div>

                <!-- Amenities & Features -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Hotel Amenities
                    </h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon">🏊</div>
                            <div class="feature-text">Swimming Pool</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🍽️</div>
                            <div class="feature-text">Restaurant & Bar</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">📶</div>
                            <div class="feature-text">Free WiFi</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🚗</div>
                            <div class="feature-text">Free Parking</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">💼</div>
                            <div class="feature-text">Business Center</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🏋️</div>
                            <div class="feature-text">Fitness Center</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🧳</div>
                            <div class="feature-text">Concierge Service</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🧼</div>
                            <div class="feature-text">Daily Housekeeping</div>
                        </div>
                    </div>
                </div>

                <!-- Room Types -->
                <div class="content-section">
                    <h3 class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 13h1v7c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-7h1V6c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v7z" />
                        </svg>
                        Room Types
                    </h3>
                    <div class="room-grid">
                        <div class="room-card">
                            <div class="room-header">
                                <div class="room-name">Deluxe Room</div>
                                <div class="room-price">$<?php echo number_format($hotel['price_per_night'] * 1.2, 0); ?></div>
                            </div>
                            <p>Spacious room with garden view, king-size bed, and modern amenities.</p>
                            <div class="room-features">
                                <span class="room-feature">35 m²</span>
                                <span class="room-feature">Garden View</span>
                                <span class="room-feature">Free WiFi</span>
                            </div>
                        </div>

                        <div class="room-card">
                            <div class="room-header">
                                <div class="room-name">Executive Suite</div>
                                <div class="room-price">$<?php echo number_format($hotel['price_per_night'] * 1.5, 0); ?></div>
                            </div>
                            <p>Luxurious suite with separate living area, balcony, and premium amenities.</p>
                            <div class="room-features">
                                <span class="room-feature">55 m²</span>
                                <span class="room-feature">Sea View</span>
                                <span class="room-feature">Private Balcony</span>
                            </div>
                        </div>

                        <div class="room-card">
                            <div class="room-header">
                                <div class="room-name">Family Room</div>
                                <div class="room-price">$<?php echo number_format($hotel['price_per_night'] * 1.8, 0); ?></div>
                            </div>
                            <p>Perfect for families with connecting rooms and child-friendly amenities.</p>
                            <div class="room-features">
                                <span class="room-feature">65 m²</span>
                                <span class="room-feature">2 Bedrooms</span>
                                <span class="room-feature">Family Friendly</span>
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
                        Guest Reviews
                    </h3>
                    <div class="reviews-grid">
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name">Sarah Johnson</div>
                                <div class="review-date">March 2024</div>
                            </div>
                            <div class="review-rating">★★★★★</div>
                            <div class="review-text">"Absolutely stunning property! The service was exceptional and the rooms were spacious and clean. Will definitely return!"</div>
                        </div>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name">Mike Chen</div>
                                <div class="review-date">February 2024</div>
                            </div>
                            <div class="review-rating">★★★★☆</div>
                            <div class="review-text">"Great location and beautiful facilities. The pool area was fantastic and the staff were very helpful throughout our stay."</div>
                        </div>
                        <div class="review-card">
                            <div class="review-header">
                                <div class="reviewer-name">Emma Davis</div>
                                <div class="review-date">January 2024</div>
                            </div>
                            <div class="review-rating">★★★★★</div>
                            <div class="review-text">"Perfect blend of modern comfort and traditional charm. The food at the restaurant was exceptional. Highly recommended!"</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="booking-sidebar">
                <div class="booking-card">
                    <div class="price-display">
                        <div class="price-amount">$<?php echo number_format($hotel['price_per_night'], 0); ?></div>
                        <div class="price-period">per night</div>
                    </div>

                    <div class="booking-features">
                        <div class="booking-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Free cancellation
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
                            No booking fees
                        </div>
                        <div class="booking-feature">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Instant confirmation
                        </div>
                    </div>

                    <a href="booking.php?hotel_id=<?php echo $hotel['hotel_id']; ?>" class="btn btn-primary">
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

                    <div class="hotel-reference">
                        <p style="text-align: center; color: #64748b; font-size: 0.9rem; margin-top: 20px;">
                            Hotel ID: <strong>#HOTEL<?php echo $hotel['hotel_id']; ?></strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2 class="cta-title">Ready to Experience Luxury?</h2>
            <p class="cta-subtitle">Book your stay now and create unforgettable memories at this exquisite property</p>
            <div class="cta-buttons">
                <a href="booking.php?hotel_id=<?php echo $hotel['hotel_id']; ?>" class="btn btn-primary">
                    Book Your Stay
                </a>
                <a href="contact.php" class="btn btn-outline">
                    Contact Hotel
                </a>
            </div>
        </div>
    </main>

<?php else: ?>
    <?php if (!empty($error_message)): ?>
        <!-- Database Error -->
        <section class="hotel-hero">
            <div class="hero-content" style="text-align: center;">
                <h1>Database Error</h1>
                <p class="hotel-subtitle"><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        </section>
        <main class="hotel-details-container">
            <div class="no-hotels">
                <div class="no-hotels-icon">🚫</div>
                <h3>Database Connection Issue</h3>
                <p>There was a problem connecting to the database. Please try again later.</p>
                <a href="hotels.php" class="btn btn-primary" style="display: inline-flex; width: auto;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 12H5m7 7l-7-7 7-7" />
                    </svg>
                    Back to Hotels
                </a>
            </div>
        </main>
    <?php else: ?>
        <!-- Hotel Not Found -->
        <section class="hotel-hero">
            <div class="hero-content" style="text-align: center;">
                <h1>Hotel Not Found</h1>
                <p class="hotel-subtitle">The hotel you're looking for doesn't exist in our system</p>
            </div>
        </section>
        <main class="hotel-details-container">
            <div class="no-hotels">
                <div class="no-hotels-icon">🏨</div>
                <h3>Hotel Not Available</h3>
                <p>The hotel ID you requested does not exist or may have been removed.</p>
                <a href="hotels.php" class="btn btn-primary" style="display: inline-flex; width: auto;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 12H5m7 7l-7-7 7-7" />
                    </svg>
                    Back to Hotels
                </a>
            </div>
        </main>
    <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        const wishlistBtn = document.getElementById('wishlistBtn');
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', function() {
                const btn = this;
                btn.classList.toggle('active');

                if (btn.classList.contains('active')) {
                    btn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Added to Wishlist
                `;
                } else {
                    btn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Add to Wishlist
                `;
                }
            });
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