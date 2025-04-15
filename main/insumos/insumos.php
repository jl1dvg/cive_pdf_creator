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

    <title>Asistente CIVE - Editor de Protocolos</title>

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
                        <h3 class="page-title">Editable Tables</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item" aria-current="page">Tables</li>
                                    <li class="breadcrumb-item active" aria-current="page">Editable Tables</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">

                <div class="row">

                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Editable with <strong>Datatable</strong></h4>
                                <h6 class="subtitle">Just click on word which you want to change and enter</h6>
                            </div>

                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="insumosEditable" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Categoría</th>
                                            <th>Código ISSPOL</th>
                                            <th>Código ISSFA</th>
                                            <th>Código IESS</th>
                                            <th>Código MSP</th>
                                            <th>Nombre</th>
                                            <th>Producto ISSFA</th>
                                            <th>Precio Base</th>
                                            <th>IVA 15%</th>
                                            <th>Gestión 10%</th>
                                            <th>Precio Total</th>
                                            <th>Precio ISSPOL</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $query = "SELECT * FROM insumos ORDER BY categoria, nombre";
                                        $result = $mysqli->query($query);
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr data-id="' . $row['id'] . '">';
                                            foreach ([
                                                'categoria', 'codigo_isspol', 'codigo_issfa', 'codigo_iess', 'codigo_msp',
                                                'nombre', 'producto_issfa', 'precio_base', 'iva_15', 'gestion_10', 'precio_total', 'precio_isspol'
                                            ] as $campo) {
                                                echo '<td contenteditable="true" class="editable" data-field="' . $campo . '">' . htmlspecialchars($row[$campo]) . '</td>';
                                            }
                                            echo '<td><button class="btn btn-sm btn-success save-btn">Guardar</button></td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">

                </div>

            </section>
            <!-- /.content -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <?php include '../footer.php'; ?>

    <!-- Botón para agregar nuevo insumo -->
    <div class="text-end pe-30 pb-20">
        <button id="agregarInsumoBtn" class="btn btn-primary">Agregar nuevo insumo</button>
    </div>
</div>
<!-- ./wrapper -->

<!-- Page Content overlay -->


<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>
<script src="../js/pages/chat-popup.js"></script>
<script src="../../assets/icons/feather-icons/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../assets/vendor_components/datatable/datatables.min.js"></script>
<script src="../../assets/vendor_components/tiny-editable/mindmup-editabletable.js"></script>
<script src="../../assets/vendor_components/tiny-editable/numeric-input-example.js"></script>


<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
<script src="../js/edit_insumos.js"></script>


</body>
</html>