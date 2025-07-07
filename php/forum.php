<?php
// Forum Message Handler
header('Content-Type: text/html; charset=utf-8');

// Simple data storage (in a real application, use a database)
$data_file = '../data/forum_messages.json';

// Create data directory if it doesn't exist
if (!is_dir('../data')) {
    mkdir('../data', 0755, true);
}

// Initialize data file if it doesn't exist
if (!file_exists($data_file)) {
    file_put_contents($data_file, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $author = htmlspecialchars(trim($_POST['author'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $website = htmlspecialchars(trim($_POST['website'] ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validate required fields
    if (empty($author) || empty($subject) || empty($message)) {
        $error = "Please fill in all required fields.";
    } else {
        // Create forum message entry
        $forum_message = [
            'id' => uniqid(),
            'author' => $author,
            'email' => $email,
            'website' => $website,
            'subject' => $subject,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'approved' => false // Messages need approval in a real system
        ];
        
        // Load existing messages
        $messages = json_decode(file_get_contents($data_file), true);
        
        // Add new message
        $messages[] = $forum_message;
        
        // Save to file
        if (file_put_contents($data_file, json_encode($messages, JSON_PRETTY_PRINT))) {
            $success = true;
        } else {
            $error = "Sorry, there was an error saving your message. Please try again.";
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
    <meta http-equiv="refresh" content="5;url=../forum.html">
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
            <p><strong>You'll be redirected back to the forum in 5 seconds...</strong></p>
            <p><a href="../forum.html">← Go back to Forum</a></p>
            <p><a href="../index.html">← Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for contributing to our awesome forum!</p>
        </div>
    </div>
</body>
</html>