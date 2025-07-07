<?php
/**
 * File Permissions Setup Script for cPanel Deployment
 * Run this once after uploading files to set correct permissions
 * 
 * Usage: Upload this file and visit it in your browser once
 * Then delete this file for security
 */

// Security check - only run if accessing directly
if (basename($_SERVER['SCRIPT_NAME']) !== 'set_permissions.php') {
    die('Access denied');
}

echo "<h2>Setting File Permissions for Birthday Website</h2>\n";

// Define permission structure
$permissions = [
    // Directories (755 - readable/executable by all, writable by owner)
    'directories' => [
        '.',
        'css',
        'js',
        'php',
        'images',
        'images/uploads',
        'data'
    ],
    
    // Regular files (644 - readable by all, writable by owner)
    'files' => [
        'index.html',
        'party.html',
        'gallery.html',
        'links.html',
        'forum.php',
        '.htaccess',
        'css/style.css',
        'js/countdown.js',
        'js/gallery.js',
        'js/forum.js',
        'php/.env',
        'php/.env.example',
        'php/config.php',
        'php/rsvp.php',
        'php/forum.php',
        'php/gallery.php',
        'php/links.php',
        'php/get_forum_posts.php'
    ],
    
    // Writable directories (755 but ensure they're writable)
    'writable' => [
        'images/uploads',
        'data'
    ]
];

$success = 0;
$errors = 0;

echo "<h3>Setting Directory Permissions (755)</h3>\n";
foreach ($permissions['directories'] as $dir) {
    if (is_dir($dir)) {
        if (chmod($dir, 0755)) {
            echo "✓ Set $dir to 755<br>\n";
            $success++;
        } else {
            echo "✗ Failed to set $dir to 755<br>\n";
            $errors++;
        }
    } else {
        echo "⚠ Directory $dir not found<br>\n";
    }
}

echo "<h3>Setting File Permissions (644)</h3>\n";
foreach ($permissions['files'] as $file) {
    if (file_exists($file)) {
        if (chmod($file, 0644)) {
            echo "✓ Set $file to 644<br>\n";
            $success++;
        } else {
            echo "✗ Failed to set $file to 644<br>\n";
            $errors++;
        }
    } else {
        echo "⚠ File $file not found<br>\n";
    }
}

echo "<h3>Verifying Writable Directories</h3>\n";
foreach ($permissions['writable'] as $dir) {
    if (is_dir($dir)) {
        $perms = fileperms($dir);
        $is_writable = is_writable($dir);
        
        echo "Directory: $dir<br>\n";
        echo "Permissions: " . decoct($perms & 0777) . "<br>\n";
        echo "Writable: " . ($is_writable ? 'Yes' : 'No') . "<br>\n";
        
        if (!$is_writable) {
            echo "⚠ Warning: $dir is not writable. File uploads may fail.<br>\n";
            $errors++;
        } else {
            echo "✓ $dir is writable<br>\n";
            $success++;
        }
        echo "<br>\n";
    }
}

echo "<h3>Summary</h3>\n";
echo "Successful operations: $success<br>\n";
echo "Errors: $errors<br>\n";

if ($errors === 0) {
    echo "<div style='color: green; font-weight: bold;'>✓ All permissions set successfully!</div>\n";
} else {
    echo "<div style='color: orange; font-weight: bold;'>⚠ Some operations failed. Check file paths and server permissions.</div>\n";
}

echo "<h3>Next Steps</h3>\n";
echo "<ol>\n";
echo "<li>Test your website functionality</li>\n";
echo "<li>Submit a test RSVP to verify database connection</li>\n";
echo "<li>Post a test forum message</li>\n";
echo "<li><strong>Delete this file (set_permissions.php) for security</strong></li>\n";
echo "</ol>\n";

echo "<h3>Manual Permission Commands (if needed)</h3>\n";
echo "<p>If this script doesn't work, use these commands via SSH:</p>\n";
echo "<code>\n";
echo "find . -type d -exec chmod 755 {} \\;<br>\n";
echo "find . -type f -exec chmod 644 {} \\;<br>\n";
echo "chmod 755 images/uploads/<br>\n";
echo "chmod 755 data/<br>\n";
echo "</code>\n";

echo "<p style='margin-top: 30px; padding: 10px; background-color: #ffe6e6; border: 1px solid #ff0000;'>";
echo "<strong>Security Notice:</strong> Delete this file after running it to prevent unauthorized access.";
echo "</p>\n";
?>