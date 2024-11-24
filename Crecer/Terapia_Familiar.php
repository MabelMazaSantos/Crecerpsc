<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("SELECT DISTINCT terapia_familiar.Id, terapia_familiar.Grupo_Familiar FROM terapia_familiar INNER JOIN paciente ON terapia_familiar.Grupo_Familiar = paciente.Grupo_Familiar WHERE paciente.Id IS NOT NULL");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

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
        </div>
        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center ">
                    <h3 class="text-purple">Grupos Familiares <img src="Img/icons/familia.png" class="small-icon"></h3>
                </div>
                <br>
                <table id="employeeTable" class="table table-bordered">
                    <thead class="table-rose text-white">
                        <tr>
                            <th>Orden</th>
                            <th>Grupo Familiar</th>
                            <th>Ver Miembros</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $row) { ?>
                            <tr>
                                <td><?php echo $row['Id'] ?></td>
                                <td><?php echo $row['Grupo_Familiar'] ?></td>
                                <td class="text-center">
                                    <a href="Detalle_Familiar.php?id=<?php echo $row['Grupo_Familiar'] ?>&token=<?php echo hash_hmac('sha1', $row['Grupo_Familiar'], KEY_TOKEN) ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-people-group"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteGrupoFamiliar" data-id="<?php echo $row['Id']; ?>">
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
<div class="modal fade" id="confirmDeleteGrupoFamiliar" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteGrupoFamiliarLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteGrupoFamiliarLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar este Grupo Familiar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButtonGrupoFamiliar">Eliminar</button>
            </div>
        </div>
    </div>
</div>



<?php include 'Footer.php'; ?>