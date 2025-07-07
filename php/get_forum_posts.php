<?php
// Get Forum Posts from Database
header('Content-Type: application/json');

// Include database configuration
require_once 'config.php';

try {
    // Get database connection
    $pdo = getDatabaseConnection();
    
    // Get pagination parameters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = FORUM_POSTS_PER_PAGE;
    $offset = ($page - 1) * $limit;
    
    // Get total count of approved posts
    $countSql = "SELECT COUNT(*) FROM forum_posts WHERE approved = 1";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute();
    $totalPosts = $countStmt->fetchColumn();
    
    // Get forum posts with pagination
    $sql = "SELECT id, author, subject, message, created_at, website 
            FROM forum_posts 
            WHERE approved = 1 
            ORDER BY created_at DESC 
            LIMIT ? OFFSET ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    $posts = $stmt->fetchAll();
    
    // Format posts for display
    $formattedPosts = [];
    foreach ($posts as $post) {
        $formattedPosts[] = [
            'id' => $post['id'],
            'author' => htmlspecialchars($post['author']),
            'subject' => htmlspecialchars($post['subject']),
            'message' => nl2br(htmlspecialchars($post['message'])),
            'date' => date('F j, Y - g:i A', strtotime($post['created_at'])),
            'website' => $post['website'] ? htmlspecialchars($post['website']) : null
        ];
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'posts' => $formattedPosts,
        'totalPosts' => $totalPosts,
        'currentPage' => $page,
        'totalPages' => ceil($totalPosts / $limit),
        'hasNextPage' => $page < ceil($totalPosts / $limit),
        'hasPrevPage' => $page > 1
    ]);
    
} catch (PDOException $e) {
    error_log("Forum Posts Database Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Unable to load forum posts. Please try again later.'
    ]);
}
?>