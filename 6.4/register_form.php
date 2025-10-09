<?php
// Functie: programma login OOP 
// Auteur: Studentnaam

require_once('classes/User.php'); // Laadt automatisch db.php binnen

$user = new User();
$errors = [];

// Is de register button aangeklikt?
if (isset($_POST['register-btn'])) {
    
    // Gegevens uit formulier halen
    $user->username = trim($_POST['username']);
    $user->setPassword($_POST['password']);

    // Validatie gegevens
    $errors = $user->validateUser();

    // Alleen verder als er geen validatiefouten zijn
    if (count($errors) == 0) {
        // Register user in database (via db.php)
        $errors = $user->registerUser();
    }

    // Fouten tonen
    if (count($errors) > 0) {
        $message = "";
        foreach ($errors as $error) {
            $message .= $error . "\\n";
        }
        
        echo "
        <script>alert('" . $message . "')</script>
        <script>window.location = 'register_form.php'</script>";
    
    } else {
        echo "
            <script>alert('" . "User geregistreerd" . "')</script>
            <script>window.location = 'login_form.php'</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head><meta charset="utf-8"><title>Registreren</title></head>
<body>
    <h3>Registration</h3>
    <hr/>

    <form action="" method="POST">
        <div>
            <label>Username</label>
            <input type="text" name="username" required />
        </div>
        <div>
            <label>Password</label>
            <input type="password" name="password" required />
        </div>
        <br />
        <div>
            <button type="submit" name="register-btn">Register</button>
        </div>
        <a href="index.php">Home</a>
    </form>
</body>
</html>
