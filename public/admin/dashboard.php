<?php

require_once __DIR__ . '/../../app/auth.php';
require_once __DIR__ . '/../../config/database.php';

requireAdmin();

$userName = $_SESSION['user_name'] ?? 'Admin';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Habit Tracker</title>
</head>

<body>

    <h1>Admin Dashboard</h1>

    <p>
        Welcome, <?= htmlspecialchars($userName) ?>
    </p>

    <p>
        You are logged in as an administrator.
    </p>

    <a href="../logout.php">Logout</a>

</body>
</html>