    
<?php
    //database connection
   require_once __DIR__ . '/../config/database.php';

   $message = "";

   if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //collecting variables from submit and trimming extra spaces front and back
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Registration validation
if ($name === '' || $email === '' || $password === '') {
    $message = "All fields are required.";

} elseif (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
    $message = "Name must be between 2 and 50 characters.";

} elseif (!preg_match("/^[\p{L}\s'-]+$/u", $name)) {
    $message = "Name can only contain letters, spaces, apostrophes and hyphens.";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Please enter a valid email.";

} elseif (strlen($password) < 8) {
    $message = "Password must be at least 8 characters.";

} elseif (!preg_match('/[A-Za-z]/', $password)) {
    $message = "Password must contain at least one letter.";

} elseif (!preg_match('/[0-9]/', $password)) {
    $message = "Password must contain at least one number.";

} else {
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
   <link rel="stylesheet" href="../assets/css/base.css">
<link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-page">

    <main class="auth-container">

        <section class="auth-card" aria-labelledby="register-title">

            <div class="auth-header">
                <h1 id="register-title">Create Account</h1>
                <p>Start building better habits today.</p>
            </div>

            <?php if ($message !== ''): ?>
                <div class="form-message" role="alert">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="register.php" class="auth-form">

                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your name"
                    autocomplete="name"
                    minlength="2"
                    maxlength="50"
                    required
                >
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Create Account
                </button>

            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="login.php">Login</a>
            </p>

        </section>

    </main>

</body>
</html>
