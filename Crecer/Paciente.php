<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("SELECT * FROM paciente");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
$current_page = 'Paciente';
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
                    <h3 class="text-purple">Pacientes <img src="Img/icons/familia.png" class="small-icon"></h3>
                    <a href="Registro_Paciente.php" class="btn btn-purpura text-white">Agregar Paciente</a>
                </div>
                <br>
                <table id="employeeTable" class="table table-bordered">
                    <thead class="table-rose text-white">
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Edad</th>
                            <th>Sexo</th>
                            <th>Grupo Familiar</th>
                            <th>Trastorno</th>
                            <th>Observación</th>
                            <th>Fecha de registro</th>
                            <th>Administrar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $row) { ?>
                            <tr>
                                <td><?php echo $row['Id'] ?></td>
                                <td><?php echo $row['Nombre'] ?></td>
                                <td><?php echo $row['Edad'] ?></td>
                                <td><?php echo $row['Sexo'] ?></td>
                                <td><?php echo $row['Grupo_Familiar'] ?></td>
                                <td><?php echo $row['Trastorno'] ?></td>
                                <td><?php echo $row['Observacion'] ?></td>
                                <td><?php echo $row['Fecha_Registro'] ?></td>
                                <td class="text-center">
                                    <a href="Editar_Paciente.php?id=<?php echo $row['Id'] ?>&token=<?php echo hash_hmac('sha1', $row['Id'], KEY_TOKEN)?>"
                                        class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal" data-id="<?php echo $row['Id']; ?>">
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
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este paciente?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Eliminar</button>
            </div>
        </div>
    </div>
</div>



<?php include 'Footer.php'; ?>