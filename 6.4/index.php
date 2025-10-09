<?php
require_once 'classes/User.php';

$user = new User();
session_start();

if (isset($_GET['logout'])) {
    $user->logout();
    header('Location: index.php');
    exit;
}

echo "<h3>Mooie Home</h3><hr>";

if (empty($_SESSION['user_id'])) {
    echo "<p>U bent niet ingelogd.</p>";
    echo '<a href="login_form.php">Login</a> | <a href="register_form.php">Registreren</a>';
} else {
    $user->getUser($_SESSION['gebruikersnaam']);
    echo "<h2>Welkom, " . htmlspecialchars($user->username) . "!</h2>";
    echo '<a href="?logout=1">Uitloggen</a>';
}
?>
