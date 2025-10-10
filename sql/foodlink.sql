-- ----------------------------------------
-- Create Database
-- ----------------------------------------
CREATE DATABASE IF NOT EXISTS foodlink;
USE foodlink_db;

-- ----------------------------------------
-- Users Table
-- ----------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `type` ENUM('donor', 'ngo', 'admin') NOT NULL DEFAULT 'donor',
  `address` TEXT,
  `phone` VARCHAR(20),
  `photo_url` VARCHAR(255) NULL,
  `is_verified` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Donations Table
-- ----------------------------------------
CREATE TABLE IF NOT EXISTS `donations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `food_name` VARCHAR(255) NOT NULL,
  `quantity` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `photo_url` VARCHAR(255) NULL,
  `expiry_time` DATETIME NOT NULL,
  `donor_id` INT NOT NULL,
  `ngo_id` INT NULL,
  `location` VARCHAR(255),
  `status` ENUM('available', 'claimed', 'picked_up', 'completed', 'cancelled') DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `claimed_at` TIMESTAMP NULL,
  `pickup_time` DATETIME NULL,
  FOREIGN KEY (`donor_id`) REFERENCES users(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ngo_id`) REFERENCES users(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Impact Table
-- ----------------------------------------
CREATE TABLE IF NOT EXISTS `impact` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `donation_id` INT NOT NULL UNIQUE,
  `meals_provided` INT,
  `weight_kg` DECIMAL(8,2),
  `people_served` INT,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`donation_id`) REFERENCES donations(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Notifications Table
-- ----------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `message` TEXT NOT NULL,
  `type` ENUM('info','success','warning','error') DEFAULT 'info',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------
-- Sample Users
-- ----------------------------------------
INSERT IGNORE INTO `users` (`username`, `email`, `password`, `name`, `type`, `address`, `phone`, `is_verified`) VALUES
('downtown_diner', 'restaurant@example.com', '$2y$10$wTGIp3T.C5z2J.9A4wO.j.X6.L8Q1g5Z8A6B3P3G4H5I6J7K8L9M', 'Downtown Diner', 'donor', '123 Main St, Pune', '9876543210', TRUE),
('city_bakery', 'bakery@example.com', '$2y$10$wTGIp3T.C5z2J.9A4wO.j.X6.L8Q1g5Z8A6B3P3G4H5I6J7K8L9M', 'City Bakery', 'donor', '456 Oak Ave, Pune', '9876543211', TRUE),
('food_bank_pune', 'ngo@example.com', '$2y$10$wTGIp3T.C5z2J.9A4wO.j.X6.L8Q1g5Z8A6B3P3G4H5I6J7K8L9M', 'Community Food Bank', 'ngo', '789 Elm St, Pune', '9876543212', TRUE),
('hope_shelter', 'shelter@example.com', '$2y$10$wTGIp3T.C5z2J.9A4wO.j.X6.L8Q1g5Z8A6B3P3G4H5I6J7K8L9M', 'Hope Shelter', 'ngo', '321 Pine Rd, Pune', '9876543213', TRUE),
('superadmin', 'admin@foodlink.com', '$2y$10$wTGIp3T.C5z2J.9A4wO.j.X6.L8Q1g5Z8A6B3P3G4H5I6J7K8L9M', 'Super Admin', 'admin', '', '', TRUE);

-- ----------------------------------------
-- Sample Donations
-- ----------------------------------------
INSERT IGNORE INTO `donations` (`food_name`, `quantity`, `description`, `expiry_time`, `donor_id`, `ngo_id`, `location`, `status`, `claimed_at`) VALUES
('Fresh Paneer Curry and Rice', '30 portions', 'Freshly prepared paneer curry and basmati rice from today''s lunch service. Packed hygienically.', NOW() + INTERVAL 8 HOUR, 1, 3, 'Downtown Diner, 123 Main St', 'claimed', NOW()),
('Assorted Bakery Goods', '5 kg', 'Includes bread, croissants, and muffins from the morning batch. Best consumed today.', NOW() + INTERVAL 12 HOUR, 2, NULL, 'City Bakery, 456 Oak Ave', 'available', NULL),
('Bulk Lentils and Rice', '50 kg total', 'Uncooked, dry goods including masoor dal and sona masoori rice. Long shelf life.', NOW() + INTERVAL 30 DAY, 1, 4, 'Downtown Diner, 123 Main St', 'completed', NOW() - INTERVAL 1 DAY),
('Fresh Vegetable Boxes', '25 boxes', 'Slightly imperfect but fresh vegetables including tomatoes, onions, and potatoes.', NOW() + INTERVAL 2 DAY, 2, NULL, 'City Bakery, 456 Oak Ave', 'available', NULL);

-- ----------------------------------------
-- Sample Impact
-- ----------------------------------------
INSERT IGNORE INTO `impact` (`donation_id`, `meals_provided`, `weight_kg`, `people_served`, `notes`) VALUES
(3, 150, 50.00, 120, 'Distributed as dry ration kits to families in the nearby slum area.');

-- ----------------------------------------
-- Indexes
-- ----------------------------------------
CREATE INDEX IF NOT EXISTS idx_users_type ON users(type);
CREATE INDEX IF NOT EXISTS idx_donations_status ON donations(status);
CREATE INDEX IF NOT EXISTS idx_impact_donation ON impact(donation_id);
