CREATE DATABASE IF NOT EXISTS bd_juego;
USE bd_juego;


-- Tabla: Usuario

CREATE TABLE Usuario (

    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
    partidas_ganadas INT DEFAULT 0,
    contrasena VARCHAR(255) NOT NULL
);

-- Tabla: Partida

CREATE TABLE Partida (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    fecha_inicio DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_fin DATETIME NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario)
);

-- Tabla: Tablero

CREATE TABLE Tablero (
    id_tablero INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    puntos_totales INT DEFAULT 0,
    FOREIGN KEY (id_partida) REFERENCES Partida(id_partida)
);

-- Tabla: Recinto

CREATE TABLE Recinto (
    id_recinto INT AUTO_INCREMENT PRIMARY KEY,
    id_tablero INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    puntos INT DEFAULT 0,
    FOREIGN KEY (id_tablero) REFERENCES Tablero(id_tablero)
);

-- Tabla: Juega

CREATE TABLE Juega (
  id_usuario INT NOT NULL,
  id_partida INT NOT NULL,
  PRIMARY KEY (id_usuario, id_partida),
  FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
  FOREIGN KEY (id_partida) REFERENCES Partida(id_partida)
);