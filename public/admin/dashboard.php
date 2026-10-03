<?php

    require_once __DIR__ . '/../../app/auth.php';
    require_once __DIR__ . '/../../config/database.php';

    requireAdmin();

    $sql = "SELECT id, name, email, role, created_at
            FROM users
            ORDER BY created_at DESC";

    $result = $connection->query($sql);

    if (!$result) {
        die('Unable to retrieve users.');
    }

    $users = $result->fetch_all(MYSQLI_ASSOC);

    $connection->close();

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



    <a href="../logout.php">Logout</a>

</body>
</html>