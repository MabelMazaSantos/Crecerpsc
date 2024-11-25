<?php
require '../../Config/Configuracion.php';
require '../../Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("UPDATE paciente SET Estado = ? WHERE Id = ?");
if (!$sql->execute([$_GET['estado'], $_GET['id']])) {
    http_response_code(500);
    exit();
}
