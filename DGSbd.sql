CREATE DATABASE bd_juego;

use bd_juego;

CREATE TABLE Usuario (
   
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
   
    nombre_usuario VARCHAR(100) NOT NULL,

    partidas_ganadas INT,

    contraseña VARCHAR(20) NOT NULL
);
 insert into Usuario(nombre_usuario, partidas_ganadas, contraseña) values("pepe", 2, "pepa");