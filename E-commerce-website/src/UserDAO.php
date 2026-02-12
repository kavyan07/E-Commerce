<?php
/**
 * User Data Access Object
 */
require_once __DIR__ . '/../includes/db.php';

class UserDAO
{
    private $db;

    public function __construct()
    {
        $this->db = getDb();
    }

    public function createUser($data)
    {
        try {
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$data['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'Email already registered.'];
            }

            $sql = "INSERT INTO users (first_name, last_name, email, password, phone, created_at) 
                    VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT), // Securing password!
                $data['phone']
            ]);

            return ['success' => true, 'id' => $this->db->lastInsertId()];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'Database error.'];
        }
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Don't keep hash in session
            return ['success' => true, 'user' => $user];
        }

        return ['success' => false, 'message' => 'Invalid email or password.'];
    }
}
