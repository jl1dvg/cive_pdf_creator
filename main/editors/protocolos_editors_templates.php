<?php
ini_set('session.save_path', __DIR__ . '/../../sessions');
session_name('mi_sesion');
session_start();
require '../../conexion.php';  // Asegúrate de que la conexión esté configurada correctamente

// Duplicar protocolo si se recibe ?duplicar=ID
if (isset($_GET['duplicar'])) {
    $idDuplicar = intval($_GET['duplicar']);

    // Buscar el protocolo original
    $stmt = $mysqli->prepare("SELECT * FROM procedimientos WHERE id = ?");
    $stmt->bind_param("i", $idDuplicar);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $row = $resultado->fetch_assoc()) {
        // Quitar el ID para que se genere uno nuevo
        unset($row['id']);

        // Preparar columnas y valores
        $columnas = implode(", ", array_keys($row));
        $placeholders = implode(", ", array_fill(0, count($row), '?'));
        $tipos = str_repeat("s", count($row)); // Ajustar tipos si hay enteros o fechas

        $stmtInsert = $mysqli->prepare("INSERT INTO procedimientos ($columnas) VALUES ($placeholders)");
        $stmtInsert->bind_param($tipos, ...array_values($row));
        $stmtInsert->execute();

        // Redirigir al nuevo protocolo
        $nuevoId = $stmtInsert->insert_id;
        header("Location: protocolos_editors_templates.php?id=$nuevoId");
        exit();
    } else {
        die("No se encontró el protocolo para duplicar.");
    }
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está logueado
    header('Location: ../auth_login.html');
    exit();
}

// Verificar si se recibió un ID
if (!isset($_GET['id'])) {
    die("ID de protocolo no especificado.");
}

$idPrimaria = $_GET['id'];

// Consulta con JOIN para cargar los datos de procedimientos y evolucion005
$sql = "
    SELECT 
        p.*, 
        e.pre_evolucion, 
        e.pre_indicacion, 
        e.post_evolucion, 
        e.post_indicacion, 
        e.alta_evolucion, 
        e.alta_indicacion
    FROM 
        procedimientos p
    LEFT JOIN 
        evolucion005 e ON p.id = e.id
    WHERE 
        p.id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $idPrimaria);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Protocolo no encontrado.");
}

$protocolo = $result->fetch_assoc();
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

    <style>
        .autocomplete-box {
            position: absolute;
            background-color: #ffffff;
            border: 1px solid #ccc;
            z-index: 9999;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 4px;
            padding: 4px 0;
        }

        .autocomplete-box .suggestion {
            padding: 8px 12px;
            cursor: pointer;
        }

        .autocomplete-box .suggestion:hover {
            background-color: #f0f0f0;
        }
    </style>

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
                                    <li class="breadcrumb-item active"
                                        aria-current="page"><?= htmlspecialchars($protocolo['membrete']) ?></li>
                                </ol>
                            </nav>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-lg-12 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Editar Protocolo</h4>
                            </div>
                            <!-- /.box-header -->
                            <form action="guardar_protocolo.php" method="POST"
                                  class="form">
                                <section>
                                    <div class="box-body">
                                        <div class="accordion mb-3" id="accordionRequerido">
                                            <div class="accordion-item">
                                                <h4 class="accordion-header" id="headingRequerido">
                                                    <button class="accordion-button collapsed box-title text-info mt-20"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseRequerido"
                                                            aria-expanded="false" aria-controls="collapseRequerido">
                                                        <i class="ti-eye me-15"></i> Requerido
                                                    </button>
                                                </h4>
                                                <div id="collapseRequerido" class="accordion-collapse collapse"
                                                     aria-labelledby="headingRequerido"
                                                     data-bs-parent="#accordionRequerido">
                                                    <div class="accordion-body">
                                                        <input type="hidden" name="id"
                                                               value="<?= htmlspecialchars($protocolo['id']) ?>">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="imagen_link">Nombre de Cirugía</label>
                                                                    <input type="text" name="membrete" id="membrete"
                                                                           class="form-control"
                                                                           value="<?= htmlspecialchars($protocolo['membrete']) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="cirugia">Nombre Corto del
                                                                        Procedimiento</label>
                                                                    <input type="text" name="cirugia" id="cirugia"
                                                                           class="form-control"
                                                                           value="<?= htmlspecialchars($protocolo['cirugia']) ?>"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="categoriaQX">Categoría</label>
                                                                    <select name="categoriaQX" id="categoriaQX"
                                                                            class="form-select"
                                                                            required>
                                                                        <option value="Catarata" <?= ($protocolo['categoria'] == 'Catarata') ? 'selected' : '' ?>>
                                                                            Catarata
                                                                        </option>
                                                                        <option value="Conjuntiva" <?= ($protocolo['categoria'] == 'Conjuntiva') ? 'selected' : '' ?>>
                                                                            Conjuntiva
                                                                        </option>
                                                                        <option value="Estrabismo" <?= ($protocolo['categoria'] == 'Estrabismo') ? 'selected' : '' ?>>
                                                                            Estrabismo
                                                                        </option>
                                                                        <option value="Glaucoma" <?= ($protocolo['categoria'] == 'Glaucoma') ? 'selected' : '' ?>>
                                                                            Glaucoma
                                                                        </option>
                                                                        <option value="Implantes secundarios" <?= ($protocolo['categoria'] == 'Implantes secundarios') ? 'selected' : '' ?>>
                                                                            Implantes secundarios
                                                                        </option>
                                                                        <option value="Inyecciones" <?= ($protocolo['categoria'] == 'Inyecciones') ? 'selected' : '' ?>>
                                                                            Inyecciones
                                                                        </option>
                                                                        <option value="Oculoplastica" <?= ($protocolo['categoria'] == 'Oculoplastica') ? 'selected' : '' ?>>
                                                                            Oculoplastica
                                                                        </option>
                                                                        <option value="Refractiva" <?= ($protocolo['categoria'] == 'Refractiva') ? 'selected' : '' ?>>
                                                                            Refractiva
                                                                        </option>
                                                                        <option value="Retina" <?= ($protocolo['categoria'] == 'Retina') ? 'selected' : '' ?>>
                                                                            Retina
                                                                        </option>
                                                                        <option value="Traumatismo Ocular" <?= ($protocolo['categoria'] == 'Traumatismo Ocular') ? 'selected' : '' ?>>
                                                                            Traumatismo Ocular
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="dieresis">Dieresis</label>
                                                                        <input type="text" name="dieresis" id="dieresis"
                                                                               class="form-control"
                                                                               value="<?= htmlspecialchars($protocolo['dieresis']) ?>"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="exposicion">Exposición</label>
                                                                        <input type="text" name="exposicion"
                                                                               id="exposicion"
                                                                               class="form-control"
                                                                               value="<?= htmlspecialchars($protocolo['exposicion']) ?>"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="hallazgo">Hallazgos</label>
                                                                        <input type="text" name="hallazgo" id="hallazgo"
                                                                               class="form-control"
                                                                               value="<?= htmlspecialchars($protocolo['hallazgo']) ?>"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="horas">Horas</label>
                                                                        <input type="text" name="horas" id="horas"
                                                                               class="form-control"
                                                                               value="<?= htmlspecialchars($protocolo['horas']) ?>"
                                                                               required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion mb-3" id="accordionOperatorio">
                                            <div class="accordion-item">
                                                <h4 class="accordion-header" id="headingOperatorio">
                                                    <button class="accordion-button collapsed box-title text-info mt-20"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseOperatorio"
                                                            aria-expanded="false" aria-controls="collapseOperatorio">
                                                        <i class="ti-pencil-alt me-15"></i> Operatorio
                                                    </button>
                                                </h4>
                                                <div id="collapseOperatorio" class="accordion-collapse collapse"
                                                     aria-labelledby="headingOperatorio"
                                                     data-bs-parent="#accordionOperatorio">
                                                    <div class="accordion-body">
                                                        <div class="form-group">
                                                            <label for="imagen_link">Enlace de Imagen</label>
                                                            <input type="text" name="imagen_link" id="imagen_link"
                                                                   class="form-control"
                                                                   value="<?= htmlspecialchars($protocolo['imagen_link']) ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Select File</label>
                                                            <label class="file">
                                                                <input type="file" id="file">
                                                            </label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="operatorio">Operatorio</label>
                                                            <textarea rows="5" name="operatorio" id="operatorio"
                                                                      class="form-control"><?= htmlspecialchars($protocolo['operatorio']) ?></textarea>
                                                            <div id="autocomplete-insumos"
                                                                 class="autocomplete-box"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion mb-3" id="accordionEvolucion">
                                            <div class="accordion-item">
                                                <h4 class="accordion-header" id="headingEvolucion">
                                                    <button class="accordion-button collapsed box-title text-info mt-20"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseEvolucion"
                                                            aria-expanded="false" aria-controls="collapseEvolucion">
                                                        <i class="ti-pencil-alt me-15"></i> Evolución
                                                    </button>
                                                </h4>
                                                <div id="collapseEvolucion" class="accordion-collapse collapse"
                                                     aria-labelledby="headingEvolucion"
                                                     data-bs-parent="#accordionEvolucion">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="pre_evolucion">Evolución Pre
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="pre_evolucion"
                                                                              id="pre_evolucion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['pre_evolucion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="pre_indicacion">Indicación Pre
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="pre_indicacion"
                                                                              id="pre_indicacion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['pre_indicacion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="post_evolucion">Evolución Post
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="post_evolucion"
                                                                              id="post_evolucion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['post_evolucion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="post_indicacion">Indicación Post
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="post_indicacion"
                                                                              id="post_indicacion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['post_indicacion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="alta_evolucion">Evolución Alta
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="alta_evolucion"
                                                                              id="alta_evolucion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['alta_evolucion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="alta_indicacion">Indicación Alta
                                                                        Quirúrgica</label>
                                                                    <textarea rows="3" name="alta_indicacion"
                                                                              id="alta_indicacion"
                                                                              class="form-control"><?= htmlspecialchars(trim($protocolo['alta_indicacion'])) ?></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion mb-3" id="accordionKardex">
                                            <div class="accordion-item">
                                                <h4 class="accordion-header" id="headingKardex">
                                                    <button class="accordion-button collapsed box-title text-info mt-20"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseKardex"
                                                            aria-expanded="false" aria-controls="collapseKardex">
                                                        <i class="ti-bookmark-alt me-15"></i> Kardex
                                                    </button>
                                                </h4>
                                                <div id="collapseKardex" class="accordion-collapse collapse"
                                                     aria-labelledby="headingKardex"
                                                     data-bs-parent="#accordionKardex">
                                                    <div class="accordion-body">                                        <?php
                                                        // Obtener los medicamentos del JSON desde la tabla `kardex`
                                                        $sql = "SELECT medicamentos FROM kardex WHERE procedimiento_id = ?";
                                                        $stmt = $mysqli->prepare($sql);
                                                        $stmt->bind_param('s', $idPrimaria);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();

                                                        // Decodificar el JSON de medicamentos
                                                        $medicamentos = !empty($row['medicamentos']) ? json_decode($row['medicamentos'], true) : [];
                                                        if (json_last_error() !== JSON_ERROR_NONE) {
                                                            die("Error al decodificar el JSON: " . json_last_error_msg());
                                                        }

                                                        // Obtener los medicamentos disponibles
                                                        $sqlOpciones = "SELECT id, medicamento FROM medicamentos ORDER BY medicamento";
                                                        $resultOpciones = $mysqli->query($sqlOpciones);

                                                        $opcionesMedicamentos = [];
                                                        while ($rowOpciones = $resultOpciones->fetch_assoc()) {
                                                            $opcionesMedicamentos[] = [
                                                                'id' => $rowOpciones['id'],
                                                                'nombre' => $rowOpciones['medicamento']
                                                            ];
                                                        }

                                                        // Opciones estáticas
                                                        $vias = ['INTRAVENOSA', 'VIA INFILTRATIVA', 'SUBCONJUNTIVAL', 'TOPICA', 'INTRAVITREA'];
                                                        $responsables = ['Asistente', 'Anestesiólogo', 'Cirujano Principal'];
                                                        ?>

                                                        <!-- Tabla HTML -->
                                                        <div class="table-responsive">
                                                            <table id="medicamentosTable"
                                                                   class="table editable-table mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>Medicamento</th>
                                                                    <th>Dosis</th>
                                                                    <th>Frecuencia</th>
                                                                    <th>Vía de Administración</th>
                                                                    <th>Responsable</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                if (!empty($medicamentos)) {
                                                                    foreach ($medicamentos as $item) {
                                                                        echo '<tr>';

                                                                        // Medicamento (con ID)
                                                                        echo '<td><select class="form-control medicamento-select" name="medicamento[]">';
                                                                        foreach ($opcionesMedicamentos as $opcion) {
                                                                            $selected = (isset($item['id']) && $opcion['id'] == $item['id']) ? 'selected' : '';
                                                                            echo '<option value="' . htmlspecialchars($opcion['id']) . '" ' . $selected . '>' . htmlspecialchars($opcion['nombre']) . '</option>';
                                                                        }
                                                                        echo '</select></td>';

                                                                        // Dosis
                                                                        echo '<td contenteditable="true" data-dosis="' . htmlspecialchars($item['dosis'] ?? '') . '">' . htmlspecialchars($item['dosis'] ?? '') . '</td>';

                                                                        // Frecuencia
                                                                        echo '<td contenteditable="true" data-frecuencia="' . htmlspecialchars($item['frecuencia'] ?? '') . '">' . htmlspecialchars($item['frecuencia'] ?? '') . '</td>';

                                                                        // Vía de administración
                                                                        echo '<td><select class="form-control via-select" name="via_administracion[]">';
                                                                        foreach ($vias as $via) {
                                                                            $selected = ($via === ($item['via_administracion'] ?? '')) ? 'selected' : '';
                                                                            echo '<option value="' . htmlspecialchars($via) . '" ' . $selected . '>' . htmlspecialchars($via) . '</option>';
                                                                        }
                                                                        echo '</select></td>';

                                                                        // Responsable
                                                                        echo '<td><select class="form-control responsable-select" name="responsable[]">';
                                                                        foreach ($responsables as $responsable) {
                                                                            $selected = ($responsable === ($item['responsable'] ?? '')) ? 'selected' : '';
                                                                            echo '<option value="' . htmlspecialchars($responsable) . '" ' . $selected . '>' . htmlspecialchars($responsable) . '</option>';
                                                                        }
                                                                        echo '</select></td>';

                                                                        // Acciones
                                                                        echo '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> ';
                                                                        echo '<button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>';

                                                                        echo '</tr>';
                                                                    }
                                                                } else {
                                                                    // Fila vacía por defecto
                                                                    echo '<tr>';
                                                                    echo '<td><select class="form-control medicamento-select" name="medicamento[]">';
                                                                    foreach ($opcionesMedicamentos as $opcion) {
                                                                        echo '<option value="' . htmlspecialchars($opcion['id']) . '">' . htmlspecialchars($opcion['nombre']) . '</option>';
                                                                    }
                                                                    echo '</select></td>';
                                                                    echo '<td contenteditable="true" data-dosis=""></td>';
                                                                    echo '<td contenteditable="true" data-frecuencia=""></td>';
                                                                    echo '<td><select class="form-control via-select" name="via_administracion[]">';
                                                                    foreach ($vias as $via) {
                                                                        echo '<option value="' . htmlspecialchars($via) . '">' . htmlspecialchars($via) . '</option>';
                                                                    }
                                                                    echo '</select></td>';
                                                                    echo '<td><select class="form-control responsable-select" name="responsable[]">';
                                                                    foreach ($responsables as $responsable) {
                                                                        echo '<option value="' . htmlspecialchars($responsable) . '">' . htmlspecialchars($responsable) . '</option>';
                                                                    }
                                                                    echo '</select></td>';
                                                                    echo '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                            <!-- Campo oculto para almacenar los medicamentos como JSON -->
                                                            <input type="hidden" id="medicamentosInput"
                                                                   name="medicamentos"
                                                                   value='<?= htmlspecialchars(json_encode($medicamentos)) ?>'>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion mb-3" id="accordionInsumos">
                                            <div class="accordion-item">
                                                <h4 class="accordion-header" id="headingInsumos">
                                                    <button class="accordion-button collapsed box-title text-info mt-20"
                                                            type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#collapseInsumos"
                                                            aria-expanded="false" aria-controls="collapseInsumos">
                                                        <i class="ti-list me-15"></i> Lista de insumos
                                                    </button>
                                                </h4>
                                                <div id="collapseInsumos" class="accordion-collapse collapse"
                                                     aria-labelledby="headingInsumos"
                                                     data-bs-parent="#accordionInsumos">
                                                    <div class="accordion-body">
                                                        <?php
                                                        // Obtener las categorías y los insumos de la tabla `insumos`
                                                        $sqlCategorias = "SELECT DISTINCT categoria FROM insumos order by categoria";
                                                        $resultCategorias = $mysqli->query($sqlCategorias);
                                                        $categorias = [];
                                                        while ($row = $resultCategorias->fetch_assoc()) {
                                                            $categorias[] = $row['categoria'];
                                                        }

                                                        $sqlInsumos = "SELECT id, nombre, categoria FROM insumos ORDER BY nombre";
                                                        $resultInsumos = $mysqli->query($sqlInsumos);
                                                        $insumosDisponibles = [];
                                                        while ($row = $resultInsumos->fetch_assoc()) {
                                                            $insumosDisponibles[$row['categoria']][] = [
                                                                'id' => $row['id'],
                                                                'nombre' => $row['nombre']
                                                            ];
                                                        }

                                                        // Obtener los insumos del JSON desde la tabla `insumos_pack` (ajusta según tu esquema)
                                                        $sql = "SELECT insumos FROM insumos_pack WHERE procedimiento_id = ?";
                                                        $stmt = $mysqli->prepare($sql);
                                                        $stmt->bind_param('s', $idPrimaria);
                                                        $stmt->execute();
                                                        $result = $stmt->get_result();
                                                        $row = $result->fetch_assoc();

                                                        // Decodificar el JSON
                                                        $insumos = json_decode($row['insumos'], true);
                                                        ?>

                                                        <!-- Tabla HTML -->
                                                        <div class="table-responsive">
                                                            <table id="insumosTable" class="table editable-table mb-0">
                                                                <thead>
                                                                <tr>
                                                                    <th>Categoría</th>
                                                                    <th>Nombre del Insumo</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Acciones</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php
                                                                if (!empty($insumos)) {
                                                                    // Iterar sobre las categorías del JSON y agregar filas a la tabla
                                                                    foreach (['equipos', 'quirurgicos', 'anestesia'] as $categoriaOrdenada) {
                                                                        if (isset($insumos[$categoriaOrdenada])) {
                                                                            foreach ($insumos[$categoriaOrdenada] as $item) {
                                                                                echo '<tr class="categoria-' . htmlspecialchars($categoriaOrdenada) . '">';
                                                                                echo '<td><select class="form-control categoria-select" name="categoria">';
                                                                                foreach ($categorias as $cat) {
                                                                                    $selected = ($cat == $categoriaOrdenada) ? 'selected' : '';
                                                                                    echo '<option value="' . htmlspecialchars($cat) . '" ' . $selected . '>' . htmlspecialchars(str_replace('_', ' ', $cat)) . '</option>';
                                                                                }
                                                                                echo '</select></td>';
                                                                                echo '<td><select class="form-control nombre-select" name="nombre" data-nombre="' . htmlspecialchars($item['nombre']) . '">';
                                                                                if (isset($insumosDisponibles[$categoriaOrdenada])) {
                                                                                    foreach ($insumosDisponibles[$categoriaOrdenada] as $insumo) {
                                                                                        $selected = (isset($item['id']) && $insumo['id'] == $item['id']) ? 'selected' : '';
                                                                                        echo '<option value="' . $insumo['id'] . '" data-nombre="' . htmlspecialchars($insumo['nombre']) . '" ' . $selected . '>' . htmlspecialchars($insumo['nombre']) . '</option>';
                                                                                    }
                                                                                } else {
                                                                                    echo '<option value="">Seleccione una categoría primero</option>';
                                                                                }
                                                                                echo '</select></td>';
                                                                                echo '<td contenteditable="true" data-cantidad="' . htmlspecialchars($item['cantidad']) . '">' . htmlspecialchars($item['cantidad']) . '</td>';
                                                                                echo '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>';
                                                                                echo '</tr>';
                                                                            }
                                                                        }
                                                                    }
                                                                } else {
                                                                    // Si no hay insumos, mostrar una fila con celdas predeterminadas para agregar el primer insumo
                                                                    echo '<tr class="categoria-equipos">';
                                                                    echo '<td><select class="form-control categoria-select" name="categoria[]">';
                                                                    foreach ($categorias as $cat) {
                                                                        echo '<option value="' . htmlspecialchars($cat) . '">' . htmlspecialchars(str_replace('_', ' ', $cat)) . '</option>';
                                                                    }
                                                                    echo '</select></td>';
                                                                    echo '<td><select class="form-control nombre-select" name="nombre[]">';
                                                                    foreach ($insumosDisponibles['equipos'] as $nombre) {
                                                                        echo '<option value="' . htmlspecialchars($nombre) . '">' . htmlspecialchars($nombre) . '</option>';
                                                                    }
                                                                    echo '</select></td>';
                                                                    echo '<td contenteditable="true" data-cantidad="1">1</td>';
                                                                    echo '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>';
                                                                    echo '</tr>';
                                                                }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                            <!-- Campo oculto para almacenar los insumos como JSON -->
                                                            <input type="hidden" id="insumosInput" name="insumos"
                                                                   value='<?= htmlspecialchars(json_encode($insumos)) ?>'>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                    <div class="box-footer">
                                        <button type="button" class="btn btn-warning me-1">
                                            <i class="ti-trash"></i> Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti-save-alt"></i> Guardar
                                        </button>
                                    </div>
                                </section>
                            </form>
                        </div>
                        <!-- /.box -->
                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../assets/vendor_components/datatable/datatables.min.js"></script>
<script src="../../assets/vendor_components/tiny-editable/mindmup-editabletable.js"></script>
<script src="../../assets/vendor_components/tiny-editable/numeric-input-example.js"></script>


<!-- Doclinic App -->
<script src="../js/jquery.smartmenus.js"></script>
<script src="../js/menus.js"></script>
<script src="../js/template.js"></script>
<script>
    const insumosDisponibles = <?= json_encode($insumosDisponibles); ?>;
    const opcionesMedicamentos = <?= json_encode($opcionesMedicamentos); ?>;
    const vias = <?= json_encode($vias); ?>;
    const responsables = <?= json_encode($responsables); ?>;
</script>
<script src="../js/autocomplete-operatorio.js"></script>
<script src="../js/editor-protocolos.js"></script>

</body>
</html>
