-- SubTrack - Simple Subscription Tracker
-- Database: subtrack_db
-- Run this file in MySQL to create the database and table

CREATE DATABASE IF NOT EXISTS subtrack_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE subtrack_db;

CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL DEFAULT 'Entertainment',
    cost DECIMAL(10,2) NOT NULL,
    billing_cycle VARCHAR(20) NOT NULL DEFAULT 'Monthly',
    renewal_date DATE NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample subscriptions
INSERT INTO subscriptions (name, category, cost, billing_cycle, renewal_date, active) VALUES
('Netflix', 'Entertainment', 15.49, 'Monthly', '2026-12-01', 1),
('Spotify', 'Music', 9.99, 'Monthly', '2026-12-05', 1),
('YouTube Premium', 'Entertainment', 13.99, 'Monthly', '2026-12-12', 1),
('Canva Pro', 'Design', 12.99, 'Monthly', '2026-12-15', 1),
('Google One', 'Storage', 1.99, 'Monthly', '2026-12-20', 1),
('Disney+', 'Entertainment', 7.99, 'Monthly', '2026-12-28', 1);
