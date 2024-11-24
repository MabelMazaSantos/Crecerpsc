<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("SELECT psicologos.Id, psicologos.Nombre, psicologos.Apellido, psicologos.Especialidad, usuario.Activacion FROM psicologos INNER JOIN usuario ON psicologos.Id = usuario.Id_Psicologo");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
$current_page = 'Psicologos';
include 'Header.php';
?>
<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Inicio.php" class="no-underline">
                Control de mando
            </a>
        </div>
        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center ">
                    <h3 class="text-purple">Nuestros Psicólogos <img src="Img/icons/Configuraciones1.png"
                            class="small-icon"></h3>
                    <a href="Registro_Psicologo.php" class="btn btn-purpura text-white">Agregar Psicólogo</a>
                </div>
                <br>
                <table id="employeeTable" class="table table-bordered">
                    <thead class="table-rose text-white">
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Especialidad</th>
                            <th>Estado de cuenta del usuario</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $row) { ?>
                            <tr>
                                <td><?php echo $row['Id'] ?></td>
                                <td><?php echo $row['Nombre'] ?></td>
                                <td><?php echo $row['Apellido'] ?></td>
                                <td><?php echo $row['Especialidad'] ?></td>
                                <td><?php 
                                if ($row['Activacion'] == 1) {
                                    echo "Activo";
                                }else {
                                    echo "Inactivo";
                                }
                                    ?></td>
                                <td class="text-center">
                                    <a href="Editar_Psicologo.php?id=<?php echo $row['Id'] ?>&token=<?php echo hash_hmac('sha1', $row['Id'], KEY_TOKEN) ?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeletePsicologo" data-id="<?php echo $row['Id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
        </link>
</main>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmDeletePsicologo" tabindex="-1" role="dialog"
    aria-labelledby="confirmDeletePsicologoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeletePsicologoLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este psicólogo?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButtonPsicologo">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<?php include 'Footer.php'; ?>