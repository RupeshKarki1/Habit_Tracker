<?php

    session_start(); //starting session for holding user info for later authentication.
    require_once __DIR__ . '/../config/database.php';

    $message = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if($email === '' || $password === ''){
            $message = "Email and password are required.";
        }else {
            $statement = $connection -> prepare(
                'SELECT id, name, email, password_hash, role FROM users WHERE  
                email = ?'
            ); //password_hash not pass directly 

            $statement -> bind_param('s', $email);
            $statement -> execute();

            $result = $statement -> get_result();
            $user = $result->fetch_assoc();

            if($user && password_verify($password, $user['password_hash'])){
                session_regenerate_id(true); //gens new session id after successfull authen

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                $message = 'Login successful.';
            }else{
                $message = 'Invalid email or password.';
            }
        }
    }
?>
 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
    <h1>Login</h1>

    
    <form method="POST">

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit">Login</button>

    </form>



</body>
</html>
