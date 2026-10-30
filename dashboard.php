<?php
// Start the session to read logged-in user data
session_start();

// Include the database connection file
require_once 'db.php';

// Security Wall: If the user is not logged in, redirect them immediately to the login page
if (!isset($_SESSION['author_id'])) {
    header("Location: login.php");
    exit;
}

$author_id = $_SESSION['author_id'];
$username = $_SESSION['username'];

// Fetch only the posts created by this specific logged-in author
$stmt = $conn->prepare("SELECT id, title, created_at FROM posts WHERE author_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 900px; margin: 0 auto; padding: 20px; background-color: #f4f6f9; color: #333; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #ccc; padding-bottom: 15px; margin-bottom: 30px; background: #fff; padding: 15px 20px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        h1 { margin: 0; font-size: 1.8rem; }
        .welcome-msg { font-size: 1.1rem; }
        .welcome-msg strong { color: #28a745; }
        .nav-links a { margin-left: 15px; text-decoration: none; font-weight: bold; }
        .btn-create { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; font-weight: bold; }
        .btn-create:hover { background: #218838; }
        .btn-logout { color: #dc3545; }
        .btn-view { color: #007BFF; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eaeaea; }
        th { background-color: #343a40; color: white; }
        tr:hover { background-color: #f8f9fa; }
        .actions a { margin-right: 10px; text-decoration: none; font-size: 0.9rem; font-weight: bold; }
        .edit-link { color: #ffc107; }
        .delete-link { color: #dc3545; }
        .no-data { text-align: center; padding: 30px; color: #777; background: #fff; border-radius: 6px; }
    </style>
</head>
<body>

<header>
    <div class="welcome-msg">Welcome back, <strong><?php echo htmlspecialchars($username); ?></strong>!</div>
    <div class="nav-links">
        <a href="index.php" class="btn-view" target="_blank">View Live Blog ↗</a>
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</header>

<main>
    <h1>Manage Your Articles</h1>
    <p>From here you can write new stories or modify your existing publications.</p>
    
    <!-- Action link to write a new blog post -->
    <a href="create_post.php" class="btn-create">+ Create New Post</a>

    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                        <td><?php echo date('M d, Y @ g:i a', strtotime($row['created_at'])); ?></td>
                        <td class="actions">
                            <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="edit-link">Edit</a>
                            <a href="delete_post.php?id=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Are you completely sure you want to delete this post permanent?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <h3>You haven't written any posts yet.</h3>
            <p>Click the green button above to get your thoughts published online!</p>
        </div>
    <?php endif; ?>
</main>

<?php $stmt->close(); ?>
</body>
</html>
