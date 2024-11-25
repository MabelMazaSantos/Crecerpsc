<?php
require '../../Config/Configuracion.php';
require '../../Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("UPDATE familia_paciente SET idParentesco = ?, Parentesco = ? WHERE idFamilia = ? AND idPaciente = ?");
if (!$sql->execute([$_GET['idParentesco'], $_GET['parentesco'], $_GET['idFamilia'], $_GET['idPaciente']])) {
    http_response_code(500);
    exit();
}
