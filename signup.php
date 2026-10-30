<?php
require_once 'db.php';
$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if username already exists in the database
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Username is already taken.";
        } else {
            // Securely insert the new user profile setup
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);
            
            if ($stmt->execute()) {
                $message = "Registration successful! You can now log in.";
            } else {
                $error = "Registration failed. Try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .signup-container { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin: 8px 0 20px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-register { width: 100%; background: #007BFF; color: white; padding: 10px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .btn-register:hover { background: #0056b3; }
        .error { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; }
        .success { color: #155724; background: #d4edda; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; }
        .link { display: block; text-align: center; margin-top: 15px; color: #007BFF; text-decoration: none; }
    </style>
</head>
<body>

<div class="signup-container">
    <h2>Create Author Account</h2>
    
    <?php if(!empty($error)): ?><div class="error"><?php echo $error; ?></div><?php endif; ?>
    <?php if(!empty($message)): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>

    <form action="signup.php" method="POST">
        <label><strong>Username</strong></label>
        <input type="text" name="username" required>

        <label><strong>Password</strong></label>
        <input type="password" name="password" required>

        <button type="submit" class="btn-register">Register Account</button>
    </form>
    
    <a href="login.php" class="link">Go to Login Page →</a>
</div>

</body>
</html>
