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
                                        <h4 class="box-title text-info mb-0"><i class="ti-eye me-15"></i> Requerido</h4>
                                        <hr class="my-15">
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
                                                    <label for="cirugia">Nombre Corto del Procedimiento</label>
                                                    <input type="text" name="cirugia" id="cirugia" class="form-control"
                                                           value="<?= htmlspecialchars($protocolo['cirugia']) ?>"
                                                           required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="categoriaQX">Categoría</label>
                                                    <select name="categoriaQX" id="categoriaQX" class="form-select"
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
                                                        <input type="text" name="exposicion" id="exposicion"
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
                                            <h4 class="box-title text-info mb-0 mt-20"><i
                                                        class="ti-pencil-alt me-15"></i>
                                                Operatorio</h4>
                                            <hr class="my-15">
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
                                                          class="form-control"
                                                          rows="4"><?= htmlspecialchars($protocolo['operatorio']) ?></textarea>
                                            </div>
                                            <h4 class="box-title text-info mb-0 mt-20"><i
                                                        class="ti-pencil-alt me-15"></i>
                                                Evolución</h4>
                                            <hr class="my-15">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hallazgo">Evolución Pre Quirúrgica</label>
                                                        <textarea rows="3" name="pre_evolucion" id="pre_evolucion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['pre_evolucion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="horas">Indicación Pre Quirúrgica</label>
                                                        <textarea rows="3" name="pre_indicacion" id="pre_indicacion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['pre_indicacion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hallazgo">Evolución Post Quirúrgica</label>
                                                        <textarea rows="3" name="post_evolucion" id="post_evolucion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['post_evolucion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="horas">Indicación Post Quirúrgica</label>
                                                        <textarea rows="3" name="post_indicacion" id="post_indicacion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['post_indicacion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="hallazgo">Evolución Alta Quirúrgica</label>
                                                        <textarea rows="3" name="alta_evolucion" id="alta_evolucion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['alta_evolucion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="horas">Indicación Alta Quirúrgica</label>
                                                        <textarea rows="3" name="alta_indicacion" id="alta_indicacion"
                                                                  class="form-control"><?= htmlspecialchars($protocolo['alta_indicacion']) ?>
                                                        </textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <h4 class="box-title text-info mb-0"><i class="ti-bookmark-alt me-15"></i>
                                                Kardex
                                            </h4>
                                            <hr class="my-15"> <?php
                                            // Obtener los insumos del JSON desde la tabla `insumos_pack` (ajusta según tu esquema)
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

                                            // Consulta para obtener opciones de medicamentos desde la tabla medicamentos
                                            $sqlOpciones = "SELECT DISTINCT medicamento FROM medicamentos ORDER BY medicamento";
                                            $resultOpciones = $mysqli->query($sqlOpciones);

                                            $opcionesMedicamentos = [];
                                            while ($rowOpciones = $resultOpciones->fetch_assoc()) {
                                                $opcionesMedicamentos[] = $rowOpciones['medicamento'];
                                            }

                                            // Opciones estáticas para Vías de Administración y Responsables
                                            $vias = ['INTRAVENOSA', 'VIA INFILTRATIVA', 'SUBCONJUNTIVAL', 'TOPICA', 'INTRAVITREA'];
                                            $responsables = ['Asistente', 'Anestesiólogo', 'Cirujano Principal'];
                                            ?>

                                            <!-- Tabla HTML -->
                                            <div class="table-responsive">
                                                <table id="medicamentosTable" class="table editable-table mb-0">
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
                                                    // Rellenar filas con datos de medicamentos o una fila vacía si no hay datos
                                                    if (!empty($medicamentos)) {
                                                        foreach ($medicamentos as $item) {
                                                            echo '<tr>';
                                                            // Columna Medicamento
                                                            echo '<td><select class="form-control medicamento-select" name="medicamento[]">';
                                                            foreach ($opcionesMedicamentos as $opcion) {
                                                                $selected = ($opcion === $item['medicamento']) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($opcion) . '" ' . $selected . '>' . htmlspecialchars($opcion) . '</option>';
                                                            }
                                                            echo '</select></td>';

                                                            // Columnas Dosis y Frecuencia
                                                            echo '<td contenteditable="true" data-dosis="' . htmlspecialchars($item['dosis'] ?? '') . '">' . htmlspecialchars($item['dosis'] ?? '') . '</td>';
                                                            echo '<td contenteditable="true" data-frecuencia="' . htmlspecialchars($item['frecuencia'] ?? '') . '">' . htmlspecialchars($item['frecuencia'] ?? '') . '</td>';

                                                            // Columna Vía de Administración
                                                            echo '<td><select class="form-control via-select" name="via_administracion[]">';
                                                            foreach ($vias as $via) {
                                                                $selected = ($via === $item['via_administracion']) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($via) . '" ' . $selected . '>' . htmlspecialchars($via) . '</option>';
                                                            }
                                                            echo '</select></td>';

                                                            // Columna Responsable
                                                            echo '<td><select class="form-control responsable-select" name="responsable[]">';
                                                            foreach ($responsables as $responsable) {
                                                                $selected = ($responsable === $item['responsable']) ? 'selected' : '';
                                                                echo '<option value="' . htmlspecialchars($responsable) . '" ' . $selected . '>' . htmlspecialchars($responsable) . '</option>';
                                                            }
                                                            echo '</select></td>';

                                                            // Columna Acciones
                                                            echo '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>';
                                                            echo '</tr>';
                                                        }
                                                    } else {
                                                        // Fila vacía por defecto
                                                        echo '<tr>';
                                                        echo '<td><select class="form-control medicamento-select" name="medicamento[]">';
                                                        foreach ($opcionesMedicamentos as $opcion) {
                                                            echo '<option value="' . htmlspecialchars($opcion) . '">' . htmlspecialchars($opcion) . '</option>';
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
                                                <input type="hidden" id="medicamentosInput" name="medicamentos"
                                                       value='<?= htmlspecialchars(json_encode($medicamentos)) ?>'>
                                            </div>
                                            <h4 class="box-title text-info mb-0"><i class="ti-list me-15"></i> Lista de
                                                insumos</h4>
                                            <hr class="my-15"> <?php
                                            // Obtener las categorías y los insumos de la tabla `insumos`
                                            $sqlCategorias = "SELECT DISTINCT categoria FROM insumos order by categoria";
                                            $resultCategorias = $mysqli->query($sqlCategorias);
                                            $categorias = [];
                                            while ($row = $resultCategorias->fetch_assoc()) {
                                                $categorias[] = $row['categoria'];
                                            }

                                            $sqlInsumos = "SELECT nombre, categoria FROM insumos order by nombre";
                                            $resultInsumos = $mysqli->query($sqlInsumos);
                                            $insumosDisponibles = [];
                                            while ($row = $resultInsumos->fetch_assoc()) {
                                                $insumosDisponibles[$row['categoria']][] = $row['nombre'];
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
                                                                        foreach ($insumosDisponibles[$categoriaOrdenada] as $nombre) {
                                                                            $selected = ($nombre == $item['nombre']) ? 'selected' : '';
                                                                            echo '<option value="' . htmlspecialchars($nombre) . '" ' . $selected . '>' . htmlspecialchars($nombre) . '</option>';
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
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch('guardar_protocolo.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text()) // Usar .text() para depurar el texto completo
                .then(text => {
                    try {
                        console.log("Respuesta completa del servidor:", text); // Verificar respuesta completa
                        const data = JSON.parse(text); // Intentar analizar como JSON

                        if (data.success) {
                            Swal.fire("Datos Actualizados!", data.message, "success");
                        } else {
                            Swal.fire("Error", data.message, "error");
                        }
                    } catch (error) {
                        console.error("Error al analizar el JSON:", error, text);
                        Swal.fire("Error", "Respuesta inesperada del servidor.", "error");
                    }
                })
                .catch(error => {
                    console.error('Error al actualizar los datos:', error);
                    Swal.fire("Error", "Ocurrió un error al actualizar los datos. Por favor, intenta nuevamente.", "error");
                });
        });
    });
</script>
<script>
    $(function () {
        "use strict";

        // Inicializar DataTable
        var table = $('#medicamentosTable').DataTable({
            "paging": false // Desactivar la paginación
        });

        // Agregar evento para eliminar filas
        $('#medicamentosTable').on('click', '.delete-btn', function () {
            table.row($(this).parents('tr')).remove().draw();
            actualizarMedicamentos();
        });

        // Evento para agregar una nueva fila justo debajo de la actual
        $('#medicamentosTable').on('click', '.add-row-btn', function (event) {
            event.preventDefault();
            var medicamentoOptions = '<?php foreach ($opcionesMedicamentos as $medicamento) {
                echo "<option value=\"" . htmlspecialchars($medicamento) . "\">" . htmlspecialchars($medicamento) . "</option>";
            } ?>';
            var viaOptions = '<?php foreach ($vias as $via) {
                echo "<option value=\"" . htmlspecialchars($via) . "\">" . htmlspecialchars($via) . "</option>";
            } ?>';
            var responsableOptions = '<?php foreach ($responsables as $responsable) {
                echo "<option value=\"" . htmlspecialchars($responsable) . "\">" . htmlspecialchars($responsable) . "</option>";
            } ?>';

            var currentRow = $(this).closest('tr');
            var newRow = $(
                '<tr>' +
                '<td><select class="form-control medicamento-select" name="medicamento[]">' + medicamentoOptions + '</select></td>' +
                '<td contenteditable="true"></td>' +
                '<td contenteditable="true"></td>' +
                '<td><select class="form-control via-select" name="via_administracion[]">' + viaOptions + '</select></td>' +
                '<td><select class="form-control responsable-select" name="responsable[]">' + responsableOptions + '</select></td>' +
                '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>' +
                '</tr>'
            );
            newRow.insertAfter(currentRow); // Insertar nueva fila justo después de la actual
            actualizarMedicamentos();
        });

        // Actualizar el campo oculto con el JSON de los medicamentos
        function actualizarMedicamentos() {
            var medicamentosArray = [];
            $('#medicamentosTable tbody tr').each(function () {
                var medicamento = $(this).find('select[name="medicamento[]"]').val();
                var dosis = $(this).find('td:eq(1)').text().trim(); // Captura texto editable
                var frecuencia = $(this).find('td:eq(2)').text().trim(); // Captura texto editable
                var via_administracion = $(this).find('select[name="via_administracion[]"]').val();
                var responsable = $(this).find('select[name="responsable[]"]').val();

                if (medicamento || dosis || frecuencia || via_administracion || responsable) {
                    medicamentosArray.push({
                        medicamento: medicamento,
                        dosis: dosis,
                        frecuencia: frecuencia,
                        via_administracion: via_administracion,
                        responsable: responsable
                    });
                }
            });
            var jsonMedicamentos = JSON.stringify(medicamentosArray);
            $('#medicamentosInput').val(jsonMedicamentos);
            console.log("Actualizado JSON medicamentos: ", jsonMedicamentos);
        }

        // Cambiar el fondo de la fila según el valor del responsable
        function cambiarColorFila() {
            $('#medicamentosTable tbody tr').each(function () {
                var responsable = $(this).find('select[name="responsable[]"]').val();
                $(this).css('background-color', ''); // Restablecer el color antes de aplicar el nuevo
                if (responsable === 'Anestesiólogo') {
                    $(this).css('background-color', '#f8d7da'); // Rojo claro
                } else if (responsable === 'Cirujano Principal') {
                    $(this).css('background-color', '#cce5ff'); // Azul claro
                } else if (responsable === 'Asistente') {
                    $(this).css('background-color', '#d4edda'); // Verde claro
                }
            });
        }

        // Aplicar el color al cambiar el valor del responsable
        $('#medicamentosTable').on('change', 'select[name="responsable[]"]', function () {
            cambiarColorFila();
            actualizarMedicamentos();
        });

        // Aplicar colores iniciales al cargar la tabla
        cambiarColorFila();

        // Asegurarse de que se actualicen los medicamentos al editar la tabla
        $('#medicamentosTable').on('input change', 'td[contenteditable="true"], select', function () {
            actualizarMedicamentos();
        });
    });
</script>
<script>
    $(function () {
        "use strict";

        // Inicializar DataTable
        var table = $('#insumosTable').DataTable({
            "paging": false // Desactivar la paginación
        });

        // Hacer la tabla editable (editableTableWidget)
        $('#insumosTable').editableTableWidget();

        // Agregar evento para eliminar filas
        $('#insumosTable').on('click', '.delete-btn', function () {
            table.row($(this).parents('tr')).remove().draw();
            actualizarInsumos();
        });

        // Evento para agregar una nueva fila debajo de la actual
        $('#insumosTable').on('click', '.add-row-btn', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            console.log("Botón de agregar fila fue presionado");
            var categoriaOptions = '<?php foreach ($categorias as $cat) {
                echo "<option value=\"" . htmlspecialchars($cat) . "\">" . htmlspecialchars(str_replace("_", " ", $cat)) . "</option>";
            } ?>';
            var newData = [
                '<select class="form-control categoria-select" name="categoria">' + categoriaOptions + '</select>', // Categoría por defecto
                '<select class="form-control nombre-select" name="nombre" data-nombre=""><option value="">Seleccione una categoría primero</option></select>',  // Nombre por defecto
                '1',             // Cantidad por defecto
                '<button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button>'
            ];
            var currentRow = $(this).parents('tr');
            var rowIndex = table.row(currentRow).index();
            table.row.add(newData).draw(false); // Agregar la nueva fila debajo de la actual
            var newRow = table.row(rowIndex + 1).nodes().to$();
            newRow.insertAfter(currentRow);
            console.log("Nueva fila agregada: ", newData);
            actualizarInsumos();
        });

        // Actualizar los insumos disponibles según la categoría seleccionada
        var insumosDisponibles = <?php echo json_encode($insumosDisponibles); ?>;
        $('#insumosTable').on('change', '.categoria-select', function () {
            var categoriaSeleccionada = $(this).val();
            var nombreSelect = $(this).closest('tr').find('.nombre-select');
            nombreSelect.empty();
            if (categoriaSeleccionada && insumosDisponibles[categoriaSeleccionada]) {
                $.each(insumosDisponibles[categoriaSeleccionada], function (index, value) {
                    nombreSelect.append('<option value="' + value + '">' + value + '</option>');
                });
                // Seleccionar el valor correspondiente del insumo si ya existe
                var nombreActual = nombreSelect.data('nombre');
                if (nombreActual) {
                    nombreSelect.val(nombreActual);
                }
            } else {
                nombreSelect.append('<option value="">Seleccione una categoría primero</option>');
            }
        }).trigger('change');

        // Colorear las filas según la categoría
        $('#insumosTable tbody tr').each(function () {
            var categoria = $(this).find('select[name="categoria"]').val().toLowerCase();
            if (categoria === 'equipos') {
                $(this).css('background-color', '#d4edda'); // Verde claro
            } else if (categoria === 'anestesia') {
                $(this).css('background-color', '#fff3cd'); // Amarillo claro
            } else if (categoria === 'quirurgicos') {
                $(this).css('background-color', '#cce5ff'); // Azul claro
            }
        });

        // Actualizar el campo oculto con el JSON de los insumos
        function actualizarInsumos() {
            var insumosObject = {
                equipos: [],
                anestesia: [],
                quirurgicos: []
            };
            $('#insumosTable tbody tr').each(function () {
                var categoria = $(this).find('select[name="categoria"]').val().toLowerCase();
                var nombre = $(this).find('select[name="nombre"]').val();
                var cantidad = $(this).find('td:eq(2)').text();

                if (categoria && nombre && cantidad) {
                    insumosObject[categoria].push({
                        nombre: nombre,
                        cantidad: parseInt(cantidad)
                    });
                }
            });
            var jsonInsumos = JSON.stringify(insumosObject);
            $('#insumosInput').val(jsonInsumos);
            console.log("Actualizado JSON insumos: ", jsonInsumos); // Depurar el valor actualizado
        }

        // Asegurarse de que se actualicen los insumos al editar la tabla
        $('#insumosTable').on('change', 'td', function () {
            actualizarInsumos();
        });
    });
</script>

</body>
</html>
