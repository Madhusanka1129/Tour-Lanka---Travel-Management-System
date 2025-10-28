<?php
$page_title = "Hotels & Accommodation";

require_once 'includes/header.php';

$local_placeholder_image = 'assets/images/H1.jpg';

try {
    $sql = "SELECT hotel_id, hotel_name, location, price_per_night, image FROM hotels ORDER BY hotel_id DESC";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Database query failed: " . $conn->error);
    }
} catch (Exception $e) {
    error_log("Hotels page error: " . $e->getMessage());
    $result = false;
}
?>

<!-- Hero Section -->
<section class="hotels-hero">
    <div class="hero-content">
        <h1>Find Your Perfect Stay in <br> Sri Lanka</h1>
        <p>Discover handpicked accommodations that blend comfort, luxury, and authentic Sri Lankan hospitality</p>
    </div>
</section>

<!-- Main Content -->
<main class="hotels-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>Featured Hotels & Accommodations</h2>
        <p class="subtitle">Browse our curated selection of premium stays across Sri Lanka</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-container">
            <div class="filter-group">
                <label for="price-filter">Price Range:</label>
                <select id="price-filter">
                    <option value="all">All Prices</option>
                    <option value="0-50">$0 - $50</option>
                    <option value="50-100">$50 - $100</option>
                    <option value="100-200">$100 - $200</option>
                    <option value="200+">$200+</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="location-filter">Location:</label>
                <select id="location-filter">
                    <option value="all">All Locations</option>
                    <option value="Colombo">Colombo</option>
                    <option value="Kandy">Kandy</option>
                    <option value="Galle">Galle</option>
                    <option value="Sigiriya">Sigiriya</option>
                    <option value="Ella">Ella</option>
                    <option value="Bentota">Bentota</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="rating-filter">Minimum Rating:</label>
                <select id="rating-filter">
                    <option value="0">Any Rating</option>
                    <option value="4">4+ Stars</option>
                    <option value="3">3+ Stars</option>
                    <option value="2">2+ Stars</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort-by">Sort By:</label>
                <select id="sort-by">
                    <option value="featured">Featured</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                </select>
            </div>
        </div>
        <div class="filter-results">
            <span id="results-count">Loading hotels...</span>
            <button id="reset-filters" class="btn-reset">Reset Filters</button>
        </div>
    </div>

    <!-- Hotels Grid -->
    <div class="hotels-grid">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {

                $db_image_path = 'assets/images/' . $row['image'];
                if (!empty($row['image']) && file_exists($db_image_path)) {
                    $image_path = $db_image_path;
                } else {
                    $image_path = $local_placeholder_image;
                    if (!file_exists($local_placeholder_image)) {
                        $image_path = 'https://picsum.photos/400/300?random=' . $row['hotel_id'];
                    }
                }

                $amenities = [];
                if (!empty($row['amenities'])) {
                    $amenities = json_decode($row['amenities'], true) ?: explode(',', $row['amenities']);
                }

                if (empty($amenities)) {
                    $amenities = ['WiFi', 'Pool', 'Breakfast'];
                }

                $rating = isset($row['rating']) ? $row['rating'] : 4.0;

                echo '
                <div class="hotel-card" data-price="' . $row['price_per_night'] . '" data-location="' . htmlspecialchars($row['location']) . '" data-rating="' . $rating . '">
                    <div class="card-image">
                        <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['hotel_name']) . '" loading="lazy">
                        <div class="price-tag">$' . number_format($row['price_per_night'], 0) . '<span>/night</span></div>
                        <button class="wishlist-btn" aria-label="Add to wishlist">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="card-content">
                        <div class="hotel-header">
                            <h3>' . htmlspecialchars($row['hotel_name']) . '</h3>
                            <div class="rating">
                                <div class="stars">';

                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= floor($rating)) {
                        echo '<span class="star filled">★</span>';
                    } else {
                        echo '<span class="star">★</span>';
                    }
                }

                echo '
                                </div>
                                <span class="rating-text">(' . number_format($rating, 1) . ')</span>
                            </div>
                        </div>
                        <p class="location">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                            </svg>
                            ' . htmlspecialchars($row['location']) . '
                        </p>
                        <p class="description">Experience comfort and authentic Sri Lankan hospitality at this beautiful property.</p>
                        
                        <div class="amenities">
                            <div class="amenities-list">';

                $amenityCount = 0;
                foreach ($amenities as $amenity) {
                    if ($amenityCount < 3) {
                        echo '<span class="amenity">' . htmlspecialchars(trim($amenity)) . '</span>';
                        $amenityCount++;
                    }
                }
                if (count($amenities) > 3) {
                    echo '<span class="amenity more">+' . (count($amenities) - 3) . ' more</span>';
                }

                echo '
                            </div>
                        </div>
                        
                        <div class="card-actions">
                            <a href="hotel_details.php?id=' . $row['hotel_id'] . '" class="btn btn-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                    <path d="M12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                View Details
                            </a>
                            <a href="booking.php?hotel_id=' . $row['hotel_id'] . '" class="btn btn-primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2z"/>
                                </svg>
                                Book Now
                            </a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="no-hotels">
                    <div class="no-hotels-icon">🏨</div>
                    <h3>No hotels available at the moment</h3>
                    <p>We\'re working on adding new properties. Please check back soon!</p>
                    <a href="contact.php" class="btn btn-primary">Contact Us</a>
                  </div>';
        }
        ?>
    </div>
</main>

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

    /* Hero Section */
    .hotels-hero {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 100px 20px 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hotels-hero::before {
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
    .hotels-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin: 50px 0 40px;
    }

    .page-header h2 {
        font-size: 2.5rem;
        color: #1e293b;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .subtitle {
        color: #64748b;
        font-size: 1.2rem;
        font-weight: 400;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
        border: 1px solid #f1f5f9;
    }

    .filter-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .filter-group label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
    }

    .filter-group select {
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: white;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .filter-group select:focus {
        outline: none;
        border-color: #009fc7;
        box-shadow: 0 0 0 3px rgba(0, 159, 199, 0.1);
    }

    .filter-results {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #f1f5f9;
    }

    #results-count {
        color: #64748b;
        font-weight: 500;
    }

    .btn-reset {
        background: none;
        border: 1px solid #e2e8f0;
        color: #64748b;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .btn-reset:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Hotels Grid */
    .hotels-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }

    /* Hotel Card */
    .hotel-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #f1f5f9;
    }

    .hotel-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .card-image {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .hotel-card:hover .card-image img {
        transform: scale(1.08);
    }

    .price-tag {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #009fc7, #0681a0);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .price-tag span {
        font-size: 0.7rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .wishlist-btn {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .wishlist-btn:hover {
        background: white;
        transform: scale(1.1);
    }

    .wishlist-btn svg {
        color: #64748b;
    }

    .wishlist-btn.active svg {
        color: #e63946;
        fill: #e63946;
    }

    .card-content {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .hotel-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .hotel-header h3 {
        font-size: 1.3rem;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.3;
        margin-right: 10px;
    }

    .rating {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        flex-shrink: 0;
    }

    .stars {
        display: flex;
        gap: 2px;
    }

    .star {
        color: #cbd5e1;
        font-size: 1rem;
    }

    .star.filled {
        color: #f59e0b;
    }

    .rating-text {
        color: #64748b;
        font-size: 0.8rem;
    }

    .location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        margin-bottom: 15px;
        font-size: 0.95rem;
    }

    .location svg {
        color: #009fc7;
    }

    .description {
        color: #64748b;
        margin-bottom: 20px;
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .amenities {
        margin-bottom: 20px;
    }

    .amenities-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .amenity {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .amenity.more {
        background: #e0f2fe;
        color: #009fc7;
    }

    .card-actions {
        display: flex;
        gap: 12px;
        margin-top: auto;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        text-align: center;
        transition: all 0.3s ease;
        flex: 1;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #009fc7, #0681a0);
        color: white;
        box-shadow: 0 4px 12px rgba(0, 159, 199, 0.3);
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

    /* No Hotels Message */
    .no-hotels {
        grid-column: 1 / -1;
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
        .hotels-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1.1rem;
        }

        .filter-container {
            grid-template-columns: 1fr;
        }

        .hotels-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .card-actions {
            flex-direction: column;
        }

        .filter-results {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .hotel-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .rating {
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .hotels-hero {
            padding: 80px 15px 60px;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hotels-container {
            padding: 0 15px;
        }

        .page-header h2 {
            font-size: 2rem;
        }

        .filter-section {
            padding: 20px;
        }
    }
</style>

<script>
    // Enhanced filtering functionality for hotels
    document.addEventListener('DOMContentLoaded', function() {
        const priceFilter = document.getElementById('price-filter');
        const locationFilter = document.getElementById('location-filter');
        const ratingFilter = document.getElementById('rating-filter');
        const sortBy = document.getElementById('sort-by');
        const resetBtn = document.getElementById('reset-filters');
        const resultsCount = document.getElementById('results-count');
        const hotelCards = document.querySelectorAll('.hotel-card');

        // Initialize results count
        updateResultsCount(hotelCards.length);

        // Filter functions
        priceFilter.addEventListener('change', applyFilters);
        locationFilter.addEventListener('change', applyFilters);
        ratingFilter.addEventListener('change', applyFilters);

        // Sort function
        sortBy.addEventListener('change', function() {
            applyFilters();
            sortCards();
        });

        // Reset filters
        resetBtn.addEventListener('click', function() {
            priceFilter.value = 'all';
            locationFilter.value = 'all';
            ratingFilter.value = '0';
            sortBy.value = 'featured';
            applyFilters();
            sortCards();
        });

        function applyFilters() {
            const priceValue = priceFilter.value;
            const locationValue = locationFilter.value;
            const ratingValue = parseFloat(ratingFilter.value);
            let visibleCount = 0;

            hotelCards.forEach(card => {
                const price = parseFloat(card.getAttribute('data-price'));
                const location = card.getAttribute('data-location');
                const rating = parseFloat(card.getAttribute('data-rating'));

                let priceMatch = false;
                let locationMatch = false;
                let ratingMatch = false;

                if (priceValue === 'all') {
                    priceMatch = true;
                } else if (priceValue === '0-50' && price <= 50) {
                    priceMatch = true;
                } else if (priceValue === '50-100' && price > 50 && price <= 100) {
                    priceMatch = true;
                } else if (priceValue === '100-200' && price > 100 && price <= 200) {
                    priceMatch = true;
                } else if (priceValue === '200+' && price > 200) {
                    priceMatch = true;
                }

                if (locationValue === 'all') {
                    locationMatch = true;
                } else if (location === locationValue) {
                    locationMatch = true;
                }

                if (ratingValue === 0) {
                    ratingMatch = true;
                } else if (rating >= ratingValue) {
                    ratingMatch = true;
                }

                if (priceMatch && locationMatch && ratingMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            updateResultsCount(visibleCount);
        }

        function sortCards() {
            const value = sortBy.value;
            const grid = document.querySelector('.hotels-grid');
            const cards = Array.from(hotelCards).filter(card => card.style.display !== 'none');

            if (value === 'featured') {} else if (value === 'price-low') {
                cards.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                });
            } else if (value === 'price-high') {
                cards.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceB - priceA;
                });
            } else if (value === 'rating') {
                cards.sort((a, b) => {
                    const ratingA = parseFloat(a.getAttribute('data-rating'));
                    const ratingB = parseFloat(b.getAttribute('data-rating'));
                    return ratingB - ratingA;
                });
            }

            cards.forEach(card => {
                grid.appendChild(card);
            });
        }

        function updateResultsCount(count) {
            if (count === 0) {
                resultsCount.textContent = 'No hotels match your filters';
            } else if (count === 1) {
                resultsCount.textContent = 'Showing 1 hotel';
            } else {
                resultsCount.textContent = `Showing ${count} hotels`;
            }
        }

        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');

                if (this.classList.contains('active')) {
                    this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                `;
                } else {
                    this.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                `;
                }
            });
        });

        applyFilters();
    });
</script>

<?php
echo '</main>';

include 'includes/footer.php';
?>