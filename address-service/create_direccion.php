<?php
require '../auth-service/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $ciudad = $_POST["ciudad"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';
    
    $idUsuario = $_COOKIE['usuario_id'] ?? ''; // Obtener el ID del usuario

    // Verificar si todos los campos están presentes
    if (empty($ciudad) || empty($direccion) || empty($descripcion)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Preparar la consulta SQL
    $consulta = "INSERT INTO Direcciones (ciudad, direccion, defecto, descripcion, usuario_direccion) VALUES (?, ?, 'true', ?, ?)";
    $stmt = $conexion->prepare($consulta);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
        exit;
    }

    // Vincular los parámetros (s = string)
    $stmt->bind_param("sssi", $ciudad, $direccion, $descripcion, $idUsuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dirección añadida con éxito.']);
        header("Location: direcciones.html");
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al añadir la dirección: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conexion->close();
}
?>

