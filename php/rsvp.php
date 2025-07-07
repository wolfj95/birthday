<?php
// RSVP Form Handler
header('Content-Type: text/html; charset=utf-8');

// Simple data storage (in a real application, use a database)
$data_file = '../data/rsvps.json';

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
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $attending = htmlspecialchars(trim($_POST['attending'] ?? ''));
    $guests = intval($_POST['guests'] ?? 0);
    $dietary = isset($_POST['dietary']) ? $_POST['dietary'] : [];
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($attending)) {
        $error = "Please fill in all required fields.";
    } else {
        // Create RSVP entry
        $rsvp = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'attending' => $attending,
            'guests' => $guests,
            'dietary' => $dietary,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        // Load existing RSVPs
        $rsvps = json_decode(file_get_contents($data_file), true);
        
        // Add new RSVP
        $rsvps[] = $rsvp;
        
        // Save to file
        if (file_put_contents($data_file, json_encode($rsvps, JSON_PRETTY_PRINT))) {
            $success = true;
        } else {
            $error = "Sorry, there was an error saving your RSVP. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>RSVP Confirmation</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="refresh" content="30;url=../party.html">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 RSVP Status 🎉</h1>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="welcome-message">
                <h2>Thank You!</h2>
                <p><strong>Your RSVP has been received!</strong></p>
                <p>Thanks for responding, <?php echo htmlspecialchars($name); ?>!</p>
                
                <?php if ($attending === 'yes'): ?>
                    <p>🎉 <strong>We're excited to see you at the party!</strong></p>
                    <?php if ($guests > 0): ?>
                        <p>We've noted that you're bringing <?php echo $guests; ?> guest(s).</p>
                    <?php endif; ?>
                    <?php if (!empty($dietary)): ?>
                        <p>We've noted your dietary preferences: <?php echo implode(', ', $dietary); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>😢 <strong>Sorry you can't make it!</strong> We'll miss you!</p>
                <?php endif; ?>
                
                <?php if (!empty($message)): ?>
                    <p><strong>Your message:</strong> "<?php echo htmlspecialchars($message); ?>"</p>
                <?php endif; ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="construction">
                <h2>Oops!</h2>
                <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
                <p>Please go back and try again.</p>
            </div>
        <?php endif; ?>

        <div class="party-details">
            <p><strong>What happens next?</strong></p>
            <ul>
                <li>You'll receive a confirmation email shortly</li>
                <li>Check back here for party updates</li>
                <li>Feel free to post on the forum if you have questions</li>
            </ul>
        </div>

        <div class="center">
            <p><strong>You'll be redirected back to the party page in 30 seconds...</strong></p>
            <p><a href="../party.html">← Go back to Party Details</a></p>
            <p><a href="../index.html">← Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for using our totally rad RSVP system!</p>
        </div>
    </div>
</body>
</html>