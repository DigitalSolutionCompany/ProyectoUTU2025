<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "database.php";

// Leer datos enviados desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$id_usuario = $data["id_usuario"] ?? null;

if (!$id_usuario) {
    echo json_encode(["success" => false, "message" => "Falta el id del usuario."]);
    exit;
}

try {
    $db = new Database();
    $conn = $db->connect();

    // 1️⃣ Crear la partida
    $stmt = $conn->prepare("
    INSERT INTO Partida (id_usuario, fecha_inicio, fecha_fin)
    VALUES (:id_usuario, NOW(), '0000-00-00 00:00:00')");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $id_partida = $conn->lastInsertId();

    // 2️⃣ Registrar en la tabla Juega
    $stmt = $conn->prepare("INSERT INTO Juega (id_usuario, id_partida) VALUES (?, ?)");
    $stmt->execute([$id_usuario, $id_partida]);

    // 3️⃣ Crear el tablero asociado
    $stmt = $conn->prepare("INSERT INTO Tablero (id_partida) VALUES (?)");
    $stmt->execute([$id_partida]);
    $id_tablero = $conn->lastInsertId();

    // 4️⃣ Crear los recintos por defecto
    $recintos = ["Semejanza", "Trios", "Rey", "Diferencia", "BosqueParejas", "Solitario"];
    $stmt = $conn->prepare("INSERT INTO Recinto (nombre, id_tablero) VALUES (?, ?)");

    foreach ($recintos as $nombre) {
        $stmt->execute([$nombre, $id_tablero]);
    }

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
