<?php
// Links Suggestion Handler
header('Content-Type: text/html; charset=utf-8');

// Simple data storage (in a real application, use a database)
$data_file = '../data/link_suggestions.json';

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
    $your_name = htmlspecialchars(trim($_POST['your_name'] ?? ''));
    $website_url = htmlspecialchars(trim($_POST['website_url'] ?? ''));
    $website_name = htmlspecialchars(trim($_POST['website_name'] ?? ''));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $category = htmlspecialchars(trim($_POST['category'] ?? ''));
    
    // Validate required fields
    if (empty($your_name) || empty($website_url) || empty($website_name) || empty($description) || empty($category)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($website_url, FILTER_VALIDATE_URL)) {
        $error = "Please enter a valid website URL.";
    } else {
        // Create link suggestion entry
        $suggestion = [
            'id' => uniqid(),
            'your_name' => $your_name,
            'website_url' => $website_url,
            'website_name' => $website_name,
            'description' => $description,
            'category' => $category,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'approved' => false // Suggestions need approval in a real system
        ];
        
        // Load existing suggestions
        $suggestions = json_decode(file_get_contents($data_file), true);
        
        // Add new suggestion
        $suggestions[] = $suggestion;
        
        // Save to file
        if (file_put_contents($data_file, json_encode($suggestions, JSON_PRETTY_PRINT))) {
            $success = true;
        } else {
            $error = "Sorry, there was an error saving your suggestion. Please try again.";
        }
    }
}

// Map category codes to display names
$category_names = [
    'gaming' => 'Gaming & Entertainment',
    'music' => 'Music & Arts',
    'learning' => 'Learning & Reference',
    'fun' => 'Fun & Weird',
    'nostalgia' => 'Nostalgia & History',
    'other' => 'Other'
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Link Suggestion Status</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="refresh" content="5;url=../links.html">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔗 Link Suggestion Status 🔗</h1>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="welcome-message">
                <h2>Link Suggested!</h2>
                <p><strong>Thank you for your suggestion, <?php echo htmlspecialchars($your_name); ?>!</strong></p>
                <p>Your suggested website has been received and will be reviewed before being added to the links page.</p>
            </div>
            
            <div class="party-details">
                <h3>Your Suggestion Preview:</h3>
                <table>
                    <tr>
                        <th>Website Name:</th>
                        <td><?php echo htmlspecialchars($website_name); ?></td>
                    </tr>
                    <tr>
                        <th>URL:</th>
                        <td><a href="<?php echo htmlspecialchars($website_url); ?>" target="_blank"><?php echo htmlspecialchars($website_url); ?></a></td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td><?php echo htmlspecialchars($category_names[$category] ?? $category); ?></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td><?php echo htmlspecialchars($description); ?></td>
                    </tr>
                </table>
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
                <li>Your suggestion will be reviewed for quality and appropriateness</li>
                <li>If approved, it will be added to the links page</li>
                <li>You can suggest more links anytime</li>
                <li>Check back to see your suggestion on the links page</li>
            </ul>
        </div>

        <div class="marquee">
            <p><strong>🔗 THANKS FOR THE SUGGESTION! 🔗 KEEP THEM COMING! 🔗</strong></p>
        </div>

        <div class="center">
            <p><strong>You'll be redirected back to the links page in 5 seconds...</strong></p>
            <p><a href="../links.html">← Go back to Links</a></p>
            <p><a href="../index.html">← Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for helping make our links page even more awesome!</p>
        </div>
    </div>
</body>
</html>