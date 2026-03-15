document.addEventListener("DOMContentLoaded", () => {
    const selectCompany = document.getElementById("selectCompany");

    selectCompany.addEventListener("change", function () {
        const companyId = this.value;

        const contreparteFieldset = document.getElementById("contreparteFieldset");
        const direccionFieldset = document.getElementById("direccionFieldset");

        if (!companyId) {
            if (contreparteFieldset) contreparteFieldset.disabled = true;
            if (direccionFieldset) direccionFieldset.disabled = true;
            setCompanyFieldsReadonly(false);
            return;
        }

        // Enable the sections now that a company is selected
        if (contreparteFieldset) contreparteFieldset.disabled = false;
        if (direccionFieldset) direccionFieldset.disabled = false;

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

                if (company.confidentiality == 1) {
                    const confSi = document.getElementById("confSi");
                    if (confSi) confSi.checked = true;
                } else if (company.confidentiality == 0) {
                    const confNo = document.getElementById("confNo");
                    if (confNo) confNo.checked = true;
                }

                document.querySelector('input[name="contact_empresa"]').value = company.denomination || "";
                document.querySelector('input[name="firma_empresa_razon_social"]').value = company.denomination || "";

                document.querySelector('input[name="pais"]').value = "Argentina";
                document.getElementById("provincia").value = company.provincia || "";
                document.getElementById("ciudad").value = company.city || "";
                document.querySelector('input[name="codigo_postal"]').value = company.postal_code || "";
                document.querySelector('input[name="calle"]').value = company.street || "";
                document.querySelector('input[name="nro_calle"]').value = company.number || "";

                setCompanyFieldsReadonly(true);

                // Cargar empleados de la empresa
                fetch(`/api/employees/${companyId}`)
                    .then((res) => res.json())
                    .then((employees) => {
                        const selectEmployeeContact = document.getElementById("selectEmployeeContact");
                        const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");
                        const selectEmployeeTitular = document.getElementById("selectEmployeeTitular");

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
                            if(selectEmployeeTitular) selectEmployeeTitular.innerHTML = '<option value="">Seleccione un representante titular</option>';

                            employees.forEach((employee) => {
                                const optionContactFirma = `<option value="${employee.id}">${employee.lastname}, ${employee.name} - (DNI: ${employee.dni})</option>`;
                                const optionTitular = `<option value="${employee.lastname}, ${employee.name}">${employee.lastname}, ${employee.name} - (DNI: ${employee.dni})</option>`;
                                
                                selectEmployeeContact.innerHTML += optionContactFirma;
                                selectEmployeeFirma.innerHTML += optionContactFirma;
                                if(selectEmployeeTitular) selectEmployeeTitular.innerHTML += optionTitular;
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
                            
                            if (selectEmployeeTitular) {
                                selectEmployeeTitular.innerHTML = '<option value="">Esta empresa no tiene empleados registrados</option>';
                            }
                        }
                    })
                    .catch((error) => {
                        console.error("Error al obtener datos de los empleados:", error);
                    });
            });
    });

    // Habilitar fieldsets antes de enviar el formulario para que lleguen al servidor
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", function () {
            const contreparteFieldset = document.getElementById("contreparteFieldset");
            const direccionFieldset = document.getElementById("direccionFieldset");
            if (contreparteFieldset) contreparteFieldset.disabled = false;
            if (direccionFieldset) direccionFieldset.disabled = false;
        });
    }
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

function setCompanyFieldsReadonly(readonly) {
    const fields = [
        "razon_social", "contraparte_cuit", "contraparte_rubro",
        "contact_empresa", "firma_empresa_razon_social",
        "pais", "provincia", "ciudad", "codigo_postal", "calle", "nro_calle"
    ];
    fields.forEach(name => {
        const el = document.querySelector(`input[name="${name}"]`) || document.getElementById(name);
        if (el) el.readOnly = readonly;
    });

    // Radios (usamos pointer-events para que no se puedan cambiar pero se envíen igual)
    const radios = ["ambitoNacional", "ambitoInternacional", "confSi", "confNo"];
    radios.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.pointerEvents = readonly ? 'none' : 'auto';
        }
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const selectEmployeeTitular = document.getElementById("selectEmployeeTitular");
});
