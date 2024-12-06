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
            CONCAT(pd.fname, ' ', pd.mname, ' ', pd.lname, ' ', pd.lname2) AS full_name, 
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
                                                    echo "<a class='dropdown-item' href='solicitud_quirurgica/solicitud_qx_pdf.php?hc_number=" . urlencode($row['hc_number']) . "' target='_blank'>Generar PDF</a>";
                                                    echo "<a class='dropdown-item' href='#'>View Details</a>";
                                                    echo "<a class='dropdown-item' href='#'>Edit</a>";
                                                    echo "<a class='dropdown-item' href='#'>Delete</a>";
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
                                <button id="exportExcel" class="btn btn-primary mt-3">Exportar a Excel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->

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

<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>
<script src="../js/pages/chat-popup.js"></script>
<script src="../../assets/icons/feather-icons/feather.min.js"></script>
<script src="../../assets/vendor_components/datatable/datatables.min.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.0.0/js/dataTables.searchPanes.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.0.0/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

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
        if (!$.fn.DataTable.isDataTable('#surgeryTable')) {
            var table = $('#surgeryTable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "order": [[4, "desc"]], // Ordenar siempre por la columna de fecha de solicitud, de más reciente a más antiguo
                "info": true,
                "columnDefs": [
                    {
                        "targets": 4, // Índice de columna para la fecha de solicitud (ajustar si es necesario)
                        "type": "dd-mm-yyyy",
                        "searchable": true // Permitir búsqueda en la columna de fecha de solicitud
                    },
                    {
                        "targets": 9, // Índice de columna para "Días Restantes" (ajustar si es necesario)
                        "type": "dias-restantes" // Tipo de ordenación personalizada
                    }
                ],
                "searchPanes": {
                    "columns": [3] // Índice de la columna "Afiliación" que tendrá filtro
                },
                "dom": 'Plfrtip', // Esto agrega los filtros por columna arriba de la tabla
                "initComplete": function () {
                    // Agregar selector de rango de fechas para la columna "Fecha de Solicitud"
                    $("#surgeryTable thead").append('<tr><th></th><th></th><th></th><th></th><th><input type="text" id="dateRangePicker" class="form-control" placeholder="Seleccione rango de fechas"></th><th></th><th></th><th></th><th></th><th></th></tr>');

                    $('#dateRangePicker').daterangepicker({
                        autoUpdateInput: false,
                        locale: {
                            cancelLabel: 'Clear',
                            format: 'DD/MM/YYYY'
                        }
                    });

                    $('#dateRangePicker').on('apply.daterangepicker', function (ev, picker) {
                        var startDate = picker.startDate.format('YYYY-MM-DD');
                        var endDate = picker.endDate.format('YYYY-MM-DD');
                        $.fn.dataTable.ext.search.push(
                            function (settings, data, dataIndex) {
                                var min = startDate;
                                var max = endDate;
                                var date = moment(data[4], 'DD/MM/YYYY').format('YYYY-MM-DD');
                                if ((min === null && max === null) ||
                                    (min === null && date <= max) ||
                                    (min <= date && max === null) ||
                                    (min <= date && date <= max)) {
                                    return true;
                                }
                                return false;
                            }
                        );
                        $('#surgeryTable').DataTable().draw();
                    });

                    $('#dateRangePicker').on('cancel.daterangepicker', function () {
                        $(this).val('');
                        $.fn.dataTable.ext.search.pop();
                        $('#surgeryTable').DataTable().draw();
                    });
                }
            });
        }

        // Exportar a Excel solo con las columnas específicas
        $('#exportExcel').on('click', function () {
            var wb = XLSX.utils.book_new();
            var ws_data = [];
            // Agregar una fila combinada en blanco y otra con el título "SOLICITUD DE LA DERIVACIÓN"
            ws_data.push(["", "", "", "", "", "", ""]);
            ws_data.push(["", "", "", "SOLICITUD DE LA DERIVACIÓN", "", "", "AUTORIZACIÓN DE LA DERIVACIÓN", "UNIDAD QUE RECIBE LA DERIVACIÓN"]);

            // Obtener encabezados específicos
            ws_data.push(["COORDINACION PROVINCIAL", "PROVINCIA", "CANTON", "NOMBRE DE LA UNIDAD MEDICA", "FECHA DE SOLICITUD DE DERIVACIÓN (F 053) dd/mm/aaaa", "APELLIDOS Y NOMBRES DEL MEDICO QUE SOLICITA LA DERIVACION (F 053)", "FECHA DE LA AUTORIZACION DE LA DERIVACION dd/mm/aaaa", "APELLIDOS Y NOMBRES COMPLETOS DEL RESPONSABLE QUE AUTORIZA LA DERIVACIÓN", "UNIDAD A LA QUE PERTENECE QUIEN AUTORIZA LA DERIVACIÓN", "NOMBRE DE LA UNIDAD MEDICA QUE RECIBE LA DERIVACIÓN", "RUC DE LA UNIDAD MEDICA QUE RECIBE LA DERIVACIÓN", "CÓDIGO DE VALIDACIÓN DE LA DERIVACIÓN (CÓDIGO PARA RPC)", "NÚMERO DE CÉDULA DEL AFILIADO"]);

            // Obtener todos los datos de la tabla (incluyendo todas las páginas)
            table.rows({search: 'applied'}).every(function () {
                var data = this.data();
                var czg = "Coordinación Zonal Guayas";
                var provincia = "Guayas";
                var canton = "Guayaquil";
                var unidad = "CLINICA INTERNACIONAL DE LA VISION ECUADOR CIVE";
                var fecha = data[4];
                var doctor = data[6];
                var fecha_derivacion = data[4];
                var nombre = data[2];
                var coordinacion = "COORDINACION PROVINCIAL DE PRESTACIONES DE SALUD IESS GUAYAS";
                var ruc = "0992807342001";
                var ci = data[1];
                var procedimiento = data[5];

                ws_data.push([czg, provincia, canton, unidad, fecha, doctor, fecha_derivacion, nombre, coordinacion, unidad, ruc, procedimiento, ci]);
            });

            var ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            XLSX.writeFile(wb, 'SurgeryDataFiltered.xlsx');
        });
    });
</script>


<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
</body>
</html>
