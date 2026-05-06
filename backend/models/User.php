<?php

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email, password
            FROM users
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            "email" => $email,
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }
    
    public function isEngineer(int $id) {
    $stmt = $this->db->prepare("
        SELECT id FROM engineers WHERE user_id = :id
    ");

    $stmt->execute([
        "id" => $id
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result["id"] : -1;
    }
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT id, username, email
            FROM users
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            "id" => $id,
        ]);

        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(string $username, string $email, string $hashedPassword): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
            RETURNING id
        ");

        $stmt->execute([
            "username" => $username,
            "email" => $email,
            "password" => $hashedPassword,
        ]);

        return (int) $stmt->fetchColumn();
    }
}