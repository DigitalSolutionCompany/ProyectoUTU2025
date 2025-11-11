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
    //esta función recibe el nombre de usuario y la contraseña como parámetros
    //y verifica si existen en la base de datos y usa consultas preparadas para evitar inyecciones SQL
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

        }
        return false; // Usuario no encontrado o contraseña incorrecta
    }
    }
