<?php
session_start();
include '../config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $conn = db_connect();

    $query = "SELECT * FROM users WHERE username='$user' AND password=MD5('$pass')";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials.';
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ACME Corp - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #fff; padding: 40px; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 340px; }
        .login-box h2 { margin-bottom: 24px; color: #1a3a6e; font-size: 20px; }
        .login-box input { width: 100%; padding: 10px; margin-bottom: 14px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        .login-box button { width: 100%; padding: 10px; background: #1a3a6e; color: #fff; border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }
        .error { color: #c0392b; font-size: 13px; margin-bottom: 12px; }
        .footer { margin-top: 20px; font-size: 11px; color: #aaa; text-align: center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>ACME Corp &mdash; Admin Panel</h2>
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text"     name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <div class="footer">ACME Corp Internal &mdash; Authorized users only</div>
    </div>
</body>
</html>
