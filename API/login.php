<?php
class Usuarios {
    // Propiedad privada para almacenar la conexión a la base de datos
    private $conexion;
    // Propiedad privada que contiene el nombre de la tabla a usar
    private $table = "Usuario";
    // El constructor recibe una conexión a la base de datos y la guarda en la propiedad $conn
    public function __construct($db) {
        $this->conexion = $db;
    }
 
//validar usuario
    public function validarUser($nombreUsuario, $contrasena) {
        $sql = "SELECT * FROM Usuario WHERE nombre_usuario = :nombre_usuario LIMIT 1;";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':nombre_usuario', $nombreUsuario, PDO::PARAM_STR);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            //para trabajar con contraseñas planas (por el momento)
            if ($usuario['contrasena'] === $contrasena) {
                return $usuario; // retorna los datos del usuario
            }
            // para mas adelante cuando usemos hasheadas
            // if (password_verify($contrasena, $usuario['contraseña'])) {
            //     return $usuario;
            // }
        }
        return false; // Usuario no encontrado o contraseña incorrecta
    }
    }
