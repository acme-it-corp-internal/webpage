<?php
session_start();
include '../config.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$conn = db_connect();

// Fetch users for display
$users     = $conn->query("SELECT id, username, password, role, email FROM users");
$employees = $conn->query("SELECT id, name, role, department, salary, email, notes FROM employees");

// DB dump trigger - writes plaintext dump to /backups/
if (isset($_GET['export'])) {
    $dump = "-- ACME Corp DB Export\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $dump .= "-- users --\n";
    $res = $conn->query("SELECT * FROM users");
    while ($row = $res->fetch_assoc()) {
        $dump .= implode(' | ', $row) . "\n";
    }
    $dump .= "\n-- employees --\n";
    $res = $conn->query("SELECT * FROM employees");
    while ($row = $res->fetch_assoc()) {
        $dump .= implode(' | ', $row) . "\n";
    }
    file_put_contents('/var/www/html/backups/export.txt', $dump);
    $export_msg = "Export written to /backups/export.txt";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ACME Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; }
        .topbar { background: #1a3a6e; color: #fff; padding: 12px 30px; display: flex; justify-content: space-between; }
        .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
        h2 { color: #1a3a6e; margin: 24px 0 12px; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 30px; font-size: 13px; }
        th { background: #1a3a6e; color: #fff; padding: 10px 12px; text-align: left; }
        td { padding: 9px 12px; border-bottom: 1px solid #eee; }
        tr:hover td { background: #f9f9f9; }
        .btn { display: inline-block; padding: 8px 16px; background: #c0392b; color: #fff; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .msg { background: #dff0d8; border: 1px solid #3c763d; color: #3c763d; padding: 10px; border-radius: 4px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="topbar">
        <span>ACME Corp &mdash; Admin Dashboard</span>
        <span>Logged in as: <?= htmlspecialchars($_SESSION['user']) ?> (<?= htmlspecialchars($_SESSION['role']) ?>)</span>
    </div>
    <div class="container">

        <?php if (isset($export_msg)): ?>
            <div class="msg"><?= $export_msg ?></div>
        <?php endif; ?>

        <h2>Users</h2>
        <table>
            <tr><th>ID</th><th>Username</th><th>Password Hash</th><th>Role</th><th>Email</th></tr>
            <?php while ($row = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td style="font-family:monospace; font-size:11px;"><?= $row['password'] ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <h2>Employees</h2>
        <table>
            <tr><th>ID</th><th>Name</th><th>Role</th><th>Dept</th><th>Salary</th><th>Email</th><th>Notes</th></tr>
            <?php while ($row = $employees->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td>$<?= number_format($row['salary']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['notes']) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a href="?export=1" class="btn">Export DB to /backups/</a>
        &nbsp;
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
