<?php
require_once __DIR__ . '/../app/auth.php';

requireLogin();

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$habits = $habits ?? [];
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
                                >
                                    Edit
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-small"
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

            <div class="empty-state">
                <h3>No analytics data yet</h3>
                <p>Analytics will become available after habit activity is recorded.</p>
            </div>
        </section>

    </main>

</div>


<!-- Add Habit Modal -->
<div class="modal" id="habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">
        <div class="modal-header">
            <div>
                <h2>Add Habit</h2>
                <p>Create a habit you want to track.</p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Close"
                data-close-modal
            >
                &times;
            </button>
        </div>

        <form
            class="habit-form"
            id="habit-form"
            action="create-habit.php"
            method="POST"
        >
            <div class="form-group">
                <label for="habit-name">Habit Name</label>
                <input
                    type="text"
                    id="habit-name"
                    name="habit_name"
                    placeholder="e.g. Morning Walk"
                    required
                >
            </div>

            <div class="form-group">
                <label for="habit-description">Description (Optional)</label>
                <textarea
                    id="habit-description"
                    name="description"
                    rows="3"
                    placeholder="Describe your habit..."
                ></textarea>
            </div>

            <div class="form-group">
                <label for="habit-category">Category</label>
                <select id="habit-category" name="category" required>
                    <option value="">Select category</option>
                    <option value="health">Health</option>
                    <option value="fitness">Fitness</option>
                    <option value="learning">Learning</option>
                    <option value="productivity">Productivity</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="habit-frequency">Frequency</label>
                <select id="habit-frequency" name="frequency" required>
                    <option value="">Select frequency</option>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-modal
                >
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    Create Habit
                </button>
            </div>
        </form>
    </div>

</div>


<!-- Edit Habit Modal -->
<div class="modal" id="edit-habit-modal" aria-hidden="true">

    <div class="modal-backdrop" data-close-edit-modal></div>

    <div class="modal-dialog" role="dialog" aria-modal="true">
        <div class="modal-header">
            <div>
                <h2>Edit Habit</h2>
                <p>Update your habit details.</p>
            </div>

            <button
                type="button"
                class="modal-close"
                aria-label="Close"
                data-close-edit-modal
            >
                &times;
            </button>
        </div>

        <form
            class="habit-form"
            id="edit-habit-form"
            method="POST"
        >
            <input
                type="hidden"
                id="edit-habit-id"
                name="habit_id"
            >

            <div class="form-group">
                <label for="edit-habit-name">Habit Name</label>
                <input
                    type="text"
                    id="edit-habit-name"
                    name="habit_name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="edit-habit-description">Description (Optional)</label>
                <textarea
                    id="edit-habit-description"
                    name="description"
                    rows="3"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="edit-habit-category">Category</label>
                <select id="edit-habit-category" name="category" required>
                    <option value="health">Health</option>
                    <option value="fitness">Fitness</option>
                    <option value="learning">Learning</option>
                    <option value="productivity">Productivity</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="edit-habit-frequency">Frequency</label>
                <select id="edit-habit-frequency" name="frequency" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                </select>
            </div>

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-close-edit-modal
                >
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</div>

<script src="../assets/js/habits.js"></script>

</body>
</html>