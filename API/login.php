<?php
// Definimos la clase usuario, que nos permitirá interactuar con la tabla 'usuario' de la base de datos
class Usuarios {
    // Propiedad privada para almacenar la conexión a la base de datos
    private $conexion;
    // Propiedad privada que contiene el nombre de la tabla a usar
    private $table = "Usuario";

    $usuario = "jugador1";

    // El constructor recibe una conexión a la base de datos y la guarda en la propiedad $conn
    public function __construct($bd_juego) {
        $this->conexion = $bd_juego;
    }

    // Método para validar usuarios

}