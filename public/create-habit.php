<?php

    //need stuff from here
    require_once __DIR__ .'/../app/auth.php';
    require_once __DIR__ .'/../config/database.php';

    requireLogin(); //only create habit if user is logged in

    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Location: dashboard.php');
        exit; //if the method is not post your request will die
    }

    //sanitizationnnn
    $name = trim($_POST['habit_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $frequency = trim($_POST['frequency'] ?? '');

    if($name === '' || $category === '' || $frequency === ''){ //validation for empty fields
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Please fill in the required fields.'
        ];
        header('Location: dashboard.php');
        exit; 
    }

    if(mb_strlen($name) > 100){
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Habit name must not exceed 100 characters.'
        ];
        header('Location: dashboard.php');
        exit;  
    }

    //validate categories to reduce complexity of project
    $allowedCategories = [
        'health',
        'fitness',
        'learning',
        'productivity',
        'other'
    ];

    if(!in_array($category, $allowedCategories, true)){

        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Invalid category selected.'
        ];
        header('Location: dashboard.php');
        exit;
    }

    $userId = $_SESSION['user_id'];

    //sql statement
    $sql = "INSERT INTO habits 
    (user_id, name, description, category, frequency)
    VALUES (?, ?, ?, ?, ?)";

    $stmt = $connection -> prepare($sql);
    
    if(!$stmt){
        $_SESSION['flash'] = [
            'type' => 'error',
            'message' => 'Unable to prepare database query.'
        ];
        header('Location: dashboard.php');
        exit;
    }

    $stmt -> bind_param(
        "issss",
        $userId,
        $name,
        $description,
        $category,
        $frequency
    );

    if($stmt -> execute()){
        $stmt -> close();
        $connection -> close();

        header('Location: dashboard.php');
        exit;
    }

    //if error occurs:
    $stmt -> close();
    $connection -> close();

    //flash error handling
    $_SESSION['flash'] = [
        'type' => 'error',
        'message' => 'please fill in all required fields.'
    ];
    header('Location: dashboard.php');
    exit;
?>