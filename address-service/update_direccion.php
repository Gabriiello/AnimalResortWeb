<?php
require '../auth-service/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $direccionId = $_POST["direccionId"];
    $ciudad = $_POST["ciudad"];
    $direccion = $_POST["direccion"];
    $descripcion = $_POST["descripcion"];

    // Verificar si todos los campos están presentes
    if (empty($direccionId) || empty($ciudad) || empty($direccion) || empty($descripcion)) {
        echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
        exit;
    }

    // Preparar la consulta SQL
    $consulta = "UPDATE Direcciones SET ciudad = ?, direccion = ?, descripcion = ? WHERE id = ?";
    $stmt = $conexion->prepare($consulta);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
        exit;
    }

    // Vincular los parámetros
    $stmt->bind_param("sssi", $ciudad, $direccion, $descripcion, $direccionId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: direcciones.html");
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la dirección: ' . $stmt->error]);
    }

    // Cerrar la consulta y la conexión
    $stmt->close();
    $conexion->close();
}
?>
