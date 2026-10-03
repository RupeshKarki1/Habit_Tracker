
<?php

    require_once __DIR__ . '/../app/auth.php';
    require_once __DIR__ . '/../config/database.php';

    requireLogin();

    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: dashboard.php');
        exit;
    }

    // Retrieve submitted data
    $habitId = filter_input(INPUT_POST, 'habit_id', FILTER_VALIDATE_INT);
    $status = trim($_POST['status'] ?? '');

    // Validate habit ID
    if (!$habitId || $habitId <= 0) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Invalid habit selected.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    // Validate status
    $allowedStatuses = ['completed', 'missed'];

    if (!in_array($status, $allowedStatuses, true)) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Invalid tracking status.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Verify that the habit belongs to the logged-in user
    $sql = "SELECT id FROM habits WHERE id = ? AND user_id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $habitId, $userId);
    $stmt->execute();

    $result = $stmt->get_result();
    $habitExists = $result->num_rows > 0;

    $stmt->close();

    if (!$habitExists) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Habit not found.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    // Record today's status
    $sql = "INSERT INTO habit_logs (habit_id, log_date, status)
            VALUES (?, CURDATE(), ?)
            ON DUPLICATE KEY UPDATE status = VALUES(status)";

    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Unable to prepare database query.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    $stmt->bind_param("is", $habitId, $status);

    if ($stmt->execute()) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => $status === 'completed'
                ? 'Habit marked as completed!'
                : 'Habit marked as missed.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Unable to save habit progress. Please try again.'
        ];
    }

    $stmt->close();
    $connection->close();

    header('Location: dashboard.php');
    exit;

?>