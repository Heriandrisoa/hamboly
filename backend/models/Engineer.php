<?php

class Engineer
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function findAboutByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                e.id,
                e.first_name,
                e.last_name,
                e.phone,
                e.year_experience,
                u.username,
                u.email
            FROM engineers e
            INNER JOIN users u ON u.id = e.user_id
            WHERE e.user_id = :user_id
            LIMIT 1
        ");

        $stmt->execute([
            "user_id" => $userId,
        ]);

        $engineer = $stmt->fetch();

        return $engineer ?: null;
    }

    public function findActivityByEngineerId(int $engineerId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.time,
                p.id AS plant_id,
                p.common_name,
                p.scientific_name,
                p.description
            FROM modify m
            INNER JOIN plants p ON p.id = m.plant_id
            WHERE m.engineer_id = :engineer_id
            ORDER BY m.time DESC
        ");

        $stmt->execute([
            "engineer_id" => $engineerId,
        ]);

        return $stmt->fetchAll();
    }
}