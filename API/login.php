<?php
// Definimos la clase usuario, que nos permitirá interactuar con la tabla 'usuario' de la base de datos
class Usuarios {
    // Propiedad privada para almacenar la conexión a la base de datos
    private $conexion;
    // Propiedad privada que contiene el nombre de la tabla a usar
    private $table = "Usuario";
    // El constructor recibe una conexión a la base de datos y la guarda en la propiedad $conn
    public function __construct($db) {
        $this->conexion = $db;
    }

//agregar usuario
    public function insertUser($nombreUsuario, $contrasena, $pdo) {
        $sql = "INSERT INTO {$this->table} (`nombre_usuario`, `contraseña`) VALUES (:nombre_usuario, :contraseña)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nombre_usuario', $nombreUsuario);
        $stmt->bindParam(':contrasena', $contrasena);
        return $stmt->execute();
    }
}