
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

function Estructura_Email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function Generar_Token()
{
    return md5(uniqid(mt_rand(), false));
}

function Registrar_Psicologo(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO  Psicologos (Nombre, Apellido, Sexo, Edad, Especialidad, Email, Telefono, Dni, Fecha_Registro) VALUES (?,?,?,?,?,?,?,?, now())");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}

function Registrar_Usuario(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO  usuario (Usuario, Nombre, Password, Id_Psicologo) VALUES (?,?,?,?)");
    if ($sql->execute($datos)) {
        return true;
    }
    return false;
}

function Email_Existente($email, $con)
{

    $sql = $con->prepare("SELECT Id FROM psicologos WHERE Email LIKE ? LIMIT 1");
    $sql->execute([$email]);
    if ($sql->fetchColumn() > 0) {
        return true;
    }
    return false;
}

function Password_validacion($password, $repassword)
{
    if (strcmp($password, $repassword) == 0) {
        return true;
    }
    return false;
}

function Validar_Login($usuario, $password, $con)
{
    $sql = $con->prepare("SELECT Id, Usuario, Nombre, Password FROM usuario WHERE Usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    if ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (Usuario_Activo($usuario, $con)) {
            if (password_verify($password, $row['Password'])) {
                $_SESSION['user_id'] = $row['Id'];
                $_SESSION['user_user'] = $row['Usuario'];
                $_SESSION['user_name'] = $row['Nombre'];

                header("Location: Inicio.php");
                exit;

            }
        } else {
            return 'Usuario no activado';
        }
    }
    return 'Usuario y/o Contraseña incorrectos';
}

function Usuario_Activo($usuario, $con)
{
    $sql = $con->prepare("SELECT Activacion FROM usuario WHERE Usuario LIKE ? LIMIT 1");
    $sql->execute([$usuario]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    if ($row['Activacion'] == 1) {
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