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

// Obtener los datos agrupados por categoría
$sql = "SELECT categoria, membrete, cirugia, imagen_link, id FROM procedimientos ORDER BY categoria, cirugia";
$result = $mysqli->query($sql);

if (!$result) {
    die("Error al ejecutar la consulta: " . $mysqli->error);
}

// Organizar los datos en un array por categoría
$procedimientosPorCategoria = [];
while ($row = $result->fetch_assoc()) {
    $procedimientosPorCategoria[$row['categoria']][] = $row;
}
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
                        <h3 class="page-title">Editores</h3>
                        <div class="d-inline-block align-items-center">
                            <nav>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Editor de Protocolos</li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <?php foreach ($procedimientosPorCategoria as $categoria => $procedimientos): ?>
                        <div class="col-12 col-xl-4">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h4 class="box-title text-primary"><?= htmlspecialchars($categoria) ?></h4>
                                </div>
                                <div class="box-body">
                                    <?php foreach ($procedimientos as $procedimiento): ?>
                                        <div class="d-flex align-items-center mb-30">
                                            <div class="me-15">
                                                <img src="<?= htmlspecialchars($procedimiento['imagen_link']) ?>"
                                                     class="avatar avatar-lg rounded10 bg-primary-light" alt=""/>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1 fw-500">
                                                <a href="#"
                                                   class="text-dark hover-primary mb-1 fs-16"><?= htmlspecialchars($procedimiento['membrete']) ?></a>
                                                <span class="text-fade"><?= htmlspecialchars($procedimiento['cirugia']) ?></span>
                                            </div>
                                            <div class="dropdown">
                                                <a class="px-10 pt-5" href="#" data-bs-toggle="dropdown"><i
                                                            class="ti-more-alt"></i></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item"
                                                       href="protocolos_editors_templates.php?id=<?= urlencode($procedimiento['id']) ?>">Editar</a>
                                                    <a class="dropdown-item"
                                                       href="eliminar_protocolo.php?id=<?= urlencode($procedimiento['id']) ?>">Eliminar</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <!-- /.content -->

        </div>
    </div>
    <!-- /.content-wrapper -->
</div>

<?php include '../footer.php'; ?>


<!-- Vendor JS -->
<script src="../js/vendors.min.js"></script>
<script src="../js/pages/chat-popup.js"></script>
<script src="../../assets/icons/feather-icons/feather.min.js"></script>


<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
<script src="../js/pages/list.js"></script>

</body>
</html>
