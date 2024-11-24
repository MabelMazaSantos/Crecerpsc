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

        $sql = $con->prepare("SELECT * FROM paciente WHERE Grupo_Familiar = ?");
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
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $paciente['Trastorno']; ?>
                                            </div>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800 col-auto">
                                                <?php echo $paciente['Edad'], " años"; ?>
                                            </div>
                                        <div class="col-auto">
                                            <img src="Img/icons/familia.png" class="small-icon">
                                        </div>
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