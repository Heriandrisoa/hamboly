<?php

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        $data = Request::json();

        $email = strtolower(trim($data["email"] ?? ""));
        $password = $data["password"] ?? "";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            Response::json([
                "error" => "invalid inputs !"
            ], 400);
        }

        try {
            $user = $this->userModel->findByEmail($email);

            if (!$user) {
                Response::json([
                    "error" => "user not found !"
                ], 404);
            }

            if (!password_verify($password, $user["password"])) {
                Response::json([
                    "error" => "bad credential !"
                ], 401);
            }

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $engineerId = $this->userModel->isEngineer($user["id"]);
            // echo "aizee ny engineerID ane ka $engineerId";
            
            Response::json([
                "msg" => "logged in",
                "user" => [
                    "id" => $user["id"],
                    "username" => $user["username"],
                    "email" => $user["email"],
                    "engineerId" => (int)$engineerId
                ]
            ], 200);
        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function signup(): void
    {
        $data = Request::json();

        $username = trim($data["username"] ?? "");
        $email = strtolower(trim($data["email"] ?? ""));
        $password = $data["password"] ?? "";

        // filtrer les champs
        if (strlen($username) < 2 || strlen($username) > 20 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || strlen($password) > 50) 
        {
            Response::json([
                "error" => "invalid inputs !"
            ], 400);
        }

        try {
            $existingUser = $this->userModel->findByEmail($email);
            
            // evite 2 comptes pour un seul email
            if ($existingUser) {
                Response::json([
                    "error" => "email already used !"
                ], 400);
            }
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, [
                "cost" => 10
            ]);
            $userId = $this->userModel->create($username, $email, $hashedPassword);
            // setup session
            session_regenerate_id(true);
            $_SESSION["user_id"] = $userId;

            Response::json([
                "msg" => "successfully added !",
                "user" => [
                    "id" => $userId,
                    "username" => $username,
                    "email" => $email,
                ]
            ], 201);

        } catch (Throwable $error) {
            Response::json([
                "error" => "internal server error"
            ], 500);
        }
    }

    public function me(): void // get de mes data a partir de ma session
    {
        if (!isset($_SESSION["user_id"])) {
            Response::json([
                "error" => "unauthorized"
            ], 401);
        }

        $userId = (int) $_SESSION["user_id"];
        $user = $this->userModel->findById($userId);

        if (!$user) { // detruire les sessions illegales
            session_destroy();

            Response::json([
                "error" => "unauthorized"
            ], 401);
        }

        Response::json([
            "user" => $user
        ], 200);
    }

    public function logout(): void
    {
        // effacer session
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        Response::json([
            "msg" => "logged out"
        ], 200);
    }
}