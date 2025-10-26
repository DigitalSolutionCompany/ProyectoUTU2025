<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'partida.php';
require_once 'database.php';

// Crear conexión
$database = new Database();
$db = $database->connect();
$partidas = new Partida($db);

// Solo permitir POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit();
}

// Leer JSON del cuerpo de la solicitud
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "JSON inválido"]);
    exit();
}

// Validar campos requeridos
$requiredFields = ["cantidad_jugadores", "jugadores", "fecha_inicio", "fecha_fin"];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || $data[$field] === "") {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Campo '$field' es requerido"]);
        exit();
    }
}

// Validar tipo de dato
if (!is_numeric($data["cantidad_jugadores"])) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Campo 'cantidad_jugadores' debe ser numérico"]);
    exit();
}

// Sanitizar
$cantidad_jugadores = intval($data["cantidad_jugadores"]);
$jugadores = $data["jugadores"]; // ya viene como string JSON
$fecha_inicio = $data["fecha_inicio"];
$fecha_fin = $data["fecha_fin"];

try {
    
    // Insertar la partida
    $id_partida = $partidas->crearPartida($cantidad_jugadores, $jugadores, $fecha_inicio, $fecha_fin);

    if ($id_partida) {
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Partida registrada correctamente",
            "id_partida" => $id_partida
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error al registrar la partida"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor",
        "error" => $e->getMessage() // visible en modo debug
    ]);
}

