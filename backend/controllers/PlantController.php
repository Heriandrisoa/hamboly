<?php

class PlantController
{
    private Plant $plantModel;

    public function __construct()
    {
        $this->plantModel = new Plant();
    }

    public function AddPlants(): void
    {
        $data = Request::json();

        $commonName = trim($data["common_name"] ?? "");
        $scientificName = trim($data["scientific_name"] ?? "");
        $description = trim($data["description"] ?? "");
        $minSpaceRequired = $data["min_space_required"] ?? null;
        $waterRequirement = trim($data["water_requirement"] ?? "");
        $digits = $data["digits"] ?? null;

        if (
            strlen($commonName) < 2 ||
            strlen($scientificName) < 2 ||
            strlen($description) < 2 ||
            $minSpaceRequired === null ||
            strlen($waterRequirement) < 2 ||
            $digits === null
        ) {
            Response::json([
                "error" => "invalid inputs !"
            ], 400);
        }

        try {
            $plantId = $this->plantModel->create([
                "common_name" => $commonName,
                "scientific_name" => $scientificName,
                "description" => $description,
                "min_space_required" => $minSpaceRequired,
                "water_requirement" => $waterRequirement,
                "digits" => $digits,
            ]);

            Response::json([
                "msg" => "plant successfully added !",
                "plant" => [
                    "id" => $plantId,
                    "common_name" => $commonName,
                    "scientific_name" => $scientificName,
                    "description" => $description,
                    "min_space_required" => $minSpaceRequired,
                    "water_requirement" => $waterRequirement,
                    "digits" => $digits,
                ]
            ], 201);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function GetPlants(?int $id = null): void
    {
        $id = $id ?? (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            Response::json([
                "error" => "invalid plant id !"
            ], 400);
        }

        try {
            $plant = $this->plantModel->findById($id);

            if (!$plant) {
                Response::json([
                    "error" => "plant not found !"
                ], 404);
            }

            Response::json([
                "plant" => $plant
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function GetAllPlants(): void
    {
        try {
            $plants = $this->plantModel->findAll();

            Response::json([
                "plants" => $plants
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function ModifyPlants(?int $id = null): void
    {
        $id = $id ?? (int) ($_GET["id"] ?? 0);
        $data = Request::json();

        if ($id <= 0) {
            Response::json([
                "error" => "invalid plant id !"
            ], 400);
        }

        $commonName = trim($data["common_name"] ?? "");
        $scientificName = trim($data["scientific_name"] ?? "");
        $description = trim($data["description"] ?? "");
        $minSpaceRequired = $data["min_space_required"] ?? null;
        $waterRequirement = trim($data["water_requirement"] ?? "");
        $digits = $data["digits"] ?? null;

        if (
            strlen($commonName) < 2 ||
            strlen($scientificName) < 2 ||
            strlen($description) < 2 ||
            $minSpaceRequired === null ||
            strlen($waterRequirement) < 2 ||
            $digits === null
        ) {
            Response::json([
                "error" => "invalid inputs !"
            ], 400);
        }

        try {
            $existingPlant = $this->plantModel->findById($id);

            if (!$existingPlant) {
                Response::json([
                    "error" => "plant not found !"
                ], 404);
            }

            $updated = $this->plantModel->update($id, [
                "common_name" => $commonName,
                "scientific_name" => $scientificName,
                "description" => $description,
                "min_space_required" => $minSpaceRequired,
                "water_requirement" => $waterRequirement,
                "digits" => $digits,
            ]);

            Response::json([
                "msg" => $updated ? "plant successfully modified !" : "no changes detected"
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function RemovePlants(?int $id = null): void
    {
        $id = $id ?? (int) ($_GET["id"] ?? 0);

        if ($id <= 0) {
            Response::json([
                "error" => "invalid plant id !"
            ], 400);
        }

        try {
            $existingPlant = $this->plantModel->findById($id);

            if (!$existingPlant) {
                Response::json([
                    "error" => "plant not found !"
                ], 404);
            }

            $this->plantModel->delete($id);

            Response::json([
                "msg" => "plant successfully removed !"
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }
}