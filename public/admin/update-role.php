
<?php

    require_once __DIR__ . '/../../app/auth.php';
    require_once __DIR__ . '/../../config/database.php';

    requireAdmin();

    // accept post requests only. many times
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: dashboard.php');
        exit;
    }

   //validate userid
    $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

    
    $newRole = $_POST['role'] ?? '';

    //checklist for roles
    $allowedRoles = ['user', 'admin'];

    if (!$userId || !in_array($newRole, $allowedRoles, true)) {
        header('Location: dashboard.php');
        exit;
    }

    
    if ($userId === (int) $_SESSION['user_id']) {
        header('Location: dashboard.php');
        exit;
    }

    //update user role
    $sql = "UPDATE users
            SET role = ?
            WHERE id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $newRole, $userId);

    $stmt->execute();

    $stmt->close();
    $connection->close();

    header('Location: dashboard.php');
    exit;

?>