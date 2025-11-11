<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "database.php";

$data = json_decode(file_get_contents("php://input"), true);//leer datos JSON y convertir a array asociativo

//aca extraemos los datos necesarios
$id_tablero = $data["id_tablero"] ?? null;
$puntos = $data["puntos"] ?? [];
$recintos = $data["recintos"] ?? [];

if (!$id_tablero || empty($puntos)) {
    echo json_encode(["success" => false, "message" => "Faltan datos para actualizar los puntos"]);
    exit;
}

try {
    // Conectar a la base de datos dentro de un bloque try-catch para manejar errores
    $db = new Database();
    $conn = $db->connect();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Actualizar los puntos por recinto
    $stmtRecinto = $conn->prepare("UPDATE Recinto SET puntos = :puntos WHERE id_tablero = :id_tablero AND nombre = :nombre
    ");

    foreach ($puntos as $nombre => $valor) {
        if ($nombre === "total") continue; // Saltar el total
        $stmtRecinto->execute([
            ":puntos" => $valor,
            ":id_tablero" => $id_tablero,
            ":nombre" => $nombre
        ]);
    }
    //aca se itero sobre cada recinto y se actualizaron los puntos en la tabla Recinto
    //ejemplo de iteracion 1:
    /*{"id_tablero":"12","puntos":{...},"recintos":{...}}*/
    //entoces sql ejecuta: UPDATE Recinto SET puntos = 8 WHERE id_tablero = 12 AND nombre = 'semejanza'
    

    //  Actualizar puntos totales en la tabla Tablero
    $stmtTablero = $conn->prepare(" UPDATE Tablero  SET puntos_totales = :puntos_totales  WHERE id_tablero = :id_tablero
    ");
    $stmtTablero->execute([
        ":puntos_totales" => $puntos["total"] ?? 0, //usa el total de puntos si no existe usa 0
        ":id_tablero" => $id_tablero
    ]);

    echo json_encode(["success" => true, "message" => "Puntos actualizados correctamente."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
