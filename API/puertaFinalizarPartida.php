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
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar los puntos en cada recinto
    $stmt = $conn->prepare("UPDATE Recinto SET puntos = :puntos WHERE id_tablero = :id_tablero AND nombre = :nombre");

    foreach ($recintos as $nombre => $puntos) {
        if (is_array($puntos)) {
            $puntos = array_sum($puntos);
        }

        $stmt->execute([
            ":puntos" => $puntos,
            ":id_tablero" => $id_tablero,
            ":nombre" => $nombre
        ]);

         // Verificar si la consulta afectó filas
    if ($stmt->rowCount() === 0) {
        echo json_encode(["success" => false, "message" => "No se actualizó ningún registro para el recinto: $nombre"]);
        exit;
    }

    }

    echo json_encode(["success" => true, "message" => "Puntos actualizados correctamente."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
