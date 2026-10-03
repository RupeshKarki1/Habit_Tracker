<?php

    session_start(); //starting session for holding user info for later authentication.
    require_once __DIR__ . '/../config/database.php';

    $message = '';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

       if ($email === '' || $password === '') {
    $message = "Email and password are required.";

} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = "Please enter a valid email address.";

} else {
            $statement = $connection -> prepare(
                'SELECT id, name, email, password_hash, role 
                FROM users 
                WHERE email = ?'
            ); //password_hash not pass directly 

            $statement -> bind_param('s', $email);
            $statement -> execute();

            $result = $statement -> get_result();
            $user = $result->fetch_assoc();

            if($user && password_verify($password, $user['password_hash'])){ //check here using pass_verify
                session_regenerate_id(true); //gens new session id after successfull authen

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                header('Location: dashboard.php');
                exit;
            }else{
                $message = "Invalid credentials.";
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
    <link rel="stylesheet" href="../assets/css/base.css">
<link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body class="auth-page">

    <main class="auth-container">

        <section class="auth-card" aria-labelledby="login-title">

            <div class="auth-header">
                <h1 id="login-title">Welcome Back</h1>
                <p>Sign in to continue tracking your habits.</p>
            </div>

            <?php if ($message !== ''): ?>
                <div class="form-message" role="alert">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">

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
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>

            </form>

            <p class="auth-switch">
                Don't have an account?
                <a href="register.php">Create an account</a>
            </p>

        </section>

    </main>

</body>
</html>
