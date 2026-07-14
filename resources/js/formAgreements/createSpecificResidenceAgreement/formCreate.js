document.addEventListener("DOMContentLoaded", function () {
    // Función para validar que sean solo dígitos y máximo 8 caracteres
    window.validarDigitos = function (input) {
        input.value = input.value.replace(/\D/g, "").slice(0, 8);
    };

    // Elementos del DOM
    const companySelect = document.getElementById("selectCompany");
    const selectEmployee = document.getElementById("selectEmployee");

    // Evento de cambio en el select de empresas
    companySelect.addEventListener("change", function () {
        const selectedId = companySelect.value;

        if (selectedId === "") {
            alert("Por favor, selecciona una empresa.");
            return;
        }

        console.log("Valor seleccionado:", selectedId);

        // Obtener datos de la empresa
        fetch(`/api/buscarConvenio/${selectedId}`)
            .then((res) => res.json())
            .then((data) => {
                const $form = $("#formEmpresaSeleccionada form");

                // Campo oculto de company_id
                if ($form.find('input[name="company_id"]').length) {
                    $form.find('input[name="company_id"]').val(selectedId);
                } else {
                    $form.prepend(
                        `<input type="hidden" name="company_id" value="${selectedId}">`
                    );
                }

                // Si existe contrato
                if (data.contract_id) {
                    document.getElementById("contract_id_field").value =
                        data.contract_id;
                    console.log("ID del contrato:", data.contract_id);
                }

                // Datos de empresa
                if (data.company) {
                    $form.find('input[name="companyName"]').val(data.company.denomination);
                    document.getElementById("companyId").value = data.company.id;
                }

                // Representante de la empresa
                if (data.representative_employee) {
                    const representativeName =
                        `${data.representative_employee.name} ${data.representative_employee.lastname}`;
                    $form.find('input[name="companyRepresentative"]').val(representativeName);
                }

                // Tutor
                if (data.teacher) {
                    $form.find('input[name="tutorFacuName"]').val(data.teacher.name).prop('readonly', true);
                    $form.find('input[name="tutorFacuLastName"]').val(data.teacher.lastname).prop('readonly', true);
                    $form.find('input[name="tutorFacuDni"]').val(data.teacher.dni).prop('readonly', true);
                }

                // Make company fields readonly
                $form.find('input[name="companyName"]').prop('readonly', true);
                $form.find('input[name="companyRepresentative"]').prop('readonly', true);

                // Mensaje de éxito
                $("#formEmpresaSeleccionada").prepend(`
                    <div id="mensajeExito" class="alert alert-success">
                        Contrato cargado correctamente.
                    </div>
                `);

                // Cargar empleados de la empresa seleccionada
                fetch(`/api/employees/${selectedId}`)
                    .then((res) => res.json())
                    .then((employees) => {
                        selectEmployee.innerHTML =
                            '<option value="">Seleccione un representante de contacto</option>';

                        employees.forEach((employee) => {
                            selectEmployee.innerHTML += `
                                <option value="${employee.id}">
                                    ${employee.name} ${employee.lastname} - (DNI: ${employee.dni})
                                </option>
                            `;
                        });
                    })
                    .catch((error) => {
                        console.error("Error al obtener datos de los empleados:", error);
                    });

                // Quitar mensaje después de 3s
                setTimeout(function () {
                    $("#mensajeExito").fadeOut(500, function () {
                        $(this).remove();
                    });
                }, 3000);
            })
            .catch((error) => {
                console.error("Error al obtener datos de la empresa:", error);
            });
    });
});
