CREATE DATABASE newlink;
USE newlink;

CREATE TABLE urls (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    original_url VARCHAR(255) NOT NULL,
    short_code VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
