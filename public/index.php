<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "users") {
    http_response_code(404);
    echo json_encode(["message" => "Url not found"]);
    exit;
}
$id = $parts[2] ?? null;

$database = new Database("mysql", "omega", "root", "root");
$database->getConnection();
$userService = new UserService($database);
$controller = new UserController($userService);
$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);