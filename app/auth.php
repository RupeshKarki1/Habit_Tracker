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



?>