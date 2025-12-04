<?php
// functions.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php'; // jouw $conn MySQLi

// Haalt een gebruiker op via gebruikersnaam
function haal_gebruiker_op_via_gebruikersnaam(string $gebruikersnaam) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, gebruikersnaam, password_hash FROM users WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc() ?: false;
}

// Registreer nieuwe gebruiker
function registreer_gebruiker(string $gebruikersnaam, string $wachtwoord, string $wachtwoord2): array {
    global $conn;
    $errors = [];

    $gebruikersnaam = trim($gebruikersnaam);

    if ($gebruikersnaam === '' || $wachtwoord === '') {
        $errors[] = 'Vul gebruikersnaam en wachtwoord in.';
    }
    if ($wachtwoord !== $wachtwoord2) {
        $errors[] = 'Wachtwoorden komen niet overeen.';
    }
    if (strlen($wachtwoord) < 8) {
        $errors[] = 'Gebruik een wachtwoord van minimaal 8 tekens.';
    }
    if (!empty($errors)) return ['success'=>false, 'errors'=>$errors];

    if (haal_gebruiker_op_via_gebruikersnaam($gebruikersnaam)) {
        return ['success'=>false, 'errors'=>['Gebruikersnaam bestaat al.']];
    }

    $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (gebruikersnaam, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $gebruikersnaam, $hash);
    $stmt->execute();

    $_SESSION['gebruiker_id'] = $stmt->insert_id;
    $_SESSION['gebruikersnaam'] = $gebruikersnaam;
    session_regenerate_id(true);

    return ['success'=>true, 'errors'=>[]];
}

// Inloggen
function log_in_gebruiker(string $gebruikersnaam, string $wachtwoord): array {
    $errors = [];
    $user = haal_gebruiker_op_via_gebruikersnaam($gebruikersnaam);

    if ($user && password_verify($wachtwoord, $user['password_hash'])) {
        $_SESSION['gebruiker_id'] = $user['id'];
        $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];
        session_regenerate_id(true);
        return ['success'=>true, 'errors'=>[]];
    }

    $errors[] = 'Ongeldige gebruikersnaam of wachtwoord.';
    return ['success'=>false, 'errors'=>$errors];
}

// Uitloggen
function log_uit_gebruiker(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time()-42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

// Helpers
function is_ingelogd(): bool {
    return !empty($_SESSION['gebruiker_id']);
}

function require_login(): void {
    if (!is_ingelogd()) {
        header('Location: login.php');
        exit;
    }
}

function huidige_gebruiker_naam(): ?string {
    return $_SESSION['gebruikersnaam'] ?? null;
}
