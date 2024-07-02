<?php
if (!empty($_POST["btn"])) {
    if (empty($_POST["email"])) {
        echo '<div class="alert alert-danger">Ingresa una dirección de correo</div>';
    } else {
        $usuario = $_POST["email"];

        $sql = $conexion->query("select * from Usuarios where email='$usuario'");

        if ($datos = $sql->fetch_object()) {
            setcookie('usuario_id', $datos->id, time() + 3600, '/'); // 'usuario_id' es el nombre de la cookie, y 3600 es la duración en segundos
            header("location:menu-service/inicio.html");
        } else {
            echo '<div class="alert alert-danger">Usuario no encontrado</div>';
        }
    }
}
?>