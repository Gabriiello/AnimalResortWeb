<?php
$conexion = new mysqli("localhost", "animalre", "y367}A]y){K4Cg4", "animalre_database");

// Verificar la conexiÃ³n
if ($conexion->connect_error) {
    die("Error en la conexi¨®n a la base de datos: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a utf8
$conexion->set_charset("utf8");
?>