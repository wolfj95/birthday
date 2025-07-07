<?php
// Gallery Photo Upload Handler
header('Content-Type: text/html; charset=utf-8');

// Simple data storage (in a real application, use a database)
$data_file = '../data/gallery_submissions.json';
$upload_dir = '../images/uploads/';

// Create data directory if it doesn't exist
if (!is_dir('../data')) {
    mkdir('../data', 0755, true);
}

// Create upload directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Initialize data file if it doesn't exist
if (!file_exists($data_file)) {
    file_put_contents($data_file, json_encode([]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $photo_title = htmlspecialchars(trim($_POST['photo_title'] ?? ''));
    $photo_description = htmlspecialchars(trim($_POST['photo_description'] ?? ''));
    $your_name = htmlspecialchars(trim($_POST['your_name'] ?? ''));
    
    // Validate required fields
    if (empty($photo_title) || empty($your_name)) {
        $error = "Please fill in the photo title and your name.";
    } else {
        // Handle file upload
        $uploaded_file = null;
        if (isset($_FILES['photo_file']) && $_FILES['photo_file']['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['photo_file']['tmp_name'];
            $file_name = $_FILES['photo_file']['name'];
            $file_size = $_FILES['photo_file']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($file_ext, $allowed_types)) {
                $error = "Only JPG, PNG, and GIF files are allowed.";
            } elseif ($file_size > 5 * 1024 * 1024) { // 5MB limit
                $error = "File size must be less than 5MB.";
            } else {
                // Generate unique filename
                $new_filename = uniqid() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $uploaded_file = $new_filename;
                } else {
                    $error = "Failed to upload file. Please try again.";
                }
            }
        }
        
        if (!isset($error)) {
            // Create gallery submission entry
            $submission = [
                'id' => uniqid(),
                'photo_title' => $photo_title,
                'photo_description' => $photo_description,
                'your_name' => $your_name,
                'filename' => $uploaded_file,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'approved' => false // Photos need approval in a real system
            ];
            
            // Load existing submissions
            $submissions = json_decode(file_get_contents($data_file), true);
            
            // Add new submission
            $submissions[] = $submission;
            
            // Save to file
            if (file_put_contents($data_file, json_encode($submissions, JSON_PRETTY_PRINT))) {
                $success = true;
            } else {
                $error = "Sorry, there was an error saving your submission. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Photo Submission Status</title>
    <link rel="stylesheet" href="../css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="refresh" content="5;url=../gallery.html">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📸 Photo Submission Status 📸</h1>
        </div>

        <?php if (isset($success) && $success): ?>
            <div class="welcome-message">
                <h2>Photo Submitted!</h2>
                <p><strong>Thank you for your submission, <?php echo htmlspecialchars($your_name); ?>!</strong></p>
                <p>Your photo "<?php echo htmlspecialchars($photo_title); ?>" has been received and will be reviewed before appearing in the gallery.</p>
            </div>
            
            <?php if ($uploaded_file): ?>
                <div class="party-details">
                    <h3>Your Photo Preview:</h3>
                    <div class="gallery">
                        <img src="../images/uploads/<?php echo htmlspecialchars($uploaded_file); ?>" alt="<?php echo htmlspecialchars($photo_title); ?>" class="screenshot">
                    </div>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($photo_title); ?></p>
                    <?php if (!empty($photo_description)): ?>
                        <p><strong>Description:</strong> <?php echo htmlspecialchars($photo_description); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
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
                <li>Your photo will be reviewed for approval</li>
                <li>Once approved, it will appear in the gallery</li>
                <li>You can submit more photos anytime</li>
                <li>Check back to see your photo in the gallery</li>
            </ul>
        </div>

        <div class="marquee">
            <p><strong>📸 THANKS FOR SHARING! 📸 KEEP THE MEMORIES COMING! 📸</strong></p>
        </div>

        <div class="center">
            <p><strong>You'll be redirected back to the gallery in 5 seconds...</strong></p>
            <p><a href="../gallery.html">← Go back to Gallery</a></p>
            <p><a href="../index.html">← Return to Homepage</a></p>
        </div>

        <div class="footer">
            <p>Thanks for adding to our awesome photo collection!</p>
        </div>
    </div>
</body>
</html>