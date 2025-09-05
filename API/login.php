<?php
// Definimos la clase usuario, que nos permitirá interactuar con la tabla 'usuario' de la base de datos
class Usuarios {
    // Propiedad privada para almacenar la conexión a la base de datos
    private $conexion;
    // Propiedad privada que contiene el nombre de la tabla a usar
    private $table = "Usuario";

    // El constructor recibe una conexión a la base de datos y la guarda en la propiedad $conn
    public function __construct($bd_juego) {
        $this->conexion = $bd_juego;
    }

    // Método para validar usuarios
    public function () {
    
    }

    // Método para obtener un animal específico por su ID
    public function getById($id) {
        // Creamos la consulta SQL con un marcador de posición para el ID
        $query = "SELECT `id_animal`, `nombre_comun`, `nombre_cientifico`, `tipo`, `habitat` FROM {$this->table} WHERE id_animal = :id";
        // Preparamos la consulta usando la conexión a la base de datos
        $stmt = $this->conn->prepare($query);
        // Asociamos el valor recibido en $id al marcador ':id' en la consulta, asegurando que sea un entero
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        // Ejecutamos la consulta preparada
        $stmt->execute();
        // Obtenemos el resultado como un array asociativo (solo un registro) y lo devolvemos
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}