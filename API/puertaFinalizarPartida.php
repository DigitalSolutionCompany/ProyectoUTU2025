<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "database.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_tablero = $data["id_tablero"] ?? null;
$puntos = $data["puntos"] ?? [];
$recintos = $data["recintos"] ?? [];

if (!$id_tablero || empty($puntos)) {
    echo json_encode(["success" => false, "message" => "Faltan datos para actualizar los puntos"]);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar los puntos por recinto
    $stmtRecinto = $conn->prepare("
        UPDATE Recinto 
        SET puntos = :puntos 
        WHERE id_tablero = :id_tablero AND nombre = :nombre
    ");

    foreach ($puntos as $nombre => $valor) {
        if ($nombre === "total") continue; // Saltar el total
        $stmtRecinto->execute([
            ":puntos" => $valor,
            ":id_tablero" => $id_tablero,
            ":nombre" => $nombre
        ]);
    }

    //  Actualizar puntos totales en la tabla Tablero
    $stmtTablero = $conn->prepare("
        UPDATE Tablero 
        SET puntos_totales = :puntos_totales 
        WHERE id_tablero = :id_tablero
    ");
    $stmtTablero->execute([
        ":puntos_totales" => $puntos["total"] ?? 0,
        ":id_tablero" => $id_tablero
    ]);

    echo json_encode(["success" => true, "message" => "Puntos actualizados correctamente."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
