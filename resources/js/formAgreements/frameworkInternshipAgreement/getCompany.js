document.addEventListener("DOMContentLoaded", () => {
    const selectCompany = document.getElementById("selectCompany");

    selectCompany.addEventListener("change", function () {
        const companyId = this.value;
        if (!companyId) return;

        fetch(`/api/company/${companyId}`)
            .then((res) => res.json())
            .then((company) => {

                // Rellenar datos de la empresa
                document.querySelector('input[name="razon_social"]').value = company.denomination || "";
                document.querySelector('input[name="contraparte_cuit"]').value = company.cuit || "";
                document.querySelector('input[name="contraparte_rubro"]').value = company.company_category || "";

                if (company.scope === "nacional") {
                    document.getElementById("ambitoNacional").checked = true;
                } else if (company.scope === "internacional") {
                    document.getElementById("ambitoInternacional").checked = true;
                }

                document.querySelector('input[name="contact_empresa"]').value = company.denomination || "";
                document.querySelector('input[name="firma_empresa_razon_social"]').value = company.denomination || "";

                document.querySelector('input[name="pais"]').value = "Argentina";
                document.getElementById("provincia").value = company.provincia || "";
                document.getElementById("ciudad").value = company.city || "";
                document.querySelector('input[name="codigo_postal"]').value = company.postal_code || "";
                document.querySelector('input[name="calle"]').value = company.street || "";
                document.querySelector('input[name="nro_calle"]').value = company.number || "";

                // Cargar empleados de la empresa
                fetch(`/api/employees/${companyId}`)
                    .then((res) => res.json())
                    .then((employees) => {
                        const selectEmployeeContact = document.getElementById("selectEmployeeContact");
                        const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");

                        const contactSelectWrapper = document.getElementById("contactSelectWrapper");
                        const firmaSelectWrapper = document.getElementById("firmaSelectWrapper");

                        const contactFields = document.getElementById("contactManualFields");
                        const firmaFields = document.getElementById("firmaManualFields");

                        const noContactMsg = document.getElementById("noContactMsg");
                        const noFirmaMsg = document.getElementById("noFirmaMsg");

                        if (employees.length > 0) {
                            // ---- Modo SELECT: la empresa ya tiene empleados ----
                            // Mostrar selects, ocultar aviso de creación
                            contactSelectWrapper.classList.remove("d-none");
                            firmaSelectWrapper.classList.remove("d-none");
                            noContactMsg.classList.add("d-none");
                            noFirmaMsg.classList.add("d-none");

                            // Poner los campos de contacto como readonly (se llenarán al elegir del select)
                            setContactReadonly(true);
                            setFirmaReadonly(true);

                            // Limpiar y rellenar selects
                            selectEmployeeContact.innerHTML = '<option value="">Seleccione un representante de contacto</option>';
                            selectEmployeeFirma.innerHTML = '<option value="">Seleccione un representante de firma</option>';

                            employees.forEach((employee) => {
                                const option = `<option value="${employee.id}">${employee.lastname}, ${employee.name} - (DNI: ${employee.dni})</option>`;
                                selectEmployeeContact.innerHTML += option;
                                selectEmployeeFirma.innerHTML += option;
                            });

                        } else {
                            // ---- Modo CREACIÓN: no hay empleados, habilitar campos para llenar ----
                            contactSelectWrapper.classList.add("d-none");
                            firmaSelectWrapper.classList.add("d-none");
                            noContactMsg.classList.remove("d-none");
                            noFirmaMsg.classList.remove("d-none");

                            // Habilitar todos los campos para escritura
                            setContactReadonly(false);
                            setFirmaReadonly(false);

                            // Limpiar valores previos
                            clearContactFields();
                            clearFirmaFields();
                        }
                    })
                    .catch((error) => {
                        console.error("Error al obtener datos de los empleados:", error);
                    });
            })
            .catch((error) => {
                console.error("Error al obtener datos de la empresa:", error);
            });
    });
});

function setContactReadonly(readonly) {
    const fields = ["contact_nombre", "contact_apellido", "contact_dni", "contact_cuil", "contact_celular", "contact_email", "contact_cargo"];
    fields.forEach(name => {
        const el = document.querySelector(`input[name="${name}"]`);
        if (el) el.readOnly = readonly;
    });
}

function setFirmaReadonly(readonly) {
    const fields = ["firma_nombre", "firma_apellido", "firma_dni", "firma_celular", "firma_email", "firma_cargo"];
    fields.forEach(name => {
        const el = document.querySelector(`input[name="${name}"]`);
        if (el) el.readOnly = readonly;
    });
}

function clearContactFields() {
    ["contact_nombre", "contact_apellido", "contact_dni", "contact_cuil", "contact_celular", "contact_email", "contact_cargo"].forEach(name => {
        const el = document.querySelector(`input[name="${name}"]`);
        if (el) el.value = "";
    });
}

function clearFirmaFields() {
    ["firma_nombre", "firma_apellido", "firma_dni", "firma_celular", "firma_email", "firma_cargo"].forEach(name => {
        const el = document.querySelector(`input[name="${name}"]`);
        if (el) el.value = "";
    });
}
