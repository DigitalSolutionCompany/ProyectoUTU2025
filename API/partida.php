<?php
// Definimos la clase usuario, que nos permitirá interactuar con la tabla 'usuario' de la base de datos
class Partida {
    // Propiedad privada para almacenar la conexión a la base de datos
    private $conexion;
    // Propiedad privada que contiene el nombre de la tabla a usar
    private $table = "Partida";
    // El constructor recibe una conexión a la base de datos y la guarda en la propiedad $conn
    public function __construct($db) {
        $this->conexion = $db;
    }

//agregar usuario 
    public function crearPartida($cantidadJugadores, $jugadores, $fechaInicio, $fechaFin) {
        $jugadores = is_array($jugadores) ? json_encode($jugadores) : $jugadores;
        $sql = "insert into Partida(cantidad_jugadores, jugadores, fecha_inicio, fecha_fin) values(:cantidad_jugadores, :jugadores, :fecha_inicio, :fecha_fin);";

        $stmt = $this->conexion->prepare($sql);
        // Corrección: Usar los nombres de los marcadores de la consulta SQL
        $stmt->bindParam(':cantidad_jugadores', $cantidadJugadores, PDO::PARAM_INT);
        $stmt->bindParam(':jugadores', $jugadores, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_inicio', $fechaInicio, PDO::PARAM_STR);
        $stmt->bindParam(':fecha_fin', $fechaFin, PDO::PARAM_STR);
    
        //ejecuta si tiene exito la funcion
        if ($stmt->execute()) {	
            //retorna el ultimo id de la tabla al insertar
            return $this->conexion->lastInsertId();
        }
        // Si la ejecución falla, retorna false
        return false;
    }
}