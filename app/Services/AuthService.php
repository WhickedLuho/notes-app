<?php
namespace App\Services;

use App\Models\User;
use PDO;

class AuthService
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function attemptLogin(string $email, string $password): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1 AND deleted_at IS NULL");
        $stmt->execute([$email]);
        // $user = $stmt->fetchObject(User::class);
        $user = null;

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $user = new User($this->db);
            $user->fill($data); // Ez lehet egy saját metódus, ami betölti az attribútumokat
            return $user;
        }

        if ($user && password_verify($password, $user->password_hash)) {
            return $user;
        }

        return null;
    }

    public function registerUser(array $data): User
    {
        if (empty($data['email']) || empty($data['password']) || empty($data['nickname']) || empty($data['fullname'])) {
            throw new \Exception("All fields are required");
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }

        // Check if email exists
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) {
            throw new \Exception("Email already registered");
        }

        // Insert user
        $stmt = $this->db->prepare("
            INSERT INTO users (nickname, fullname, email, password_hash, is_active, created_at, modified_at)
            VALUES (?, ?, ?, ?, 1, NOW(), NULL)
        ");

        $success = $stmt->execute([
            $data['nickname'],
            $data['fullname'],
            $data['email'],
            password_hash($data['password'], PASSWORD_BCRYPT)
        ]);

        if (!$success) {
            throw new \Exception("Registration failed");
        }

        return $this->attemptLogin($data['email'], $data['password']);
    }

}