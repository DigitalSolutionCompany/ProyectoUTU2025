CREATE DATABASE bd_juego;

use bd_juego;

CREATE TABLE Usuario (
   
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
   
    nombre_usuario VARCHAR(100) NOT NULL,

    partidas_ganadas INT,

    contraseña VARCHAR(20) NOT NULL
);
 insert into Usuario(nombre_usuario, partidas_ganadas, contraseña) values("pepe", 2, "pepa");

 CREATE table Partida (
   
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
   
    cantidad_jugadores INT,

    jugadores VARCHAR(255),
   
    fecha_inicio DATETIME,
   
    fecha_fin DATETIME
 );

CREATE TABLE Tablero(
    id_tablero INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    FOREIGN KEY (id_partida) REFERENCES Partida(id_partida)
);

CREATE TABLE Recinto(
    id_recinto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_tablero INT NOT NULL,
    FOREIGN KEY (id_tablero) REFERENCES Tablero(id_tablero)
);

CREATE TABLE Juega(
    id_usuario INT NOT NULL,
    id_partida INT NOT NULL,
    PRIMARY KEY (id_usuario, id_partida),
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_partida) REFERENCES Partida(id_partida)
);
