<?php

function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: /Habit_Tracker/public/login.php');
        exit;
    }
}

function requireAdmin(): void
{
    requireLogin();

    if ($_SESSION['role'] !== 'admin') {
        header('Location: /Habit_Tracker/public/dashboard.php');
        exit;
    }
}

?>