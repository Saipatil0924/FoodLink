CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT, -- optional if you want to track donor
    food_item VARCHAR(255) NOT NULL,
    quantity VARCHAR(100) NOT NULL,
    description TEXT,
    pickup_time VARCHAR(100),
    status ENUM('Available','Claimed','Completed') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
