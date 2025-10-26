<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once 'database.php';
require_once 'login.php'; // Aquí está la clase Usuarios con validarUser()

$database = new Database();
$db = $database->connect();
$usuarios = new Usuarios($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["nombre_usuario"]) || !isset($data["contrasena"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Faltan campos"]);
        exit();
    }

    $usuario = $usuarios->validarUser($data["nombre_usuario"], $data["contrasena"]);

    if ($usuario) {
        echo json_encode([
            "success" => true,
            "message" => "Login correcto",
            "usuario" => [
                "id" => $usuario["id_usuario"],
                "nombre" => $usuario["nombre_usuario"],
                "partidas_ganadas" => $usuario["partidas_ganadas"]
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
}


