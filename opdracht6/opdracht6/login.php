<?php
require_once 'functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $res = log_in_gebruiker($_POST['gebruikersnaam'] ?? '', $_POST['wachtwoord'] ?? '');
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
<head><meta charset="utf-8"><title>Inloggen</title></head>
<body>
<h2>Inloggen</h2>
<?php if ($errors): ?>
  <ul style="color:red">
    <?php foreach ($errors as $e): ?><li><?=htmlspecialchars($e)?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>
<form method="post" action="">
  <label>Gebruikersnaam: <input name="gebruikersnaam" required value="<?=isset($_POST['gebruikersnaam']) ? htmlspecialchars($_POST['gebruikersnaam']) : ''?>"></label><br>
  <label>Wachtwoord: <input name="wachtwoord" type="password" required></label><br>
  <button type="submit">Inloggen</button>
</form>
<p><a href="register.php">Nog geen account? Registreer</a></p>
</body>
</html>
