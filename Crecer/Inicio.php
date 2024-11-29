<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sqlCount1 = $con->prepare("SELECT COUNT(*) as total FROM paciente WHERE Estado = 0");
$sqlCount1->execute();
$countResult1 = $sqlCount1->fetch(PDO::FETCH_ASSOC);
$totalPacientes = $countResult1['total'];

$sqlCount3 = $con->prepare("SELECT COUNT(*) as total FROM terapia_familiar");
$sqlCount3->execute();
$countResult3 = $sqlCount3->fetch(PDO::FETCH_ASSOC);
$totalTerapia = $countResult3['total'];

$sql1 = $con->prepare("SELECT p.Id, p.Nombre, p.Edad, p.Sexo, IFNULL(f.Grupo_Familiar, 'Sin grupo familiar') as Grupo_Familiar, p.Trastorno, p.Observacion, p.Estado, p.Fecha_Registro FROM paciente p 
    LEFT JOIN familia_paciente fp on p.Id = fp.idPaciente
    LEFT JOIN terapia_familiar f on fp.idFamilia = f.Id
    WHERE p.Estado = 0 ORDER BY p.Id DESC LIMIT 5");
$sql1->execute();
$pacientes = $sql1->fetchAll(PDO::FETCH_ASSOC);

$graficoEtiquetas = [];
$graficoDatos = [];
$mensaje = "";

$estadistica = $_POST['estadistica'] ?? 'anio';

switch ($estadistica) {
    case 'anio':
        $sqlAnio = $con->prepare("SELECT YEAR(Fecha_Registro) as anio, COUNT(*) as total 
                                  FROM paciente 
                                  GROUP BY anio 
                                  ORDER BY anio ASC");
        $sqlAnio->execute();
        $resultadosAnio = $sqlAnio->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosAnio as $resultado) {
            $graficoEtiquetas[] = $resultado['anio'];
            $graficoDatos[] = $resultado['total'];
        }
        $mensaje = "Distribución de pacientes por año.";
        break;

    case 'edad':
        $sqlEdad = $con->prepare("SELECT CASE 
                WHEN Edad BETWEEN 12 and 18 THEN 'Adolescencia (12 a 18 años)'
                WHEN Edad BETWEEN 14 and 26 THEN 'Juventud (14 a 26 año)'
                WHEN Edad BETWEEN 27 and 59 THEN 'Adultez (27 a 59 años)'
                WHEN Edad >= 60 THEN 'Persona mayor (60 o más)'
            END as grupo, COUNT(*) as total 
            FROM paciente 
            GROUP BY grupo 
            ORDER BY total DESC");
        /**
         * Adolescencia: de 12 a 18 años 
         * Juventud: de 14 a 26 años 
         * Adultez: de 27 a 59 años 
         * Persona mayor: de 60 años o más
         */
        $sqlEdad->execute();
        $resultadosEdad = $sqlEdad->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosEdad as $resultado) {
            $graficoEtiquetas[] = $resultado['grupo'];
            $graficoDatos[] = $resultado['total'];
        }
        $mensaje = "Distribución de pacientes por rango de edad.";
        break;

    case 'trastorno':
        $sqlTrastorno = $con->prepare("SELECT Trastorno, COUNT(*) as total 
                                       FROM paciente 
                                       GROUP BY Trastorno 
                                       ORDER BY total DESC");
        $sqlTrastorno->execute();
        $resultadosTrastorno = $sqlTrastorno->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosTrastorno as $resultado) {
            switch ($resultado['Trastorno']) {
                case '0':
                    $graficoEtiquetas[] = 'Ansiedad';
                    break;
                case '1':
                    $graficoEtiquetas[] = 'Depresión';
                    break;
                case '2':
                    $graficoEtiquetas[] = 'Trastorno límite de la personalidad';
                    break;
                case '3':
                    $graficoEtiquetas[] = 'Trastorno de conducta alimentaria';
                    break;
                case '4':
                    $graficoEtiquetas[] = 'Limitaciones';
                    break;
                case '5':
                    $graficoEtiquetas[] = 'Déficit de atención e hiperactividad';
                    break;
                case '6':
                    $graficoEtiquetas[] = 'Agresividad';
                    break;
                default:
                    $graficoEtiquetas[] = 'Desconocido';
            }
            $graficoDatos[] = $resultado['total'];
        }
        $mensaje = "Distribución de pacientes por trastorno.";
        break;

    default:
        $mensaje = "Selecciona una opción para ver las estadísticas.";
}

$current_page = 'Inicio';
include 'Header.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <main>
        <div class="container-fluid px-4">

            <h2 class="mt-4 text-purple">Bienvenido
                <?php if (isset($_SESSION['user_id'])) {
                    echo $_SESSION['user_name'];
                } ?>
                <img src="Img/icons/sonrisa.png" class="small-icon">
            </h2>

            <br>

            <div class="row">

                <div class="col-xl-6 col-md-6 mb-6 mt-4">
                    <a href="Paciente.php" class="text-decoration-none">
                        <div class="card border-left-rose shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                            <b>Pacientes</b>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <?php echo $totalPacientes; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <img src="Img/icons/familia.png" class="small-icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-6 col-md-6 mb-6 mt-4">
                    <a href="Terapia_Familiar.php" class="text-decoration-none">
                        <div class="card border-left-rose shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                            <b>Grupo Familiar</b>
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalTerapia; ?>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <img src="Img/icons/medico.png" class="small-icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-4 col-md-6 mb-6 mt-4">
                    <div class="card mb-4 shadow">
                        <div class="card-header card-rose">
                            <i class="fas fa-chart-area me-1"></i>
                            Selecciona Estadísticas
                        </div>
                        <div class="card-body">
                            <form method="post" action="Inicio.php">
                                <div class="mb-3">
                                    <label for="estadistica" class="form-label">Selecciona una estadística:</label>
                                    <select name="estadistica" id="estadistica" class="form-control">
                                        <option value="anio" <?php if ($estadistica == 'anio') echo ('selected') ?> selected>Año con más pacientes</option>
                                        <option value="edad" <?php if ($estadistica == 'edad') echo ('selected') ?>>Distribución de edades</option>
                                        <option value="trastorno" <?php if ($estadistica == 'trastorno') echo ('selected') ?>>Trastorno más diagnosticado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-purpura text-white">Ver Estadísticas</button>
                                <button type="button" class="btn btn-secondary text-white" onclick="generatePDF()">Generar PDF</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-md-6 mb-6 mt-4">
                    <div class="card mb-4 shadow">
                        <div class="card-header card-rose">
                            <i class="fas fa-chart-area me-1"></i>
                            Resultados de Estadísticas
                        </div>
                        <div class="card-body">
                            <p><?php echo $mensaje; ?></p>
                            <?php if (!empty($graficoEtiquetas) && !empty($graficoDatos)): ?>
                                <canvas id="myChart" width="100%" height="40"></canvas>
                                <script>
                                    const ctx = document.getElementById('myChart').getContext('2d');
                                    const myChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: {
                                            labels: <?php echo json_encode($graficoEtiquetas); ?>,
                                            datasets: [{
                                                label: 'Cantidad de Pacientes',
                                                data: <?php echo json_encode($graficoDatos); ?>,
                                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: {
                                                        callback: function(value) {
                                                            return Number.isInteger(value) ? value : null;
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    });

                                    function generatePDF() {
                                        const canvas = document.getElementById('myChart');
                                        const imgData = canvas.toDataURL('image/png');
                                        const {
                                            jsPDF
                                        } = window.jspdf;
                                        const pdf = new jsPDF();
                                        const stadisticSelected = document.getElementById('estadistica').value;
                                        pdf.text(`Estadistica de: ${stadisticSelected}`, 10, 10);
                                        pdf.addImage(imgData, 'PNG', 10, 40, 190, 100);

                                        // Add table data
                                        let startY = 150;
                                        pdf.text('Tabla de Datos:', 10, startY);
                                        startY += 10;
                                        <?php foreach ($graficoEtiquetas as $index => $etiqueta): ?>
                                            pdf.text('<?php echo $etiqueta; ?>: <?php echo $graficoDatos[$index]; ?>', 10, startY);
                                            startY += 10;
                                        <?php endforeach; ?>

                                        pdf.save(`Estadistica_${stadisticSelected}.pdf`);
                                    }
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-md-6 mb-6 mt-4">
                    <div class="card mb-4 shadow">
                        <div class="card-header card-rose">
                            <i class="fas fa-chart-bar me-1"></i>
                            Pacientes Recientes
                        </div>
                        <div class="card-body">
                            <table class="table">
                                <tbody>
                                    <?php foreach ($pacientes as $paciente): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($paciente['Nombre']); ?></td>
                                            <td>
                                                <?php 
                                                    switch ($paciente['Trastorno']) {
                                                        case '0':
                                                            echo 'Ansiedad';
                                                            break;
                                                        case '1':
                                                            echo 'Depresión';
                                                            break;
                                                        case '2':
                                                            echo 'Trastorno límite de la personalidad';
                                                            break;
                                                        case '3':
                                                            echo 'Trastorno de conducta alimentaria';
                                                            break;
                                                        case '4':
                                                            echo 'Limitaciones';
                                                            break;
                                                        case '5':
                                                            echo 'Déficit de atención e hiperactividad';
                                                            break;
                                                        case '6':
                                                            echo 'Agresividad';
                                                            break;
                                                        default:
                                                            echo 'Desconocido';
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($paciente['Grupo_Familiar']); ?></td>
                                            <td>
                                                <img src="Img/icons/familia.png" class="small-icon">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <?php include 'Footer.php'; ?>