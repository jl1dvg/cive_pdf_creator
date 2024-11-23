<?php
ini_set('session.save_path', __DIR__ . '/../../sessions');
session_name('mi_sesion');
session_start();
require '../../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está logueado
    header('Location: ../auth_login.html');
    exit();
}

// Obtener la información del usuario logueado
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../images/favicon.ico">

    <title>Asistente CIVE - Dashboard</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="../css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="../css/horizontal-menu.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/skin_color.css">

</head>
<body class="layout-top-nav light-skin theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>
    <?php include '../header.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Reporte de Solicitudes de Cirugías</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Reporte de Solicitudes de
                                        Cirugías
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <?php
            // Consulta para obtener los datos de solicitudes de procedimiento
            $sql = "SELECT 
            sp.id,
            sp.hc_number, 
            sp.form_id,
            CONCAT(pd.fname, ' ', pd.lname, ' ', pd.lname2) AS full_name, 
            sp.tipo,
            pd.afiliacion,
            sp.procedimiento,
            sp.doctor,
            sp.fecha,
            sp.duracion,
            sp.ojo,
            sp.prioridad,
            sp.producto,
            sp.observacion,
            sp.created_at,
            pd.fecha_caducidad,
            cd.diagnosticos
        FROM solicitud_procedimiento sp
        INNER JOIN patient_data pd ON sp.hc_number = pd.hc_number
        LEFT JOIN consulta_data cd ON sp.hc_number = cd.hc_number AND sp.form_id = cd.form_id
        WHERE sp.procedimiento != 'SELECCIONE' AND sp.doctor != 'SELECCIONE'
        ORDER BY sp.fecha DESC";

            $result = $mysqli->query($sql);

            if (!$result) {
                die("Error en la consulta: " . $mysqli->error);
            }
            ?>

            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-body">
                                <div class="table-responsive rounded card-table">
                                    <table class="table border-no" id="surgeryTable">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>C.I.</th>
                                            <th>Nombre</th>
                                            <th>Afiliación</th>
                                            <th>Fecha de Solicitud</th>
                                            <th>Procedimiento</th>
                                            <th>Doctor</th>
                                            <th>Ojo</th>
                                            <th>Días Restantes</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody id="patientTableBody">
                                        <?php
                                        if ($result->num_rows > 0) {
                                            $counter = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                // Procesar procedimiento para mostrar solo el nombre
                                                $procedimiento_parts = explode(' - ', $row['procedimiento']);
                                                $nombre_procedimiento = ucwords(strtolower(end($procedimiento_parts)));

                                                // Convertir el nombre del doctor en tipo título
                                                $nombre_paciente = ucwords(strtolower($row['full_name']));
                                                $doctor = ucwords(strtolower($row['doctor']));

                                                // Procesar diagnósticos
                                                $diagnosticos_string = [];
                                                if (!empty($row['diagnosticos'])) {
                                                    $diagnosticos = json_decode($row['diagnosticos'], true);
                                                    if (is_array($diagnosticos)) {
                                                        foreach ($diagnosticos as $diagnostico) {
                                                            $diagnosticos_string[] = htmlspecialchars($diagnostico['idDiagnostico']) . ' (' . htmlspecialchars($diagnostico['ojo']) . ')';
                                                        }
                                                    }
                                                }
                                                $diagnosticos_formateados = implode(', ', $diagnosticos_string);

                                                // Calcular días restantes para fecha de caducidad
                                                $badgeClass = "badge-success-light";
                                                $dias_restantes = "N/A";
                                                if (!empty($row['fecha_caducidad'])) {
                                                    $fecha_caducidad = new DateTime($row['fecha_caducidad']);
                                                    $hoy = new DateTime();
                                                    $dias_restantes = (int)$hoy->diff($fecha_caducidad)->format('%r%a');

                                                    // Asignar clases de badge según el estado de caducidad
                                                    if ($dias_restantes < 0) {
                                                        // Fecha de caducidad ya pasó
                                                        $badgeClass = "badge-danger-light";
                                                    } elseif ($dias_restantes <= 30) {
                                                        // Próximo a caducar (30 días o menos)
                                                        $badgeClass = "badge-warning-light";
                                                    }
                                                }
                                                if (!empty($nombre_procedimiento)) {
                                                    echo "<tr class='hover-primary'>";
                                                    echo "<td>" . $counter++ . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['hc_number']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($nombre_paciente) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['afiliacion']) . "</td>";
                                                    echo "<td>" . date('d/m/Y', strtotime($row['created_at'])) . "</td>";

                                                    // Solo imprimir la línea del procedimiento si no está vacío
                                                    echo "<td>" . htmlspecialchars($nombre_procedimiento) . "</td>";

                                                    echo "<td>" . htmlspecialchars($doctor) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['ojo']) . "</td>";
                                                    echo "<td><span class='badge $badgeClass'>" . htmlspecialchars($dias_restantes) . "</span></td>";

                                                    // Columna de acciones con menú desplegable
                                                    echo "<td>";
                                                    echo "<div class='btn-group'>";
                                                    echo "<a class='hover-primary dropdown-toggle no-caret' data-bs-toggle='dropdown'><i class='fa fa-ellipsis-h'></i></a>";
                                                    echo "<div class='dropdown-menu'>";
                                                    echo "<a class='dropdown-item' href='solicitud_quirurgica/solicitud_qx_pdf.php?hc_number=" . urlencode($row['hc_number']) . "&form_id=" . urlencode($row['form_id']) . "' target='_blank'>Generar PDF</a>";
                                                    echo "<a class='dropdown-item' href='#'>View Details</a>";
                                                    echo "<a class='dropdown-item' href='#'>Edit</a>";
                                                    echo "<a class='dropdown-item' href='#'>Delete</a>";
                                                    echo "<div class='dropdown-divider'></div>";
                                                    echo "<a class='dropdown-item text-muted' href='#'>Diagnósticos: " . htmlspecialchars($diagnosticos_formateados) . "</a>";
                                                    echo "<a class='dropdown-item text-muted' href='#'>Observación: " . htmlspecialchars($row['observacion']) . "</a>";
                                                    echo "</div>";
                                                    echo "</div>";
                                                    echo "</td>";

                                                    echo "</tr>";
                                                }
                                            }
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

    <!--Model Popup Area-->
    <!-- result modal content -->
    <div class="modal fade" id="resultModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="result-popup">Resultados</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-between">
                        <div class="col-md-7 col-12">
                            <h4 id="test-name">Diagnóstico</h4>
                        </div>
                        <div class="col-md-5 col-12">
                            <h4 class="text-end" id="lab-order-id">Orden ID</h4>
                        </div>
                    </div>
                    <!-- Nueva tabla para Diagnósticos -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th scope="col">CIE10</th>
                                <th scope="col">Detalle</th>
                            </tr>
                            </thead>
                            <tbody id="diagnostico-table">
                            <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <!-- Nueva tabla para Procedimientos -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th scope="col">Código</th>
                                <th scope="col">Nombre del Procedimiento</th>
                            </tr>
                            </thead>
                            <tbody id="procedimientos-table">
                            <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Nueva tabla para mostrar fecha de inicio, hora de inicio, hora de fin, y duración -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th>Fecha de Inicio</th>
                                <th>Hora de Inicio</th>
                                <th>Hora de Fin</th>
                                <th>Duración</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr id="timing-row">
                                <!-- Se llenará dinámicamente con 4 <td> -->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th scope="col" colspan="2">Procedimiento</th>
                            </tr>
                            </thead>
                            <tbody id="result-table">
                            <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-secondary">
                            <tr>
                                <th scope="col" colspan="2">Staff Quirúrgico</th>
                            </tr>
                            </thead>
                            <tbody id="staff-table">
                            <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div class="comment">
                        <p><span class="fw-600">Comentario</span> : <span class="comment-here text-mute"></span></p>
                    </div>
                    <!-- Agregar checkbox para marcar como revisado -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="markAsReviewed">
                        <label class="form-check-label" for="markAsReviewed">Marcar como revisado</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-right" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-info pull-right">Imprimir</button>
                    <button type="button" class="btn btn-success pull-right" onclick="updateProtocolStatus()">Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- comment modal content -->
<div class="modal fade" id="comment-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="comment-popup">Radiology Investigations - Comment</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-between">
                    <div class="col-12">
                        <h4>Comment</h4>
                    </div>
                </div>
                <form>
                    <div class="form-group">
                        <textarea class="form-control" id="comment-area" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-right" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success pull-right me-10">Save</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include '../footer.php'; ?>
<!-- Control Sidebar -->
<aside class="control-sidebar">

    <div class="rpanel-title"><span class="pull-right btn btn-circle btn-danger" data-toggle="control-sidebar"><i
                    class="ion ion-close text-white"></i></span></div>  <!-- Create the tabs -->
    <ul class="nav nav-tabs control-sidebar-tabs">
        <li class="nav-item"><a href="#control-sidebar-home-tab" data-bs-toggle="tab" class="active"><i
                        class="mdi mdi-message-text"></i></a></li>
        <li class="nav-item"><a href="#control-sidebar-settings-tab" data-bs-toggle="tab"><i
                        class="mdi mdi-playlist-check"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-home-tab">
            <div class="flexbox">
                <a href="javascript:void(0)" class="text-grey">
                    <i class="ti-more"></i>
                </a>
                <p>Users</p>
                <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
            </div>
            <div class="lookup lookup-sm lookup-right d-none d-lg-block">
                <input type="text" name="s" placeholder="Search" class="w-p100">
            </div>
            <div class="media-list media-list-hover mt-20">
                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-success" href="#">
                        <img src="../../images/avatar/1.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                        </p>
                        <p>Praesent tristique diam...</p>
                        <span>Just now</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-danger" href="#">
                        <img src="../../images/avatar/2.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Luke</strong></a>
                        </p>
                        <p>Cras tempor diam ...</p>
                        <span>33 min ago</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-warning" href="#">
                        <img src="../../images/avatar/3.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Evan</strong></a>
                        </p>
                        <p>In posuere tortor vel...</p>
                        <span>42 min ago</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-primary" href="#">
                        <img src="../../images/avatar/4.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Evan</strong></a>
                        </p>
                        <p>In posuere tortor vel...</p>
                        <span>42 min ago</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-success" href="#">
                        <img src="../../images/avatar/1.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Tyler</strong></a>
                        </p>
                        <p>Praesent tristique diam...</p>
                        <span>Just now</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-danger" href="#">
                        <img src="../../images/avatar/2.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Luke</strong></a>
                        </p>
                        <p>Cras tempor diam ...</p>
                        <span>33 min ago</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-warning" href="#">
                        <img src="../../images/avatar/3.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Evan</strong></a>
                        </p>
                        <p>In posuere tortor vel...</p>
                        <span>42 min ago</span>
                    </div>
                </div>

                <div class="media py-10 px-0">
                    <a class="avatar avatar-lg status-primary" href="#">
                        <img src="../../images/avatar/4.jpg" alt="...">
                    </a>
                    <div class="media-body">
                        <p class="fs-16">
                            <a class="hover-primary" href="#"><strong>Evan</strong></a>
                        </p>
                        <p>In posuere tortor vel...</p>
                        <span>42 min ago</span>
                    </div>
                </div>

            </div>

        </div>
        <!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <div class="flexbox">
                <a href="javascript:void(0)" class="text-grey">
                    <i class="ti-more"></i>
                </a>
                <p>Todo List</p>
                <a href="javascript:void(0)" class="text-end text-grey"><i class="ti-plus"></i></a>
            </div>
            <ul class="todo-list mt-20">
                <li class="py-15 px-5 by-1">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_1" class="filled-in">
                    <label for="basic_checkbox_1" class="mb-0 h-15"></label>
                    <!-- todo text -->
                    <span class="text-line">Nulla vitae purus</span>
                    <!-- Emphasis label -->
                    <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_2" class="filled-in">
                    <label for="basic_checkbox_2" class="mb-0 h-15"></label>
                    <span class="text-line">Phasellus interdum</span>
                    <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5 by-1">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_3" class="filled-in">
                    <label for="basic_checkbox_3" class="mb-0 h-15"></label>
                    <span class="text-line">Quisque sodales</span>
                    <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_4" class="filled-in">
                    <label for="basic_checkbox_4" class="mb-0 h-15"></label>
                    <span class="text-line">Proin nec mi porta</span>
                    <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5 by-1">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_5" class="filled-in">
                    <label for="basic_checkbox_5" class="mb-0 h-15"></label>
                    <span class="text-line">Maecenas scelerisque</span>
                    <small class="badge bg-primary"><i class="fa fa-clock-o"></i> 1 week</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_6" class="filled-in">
                    <label for="basic_checkbox_6" class="mb-0 h-15"></label>
                    <span class="text-line">Vivamus nec orci</span>
                    <small class="badge bg-info"><i class="fa fa-clock-o"></i> 1 month</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5 by-1">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_7" class="filled-in">
                    <label for="basic_checkbox_7" class="mb-0 h-15"></label>
                    <!-- todo text -->
                    <span class="text-line">Nulla vitae purus</span>
                    <!-- Emphasis label -->
                    <small class="badge bg-danger"><i class="fa fa-clock-o"></i> 2 mins</small>
                    <!-- General tools such as edit or delete-->
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_8" class="filled-in">
                    <label for="basic_checkbox_8" class="mb-0 h-15"></label>
                    <span class="text-line">Phasellus interdum</span>
                    <small class="badge bg-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5 by-1">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_9" class="filled-in">
                    <label for="basic_checkbox_9" class="mb-0 h-15"></label>
                    <span class="text-line">Quisque sodales</span>
                    <small class="badge bg-warning"><i class="fa fa-clock-o"></i> 1 day</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
                <li class="py-15 px-5">
                    <!-- checkbox -->
                    <input type="checkbox" id="basic_checkbox_10" class="filled-in">
                    <label for="basic_checkbox_10" class="mb-0 h-15"></label>
                    <span class="text-line">Proin nec mi porta</span>
                    <small class="badge bg-success"><i class="fa fa-clock-o"></i> 3 days</small>
                    <div class="tools">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-trash-o"></i>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->

<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Sidebar -->

<div id="chat-box-body">
    <div id="chat-circle" class="waves-effect waves-circle btn btn-circle btn-sm btn-warning l-h-50">
        <div id="chat-overlay"></div>
        <span class="icon-Group-chat fs-18"><span class="path1"></span><span class="path2"></span></span>
    </div>

    <div class="chat-box">
        <div class="chat-box-header p-15 d-flex justify-content-between align-items-center">
            <div class="btn-group">
                <button class="waves-effect waves-circle btn btn-circle btn-primary-light h-40 w-40 rounded-circle l-h-45"
                        type="button" data-bs-toggle="dropdown">
                    <span class="icon-Add-user fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
                <div class="dropdown-menu min-w-200">
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Color me-15"></span>
                        New Group</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Clipboard me-15"><span class="path1"></span><span class="path2"></span><span
                                    class="path3"></span><span class="path4"></span></span>
                        Contacts</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Group me-15"><span class="path1"></span><span class="path2"></span></span>
                        Groups</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Active-call me-15"><span class="path1"></span><span
                                    class="path2"></span></span>
                        Calls</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Settings1 me-15"><span class="path1"></span><span class="path2"></span></span>
                        Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Question-circle me-15"><span class="path1"></span><span class="path2"></span></span>
                        Help</a>
                    <a class="dropdown-item fs-16" href="#">
                        <span class="icon-Notifications me-15"><span class="path1"></span><span
                                    class="path2"></span></span>
                        Privacy</a>
                </div>
            </div>
            <div class="text-center flex-grow-1">
                <div class="text-dark fs-18">Mayra Sibley</div>
                <div>
                    <span class="badge badge-sm badge-dot badge-primary"></span>
                    <span class="text-muted fs-12">Active</span>
                </div>
            </div>
            <div class="chat-box-toggle">
                <button id="chat-box-toggle"
                        class="waves-effect waves-circle btn btn-circle btn-danger-light h-40 w-40 rounded-circle l-h-45"
                        type="button">
                    <span class="icon-Close fs-22"><span class="path1"></span><span class="path2"></span></span>
                </button>
            </div>
        </div>
        <div class="chat-box-body">
            <div class="chat-box-overlay">
            </div>
            <div class="chat-logs">
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="../../images/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-muted fs-12 mb-0">2 Hours</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Hi there, I'm Jesse and you?
                    </div>
                </div>
                <div class="chat-msg self">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">You</a>
                            <p class="text-muted fs-12 mb-0">3 minutes</p>
                        </div>
                        <span class="msg-avatar">
                                <img src="../../images/avatar/3.jpg" class="avatar avatar-lg">
                            </span>
                    </div>
                    <div class="cm-msg-text">
                        My name is Anne Clarc.
                    </div>
                </div>
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="../../images/avatar/2.jpg" class="avatar avatar-lg">
                            </span>
                        <div class="mx-10">
                            <a href="#" class="text-dark hover-primary fw-bold">Mayra Sibley</a>
                            <p class="text-muted fs-12 mb-0">40 seconds</p>
                        </div>
                    </div>
                    <div class="cm-msg-text">
                        Nice to meet you Anne.<br>How can i help you?
                    </div>
                </div>
            </div><!--chat-log -->
        </div>
        <div class="chat-input">
            <form>
                <input type="text" id="chat-input" placeholder="Send a message..."/>
                <button type="submit" class="chat-submit" id="chat-submit">
                    <span class="icon-Send fs-22"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Page Content overlay -->
<script>
    function togglePrintStatus(form_id, hc_number, button, currentStatus) {
        // Verificar si el botón está activo
        var isActive = button.classList.contains('active');
        var newStatus = isActive ? 1 : 0;  // Si el botón está activo (on), el nuevo estado será 0 (off); si no, será 1 (on)

        // Cambiar visualmente el estado del botón
        if (isActive) {
            button.classList.add('active');
            button.setAttribute('aria-pressed', 'true');
        } else {
            button.classList.remove('active');
            button.setAttribute('aria-pressed', 'false');
        }

        // Realizar la petición AJAX para actualizar el estado en la base de datos
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_print_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('form_id=' + form_id + '&hc_number=' + hc_number + '&printed=' + newStatus);

        xhr.onload = function () {
            if (xhr.status === 200 && xhr.responseText === 'success') {
                console.log('Estado actualizado en la base de datos');

                // Solo si el nuevo estado es "on" (printed = 1), generamos el PDF
                if (newStatus === 1) {
                    window.open('../../generate_pdf.php?form_id=' + form_id + '&hc_number=' + hc_number, '_blank');
                }
            } else {
                console.log('Error al actualizar el estado');
            }
        };
    }

    let currentFormId;  // Variable para almacenar el form_id actual
    let currentHcNumber;  // Variable para almacenar el hc_number actual
    function loadResult(rowData) {
        // Guardar form_id y hc_number para uso posterior
        currentFormId = rowData.form_id;
        currentHcNumber = rowData.hc_number;

        // Actualizar el contenido del modal con los datos de la fila seleccionada
        document.getElementById('result-popup').innerHTML = "QX realizada - " + rowData.membrete;
        document.getElementById('lab-order-id').innerHTML = "Protocolo: " + rowData.form_id;

        // Marcar o desmarcar el checkbox basado en el estado del protocolo (status)
        const markAsReviewedCheckbox = document.getElementById('markAsReviewed');
        markAsReviewedCheckbox.checked = rowData.status == 1 ? true : false;  // Si el estado es 1, marcar el checkbox

        // Procesar los diagnósticos
        let diagnosticoData = JSON.parse(rowData.diagnosticos);  // Asegurarse de que esté en formato JSON
        let diagnosticoTable = '';

        diagnosticoData.forEach(diagnostico => {
            let cie10 = '';
            let detalle = '';

            // Dividir el campo idDiagnostico en código y detalle
            if (diagnostico.idDiagnostico) {
                const parts = diagnostico.idDiagnostico.split(' - ', 2);  // Separar por " - "
                cie10 = parts[0];  // CIE10 Code
                detalle = parts[1];  // Detail
            }

            // Agregar una fila a la tabla
            diagnosticoTable += `
                <tr>
                    <td>${cie10}</td>
                    <td>${detalle}</td>
                </tr>
            `;
        });

        // Insertar la tabla de diagnóstico en el modal
        document.getElementById('diagnostico-table').innerHTML = diagnosticoTable;

        // Procesar los procedimientos
        let procedimientoData = JSON.parse(rowData.procedimientos);  // Convertir a JSON
        let procedimientoTable = '';

        procedimientoData.forEach(procedimiento => {
            let codigo = '';
            let nombre = '';

            // Dividir el campo procInterno en código y nombre
            if (procedimiento.procInterno) {
                const parts = procedimiento.procInterno.split(' - ', 3);  // Separar por " - "
                codigo = parts[1];  // Código del procedimiento
                nombre = parts[2];  // Nombre del procedimiento
            }

            // Agregar una fila a la tabla de procedimientos
            procedimientoTable += `
                <tr>
                    <td>${codigo}</td>
                    <td>${nombre}</td>
                </tr>
            `;
        });

        // Insertar la tabla de procedimientos en el modal
        document.getElementById('procedimientos-table').innerHTML = procedimientoTable;


        // Llenar otras tablas como antes (resultados, tiempos, staff, etc.)
        document.getElementById('result-table').innerHTML = `
                <tr>
                    <td>Dieresis</td>
                <td>${rowData.dieresis}</td>
            </tr>
                <tr>
                    <td>Exposición</td>
                <td>${rowData.exposicion}</td>
            </tr>
                <tr>
                    <td>Hallazgo</td>
                <td>${rowData.hallazgo}</td>
            </tr>
                <tr>
                    <td>Operatorio</td>
                <td>${rowData.operatorio}</td>
            </tr>
            `;

        // Calcular la duración entre hora_inicio y hora_fin
        let horaInicio = new Date('1970-01-01T' + rowData.hora_inicio + 'Z');
        let horaFin = new Date('1970-01-01T' + rowData.hora_fin + 'Z');
        let diff = new Date(horaFin - horaInicio);  // Diferencia de tiempo

        let duration = `${diff.getUTCHours().toString().padStart(2, '0')}:${diff.getUTCMinutes().toString().padStart(2, '0')}`;

        // Actualizar la fila con la fecha de inicio, hora de inicio, hora de fin y duración
        document.getElementById('timing-row').innerHTML = `
            <td>${rowData.fecha_inicio}</td>
            <td>${rowData.hora_inicio}</td>
            <td>${rowData.hora_fin}</td>
            <td>${duration}</td>
        `;

        // Inicializar el staffTable vacía
        let staffTable = '';

        // Campos del staff que queremos mostrar si no están vacíos
        const staffFields = {
            'Cirujano Principal': rowData.cirujano_1,
            'Instrumentista': rowData.instrumentista,
            'Cirujano Asistente': rowData.cirujano_2,
            'Circulante': rowData.circulante,
            'Primer Ayudante': rowData.primer_ayudante,
            'Anestesiólogo': rowData.anestesiologo,
            'Segundo Ayudante': rowData.segundo_ayudante,
            'Ayudante de Anestesia': rowData.ayudante_anestesia,
            'Tercer Ayudante': rowData.tercer_ayudante
        };

        // Iterar sobre los campos del staff y añadir solo los que no están vacíos
        for (const [label, value] of Object.entries(staffFields)) {
            if (value && value.trim() !== '') {
                staffTable += `
                    <tr>
                        <td>${label}</td>
                        <td>${value}</td>
                    </tr>
                `;
            }
        }

        // Agregar el contenido del staff al modal
        document.getElementById('staff-table').innerHTML = staffTable;

        // Actualizar los comentarios y las firmas
        document.querySelector('.comment-here').innerHTML = rowData.complicaciones_operatorio || 'Sin comentarios';
    }

    function updateProtocolStatus() {
        // Obtener si el checkbox está marcado
        const isReviewed = document.getElementById('markAsReviewed').checked ? 1 : 0;

        // Realizar la petición AJAX para actualizar el campo "status" en la base de datos
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_protocol_status.php', true);  // Archivo PHP para manejar la actualización
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);  // Ver la respuesta del servidor

                // Verificamos si la respuesta contiene 'success'
                if (xhr.status === 200 && xhr.responseText.trim().includes('success')) {
                    console.log('Estado del protocolo actualizado correctamente');

                    // Cerrar el modal sin usar jQuery
                    const modalElement = document.getElementById('resultModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    modalInstance.hide();

                    // Recargar la tabla general después de cerrar el modal
                    reloadPatientTable();
                } else {
                    console.log('Error al actualizar el estado del protocolo');
                }
            }
        };
        // Enviar el form_id, hc_number y el nuevo estado (revisado o no)
        xhr.send(`form_id=${encodeURIComponent(currentFormId)}&hc_number=${encodeURIComponent(currentHcNumber)}&status=${isReviewed}`);
    }

    function reloadPatientTable() {
        // Hacer una petición AJAX al mismo archivo
        const xhr = new XMLHttpRequest();
        xhr.open('GET', window.location.href, true);  // Hacer una petición GET al mismo archivo PHP
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');  // Esto ayuda a diferenciar solicitudes AJAX

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Actualizar el contenido del tbody con el nuevo HTML de las filas
                const parser = new DOMParser();
                const htmlDoc = parser.parseFromString(xhr.responseText, 'text/html');
                const newTableBody = htmlDoc.getElementById('patientTableBody').innerHTML;
                document.getElementById('patientTableBody').innerHTML = newTableBody;
            }
        };
        xhr.send();
    }
</script>


<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>
<script src="../js/pages/chat-popup.js"></script>
<script src="../../assets/icons/feather-icons/feather.min.js"></script>
<script src="../../assets/vendor_components/datatable/datatables.min.js"></script>

<script>
    // Custom sorting for dd/mm/yyyy format
    $.fn.dataTable.ext.type.order['dd-mm-yyyy-pre'] = function (d) {
        if (!d) {
            return 0;
        }
        var parts = d.split('/');
        return new Date(parts[2], parts[1] - 1, parts[0]).getTime();
    };

    // Custom sorting for "Días Restantes" column
    $.fn.dataTable.ext.type.order['dias-restantes-pre'] = function (d) {
        if (d === "N/A") {
            return Infinity; // Coloca "N/A" al final al ordenar ascendente
        }
        return parseInt(d.trim(), 10); // Convierte el valor a un entero
    };

    $(document).ready(function () {
        $('#surgeryTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "columnDefs": [
                {
                    "targets": 4, // Índice de columna para la fecha de solicitud (ajustar si es necesario)
                    "type": "dd-mm-yyyy"
                },
                {
                    "targets": 9, // Índice de columna para "Días Restantes" (ajustar si es necesario)
                    "type": "dias-restantes" // Tipo de ordenación personalizada
                }
            ]
        });
    });
</script>

<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
</body>
</html>
