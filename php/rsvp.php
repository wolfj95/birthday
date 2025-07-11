<?php
// RSVP Form Handler
header('Content-Type: text/html; charset=utf-8');

// Include database configuration
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $name = sanitizeInput($_POST['name'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $attending = sanitizeInput($_POST['attending'] ?? '');
    $guests = intval($_POST['guests'] ?? 0);
    $nights = isset($_POST['nights']) ? $_POST['nights'] : [];
    $transportation = sanitizeInput($_POST['transportation'] ?? '');
    $dietary = isset($_POST['dietary']) ? $_POST['dietary'] : [];
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($attending)) {
        $error = "Please fill in all required fields.";
    } elseif (!isValidEmail($email)) {
        $error = "Please enter a valid email address.";
    } elseif (!in_array($attending, ['yes', 'no'])) {
        $error = "Please select whether you will attend or not.";
    } else {
        try {
            // Get database connection
            $pdo = getDatabaseConnection();
            
            // Check if email already exists
            $checkStmt = $pdo->prepare("SELECT id FROM rsvps WHERE email = ?");
            $checkStmt->execute([$email]);
            
            if ($checkStmt->rowCount() > 0) {
                $error = "An RSVP with this email address already exists. Please contact us if you need to update your RSVP.";
            } else {
                // Process nights checkboxes into ENUM value
                $nightsEnum = 'none';
                if (!empty($nights)) {
                    $hasFriday = in_array('friday', $nights);
                    $hasSaturday = in_array('saturday', $nights);
                    
                    if ($hasFriday && $hasSaturday) {
                        $nightsEnum = 'both';
                    } elseif ($hasFriday) {
                        $nightsEnum = 'friday';
                    } elseif ($hasSaturday) {
                        $nightsEnum = 'saturday';
                    }
                }
                
                // Convert dietary array to JSON string
                $dietaryJson = !empty($dietary) ? json_encode($dietary) : null;
                
                // Insert RSVP into database
                $sql = "INSERT INTO rsvps (name, email, phone, attending, guests, nights, transportation, dietary, message, ip_address) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    $name,
                    $email,
                    $phone,
                    $attending,
                    $guests,
                    $nightsEnum,
                    $transportation,
                    $dietaryJson,
                    $message,
                    getClientIP()
                ]);
                
                if ($result) {
                    $success = true;
                    $rsvp_id = $pdo->lastInsertId();
                } else {
                    $error = "Sorry, there was an error saving your RSVP. Please try again.";
                }
            }
        } catch (PDOException $e) {
            error_log("RSVP Database Error: " . $e->getMessage());
            $error = "Sorry, we're experiencing technical difficulties. Please try again later.";
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
            <h1><img src="../images/party.gif" alt="Party" width="25" height="25"> RSVP Status <img src="../images/party.gif" alt="Party" width="25" height="25"></h1>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="welcome-message">
                <h2>Thank You!</h2>
                <p><strong>Your RSVP has been received!</strong></p>
                <p>Thanks for responding, <?php echo htmlspecialchars($name); ?>!</p>
                
                <?php if ($attending === 'yes'): ?>
                    <p><img src="../images/party-hat.gif" alt="Party" width="20" height="20"> <strong>We're excited to see you at the party!</strong></p>
                    <?php if ($guests > 0): ?>
                        <p>We've noted that you're bringing <?php echo $guests; ?> guest(s).</p>
                    <?php endif; ?>
                    <?php if (!empty($nights)): ?>
                        <p>We've noted that you'll be staying: <?php echo implode(', ', array_map('htmlspecialchars', $nights)); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($transportation)): ?>
                        <p>Transportation: <?php echo htmlspecialchars(str_replace('_', ' ', $transportation)); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($dietary)): ?>
                        <p>We've noted your dietary preferences: <?php echo implode(', ', array_map('htmlspecialchars', $dietary)); ?></p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>:,( <strong>Sorry you can't make it!</strong> We'll miss you!</p>
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
                <li>You'll receive a confirmation email eventually (but maybe don't hold your breath)</li>
                <li>Check back here for party updates</li>
                <li>Feel free to post on the forum if you have questions</li>
            </ul>
        </div>

        <div class="center">
            <p><strong>You'll be redirected back to the party page in 30 seconds...</strong></p>
            <p><a href="../party.html">&larr; Go back to Party Details</a></p>
            <p><a href="../index.html">&larr; Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for using our totally rad RSVP system!</p>
        </div>
    </div>
</body>
</html>