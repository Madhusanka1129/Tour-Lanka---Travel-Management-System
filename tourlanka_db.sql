-- tourlanka_db.sql
-- Database Name: tourlanka_db

-- 1. Create Database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS tourlanka_db;
USE tourlanka_db;

------------------------------------------------------
-- 2. Table: users - Stores customer registration details
------------------------------------------------------
CREATE TABLE users (
    user_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL, -- To store hashed password
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

------------------------------------------------------
-- 3. Table: tours - Stores available tour package details
------------------------------------------------------
CREATE TABLE tours (
    tour_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    tour_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255)  -- Path/URL to the tour image
);

------------------------------------------------------
-- 4. Table: hotels - Stores available hotel details
------------------------------------------------------
CREATE TABLE hotels (
    hotel_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    hotel_name VARCHAR(150) NOT NULL,
    location VARCHAR(100),
    price_per_night DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) -- Path/URL to the hotel image
);

------------------------------------------------------
-- 5. Table: admin - Stores admin user credentials for panel login
------------------------------------------------------
CREATE TABLE admin (
    admin_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL -- To store hashed password
);

------------------------------------------------------
-- 6. Table: bookings - Stores all customer booking records
------------------------------------------------------
CREATE TABLE bookings (
    booking_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    tour_id INT(11) NULL,       -- NULL if it's a hotel booking
    hotel_id INT(11) NULL,      -- NULL if it's a tour booking
    booking_date DATE NOT NULL, -- The date the customer wants the tour/stay
    status VARCHAR(50) DEFAULT 'Pending', -- e.g., 'Pending', 'Confirmed', 'Cancelled'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Keys to link bookings to users, tours, and hotels
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE SET NULL,
    FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id) ON DELETE SET NULL,
    
    -- Check constraint (MySQL does not enforce CHECK constraints until version 8.0.16, 
    -- but it's good practice to include it conceptually:
    -- At least one of tour_id or hotel_id must be provided
    CHECK (tour_id IS NOT NULL OR hotel_id IS NOT NULL)
);

------------------------------------------------------
-- 7. Insert Initial Data
------------------------------------------------------

-- Admin User: username 'admin', password 'admin'
INSERT INTO admin (username, password) VALUES 
('admin', 'admin');

-- Sample Tours
INSERT INTO tours (tour_name, description, price, image) VALUES 
('Cultural Triangle Wonders', 'Explore the ancient cities of Anuradhapura, Polonnaruwa, and climb the majestic Sigiriya Rock Fortress. Includes accommodation and transfers.', 250.00, 'images/tour_sigiriya.jpg'),
('Southern Beach Bliss', 'Relax on the golden sands of Mirissa and Unawatuna, with an optional morning whale watching boat trip.', 180.00, 'images/tour_mirissa.jpg'),
('Kandy Hill Country Escapes', 'A scenic journey through tea plantations, visiting the Temple of the Tooth Relic and the Royal Botanical Gardens.', 195.50, 'images/tour_kandy.jpg');

-- Sample Hotels
INSERT INTO hotels (hotel_name, location, price_per_night, image) VALUES 
('The Heritage Colombo', 'Colombo', 85.00, 'images/hotel_colombo.jpg'),
('Kandy Lakeside Retreat', 'Kandy', 60.00, 'images/hotel_kandy.jpg'),
('Galle Fort Boutique Stay', 'Galle', 110.00, 'images/hotel_galle.jpg');

-- Sample User (Password: 123)
INSERT INTO users (name, email, phone, password) VALUES
('John Doe', 'john@example.com', '0771234567', '123');

-- Sample Booking (User John Doe booking the Cultural Triangle Tour)
INSERT INTO bookings (user_id, tour_id, hotel_id, booking_date, status) VALUES
(1, 1, NULL, '2025-01-15', 'Confirmed');

-- Sample Booking (User John Doe booking the Galle Fort Hotel)
INSERT INTO bookings (user_id, tour_id, hotel_id, booking_date, status) VALUES
(1, NULL, 3, '2025-02-20', 'Pending');




