<?php
// Forum Message Handler
header('Content-Type: text/html; charset=utf-8');

// Include database configuration
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $author = sanitizeInput($_POST['author'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $website = sanitizeInput($_POST['website'] ?? '');
    $subject = sanitizeInput($_POST['subject'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($author) || empty($subject) || empty($message)) {
        $error = "Please fill in all required fields.";
    } elseif (strlen($message) < 10) {
        $error = "Your message must be at least 10 characters long.";
    } elseif (strlen($message) > 1000) {
        $error = "Your message must be less than 1000 characters long.";
    } elseif (!empty($email) && !isValidEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $error = "Please enter a valid website URL.";
    } else {
        try {
            // Get database connection
            $pdo = getDatabaseConnection();
            
            // Insert forum message into database
            $sql = "INSERT INTO forum_posts (author, email, website, subject, message, approved, ip_address) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $result = $stmt->execute([
                $author,
                $email ?: null,
                $website ?: null,
                $subject,
                $message,
                !REQUIRE_FORUM_APPROVAL, // Auto-approve if REQUIRE_FORUM_APPROVAL is false
                getClientIP()
            ]);
            
            if ($result) {
                $success = true;
                $message_id = $pdo->lastInsertId();
            } else {
                $error = "Sorry, there was an error saving your message. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Forum Database Error: " . $e->getMessage());
            $error = "Sorry, we're experiencing technical difficulties. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forum Message Posted</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="refresh" content="30;url=../forum.php">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Forum Message Status 💬</h1>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="welcome-message">
                <h2>Message Posted!</h2>
                <p><strong>Thank you for your message, <?php echo htmlspecialchars($author); ?>!</strong></p>
                <p>Your message has been received and will be reviewed before appearing on the forum.</p>
            </div>
            
            <div class="party-details">
                <h3>Your Message Preview:</h3>
                <div class="forum-post">
                    <div class="author">📝 <?php echo htmlspecialchars($author); ?></div>
                    <div class="date"><?php echo date('F j, Y - g:i A'); ?></div>
                    <div class="subject"><strong><?php echo htmlspecialchars($subject); ?></strong></div>
                    <div class="content">
                        <p><?php echo nl2br(htmlspecialchars($message)); ?></p>
                    </div>
                </div>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="construction">
                <h2>Oops!</h2>
                <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
                <p>Please go back and try again.</p>
            </div>
        <?php endif; ?>

        <div class="link-section">
            <h3>What happens next?</h3>
            <ul>
                <li>Your message will be reviewed for approval</li>
                <li>Once approved, it will appear on the forum</li>
                <li>You can post more messages anytime</li>
                <li>Check back to see replies from other visitors</li>
            </ul>
        </div>

        <div class="marquee">
            <p><strong>💬 THANKS FOR POSTING! 💬 COME BACK SOON! 💬</strong></p>
        </div>

        <div class="center">
            <p><strong>You'll be redirected back to the forum in 30 seconds...</strong></p>
            <p><a href="../forum.php">← Go back to Forum</a></p>
            <p><a href="../index.html">← Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for contributing to our awesome forum!</p>
        </div>
    </div>
</body>
</html>