<?php
$servername = "localhost";
$username = "animalre";
$password = "y367}A]y){K4Cg4";
$dbname = "animalre_database";

if(!isset($_COOKIE['usuario_id'])) {
    echo "<script>alert('Sesión expirada, inicie nuevamente'); window.location.href='index.php';</script>";
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$idUsuario = $_COOKIE['usuario_id'];
$sql = "SELECT  id, nombre_mascota, anios, meses, raza, genero, peso_mascota FROM Mascotas WHERE usuario_mascota=$idUsuario";
$result = $conn->query($sql);

$data = array(); // Arreglo para almacenar los datos

if ($result->num_rows > 0) {
    // Guardar los datos en el arreglo
    while ($row = $result->fetch_assoc()) {
        $idRaza = $row["raza"];
        $sql_extra = "SELECT raza FROM Razas WHERE id = $idRaza";
        $result_extra = $conn->query($sql_extra);
        $extra_data = "";

        if ($result_extra->num_rows > 0) {
            $row_extra = $result_extra->fetch_assoc();
            $extra_data = $row_extra["raza"];
        }
        $row["raza"] = $extra_data;
        $data[] = $row;
    }
}
$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>