<?php
// Database Configuration
// Update these values to match your MySQL setup

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'birthday_website');
define('DB_USER', 'root');  // Change to your MySQL username
define('DB_PASS', '');      // Change to your MySQL password
define('DB_CHARSET', 'utf8mb4');

// Database connection function
function getDatabaseConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        // Log error and show user-friendly message
        error_log("Database connection failed: " . $e->getMessage());
        die("Sorry, we're experiencing technical difficulties. Please try again later.");
    }
}

// Helper function to sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Helper function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Helper function to get client IP address
function getClientIP() {
    $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            $ip = $_SERVER[$key];
            if (strpos($ip, ',') !== false) {
                $ip = explode(',', $ip)[0];
            }
            $ip = trim($ip);
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

// Configuration settings
define('SITE_NAME', 'Birthday Website');
define('SITE_EMAIL', 'jacob.h.wolf@gmail.com');
define('FORUM_POSTS_PER_PAGE', 10);
define('REQUIRE_FORUM_APPROVAL', false); // Set to true to require admin approval for forum posts
?>