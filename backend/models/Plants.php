<?php

class Plant
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO plants (
                common_name,
                scientific_name,
                description,
                min_space_required,
                water_requirement,
                digits
            )
            VALUES (
                :common_name,
                :scientific_name,
                :description,
                :min_space_required,
                :water_requirement,
                :digits
            )
            RETURNING id
        ");

        $stmt->execute([
            "common_name" => $data["common_name"],
            "scientific_name" => $data["scientific_name"],
            "description" => $data["description"],
            "min_space_required" => $data["min_space_required"],
            "water_requirement" => $data["water_requirement"],
            "digits" => $data["digits"],
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                common_name,
                scientific_name,
                description,
                min_space_required,
                water_requirement,
                digits
            FROM plants
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            "id" => $id,
        ]);

        $plant = $stmt->fetch();

        return $plant ?: null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT
                id,
                common_name,
                scientific_name,
                description,
                min_space_required,
                water_requirement,
                digits
            FROM plants
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE plants
            SET
                common_name = :common_name,
                scientific_name = :scientific_name,
                description = :description,
                min_space_required = :min_space_required,
                water_requirement = :water_requirement,
                digits = :digits
            WHERE id = :id
        ");

        $stmt->execute([
            "id" => $id,
            "common_name" => $data["common_name"],
            "scientific_name" => $data["scientific_name"],
            "description" => $data["description"],
            "min_space_required" => $data["min_space_required"],
            "water_requirement" => $data["water_requirement"],
            "digits" => $data["digits"],
        ]);

        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM plants
            WHERE id = :id
        ");

        $stmt->execute([
            "id" => $id,
        ]);

        return $stmt->rowCount() > 0;
    }
}