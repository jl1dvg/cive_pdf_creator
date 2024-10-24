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

    <title>Asistente CIVE - Editar Protocolo</title>

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
            <!-- Content Header (Page header)
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <h3 class="page-title">Form Wizard</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">Forms</li>
                                    <li class="breadcrumb-item active" aria-current="page">Form Wizard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div> -->

            <!-- Main content -->
            <section class="content">

                <!-- vertical wizard -->
                <?php
                // Obtener los parámetros enviados a través de GET
                $form_id = $_GET['form_id'] ?? null;
                $hc_number = $_GET['hc_number'] ?? null;

                if ($form_id && $hc_number) {
                    // Consulta para obtener los datos del paciente y el procedimiento
                    $sql = "SELECT p.hc_number, p.fname, p.mname, p.lname, p.lname2, p.fecha_nacimiento, p.afiliacion, p.sexo, p.ciudad, 
                    pr.form_id, pr.fecha_inicio, pr.hora_inicio, pr.fecha_fin, pr.hora_fin, pr.cirujano_1, pr.instrumentista, 
                    pr.cirujano_2, pr.circulante, pr.primer_ayudante, pr.anestesiologo, pr.segundo_ayudante, 
                    pr.ayudante_anestesia, pr.tercer_ayudante, pr.membrete, pr.dieresis, pr.exposicion, pr.hallazgo, 
                    pr.operatorio, pr.complicaciones_operatorio, pr.datos_cirugia, pr.procedimientos, pr.lateralidad, 
                    pr.tipo_anestesia, pr.diagnosticos, pp.procedimiento_proyectado
                    FROM patient_data p 
                    INNER JOIN protocolo_data pr ON p.hc_number = pr.hc_number
                    LEFT JOIN procedimiento_proyectado pp ON pp.form_id = pr.form_id AND pp.hc_number = pr.hc_number
                    WHERE pr.form_id = ? AND p.hc_number = ?";
                    $stmt = $mysqli->prepare($sql);
                    $stmt->bind_param('ss', $form_id, $hc_number);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $data = $result->fetch_assoc();
                    } else {
                        echo "No se encontró información para el form_id y hc_number proporcionados.";
                        exit;
                    }
                }
                ?>

                <!-- Formulario de modificación de información -->
                <div class="box">
                    <div class="box-header with-border">
                        <h4 class="box-title">Modificar Información del Procedimiento</h4>
                    </div>
                    <div class="box-body wizard-content">
                        <form action="actualizar_procedimiento.php" method="POST"
                              class="tab-wizard vertical wizard-circle">
                            <!-- Enviar form_id y hc_number ocultos para saber qué registro actualizar -->
                            <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                            <input type="hidden" name="hc_number" value="<?php echo $hc_number; ?>">

                            <!-- Sección 1: Datos del Paciente -->
                            <h6>Datos del Paciente</h6>
                            <section>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="firstName1" class="form-label">Nombre :</label>
                                            <input type="text" class="form-control" id="firstName1" name="fname"
                                                   value="<?php echo htmlspecialchars($data['fname']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="middleName2" class="form-label">Segundo Nombre:</label>
                                            <input type="text" class="form-control" id="middleName2" name="mname"
                                                   value="<?php echo htmlspecialchars($data['mname']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName1" class="form-label">Primer Apellido:</label>
                                            <input type="text" class="form-control" id="lastName1" name="lname"
                                                   value="<?php echo htmlspecialchars($data['lname']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lastName2" class="form-label">Segundo Apellido:</label>
                                            <input type="text" class="form-control" id="lastName2" name="lname2"
                                                   value="<?php echo htmlspecialchars($data['lname2']); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="birthDate" class="form-label">Fecha de Nacimiento :</label>
                                            <input type="date" class="form-control" id="birthDate"
                                                   name="fecha_nacimiento"
                                                   value="<?php echo htmlspecialchars($data['fecha_nacimiento']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="afiliacion" class="form-label">Afiliación :</label>
                                            <input type="text" class="form-control" id="afiliacion" name="afiliacion"
                                                   value="<?php echo htmlspecialchars($data['afiliacion']); ?>"
                                                   readonly>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Sección 2: Procedimientos, Diagnósticos y Lateralidad -->
                            <h6>Procedimientos, Diagnósticos y Lateralidad</h6>
                            <section>
                                <!-- Procedimientos -->
                                <div class="form-group">
                                    <label for="procedimientos" class="form-label">Procedimientos :</label>
                                    <?php
                                    $procedimientosArray = json_decode($data['procedimientos'], true); // Decodificar el JSON

                                    // Si hay procedimientos, los mostramos en inputs separados
                                    if (!empty($procedimientosArray)) {
                                        foreach ($procedimientosArray as $index => $proc) {
                                            $codigo = isset($proc['procInterno']) ? $proc['procInterno'] : '';  // Código completo del procedimiento
                                            echo '<div class="row mb-2">';
                                            echo '<div class="col-md-12">';
                                            echo '<input type="text" class="form-control" name="procedimientos[' . $index . '][procInterno]" value="' . htmlspecialchars($codigo) . '" />';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<input type="text" class="form-control" name="procedimientos[0][procInterno]" placeholder="Agregar Procedimiento" />';
                                    }
                                    ?>
                                </div>

                                <!-- Diagnósticos -->
                                <div class="form-group">
                                    <label for="diagnosticos" class="form-label">Diagnósticos :</label>
                                    <?php
                                    $diagnosticosArray = json_decode($data['diagnosticos'], true); // Decodificar el JSON

                                    // Si hay diagnósticos, los mostramos en inputs separados
                                    if (!empty($diagnosticosArray)) {
                                        foreach ($diagnosticosArray as $index => $diag) {
                                            $ojo = isset($diag['ojo']) ? $diag['ojo'] : '';
                                            $evidencia = isset($diag['evidencia']) ? $diag['evidencia'] : '';
                                            $idDiagnostico = isset($diag['idDiagnostico']) ? $diag['idDiagnostico'] : '';
                                            $observaciones = isset($diag['observaciones']) ? $diag['observaciones'] : '';

                                            echo '<div class="row mb-2">';
                                            echo '<div class="col-md-2">';
                                            echo '<input type="text" class="form-control" name="diagnosticos[' . $index . '][ojo]" value="' . htmlspecialchars($ojo) . '" placeholder="Ojo" />';
                                            echo '</div>';
                                            echo '<div class="col-md-2">';
                                            echo '<input type="text" class="form-control" name="diagnosticos[' . $index . '][evidencia]" value="' . htmlspecialchars($evidencia) . '" placeholder="Evidencia" />';
                                            echo '</div>';
                                            echo '<div class="col-md-6">';
                                            echo '<input type="text" class="form-control" name="diagnosticos[' . $index . '][idDiagnostico]" value="' . htmlspecialchars($idDiagnostico) . '" placeholder="Código CIE-10" />';
                                            echo '</div>';
                                            echo '<div class="col-md-2">';
                                            echo '<input type="text" class="form-control" name="diagnosticos[' . $index . '][observaciones]" value="' . htmlspecialchars($observaciones) . '" placeholder="Observaciones" />';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<input type="text" class="form-control" name="diagnosticos[0][idDiagnostico]" placeholder="Agregar Diagnóstico" />';
                                    }
                                    ?>
                                </div>

                                <!-- Lateralidad -->
                                <div class="form-group">
                                    <label for="lateralidad" class="form-label">Lateralidad :</label>
                                    <input type="text" class="form-control" id="lateralidad" name="lateralidad"
                                           value="<?php echo htmlspecialchars($data['lateralidad']); ?>">
                                </div>
                            </section>

                            <!-- Sección 3: Staff Quirúrgico -->
                            <h6>Staff Quirúrgico</h6>
                            <section>
                                <div class="row">
                                    <!-- Cirujano Principal -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mainSurgeon" class="form-label">Cirujano Principal :</label>
                                            <input type="text" class="form-control" id="mainSurgeon" name="cirujano_1"
                                                   value="<?php echo htmlspecialchars($data['cirujano_1']); ?>">
                                        </div>
                                    </div>
                                    <!-- Cirujano Asistente -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="assistantSurgeon" class="form-label">Cirujano Asistente
                                                :</label>
                                            <input type="text" class="form-control" id="assistantSurgeon"
                                                   name="cirujano_2"
                                                   value="<?php echo htmlspecialchars($data['cirujano_2']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Primer Ayudante -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primerAyudante" class="form-label">Primer Ayudante :</label>
                                            <input type="text" class="form-control" id="primerAyudante"
                                                   name="primer_ayudante"
                                                   value="<?php echo htmlspecialchars($data['primer_ayudante']); ?>">
                                        </div>
                                    </div>
                                    <!-- Segundo Ayudante -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="segundoAyudante" class="form-label">Segundo Ayudante :</label>
                                            <input type="text" class="form-control" id="segundoAyudante"
                                                   name="segundo_ayudante"
                                                   value="<?php echo htmlspecialchars($data['segundo_ayudante']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Tercer Ayudante -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tercerAyudante" class="form-label">Tercer Ayudante :</label>
                                            <input type="text" class="form-control" id="tercerAyudante"
                                                   name="tercer_ayudante"
                                                   value="<?php echo htmlspecialchars($data['tercer_ayudante']); ?>">
                                        </div>
                                    </div>
                                    <!-- Ayudante de Anestesia -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ayudanteAnestesia" class="form-label">Ayudante de Anestesia
                                                :</label>
                                            <input type="text" class="form-control" id="ayudanteAnestesia"
                                                   name="ayudante_anestesia"
                                                   value="<?php echo htmlspecialchars($data['ayudante_anestesia']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Anestesiólogo -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="anesthesiologist" class="form-label">Anestesiólogo :</label>
                                            <input type="text" class="form-control" id="anesthesiologist"
                                                   name="anestesiologo"
                                                   value="<?php echo htmlspecialchars($data['anestesiologo']); ?>">
                                        </div>
                                    </div>
                                    <!-- Instrumentista -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="instrumentista" class="form-label">Instrumentista :</label>
                                            <input type="text" class="form-control" id="instrumentista"
                                                   name="instrumentista"
                                                   value="<?php echo htmlspecialchars($data['instrumentista']); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Enfermera Circulante -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="circulante" class="form-label">Enfermera Circulante :</label>
                                            <input type="text" class="form-control" id="circulante" name="circulante"
                                                   value="<?php echo htmlspecialchars($data['circulante']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Sección 4: Fechas, Horas y Tipo de Anestesia -->
                            <h6>Fechas, Horas y Tipo de Anestesia</h6>
                            <section>
                                <!-- Fecha de Inicio -->
                                <div class="form-group">
                                    <label for="fecha_inicio" class="form-label">Fecha de Inicio :</label>
                                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                                           value="<?php echo htmlspecialchars($data['fecha_inicio']); ?>">
                                </div>

                                <!-- Hora de Inicio -->
                                <div class="form-group">
                                    <label for="hora_inicio" class="form-label">Hora de Inicio :</label>
                                    <input type="time" class="form-control" id="hora_inicio" name="hora_inicio"
                                           value="<?php echo htmlspecialchars($data['hora_inicio']); ?>">
                                </div>

                                <!-- Fecha de Fin -->
                                <div class="form-group">
                                    <label for="fecha_fin" class="form-label">Fecha de Fin :</label>
                                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                                           value="<?php echo htmlspecialchars($data['fecha_fin']); ?>">
                                </div>

                                <!-- Hora de Fin -->
                                <div class="form-group">
                                    <label for="hora_fin" class="form-label">Hora de Fin :</label>
                                    <input type="time" class="form-control" id="hora_fin" name="hora_fin"
                                           value="<?php echo htmlspecialchars($data['hora_fin']); ?>">
                                </div>

                                <!-- Tipo de Anestesia -->
                                <div class="form-group">
                                    <label for="tipo_anestesia" class="form-label">Tipo de Anestesia :</label>
                                    <input type="text" class="form-control" id="tipo_anestesia" name="tipo_anestesia"
                                           value="<?php echo htmlspecialchars($data['tipo_anestesia']); ?>">
                                </div>
                            </section>

                            <!-- Sección 5: Procedimiento -->
                            <h6>Procedimiento</h6>
                            <section>
                                <!-- Procedimiento Proyectado -->
                                <div class="form-group">
                                    <label for="procedimiento_proyectado" class="form-label">Procedimiento Proyectado
                                        :</label>
                                    <textarea name="procedimiento_proyectado" id="procedimiento_proyectado" rows="3"
                                              class="form-control"
                                              readonly><?php echo htmlspecialchars($data['procedimiento_proyectado']); ?></textarea>
                                </div>

                                <!-- Procedimiento Realizado (Membrete) -->
                                <div class="form-group">
                                    <label for="membrete" class="form-label">Procedimiento Realizado (Cirugía Realizada)
                                        :</label>
                                    <textarea name="membrete" id="membrete" rows="4"
                                              class="form-control"><?php echo htmlspecialchars($data['membrete']); ?></textarea>
                                </div>

                                <!-- Dieresis -->
                                <div class="form-group">
                                    <label for="dieresis" class="form-label">Dieresis :</label>
                                    <textarea name="dieresis" id="dieresis" rows="2"
                                              class="form-control"><?php echo htmlspecialchars($data['dieresis']); ?></textarea>
                                </div>

                                <!-- Exposición -->
                                <div class="form-group">
                                    <label for="exposicion" class="form-label">Exposición :</label>
                                    <textarea name="exposicion" id="exposicion" rows="2"
                                              class="form-control"><?php echo htmlspecialchars($data['exposicion']); ?></textarea>
                                </div>

                                <!-- Hallazgo -->
                                <div class="form-group">
                                    <label for="hallazgo" class="form-label">Hallazgo :</label>
                                    <textarea name="hallazgo" id="hallazgo" rows="3"
                                              class="form-control"><?php echo htmlspecialchars($data['hallazgo']); ?></textarea>
                                </div>

                                <!-- Descripción Operatoria -->
                                <div class="form-group">
                                    <label for="operatorio" class="form-label">Descripción Operatoria :</label>
                                    <textarea name="operatorio" id="operatorio" rows="5"
                                              class="form-control"><?php echo htmlspecialchars($data['operatorio']); ?></textarea>
                                </div>

                                <!-- Complicaciones Operatorias -->
                                <div class="form-group">
                                    <label for="complicaciones_operatorio" class="form-label">Complicaciones Operatorias
                                        :</label>
                                    <textarea name="complicaciones_operatorio" id="complicaciones_operatorio" rows="3"
                                              class="form-control"><?php echo htmlspecialchars($data['complicaciones_operatorio']); ?></textarea>
                                </div>

                                <!-- Detalles de la Cirugía -->
                                <div class="form-group">
                                    <label for="datos_cirugia" class="form-label">Detalles de la Cirugía :</label>
                                    <textarea name="datos_cirugia" id="datos_cirugia" rows="5"
                                              class="form-control"><?php echo htmlspecialchars($data['datos_cirugia']); ?></textarea>
                                </div>
                            </section>
                        </form>
                    </div>
                </div>                <!-- /.box -->

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
                            <img src="../images/avatar/1.jpg" alt="...">
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
                            <img src="../images/avatar/2.jpg" alt="...">
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
                            <img src="../images/avatar/3.jpg" alt="...">
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
                            <img src="../images/avatar/4.jpg" alt="...">
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
                            <img src="../images/avatar/1.jpg" alt="...">
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
                            <img src="../images/avatar/2.jpg" alt="...">
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
                            <img src="../images/avatar/3.jpg" alt="...">
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
                            <img src="../images/avatar/4.jpg" alt="...">
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
                                <img src="../images/avatar/2.jpg" class="avatar avatar-lg">
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
                                <img src="../images/avatar/3.jpg" class="avatar avatar-lg">
                            </span>
                    </div>
                    <div class="cm-msg-text">
                        My name is Anne Clarc.
                    </div>
                </div>
                <div class="chat-msg user">
                    <div class="d-flex align-items-center">
                            <span class="msg-avatar">
                                <img src="../images/avatar/2.jpg" class="avatar avatar-lg">
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


<!-- Vendor JS -->
<script src="js/vendors.min.js"></script>
<script src="js/pages/chat-popup.js"></script>
<script src="../assets/icons/feather-icons/feather.min.js"></script>
<script src="../assets/vendor_components/jquery-steps-master/build/jquery.steps.js"></script>
<script src="../assets/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.min.js"></script>
<script src="../assets/vendor_components/sweetalert/sweetalert.min.js"></script>

<!-- Doclinic App -->
<script src="js/jquery.smartmenus.js"></script>
<script src="js/menus.js"></script>
<script src="js/template.js"></script>

<script src="js/pages/steps.js?v=<?php echo time(); ?>"></script>


</body>
</html>

