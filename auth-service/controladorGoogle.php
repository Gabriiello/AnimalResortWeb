<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('631022063904-0n6jn3vcl53bi1gp1urp0o721ef0q2p0.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://animalresort.com.co/web/auth-service/controladorGoogle.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (isset($token['error'])) {
        die('Error fetching access token: ' . $token['error']);
    }
    $client->setAccessToken($token['access_token']);

    // Obtener el perfil del usuario
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();
    $email = $userInfo->email;
    $givenName = $userInfo->givenName;
    $familyName = $userInfo->familyName;
    $nombre = $givenName . ' ' . $familyName;

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "animalre", "y367}A]y){K4Cg4", "animalre_database");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die('Error de conexión: ' . $conexion->connect_error);
    }

    $usuario = $conexion->real_escape_string($email);
    $sql = $conexion->query("SELECT * FROM Usuarios WHERE email='$usuario'");

    if ($datos = $sql->fetch_object()) {
        setcookie('usuario_id', $datos->id, time() + 3600, '/'); // 'usuario_id' es el nombre de la cookie, y 3600 es la duración en segundos
        header("Location: ../menu-service/inicio.html");
        exit();
    } else {
        echo "<script>
            var confirmacion = confirm('Cuenta no encontrada, ¿desea registrarse?');
            if (confirmacion) {
                window.location.href = 'registro.php?nombre=" . urlencode($nombre) . "&email=" . urlencode($email) . "';
            } else {
                window.location.href = '../index.php';
            }
        </script>";
    }

    $conexion->close();
} else {
    echo "No se pudo autenticar al usuario.";
}
?>