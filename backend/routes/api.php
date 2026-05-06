<?php

$method = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$path = rtrim($path, "/");

$authController = new AuthController();
$plantController = new PlantController();


if ($method === "POST" && $path === "/api/auth/login") {
    $authController->login();
    exit;
}

if ($method === "POST" && $path === "/api/auth/signup") {
    $authController->signup();
    exit;
}

if ($method === "GET" && $path === "/api/auth/me") {
    $authController->me();
    exit;
}

if ($method === "POST" && $path === "/api/auth/logout") {
    $authController->logout();
    exit;
}

if ($method === "POST" && $path === "/api/plants") {
    $plantController->AddPlants();
    exit;
}

if ($method === "GET" && $path === "/api/plants") {
    $plantController->GetAllPlants();
    exit;
}

if ($method === "GET" && preg_match("#^/api/plants/([0-9]+)$#", $path, $matches)) {
    $plantController->GetPlants((int) $matches[1]);
    exit;
}

if (
    ($method === "PUT" || $method === "PATCH") &&
    preg_match("#^/api/plants/([0-9]+)$#", $path, $matches)
) {
    $plantController->ModifyPlants((int) $matches[1]);
    exit;
}

if ($method === "DELETE" && preg_match("#^/api/plants/([0-9]+)$#", $path, $matches)) {
    $plantController->RemovePlants((int) $matches[1]);
    exit;
}

Response::json([
    "error" => "route not found",
    "method" => $method,
    "path" => $path
], 404);