<?php
require '../auth-service/conexion.php';

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    $deleteConsulta = "DELETE FROM Direcciones WHERE id = ?";
    if ($stmt = $conexion->prepare($deleteConsulta)) {
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error al ejecutar la consulta.';
        }
        $stmt->close();
    } else {
        $response['error'] = 'Error al preparar la consulta.';
    }
}

$conexion->close();
echo json_encode($response);
?>