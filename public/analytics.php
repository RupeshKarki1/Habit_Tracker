
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
    

    //for the weekly progress
    $weeklySql = "SELECT
        hl.log_date,
        SUM(hl.status = 'completed') AS completed,
        SUM(hl.status = 'missed') AS missed
        FROM habit_logs hl
        INNER JOIN habits h
        ON hl.habit_id = h.id
        WHERE h.user_id = ?
        AND hl.log_date >= CURDATE() - INTERVAL 6 DAY
        AND hl.log_date <= CURDATE()
        GROUP BY hl.log_date
        ORDER BY hl.log_date ASC";

    $stmt = $connection->prepare($weeklySql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $weeklyData = [];

    while ($row = $result->fetch_assoc()) {
        $weeklyData[$row['log_date']] = [
            'completed' => (int) $row['completed'],
            'missed' => (int) $row['missed']
        ];
    }

    $stmt->close(); 



    $weeklyLabels = [];
    $weeklyCompleted = [];
    $weeklyMissed = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));

        $weeklyLabels[] = date('D', strtotime($date));

        $weeklyCompleted[] = $weeklyData[$date]['completed'] ?? 0;

        $weeklyMissed[] = $weeklyData[$date]['missed'] ?? 0;
    }

    $monthlySql = "SELECT
        hl.log_date,
        SUM(hl.status = 'completed') AS completed,
        SUM(hl.status = 'missed') AS missed
        FROM habit_logs hl
        INNER JOIN habits h
            ON hl.habit_id = h.id
        WHERE h.user_id = ?
            AND hl.log_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
            AND hl.log_date <= CURDATE()
        GROUP BY hl.log_date
        ORDER BY hl.log_date ASC";

    $stmt = $connection->prepare($monthlySql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $monthlyData = [];

    while ($row = $result->fetch_assoc()) {
        $monthlyData[$row['log_date']] = [
            'completed' => (int) $row['completed'],
            'missed' => (int) $row['missed']
        ];
    }

    $stmt->close();

    $monthlyLabels = [];
    $monthlyCompleted = [];
    $monthlyMissed = [];

    $daysInMonth = (int) date('j');

    for ($day = 1; $day <= $daysInMonth; $day++) {

        $date = date('Y-m-d', strtotime(
            date('Y-m-01') . " + " . ($day - 1) . " days"
        ));

        $monthlyLabels[] = $day;

        $monthlyCompleted[] =
            $monthlyData[$date]['completed'] ?? 0;

        $monthlyMissed[] =
            $monthlyData[$date]['missed'] ?? 0;
    }


    $yearlySql = "SELECT
                MONTH(hl.log_date) AS month_number,
                SUM(hl.status = 'completed') AS completed,
                SUM(hl.status = 'missed') AS missed
              FROM habit_logs hl
              INNER JOIN habits h
                ON hl.habit_id = h.id
              WHERE h.user_id = ?
                AND YEAR(hl.log_date) = YEAR(CURDATE())
              GROUP BY MONTH(hl.log_date)
              ORDER BY month_number ASC";

    $stmt = $connection->prepare($yearlySql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    $yearlyData = [];

    while ($row = $result->fetch_assoc()) {
        $yearlyData[(int) $row['month_number']] = [
            'completed' => (int) $row['completed'],
            'missed' => (int) $row['missed']
        ];
    }

    $stmt->close();


    $yearlyLabels = [];
    $yearlyCompleted = [];
    $yearlyMissed = [];

    for ($month = 1; $month <= 12; $month++) {

        $yearlyLabels[] = date('M', mktime(0, 0, 0, $month, 1));

        $yearlyCompleted[] =
            $yearlyData[$month]['completed'] ?? 0;

        $yearlyMissed[] =
            $yearlyData[$month]['missed'] ?? 0;
    }


    //api for chart
    $analyticsData = [
        'summary' => [
            'totalHabits' => (int) $summary['total_habits'],
            'totalCompleted' => (int) $summary['total_completed'],
            'totalMissed' => (int) $summary['total_missed'],
            'completionRate' => $completionRate
        ],

        'weekly' => [
            'labels' => $weeklyLabels,
            'completed' => $weeklyCompleted,
            'missed' => $weeklyMissed
        ],

        'monthly' => [
            'labels' => $monthlyLabels,
            'completed' => $monthlyCompleted,
            'missed' => $monthlyMissed
        ],

        'yearly' => [
            'labels' => $yearlyLabels,
            'completed' => $yearlyCompleted,
            'missed' => $yearlyMissed
        ]
    ];

?>