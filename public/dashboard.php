<?php
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../config/database.php';

requireLogin();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$userId = $_SESSION['user_id'];

$sql = "SELECT id, name, description, category, frequency, created_at
        FROM habits
        WHERE user_id = ?
        ORDER BY created_at DESC";

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

                            <div class="habit-actions">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-small"
                                >
                                    Complete
                                </button>

                             <button
                                type="button"
                                class="btn btn-secondary btn-small edit-habit-button"
                                data-habit-id="<?= (int) $habit['id'] ?>"
                                data-name="<?= htmlspecialchars($habit['name']) ?>"
                                data-description="<?= htmlspecialchars($habit['description'] ?? '') ?>"
                                data-category="<?= htmlspecialchars($habit['category']) ?>"
                                data-frequency="<?= htmlspecialchars($habit['frequency']) ?>"
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



                <!-- Delete Habit Modal -->
<div class="modal" id="delete-habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-delete-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">

        <div class="modal-header">
            <div>
                <h2>Delete Habit</h2>
                <p>Are you sure you want to delete this habit?</p>
            </div>

            <button
                type="button"
                class="modal-close"
                data-close-delete-modal
            >
                &times;
            </button>
        </div>

        <form id="delete-habit-form" method="POST">

            <input
                type="hidden"
                id="delete-habit-id"
                name="habit_id"
            >

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-delete-modal
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    Delete
                </button>

            </div>

        </form>

    </div>
</div>
<script src="../assets/js/habits.js"></script>

</body>
</html>