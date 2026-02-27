document.addEventListener("DOMContentLoaded", () => {

    const selectEmployeeContact = document.getElementById("selectEmployeeContact");
    const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");

    // --- Limpiar error de empresa al cambiar DNI ---
    function clearNextError(inputEl) {
        if (!inputEl) return;
        inputEl.addEventListener("input", () => {
            // Busca el primer .text-danger hermano siguiente
            let next = inputEl.nextElementSibling;
            while (next) {
                if (next.classList.contains("text-danger")) {
                    next.textContent = "";
                    break;
                }
                next = next.nextElementSibling;
            }
        });
    }

    clearNextError(document.querySelector('input[name="contact_dni"]'));
    clearNextError(document.querySelector('input[name="firma_dni"]'));

    // --- Selects de empleados ---
    if (selectEmployeeContact) {
        selectEmployeeContact.addEventListener("change", () => {
            const selectedOption = selectEmployeeContact.value;
            if (!selectedOption) return;
            fetch(`/api/employee/${selectedOption}`)
                .then((response) => response.json())
                .then((employee) => {
                    document.querySelector('input[name="contact_nombre"]').value = employee.name || "";
                    document.querySelector('input[name="contact_apellido"]').value = employee.lastname || "";
                    document.querySelector('input[name="contact_dni"]').value = employee.dni || "";
                    document.querySelector('input[name="contact_email"]').value = employee.email || "";
                    document.querySelector('input[name="contact_celular"]').value = employee.phones[0] || "";
                    document.querySelector('input[name="contact_cargo"]').value = employee.position || "";
                    document.querySelector('input[name="contact_cuil"]').value = employee.cuil || "";
                })
                .catch((error) => {
                    console.error("Error al obtener los datos del empleado:", error);
                });
        });
    }

    if (selectEmployeeFirma) {
        selectEmployeeFirma.addEventListener("change", () => {
            const selectedOption = selectEmployeeFirma.value;
            if (!selectedOption) return;
            fetch(`/api/employee/${selectedOption}`)
                .then((response) => response.json())
                .then((employee) => {
                    document.querySelector('input[name="firma_nombre"]').value = employee.name || "";
                    document.querySelector('input[name="firma_apellido"]').value = employee.lastname || "";
                    document.querySelector('input[name="firma_dni"]').value = employee.dni || "";
                    document.querySelector('input[name="firma_email"]').value = employee.email || "";
                    document.querySelector('input[name="firma_celular"]').value = employee.phones[0] || "";
                    document.querySelector('input[name="firma_cargo"]').value = employee.position || "";
                    document.querySelector('input[name="firma_cuil"]').value = employee.cuil || "";
                })
                .catch((error) => {
                    console.error("Error al obtener los datos del empleado:", error);
                });
        });
    }
});
