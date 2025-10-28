<?php
$page_title = "Tour Packages";
require_once 'includes/header.php';

// Fetch all tours from the database
$sql = "SELECT tour_id, tour_name, description, price, image FROM tours";
$result = $conn->query($sql);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Discover Amazing Destinations</h1>
        <p>Explore our carefully curated tour packages designed for unforgettable experiences</p>
        <div class="hero-scroll-indicator">
            <span>Explore Tours</span>
            <div class="scroll-arrow"></div>
        </div>
    </div>
</section>

<!-- Main Content -->
<main class="main-container">
    <div class="page-header">
        <h1>Explore Our Tour Packages</h1>
        <p class="subtitle">Find the perfect adventure for your next getaway</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-container">
            <div class="filter-group">
                <label for="price-filter">Price Range:</label>
                <select id="price-filter">
                    <option value="all">All Prices</option>
                    <option value="0-500">$0 - $500</option>
                    <option value="500-1000">$500 - $1,000</option>
                    <option value="1000-2000">$1,000 - $2,000</option>
                    <option value="2000+">$2,000+</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="sort-by">Sort By:</label>
                <select id="sort-by">
                    <option value="name">Name</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="duration-filter">Duration:</label>
                <select id="duration-filter">
                    <option value="all">Any Duration</option>
                    <option value="1-3">1-3 Days</option>
                    <option value="4-7">4-7 Days</option>
                    <option value="8-14">8-14 Days</option>
                    <option value="15+">15+ Days</option>
                </select>
            </div>
        </div>
        <div class="filter-results">
            <span id="results-count">Showing all tours</span>
            <button id="reset-filters" class="btn-reset">Reset Filters</button>
        </div>
    </div>

    <!-- Tour Cards -->
    <div class="card-grid">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $short_desc = substr($row['description'], 0, 100) . '...';
                $image_path = file_exists($row['image']) ? $row['image'] : 'https://picsum.photos/400/300?random=' . $row['tour_id'];

                echo '
                <div class="tour-card" data-price="' . $row['price'] . '" data-name="' . htmlspecialchars($row['tour_name']) . '">
                    <div class="card-image">
                        <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['tour_name']) . '" loading="lazy">
                        <div class="price-tag">$' . number_format($row['price'], 0) . '</div>
                        <div class="card-overlay">
                            <button class="wishlist-btn" aria-label="Add to wishlist">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3>' . htmlspecialchars($row['tour_name']) . '</h3>
                        <div class="rating">
                            <div class="stars">
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star filled">★</span>
                                <span class="star">★</span>
                            </div>
                            <span class="rating-text">(4.0)</span>
                        </div>
                        <p class="description">' . htmlspecialchars($short_desc) . '</p>
                        <div class="card-features">
                            <span class="feature">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
                                </svg>
                                5 Destinations
                            </span>
                            <span class="feature">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M22 9.24l-7.19-.62L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24z"/>
                                </svg>
                                Guided
                            </span>
                        </div>
                        <div class="card-actions">
                            <a href="tour_details.php?id=' . $row['tour_id'] . '" class="btn btn-outline">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
                                    <path d="M12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                                View Details
                            </a>
                            <a href="booking.php?tour_id=' . $row['tour_id'] . '" class="btn btn-primary">
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
            echo '<div class="no-tours">
                    <div class="no-tours-icon">✈️</div>
                    <h3>No tour packages found</h3>
                    <p>Check back later for new adventures!</p>
                    <a href="#" class="btn btn-primary">Browse All Destinations</a>
                  </div>';
        }
        ?>
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
        scroll-behavior: smooth;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #009fc7 0%, #0681a0 50%, #073455 100%);
        color: white;
        padding: 100px 20px 80px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
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
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 800;
        line-height: 1.1;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-content p {
        font-size: 1.3rem;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.95;
        font-weight: 400;
        line-height: 1.5;
    }

    .hero-scroll-indicator {
        margin-top: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        opacity: 0.8;
        animation: bounce 2s infinite;
    }

    .hero-scroll-indicator span {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .scroll-arrow {
        width: 20px;
        height: 20px;
        border-right: 2px solid white;
        border-bottom: 2px solid white;
        transform: rotate(45deg);
    }

    @keyframes bounce {

        0%,
        20%,
        50%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-10px);
        }

        60% {
            transform: translateY(-5px);
        }
    }

    /* Main Container */
    .main-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Page Header */
    .page-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .page-header h1 {
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
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 180px;
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

    /* Card Grid */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }

    /* Tour Card */
    .tour-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        border: 1px solid #f1f5f9;
    }

    .tour-card:hover {
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

    .tour-card:hover .card-image img {
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
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .card-overlay {
        position: absolute;
        top: 15px;
        left: 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .tour-card:hover .card-overlay {
        opacity: 1;
    }

    .wishlist-btn {
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

    .card-content {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .card-content h3 {
        font-size: 1.4rem;
        margin-bottom: 12px;
        color: #1e293b;
        font-weight: 700;
        line-height: 1.3;
    }

    .rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
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
        font-size: 0.9rem;
    }

    .description {
        color: #64748b;
        margin-bottom: 20px;
        flex-grow: 1;
        line-height: 1.5;
    }

    .card-features {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .feature {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
        color: #475569;
        background: #f8fafc;
        padding: 6px 12px;
        border-radius: 20px;
    }

    .feature svg {
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

    /* No Tours Message */
    .no-tours {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #f1f5f9;
    }

    .no-tours-icon {
        font-size: 4rem;
        margin-bottom: 24px;
    }

    .no-tours h3 {
        font-size: 1.8rem;
        color: #1e293b;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .no-tours p {
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .card-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
        }

        .filter-group {
            min-width: auto;
        }

        .card-grid {
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
    }

    @media (max-width: 480px) {
        .hero-section {
            padding: 80px 15px 60px;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .main-container {
            padding: 0 15px;
        }

        .page-header h1 {
            font-size: 2rem;
        }

        .filter-section {
            padding: 20px;
        }
    }
</style>

<script>
    // Enhanced filtering functionality
    document.addEventListener('DOMContentLoaded', function() {
        const priceFilter = document.getElementById('price-filter');
        const sortBy = document.getElementById('sort-by');
        const durationFilter = document.getElementById('duration-filter');
        const resetBtn = document.getElementById('reset-filters');
        const resultsCount = document.getElementById('results-count');
        const tourCards = document.querySelectorAll('.tour-card');

        // Initialize results count
        updateResultsCount(tourCards.length);

        // Price filter function
        priceFilter.addEventListener('change', applyFilters);
        durationFilter.addEventListener('change', applyFilters);

        // Sort function
        sortBy.addEventListener('change', function() {
            applyFilters();
            sortCards();
        });

        // Reset filters
        resetBtn.addEventListener('click', function() {
            priceFilter.value = 'all';
            durationFilter.value = 'all';
            sortBy.value = 'name';
            applyFilters();
            sortCards();
        });

        function applyFilters() {
            const priceValue = priceFilter.value;
            const durationValue = durationFilter.value;
            let visibleCount = 0;

            tourCards.forEach(card => {
                const price = parseFloat(card.getAttribute('data-price'));
                let priceMatch = false;
                let durationMatch = true; // Default to true since we don't have duration data

                // Price filtering
                if (priceValue === 'all') {
                    priceMatch = true;
                } else if (priceValue === '0-500' && price <= 500) {
                    priceMatch = true;
                } else if (priceValue === '500-1000' && price > 500 && price <= 1000) {
                    priceMatch = true;
                } else if (priceValue === '1000-2000' && price > 1000 && price <= 2000) {
                    priceMatch = true;
                } else if (priceValue === '2000+' && price > 2000) {
                    priceMatch = true;
                }

                // Show card if it matches all filters
                if (priceMatch && durationMatch) {
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
            const grid = document.querySelector('.card-grid');
            const cards = Array.from(tourCards).filter(card => card.style.display !== 'none');

            if (value === 'name') {
                cards.sort((a, b) => {
                    const nameA = a.getAttribute('data-name').toLowerCase();
                    const nameB = b.getAttribute('data-name').toLowerCase();
                    return nameA.localeCompare(nameB);
                });
            } else if (value === 'price-low') {
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
            }

            // Re-append cards in sorted order
            cards.forEach(card => {
                grid.appendChild(card);
            });
        }

        function updateResultsCount(count) {
            if (count === 0) {
                resultsCount.textContent = 'No tours match your filters';
            } else if (count === 1) {
                resultsCount.textContent = 'Showing 1 tour';
            } else {
                resultsCount.textContent = `Showing ${count} tours`;
            }
        }

        // Wishlist functionality
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
    });
</script>

<?php
include 'includes/footer.php';
?>