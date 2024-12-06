<?php
ini_set('session.save_path', __DIR__ . '/../../sessions');
session_name('mi_sesion');
session_start();
require '../../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente
require '../../library/patients.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está logueado
    header('Location: ../auth_login.html');
    exit();
}
$hc_number = $_GET['hc_number'] ?? null;

// Obtener la información del usuario logueado
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

$patientData = getPatientData($mysqli, $hc_number);
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
                        <h3 class="page-title">Detalles del paciente</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Detalles del paciente</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-xl-4 col-12">
                        <div class="box">
                            <div class="box-body box-profile">
                                <div class="row">
                                    <div class="col-12">
                                        <div>
                                            <p>Fecha de Nacimiento :<span
                                                        class="text-gray ps-10"><?php echo $patientData['fecha_nacimiento']; ?></span>
                                            </p>
                                            <p>Celular :<span
                                                        class="text-gray ps-10"><?php echo $patientData['celular']; ?></span>
                                            </p>
                                            <p>Dirección :<span
                                                        class="text-gray ps-10"><?php echo $patientData['ciudad']; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="pb-15">
                                            <p class="mb-10">Social Profile</p>
                                            <div class="user-social-acount">
                                                <button class="btn btn-circle btn-social-icon btn-facebook"><i
                                                            class="fa fa-facebook"></i></button>
                                                <button class="btn btn-circle btn-social-icon btn-twitter"><i
                                                            class="fa fa-twitter"></i></button>
                                                <button class="btn btn-circle btn-social-icon btn-instagram"><i
                                                            class="fa fa-instagram"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div>
                                            <div class="map-box">
                                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2805244.1745767146!2d-86.32675167439648!3d29.383165774894163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88c1766591562abf%3A0xf72e13d35bc74ed0!2sFlorida%2C+USA!5e0!3m2!1sen!2sin!4v1501665415329"
                                                        width="100%" height="175" frameborder="0" style="border:0"
                                                        allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <?php
                        // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                        // Consulta para obtener los datos de la historia clínica
                        $sql = "SELECT fecha, diagnosticos FROM consulta_data WHERE hc_number = ? ORDER BY fecha DESC";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param('s', $hc_number);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $uniqueDiagnoses = [];

                        // Recorrer los resultados de la consulta y procesar los diagnósticos
                        while ($row = $result->fetch_assoc()) {
                            $diagnosticos = json_decode($row['diagnosticos'], true);
                            $fecha = date('d M Y', strtotime($row['fecha']));

                            foreach ($diagnosticos as $diagnostico) {
                                $idDiagnostico = $diagnostico['idDiagnostico'];

                                // Solo agregamos el diagnóstico si no está ya en la lista (para mantener el más reciente)
                                if (!isset($uniqueDiagnoses[$idDiagnostico])) {
                                    $uniqueDiagnoses[$idDiagnostico] = [
                                        'idDiagnostico' => $idDiagnostico,
                                        'fecha' => $fecha
                                    ];
                                }
                            }
                        }
                        ?>

                        <div class="box">
                            <div class="box-header border-0 pb-0">
                                <h4 class="box-title">Antecedentes Patológicos</h4>
                            </div>
                            <div class="box-body">
                                <div class="widget-timeline-icon">
                                    <ul>
                                        <?php foreach ($uniqueDiagnoses as $diagnosis): ?>
                                            <li>
                                                <div class="icon bg-primary fa fa-heart-o"></div>
                                                <a class="timeline-panel text-muted" href="#">
                                                    <h4 class="mb-2 mt-1"><?php echo htmlspecialchars($diagnosis['idDiagnostico']); ?></h4>
                                                    <p class="fs-15 mb-0 "><?php echo htmlspecialchars($diagnosis['fecha']); ?></p>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                        // Consulta para obtener la lista de médicos que han atendido al paciente, excluyendo valores nulos/vacíos y la palabra 'optometría'
                        $sql = "SELECT doctor, form_id FROM procedimiento_proyectado WHERE hc_number = ? AND doctor IS NOT NULL AND doctor != '' AND doctor NOT LIKE '%optometría%' ORDER BY form_id DESC";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param('s', $hc_number);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $uniqueDoctors = [];

                        // Recorrer los resultados de la consulta y procesar los médicos
                        while ($row = $result->fetch_assoc()) {
                            $doctor = $row['doctor'];

                            // Solo agregamos el doctor si no está ya en la lista (para mantener el más reciente)
                            if (!isset($uniqueDoctors[$doctor])) {
                                $uniqueDoctors[$doctor] = [
                                    'doctor' => $doctor,
                                    'form_id' => $row['form_id']
                                ];
                            }
                        }
                        ?>

                        <div class="box">
                            <div class="box-header border-0 pb-0">
                                <h4 class="box-title">Médicos asignados</h4>
                            </div>
                            <div class="box-body">
                                <?php foreach ($uniqueDoctors as $doctorData): ?>
                                    <div class="d-flex align-items-center mb-15">
                                        <img src="../images/avatar/avatar-10.png"
                                             class="w-100 bg-primary-light rounded10 me-15" alt=""/>
                                        <div>
                                            <h4 class="mb-0">
                                                <?php
                                                $formattedName = 'Md. ' . ucwords(strtolower($doctorData['doctor']));
                                                echo htmlspecialchars($formattedName);
                                                ?></h4>
                                            <p class="text-muted">Oftalmólogo</p>
                                            <div class="d-flex">
                                                <i class="text-warning fa fa-star"></i>
                                                <i class="text-warning fa fa-star"></i>
                                                <i class="text-warning fa fa-star"></i>
                                                <i class="text-warning fa fa-star"></i>
                                                <i class="text-warning fa fa-star-half"></i>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                        // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                        // Consulta para obtener la lista de procedimientos
                        $sql = "SELECT procedimiento, fecha, tipo FROM solicitud_procedimiento WHERE hc_number = ? ORDER BY fecha DESC";
                        $stmt = $mysqli->prepare($sql);
                        $stmt->bind_param('s', $hc_number);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $procedimientos = [];

                        // Recorrer los resultados de la consulta y procesar los procedimientos
                        while ($row = $result->fetch_assoc()) {
                            $procedimiento = [
                                'nombre' => $row['procedimiento'],
                                'fecha' => $row['fecha'],
                                'tipo' => strtolower($row['tipo'])
                            ];
                            $procedimientos[] = $procedimiento;
                        }
                        ?>

                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Solicitudes</h4>
                                <ul class="box-controls pull-right d-md-flex d-none">
                                    <li class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle px-10 " data-bs-toggle="dropdown"
                                                href="#">Crear
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#"><i class="ti-import"></i> Import</a>
                                            <a class="dropdown-item" href="#"><i class="ti-export"></i> Export</a>
                                            <a class="dropdown-item" href="#"><i class="ti-printer"></i> Print</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#"><i class="ti-settings"></i> Settings</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="box-body">
                                <?php foreach ($procedimientos as $procedimientoData): ?>
                                    <?php
                                    // Determinar el color en función del tipo de procedimiento
                                    $bulletColor = 'bg-info'; // Valor predeterminado
                                    if ($procedimientoData['tipo'] === 'cirugia') {
                                        $bulletColor = 'bg-danger';
                                    } elseif ($procedimientoData['tipo'] === 'interconsulta') {
                                        $bulletColor = 'bg-primary';
                                    } else {
                                        $bulletColor = 'bg-warning';
                                    }

                                    // Calcular los días restantes desde la fecha de solicitud
                                    $fechaSolicitud = new DateTime($procedimientoData['fecha']);
                                    $fechaActual = new DateTime();
                                    $interval = $fechaActual->diff($fechaSolicitud);
                                    $diasRespuesta = $interval->days;
                                    ?>
                                    <div class="d-flex align-items-center mb-25">
                                        <span class="bullet bullet-bar <?php echo $bulletColor; ?> align-self-stretch"></span>
                                        <div class="h-20 mx-20 flex-shrink-0">
                                            <input type="checkbox" id="md_checkbox_<?php echo uniqid(); ?>"
                                                   class="filled-in chk-col-<?php echo $procedimientoData['tipo'] === 'cirugia' ? 'danger' : 'primary'; ?>">
                                            <label for="md_checkbox_<?php echo uniqid(); ?>"
                                                   class="h-20 p-10 mb-0"></label>
                                        </div>

                                        <div class="d-flex flex-column flex-grow-1">
                                            <a href="#"
                                               class="text-dark hover-<?php echo $procedimientoData['tipo'] === 'cirugia' ? 'danger' : 'primary'; ?> fw-500 fs-16">
                                                <?php echo htmlspecialchars($procedimientoData['nombre']); ?>
                                            </a>
                                            <span class="text-fade fw-500">Solicitado hace <?php echo $diasRespuesta; ?> Días</span>
                                        </div>
                                        <div class="dropdown">
                                            <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                        class="ti-more-alt"></i></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <h6 class="dropdown-header">Choose Label:</h6>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span class="badge badge-primary-light">Customer</span>
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <span class="badge badge-danger-light">Partner</span>
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <span class="badge badge-success-light">Supplier</span>
                                                </a>
                                                <a class="dropdown-item" href="#">
                                                    <span class="badge badge-info-light">Member</span>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item flexbox" href="#">
                                                    <span>Add New</span>
                                                    <span class="badge badge-pill badge-default">+</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>


                    </div>
                    <div class="col-xl-8 col-12">
                        <div class="d-md-flex align-items-center justify-content-between mb-20">
                            <a href="javascript:void(0);" class="btn btn-primary me-5 mb-md-0 mb-5"><i
                                        class="fa fa-edit"></i> Edit Profile</a>
                            <div class="d-flex">
                                <a href="javascript:void(0);" class="btn btn-outline btn-danger me-5"><i
                                            class="fa fa-times-circle-o"></i> Reject Patient</a>
                                <a href="javascript:void(0);" class="btn btn-success"><i
                                            class="fa fa-check-circle-o"></i> Accept Patient</a>
                            </div>
                        </div>
                        <div class="box">
                            <?php
                            // Determinar la imagen de fondo en función del seguro
                            $insurance = strtolower($patientData['afiliacion']);
                            $backgroundImage = '../../assets/logos_seguros/5.png'; // Imagen predeterminada

                            $generalInsurances = [
                                'contribuyente voluntario', 'conyuge', 'conyuge pensionista', 'seguro campesino', 'seguro campesino jubilado',
                                'seguro general', 'seguro general jubilado', 'seguro general por montepío', 'seguro general tiempo parcial'
                            ];

                            foreach ($generalInsurances as $generalInsurance) {
                                if (strpos($insurance, $generalInsurance) !== false) {
                                    $backgroundImage = '../../assets/logos_seguros/1.png';
                                    break;
                                }
                            }

                            if (strpos($insurance, 'issfa') !== false) {
                                $backgroundImage = '../assets/logos_seguros/2.png';
                            } elseif (strpos($insurance, 'isspol') !== false) {
                                $backgroundImage = '../../assets/logos_seguros/3.png';
                            } elseif (strpos($insurance, 'msp') !== false) {
                                $backgroundImage = '../../assets/logos_seguros/4.png';
                            }

                            // Determinar la imagen del avatar en función del sexo
                            $gender = strtolower($patientData['sexo']);
                            $avatarImage = '../../images/avatar/female.png'; // Imagen predeterminada

                            if (strpos($gender, 'masculino') !== false) {
                                $avatarImage = '../../images/avatar/male.png';
                            }
                            ?>

                            <div class="box-body text-end min-h-150"
                                 style="background-image:url(<?php echo $backgroundImage; ?>); background-repeat: no-repeat; background-position: center; background-size: cover;">
                            </div>
                            <div class="box-body wed-up position-relative">
                                <div class="d-md-flex align-items-center">
                                    <div class=" me-20 text-center text-md-start">
                                        <img src="<?php echo $avatarImage; ?>" style="height: 150px"
                                             class="bg-success-light rounded10"
                                             alt=""/>
                                        <div class="text-center my-10">
                                            <p class="mb-0">Afiliación</p>
                                            <h4><?php echo $patientData['afiliacion']; ?></h4>
                                        </div>
                                    </div>
                                    <div class="mt-40">
                                        <h4 class="fw-600 mb-5"><?php
                                            echo $patientData['fname'] . " " . $patientData['mname'] . " " . $patientData['lname'] . " " . $patientData['lname2'];
                                            ?></h4>
                                        <h5 class="fw-500 mb-5"><?php echo "C. I.: " . $patientData['hc_number']; ?></h5>
                                        <p><i class="fa fa-clock-o"></i> Edad: <?
                                            $ageWithCurrentDate = calculatePatientAge($patientData['fecha_nacimiento']);
                                            echo $ageWithCurrentDate . " años";
                                            ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php
                                // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                                // Consulta para obtener los datos del timeline uniendo las tablas "procedimiento_proyectado" con "consulta_data" o "protocolo_data"
                                $sql = "SELECT pp.procedimiento_proyectado, pp.form_id, pp.hc_number, 
                                            COALESCE(cd.fecha, pr.fecha_inicio) AS fecha, 
                                            COALESCE(cd.examen_fisico, pr.membrete) AS contenido
                                        FROM procedimiento_proyectado pp
                                        LEFT JOIN consulta_data cd ON pp.hc_number = cd.hc_number AND pp.form_id = cd.form_id
                                        LEFT JOIN protocolo_data pr ON pp.hc_number = pr.hc_number AND pp.form_id = pr.form_id
                                        WHERE pp.hc_number = ? AND pp.procedimiento_proyectado NOT LIKE '%optometría%'
                                        ORDER BY fecha ASC";

                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param('s', $hc_number);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Depuración: Verificar el resultado de la consulta
                                if ($result === false) {
                                    echo "Error en la consulta: " . $mysqli->error;
                                    exit;
                                }

                                // Obtener la primera fila para verificar si hay resultados
                                $row = $result->fetch_assoc();
                                if ($row): ?>
                                    <section class="cd-horizontal-timeline">
                                        <div class="timeline">
                                            <div class="events-wrapper">
                                                <div class="events">
                                                    <ol>
                                                        <?php $isFirst = true;
                                                        do { ?>
                                                            <li>
                                                                <a href="#0"
                                                                   data-date="<?php echo date('d/m/Y', strtotime($row['fecha'])); ?>"
                                                                   class="<?php echo $isFirst ? 'selected' : ''; ?>">
                                                                    <?php echo date('d M', strtotime($row['fecha'])); ?>
                                                                </a>
                                                            </li>
                                                            <?php $isFirst = false; ?>
                                                        <?php } while ($row = $result->fetch_assoc()); ?>
                                                    </ol>
                                                    <span class="filling-line" aria-hidden="true"></span>
                                                </div>
                                                <!-- .events -->
                                            </div>
                                            <!-- .events-wrapper -->
                                            <ul class="cd-timeline-navigation">
                                                <li><a href="#0" class="prev inactive">Prev</a></li>
                                                <li><a href="#0" class="next">Next</a></li>
                                            </ul>
                                            <!-- .cd-timeline-navigation -->
                                        </div>
                                        <!-- .timeline -->
                                        <div class="events-content">
                                            <ol>
                                                <?php
                                                // Reiniciamos el puntero del resultado para iterar nuevamente
                                                $result->data_seek(0);
                                                $isFirst = true;
                                                while ($row = $result->fetch_assoc()):
                                                    $procedimiento_parts = explode(' - ', $row['procedimiento_proyectado']);
                                                    $nombre_procedimiento = implode(' - ', array_slice($procedimiento_parts, 2));
                                                    ?>
                                                    <li data-date="<?php echo date('d/m/Y', strtotime($row['fecha'])); ?>"
                                                        class="<?php echo $isFirst ? 'selected' : ''; ?>">
                                                        <h2><?php echo htmlspecialchars($nombre_procedimiento); ?></h2>
                                                        <small><?php echo date('F jS, Y', strtotime($row['fecha'])); ?></small>
                                                        <hr class="my-30">
                                                        <p class="pb-30">
                                                            <?php echo nl2br(htmlspecialchars($row['contenido'])); ?>
                                                        </p>
                                                    </li>
                                                    <?php $isFirst = false; ?>
                                                <?php endwhile; ?>
                                            </ol>
                                        </div>
                                        <!-- .events-content -->
                                    </section>
                                <?php else: ?>
                                    <p>No hay datos disponibles para mostrar en el timeline.</p>
                                <?php endif; ?>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-12">
                                <?php
                                // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                                // Consulta para obtener los protocolos generados
                                $sql = "SELECT form_id, hc_number, membrete, fecha_inicio FROM protocolo_data WHERE hc_number = ?";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param('s', $hc_number);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $protocolos = [];

                                // Recorrer los resultados de la consulta y procesar los protocolos
                                while ($row = $result->fetch_assoc()) {
                                    $protocolos[] = [
                                        'form_id' => $row['form_id'],
                                        'hc_number' => $row['hc_number'],
                                        'membrete' => $row['membrete'],
                                        'fecha_inicio' => $row['fecha_inicio']
                                    ];
                                }

                                // Consulta para obtener las solicitudes quirúrgicas
                                $sql = "SELECT form_id, hc_number, procedimiento, created_at FROM solicitud_procedimiento WHERE hc_number = ?";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param('s', $hc_number);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $solicitudes = [];

                                // Recorrer los resultados de la consulta y procesar las solicitudes
                                while ($row = $result->fetch_assoc()) {
                                    $solicitudes[] = [
                                        'form_id' => $row['form_id'],
                                        'hc_number' => $row['hc_number'],
                                        'procedimiento' => $row['procedimiento'],
                                        'created_at' => $row['created_at']
                                    ];
                                }

                                // Combinar y ordenar los protocolos y solicitudes cronológicamente
                                $documentos = array_merge($protocolos, $solicitudes);

                                usort($documentos, function ($a, $b) {
                                    $fechaA = isset($a['fecha_inicio']) ? $a['fecha_inicio'] : $a['created_at'];
                                    $fechaB = isset($b['fecha_inicio']) ? $b['fecha_inicio'] : $b['created_at'];
                                    return strtotime($fechaB) - strtotime($fechaA);
                                });
                                ?>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        filterDocuments('last_3_months'); // Filtrar por defecto los últimos 3 meses
                                    });

                                    function filterDocuments(filter) {
                                        const items = document.querySelectorAll('.media-list .media');
                                        const now = new Date();
                                        items.forEach(item => {
                                            const dateElement = item.querySelector('.text-fade');
                                            const dateText = dateElement ? dateElement.textContent.trim() : '';
                                            const itemDate = new Date(dateText);
                                            let showItem = true;

                                            switch (filter) {
                                                case 'ultimo_mes':
                                                    const lastMonth = new Date();
                                                    lastMonth.setMonth(now.getMonth() - 1);
                                                    showItem = itemDate >= lastMonth;
                                                    break;
                                                case 'ultimos_3_meses':
                                                    const last3Months = new Date();
                                                    last3Months.setMonth(now.getMonth() - 3);
                                                    showItem = itemDate >= last3Months;
                                                    break;
                                                case 'ultimos_6_meses':
                                                    const last6Months = new Date();
                                                    last6Months.setMonth(now.getMonth() - 6);
                                                    showItem = itemDate >= last6Months;
                                                    break;
                                                default:
                                                    showItem = true;
                                            }

                                            item.style.display = showItem ? 'flex' : 'none';
                                        });
                                    }
                                </script>

                                <div class="box">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">Descargar Archivos</h4>
                                        <div class="dropdown pull-right">
                                            <h6 class="dropdown-toggle mb-0" data-bs-toggle="dropdown">Filtro</h6>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#"
                                                   onclick="filterDocuments('todos'); return false;">Todos</a>
                                                <a class="dropdown-item" href="#"
                                                   onclick="filterDocuments('ultimo_mes'); return false;">Último Mes</a>
                                                <a class="dropdown-item" href="#"
                                                   onclick="filterDocuments('ultimos_3_meses'); return false;">Últimos 3
                                                    Meses</a>
                                                <a class="dropdown-item" href="#"
                                                   onclick="filterDocuments('ultimos_6_meses'); return false;">Últimos 6
                                                    Meses</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="media-list media-list-divided">
                                            <?php foreach ($documentos as $documento): ?>
                                                <div class="media media-single px-0">
                                                    <div class="ms-0 me-15 bg-<?php echo isset($documento['membrete']) ? 'success' : 'primary'; ?>-light h-50 w-50 l-h-50 rounded text-center d-flex align-items-center justify-content-center">
                                                        <span class="fs-24 text-<?php echo isset($documento['membrete']) ? 'success' : 'primary'; ?>"><i
                                                                    class="fa fa-file-<?php echo isset($documento['membrete']) ? 'pdf' : 'text'; ?>-o"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-column flex-grow-1">
                                                        <span class="title fw-500 fs-16 text-truncate"
                                                              style="max-width: 200px;"><?php echo htmlspecialchars(isset($documento['membrete']) ? $documento['membrete'] : $documento['procedimiento']); ?></span>
                                                        <span class="text-fade fw-500 fs-12"><?php echo date('d M Y', strtotime(isset($documento['fecha_inicio']) ? $documento['fecha_inicio'] : $documento['created_at'])); ?></span>
                                                    </div>
                                                    <a class="fs-18 text-gray hover-info"
                                                       href="<?php echo isset($documento['membrete']) ? '../../generate_pdf.php?form_id=' . $documento['form_id'] . '&hc_number=' . $documento['hc_number'] : '../reports/solicitud_quirurgica/solicitud_qx_pdf.php?hc_number=' . $documento['hc_number'] . '&form_id=' . $documento['form_id']; ?>"
                                                       target="_blank">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <?php
                                // Suponiendo que ya tienes la conexión a la base de datos establecida en $mysqli

                                // Consulta para obtener los tipos de procedimientos proyectados
                                $sql = "SELECT procedimiento_proyectado FROM procedimiento_proyectado WHERE hc_number = ?";
                                $stmt = $mysqli->prepare($sql);
                                $stmt->bind_param('s', $hc_number);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                $procedimientos = [];

                                // Recorrer los resultados de la consulta y procesar los procedimientos
                                while ($row = $result->fetch_assoc()) {
                                    // Extraer el nombre del procedimiento (ej. CONSULTA OFTALMOLOGICA NUEVO PACIENTE)
                                    $procedimiento_parts = explode(' - ', $row['procedimiento_proyectado']);
                                    if (isset($procedimiento_parts[0])) {
                                        $categoria = strtoupper($procedimiento_parts[0]);
                                        if (in_array($categoria, ['CIRUGIAS', 'PNI', 'IMAGENES'])) {
                                            $nombre = $categoria; // Agrupar por categoría
                                        } else {
                                            $nombre = isset($procedimiento_parts[2]) ? $procedimiento_parts[2] : $categoria;
                                        }

                                        if (!isset($procedimientos[$nombre])) {
                                            $procedimientos[$nombre] = 0;
                                        }
                                        $procedimientos[$nombre]++;
                                    }
                                }

                                // Calcular el porcentaje de cada tipo de procedimiento
                                $total_procedimientos = array_sum($procedimientos);
                                $porcentajes = [];

                                foreach ($procedimientos as $nombre => $cantidad) {
                                    $porcentajes[$nombre] = ($cantidad / $total_procedimientos) * 100;
                                }
                                ?>

                                <div class="box">
                                    <div class="box-header no-border">
                                        <h4 class="box-title">Estadísticas de Citas</h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="chart123"></div>
                                    </div>
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

    <?php include '../footer.php'; ?>

</div>
<!-- ./wrapper -->


<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>
<script src="../js/pages/chat-popup.js"></script>
<script src="../../assets/icons/feather-icons/feather.min.js"></script>
<script src="../../assets/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>
<script src="../../assets/vendor_components/horizontal-timeline/js/horizontal-timeline.js"></script>


<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
<script>
    $(function () {
        'use strict';

        var options = {
            series: [
                <?php echo implode(', ', array_values($porcentajes)); ?>
            ],
            chart: {
                type: 'donut',
            },
            colors: ['#3246D3', '#00D0FF', '#ee3158', '#ffa800', '#05825f'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '45%',
                    }
                }
            },
            labels: [
                <?php echo implode(', ', array_map(function ($nombre) {
                return "'" . $nombre . "'";
            }, array_keys($porcentajes))); ?>
            ],
            responsive: [{
                breakpoint: 1600,
                options: {
                    chart: {
                        width: 330,
                    }
                }
            }, {
                breakpoint: 500,
                options: {
                    chart: {
                        width: 280,
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart123"), options);
        chart.render();
    });
</script>
</body>
</html>
