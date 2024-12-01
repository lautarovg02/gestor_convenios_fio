"use strict";

document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('departmentSelect');
    const editButton = document.getElementById('editDepartmentButton');
    const editInputWrapper = document.getElementById('editDepartmentInputWrapper');
    const editInput = document.getElementById('edit_department_input');
    const hiddenInput = document.getElementById('name');

    // Habilitar edición al hacer clic en "Editar"
    editButton.addEventListener('click', function () {
        editInputWrapper.style.display = 'block'; // Mostrar el input de texto
        editInput.focus(); // Enfocar el input de texto
        departmentSelect.disabled = true; // Deshabilitar el select
        this.disabled = true; // Deshabilitar el botón de edición
    });

    // Al enviar el formulario, definir qué valor se envía en el campo "name"
    document.getElementById('editDepartmentForm').addEventListener('submit', function () {
        if (!departmentSelect.disabled) {
            // Si el select está habilitado, enviar su valor
            hiddenInput.value = departmentSelect.value;
        } else {
            // Si el input está habilitado, enviar su valor
            hiddenInput.value = editInput.value;
        }
    });
});
