document.addEventListener("DOMContentLoaded", () => {
    document
        .getElementById("provincia")
        .addEventListener("change", function () {
            const provincia = this.value;
            const ciudadSelect = document.getElementById("ciudad");

            ciudadSelect.innerHTML = "<option>Cargando ciudades...</option>";
            ciudadSelect.disabled = true;

            if (provincia) {
                fetch(`/cities?provincia=${encodeURIComponent(provincia)}`)
                    .then((response) => response.json())
                    .then((data) => {
                        ciudadSelect.innerHTML =
                            '<option value="">Seleccione una ciudad</option>';
                        data.forEach((ciudad) => {
                            ciudadSelect.innerHTML += `<option value="${ciudad.nombre}">${ciudad.nombre}</option>`;
                        });
                        ciudadSelect.disabled = false;
                    })
                    .catch((error) => {
                        ciudadSelect.innerHTML =
                            "<option>Error al cargar</option>";
                        ciudadSelect.disabled = true;
                        console.error(error);
                    });
            } else {
                ciudadSelect.innerHTML =
                    '<option value="">Seleccione una ciudad</option>';
                ciudadSelect.disabled = true;
            }
        });

    //------------------------------------------------auto completar datos de representante de firma-----------------------

    function copyContactToSignature() {
        document.getElementById("firma_nombre").value = document.getElementById("contact_nombre").value;
        document.getElementById("firma_apellido").value = document.getElementById("contact_apellido").value;
        document.getElementById("firma_dni").value = document.getElementById("contact_dni").value;
        document.getElementById("firma_razon_social").value = document.getElementById("contact_empresa").value;
        document.getElementById("firma_email").value = document.getElementById("contact_email").value;
        document.getElementById("firma_cargo").value = document.getElementById("contact_cargo").value;

        // Bloquear inputs
        bloquearInputsFirma(true);
    }

    function clearSignatureData() {
        document.getElementById("firma_nombre").value = "";
        document.getElementById("firma_apellido").value = "";
        document.getElementById("firma_dni").value = "";
        document.getElementById("firma_razon_social").value = "";
        document.getElementById("firma_email").value = "";
        document.getElementById("firma_cargo").value = "";

        // Desbloquear inputs
        bloquearInputsFirma(false);
    }

    function bloquearInputsFirma(bloquear) {
        const campos = [
            "firma_nombre",
            "firma_apellido",
            "firma_dni",
            "firma_razon_social",
            "firma_email",
            "firma_cargo",
        ];
        campos.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.readOnly = bloquear;  // true bloquea, false desbloquea
            }
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

    document.getElementById("sameRepresentative").addEventListener("change", toggleSignatureData);

});
