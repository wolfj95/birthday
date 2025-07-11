-- Birthday Website Database Schema
-- Run this SQL script to create the necessary tables

-- CREATE DATABASE IF NOT EXISTS birthday_website;
-- USE birthday_website;

CREATE DATABASE IF NOT EXISTS wolfiede_birthday_db;
USE wolfiede_birthday_db;

-- RSVP Table
CREATE TABLE IF NOT EXISTS rsvps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    attending ENUM('yes', 'no') NOT NULL,
    guests INT DEFAULT 0,
    nights ENUM('friday', 'saturday', 'both', 'none'),
    transportation ENUM('has_car', 'can_drive_others', 'need_ride', 'other_transport', 'not_sure'),
    dietary TEXT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    INDEX idx_email (email),
    INDEX idx_attending (attending),
    INDEX idx_created_at (created_at)
);

-- Forum Posts Table
CREATE TABLE IF NOT EXISTS forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    website VARCHAR(255),
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    approved BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    INDEX idx_author (author),
    INDEX idx_approved (approved),
    INDEX idx_created_at (created_at)
);

-- Create a sample admin user (optional)
-- You can use this to approve forum posts
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
-- Insert some sample forum posts for testing
INSERT INTO forum_posts (author, email, subject, message, approved, created_at) VALUES
('🎂 WebMaster', 'jacob@example.com', 'Welcome to the Forum!', 'Thanks for visiting my birthday website! This forum is where you can share your thoughts, memories, and birthday wishes. I''m so excited to read what you have to say!\n\nDon''t forget to RSVP for the party if you haven''t already. See you there!', TRUE, '2025-01-15 15:42:00'),
('🌟 RetroFan1995', 'retro@example.com', 'Love the 90s Theme!', 'This website totally takes me back to the good old days of the web! The design is so authentic - it''s like stepping into a time machine. 🕰️\n\nHappy early birthday! Can''t wait to party like it''s 1995! 🎉', TRUE, '2025-01-14 20:21:00'),
('🎮 GamerDude', 'gamer@example.com', 'Remember These Games?', '1995 was such a great year for gaming! Super Mario World 2, Chrono Trigger, Command & Conquer... those were the days!\n\nAre you planning any retro gaming at the party? I''d love to bring my old console! 🎮', TRUE, '2025-01-13 14:15:00'),
('🎵 MusicLover', 'music@example.com', '90s Music Playlist Request', 'Please tell me you''re going to play some classic 90s hits at the party! I''m talking Alanis Morissette, TLC, Boyz II Men, Green Day...\n\nI have a whole playlist ready if you need suggestions! This is going to be epic! 🎵', TRUE, '2025-01-12 11:47:00'),
('📷 PhotoBuff', 'photos@example.com', 'Great Photo Gallery!', 'Just checked out your 1995 photo gallery - what a trip down memory lane! The world looked so different back then.\n\nI have some photos from 1995 too. Maybe I can share them at the party? 📸', TRUE, '2025-01-11 16:33:00');

-- Create an index for better performance on frequently queried columns
CREATE INDEX idx_forum_approved_created ON forum_posts (approved, created_at DESC);