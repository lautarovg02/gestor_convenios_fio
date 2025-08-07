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
    });