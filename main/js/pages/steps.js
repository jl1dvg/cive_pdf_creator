$(document).ready(function () {
    $(".tab-wizard").validate({
        onsubmit: false,  // Desactivar el envío automático
    });

    // Inicializar el plugin de steps
    $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "none",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onFinished: function (event, currentIndex) {
            // Verificar si el formulario es válido manualmente
            if ($(".tab-wizard").valid()) {
                const form = document.querySelector('.tab-wizard');
                const formData = new FormData(form);

                fetch('actualizar_procedimiento.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta de la red');
                        }
                        return response.json();  // Asegúrate de que la respuesta sea JSON
                    })
                    .then(data => {
                        if (data.success) {
                            swal("Datos Actualizados!", data.message, "success");
                        } else {
                            swal("Error", data.message, "error");
                        }
                    })
                    .catch(error => {
                        console.error('Error al actualizar los datos:', error);
                        swal("Error", "Ocurrió un error al actualizar los datos. Por favor, intenta nuevamente.", "error");
                    });
            } else {
                swal("Error", "Por favor, completa los campos obligatorios.", "error");
            }
        }
    });
});