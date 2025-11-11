<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

require_once 'database.php';
require_once 'login.php'; 

$database = new Database();
$db = $database->connect();
$usuarios = new Usuarios($db);

//verifico que sea una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    //verifico que vengan los campos necesarios
    if (!isset($data["nombre_usuario"]) || !isset($data["contrasena"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Faltan campos"]);
        exit();
    }

    $usuario = $usuarios->validarUser($data["nombre_usuario"], $data["contrasena"]);//llamo a la funcion validarUser del archivo login.php
     //si la validacion es exitosa devuelve los datos del usuario
    if ($usuario) {
        echo json_encode([
            "success" => true,
            "message" => "Login correcto",
            "usuario" => [
               "id_usuario" => $usuario["id_usuario"],
               "nombre" => $usuario["nombre_usuario"],
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


