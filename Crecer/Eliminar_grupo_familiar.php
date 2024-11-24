<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

try {
    $con->beginTransaction();

    $sql = $con->prepare("SELECT Grupo_Familiar FROM terapia_familiar WHERE Id = ?");
    $sql->execute([$id]);
    $grupoFamiliar = $sql->fetchColumn();

    if ($grupoFamiliar) {

        $sql = $con->prepare("UPDATE paciente SET Grupo_Familiar = 'Sin Grupo Familiar' WHERE Grupo_Familiar = ?");
        $resultUpdate = $sql->execute([$grupoFamiliar]);

        if ($resultUpdate) {

            $sql = $con->prepare("DELETE FROM terapia_familiar WHERE Id = ?");
            $resultDelete = $sql->execute([$id]);

            if ($resultDelete) {
                $con->commit();
                echo 'success';
            } else {
                $con->rollBack();
                echo 'error';
            }
        } else {
            $con->rollBack();
            echo 'error';
        }
    } else {

        echo 'Grupo familiar no encontrado';
    }
} catch (Exception $e) {
    $con->rollBack();
    echo 'error';
}
?>
