<?php

class EngineerController
{
    private Engineer $engineerModel;
    
    public function __construct()
    {
        $this->engineerModel = new Engineer();
    }

    public function getAbout(): void
    {
        if (!isset($_SESSION["user_id"])) {
            Response::json([
                "error" => "unauthorized"
            ], 401);
        }

        $userId = (int) $_SESSION["user_id"];

        try {
            $engineer = $this->engineerModel->findAboutByUserId($userId);

            if (!$engineer) {
                Response::json([
                    "error" => "engineer not found !"
                ], 404);
            }

            Response::json([
                "engineer" => $engineer
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function getActivity(): void
    {
        if (!isset($_SESSION["user_id"])) {
            Response::json([
                "error" => "unauthorized"
            ], 401);
        }

        $userId = (int) $_SESSION["user_id"];

        try {
            $engineer = $this->engineerModel->findAboutByUserId($userId);

            if (!$engineer) {
                Response::json([
                    "error" => "engineer not found !"
                ], 404);
            }

            $activities = $this->engineerModel->findActivityByEngineerId((int) $engineer["id"]);

            Response::json([
                "activities" => $activities
            ], 200);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }
}