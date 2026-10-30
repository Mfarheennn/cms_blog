<?php
session_start();
require_once 'db.php';

// Kick the user out if they try to access this page without logging in
if (!isset($_SESSION['author_id'])) {
    header("Location: login.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $author_id = $_SESSION['author_id'];

    if (empty($title) || empty($content)) {
        $error = "Please fill in all fields.";
    } else {
        // Insert the new blog post securely using a prepared statement
        $stmt = $conn->prepare("INSERT INTO posts (author_id, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $author_id, $title, $content);
        
        if ($stmt->execute()) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; max-width: 700px; margin: 40px auto; padding: 20px; }
        .form-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        input[type="text"], textarea { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 250px; font-family: Arial, sans-serif; resize: vertical; }
        .btn-submit { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; }
        .btn-cancel { color: #666; text-decoration: none; margin-left: 15px; }
        .error { color: #dc3545; margin-bottom: 15px; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Write a New Article</h2>
    <?php if(!empty($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    
    <form action="create_post.php" method="POST">
        <label><strong>Article Title</strong></label>
        <input type="text" name="title" placeholder="Enter post title here..." required>
        
        <label><strong>Body Content</strong></label>
        <textarea name="content" placeholder="Type your story or post content here..." required></textarea>
        
        <button type="submit" class="btn-submit">Publish Post</button>
        <a href="dashboard.php" class="btn-cancel">Cancel and Go Back</a>
    </form>
</div>
</body>
</html>
