// ================== Helpers (top-level) ==================
function cssEscape(str) {
    return String(str).replace(/(["\\])/g, "\\$1");
}

function setVal(key, value) {
    let el =
        document.getElementById(key) ||
        document.querySelector(`[name="${cssEscape(key)}"]`);
    if (!el) return false;
    el.value = value ?? "";
    return true;
}

// acepta varios posibles ids/names y setea en el primero que exista
function setValAny(candidates, value) {
    for (const k of candidates) if (setVal(k, value)) return true;
    return false;
}

function setChecked(id, checked) {
    const el = document.getElementById(id);
    if (!el) return false;
    el.checked = !!checked;
    return true;
}

function clearCompanyFields() {
    [
        "razon_social",
        "cuit_prefijo",
        "cuit_dni",
        "cuit_dv",
        "contraparte_rubro",
        "dedicacion",
        "contact_empresa",
        "firma_empresa_razon_social",
        "empresa_calle",
        "empresa_numero",
        "nro_calle",
        "empresa_codigo_postal",
        "empresa_pais",
        "pais",
    ].forEach((k) => setVal(k, ""));
    setChecked("ambitoNacional", false);
    setChecked("ambitoInternacional", false);
    
    const contreparteFieldset = document.getElementById("contreparteFieldset");
    const direccionFieldset = document.getElementById("direccionFieldset");
    if (contreparteFieldset) contreparteFieldset.disabled = true;
    if (direccionFieldset) direccionFieldset.disabled = true;

    setCompanyFieldsReadonly(false);
}

function clearEmployees() {
    const selC = document.getElementById("selectEmployeeContact");
    const selF = document.getElementById("selectEmployeeFirma");
    if (selC) selC.innerHTML = '<option value="">Seleccione un representante de contacto</option>';
    if (selF) selF.innerHTML = '<option value="">Seleccione un representante de firma</option>';
}

// parte CUIT en 3 campos (acepta "XX-XXXXXXXX-X" o "XXXXXXXXXXX" o number)
function splitCUIT(cuitRaw) {
    const digits = String(cuitRaw ?? "").replace(/\D/g, "");
    const d = digits.length >= 11 ? digits.slice(0, 11) : digits.padStart(11, "0");
    return { prefijo: d.slice(0, 2), dni: d.slice(2, 10), dv: d.slice(10, 11) };
}

// ================== Toggle modo empleado ==================
function toggleEmployeeMode(hasEmployees) {
    const contactSelectWrapper = document.getElementById("contactSelectWrapper");
    const firmaSelectWrapper = document.getElementById("firmaSelectWrapper");
    const noContactMsg = document.getElementById("noContactMsg");
    const noFirmaMsg = document.getElementById("noFirmaMsg");

    if (hasEmployees) {
        contactSelectWrapper?.classList.remove("d-none");
        firmaSelectWrapper?.classList.remove("d-none");
        noContactMsg?.classList.add("d-none");
        noFirmaMsg?.classList.add("d-none");
        setEmployeeFieldsReadonly(true);
    } else {
        contactSelectWrapper?.classList.add("d-none");
        firmaSelectWrapper?.classList.add("d-none");
        noContactMsg?.classList.remove("d-none");
        noFirmaMsg?.classList.remove("d-none");
        setEmployeeFieldsReadonly(false);
        clearEmployeeTextFields();
        
        const selectEmployeeTitular = document.getElementById("selectEmployeeTitular");
        if (selectEmployeeTitular) {
            selectEmployeeTitular.disabled = false;
            selectEmployeeTitular.innerHTML = '<option value="">Esta empresa no tiene empleados registrados</option>';
        }
    }
}

function setEmployeeFieldsReadonly(readonly) {
    const names = [
        "contact_nombre", "contact_apellido", "contact_dni",
        "contact_celular", "contact_email", "contact_cargo",
        "cuil_prefijo", "cuil_dni", "cuil_dv",
        "firma_nombre", "firma_apellido", "firma_dni",
        "firma_celular", "firma_email", "firma_cargo",
    ];
    names.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) el.readOnly = readonly;
    });
}

function clearEmployeeTextFields() {
    const names = [
        "contact_nombre", "contact_apellido", "contact_dni",
        "contact_celular", "contact_email", "contact_cargo",
        "cuil_prefijo", "cuil_dni", "cuil_dv",
        "firma_nombre", "firma_apellido", "firma_dni",
        "firma_celular", "firma_email", "firma_cargo",
    ];
    names.forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) el.value = "";
    });
}

function setCompanyFieldsReadonly(readonly) {
    const fields = [
        "razon_social", "contraparte_razon_social",
        "cuit_prefijo", "cuit_dni", "cuit_dv",
        "contraparte_rubro", "rubro", "sector", "dedicacion",
        "empresa_entidad", "entidad",
        "contact_empresa", "empresa_contacto", "empresa",
        "firma_empresa_razon_social", "empresa_firma",
        "empresa_calle", "calle",
        "empresa_numero", "nro_calle", "numero", "nro",
        "postal_code", "codigo_postal", "cp",
        "empresa_pais", "pais", "provincia", "ciudad"
    ];
    fields.forEach(f => {
        const el = document.getElementById(f) || document.querySelector(`[name="${f}"]`);
        if (el) el.readOnly = readonly;
    });

    // Radios (usamos pointer-events para que no se puedan cambiar pero se envíen igual)
    const radios = ["ambitoNacional", "ambitoInternacional", "confSi", "confNo"];
    radios.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.pointerEvents = readonly ? 'none' : 'auto';
            // el.style.opacity = readonly ? '1' : '1'; 
            // Opcional: el.parentElement.style.opacity = readonly ? '0.8' : '1';
        }
    });
}

// ================== Carga de empresa + empleados ==================
async function loadCompany(companyId) {
    try {
        const res = await fetch(`/api/company/${companyId}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const company = await res.json();

        const contreparteFieldset = document.getElementById("contreparteFieldset");
        const direccionFieldset = document.getElementById("direccionFieldset");
        if (contreparteFieldset) contreparteFieldset.disabled = false;
        if (direccionFieldset) direccionFieldset.disabled = false;

        const { prefijo, dni, dv } = splitCUIT(company.cuit);
        setValAny(["cuit_prefijo"], prefijo);
        setValAny(["cuit_dni"], dni);
        setValAny(["cuit_dv"], dv);

        setValAny(["razon_social", "contraparte_razon_social"], company.denomination ?? company.company_name ?? "");
        setValAny(["contraparte_rubro", "rubro", "sector"], company.company_category ?? company.sector ?? "");
        setValAny(["dedicacion"], company.dedicacion ?? "");
        setValAny(["empresa_entidad", "entidad"], company.entity_name || "");

        const scope = (company.scope ?? "").toLowerCase().trim();
        setChecked("ambitoNacional", scope.startsWith("nac"));
        setChecked("ambitoInternacional", scope.startsWith("int"));

        if (company.confidentiality !== undefined && company.confidentiality !== null) {
            setChecked("confSi", company.confidentiality == 1);
            setChecked("confNo", company.confidentiality == 0);
        }

        setValAny(["contact_empresa", "empresa_contacto", "empresa"], company.company_name ?? company.denomination ?? "");
        setValAny(["firma_empresa_razon_social", "empresa_firma", "empresa"], company.denomination ?? company.company_name ?? "");

        const dir = company?.direccion || company?.address || {};
        setValAny(["empresa_calle", "calle"], dir.calle ?? company.street ?? "");
        setValAny(["empresa_numero", "nro_calle", "numero", "nro"], dir.numero ?? company.number ?? "");
        setValAny(["postal_code", "codigo_postal", "cp"], dir.postal_code ?? dir.zip ?? "");
        setValAny(["empresa_pais", "pais"], dir.pais ?? company?.country ?? "Argentina");

        const provName = company?.provincia ?? company?.direccion?.provincia ?? "";
        const cityName = company?.city ?? company?.direccion?.ciudad ?? "";
        const postal_code = company?.postal_code ?? "";

        if (postal_code) setVal("postal_code", postal_code);
        if (provName) setVal("provincia", provName);
        if (cityName) setVal("ciudad", cityName);

        setCompanyFieldsReadonly(true);

        await loadEmployees(companyId);
    } catch (error) {
        console.error("Error al obtener datos de la empresa:", error);
    }
}

async function loadEmployees(companyId) {
    const selectEmployeeContact = document.getElementById("selectEmployeeContact");
    const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");
    const selectEmployeeTitular = document.getElementById("selectEmployeeTitular");

    if (selectEmployeeContact) {
        selectEmployeeContact.disabled = true;
        selectEmployeeContact.innerHTML = '<option value="">Cargando representantes de contacto...</option>';
    }
    if (selectEmployeeFirma) {
        selectEmployeeFirma.disabled = true;
        selectEmployeeFirma.innerHTML = '<option value="">Cargando representantes de firma...</option>';
    }
    if (selectEmployeeTitular) {
        selectEmployeeTitular.disabled = true;
        selectEmployeeTitular.innerHTML = '<option value="">Cargando titulares...</option>';
    }

    try {
        const res = await fetch(`/api/employees/${companyId}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const employees = await res.json();

        const makeLabel = (e) => {
            if (e.text) return e.text;
            const ape = e.lastname ?? "";
            const nom = e.name ?? "";
            const dni = e.dni ? ` - (DNI: ${e.dni})` : "";
            return `${ape}, ${nom}${dni}`.trim();
        };

        if (selectEmployeeContact) {
            selectEmployeeContact.disabled = false;
            selectEmployeeContact.innerHTML = '<option value="">Seleccione un representante de contacto</option>';
        }
        if (selectEmployeeFirma) {
            selectEmployeeFirma.disabled = false;
            selectEmployeeFirma.innerHTML = '<option value="">Seleccione un representante de firma</option>';
        }
        if (selectEmployeeTitular) {
            selectEmployeeTitular.disabled = false;
            selectEmployeeTitular.innerHTML = '<option value="">Seleccione un representante titular</option>';
        }

        if (Array.isArray(employees) && employees.length) {
            employees.forEach((e) => {
                const optC = document.createElement("option");
                optC.value = e.id;
                optC.textContent = makeLabel(e);
                
                const optT = document.createElement("option");
                const nameStr = (e.lastname ?? "") + ", " + (e.name ?? "");
                optT.value = nameStr;
                optT.textContent = makeLabel(e);
                
                const optF = optC.cloneNode(true);
                
                if (selectEmployeeContact) selectEmployeeContact.appendChild(optC);
                if (selectEmployeeFirma) selectEmployeeFirma.appendChild(optF);
                if (selectEmployeeTitular) selectEmployeeTitular.appendChild(optT);
            });
            toggleEmployeeMode(true);
        } else {
            toggleEmployeeMode(false);
        }
    } catch (error) {
        console.error("Error al obtener datos de los empleados:", error);
        clearEmployees();
        toggleEmployeeMode(false);
    }
}

// ================== Listeners ==================
document.addEventListener("DOMContentLoaded", () => {
    const selectCompany = document.getElementById("selectCompany");
    
    if (!selectCompany) return;

    if (selectCompany.value) loadCompany(selectCompany.value);

    selectCompany.addEventListener("change", function () {
        const companyId = this.value;
        if (!companyId) {
            clearCompanyFields();
            clearEmployees();
            return;
        }
        loadCompany(companyId);
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
