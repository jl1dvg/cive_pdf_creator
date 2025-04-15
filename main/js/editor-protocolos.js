$(function () {
    "use strict";

    // ---------- MEDICAMENTOS ----------
    var medicamentosTable = $('#medicamentosTable').DataTable({paging: false});

    $('#medicamentosTable').on('click', '.delete-btn', function () {
        medicamentosTable.row($(this).parents('tr')).remove().draw();
        actualizarMedicamentos();
    });

    $('#medicamentosTable').on('click', '.add-row-btn', function (e) {
        e.preventDefault();

        let medicamentoOptions = '';
        opcionesMedicamentos.forEach(function (med) {
            medicamentoOptions += '<option value="' + med.id + '">' + med.nombre + '</option>';
        });

        let viaOptions = '';
        vias.forEach(function (via) {
            viaOptions += '<option value="' + via + '">' + via + '</option>';
        });

        let responsableOptions = '';
        responsables.forEach(function (r) {
            responsableOptions += '<option value="' + r + '">' + r + '</option>';
        });

        const newRow = $(
            '<tr>' +
            '<td><select class="form-control medicamento-select" name="medicamento[]">' + medicamentoOptions + '</select></td>' +
            '<td contenteditable="true"></td>' +
            '<td contenteditable="true"></td>' +
            '<td><select class="form-control via-select" name="via_administracion[]">' + viaOptions + '</select></td>' +
            '<td><select class="form-control responsable-select" name="responsable[]">' + responsableOptions + '</select></td>' +
            '<td><button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button></td>' +
            '</tr>'
        );
        $(this).closest('tr').after(newRow);
        actualizarMedicamentos();
    });

    function actualizarMedicamentos() {
        var medicamentosArray = [];
        $('#medicamentosTable tbody tr').each(function () {
            const medicamentoId = $(this).find('select[name="medicamento[]"]').val();
            const medicamentoNombre = $(this).find('select[name="medicamento[]"] option:selected').text();
            const dosis = $(this).find('td:eq(1)').text().trim();
            const frecuencia = $(this).find('td:eq(2)').text().trim();
            const via = $(this).find('select[name="via_administracion[]"]').val();
            const responsable = $(this).find('select[name="responsable[]"]').val();

            if (medicamentoId || dosis || frecuencia || via || responsable) {
                medicamentosArray.push({
                    id: medicamentoId,
                    medicamento: medicamentoNombre,
                    dosis: dosis,
                    frecuencia: frecuencia,
                    via_administracion: via,
                    responsable: responsable
                });
            }
        });
        $('#medicamentosInput').val(JSON.stringify(medicamentosArray));
        console.log("✅ JSON medicamentos:", medicamentosArray);
    }

    function cambiarColorFila() {
        $('#medicamentosTable tbody tr').each(function () {
            const responsable = $(this).find('select[name="responsable[]"]').val();
            $(this).css('background-color', '');
            if (responsable === 'Anestesiólogo') $(this).css('background-color', '#f8d7da');
            else if (responsable === 'Cirujano Principal') $(this).css('background-color', '#cce5ff');
            else if (responsable === 'Asistente') $(this).css('background-color', '#d4edda');
        });
    }

    $('#medicamentosTable').on('change', 'select[name="responsable[]"]', function () {
        cambiarColorFila();
        actualizarMedicamentos();
    });

    $('#medicamentosTable').on('input change', 'td[contenteditable="true"], select', function () {
        actualizarMedicamentos();
    });

    cambiarColorFila();


    // ---------- INSUMOS ----------
    var insumosTable = $('#insumosTable').DataTable({paging: false});
    $('#insumosTable').editableTableWidget();

    $('#insumosTable').on('click', '.delete-btn', function () {
        insumosTable.row($(this).parents('tr')).remove().draw();
        actualizarInsumos();
    });

    $('#insumosTable').on('click', '.add-row-btn', function (event) {
        event.preventDefault();
        var categoriaOptions = '';
        for (const cat in insumosDisponibles) {
            categoriaOptions += `<option value="${cat}">${cat.replace('_', ' ')}</option>`;
        }

        var newData = [
            `<select class="form-control categoria-select" name="categoria">${categoriaOptions}</select>`,
            '<select class="form-control nombre-select" name="nombre"><option value="">Seleccione una categoría</option></select>',
            '1',
            '<button class="delete-btn btn btn-danger"><i class="fa fa-minus"></i></button> <button class="add-row-btn btn btn-success"><i class="fa fa-plus"></i></button>'
        ];

        const currentRow = $(this).parents('tr');
        const rowIndex = insumosTable.row(currentRow).index();
        insumosTable.row.add(newData).draw(false);
        const newRow = insumosTable.row(rowIndex + 1).nodes().to$();
        newRow.insertAfter(currentRow);
        actualizarInsumos();
    });

    $('#insumosTable').on('change', '.categoria-select', function () {
        const categoria = $(this).val();
        const nombreSelect = $(this).closest('tr').find('.nombre-select');
        nombreSelect.empty();
        if (categoria && insumosDisponibles[categoria]) {
            insumosDisponibles[categoria].forEach(insumo => {
                nombreSelect.append(`<option value="${insumo.id}">${insumo.nombre}</option>`);
            });
        } else {
            nombreSelect.append('<option value="">Seleccione una categoría primero</option>');
        }
    }).trigger('change');

    $('#insumosTable tbody tr').each(function () {
        var categoria = $(this).find('select[name="categoria"]').val().toLowerCase();
        if (categoria === 'equipos') {
            $(this).css('background-color', '#d4edda');
        } else if (categoria === 'anestesia') {
            $(this).css('background-color', '#fff3cd');
        } else if (categoria === 'quirurgicos') {
            $(this).css('background-color', '#cce5ff');
        }
    });

    $('#insumosTable').on('change input', 'td', function () {
        actualizarInsumos();
    });

    window.actualizarInsumos = function () {
        var insumosObject = {
            equipos: [],
            anestesia: [],
            quirurgicos: []
        };
        $('#insumosTable tbody tr').each(function () {
            const categoria = $(this).find('select[name="categoria"]').val().toLowerCase();
            const insumoId = $(this).find('select[name="nombre"]').val();
            const insumoNombre = $(this).find('select[name="nombre"] option:selected').text();
            const cantidad = $(this).find('td:eq(2)').text().trim();
            if (categoria && insumoId && insumoNombre && cantidad) {
                insumosObject[categoria].push({
                    id: insumoId,
                    nombre: insumoNombre,
                    cantidad: parseInt(cantidad)
                });
            }
        });
        const json = JSON.stringify(insumosObject);
        $('#insumosInput').val(json);
        console.log("✅ INSUMOS JSON ACTUALIZADO:", json);
    };

    // SUBMIT DEL FORMULARIO
    $('form').on('submit', function (e) {
        e.preventDefault();
        actualizarInsumos();
        actualizarMedicamentos();

        const form = this;
        const formData = new FormData(form);

        fetch('guardar_protocolo.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(text => {
                try {
                    console.log("Respuesta completa del servidor:", text);
                    const data = JSON.parse(text);
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
                Swal.fire("Error", "Error al actualizar los datos.", "error");
            });
    });
});