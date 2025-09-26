-- sql/foodlink.sql
CREATE DATABASE IF NOT EXISTS foodlink;
USE foodlink;

-- Users table (for both donors and NGOs)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('donor', 'ngo') NOT NULL DEFAULT 'donor',
    address TEXT,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    food_name VARCHAR(255) NOT NULL,
    quantity VARCHAR(100) NOT NULL,
    description TEXT,
    expiry_time DATETIME,
    donor_id INT NOT NULL,
    ngo_id INT NULL,
    location VARCHAR(255),
    status ENUM('available', 'claimed', 'picked_up', 'completed', 'cancelled') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    claimed_at TIMESTAMP NULL,
    pickup_time DATETIME NULL,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (ngo_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Impact tracking table
CREATE TABLE IF NOT EXISTS impact (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    meals_provided INT,
    weight_kg DECIMAL(8,2),
    people_served INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE
);

-- Sample Data
INSERT INTO users (email, password, name, type, address, phone) VALUES
('restaurant@example.com', '$2y$10$EXAMPLEHASH', 'Downtown Restaurant', 'donor', '123 Main St, City', '555-1234'),
('bakery@example.com', '$2y$10$EXAMPLEHASH', 'City Bakery', 'donor', '456 Oak Ave, City', '555-5678'),
('ngo@example.com', '$2y$10$EXAMPLEHASH', 'Community Food Bank', 'ngo', '789 Elm St, City', '555-9012'),
('shelter@example.com', '$2y$10$EXAMPLEHASH', 'Hope Shelter', 'ngo', '321 Pine Rd, City', '555-3456');

INSERT INTO donations (food_name, quantity, description, expiry_time, donor_id, location, status) VALUES
('Fresh Baked Bread', '20 portions', 'Freshly baked bread from daily batch', NOW() + INTERVAL 1 DAY, 1, '123 Main St, City', 'available'),
('Prepared Meals', '30 portions', 'Leftover from lunch service', NOW() + INTERVAL 1 DAY, 1, '123 Main St, City', 'claimed'),
('Vegetable Boxes', '25 portions', 'Slightly imperfect but fresh vegetables', NOW() + INTERVAL 3 DAY, 2, '456 Oak Ave, City', 'completed');

INSERT INTO impact (donation_id, meals_provided, weight_kg, people_served) VALUES
(4, 25, 12.5, 20);
