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
    public function insertUser($nombreUsuario, $partidasGanadas, $contrasena) {
        $sql = "insert into Usuario(nombre_usuario, partidas_ganadas, contraseña) values(:nombre_usuario, :partidas_ganadas, :contrasena);";

        $stmt = $this->conexion->prepare($sql);
        // Corrección: Usar los nombres de los marcadores de la consulta SQL
        $stmt->bindParam(':nombre_usuario', $nombreUsuario, PDO::PARAM_STR);
        $stmt->bindParam(':partidas_ganadas', $partidasGanadas, PDO::PARAM_INT);
        $stmt->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
        
        //ejecuta si tiene exito la funcion
        if ($stmt->execute()) {	
            //retorna el ultimo id de la tabla al insertar
            return $this->conexion->lastInsertId();
        }
        // Si la ejecución falla, retorna false
        return false;
    }
}
