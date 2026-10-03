
<?php

    require_once __DIR__ . '/../app/auth.php';
    require_once __DIR__ . '/../config/database.php';

    requireLogin();

    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: dashboard.php');
        exit;
    }

    // Validate habit ID
    $habitId = filter_input(INPUT_POST, 'habit_id', FILTER_VALIDATE_INT);

    if (!$habitId || $habitId <= 0) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Invalid habit selected.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    $userId = $_SESSION['user_id'];

    // Delete only the habit belonging to the logged-in user
    $sql = "DELETE FROM habits WHERE id = ? AND user_id = ?";

    $stmt = $connection->prepare($sql);

    if (!$stmt) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Unable to prepare database query.'
        ];

        header('Location: dashboard.php');
        exit;
    }

    $stmt->bind_param("ii", $habitId, $userId);

    if ($stmt->execute()) {

        if ($stmt->affected_rows > 0) {
            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Habit deleted successfully!'
            ];
        } else {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Habit not found or already deleted.'
            ];
        }

    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Unable to delete habit. Please try again.'
        ];
    }

    $stmt->close();
    $connection->close();

    header('Location: dashboard.php');
    exit;

?>