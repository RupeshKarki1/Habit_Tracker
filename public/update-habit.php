
<?php

require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../config/database.php';

requireLogin();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

// Retrieve and sanitize form data
$habitId = filter_input(INPUT_POST, 'habit_id', FILTER_VALIDATE_INT);
$name = trim($_POST['habit_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$category = trim($_POST['category'] ?? '');
$frequency = trim($_POST['frequency'] ?? '');

// Validate habit ID
if (!$habitId || $habitId <= 0) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Invalid habit selected.'
    ];
    header('Location: dashboard.php');
    exit;
}

// Validate required fields
if ($name === '' || $category === '' || $frequency === '') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Please fill in the required fields.'
    ];
    header('Location: dashboard.php');
    exit;
}

// Validate name length
if (mb_strlen($name) > 100) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Habit name must not exceed 100 characters.'
    ];
    header('Location: dashboard.php');
    exit;
}

// Validate allowed categories
$allowedCategories = [
    'health',
    'fitness',
    'learning',
    'productivity',
    'other'
];

if (!in_array($category, $allowedCategories, true)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Invalid category selected.'
    ];
    header('Location: dashboard.php');
    exit;
}

// Validate frequency
$allowedFrequencies = ['daily', 'weekly'];

if (!in_array($frequency, $allowedFrequencies, true)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Invalid frequency selected.'
    ];
    header('Location: dashboard.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Update only the habit belonging to the logged-in user
$sql = "UPDATE habits
        SET name = ?, description = ?, category = ?, frequency = ?
        WHERE id = ? AND user_id = ?";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Unable to prepare database query.'
    ];
    header('Location: dashboard.php');
    exit;
}

$stmt->bind_param(
    "ssssii",
    $name,
    $description,
    $category,
    $frequency,
    $habitId,
    $userId
);

if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Habit updated successfully!'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'No changes made, or habit not found.'
        ];
    }

} else {
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'Unable to update habit. Please try again.'
    ];
}

$stmt->close();
$connection->close();

header('Location: dashboard.php');
exit;

?>