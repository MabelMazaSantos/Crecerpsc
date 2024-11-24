<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Psicologia</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- jQuery CSS -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="Css/styles.css" rel="stylesheet" />
    <link href="Css/Index.css" rel="stylesheet" />
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand  text-center" href="Inicio.php">
            Crecer
        </a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="Config/CerrarSesion.php">Cerrar Sesión</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        
                        <div class="sb-sidenav-menu-heading">Panel de Control</div>

                        <a class="nav-link <?php echo isset($current_page) && $current_page == 'Inicio' ? 'active' : ''; ?>"
                            href="Inicio.php">
                            <div class="sb-nav-link-icon">
                                <img src="Img/icons/control.png" class="small-icon">
                            </div>
                            Control de mando
                        </a>

                        <div class="sb-sidenav-menu-heading">Interfaces</div>

                        <a class="nav-link <?php echo isset($current_page) && $current_page == 'Paciente' ? 'active' : ''; ?>"
                            href="Paciente.php">
                            <div class="sb-nav-link-icon">
                                <img src="Img/icons/familia.png" class="small-icon">
                            </div>
                            Pacientes
                        </a>

                        <a class="nav-link <?php echo isset($current_page) && $current_page == 'Terapia' ? 'active' : ''; ?>"
                            href="Terapia_Familiar.php">
                            <div class="sb-nav-link-icon">
                                <img src="Img/icons/medico.png" class="small-icon">
                            </div>
                            Terapia Familiar
                        </a>

                        <a class="nav-link <?php echo isset($current_page) && $current_page == 'Psicologos' ? 'active' : ''; ?>"
                            href="Psicologos.php">
                            <div class="sb-nav-link-icon">
                                <img src="Img/icons/Configuraciones1.png" class="small-icon">
                            </div>
                            Psicólogos
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">