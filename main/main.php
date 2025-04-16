<?php
ini_set('session.save_path', __DIR__ . '/../sessions');
session_name('mi_sesion');
session_start();
require '../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está logueado
    header('Location: auth_login.html');
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
    <link rel="icon" href="../images/favicon.ico">

    <title>Asistente CIVE - Dashboard</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="css/horizontal-menu.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/skin_color.css">

</head>

<body class="layout-top-nav light-skin theme-primary fixed">

<div class="wrapper">
    <div id="loader"></div>

    <?php include 'header.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="container-full">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xxxl-9 col-xl-8 col-12">
                        <div class="row">
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-20.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_patients = "SELECT COUNT(*) AS total FROM patient_data";
                                                $result_total_patients = $mysqli->query($sql_total_patients);

                                                $total_patients = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_patients->num_rows > 0) {
                                                    $row_total = $result_total_patients->fetch_assoc();
                                                    $total_patients = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Total de Pacientes</h4>
                                                <h3 class="mb-0"><?php echo $total_patients; ?></h3>
                                                <!-- Mostrar el total de pacientes -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-18.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_users = "SELECT COUNT(*) AS total FROM users";
                                                $result_total_users = $mysqli->query($sql_total_users);

                                                $total_users = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_users->num_rows > 0) {
                                                    $row_total = $result_total_users->fetch_assoc();
                                                    $total_users = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Staffs</h4>
                                                <h3 class="mb-0"><?php echo $total_users; ?></h3>
                                                <!-- Mostrar el total de protocolo -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-15">
                                                <img src="../images/svg-icon/color-svg/custom-19.svg" alt=""
                                                     class="w-120"/>
                                            </div>
                                            <div>
                                                <?php
                                                // Consulta para obtener el total de pacientes
                                                $sql_total_protocolo = "SELECT COUNT(*) AS total FROM protocolo_data";
                                                $result_total_protocolo = $mysqli->query($sql_total_protocolo);

                                                $total_protocolo = 0;  // Inicializar en 0 por si no se encuentran registros
                                                if ($result_total_protocolo->num_rows > 0) {
                                                    $row_total = $result_total_protocolo->fetch_assoc();
                                                    $total_protocolo = $row_total['total'];
                                                }
                                                ?>
                                                <h4 class="mb-0">Cirigías Realizadas</h4>
                                                <h3 class="mb-0"><?php echo $total_protocolo; ?></h3>
                                                <!-- Mostrar el total de protocolo -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Tipos de cirugías realizadas</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="patient_statistics"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="flexbox mb-20">
                                            <div class="dropdown">
                                                <h6 class="text-uppercase dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    Today</h6>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item active" href="#">Today</a>
                                                    <a class="dropdown-item" href="#">Yesterday</a>
                                                    <a class="dropdown-item" href="#">Last week</a>
                                                    <a class="dropdown-item" href="#">Last month</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="recovery_statistics"></div>
                                        <!-- Este es el id donde aparecerá el gráfico pie -->
                                    </div>
                                </div>
                            </div>
                            <?php
                            // Consulta para obtener los 9 registros más recientes ordenados por fecha
                            $sql = "SELECT p.hc_number, p.fname, p.lname, p.lname2, p.fecha_nacimiento, p.ciudad, p.afiliacion, 
                                    pr.fecha_inicio, pr.id, pr.membrete, pr.form_id
                                    FROM patient_data p 
                                    INNER JOIN protocolo_data pr ON p.hc_number = pr.hc_number
                                    ORDER BY pr.fecha_inicio DESC, pr.id DESC
                                    LIMIT 8";
                            $result = $mysqli->query($sql);

                            if ($result->num_rows > 0) {
                                $patients = $result->fetch_all(MYSQLI_ASSOC);  // Guardar todos los datos en un array asociativo
                            } else {
                                $patients = [];  // Si no hay datos
                            }

                            // Consulta para obtener el total de pacientes en la tabla protocolo_data
                            $totalSql = "SELECT COUNT(*) as total FROM protocolo_data";
                            $totalResult = $mysqli->query($totalSql);
                            $totalPatients = $totalResult->fetch_assoc()['total'];  // Guardar el total de pacientes
                            ?>
                            <div class="col-12">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Cirugías Recientes</h4>
                                        <div class="box-controls pull-right">
                                            <div class="lookup lookup-circle lookup-right">
                                                <input type="text" name="s">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body no-padding">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                <tr class="bg-info-light">
                                                    <th>No</th>
                                                    <th>Fecha</th>
                                                    <th>ID</th>
                                                    <th>Nombre</th>
                                                    <th>Edad</th>
                                                    <th>Procedimiento</th>
                                                    <th>Afiliación</th>
                                                    <th>Opciones</th>
                                                </tr>
                                                <?php if (!empty($patients)): ?>
                                                    <?php foreach ($patients as $index => $patient): ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>  <!-- Contador -->
                                                            <td><?php echo date('d/m/Y', strtotime($patient['fecha_inicio'])); ?></td>
                                                            <!-- Fecha de cirugía -->
                                                            <td><?php echo $patient['hc_number']; ?></td>
                                                            <!-- Número de historia clínica -->
                                                            <td>
                                                                <strong><?php echo $patient['fname'] . ' ' . $patient['lname'] . ' ' . $patient['lname2']; ?></strong>
                                                            </td>  <!-- Nombre completo -->
                                                            <td>
                                                                <?php
                                                                // Calcular la edad a partir de la fecha de nacimiento
                                                                $birthDate = new DateTime($patient['fecha_nacimiento']);
                                                                $today = new DateTime($patient['fecha_inicio']);
                                                                $age = $today->diff($birthDate)->y;
                                                                echo $age;
                                                                ?>
                                                            </td>
                                                            <td><?php echo $patient['membrete']; ?></td>
                                                            <!-- Procedimiento -->
                                                            <td><?php echo $patient['afiliacion']; ?></td>
                                                            <!-- Afiliación -->
                                                            <td>
                                                                <div class="d-flex">
                                                                    <a href="edit_protocol.php?form_id=<?php echo $patient['form_id']; ?>&hc_number=<?php echo $patient['hc_number']; ?>"
                                                                       class="waves-effect waves-circle btn btn-circle btn-success btn-xs me-5"><i
                                                                                class="fa fa-pencil"></i></a>
                                                                    <a href="../generate_pdf.php?form_id=<?php echo $patient['form_id']; ?>&hc_number=<?php echo $patient['hc_number']; ?>"
                                                                       class="waves-effect waves-circle btn btn-circle btn-secondary btn-xs"><i
                                                                                class="fa fa-print"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No data available</td>
                                                    </tr>
                                                <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="box-footer bg-light py-10 with-border">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">Total <?php echo $totalPatients; ?> Patient(s)</p>
                                            <a href="reports/qx_reports.php"
                                               class="waves-effect waves-light btn btn-primary">Ver Todos</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-body px-0 pb-0">
                                        <div class="px-20 bb-1 pb-15 d-flex align-items-center justify-content-between">
                                            <h4 class="mb-0">Plantillas Recientes</h4>
                                            <div class="d-flex align-items-center justify-content-end">
                                                <button type="button"
                                                        class="waves-effect waves-light btn btn-sm btn-primary-light btn-filter active"
                                                        data-filter="all">
                                                    All
                                                </button>
                                                <button type="button"
                                                        class="waves-effect waves-light mx-10 btn btn-sm btn-primary-light btn-filter"
                                                        data-filter="creado">
                                                    Creado
                                                </button>
                                                <button type="button"
                                                        class="waves-effect waves-light btn btn-sm btn-primary-light btn-filter"
                                                        data-filter="modificado">
                                                    Modificado
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="inner-user-div4" id="plantilla-container">
                                            <?php
                                            $sql = "SELECT id, membrete, cirugia, 
                                                   COALESCE(fecha_actualizacion, fecha_creacion) AS fecha,
                                                   CASE 
                                                       WHEN fecha_actualizacion IS NOT NULL THEN 'Modificado'
                                                       ELSE 'Creado'
                                                   END AS tipo
                                            FROM procedimientos
                                            ORDER BY fecha DESC
                                            LIMIT 20";
                                            $result = $mysqli->query($sql);

                                            if ($result && $result->num_rows > 0):
                                                while ($row = $result->fetch_assoc()):
                                                    ?>
                                                    <div class="d-flex justify-content-between align-items-center pb-20 mb-10 bb-dashed border-bottom plantilla-card"
                                                         data-tipo="<?= $row['tipo'] ?>">
                                                        <div class="pe-20">
                                                            <p class="fs-12 text-fade"><?= date('d M Y', strtotime($row['fecha'])) ?>
                                                                <span class="mx-10">/</span> <?= $row['tipo'] ?></p>
                                                            <h4><?= $row['membrete'] ?></h4>
                                                            <p class="text-fade mb-5"><?= $row['cirugia'] ?></p>
                                                            <div class="d-flex align-items-center">
                                                                <a href="editors/protocolos_editors_templates.php?id=<?= $row['id'] ?>"
                                                                   class="waves-effect waves-light btn me-10 btn-xs btn-primary-light">Ver</a>
                                                                <a href="../generate_pdf.php?id=<?= $row['id'] ?>"
                                                                   class="waves-effect waves-light btn btn-xs btn-primary-light">PDF</a>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <a href="edit_protocol.php?id=<?= $row['id'] ?>"
                                                               class="waves-effect waves-circle btn btn-circle btn-outline btn-light btn-lg">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php endwhile; else: ?>
                                                <p class="text-muted">No hay protocolos recientes.</p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end mt-2 fs-12 text-fade">
                                            <span id="plantilla-count">Mostrando <?= $result->num_rows ?> plantillas</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="box">
                                    <div class="box-header">
                                        <h4 class="box-title">Diagnósticos más frecuentes</h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="news-slider owl-carousel owl-sl">
                                            <?php
                                            $sql = "SELECT hc_number, diagnosticos FROM consulta_data WHERE diagnosticos IS NOT NULL AND diagnosticos != ''";
                                            $result = $mysqli->query($sql);

                                            $conteoDiagnosticos = [];

                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $hc = $row['hc_number'];
                                                    $diagnosticos = json_decode($row['diagnosticos'], true);

                                                    if (is_array($diagnosticos)) {
                                                        foreach ($diagnosticos as $dx) {
                                                            $id = strtoupper(str_replace('.', '', $dx['idDiagnostico']));
                                                            $desc = $dx['descripcion'];
                                                            if (stripos($id, 'Z') === 0) continue; // Excluir diagnósticos tipo Z

                                                            // Agrupación específica: unificar H25 y H251 como un solo diagnóstico
                                                            if ($id === 'H25' || $id === 'H251') {
                                                                $key = 'H25 | Catarata senil';
                                                            } else {
                                                                $key = $id . ' | ' . $desc;
                                                            }

                                                            $conteoDiagnosticos[$key][$hc] = true;
                                                        }
                                                    }
                                                }
                                            }

                                            // Calcular cuántos pacientes únicos por diagnóstico
                                            $prevalencias = [];
                                            foreach ($conteoDiagnosticos as $key => $pacientes) {
                                                $prevalencias[$key] = count($pacientes);
                                            }

                                            // Calcular total de pacientes únicos con al menos un diagnóstico
                                            $totalPacientes = [];
                                            foreach ($conteoDiagnosticos as $pacientes) {
                                                foreach ($pacientes as $hc => $v) {
                                                    $totalPacientes[$hc] = true;
                                                }
                                            }
                                            $totalPacientesCount = count($totalPacientes);

                                            // Ordenar y tomar los 4 más frecuentes
                                            arsort($prevalencias);
                                            $top = array_slice($prevalencias, 0, 9, true);

                                            if ($totalPacientesCount > 0):
                                                foreach ($top as $key => $cantidad):
                                                    $porcentaje = round(($cantidad / $totalPacientesCount) * 100, 1);
                                                    ?>
                                                    <div>
                                                        <div class="d-flex align-items-center mb-10">
                                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                                <p class="hover-primary text-fade mb-1 fs-14"><i
                                                                            class="fa fa-stethoscope"></i> Diagnóstico
                                                                </p>
                                                                <span class="text-dark fs-16"><?= $key ?></span>
                                                                <p class="mb-0 fs-14"><?= $porcentaje ?>% de pacientes
                                                                    <span class="badge badge-dot badge-primary"></span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="progress progress-xs mt-5">
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                 style="width: <?= $porcentaje ?>%"
                                                                 aria-valuenow="<?= $porcentaje ?>" aria-valuemin="0"
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; else: ?>
                                                <p class="text-muted">No hay diagnósticos registrados.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxxl-3 col-xl-4 col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Cirugías diarias</h4>
                            </div>
                            <div class="box-body">
                                <div id="total_patient"></div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Últimas Solicitudes Quirúrgicas</h4>
                            </div>
                            <div class="box-body">
                                <?php
                                $sql = "SELECT sp.id, sp.fecha, sp.procedimiento, p.fname, p.lname, p.hc_number
                                        FROM solicitud_procedimiento sp
                                        JOIN patient_data p ON sp.hc_number = p.hc_number
                                        WHERE sp.procedimiento IS NOT NULL AND sp.procedimiento != '' AND sp.procedimiento != 'SELECCIONE'
                                        ORDER BY sp.fecha DESC
                                        LIMIT 5";
                                $result = $mysqli->query($sql);

                                if ($result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
                                    <div class="d-flex justify-content-between align-items-start mb-10 border-bottom pb-10">
                                        <div>
                                            <strong><?= $row['fname'] . ' ' . $row['lname'] ?></strong><br>
                                            <span class="text-fade"><?= $row['procedimiento'] ?> | <?= date('d/m/Y', strtotime($row['fecha'])) ?></span>
                                        </div>
                                        <div>
                                            <a href="ver_solicitud.php?id=<?= $row['id'] ?>"
                                               class="btn btn-xs btn-primary-light">
                                                Ver
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; else: ?>
                                    <p class="text-muted">No hay solicitudes registradas.</p>
                                <?php endif; ?>

                                <hr>
                                <?php
                                $sqlTotal = "SELECT COUNT(*) as total 
                                   FROM solicitud_procedimiento 
                                   WHERE procedimiento IS NOT NULL 
                                     AND procedimiento != '' 
                                     AND procedimiento != 'SELECCIONE'";
                                $resTotal = $mysqli->query($sqlTotal);
                                $total = $resTotal->fetch_assoc()['total'];
                                ?>
                                <p class="mb-0 text-end"><strong>Total:</strong> <?= $total ?> solicitud(es)</p>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Doctor List</h4>
                                <p class="mb-0 pull-right">Últimos 3 meses</p>
                            </div>
                            <div class="box-body">
                                <div class="inner-user-div3">
                                    <?php
                                    $sql = "SELECT cirujano_1, COUNT(*) as total
                    FROM protocolo_data
                    WHERE cirujano_1 IS NOT NULL 
                      AND cirujano_1 != ''
                      AND fecha_inicio >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                    GROUP BY cirujano_1
                    ORDER BY total DESC
                    LIMIT 5";
                                    $result = $mysqli->query($sql);

                                    if ($result && $result->num_rows > 0):
                                        while ($row = $result->fetch_assoc()):
                                            $doctorName = $row['cirujano_1'];
                                            $totalCirugias = $row['total'];
                                            ?>
                                            <div class="d-flex align-items-center mb-30">
                                                <div class="me-15">
                                                    <img src="../images/avatar/avatar-<?= rand(1, 15) ?>.png"
                                                         class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                                </div>
                                                <div class="d-flex flex-column flex-grow-1 fw-500">
                                                    <span class="text-dark mb-1 fs-16"><?= $doctorName ?></span>
                                                    <span class="text-fade">Cirugías: <?= $totalCirugias ?></span>
                                                </div>
                                            </div>
                                        <?php endwhile; else: ?>
                                        <p class="text-muted">No hay datos disponibles.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->
    <?php include 'footer.php'; ?>

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
                <?php include 'components/notifications_placeholder.php' ?>
            </div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <?php include 'components/settings_placeholder.php' ?>
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
    <?php include 'components/chat.php' ?>
</div>

<?php
// Consulta SQL para obtener el número de procedimientos por día
$sql = "SELECT DATE(fecha_inicio) as fecha, COUNT(*) as total_procedimientos 
        FROM protocolo_data 
        GROUP BY DATE(fecha_inicio)
        ORDER BY fecha DESC 
        LIMIT 12";  // Limitar a los últimos 12 días

$result = $mysqli->query($sql);

$fechas = [];
$procedimientos_por_dia = [];  // Renombrar esta variable para evitar conflicto

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fechas[] = date('Y-m-d', strtotime($row['fecha']));
        $procedimientos_por_dia[] = $row['total_procedimientos'];
    }
} else {
    $fechas = ['No data'];
    $procedimientos_por_dia = [0];  // Asegurarse de no usar la misma variable dos veces
}

// Convertir los arrays a formato JSON
$fechas_json = json_encode($fechas);
$procedimientos_dia_json = json_encode($procedimientos_por_dia);

// Obtener el mes y año actual
$current_month = date('m');
$current_year = date('Y');

//MONTH(fecha_inicio) = '$current_month' AND

$sql = "SELECT procedimiento_id, COUNT(*) as total_procedimientos 
        FROM protocolo_data 
        WHERE YEAR(fecha_inicio) = '$current_year' 
          AND procedimiento_id IS NOT NULL 
          AND procedimiento_id != ''
        GROUP BY procedimiento_id
        ORDER BY total_procedimientos DESC
        LIMIT 5";

$result = $mysqli->query($sql);

$membretes = [];
$procedimientos_por_membrete = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $membretes[] = $row['procedimiento_id'];  // Lista de IDs de procedimientos
        $procedimientos_por_membrete[] = $row['total_procedimientos']; // Total por procedimiento
    }
} else {
    $membretes = ['No data'];
    $procedimientos_por_membrete = [0];
}

// Convertir los arrays a formato JSON
$membretes_json = json_encode($membretes);
$procedimientos_membrete_json = json_encode($procedimientos_por_membrete);

// Consulta SQL para contar los procedimientos por afiliación en el mes actual
$sql = "SELECT p.afiliacion, COUNT(*) as total_procedimientos
        FROM protocolo_data pr
        INNER JOIN patient_data p ON pr.hc_number = p.hc_number
        WHERE MONTH(pr.fecha_inicio) = '$current_month' AND YEAR(pr.fecha_inicio) = '$current_year'
        GROUP BY p.afiliacion";

$result = $mysqli->query($sql);

$afiliaciones = [];
$procedimientos_por_afiliacion = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $afiliaciones[] = $row['afiliacion'];  // Guardar las afiliaciones
        $procedimientos_por_afiliacion[] = $row['total_procedimientos'];  // Total de procedimientos por afiliación
    }
} else {
    $afiliaciones = ['No data'];
    $procedimientos_por_afiliacion = [0];
}

// Convertir los arrays a formato JSON para el uso en JavaScript
$afiliaciones_json = json_encode($afiliaciones);
$procedimientos_por_afiliacion_json = json_encode($procedimientos_por_afiliacion);

$sql = "SELECT pr.status, pr.membrete, pr.dieresis, pr.exposicion, pr.hallazgo, pr.operatorio, pr.complicaciones_operatorio, pr.datos_cirugia, 
               pr.procedimientos, pr.lateralidad, pr.tipo_anestesia, pr.diagnosticos, pp.procedimiento_proyectado,
               pr.cirujano_1, pr.instrumentista, pr.cirujano_2, pr.circulante, pr.primer_ayudante, pr.anestesiologo, 
               pr.segundo_ayudante, pr.ayudante_anestesia, pr.tercer_ayudante
        FROM protocolo_data pr
        LEFT JOIN procedimiento_proyectado pp ON pp.form_id = pr.form_id AND pp.hc_number = pr.hc_number
        ORDER BY pr.fecha_inicio DESC, pr.id DESC";

$result = $mysqli->query($sql);

// Inicializar contadores
$incompletos = 0;
$revisados = 0;
$no_revisados = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $invalidValues = ['CENTER', 'undefined'];
        $requiredFields = [$row['membrete'], $row['dieresis'], $row['exposicion'], $row['hallazgo'], $row['operatorio'],
            $row['complicaciones_operatorio'], $row['datos_cirugia'], $row['procedimientos'],
            $row['lateralidad'], $row['tipo_anestesia'], $row['diagnosticos'], $row['procedimiento_proyectado']];
        $staffFields = [$row['cirujano_1'], $row['instrumentista'], $row['cirujano_2'], $row['circulante'],
            $row['primer_ayudante'], $row['anestesiologo'], $row['segundo_ayudante'],
            $row['ayudante_anestesia'], $row['tercer_ayudante']];

        $invalidFields = false;
        $staffCount = 0;

        // Si el estado es 1, es "revisado"
        if ($row['status'] == 1) {
            $revisados++;
        } else {
            // Verificar si algún campo tiene un valor no permitido
            foreach ($requiredFields as $field) {
                if (!empty($field)) {
                    foreach ($invalidValues as $invalidValue) {
                        if (stripos($field, $invalidValue) !== false) {
                            $invalidFields = true;
                            break 2;
                        }
                    }
                }
            }

            // Contar miembros del staff válidos
            if (!empty($row['cirujano_1'])) {
                foreach ($staffFields as $staff) {
                    if (!empty($staff)) {
                        foreach ($invalidValues as $invalidValue) {
                            if (stripos($staff, $invalidValue) !== false) {
                                $invalidFields = true;
                                break 2;
                            }
                        }
                        $staffCount++;
                    }
                }
            } else {
                $invalidFields = true;
            }

            // Determinar si es "no revisado" o "incompleto"
            if (!$invalidFields && $staffCount >= 5) {
                $no_revisados++;
            } else {
                $incompletos++;
            }
        }
    }
}

// Pasar los datos a JavaScript
$incompletos_json = json_encode($incompletos);
$revisados_json = json_encode($revisados);
$no_revisados_json = json_encode($no_revisados);
?>


<!-- Vendor JS -->
<script src="js/vendors.min.js"></script>
<script src="js/pages/chat-popup.js"></script>
<script src="../assets/icons/feather-icons/feather.min.js"></script>

<script src="../assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
<script src="../assets/vendor_components/OwlCarousel2/dist/owl.carousel.js"></script>

<!-- Doclinic App -->
<script src="js/jquery.smartmenus.js"></script>
<script src="js/menus.js"></script>
<script src="js/template.js"></script>
<script>
    var fechas = <?php echo $fechas_json; ?>;
    var procedimientos_dia = <?php echo $procedimientos_dia_json; ?>;  // Usar nombre único
    var membretes = <?php echo $membretes_json; ?>;
    var procedimientos_membrete = <?php echo $procedimientos_membrete_json; ?>;  // Usar nombre único
    var afiliaciones = <?php echo $afiliaciones_json; ?>;
    var procedimientos_por_afiliacion = <?php echo $procedimientos_por_afiliacion_json; ?>;
    // Datos desde PHP
    var incompletos = <?php echo $incompletos_json; ?>;
    var revisados = <?php echo $revisados_json; ?>;
    var no_revisados = <?php echo $no_revisados_json; ?>;</script>
<script src="js/pages/dashboard3.js?v=<?php echo time(); ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const filterButtons = document.querySelectorAll('.btn-filter');
        const cards = document.querySelectorAll('.plantilla-card');
        const countSpan = document.getElementById('plantilla-count');

        function updateCount() {
            const visibles = [...cards].filter(c => c.style.display !== 'none').length;
            countSpan.textContent = `Mostrando ${visibles} plantilla${visibles !== 1 ? 's' : ''}`;
        }

        function filterCards(type) {
            cards.forEach(card => {
                const tipo = card.dataset.tipo.toLowerCase();
                if (type === 'all' || tipo === type) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
            updateCount();
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function () {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const type = this.dataset.filter;
                filterCards(type);
            });
        });

        updateCount(); // Inicial
    });
</script>
</body>
</html>

