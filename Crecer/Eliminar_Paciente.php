<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

try {
    $con->beginTransaction();

    $sql = $con->prepare("DELETE FROM paciente WHERE Id = ?");
    $result = $sql->execute([$id]);

    if ($result) {
        $con->commit();
        echo 'success';
    } else {
        $con->rollBack();
        echo 'error';
    }
} catch (Exception $e) {
    $con->rollBack();
    echo 'error';
}
?>
