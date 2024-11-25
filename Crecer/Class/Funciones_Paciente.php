<?php

function Validar_Nulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function Generar_Token()
{
    return md5(uniqid(mt_rand(), false));
}

function Registrar_Paciente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO  paciente (Nombre, Edad, Sexo,  Trastorno, Observacion, Fecha_Registro) VALUES (?,?,?,?,?, now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}
function Registrar_Paciente_Familia(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO  familia_paciente (idFamilia, idPaciente) VALUES (?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}
function Mostrar_Error(array $errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}