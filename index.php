<?php
// Include the database connection file
require_once 'db.php';

// Fetch all posts alongside their author usernames, newest first
$sql = "SELECT posts.*, users.username FROM posts 
        JOIN users ON posts.author_id = users.id 
        ORDER BY posts.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Blog Feed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; color: #333; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 30px; }
        h1 { margin: 0; font-size: 2rem; }
        .post { background: #fff; padding: 25px; margin-bottom: 25px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border: 1px solid #eaeaea; }
        .post h2 { margin-top: 0; color: #111; font-size: 1.5rem; }
        .meta { font-size: 0.85rem; color: #666; margin-bottom: 15px; }
        .meta strong { color: #007BFF; }
        .content { font-size: 1.05rem; white-space: pre-line; }
        .login-btn { background: #007BFF; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; transition: background 0.2s; }
        .login-btn:hover { background: #0056b3; }
        .no-posts { text-align: center; padding: 40px; background: #fff; border-radius: 6px; border: 1px dashed #ccc; color: #666; }
    </style>
</head>
<body>

<header>
    <h1>Welcome to the Blog</h1>
    <!-- Navigates to the author login portal -->
    <a href="login.php" class="login-btn">Author Login</a>
</header>

<main>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <article class="post">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <div class="meta">
                    Published by <strong><?php echo htmlspecialchars($row['username']); ?></strong> 
                    on <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>
                </div>
                <div class="content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></div>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-posts">
            <h3>No posts published yet.</h3>
            <p>Click "Author Login" at the top to sign in and write the very first article!</p>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
