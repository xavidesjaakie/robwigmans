<?php
    // Functie: classdefinitie User 
    // Auteur: Studentnaam

    require_once __DIR__ . '/db.php';

    class User{

        // Eigenschappen 
        public string $username = "";
        private string $password = "";
        
        function setPassword($password){
            $this->password = $password;
        }

        function getPassword(){
            return $this->password;
        }

        public function showUser() {
            echo "<br>Username: " . htmlspecialchars($this->username) . "<br>";
            echo "<br>Password: " . htmlspecialchars($this->password) . "<br>";
        }

        /** ----------------------------
         *  USER REGISTREREN (van variant 1)
         *  ---------------------------- */
        public function registerUser() : array {
            $errors=[];
            $pdo = getPDO();

            if(empty($this->username) || empty($this->password)){
                $errors[] = "Vul alle velden in.";
                return $errors;
            }

            // Bestaat username al?
            $stmt = $pdo->prepare("SELECT id FROM users WHERE gebruikersnaam = ?");
            $stmt->execute([$this->username]);

            if($stmt->fetch()){
                $errors[] = "Gebruikersnaam bestaat al.";
            } else {
                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare("INSERT INTO users (gebruikersnaam, password_hash, created_at) VALUES (?, ?, NOW())");
                if(!$insert->execute([$this->username, $hash])){
                    $errors[] = "Fout bij registreren, probeer later opnieuw.";
                }
            }
            return $errors;
        }

        /** ----------------------------
         *  VALIDATIE
         *  ---------------------------- */
        function validateUser(){
            $errors=[];

            if (empty($this->username)){
                $errors[] = "Invalid username";
            } 
            if (empty($this->password)){
                $errors[] = "Invalid password";
            }
            return $errors;
        }

        /** ----------------------------
         *  LOGIN (van variant 1)
         *  ---------------------------- */
        public function loginUser(): bool {
            $pdo = getPDO();

            $stmt = $pdo->prepare("SELECT id, gebruikersnaam, password_hash FROM users WHERE gebruikersnaam = ?");
            $stmt->execute([$this->username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($this->password, $user['password_hash'])) {
                if (session_status() === PHP_SESSION_NONE) session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['gebruikersnaam'] = $user['gebruikersnaam'];

                // Rehash indien nodig
                if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                    $newHash = password_hash($this->password, PASSWORD_DEFAULT);
                    $up = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                    $up->execute([$newHash, $user['id']]);
                }
                return true;
            }
            return false;
        }

        /** ----------------------------
         *  IS USER INGELOGD?
         *  ---------------------------- */
        public function isLoggedin(): bool {
            if (session_status() === PHP_SESSION_NONE) session_start();
            return !empty($_SESSION['user_id']);
        }

        /** ----------------------------
         *  GEBRUIKER OPHALEN UIT DB
         *  ---------------------------- */
        public function getUser(string $username): bool {
            $pdo = getPDO();
            $stmt = $pdo->prepare("SELECT gebruikersnaam, created_at FROM users WHERE gebruikersnaam = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $this->username = $user['gebruikersnaam'];
                return true;
            }
            return false;
        }

        /** ----------------------------
         *  LOGOUT
         *  ---------------------------- */
        public function logout(){
            if (session_status() === PHP_SESSION_NONE) session_start();
            session_unset();
            session_destroy();
        }

        public function registerUserExtra(): array {
            $errors = [];
            $pdo = getPDO();

            if (empty($this->username) || empty($this->password)) {
                $errors[] = "Vul gebruikersnaam en wachtwoord in.";
                return $errors;
            }

            $check = $pdo->prepare("SELECT id FROM users WHERE gebruikersnaam = ?");
            $check->execute([$this->username]);

            if ($check->fetch()) {
                $errors[] = "Gebruikersnaam bestaat al.";
            } else {
                $hash = password_hash($this->password, PASSWORD_DEFAULT);
                $insert = $pdo->prepare("INSERT INTO users (gebruikersnaam, password_hash, created_at) VALUES (?, ?, NOW())");
                if (!$insert->execute([$this->username, $hash])) {
                    $errors[] = "Er is iets fout gegaan bij het registreren.";
                }
            }

            return $errors;
        }

     
        public function logoutExtra() {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION = [];
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/');
        }

    }
?>
