

    //------------------------------------------------auto completar datos de representante de firma-----------------------
document.addEventListener("DOMContentLoaded", () => {
    function copyContactToSignature() {
        document.getElementById("selectEmployeeFirma").value =
            document.getElementById("selectEmployeeContact").value;
        document.getElementById("firma_nombre").value =
            document.getElementById("contact_nombre").value;
        document.getElementById("firma_apellido").value =
            document.getElementById("contact_apellido").value;
        document.getElementById("firma_dni").value =
            document.getElementById("contact_dni").value;
        document.getElementById("firma_razon_social").value =
            document.getElementById("contact_empresa").value;
        document.getElementById("firma_email").value =
            document.getElementById("contact_email").value;
        document.getElementById("firma_cargo").value =
            document.getElementById("contact_cargo").value;

        // Bloquear inputs
        bloquearInputsFirma(true);
    }

    function clearSignatureData() {
        document.getElementById("selectEmployeeFirma").value = "";
        document.getElementById("firma_nombre").value = "";
        document.getElementById("firma_apellido").value = "";
        document.getElementById("firma_dni").value = "";
        document.getElementById("firma_razon_social").value = "";
        document.getElementById("firma_email").value = "";
        document.getElementById("firma_cargo").value = "";

        // Desbloquear inputs
        bloquearInputsFirma(false);
    }

    function clearContactData() {
        document.getElementById("selectEmployeeContact").value = "";
        document.getElementById("contact_nombre").value = "";
        document.getElementById("contact_apellido").value = "";
        document.getElementById("contact_dni").value = "";
        document.getElementById("contact_empresa").value = "";
        document.getElementById("contact_email").value = "";
        document.getElementById("contact_cargo").value = "";
        document.getElementById("contact_celular").value = "";
        document.getElementById("contact_cuil").value = "";

        clearSignatureData();
    }

    function bloquearInputsFirma(bloquear) {
        const campos = [
            "selectEmployeeFirma",
            "firma_nombre",
            "firma_apellido",
            "firma_dni",
            "firma_razon_social",
            "firma_email",
            "firma_cargo",
        ];
        campos.forEach((id) => {
            const input = document.getElementById(id);
            if (input.tagName === "SELECT") input.disabled = bloquear;
            else input.readOnly = bloquear; // true bloquea, false desbloquea
        });
    }

    function toggleSignatureData() {
        const checkbox = document.getElementById("sameRepresentative");
        if (checkbox.checked) {
            copyContactToSignature();
        } else {
            clearSignatureData();
        }
    }
    document
        .getElementById("selectCompany")
        .addEventListener("change", clearContactData);
    document
        .getElementById("sameRepresentative")
        .addEventListener("change", toggleSignatureData);
});
