<?php
$servername = "localhost";
$username = "animalre";
$password = "y367}A]y){K4Cg4";
$dbname = "animalre_database";

if(!isset($_COOKIE['usuario_id'])) {
    echo "<script>alert('Sesi車n expirada, inicie nuevamente'); window.location.href='index.php';</script>";
    exit();
}
// Crear conexi車n
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar conexi車n
if ($conn->connect_error) {
    die("Error de conexi車n: " . $conn->connect_error);
}

$idUsuario = $_COOKIE['usuario_id'];
$sql = "SELECT  ciudad, direccion, descripcion FROM Direcciones WHERE usuario_direccion=$idUsuario";
$result = $conn->query($sql);

$data = array(); // Arreglo para almacenar los datos

if ($result->num_rows > 0) {
    // Guardar los datos en el arreglo
    while ($row = $result->fetch_assoc()) {
        // Agregar toda la fila al arreglo $data
        $data[] = $row;
    }
}
// Cerrar conexi車n
$conn->close();
// Devolver los datos en formato JSON
header('Content-Type: application/json');
echo json_encode($data);
?>