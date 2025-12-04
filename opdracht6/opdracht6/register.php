<?php
require_once 'functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = registreer_gebruiker($_POST['gebruikersnaam'] ?? '', $_POST['wachtwoord'] ?? '', $_POST['wachtwoord2'] ?? '');
    if ($res['success']) {
        header('Location: protected.php');
        exit;
    } else {
        $errors = $res['errors'];
    }
}
?>
<!doctype html>
<html lang="nl">
<head><meta charset="utf-8"><title>Registreren</title></head>
<body>
<h2>Registreren</h2>
<?php if ($errors): ?>
  <ul style="color:red">
    <?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>
<form method="post" action="">
  <label>Gebruikersnaam: <input name="gebruikersnaam" required value="<?=isset($_POST['gebruikersnaam']) ? htmlspecialchars($_POST['gebruikersnaam']) : ''?>"></label><br>
  <label>Wachtwoord: <input name="wachtwoord" type="password" required></label><br>
  <label>Herhaal wachtwoord: <input name="wachtwoord2" type="password" required></label><br>
  <button type="submit">Registreer</button>
</form>
<p><a href="login.php">Ik heb al een account — inloggen</a></p>
</body>
</html>
