<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Manejo de preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'registro.php';
require_once 'database.php';

// Crear instancia de base de datos
$database = new Database();
$db = $database->connect();
$usuarios = new Usuarios($db);

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido"]);
    exit();
}

// Leer y decodificar el JSON recibido
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(["mensaje" => "JSON inválido"]);
    exit();
}

// Validar campos requeridos
$requiredFields = ["nombre_usuario", "contrasena"];
foreach ($requiredFields as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Campo '$field' es requerido"]);
        exit();
    }
}



// Sanitizar e insertar
$nombreJug = $data["nombre_usuario"];
$contra = $data["contrasena"];

try {
    $result = $usuarios->insertUser($nombreJug, $contra);

    if ($result) {
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Usuario registrado exitosamente",
            "id" => $result
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Error al registrar usuario"
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor"
    ]);
}
