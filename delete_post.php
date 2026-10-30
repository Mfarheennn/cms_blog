<?php
session_start();
require_once 'db.php';

// Verify author validation status
if (isset($_SESSION['author_id']) && isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $author_id = $_SESSION['author_id'];

    // Execute deletion sequence targeting only rows belonging explicitly to this user
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ? AND author_id = ?");
    $stmt->bind_param("ii", $post_id, $author_id);
    $stmt->execute();
    $stmt->close();
}

// Seamless dashboard snapback routing layout
header("Location: dashboard.php");
exit;
