<?php $page_title = "Home";
require_once 'includes/header.php';
?>

<section class="hero">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>Discover the Pearl of the <br> Sri Lanka </h1>
            <p>Experience breathtaking landscapes, rich culture, and unforgettable adventures in Sri Lanka</p>
            <div class="hero-buttons">
                <a href="tours.php" class="btn btn-primary">Explore Tours</a>
                <a href="hotels.php" class="btn btn-secondary">Find Hotels</a>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Why Choose Tour Sri Lanka?</h2>
        <p class="section-subtitle">We provide exceptional travel experiences with local expertise</p>

        <div class="feature-grid">
            <div class="feature-item">
                <div class="feature-icon">🏛️</div>
                <h3>Cultural Heritage</h3>
                <p>Explore ancient temples, historical sites, and traditional villages</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🏞️</div>
                <h3>Natural Beauty</h3>
                <p>Discover stunning beaches, lush mountains, and wildlife sanctuaries</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🍛</div>
                <h3>Authentic Cuisine</h3>
                <p>Taste traditional Sri Lankan dishes and cooking experiences</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🛌</div>
                <h3>Luxury Stays</h3>
                <p>Stay at carefully selected hotels and boutique accommodations</p>
            </div>
        </div>
    </div>
</section>

<section class="popular-destinations">
    <div class="container">
        <h2>Popular Destinations</h2>
        <p class="section-subtitle">Explore Sri Lanka's most breathtaking locations</p>

        <div class="destinations-grid">
            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img01.jpg');">
                    <div class="destination-overlay">
                        <h3>Sigiriya Rock Fortress</h3>
                    </div>
                </div>
                <p>Ancient rock fortress with stunning views and historical significance</p>
            </div>

            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img02.jpeg');">
                    <div class="destination-overlay">
                        <h3>Ella Rock</h3>
                    </div>
                </div>
                <p>Beautiful hiking trails and panoramic views of hill country</p>
            </div>

            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img03.jpg');">
                    <div class="destination-overlay">
                        <h3>Galle Fort</h3>
                    </div>
                </div>
                <p>Historic fort with colonial architecture and coastal charm</p>
            </div>

            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img04.jpg');">
                    <div class="destination-overlay">
                        <h3>Yala National Park</h3>
                    </div>
                </div>
                <p>Wildlife safari adventures and leopard spotting opportunities</p>
            </div>

            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img05.jpg');">
                    <div class="destination-overlay">
                        <h3>Kandy</h3>
                    </div>
                </div>
                <p>Sacred city with the Temple of the Tooth Relic and cultural performances</p>
            </div>

            <div class="destination-card">
                <div class="destination-image" style="background-image: url('assets/images/img06.jpg');">
                    <div class="destination-overlay">
                        <h3>Mirissa</h3>
                    </div>
                </div>
                <p>Pristine beaches, whale watching, and stunning coastal sunsets</p>
            </div>

        </div>
    </div>
</section>

<section class="featured-tours">
    <div class="container">
        <h2>Featured Tour Packages</h2>
        <p class="section-subtitle">Handpicked holidays crafted for unforgettable experiences</p>

        <div class="card-grid">
            <?php
            // Check if database connection exists
            if (!isset($conn)) {
                echo '<div class="error-message">Database connection not available.</div>';
            } else {
                // Fetch 2 featured tours
                $sql = "SELECT tour_id, tour_name, description, price, image FROM tours LIMIT 2";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Shorten description for card view
                        $short_desc = substr($row['description'] ?? '', 0, 100) . '...';
                        $image_path = (!empty($row['image']) && file_exists($row['image'])) ? $row['image'] : 'https://images.unsplash.com/photo-1571718986049-8c5c85abb5a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                        $duration = $row['duration'] ?? 'Not specified';
                        $price = isset($row['price']) ? number_format($row['price'], 2) : '0.00';

                        echo '
                <div class="card">
                    <div class="card-image">
                        <img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($row['tour_name']) . '" loading="lazy">
                        <div class="card-badge">Featured</div>
                        <div class="tour-duration">' . htmlspecialchars($duration) . '</div>
                    </div>
                    <div class="card-content">
                        <h3>' . htmlspecialchars($row['tour_name']) . '</h3>
                        <p>' . htmlspecialchars($short_desc) . '</p>
                        <div class="card-footer">
                            <p class="price">$' . $price . '</p>
                            <a href="tour_details.php?id=' . $row['tour_id'] . '" class="btn btn-card">View Details</a>
                        </div>
                    </div>
                </div>';
                    }
                } else {
                    // Fallback demo cards - always show these for testing
                    echo '
            <div class="card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1571718986049-8c5c85abb5a9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Cultural Triangle Tour" loading="lazy">
                    <div class="card-badge">Best Seller</div>
                    <div class="tour-duration">7 Days</div>
                </div>
                <div class="card-content">
                    <h3>Cultural Triangle Tour</h3>
                    <p>Explore ancient kingdoms, royal palaces, and sacred temples in Sri Lanka\'s cultural heartland.</p>
                    <div class="card-footer">
                        <p class="price">$899.00</p>
                        <a href="tours.php" class="btn btn-card">View Details</a>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-image">
                    <img src="https://images.unsplash.com/photo-1544644181-1484c5a6b748?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Beach Paradise Tour" loading="lazy">
                    <div class="card-badge">Popular</div>
                    <div class="tour-duration">5 Days</div>
                </div>
                <div class="card-content">
                    <h3>Beach Paradise Tour</h3>
                    <p>Relax on pristine beaches, enjoy water sports, and experience coastal culture in southern Sri Lanka.</p>
                    <div class="card-footer">
                        <p class="price">$649.00</p>
                        <a href="tours.php" class="btn btn-card">View Details</a>
                    </div>
                </div>
            </div>';
                }
            }
            ?>
        </div>
        <div class="section-cta">
            <a href="tours.php" class="btn btn-outline">View All Tours</a>
        </div>
    </div>
</section>

<section class="testimonials">
    <div class="container">
        <h2>What Our Travelers Say</h2>
        <div class="testimonial-grid">
            <div class="testimonial">
                <div class="testimonial-content">
                    <div class="rating">★★★★★</div>
                    <p>"Tour Lanka made our Sri Lankan vacation absolutely perfect. The guides were knowledgeable and the itinerary was well-planned. The cultural experiences were authentic and unforgettable."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar" style="background-image: url('assets/images/faceicon.png');"></div>
                    <div class="author-info">
                        <h4>Rohan Rathnayaka</h4>
                        <p>Sri Lankan Taveler</p>
                    </div>
                </div>
            </div>
            <div class="testimonial">
                <div class="testimonial-content">
                    <div class="rating">★★★★★</div>
                    <p>"The wildlife safari in Yala National Park was incredible! We spotted leopards, elephants, and so much more. The beach extensions were the perfect way to relax after our adventures."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar" style="background-image: url('assets/images/faceicon.png');"></div>
                    <div class="author-info">
                        <h4>Maneesha Madhuwanthi</h4>
                        <p>Sri Lankan Taveler</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready for Your Sri Lankan Adventure?</h2>
            <p>Contact us today to plan your perfect itinerary</p>
            <div class="cta-buttons">
                <a href="contact.php" class="btn btn-primary">Get Custom Quote</a>
                <a href="tel:+94112345678" class="btn btn-secondary">Call Now</a>
            </div>
        </div>
    </div>
</section>

<?php
// Close main tag from header.php
echo '</main>';
// Footer HTML
include 'includes/footer.php';
?>