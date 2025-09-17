<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 3600");

require_once 'registro.php';
require_once 'database.php';
// Manejar preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}


// Crear instancias
$database = new Database();
$db = $database->connect();
$usuarios = new Usuarios($db);

// Obtener método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Manejar diferentes métodos
// switch ($method) {
//     // case 'GET':
//     //     funcGet($usuarios);
//     //     break;

//     case 'POST':
//         funcPost($usuarios);
//         break;

//     default:
//         http_response_code(405);
//         echo json_encode(["mensaje" => "Método no permitido"]);
//         break;
// }

if ($method == 'POST') {
    funcPost($usuarios);
} else {
    http_response_code(405);
    echo json_encode(["mensaje" => "Método no permitido"]);
}



// Función para manejar GET
// function funcGet($usuarios)
// {
//     if (isset($_GET['nombre'])) {
//         // Validar y sanitizar input
//         $nombre = filter_input(INPUT_GET, 'nombre', FILTER_SANITIZE_STRING);
//         if (empty($nombre)) {
//             http_response_code(400);
//             echo json_encode(["mensaje" => "Nombre inválido"]);
//             return;
//         }

//         $data = $usuarios->getByName($nombre);
//         echo json_encode($data ? $data : ["mensaje" => "Usuario no encontrado"]);
//     } else {
//         $data = $usuarios->getAll();
//         echo json_encode($data);
//     }
// }

// Función para manejar POST
function funcPost($usuarios)
{
    // Lee el cuerpo de la solicitud POST, que se espera que sea un JSON
    $json = file_get_contents('php://input');
    // Decodifica el JSON en un array asociativo de PHP (el 'true' es para que sea asociativo)
    $data = json_decode($json, true);

    // Validaciones exhaustivas
    if ($data === null) {
        http_response_code(400);
        echo json_encode(["mensaje" => "JSON inválido"]);
        return;
    }

    // Campos requeridos
    $requiredFields = ["nombre_usuario", "partidas_ganadas", "contrasena"];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(["mensaje" => "Campo '$field' es requerido"]);
            return;
        }
    }

    // Validar tipos de datos
    if (!is_numeric($data["partidas_ganadas"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Campo numérico inválido"]);
        return;
    }

    // Sanitizar datos

    /*prevenir ataques de Cross-Site Scripting (XSS) al evitar que se inyectara código HTML o JavaScript malicioso*/ 
    
    $nombreJug = $data["nombre_usuario"];
    $partidasGanadas = intval($data["partidas_ganadas"]);
    $contra = $data["contrasena"];

    /* Validar fecha
    if (!DateTime::createFromFormat('Y-m-d H:i:s', $fecha)) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Formato de fecha inválido. Use Y-m-d H:i:s"]);
        return;
    }*/

    // Insertar con manejo de errores
    try {
        $result = $usuarios->insertUser($nombreJug, $partidasGanadas, $contra);

        if ($result) {
            http_response_code(201);
            echo json_encode([
                "success" => true,
                "message" => "Usuario ingresado exitosamente",
                "id" => $result
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al ingresar usuario"
            ]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Error interno del servidor",
            // "error" => $e->getMessage() // Solo en desarrollo, quitar en producción
        ]);
    }
}

