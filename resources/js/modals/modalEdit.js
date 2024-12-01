// resources/js/modals/modalEdit.js

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-edit');
    const departmentNameElem = document.getElementById('department-name');
    const directorNameElem = document.getElementById('director-name');
    const directorLastnameElem = document.getElementById('director-lastname');
    const directorIdInput = document.getElementById('director-id-input');
    const departmentNameInput = document.getElementById('modal-department-name');
    const directorSelect = document.getElementById('director_id'); // El campo select para el director en el formulario

    // Escuchar cuando el modal se muestra
    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // El botón que activó el modal

        // Obtener los datos de los atributos data-* del botón
        const departmentName = button.getAttribute('data-department-name');
        const directorId = button.getAttribute('data-director-id');
        const directorName = button.getAttribute('data-director-name');
        const directorLastname = button.getAttribute('data-director-lastname');

        // Llenar los campos del modal con los datos obtenidos
        departmentNameElem.textContent = departmentName;
        directorNameElem.textContent = directorName;
        directorLastnameElem.textContent = directorLastname;

        // Llenar el input oculto con el id del director
        directorIdInput.value = directorId;

        //Actualizar el campo 'name' en el form original
        departmentNameInput.value = departmentName  // Actualiza el valor del campo "name" en el formulario de edición
    });
    // Sincronizar el cambio en el campo de "name" con el botón de guardar del modal
    const nameInput = document.getElementById('name');
    const saveButton = document.querySelector('[data-bs-target="#modal-edit"]'); // El botón que abre el modal

    // Función para actualizar el atributo data-department-name cuando el nombre cambie
    nameInput.addEventListener('input', function() {
        // Actualizar el atributo data-department-name con el valor del campo de nombre
        saveButton.setAttribute('data-department-name', nameInput.value);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const saveButton = document.querySelector('[data-bs-target="#modal-edit"]');
    const directorSelect = document.getElementById('director_id'); // El campo select para el director

    // Escuchar cambios en el select de director
    directorSelect.addEventListener('change', function () {
        const selectedOption = directorSelect.options[directorSelect.selectedIndex];

        const selectedDirectorId = selectedOption.value;
        const selectedDirectorName = selectedOption.text.split(" ")[1]; // Nombre del director
        const selectedDirectorLastname = selectedOption.text.split(" ")[0]; // Apellido del director

        // Actualizar los datos del botón que abre el modal
        saveButton.setAttribute('data-director-id', selectedDirectorId);
        saveButton.setAttribute('data-director-name', selectedDirectorName);
        saveButton.setAttribute('data-director-lastname', selectedDirectorLastname);

    });
});
