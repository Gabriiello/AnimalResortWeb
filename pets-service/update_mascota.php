<?php
require '../auth-service/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mascotaId = $_POST["mascotaId"];
    $nombre_mascota = $_POST["nombre_mascota"];
    $anios = $_POST["anios"];
    $meses = $_POST["meses"];
    $genero = $_POST["genero"];

    if (empty($mascotaId) || empty($nombre_mascota) || empty($anios) || empty($meses)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    $anios = (int)$anios;
    $meses = (int)$meses;

    $consulta = "UPDATE Mascotas SET nombre_mascota = ?, anios = ?, meses = ?, genero=? WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
        exit;
    }

    $stmt->bind_param("siisi", $nombre_mascota, $anios, $meses, $genero, $mascotaId);

    if ($stmt->execute()) {
        header("Location: mascotas.html");
    } else {
       echo "Error al actualizar la mascota: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>