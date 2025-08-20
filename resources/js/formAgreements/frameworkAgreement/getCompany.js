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
        "contact_empresa",
        "firma_empresa_razon_social",
        "empresa_calle",
        "empresa_numero",
        "nro_calle",
        "empresa_codigo_postal",
        // OJO: provincia/ciudad NO las tocamos aquí; las maneja getCitiesAndProvinces.js
        "empresa_pais",
        "pais",
    ].forEach((k) => setVal(k, ""));
    setChecked("ambitoNacional", false);
    setChecked("ambitoInternacional", false);
}

function clearEmployees() {
    const selC = document.getElementById("selectEmployeeContact");
    const selF = document.getElementById("selectEmployeeFirma");
    if (selC)
        selC.innerHTML =
            '<option value="">Seleccione un representante de contacto</option>';
    if (selF)
        selF.innerHTML =
            '<option value="">Seleccione un representante de firma</option>';
}

// parte CUIT en 3 campos (acepta "XX-XXXXXXXX-X" o "XXXXXXXXXXX" o number)
function splitCUIT(cuitRaw) {
    const digits = String(cuitRaw ?? "").replace(/\D/g, "");
    const d =
        digits.length >= 11 ? digits.slice(0, 11) : digits.padStart(11, "0");
    return { prefijo: d.slice(0, 2), dni: d.slice(2, 10), dv: d.slice(10, 11) };
}

// ================== Carga de empresa + empleados ==================
async function loadCompany(companyId) {
    try {
        const res = await fetch(`/api/company/${companyId}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const company = await res.json();

        console.log("Datos de la empresa:", company);

        // CUIT dividido en 3 inputs (los de tu Blade)
        const { prefijo, dni, dv } = splitCUIT(company.cuit);
        setValAny(["cuit_prefijo"], prefijo);
        setValAny(["cuit_dni"], dni);
        setValAny(["cuit_dv"], dv);

        // Razón social / Rubro / Entidad
        setValAny(
            ["razon_social", "contraparte_razon_social"],
            company.denomination ?? company.company_name ?? ""
        );
        setValAny(
            ["contraparte_rubro", "rubro", "sector"],
            company.company_category ?? company.sector ?? ""
        );
        setValAny(["empresa_entidad", "entidad"], company.entity_name || "");

        // Ámbito (si tenés los radios)
        const scope = (company.scope ?? "").toLowerCase().trim();
        setChecked("ambitoNacional", scope.startsWith("nac"));
        setChecked("ambitoInternacional", scope.startsWith("int"));

        // Empresa visible en Contacto y Firma (no pisamos si vienen vacíos desde empleado)
        setValAny(
            ["contact_empresa", "empresa_contacto", "empresa"],
            company.company_name ?? company.denomination ?? ""
        );
        setValAny(
            ["firma_empresa_razon_social", "empresa_firma", "empresa"],
            company.denomination ?? company.company_name ?? ""
        );

        // Dirección (sin provincia/ciudad — las maneja geoUI)
        const dir = company?.direccion || company?.address || {};
        setValAny(
            ["empresa_calle", "calle"],
            dir.calle ?? company.street ?? ""
        );
        setValAny(
            ["empresa_numero", "nro_calle", "numero", "nro"],
            dir.numero ?? company.number ?? ""
        );
        setValAny(
            ["empresa_codigo_postal", "codigo_postal", "cp"],
            dir.codigo_postal ?? dir.zip ?? ""
        );
        setValAny(
            ["empresa_pais", "pais"],
            dir.pais ?? company?.country ?? "Argentina"
        );

        // Provincia / Ciudad: usar integración con tu loader para evitar bucles
        const provName =
            company?.province_name ?? company?.direccion?.provincia ?? "";
        const cityName = company?.city_name ?? company?.direccion?.ciudad ?? "";
        if (provName || cityName) {
            // getCitiesAndProvinces.js expone geoUI.selectByNames
            if (window.geoUI?.selectByNames) {
                await window.geoUI.selectByNames(provName, cityName);
            }
        }

        // Empleados (solo representantes)
        await loadEmployees(companyId);
    } catch (error) {
        console.error("Error al obtener datos de la empresa:", error);
    }
}

async function loadEmployees(companyId) {
    const selectEmployeeContact = document.getElementById(
        "selectEmployeeContact"
    );
    const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");

    if (selectEmployeeContact) {
        selectEmployeeContact.disabled = true;
        selectEmployeeContact.innerHTML =
            '<option value="">Cargando representantes de contacto...</option>';
    }
    if (selectEmployeeFirma) {
        selectEmployeeFirma.disabled = true;
        selectEmployeeFirma.innerHTML =
            '<option value="">Cargando representantes de firma...</option>';
    }

    try {
        const res = await fetch(`/api/employees/${companyId}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const employees = await res.json();

        const makeLabel = (e) => {
            if (e.text) return e.text;
            const ape = e.lastname ?? "";
            const nom = e.name ?? "";
            const cargo = e.position ? ` — ${e.position}` : "";
            const dni = e.dni ? ` — DNI ${e.dni}` : "";
            return `${ape}, ${nom}${cargo}${dni}`.trim();
        };

        if (selectEmployeeContact) {
            selectEmployeeContact.disabled = false;
            selectEmployeeContact.innerHTML =
                '<option value="">Seleccione un representante de contacto</option>';
        }
        if (selectEmployeeFirma) {
            selectEmployeeFirma.disabled = false;
            selectEmployeeFirma.innerHTML =
                '<option value="">Seleccione un representante de firma</option>';
        }

        if (Array.isArray(employees) && employees.length) {
            employees.forEach((e) => {
                const optC = document.createElement("option");
                optC.value = e.id;
                optC.textContent = makeLabel(e);
                const optF = optC.cloneNode(true);
                if (selectEmployeeContact)
                    selectEmployeeContact.appendChild(optC);
                if (selectEmployeeFirma) selectEmployeeFirma.appendChild(optF);
            });
        } else {
            if (selectEmployeeContact)
                selectEmployeeContact.innerHTML =
                    '<option value="">No hay representantes para esta empresa</option>';
            if (selectEmployeeFirma)
                selectEmployeeFirma.innerHTML =
                    '<option value="">No hay representantes para esta empresa</option>';
        }
    } catch (error) {
        console.error("Error al obtener datos de los empleados:", error);
        clearEmployees();
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
});
