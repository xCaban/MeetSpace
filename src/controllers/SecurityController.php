<?php
require_once __DIR__ . "/../../Database.php";

class SecurityController {
    public function login() {
        require_once __DIR__ . "/../views/security/login.php";
    }

    public function authenticate() {
        if (!isset($_POST['email']) || !isset($_POST['password'])) {
            $_SESSION['error'] = "Wszystkie pola są wymagane";
            header('Location: /login');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            header('Location: /rooms');
        } else {
            $_SESSION['error'] = "Nieprawidłowy email lub hasło";
            header('Location: /login');
        }
    }

    public function register() {
        require_once __DIR__ . "/../views/security/register.php";
    }

    public function registerUser() {
        // Debugowanie
        error_log("Register attempt - POST data: " . print_r($_POST, true));

        if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['name']) || !isset($_POST['password_confirm'])) {
            error_log("Missing required fields in registration form");
            $_SESSION['error'] = "Wszystkie pola są wymagane";
            header('Location: /register');
            return;
        }

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $name = $_POST['name'];

        if ($password !== $password_confirm) {
            error_log("Password mismatch in registration");
            $_SESSION['error'] = "Hasła nie są identyczne";
            header('Location: /register');
            return;
        }

        if (strlen($password) < 6) {
            error_log("Password too short in registration");
            $_SESSION['error'] = "Hasło musi mieć co najmniej 6 znaków";
            header('Location: /register');
            return;
        }

        try {
            error_log("Attempting database connection");
            $db = Database::getInstance()->getConnection();
            
            // Sprawdź czy email już istnieje
            error_log("Checking if email exists: " . $email);
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                error_log("Email already exists: " . $email);
                $_SESSION['error'] = "Ten email jest już zarejestrowany";
                header('Location: /register');
                return;
            }

            // Dodaj nowego użytkownika
            error_log("Attempting to insert new user");
            $stmt = $db->prepare("INSERT INTO users (email, password, name, role) VALUES (:email, :password, :name, :role)");
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            error_log("Password hashed successfully");
            
            $result = $stmt->execute([
                'email' => $email,
                'password' => $hashedPassword,
                'name' => $name,
                'role' => 'user'
            ]);

            if ($result) {
                error_log("User registered successfully: " . $email);
                $_SESSION['success'] = "Rejestracja zakończona sukcesem. Możesz się teraz zalogować.";
                header('Location: /login');
            } else {
                error_log("Database insert failed");
                throw new Exception("Błąd podczas wykonywania zapytania");
            }
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "Wystąpił błąd podczas rejestracji: " . $e->getMessage();
            header('Location: /register');
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
    }
} 