<?php
class Auth {
    public $db;

    public function __construct() {
        $this->db = new mysqli('localhost', 'root', '', 'friendzone2');

        if ($this->db->connect_error) {
            die('Database connection error: ' . $this->db->connect_error);
        }

        // Start session if it hasn't been started yet
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function signup($name, $username, $email, $password) {
        $errors = [];

        // Validate name
        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            $errors['name'] = 'Name must contain only letters and spaces.';
        }

        // Validate username
        if (empty($username)) {
            $errors['username'] = 'Username is required.';
        } elseif ($this->userExists($username, $email)) {
            $errors['username'] = 'Username or email already exists.';
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required.';
        }

        // Validate password
        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{6,}$/', $password)) {
            $errors['password'] = 'Password must be at least 6 characters long, contain at least one uppercase letter and one number.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'message' => 'Validation failed.', 'errors' => $errors];
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->db->error];
        }
        $stmt->bind_param('ssss', $name, $username, $email, $passwordHash);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Signup successful.'];
        } else {
            return ['success' => false, 'message' => 'Signup failed.'];
        }
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT id, password FROM users WHERE username = ?");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->db->error];
        }
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($userId, $passwordHash);
        $stmt->fetch();
    
        if ($stmt->num_rows > 0 && password_verify($password, $passwordHash)) {
            $_SESSION['user_id'] = $userId;
            return ['success' => true, 'message' => 'Login successful.'];
        } else {
            return ['success' => false, 'message' => 'Invalid username or password.'];
        }
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: ../index.html');
        exit;
    }

    private function userExists($username, $email) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        if (!$stmt) {
            return ['success' => false, 'message' => 'Database error: ' . $this->db->error];
        }
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }
}
?>
