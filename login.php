<?php
// Start a secure session to keep the author logged in
session_start();

// Include your database connection script
require_once 'db.php';

$error = "";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if fields are empty
    if (empty($username) || empty($password)) {
        $error = "Please fill in both fields.";
    } else {
        // Use a prepared statement to prevent SQL Injection security vulnerabilities
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify the password (matching plain text for our default 'password123' account)
            if ($password === $user['password']) {
                // Store user session details
                $_SESSION['author_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to the author dashboard panel
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Author Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #333; text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #666; font-size: 0.9rem; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-login { width: 100%; background: #28a745; color: white; padding: 10px; border: none; border-radius: 4px; font-size: 1rem; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .btn-login:hover { background: #218838; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.85rem; border: 1px solid #f5c6cb; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #007BFF; text-decoration: none; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Author Login</h2>
    <!-- Link to the registration page -->
<p style="text-align: center; font-size: 0.9rem; color: #666; margin-top: 15px;">
    New author? <a href="signup.php" style="color: #007BFF; text-decoration: none; font-weight: bold;">Create an account here</a>
</p>
    
    <?php if (!empty($error)): ?>
        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn-login">Sign In</button>
    </form>
    
    <a href="index.php" class="back-link">← Back to Blog Feed</a>
</div>

</body>
</html>
