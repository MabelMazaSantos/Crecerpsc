<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';


$db = new Database();
$con = $db->conectar();


$id = $_POST['id'];

try {
    $con->beginTransaction();

    $sql = $con->prepare("DELETE FROM usuario WHERE Id_Psicologo = ?");
    $resultUser = $sql->execute([$id]);

    if ($resultUser) {

        $sql = $con->prepare("DELETE FROM psicologos WHERE Id = ?");
        $resultadoPsicologo = $sql->execute([$id]);
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