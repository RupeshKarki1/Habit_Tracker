
<?php

    require_once __DIR__ . '/../../app/auth.php';
    require_once __DIR__ . '/../../config/database.php';

    requireAdmin();

    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: dashboard.php');
        exit;
    }

    
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

    if (!$userId) {
        header('Location: dashboard.php');
        exit;
    }

    
    if ($userId === (int) $_SESSION['user_id']) {
        header('Location: dashboard.php');
        exit;
    }

    
    $sql = "DELETE FROM users WHERE id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $userId);

    $stmt->execute();

    $stmt->close();
    $connection->close();

    header('Location: dashboard.php');
    exit;

?>