-- Database Schema for Product Reservation System

-- Create the database
CREATE DATABASE IF NOT EXISTS product_reservation_system;
USE product_reservation_system;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create reservations table
CREATE TABLE IF NOT EXISTS reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'canceled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample product data
INSERT INTO products (name, quantity, description) VALUES
('MacBook Pro 14"', 25, 'Apple MacBook Pro with M2 Pro chip, 16GB RAM, 512GB SSD'),
('Dell XPS 15', 18, 'Intel Core i7, 32GB RAM, 1TB SSD, NVIDIA RTX 3050'),
('iPad Pro 12.9"', 40, 'Apple iPad Pro with M2 chip, 256GB storage, WiFi + Cellular'),
('Sony WH-1000XM5', 50, 'Wireless Noise Cancelling Headphones with 30-hour battery life'),
('Samsung Galaxy S23 Ultra', 35, '512GB Storage, 12GB RAM, 200MP Camera System'),
('Nintendo Switch OLED', 20, 'OLED Model with enhanced audio and 64GB storage'),
('Canon EOS R6', 15, 'Mirrorless Full-Frame Camera with 20MP sensor and 4K video'),
('LG C2 65" OLED TV', 10, '4K Smart OLED TV with AI ThinQ, Dolby Vision and Dolby Atmos'),
('Herman Miller Aeron Chair', 8, 'Ergonomic Office Chair with PostureFit SL and adjustable arms'),
('DJI Mini 3 Pro', 12, 'Lightweight drone with 4K/60fps video and 34-minute flight time');

-- Insert sample reservation data
INSERT INTO reservations (product_id, name, quantity, status) VALUES
(1, 'John Doe', 2, 'confirmed'),
(3, 'Jane Smith', 1, 'pending'),
(5, 'Mike Johnson', 1, 'confirmed'),
(2, 'Sarah Williams', 3, 'pending'),
(4, 'David Brown', 2, 'confirmed');