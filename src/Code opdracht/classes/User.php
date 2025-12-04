<?php
declare(strict_types=1);

/**
 * Functie: classdefinitie User
 * Auteur: Studentnaam
 *
 * Opmerkingen:
 * - Dit is een self-contained voorbeeld. Database-operaties (SELECT/INSERT) zijn
 *   als comments/plaatsaanduidingen toegevoegd — vervang die door echte DB-code.
 * - validateUser() controleert nu of username tussen 3 en 50 tekens is.
 */

class User {

    // Eigenschappen
    public string $username = "";
    public string $email = "";
    private string $password = "";

    // Optioneel: rol, id etc. kunnen hier toegevoegd worden
    // public string $role = '';
    // public int $id = 0;

    // Setter / getter voor password (in echte app: werk met hashes)
    public function setPassword(string $password): void {
        // Bewaar alleen gehashte wachtwoorden in productie: password_hash()
        $this->password = $password;
    }

    public function getPassword(): string {
        // In productie nooit het gehashte wachtwoord 'echo'en; hier alleen voor demo
        return $this->password;
    }

    // Toon gebruiker (debugging)
    public function showUser(): void {
        echo "<br>Username: " . htmlspecialchars($this->username, ENT_QUOTES | ENT_SUBSTITUTE) . "<br>";
        echo "<br>Password: " . htmlspecialchars($this->password, ENT_QUOTES | ENT_SUBSTITUTE) . "<br>";
        echo "<br>Email: " . htmlspecialchars($this->email, ENT_QUOTES | ENT_SUBSTITUTE) . "<br>";
    }

    /**
     * registerUser
     * Probeert de user te registreren.
     * Retourneert een array met foutmeldingen (leeg = success).
     */
    public function registerUser(): array {
        $errors = [];

        // Valideer invoer
        $errors = array_merge($errors, $this->validateUser());

        if (!empty($errors)) {
            return $errors;
        }

        // ----------------------------
        // HIER DE DATABASE-CHECKS
        // ----------------------------
        // Voorbeeld (pseudocode):
        // $db = getDatabaseConnection();
        // $stmt = $db->prepare("SELECT COUNT(*) FROM user WHERE username = :username");
        // $stmt->execute([':username' => $this->username]);
        // if ($stmt->fetchColumn() > 0) {
        //     $errors[] = "Username bestaat al.";
        //     return $errors;
        // }
        //
        // Sla gebruiker op (wachtwoord gehasht):
        // $hash = password_hash($this->password, PASSWORD_DEFAULT);
        // $stmt = $db->prepare("INSERT INTO user (username, password, email) VALUES (:username, :password, :email)");
        // $stmt->execute([':username'=>$this->username, ':password'=>$hash, ':email'=>$this->email]);
        //
        // Als insert faalt: $errors[] = 'Registratie mislukt, probeer later opnieuw.';

        // Omdat we hier geen echte DB hebben: neem aan dat alles gelukt is.
        return $errors;
    }

    /**
     * validateUser
     * Valideert username en password. Retourneert array met foutmeldingen.
     * Username moet tussen 3 en 50 tekens zijn.
     */
    public function validateUser(): array {
        $errors = [];

        // Username niet leeg
        if (empty($this->username)) {
            $errors[] = "Invalid username";
        } else {
            // Controleer lengte username tussen 3 en 50 tekens
            $len = mb_strlen($this->username, 'UTF-8');
            if ($len < 3 || $len > 50) {
                $errors[] = "Username moet tussen 3 en 50 tekens lang zijn.";
            }

            // Eventueel: extra validaties (alleen letters/cijfers/underscore)
            // if (!preg_match('/^[A-Za-z0-9_]+$/', $this->username)) {
            //     $errors[] = "Username bevat ongeldige tekens. Gebruik letters, cijfers en underscore.";
            // }
        }

        // Password niet leeg
        if (empty($this->password)) {
            $errors[] = "Invalid password";
        } else {
            // Optionele extra password checks (lengte, complexity)
            // if (strlen($this->password) < 6) $errors[] = "Wachtwoord moet minstens 6 tekens hebben.";
        }

        return $errors;
    }

    /**
     * validateLogin
     * Alias voor validateUser() om backward compatibility te behouden
     */
    public function validateLogin(): array {
        return $this->validateUser();
    }

    /**
     * loginUser
     * Probeert in te loggen; retourneert true bij succes, false bij mislukking.
     * In echte toepassing: controleer password met password_verify()
     */
    public function loginUser(): bool {
        // ----------------------------
        // HIER DATABASE-LOGICA
        // ----------------------------
        // Pseudocode:
        // $db = getDatabaseConnection();
        // $stmt = $db->prepare("SELECT id, username, password_hash, email FROM user WHERE username = :username LIMIT 1");
        // $stmt->execute([':username'=>$this->username]);
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // if ($row && password_verify($this->password, $row['password_hash'])) {
        //     // vul eigenschappen en start sessie
        //     $this->username = $row['username'];
        //     $this->email = $row['email'];
        //     session_start();
        //     $_SESSION['user'] = $row['username'];
        //     return true;
        // }
        // return false;

        // Voor demo zonder DB: false teruggeven zodat caller weet dat er geen echte login is
        return false;
    }

    /**
     * isLoggedin
     * Controleert of gebruiker is ingelogd via sessie
     */
    public function isLoggedin(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']) && !empty($_SESSION['user']);
    }

    /**
     * getUser
     * Vult object-eigenschappen op basis van username (van DB).
     * Retourneert true indien gevonden, anders false.
     */
    public function getUser(string $username): bool {
        // ----------------------------
        // HIER DATABASE-SELECT
        // ----------------------------
        // Pseudocode:
        // $db = getDatabaseConnection();
        // $stmt = $db->prepare("SELECT id, username, email, password_hash FROM user WHERE username = :username LIMIT 1");
        // $stmt->execute([':username' => $username]);
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // if ($row) {
        //     $this->username = $row['username'];
        //     $this->email = $row['email'];
        //     $this->password = $row['password_hash'];
        //     return true;
        // }
        //
        // return false;

        // Geen DB: simulate niet gevonden
        return false;
    }

    /**
     * logout
     * Vernietigt de sessie en unset user-variabelen
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Unset all of the session variables.
        $_SESSION = [];

        // If it's desired to kill the session, also delete the session cookie.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }
}
?>
