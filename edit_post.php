<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['author_id'])) {
    header("Location: login.php");
    exit;
}

$author_id = $_SESSION['author_id'];
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the specific post details, ensuring it belongs to the logged-in author
$stmt = $conn->prepare("SELECT title, content FROM posts WHERE id = ? AND author_id = ?");
$stmt->bind_param("ii", $post_id, $author_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

// Secure Wall: If the post doesn't exist or doesn't belong to this user, block access
if (!$post) {
    die("Access Denied: You do not have permission to modify this post.");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Fields cannot be left blank.";
    } else {
        // Update the post content details inside the database
        $update_stmt = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND author_id = ?");
        $update_stmt->bind_param("ssii", $title, $content, $post_id, $author_id);
        
        if ($update_stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Failed to update article.";
        }
        $update_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Article</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; max-width: 700px; margin: 40px auto; padding: 20px; }
        .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        input[type="text"], textarea { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 250px; font-family: Arial, sans-serif; resize: vertical; }
        .btn-save { background: #007BFF; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        .btn-cancel { color: #666; text-decoration: none; margin-left: 15px; }
        .error { color: #dc3545; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Article Entry</h2>
    <?php if(!empty($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    
    <form action="" method="POST">
        <label><strong>Article Title</strong></label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        
        <label><strong>Body Content</strong></label>
        <textarea name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
        
        <button type="submit" class="btn-save">Save Changes</button>
        <a href="dashboard.php" class="btn-cancel">Cancel</a>
    </form>
</div>
</body>
</html>
