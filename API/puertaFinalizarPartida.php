<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "database.php";

$data = json_decode(file_get_contents("php://input"), true);
$id_tablero = $data["id_tablero"] ?? null;
$recintos = $data["recintos"] ?? [];

if (!$id_tablero || empty($recintos)) {
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();

    // Actualizar los puntos en cada recinto
    $stmt = $conn->prepare("UPDATE Recinto SET puntos = :puntos WHERE id_tablero = :id_tablero AND nombre = :nombre");

    foreach ($recintos as $nombre => $puntos) {
        $stmt->execute([
            ":puntos" => $puntos,
            ":id_tablero" => $id_tablero,
            ":nombre" => $nombre
        ]);
    }

    echo json_encode(["success" => true, "message" => "Puntos actualizados correctamente."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
