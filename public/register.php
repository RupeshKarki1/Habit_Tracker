    
<?php
    //database connection
   require_once __DIR__ . '/../config/database.php';

   $message = "";

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //collecting variables from submit and trimming extra spaces front and back
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    //register info validation
    if($name === '' || $email === '' || $password === ''){
        $message = "All fields are required";
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = "Please enter a valid email";
    }elseif(strlen($password) < 6){
        $message = "password must be at least 6 characters.";
    }else{
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $statement = $connection -> prepare(
            'INSERT INTO users (name, email, password_hash) VALUES(?, ?, ?)'
        );

        $statement -> bind_param(
            'sss',
            $name,
            $email,
            $passwordHash
        );

        try{
            $statement -> execute();
            $message = "Registration successful.";
        }catch(mysqli_sql_exception $exception){
            if($exception -> getCode() === 1062){
            $message = "An account with this email already exists.";
            }
            else{
                $message = "Registration failed. Please try again!";
            }
        }

        $statement -> close();
    }
   }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    
    <h1> Create Account </h1>

    <?php if($message !== ''): ?>
        <p><?= htmlspecialchars($message)?></p>
    <?php endif; ?>

    <form method="POST" action="register.php">

        <div>
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>

        <button type="submit">Register</button>

    </form>



</body>
</html>
