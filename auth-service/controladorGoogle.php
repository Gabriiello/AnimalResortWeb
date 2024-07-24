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
    var_dump($userInfo); 
    $email = $userInfo->email;

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "animalre", "y367}A]y){K4Cg4", "animalre_database");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die('Error de conexión: ' . $conexion->connect_error);
    }

    $usuario = $conexion->real_escape_string($email);
    $sql = $conexion->query("SELECT * FROM Usuarios WHERE email='$usuario'");

    if ($datos = $sql->fetch_object()) {
        setcookie('usuario_id', $datos->id, time() + 3600, '/'); 
        $_SESSION['usuario_id'] = $datos->id; // Guardar el ID del usuario en sesión
        header("Location: ../menu-service/inicio.html");
        exit();
    } else {
        // Si el usuario no existe en la base de datos, podrías redirigirlo a una página de registro
        header("Location: registro.php?nombre=" . urlencode($email) . "&email=" . urlencode($email));
        exit();
    }

    $conexion->close();
} else {
    echo "No se pudo autenticar al usuario.";
}
?>