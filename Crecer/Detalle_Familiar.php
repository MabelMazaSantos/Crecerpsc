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

        $sql2 = $con->prepare("SELECT * FROM parentesco");
        $sql2->execute();
        $parentesco = $sql2->fetchAll(PDO::FETCH_ASSOC);

        $sql = $con->prepare("SELECT p.Id, p.Nombre, p.Edad, p.Sexo, (SELECT p.Id FROM paciente p WHERE p.Id = fp.parentesco) as IdParentesco, fp.idFamilia, p.Trastorno, p.Observacion, fp.idParentesco as Parentesco FROM paciente p 
        INNER JOIN familia_paciente fp ON p.Id = fp.idPaciente
        INNER JOIN terapia_familiar f ON fp.idFamilia = f.Id WHERE f.Grupo_Familiar = ?");
        $sql->execute([$id]);
        $pacientes = $sql->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo 'Error de petición';
        exit;
    }
}

$errors = [];

if (empty($pacientes)) {
    die("Grupo familiar no encontrado.");
}
?>

<?php
$current_page = 'Terapia';
include 'Header.php';
?>

<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Inicio.php" class="no-underline">
                Control de mando
            </a>
            <a href="Terapia_Familiar.php" class="no-underline">
                / Terapia familiar
            </a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Miembros del Grupo Familiar: <?php echo $id ?></h3>
                <div class="row">
                    <?php foreach ($pacientes as $paciente): ?>
                        <div class="col-xl-6 col-md-6 mb-6 mt-4">
                            <div class="card border-left-rose shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                                <b><?php echo $paciente['Nombre']; ?></b>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 mb-2">
                                                <?php
                                                switch ($paciente['Trastorno']) {
                                                    case '0':
                                                        echo 'Ansiedad';
                                                        break;
                                                    case '1':
                                                        echo 'Depresión';
                                                        break;
                                                    case '2':
                                                        echo 'Trastorno límite de la personalidad';
                                                        break;
                                                    case '3':
                                                        echo 'Trastorno de conducta alimentaria';
                                                        break;
                                                    case '4':
                                                        echo 'Limitaciones';
                                                        break;
                                                    case '5':
                                                        echo 'Déficit de atención e hiperactividad';
                                                        break;
                                                    case '6':
                                                        echo 'Agresividad';
                                                        break;
                                                    default:
                                                        echo 'Desconocido';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 col-auto">
                                            <?php echo $paciente['Edad'], " años"; ?>
                                        </div>
                                        <div class="col-auto">
                                            <img src="Img/icons/familia.png" class="small-icon">
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <select name="ParentescoSelect" id="select_pr_<?php echo $paciente['Id']; ?>" class="form-control" onchange="actualizarEstado(<?php echo $paciente['Id']; ?>)">
                                                <option value="" disabled <?php echo $paciente['Parentesco'] == NULL || $paciente['Parentesco'] == 0 || $paciente['Parentesco'] == "" ? 'selected' : ''; ?>>Parentesco</option>
                                                <?php foreach ($parentesco as $par) { ?>
                                                    <option value="<?php echo $par['idParentesco']; ?>" <?php echo $paciente['Parentesco'] == $par['idParentesco'] ? 'selected' : ''; ?>><?php echo $par['nombre']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="mx-2">de</span>
                                            <select name="PacientesSelect" id="select_pc_<?php echo $paciente['Id']; ?>" class="form-control" onchange="actualizarEstado(<?php echo $paciente['Id']; ?>)">
                                                <option value="" disabled selected>Familiar</option>
                                                <?php foreach ($pacientes as $otroPaciente) {
                                                    if ($paciente['Id'] != $otroPaciente['Id']) { ?>
                                                        <option value="<?php echo $otroPaciente['Id']; ?>" <?php echo $paciente['IdParentesco'] != NULL && $paciente['IdParentesco'] == $otroPaciente['Id'] ? 'selected' : ''; ?>><?php echo $otroPaciente['Nombre']; ?></option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                        <script>
                                            function actualizarEstado(id) {
                                                var parentesco = document.getElementById('select_pr_' + id).value;
                                                parentesco = parentesco == '' ? null : parentesco;
                                                var paciente = document.getElementById('select_pc_' + id).value;
                                                paciente = paciente == '' ? null : paciente;
                                                fetch('/Crecerpsc/Crecer/Controller/Familia/Actualizar_Parentesco.php?idParentesco=' + parentesco + '&parentesco=' + paciente + '&idFamilia=' + <?php echo $paciente['idFamilia']; ?> + '&idPaciente=' + id)
                                                    .then(response => {
                                                        if (response.status === 200) {
                                                            console.log('Estado actualizado correctamente');
                                                        }
                                                    });
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>