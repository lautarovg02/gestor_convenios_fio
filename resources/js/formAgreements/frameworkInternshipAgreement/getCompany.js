document.addEventListener("DOMContentLoaded", () => {
    const selectCompany = document.getElementById("selectCompany");

    selectCompany.addEventListener("change", function () {
        const companyId = this.value;
        if (!companyId) return;

        const selectEmployeeContact = document.getElementById(
            "selectEmployeeContact"
        );

        const selectEmployeeFirma = document.getElementById(
            "selectEmployeeFirma"
        );

        fetch(`/api/company/${companyId}`)
            .then((res) => res.json())
            .then((company) => {

                console.log(company);
                // Razón Social
                document.querySelector('input[name="razon_social"]').value =
                    company.denomination || "";

                // CUIT

                document.querySelector('input[name="contraparte_cuit"]').value =
                    company.cuit || "";

                // Rubro
                document.querySelector(
                    'input[name="contraparte_rubro"]'
                ).value = company.sector || "";

                // Ámbito
                if (company.scope === "nacional") {
                    document.getElementById("ambitoNacional").checked = true;
                } else if (company.scope === "internacional") {
                    document.getElementById(
                        "ambitoInternacional"
                    ).checked = true;
                }

                //carga el input de "Empresa" del empleado de contacto y de firma
                    document.querySelector('input[name="contact_empresa"]').value =
                    company.denomination || "";

                document.querySelector('input[name="firma_empresa_razon_social"]').value =
                    company.denomination || "";

                document.querySelector('input[name="pais"]').value = "Argentina";
                document.getElementById("provincia").value = company.provincia || "";
                document.getElementById("provincia").dispatchEvent(new Event("change"));

                setTimeout(() => {
                    document.getElementById("ciudad").value = company.city || "";
                }, 5000); // pequeño delay para esperar a que se carguen las ciudades

                document.querySelector('input[name="calle"]').value = company.street || "";
                document.querySelector('input[name="nro_calle"]').value = company.number || "";

                fetch(`/api/employees/${companyId}`)
                    .then((res) => res.json())
                    .then((employees) => {
                        
                        selectEmployeeContact.innerHTML =
                            '<option value="">Seleccione un representante de contacto</option>';

                        selectEmployeeFirma.innerHTML =
                            '<option value="">Seleccione un representante de firma</option>';
                        
                            employees.forEach((employee) => {
                            
                            selectEmployeeContact.innerHTML += `
                            <option value="${employee.id}">${employee.name} ${employee.lastname} - (DNI: ${employee.dni})</option>`;

                            selectEmployeeFirma.innerHTML += `
                            <option value="${employee.id}">${employee.name} ${employee.lastname} - (DNI: ${employee.dni})</option>`;
                        });

                    })
                    .catch((error) => {
                        console.error(
                            "Error al obtener datos de los empleados:",
                            error
                        );
                    });
            })
            .catch((error) => {
                console.error("Error al obtener datos de la empresa:", error);
            });
    });
});
