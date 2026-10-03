<?php

    function requireLogin(): void{ //return type void
        if(session_status() === PHP_SESSION_NONE){ //enabled but not active session 
            session_start();
        }

        if(!isset($_SESSION['user_id'])){
            header('Location: login.php');
            exit;
        }
    }

    function requireAdmin(): void{
        requireLogin();

        if($_SESSION['role'] !== 'admin'){
            header('Location: dashboard.php');
            exit;
        }
    }



?>