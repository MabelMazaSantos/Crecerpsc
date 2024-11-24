<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';
require 'Class/Funciones_Paciente.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$errors = [];

$sql = "SELECT Grupo_Familiar FROM terapia_familiar";
$stmt = $con->prepare($sql);
$stmt->execute();
$grupos_familiares = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
    $nombre = trim($_POST['Nombre']);
    $edad = trim($_POST['Edad']);
    $sexo = trim($_POST['Sexo']);
    $trastorno = trim($_POST['Trastorno']);
    $observacion = trim($_POST['Observacion']);

    if (isset($_POST['toggleGrupoFamiliar']) && $_POST['toggleGrupoFamiliar'] == 'on') {
        $grupo_familiar = trim($_POST['GrupoFamiliarSelect']);
    } else {
        $grupo_familiar = trim($_POST['GrupoFamiliarText']);
    }

    if (Validar_Nulo([$nombre, $sexo, $edad, $trastorno, $observacion, $grupo_familiar])) {
        $errors[] = "Debe completar todos los campos";
    }

    if (count($errors) == 0) {
        $id = Registrar_Paciente([$nombre, $edad, $sexo, $grupo_familiar, $trastorno, $observacion], $con);

        if ($id > 0) {
            if (!isset($_POST['toggleGrupoFamiliar']) || $_POST['toggleGrupoFamiliar'] != 'on') {
                $sql = "INSERT INTO terapia_familiar (Grupo_Familiar) VALUES (?)";
                $stmt = $con->prepare($sql);
                $stmt->execute([$grupo_familiar]);
            }
            header("Location: Paciente.php");
            exit;
        } else {
            $errors[] = "Error al registrar paciente";
        }
    }
}
?>


<?php
$current_page = 'Paciente';
include 'Header.php';
?>

<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Inicio.php" class="no-underline">Control de mando</a>
            <a href="Paciente.php" class="no-underline">/ Paciente</a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Datos del Paciente</h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" action="Registro_Paciente.php" method="post" autocomplete="off">
                    <div class="col-md-6">
                        <label for="Nombre"><span class="text-danger">*</span>Nombre</label>
                        <input type="text" name="Nombre" id="Nombre" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="Edad"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="Edad" id="Edad" class="form-control">
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
                        <label for="Trastorno"><span class="text-danger">*</span>Trastorno</label>
                        <input type="text" name="Trastorno" id="Trastorno" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="Observacion"><span class="text-danger">*</span>Observación</label>
                        <textarea name="Observacion" id="Observacion" class="form-control" rows="4"></textarea>
                    </div>

                    <div class="col-md-12">
                        <h3 class="text-purple">Grupo Familiar</h3>
                    </div>

                    <div class="col-md-12 d-flex align-items-center">
                        <span class="me-3" style="font-weight: 500;">Crear</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleGrupoFamiliar"
                                name="toggleGrupoFamiliar" onchange="toggleGrupoFamiliarInput()">
                        </div>
                        <span class="ms-3" style="font-weight: 500;">Seleccionar</span>
                    </div>

                    <div class="col-md-6" id="grupoFamiliarTextInput" style="display:block;">
                        <input type="text" name="GrupoFamiliarText" id="GrupoFamiliarText" class="form-control">
                    </div>

                    <div class="col-md-6" id="grupoFamiliarSelectInput" style="display:none;">
                        <select name="GrupoFamiliarSelect" id="GrupoFamiliarSelect" class="form-control">
                            <option>Selecciona</option>
                            <option value="Sin grupo familiar">Sin grupo familiar</option>
                            <?php foreach ($grupos_familiares as $grupo) { ?>
                                <option value="<?php echo htmlspecialchars($grupo['Grupo_Familiar']); ?>">
                                    <?php echo htmlspecialchars($grupo['Grupo_Familiar']); ?>
                                </option>
                            <?php } ?>
                        </select>
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