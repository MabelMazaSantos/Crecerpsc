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

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($id == '' || $token == '') {
    echo 'Error de petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT count(Id) FROM psicologos WHERE Id = ?");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $sql = $con->prepare("SELECT psicologos.Id, psicologos.Nombre, psicologos.Apellido, psicologos.Sexo, psicologos.Edad, psicologos.Especialidad, psicologos.Telefono, usuario.Activacion FROM psicologos INNER JOIN usuario ON psicologos.Id = usuario.Id_Psicologo WHERE psicologos.Id = ?");
            $sql->execute([$id]);
            $psicologo = $sql->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        echo 'Error de petición';
        exit;
    }
}

$errors = [];

if (!$psicologo) {
    die("Psicólogo no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['Nombre']);
    $apellido = trim($_POST['Apellido']);
    $sexo = trim($_POST['Sexo']);
    $edad = trim($_POST['Edad']);
    $especialidad = trim($_POST['Especialidad']);
    $telefono = trim($_POST['Telefono']);
    $activacion = trim($_POST['Activacion']);

    if (Validar_Nulo([$nombre, $apellido, $sexo, $edad, $especialidad, $telefono, $activacion])) {
        $errors[] = "Debe completar todos los campos obligatorios.";
    }

    $nombrecompleto = $nombre . " " . $apellido;

    if (count($errors) == 0) {
        $sql = $con->prepare("UPDATE psicologos SET Nombre = ?, Apellido = ?, Sexo = ?, Edad = ?, Especialidad = ?, Telefono = ? WHERE Id = ?");
        $result = $sql->execute([$nombre, $apellido, $sexo, $edad, $especialidad, $telefono, $id]);

        if ($result) {

            $sql2 = $con->prepare("UPDATE usuario SET Nombre = ?, Activacion = ? WHERE Id = ?");
            $result2 = $sql2->execute([$nombrecompleto, $activacion, $id]);

            if ($result2) {
                header("Location: Psicologos.php");
                exit();
            } else {
                $errors[] = "Error al actualizar el Usuario.";
            }


        } else {
            $errors[] = "Error al actualizar el psicólogo.";
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
            <a href="Inicio.php" class="no-underline">
                Control de mando
            </a>
            <a href="Psicologos.php" class="no-underline">
                / Psicólogos
            </a>
        </div>
        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Editar Psicólogo</h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" action="Editar_Psicologo.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>"
                    method="post" autocomplete="off">
                    <div class="col-md-6">
                        <label for="Nombre"><span class="text-danger">*</span>Nombre</label>
                        <input type="text" name="Nombre" id="Nombre" class="form-control"
                            value="<?php echo $psicologo['Nombre']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="Apellido"><span class="text-danger">*</span>Apellido</label>
                        <input type="text" name="Apellido" id="Apellido" class="form-control"
                            value="<?php echo $psicologo['Apellido']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="Sexo"><span class="text-danger">*</span>Sexo</label>
                        <select name="Sexo" id="Sexo" class="form-control">
                            <option value="Masculino" <?php echo $psicologo['Sexo'] == 'Masculino' ? 'selected' : ''; ?>>
                                Masculino</option>
                            <option value="Femenino" <?php echo $psicologo['Sexo'] == 'Femenino' ? 'selected' : ''; ?>>
                                Femenino</option>
                            <option value="Otro" <?php echo $psicologo['Sexo'] == 'Otro' ? 'selected' : ''; ?>>Otro
                            </option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="Edad"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="Edad" id="Edad" class="form-control"
                            value="<?php echo $psicologo['Edad']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="Especialidad"><span class="text-danger">*</span>Especialidad</label>
                        <input type="text" name="Especialidad" id="Especialidad" class="form-control"
                            value="<?php echo $psicologo['Especialidad']; ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="Telefono"><span class="text-danger">*</span>Teléfono</label>
                        <input type="tel" name="Telefono" id="Telefono" class="form-control"
                            value="<?php echo $psicologo['Telefono']; ?>">
                    </div>

                    <h4 class="text-purple">Estado de la cuenta del usuario</h4>

                    <div class="col-md-6">
                        <select name="Activacion" id="Activacion" class="form-control">
                            <option value="1" <?php echo $psicologo['Activacion'] == '1' ? 'selected' : ''; ?>>Activo
                            </option>
                            <option value="2" <?php echo $psicologo['Activacion'] == '2' ? 'selected' : ''; ?>>Inactivo
                            </option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-purpura text-white">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>