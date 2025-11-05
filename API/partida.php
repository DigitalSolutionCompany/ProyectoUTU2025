<?php
class Partida {
    private $conexion;
    private $table = "Partida";

    public function __construct($db) {
        $this->conexion = $db;
    }

    // Crear una partida para un solo usuario
    public function crearPartida($id_usuario) {
        try {
            // Insertar la partida 
            $sql = "INSERT INTO Partida (id_usuario, fecha_inicio, fecha_fin)
                    VALUES (:id_usuario, NOW(), '0000-00-00 00:00:00')";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            $id_partida = $this->conexion->lastInsertId();

            // Registrar en tabla Juega
            $sql = "INSERT INTO Juega (id_usuario, id_partida) VALUES (:id_usuario, :id_partida)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(':id_partida', $id_partida, PDO::PARAM_INT);
            $stmt->execute();

            return $id_partida;
        } catch (PDOException $e) {
            error_log("Error al crear partida: " . $e->getMessage());
            return false;
        }
    }
}
