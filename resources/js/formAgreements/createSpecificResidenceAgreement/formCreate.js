document.addEventListener('DOMContentLoaded', function () {

    // Obtener los elementos necesarios del DOM
    const companySelect = document.getElementById('companySelect');
    const confirmCompanyBtn = document.getElementById('confirmCompanyBtn');

    // Si necesitas, puedes activar el botón cuando el usuario seleccione alguna opción
    // por ejemplo, removiendo la clase 'd-none' si el select tiene un valor válido.
    companySelect.addEventListener('change', function () {
        if (this.value !== '') {
            confirmCompanyBtn.classList.remove('d-none');
        } else {
            confirmCompanyBtn.classList.add('d-none');
        }
    });

    // Agregar el listener en el botón "Confirmar empresa"
    confirmCompanyBtn.addEventListener('click', function () {
        // Obtener el valor seleccionado en el select
        const selectedId = companySelect.value;
        
        // Verificar que se haya seleccionado una empresa
        if (selectedId === '') {
            alert('Por favor, selecciona una empresa.');
            return;
        }

        console.log('Valor seleccionado:', selectedId);

        // Realizar la petición AJAX para buscar el convenio
        $.ajax({
            url: `/buscarConvenio/${selectedId}`,
            method: 'GET',
            success: function (data) {
                console.log(data);

                // Mostrar el formulario oculto
                $('#formEmpresaSeleccionada').removeClass('d-none');
                $('.containerButtonSearchCompany').remove();

                // Obtener el formulario dentro del contenedor y actualizar el input hidden de company_id
                const $form = $('#formEmpresaSeleccionada form');
                if ($form.find('input[name="company_id"]').length) {
                    $form.find('input[name="company_id"]').val(selectedId);
                } else {
                    $form.prepend(`<input type="hidden" name="company_id" value="${selectedId}">`);
                }

                // Actualizar el campo de contrato_id si existe
                if (data.contract_id) {
                    document.getElementById('contract_id_field').value = data.contract_id;
                console.log('ID del contrato:', data.contract_id);
                }
                // Insertar ddatos de la empresa en los campos del formulario
                if (data.company){ 
                    $form.find('input[name="companyName"]').val(data.company.denomination);
                    document.getElementById('companyId').value = data.company.id;
                    
                }

                if (data.representative_employee) {
                    const representativeName = data.representative_employee.name + ' ' + data.representative_employee.lastname;
                    $form.find('input[name="companyRepresentative"]').val(representativeName);
                }

                if (data.teacher) {
                    $form.find('input[name="tutorFacuName"]').val(data.teacher.name);
                    $form.find('input[name="tutorFacuLastName"]').val(data.teacher.lastname);
                    $form.find('input[name="tutorFacuDni"]').val(data.teacher.dni);
                }


                // Mostrar un mensaje de éxito al usuario
                $('#formEmpresaSeleccionada').prepend(`
                    <div id="mensajeExito" class="alert alert-success">
                        Contrato cargado correctamente.
                    </div>
                `);

                                // Esperar 3 segundos y luego eliminar el mensaje
                setTimeout(function() {
                    $('#mensajeExito').fadeOut(500, function() {
                        $(this).remove();
                    });
                }, 3000);
            },
            error: function () {
                alert('No se pudo obtener el contrato vinculado a la empresa.');
            }
        });
    });





});
