<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
/*Define que la respuesta será JSON
Permite solicitudes CORS desde cualquier origen
Especifica que acepta método POST
Autoriza header Content-Type*/

require_once "database.php";

// Leer datos enviados desde el cliente
$data = json_decode(file_get_contents("php://input"), true);
$id_usuario = $data["id_usuario"] ?? null; // Obtener el id del usuario

if (!$id_usuario) {
    echo json_encode(["success" => false, "message" => "Falta el id del usuario."]);
    exit; // Detener ejecución si falta id_usuario
}
/*Si $id_usuario = null → error 400
Si $id_usuario = 5 → continúa*/

try {
    // Conectar a la base de datos dentro de un bloque try-catch para manejar errores
    $db = new Database();
    $conn = $db->connect();

    // Crear la partida
    $stmt = $conn->prepare("INSERT INTO Partida (id_usuario, fecha_inicio, fecha_fin) VALUES (:id_usuario, NOW(), '0000-00-00 00:00:00')");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $id_partida = $conn->lastInsertId();

    // Registrar en la tabla Juega
    $stmt = $conn->prepare("INSERT INTO Juega (id_usuario, id_partida) VALUES (?, ?)");
    $stmt->execute([$id_usuario, $id_partida]);

    // Crear el tablero asociado tras la creación de la partida con el id de esa partida(el id del tablero se genera solo)
    $stmt = $conn->prepare("INSERT INTO Tablero (id_partida) VALUES (?)");
    $stmt->execute([$id_partida]);
    $id_tablero = $conn->lastInsertId();

    // Crear los recintos por defecto
    $recintos = ["Semejanza", "Trios", "Rey", "Diferencia", "BosqueParejas", "Solitario"];//nombres de los recintos por defecto
    $stmt = $conn->prepare("INSERT INTO Recinto (nombre, id_tablero) VALUES (?, ?)");

    foreach ($recintos as $nombre) {
        $stmt->execute([$nombre, $id_tablero]);
    }
    //crea una inserción por cada recinto y los asocia al tablero creado

    // Responder con éxito y los IDs creados
    echo json_encode([
        "success" => true,
        "message" => "Partida, tablero y recintos creados correctamente.",
        "id_partida" => $id_partida,
        "id_tablero" => $id_tablero
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la base de datos: " . $e->getMessage()
    ]);
}
