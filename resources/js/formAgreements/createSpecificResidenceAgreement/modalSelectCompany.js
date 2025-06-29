document.addEventListener("DOMContentLoaded", function () {
$(document).ready(function () {
    const $select = $('#companySelect');
    const $confirmBtn = $('#confirmCompanyBtn');
    const $formContainer = $('#formEmpresaSeleccionada');

    // Inicializar select2
    $select.select2({
        dropdownParent: $('#searchModal'),
        placeholder: 'Buscar empresa...',
        allowClear: true
    });

    // Mostrar botón al seleccionar
    $select.on('change', function () {
        if ($select.val()) {
            $confirmBtn.removeClass('d-none');
        } else {
            $confirmBtn.addClass('d-none');
        }
    });

    // Al confirmar la empresa, mostrar el formulario y setear el valor
    $confirmBtn.on('click', function () {
        const selectedId = $select.val();

        // Mostrar formulario oculto
        $formContainer.removeClass('d-none');

        // Si querés guardar el ID de la empresa seleccionada en un campo oculto:
        if (!$('#formEmpresaSeleccionada input[name="company_id"]').length) {
            $formContainer.prepend(`
                <input type="hidden" name="company_id" value="${selectedId}">
            `);
        } else {
            $('#formEmpresaSeleccionada input[name="company_id"]').val(selectedId);
        }
    });
});

$('#confirmCompanyBtn').on('click', function () {
    const selectedId = $('#companySelect').val();

    // Mostrar el formulario
    $('#formEmpresaSeleccionada').removeClass('d-none');

    // Eliminar el contenedor del botón de búsqueda del DOM
    $('.containerButtonSearchCompany').remove();

    // Agregar o actualizar input hidden con company_id
    const $form = $('#formEmpresaSeleccionada form'); // Asegúrate de apuntar al form
    const $existingInput = $form.find('input[name="company_id"]');

    if ($existingInput.length) {
        $existingInput.val(selectedId);
    } else {
        $form.prepend(`<input type="hidden" name="company_id" value="${selectedId}">`);
    }
});
});
