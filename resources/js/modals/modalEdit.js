// resources/js/modals/modalEdit.js
"use strict";

const MODAL_EDIT = document.querySelector('#modal-edit');

// Selector del <select> de directores
const SELECT_DIRECTOR = document.querySelector('#director_id');

const BUTTON_SAVE = document.querySelector('button[data-bs-target="#modal-edit"]'); // Botón de guardar del modal


// Evento para actualizar datos al cambiar el director en el <select>
SELECT_DIRECTOR.addEventListener('change', function (e) {
    const SELECT_OPTION = e.target.options[e.target.selectedIndex];

    // Separar el nombre y apellido (si vienen juntos en el texto de la opción)
    const [lastname, name] = SELECT_OPTION.textContent.trim().split(' ', 2);
    const DIRECTOR_ID = SELECT_OPTION.value;

    // Actualizamos los datos del modal
    const DIRECTOR_NAME_SPAN = MODAL_EDIT.querySelector('#director-name');
    const DIRECTOR_LASTNAME_SPAN = MODAL_EDIT.querySelector('#director-lastname');
    DIRECTOR_NAME_SPAN.textContent = name || '';
    DIRECTOR_LASTNAME_SPAN.textContent = lastname || '';

    // Actualizamos el botón guardar para reflejar el nuevo director seleccionado
    BUTTON_SAVE.setAttribute('data-director-id', DIRECTOR_ID);
    BUTTON_SAVE.setAttribute('data-director-name', name || '');
    BUTTON_SAVE.setAttribute('data-director-lastname', lastname || '');
});

// Este código debe estar en el archivo modalEdit.js o en un bloque <script> en la vista correspondiente
document.querySelector('#modal-edit').addEventListener('show.bs.modal', function (event) {
    // Obtener el botón que activó el modal
    const button = event.relatedTarget;

    // Obtener los datos asociados al botón
    const departmentId = button.getAttribute('data-id');  // ID del departamento
    const directorId = button.getAttribute('data-director-id');  // ID del director
    const directorName = button.getAttribute('data-director-name');  // Nombre del director
    const directorLastname = button.getAttribute('data-director-lastname');  // Apellido del director

    // Log de depuración: asegurarse de que los datos se obtienen correctamente
    console.log("Department ID:", departmentId);
    console.log("Director ID:", directorId);
    console.log("Director Name:", directorName);
    console.log("Director Lastname:", directorLastname);

    // Asignar el valor del director_id al campo oculto en el formulario
    const directorIdInput = document.getElementById('director-id-input');
    if (directorIdInput) {
        directorIdInput.value = directorId;
        console.log("Campo director_id actualizado con el valor:", directorIdInput.value);
    }

    // Actualizar el contenido del modal con los nombres del director
    const directorNameSpan = document.getElementById('director-name');
    const directorLastnameSpan = document.getElementById('director-lastname');
    if (directorNameSpan && directorLastnameSpan) {
        directorNameSpan.textContent = directorName || '';
        directorLastnameSpan.textContent = directorLastname || '';
    }

    // Actualizar la acción del formulario con el ID del departamento
    const modalEditForm = document.getElementById('modal-edit-form');
    if (modalEditForm) {
        modalEditForm.action = `/departments/${departmentId}`;
    }

    // Asegúrate de que el formulario esté enviando un PATCH
    let methodField = modalEditForm.querySelector('input[name="_method"]');
    if (!methodField) {
        methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        modalEditForm.appendChild(methodField);
    }
    methodField.value = 'PATCH';
});
