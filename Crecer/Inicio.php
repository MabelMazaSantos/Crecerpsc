<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sqlCount1 = $con->prepare("SELECT COUNT(*) as total FROM paciente");
$sqlCount1->execute();
$countResult1 = $sqlCount1->fetch(PDO::FETCH_ASSOC);
$totalPacientes = $countResult1['total'];

$sqlCount3 = $con->prepare("SELECT COUNT(*) as total FROM terapia_familiar");
$sqlCount3->execute();
$countResult3 = $sqlCount3->fetch(PDO::FETCH_ASSOC);
$totalTerapia = $countResult3['total'];

$sql1 = $con->prepare("SELECT * FROM paciente ORDER BY Id DESC LIMIT 5");
$sql1->execute();
$pacientes = $sql1->fetchAll(PDO::FETCH_ASSOC);

$graficoEtiquetas = [];
$graficoDatos = [];
$mensaje = "";

$estadistica = $_POST['estadistica'] ?? '';

switch ($estadistica) {
    case 'anio':
        $sqlAnio = $con->prepare("SELECT YEAR(creado_en) as anio, COUNT(*) as total 
                                  FROM paciente 
                                  GROUP BY anio 
                                  ORDER BY total DESC");
        $sqlAnio->execute();
        $resultadosAnio = $sqlAnio->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosAnio as $resultado) {
            $graficoEtiquetas[] = $resultado['anio'];
            $graficoDatos[] = $resultado['total'];
        }
        $mensaje = "Distribución de pacientes por año.";
        break;

    case 'edad':
        $sqlEdad = $con->prepare("SELECT Edad, COUNT(*) as total 
                                  FROM paciente 
                                  GROUP BY Edad 
                                  ORDER BY total DESC");
        $sqlEdad->execute();
        $resultadosEdad = $sqlEdad->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosEdad as $resultado) {
            $graficoEtiquetas[] = $resultado['Edad'];
            $graficoDatos[] = $resultado['total'];
        }
        $mensaje = "Distribución de pacientes por edad.";
        break;

    case 'trastorno':
        $sqlTrastorno = $con->prepare("SELECT Trastorno, COUNT(*) as total 
                                       FROM paciente 
                                       GROUP BY Trastorno 
                                       ORDER BY total DESC");
        $sqlTrastorno->execute();
        $resultadosTrastorno = $sqlTrastorno->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultadosTrastorno as $resultado) {
            $graficoEtiquetas[] = $resultado['Trastorno'];
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

                <div class="col-xl-6 col-md-6 mb-6 mt-4">
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
                                        <option value="anio">Año con más pacientes</option>
                                        <option value="edad">Distribución de edades</option>
                                        <option value="trastorno">Trastorno más diagnosticado</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-purpura text-white">Ver Estadísticas</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-md-6 mb-6 mt-4">
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
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
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
                                            <td><?php echo htmlspecialchars($paciente['Trastorno']); ?></td>
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

    <?php include 'Footer.php'; ?>