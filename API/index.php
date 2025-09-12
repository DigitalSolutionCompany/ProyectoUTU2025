<?php
header('Content-Type: application/json');

require_once 'login.php'; // cambia esto por tu clase real
require_once 'database.php';
$pdo = new PDO("mysql:host=localhost;dbname=tu_base", "tu_usuario", "tu_contraseña");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Leer JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Acción no especificada']);
    exit;
}

if ($data['action'] === 'insertUser') {
    $nombre = $data['nombre_usuario'] ?? '';
    $contrasena = $data['contraseña'] ?? '';

    if (empty($nombre) || empty($contrasena)) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos']);
        exit;
    }

    // Instancia la clase y llama al método
    $usuario = new Usuarios(); // Cambia esto por el nombre real
    $contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);
    $resultado = $usuario->insertUser($nombre, $contrasenaHash, $pdo);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo insertar el usuario']);
    }
}
