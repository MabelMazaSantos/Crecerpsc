<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';
require 'Class/Funciones_Psicologo.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$errors = [];

if (!empty($_POST)) {
    $nombre = trim($_POST['Nombre']);
    $apellido = trim($_POST['Apellido']);
    $sexo = trim($_POST['Sexo']);
    $edad = trim($_POST['Edad']);
    $especialidad = trim($_POST['Especialidad']);
    $email = trim($_POST['Email']);
    $usuario = trim($_POST['Usuario']);
    $password = trim($_POST['Password']);
    $repassword = trim($_POST['Repassword']);
    $telefono = trim($_POST['Telefono']);
    $dni = trim($_POST['Dni']);

    if (Validar_Nulo([$nombre, $apellido, $sexo, $edad, $especialidad, $email, $telefono, $dni])) {
        $errors[] = "Debe completar todos los campos obligatorios.";
    }

    if (!Estructura_Email($email)) {
        $errors[] = "La dirección del correo electrónico no es válida.";
    }

    if (Email_Existente($email, $con)) {
        $errors[] = "El correo electrónico $email ya existe.";
    }

    if (!Password_validacion($password, $repassword)) {
        $errors[] = "La contraseña no coincide";
    }

    $nombrecompleto = $nombre . " " . $apellido;

    if (count($errors) == 0) {
        $id = Registrar_Psicologo([$nombre, $apellido, $sexo, $edad, $especialidad, $email, $telefono, $dni], $con);

        if ($id > 0) {

            require 'Class/Enviar_Email.php';
            $mailer = new Mailer();
            $url = SITIO_URL . '/Index.php';
            $asunto = "Cuenta de acceso - Crecer";
            $cuerpo = "Estimado(a) $nombre $apellido, es un gusto contar con usted.<br>Sus credenciales son:<br>
                <b>USUARIO:</b> $email<br><b>PASSWORD:</b> $dni<br> Inicie sesión aquí: <a href='$url'>INICIAR</a>";

            $pass_hash = password_hash($password, PASSWORD_DEFAULT);
            if (Registrar_Usuario([$email, $nombrecompleto, $pass_hash, $id], $con)) {
                $mailer->Enviar_Email($email, $asunto, $cuerpo);

                header("Location: Psicologos.php");
                exit();
            } else {
                $errors[] = "Error al registrar usuario.";
            }

        } else {
            $errors[] = "Error al registrar Psicólogo.";
        }
    }
}
?>

<?php
$current_page = 'Psicologos';
include 'Header.php';
?>

<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Inicio.php" class="no-underline">Control de mando</a>
            <a href="Psicologos.php" class="no-underline">/ Psicologos</a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Datos del Psicólogo</h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" action="Registro_Psicologo.php" method="post" enctype="multipart/form-data"
                    autocomplete="off">
                    <div class="col-md-6">
                        <label for="Nombre"><span class="text-danger">*</span>Nombre</label>
                        <input type="text" name="Nombre" id="Nombre" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Apellido"><span class="text-danger">*</span>Apellido</label>
                        <input type="text" name="Apellido" id="Apellido" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Sexo"><span class="text-danger">*</span>Sexo</label>
                        <select name="Sexo" id="Sexo" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="Edad"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="Edad" id="Edad" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Especialidad"><span class="text-danger">*</span>Especialidad</label>
                        <input type="text" name="Especialidad" id="Especialidad" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Email"><span class="text-danger">*</span>Correo Electrónico</label>
                        <input type="email" name="Email" id="Email" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Telefono"><span class="text-danger">*</span>Teléfono</label>
                        <input type="tel" name="Telefono" id="Telefono" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Dni"><span class="text-danger">*</span>DNI</label>
                        <input type="text" name="Dni" id="Dni" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <input type="hidden" name="Usuario" id="Usuario" class="form-control" type="text">
                    </div>

                    <div class="col-md-6">
                        <input type="hidden" name="Password" id="Password" class="form-control" type="Password">
                    </div>

                    <div class="col-md-6">
                        <input type="hidden" name="Repassword" id="Repassword" class="form-control" type="Password">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-purpura text-white">Registrar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>