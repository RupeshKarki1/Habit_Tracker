<?php
    require_once __DIR__ . '/../app/auth.php';
    requireLogin();

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);

    require_once __DIR__ .'/../config/database.php';

    $userId = $_SESSION['user_id'];

    $sql = "SELECT id, name, description, category, frequency, created_at
        FROM habits
        WHERE user_id = ?
        ORDER BY created_at DESC"; 
        
    $stmt = $connection->prepare($sql);

    if(!$stmt){
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

    <link rel="stylesheet" href="../assets/css/styles.css">
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

                <a href="dashboard.php" class="nav-link active">
                    Dashboard
                </a>

                <a href="#habits" class="nav-link">
                    My Habits
                </a>

                <a href="#analytics" class="nav-link">
                    Analytics
                </a>

                <a href = "logout.php">Logout</a> //for logout in dashboard


            </nav>

        </aside>


        <!-- Main dashboard -->
        <main class="dashboard-main">

            <!--error display need to refactor again ig-->
            <?php if ($flash): ?>

                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>

            <?php endif; ?>  
            
            
            <!-- Header -->
            <header class="dashboard-header">

                <div>
                    <h1>Dashboard</h1>

                    <p>
                        Welcome back,
                        <strong>
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </strong>
                    </p>
                </div>

            </header>


            <!-- Overview -->
            <section class="dashboard-content">

                <div class="section-header">
                    <h2>Overview</h2>
                    <p>A quick summary of your habit progress.</p>
                </div>

                <div class="stats-grid">

                    <article class="stat-card">
                        <span class="stat-label">
                            Total Habits
                        </span>

                        <strong class="stat-value">
                            0
                        </strong>
                    </article>


                    <article class="stat-card">
                        <span class="stat-label">
                            Completed Today
                        </span>

                        <strong class="stat-value">
                            0
                        </strong>
                    </article>


                    <article class="stat-card">
                        <span class="stat-label">
                            Current Streak
                        </span>

                        <strong class="stat-value">
                            0 days
                        </strong>
                    </article>


                    <article class="stat-card">
                        <span class="stat-label">
                            Completion Rate
                        </span>

                        <strong class="stat-value">
                            0%
                        </strong>
                    </article>

                </div>

            </section>


            <!-- Today's Habits -->
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
                        aria-haspopup="dialog"
                        aria-controls="habit-modal"
                    >
                        Add Habit
                    </button>

                </div>


               
            <?php if (empty($habits)): ?>

                <!-- Empty state -->
                <div class="empty-state">

                    <h3>No habits yet</h3>

                    <p>
                        Create your first habit to start tracking your progress.
                    </p>

                </div>

            <?php else: ?>

            <!-- Habit list -->
            <div class="habits-grid">

                <?php foreach ($habits as $habit): ?>

                    <article class="habit-card">

                        <div class="habit-card-header">

                            <h3>
                                <?= htmlspecialchars($habit['name']) ?>
                            </h3>

                            <span class="habit-category">
                                <?= htmlspecialchars(ucfirst($habit['category'])) ?>
                            </span>

                        </div>

                        <p class="habit-description">

                            <?= htmlspecialchars(
                                $habit['description'] ?: 'No description provided.'
                            ) ?>

                        </p>


                    <div class="habit-card-footer">

                        <div class="habit-card-meta">

                            <span class="habit-frequency">
                                <?= htmlspecialchars(ucfirst($habit['frequency'])) ?>
                            </span>

                            <span class="habit-created">
                                Created:
                                <?= htmlspecialchars(
                                    date('M j, Y', strtotime($habit['created_at']))
                                ) ?>
                            </span>

                        </div>

                        <button
                            type="button"
                            class="btn btn-secondary edit-habit-btn"
                            data-id="<?= (int) $habit['id'] ?>"
                            data-name="<?= htmlspecialchars($habit['name'], ENT_QUOTES, 'UTF-8') ?>"
                            data-description="<?= htmlspecialchars($habit['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            data-category="<?= htmlspecialchars($habit['category'], ENT_QUOTES, 'UTF-8') ?>"
                            data-frequency="<?= htmlspecialchars($habit['frequency'], ENT_QUOTES, 'UTF-8') ?>"
                        >
                            Edit
                        </button>

                    </div>

                    </article>

                <?php endforeach; ?>

            </div>

            <?php endif; ?>

            </section>


            <!-- Analytics placeholder -->
            <section class="habits-section" id="analytics">

                <div class="section-header">

                    <h2>Analytics</h2>

                    <p>
                        Weekly, monthly, and yearly habit analytics will appear here.
                    </p>

                </div>

                <div class="empty-state">

                    <h3>No analytics data yet</h3>

                    <p>
                        Analytics will become available after habit activity is recorded.
                    </p>

                </div>

            </section>

        </main>

    </div>

    <?php include __DIR__ . '/../views/dashboard/add-habit-modal.php'; ?>

    <?php include __DIR__ . '/../views/dashboard/edit-habit-modal.php'; ?>               
    

    <!-- Dashboard JavaScript -->
    <script src="../assets/js/dashboard.js"></script>

</body>

</html>