<?php
require '../auth-service/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_mascota = $_POST["nombre_mascota"];
    $anios = $_POST["anios"];
    $meses = $_POST["meses"];
    $genero = $_POST["genero"];
    $tamaño = $_POST["tamaño"];
    
    $idUsuario = $_COOKIE['usuario_id'] ?? ''; // Obtener el ID del usuario

    // Verificar si todos los campos están presentes
    if (empty($anios) || empty($meses) || empty($genero) || empty($tamaño) || empty($nombre_mascota)) {
        echo json_encode(['success' => false, 'message' => 'alo los campos son obligatorios.']);
        exit;
    }

    // Preparar la consulta SQL
    $consulta = "INSERT INTO Mascotas (nombre_mascota, anios, meses, raza,genero, peso_mascota, usuario_mascota,estado_mascota) VALUES (?, ?,?,'1', ?, ?, ?, 'activo')";
    $stmt = $conexion->prepare($consulta);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
        exit;
    }

    // Vincular los parámetros (s = string)
    $stmt->bind_param("siissi", $nombre_mascota, $anios, $meses,$genero,$tamaño, $idUsuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Mascota añadida con éxito.']);
        header("Location: mascotas.html");
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al añadir la Mascota: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conexion->close();
}
?>
