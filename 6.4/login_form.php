<?php
require_once('classes/User.php');

$user = new User();
$errors = [];

if(isset($_POST['login-btn'])) {

    $user->username = $_POST['username'];
    $user->setPassword($_POST['password']);

    // Validatie via class
    $errors = $user->validateUser();

    if(empty($errors)) {
        if ($user->loginUser()) {
            header("location: index.php");
            exit;
        } else {
            $errors[] = "Login mislukt";
        }
    }

    if(!empty($errors)){
        echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Inloggen</title>
</head>
<body>
    <h3>Login</h3>
    <hr/>
    <form action="" method="POST">
        <label>Username</label>
        <input type="text" name="username" required />
        <br>
        <label>Password</label>
        <input type="password" name="password" required />
        <br>
        <button type="submit" name="login-btn">Login</button>
        <br>
        <a href="register_form.php">Registreren</a>
    </form>
</body>
</html>
