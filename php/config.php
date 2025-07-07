<?php
// Database Configuration
// Update these values to match your MySQL setup

// Database connection settings
define('DB_CHARSET', 'utf8mb4');

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

// Load the .env file
loadEnv(__DIR__ . '/.env');


// Database connection function
function getDatabaseConnection() {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=" . DB_CHARSET;
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    try {
        $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], $options);
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
define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Birthday Website');
define('SITE_EMAIL', $_ENV['SITE_EMAIL'] ?? 'jacob.h.wolf@gmail.com');
define('SITE_URL', $_ENV['SITE_URL'] ?? 'https://localhost');
define('FORUM_POSTS_PER_PAGE', $_ENV['FORUM_POSTS_PER_PAGE'] ?? 10);
define('REQUIRE_FORUM_APPROVAL', $_ENV['REQUIRE_FORUM_APPROVAL'] === 'true');
define('MAX_UPLOAD_SIZE', $_ENV['MAX_UPLOAD_SIZE'] ?? 5242880); // 5MB default

// Production error settings
if (isset($_ENV['PHP_ERROR_REPORTING'])) {
    error_reporting($_ENV['PHP_ERROR_REPORTING']);
}

if (isset($_ENV['PHP_DISPLAY_ERRORS'])) {
    ini_set('display_errors', $_ENV['PHP_DISPLAY_ERRORS']);
}

// Production security settings
if (!isset($_ENV['DEBUG']) || $_ENV['DEBUG'] !== 'true') {
    // Hide PHP version
    header_remove('X-Powered-By');
    
    // Set secure session settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    ini_set('session.use_strict_mode', 1);
}
?>