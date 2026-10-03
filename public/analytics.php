
<?php

    require_once __DIR__ . '/../app/auth.php';
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../app/habits.php';

    requireLogin();

    $userId = $_SESSION['user_id'];

    // only foundation..need to do this later brochacho

    $sql = "SELECT
            (SELECT COUNT(*)
             FROM habits
             WHERE user_id = ?) AS total_habits,

            (SELECT COUNT(*)
             FROM habit_logs hl
             INNER JOIN habits h
                ON hl.habit_id = h.id
             WHERE h.user_id = ?
                AND hl.status = 'completed') AS total_completed,

            (SELECT COUNT(*)
             FROM habit_logs hl
             INNER JOIN habits h
                ON hl.habit_id = h.id
             WHERE h.user_id = ?
                AND hl.status = 'missed') AS total_missed";

    $stmt = $connection->prepare($sql);

    $stmt->bind_param("iii", $userId, $userId, $userId);

    $stmt->execute();

    $result = $stmt->get_result();

    $summary = $result->fetch_assoc();

    $stmt->close();

    $totalTracked = (int) $summary['total_completed']
    + (int) $summary['total_missed'];

    $completionRate = $totalTracked > 0
    ? round(
        ($summary['total_completed'] / $totalTracked) * 100,
        1
    )
    : 0;    

    //temp test stuff
    echo '<pre>';

    print_r($summary);

    echo "Completion Rate: " . $completionRate . "%";

    echo '</pre>';

?>