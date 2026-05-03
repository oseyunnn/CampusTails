CREATE DATABASE IF NOT EXISTS campustails;
USE campustails;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('guest', 'student', 'admin') DEFAULT 'guest'
);

CREATE TABLE pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    species VARCHAR(50),
    description TEXT,
    date_found DATE,
    health_status VARCHAR(50),
    location_found VARCHAR(100),
    last_vaccine DATE,
    next_due DATE,
    vet_info VARCHAR(255)
);

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(50), -- CREATE, UPDATE, DELETE
    target_pet VARCHAR(100),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);