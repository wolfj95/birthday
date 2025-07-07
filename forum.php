<?php
// Forum Page with Database Integration
require_once 'php/config.php';

// Get forum posts from database
try {
    $pdo = getDatabaseConnection();
    
    // Get total count of approved posts
    $countSql = "SELECT COUNT(*) FROM forum_posts WHERE approved = 1";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $totalPosts = $countStmt->fetchColumn();
    
    // Get recent forum posts (limit to 10 for display)
    $sql = "SELECT id, author, subject, message, created_at, website 
            FROM forum_posts 
            WHERE approved = 1 
            ORDER BY created_at DESC 
            LIMIT 10";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $recentPosts = $stmt->fetchAll();
    
    // Get latest message date
    $latestSql = "SELECT MAX(created_at) FROM forum_posts WHERE approved = 1";
    $latestStmt = $pdo->prepare($latestSql);
    $latestStmt->execute();
    $latestDate = $latestStmt->fetchColumn();
    
} catch (PDOException $e) {
    error_log("Forum Database Error: " . $e->getMessage());
    $recentPosts = [];
    $totalPosts = 0;
    $latestDate = null;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Birthday Message Forum</title>
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Birthday Message Forum 💬</h1>
            <p><em>Share your birthday wishes and memories!</em></p>
            <p><a href="index.html">← Back to Homepage</a></p>
        </div>

        <div class="welcome-message">
            <h2>Welcome to My Birthday Forum!</h2>
            <p>This is where you can leave me birthday messages, share memories, or just chat with other visitors to my site! Think of it as a digital guestbook for the Information Age.</p>
            <p>Please be nice and keep it fun - this is a celebration after all! 🎉</p>
        </div>

        <div class="marquee">
            <marquee><p><strong>💬 NEW MESSAGES WELCOME! 💬 SHARE YOUR THOUGHTS! 💬</strong></p></marquee>
        </div>

        <div class="form-section">
            <h3>📝 Post a New Message</h3>
            <form action="php/forum.php" method="POST">
                <table>
                    <tr>
                        <td><label for="author">Your Name:</label></td>
                        <td><input type="text" id="author" name="author" required size="30"></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email (optional):</label></td>
                        <td><input type="email" id="email" name="email" size="30"></td>
                    </tr>
                    <tr>
                        <td><label for="website">Website (optional):</label></td>
                        <td><input type="url" id="website" name="website" size="40" placeholder="http://"></td>
                    </tr>
                    <tr>
                        <td><label for="subject">Subject:</label></td>
                        <td><input type="text" id="subject" name="subject" required size="40"></td>
                    </tr>
                    <tr>
                        <td><label for="message">Message:</label></td>
                        <td><textarea id="message" name="message" required rows="6" cols="50" placeholder="Share your birthday wishes, memories, or just say hello!"></textarea></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;">
                            <input type="submit" value="Post Message">
                            <input type="reset" value="Clear Form">
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="construction">
            <p><strong>📋 FORUM RULES 📋</strong></p>
            <ul style="text-align: left; display: inline-block;">
                <li>Keep messages friendly and appropriate</li>
                <li>No spam or advertising</li>
                <li>Share your favorite memories or birthday wishes</li>
                <li>HTML tags are not allowed in messages</li>
                <?php if (REQUIRE_FORUM_APPROVAL): ?>
                <li>Messages are moderated before appearing</li>
                <?php else: ?>
                <li>Messages appear immediately (please be respectful!)</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="party-details">
            <h2>📨 Recent Messages</h2>
            <p><em>Messages from visitors like you!</em></p>
            
            <?php if (!empty($recentPosts)): ?>
                <?php foreach ($recentPosts as $post): ?>
                    <div class="forum-post">
                        <div class="author">💬 <?php echo htmlspecialchars($post['author']); ?></div>
                        <div class="date"><?php echo date('F j, Y - g:i A', strtotime($post['created_at'])); ?></div>
                        <div class="subject"><strong><?php echo htmlspecialchars($post['subject']); ?></strong></div>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($post['message'])); ?>
                        </div>
                        <?php if (!empty($post['website'])): ?>
                            <div class="website">
                                <small>Website: <a href="<?php echo htmlspecialchars($post['website']); ?>" target="_blank"><?php echo htmlspecialchars($post['website']); ?></a></small>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="forum-post">
                    <div class="author">🎂 WebMaster</div>
                    <div class="date">July 8, 2025 - 12:00 PM</div>
                    <div class="subject"><strong>Welcome to the Forum!</strong></div>
                    <div class="content">
                        <p>Thanks for visiting my birthday website! This forum is where you can share your thoughts, memories, and birthday wishes. I'm so excited to read what you have to say!</p>
                        <p>Don't forget to RSVP for the party if you haven't already. See you there!</p>
                    </div>
                </div>
                
                <div class="construction">
                    <p><strong>🔍 NO MESSAGES YET 🔍</strong></p>
                    <p>Be the first to post a message! Share your birthday wishes or memories from 1995.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="link-section">
            <h3>Forum Statistics</h3>
            <table>
                <tr>
                    <th>Total Messages:</th>
                    <td><?php echo $totalPosts; ?></td>
                </tr>
                <tr>
                    <th>Latest Message:</th>
                    <td><?php echo $latestDate ? date('F j, Y', strtotime($latestDate)) : 'No messages yet'; ?></td>
                </tr>
                <tr>
                    <th>Forum Status:</th>
                    <td><span style="color: green;">Online</span></td>
                </tr>
                <tr>
                    <th>Approval Required:</th>
                    <td><?php echo REQUIRE_FORUM_APPROVAL ? 'Yes' : 'No'; ?></td>
                </tr>
            </table>
        </div>

        <div class="center">
            <h3>💡 Message Ideas</h3>
            <p>Not sure what to write? Here are some suggestions:</p>
            <ul style="text-align: left; display: inline-block;">
                <li>Share your favorite memory from 1995</li>
                <li>Tell me what you're most excited about for the party</li>
                <li>Recommend something cool from the 90s</li>
                <li>Ask a question about the party or website</li>
                <li>Just say hello and introduce yourself!</li>
            </ul>
        </div>

        <div class="footer">
            <p><a href="index.html">Home</a> | <a href="party.html">Party & RSVP</a> | <a href="gallery.html">Photo Gallery</a> | <a href="links.html">Cool Links</a></p>
            <p>Forum powered by PHP and good vibes since 2025</p>
            <p>Best viewed in Netscape Navigator 2.0 or Internet Explorer 3.0</p>
            <p>© 2025 Jacob Wolf | All rights reserved</p>
        </div>
    </div>

    <script src="js/forum.js"></script>
</body>
</html>