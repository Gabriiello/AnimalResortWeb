<?php
$servername = "localhost";
$username = "animalre";
$password = "y367}A]y){K4Cg4";
$dbname = "animalre_database";

if(!isset($_COOKIE['usuario_id'])) {
    echo "<script>alert('Sesión expirada, inicie nuevamente'); window.location.href='index.php';</script>";
    exit();
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array('error' => 'Error de conexión: ' . $conn->connect_error)));
}

$idUsuario = $conn->real_escape_string($_COOKIE['usuario_id']);
$sql = "SELECT id, nombre_mascota FROM Mascotas WHERE usuario_mascota = '$idUsuario'";
$result = $conn->query($sql);

$data = array(); // Array to store the data

if ($result) {
    // Save the data in the array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    // Handle query error
    echo json_encode(array('error' => 'Error en la consulta: ' . $conn->error));
}

// Close connection
$conn->close();

// Return data in JSON format
header('Content-Type: application/json');
echo json_encode($data);
?>