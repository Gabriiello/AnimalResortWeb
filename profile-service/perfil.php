<?php
require '../auth-service/conexion.php';

if(!isset($_COOKIE['usuario_id'])) {
    echo "<script>alert('Sesión expirada, inicie nuevamente'); window.location.href='../index.php';</script>";
    exit();
}

$idUsuario = $_COOKIE['usuario_id'];

$consulta = "SELECT nombre, email, celular, documento FROM Usuarios WHERE id = $idUsuario";

$resultado = $conexion->query($consulta);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombre = $fila["nombre"];
    $email = $fila["email"];
    $telefono = $fila["celular"];
    $documento = $fila["documento"];
} else {
    die("Usuario no encontrado");
}

$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
                        .navbar {
                    background-color: #343a40;
                    z-index: 1;
                    margin-top: 0; 
                }

                .navbar-brand img {
                    max-width: 40px;
                    height: auto;
                    margin-right: 10px;
                }

                .navbar-brand {
                    display: flex;
                    align-items: center;
                    font-size: 1.5rem;
                    font-weight: 600;
                }

                .navbar-nav .nav-link {
                    color: #fff !important;
                    margin-right: 1rem;
                    transition: color 0.3s;
                }

                .navbar-nav .nav-link:hover {
                    color: #3c8735 !important;
                }

                .navbar-toggler-icon {
                    color: #fff;
                }
                .banner {
                    text-align: center;
                }

                body {
                    background-color: #9dcd55;
                }

                .info-div {
                    background-color: white;
                    border-radius: 10px;
                    padding: 20px;
                    text-align: center;
                }

                .info-div ul {
                    list-style: none;
                    padding: 0;
                }

                .info-div li {
                    margin-bottom: 10px;
                }

                .btn-logout {
                    background-color: #3c8735;
                    color: #ffffff;
                    padding: 10px 20px;
                    border-radius: 5px;
                    text-decoration: none;
                    display: inline-block;
                }

                .btn-logout:hover {
                    background-color: #3c8735;
                    color: #ffffff;
                }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img src="../statics/logoAnimalR.png" alt="Logo Animal Resort">
          Animal Resort
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="../menu-service/inicio.html">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../profile-service/perfil.php">Perfil</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../history-service/reservas.html">Reservas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../pets-service/mascotas.html">Mascotas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../address-service/direcciones.html">Direcciones</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                
                <div class="info-div">
                    <ul>
                        <li> <?php echo $nombre; ?></li>
                        <li> <?php echo $email; ?></li>
                        <li><strong>Teléfono:</strong> <?php echo $telefono; ?></li>
                        <li><strong>Documento:</strong> <?php echo $documento; ?></li>
                        <li>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Editar</button>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn-logout" href="../index.php">CERRAR SESION</a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="actualizar_perfil.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Perfil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $nombre; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $telefono; ?>">
                        </div>
                        <div class="mb-3">
                            <label for="documento" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="documento" name="documento" value="<?php echo $documento; ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>