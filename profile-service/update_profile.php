<?php
require '../auth-service/conexion.php';

if (!isset($_COOKIE['usuario_id'])) {
    echo "<script>alert('Sesión expirada, inicie nuevamente'); window.location.href='../index.php';</script>";
    exit();
}

$idUsuario = $_COOKIE['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $documento = $_POST['documento'];

    $updateConsulta = "UPDATE Usuarios SET nombre = ?, celular = ?, documento = ? WHERE id = ?";
    $stmt = $conexion->prepare($updateConsulta);
    $stmt->bind_param("sssi", $nombre, $telefono, $documento, $idUsuario);

    if ($stmt->execute()) {
        echo "<script>alert('Perfil actualizado correctamente'); window.location.href='perfil.php';</script>";
    } else {
        echo "Error al actualizar el perfil: " . $conexion->error;
    }

    $stmt->close();
}

$conexion->close();
?>