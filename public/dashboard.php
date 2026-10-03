<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../config/database.php';

requireLogin();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$userId = $_SESSION['user_id'];

$sql = "SELECT
            h.id,
            h.name,
            h.description,
            h.category,
            h.frequency,
            h.created_at,
            hl.status AS today_status
        FROM habits h
        LEFT JOIN habit_logs hl
            ON h.id = hl.habit_id
            AND hl.log_date = CURDATE()
        WHERE h.user_id = ?
        ORDER BY h.created_at DESC";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    die('Unable to retrieve habits.');
}

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();
$habits = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/habits.css">
    <link rel="stylesheet" href="../assets/css/analytics.css">
    <link rel="stylesheet" href="../assets/css/error.css">
</head>

<body class="dashboard-page">

<div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h2>Habit Tracker</h2>
        </div>

        <nav class="sidebar-nav" aria-label="Main navigation">
            <a href="dashboard.php" class="nav-link active">Dashboard</a>
            <a href="#habits" class="nav-link">My Habits</a>
            <a href="#analytics" class="nav-link">Analytics</a>
            <a href="logout.php" class="nav-link">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <header class="dashboard-header">
            <h1>Dashboard</h1>
            <p>
                Welcome back,
                <strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong>
            </p>
        </header>

        <!-- Overview -->
        <section class="dashboard-content">
            <div class="section-header">
                <h2>Overview</h2>
                <p>A quick summary of your habit progress.</p>
            </div>

            <div class="stats-grid">
                <article class="stat-card">
                    <span class="stat-label">Total Habits</span>
                    <strong class="stat-value">0</strong>
                </article>

                <article class="stat-card">
                    <span class="stat-label">Completed Today</span>
                    <strong class="stat-value">0</strong>
                </article>

                <article class="stat-card">
                    <span class="stat-label">Current Streak</span>
                    <strong class="stat-value">0 days</strong>
                </article>

                <article class="stat-card">
                    <span class="stat-label">Completion Rate</span>
                    <strong class="stat-value">0%</strong>
                </article>
            </div>
        </section>

        <!-- Habits -->
        <section class="habits-section" id="habits">

            <div class="section-header section-header-row">
                <div>
                    <h2>Today's Habits</h2>
                    <p>Track the habits scheduled for today.</p>
                </div>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="open-habit-modal"
                >
                    Add Habit
                </button>
            </div>

            <?php if (!empty($habits)): ?>

                <div class="habit-list">

                    <?php foreach ($habits as $habit): ?>

                        <article class="habit-card">

                            <div class="habit-card-content">
                                <h3>
                                    <?= htmlspecialchars($habit['name']) ?>
                                </h3>

                                <?php if (!empty($habit['description'])): ?>
                                    <p class="habit-description">
                                        <?= htmlspecialchars($habit['description']) ?>
                                    </p>
                                <?php endif; ?>

                                <div class="habit-meta">
                                    <span class="habit-tag">
                                        <?= htmlspecialchars(ucfirst($habit['category'])) ?>
                                    </span>

                                    <span class="habit-tag">
                                        <?= htmlspecialchars(ucfirst($habit['frequency'])) ?>
                                    </span>
                                </div>
                            </div>

                            <!--completed status-->
                            <div class="habit-today-status">

                                <?php if ($habit['today_status'] === 'completed'): ?>

                                    <span class="status-completed">
                                        Completed today
                                    </span>

                                <?php elseif ($habit['today_status'] === 'missed'): ?>

                                    <span class="status-missed">
                                        Missed today
                                    </span>

                                <?php else: ?>

                                    <span class="status-pending">
                                        Not completed
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="habit-actions">
                               
                                <form action="log-habit.php" method="POST" class="inline-form">
                                    <input
                                        type="hidden"
                                        name="habit_id"
                                        value="<?= (int) $habit['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="completed"
                                    >

                                    <button type="submit" class="btn btn-primary btn-small">
                                        Complete
                                    </button>
                                </form>

                                <button
                                    type="button"
                                    class="btn btn-secondary btn-small edit-habit-button"
                                    data-habit-id="<?= (int) $habit['id'] ?>"
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-small delete-habit-button"
                                    data-habit-id="<?= (int) $habit['id'] ?>"
                                    >
                                     Delete
                                </button>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="empty-state">
                    <h3>No habits yet</h3>
                    <p>Create your first habit to start tracking your progress.</p>
                </div>

            <?php endif; ?>

        </section>

        <!-- Analytics -->
        <section class="habits-section" id="analytics">
            <div class="section-header">
                <h2>Analytics</h2>
                <p>Weekly, monthly, and yearly habit analytics will appear here.</p>
            </div>

            <div class="analytics-grid">

    <article class="analytics-card">
        <span class="analytics-label">This Week</span>
        <strong class="analytics-value">0%</strong>
        <p>Completion rate</p>
    </article>

    <article class="analytics-card">
        <span class="analytics-label">This Month</span>
        <strong class="analytics-value">0%</strong>
        <p>Completion rate</p>
    </article>

    <article class="analytics-card">
        <span class="analytics-label">Best Streak</span>
        <strong class="analytics-value">0 days</strong>
        <p>Longest habit streak</p>
    </article>

</div>
        </section>

    </main>

</div>


<?php include __DIR__ . '/views/dashboard/add-habit-modal.php'; ?>
<?php include __DIR__ . '/views/dashboard/edit-habit-modal.php'; ?>
<?php include __DIR__ . '/views/dashboard/delete-habit-modal.php'; ?>


<script src="../assets/js/habits.js"></script>

</body>
</html>