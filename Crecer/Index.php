
<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';
require 'Class/Funciones_Psicologo.php';

$db = new Database();
$con = $db->conectar();


$errors = [];

if (!empty($_POST)) {
    $usuario = trim($_POST['Usuario']);
    $password = trim($_POST['Password']);

    if (Validar_Nulo([$usuario, $password])) {
        $errors[] = "Debe de completar todos los campos";
    }

    if (count($errors) == 0) {
        $errors[] = Validar_Login($usuario, $password, $con);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Psicología</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/Login.css">

</head>

<body class="body">
    <main class="form-login">
        <div class="container">

            <img src="Img/crecer1.png">
            <h2><b>Iniciar Sesión</b></h2>

            <?php Mostrar_Error($errors) ?>

            <form class="row g-3" action="Index.php" method="post" autocomplete="off">
                <div class="form-floating">
                    <input type="text" name="Usuario" class="form-control" id="Usuario" placeholder="Usuario" required>
                    <label for="Usuario">Usuario</label>
                </div>

                <div class="form-floating">
                    <input type="password" name="Password" class="form-control" id="Password" placeholder="Contraseña"
                        required>
                    <label for="Password">Contraseña</label>
                </div>

                <div class="d-grid gap-2 col-12">
                    <button type="submit" class="btn btn-purple">Ingresar</button>
                </div>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>