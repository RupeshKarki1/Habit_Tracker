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
    $totalUsers = count($users);
    $totalAdmins = 0;
    $totalRegularUsers = 0;

    foreach ($users as $user) {
        if ($user['role'] === 'admin') {
            $totalAdmins++;
        } else {
            $totalRegularUsers++;
        }
    }

    $connection->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Habit Tracker</title>
    <link rel="stylesheet" href="../../assets/css/base.css">
<link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body class="admin-page">

    <div class="admin-layout">

        <aside class="admin-sidebar">

            <h2>Habit Tracker</h2>

            <nav class="admin-nav">
                <a href="#overview" class="admin-nav-link active">
                    Dashboard
                </a>

                <a href="#users" class="admin-nav-link">
                    Users
                </a>

                <a href="../logout.php" class="admin-nav-link">
                    Logout
                </a>
            </nav>

        </aside>


        <main class="admin-main">

            <header class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Manage Habit Tracker users and accounts.</p>
            </header>


            <section class="admin-section" id="overview">

                <div class="section-header">
                    <h2>Overview</h2>
                    <p>A quick summary of registered accounts.</p>
                </div>

                <div class="admin-stats">

                    <div class="admin-stat-card">
                        <span>Total Users</span>
                        <strong><?= $totalUsers ?></strong>
                    </div>

                    <div class="admin-stat-card">
                        <span>Regular Users</span>
                        <strong><?= $totalRegularUsers ?></strong>
                    </div>

                    <div class="admin-stat-card">
                        <span>Administrators</span>
                        <strong><?= $totalAdmins ?></strong>
                    </div>

                </div>

            </section>


            <section class="admin-section" id="users">

                <div class="section-header">
                    <h2>User Management</h2>
                    <p>View registered Habit Tracker accounts.</p>
                </div>

                <div class="admin-table-wrapper">

                    <table class="admin-table">

                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Joined</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach ($users as $user): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($user['name']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($user['email']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars(
                                            date(
                                                'M j, Y',
                                                strtotime($user['created_at'])
                                            )
                                        ) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</body>
</html>