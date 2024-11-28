"use strict"

document.addEventListener('DOMContentLoaded', () => {
    // Seleccionar el formulario y el botón
    const form = document.getElementById('edit-career-form');
    const saveButton = document.querySelector('button[data-bs-target="#modal-edit-career"]');

    // Escuchar cambios en los campos on 'input'
    form.addEventListener('input', () => {
        // Obtener valores actualizados del formulario
        const name = form.querySelector('#name').value;
        const departmentSelect = form.querySelector('#department_id');
        const departmentId = departmentSelect.value;
        const departmentName = departmentSelect.options[departmentSelect.selectedIndex]?.text;

        const coordinatorSelect = form.querySelector('#coordinator_id');
        const coordinatorId = coordinatorSelect.value;
        const coordinatorName = coordinatorSelect.options[coordinatorSelect.selectedIndex]?.text?.split(' ')[1] || '';
        const coordinatorLastName = coordinatorSelect.options[coordinatorSelect.selectedIndex]?.text?.split(' ')[0] || '';

        // Actualizar atributos data- del botón
        saveButton.dataset.careerName = name;
        saveButton.dataset.departmentId = departmentId;
        saveButton.dataset.departmentName = departmentName;
        saveButton.dataset.coordinatorId = coordinatorId;
        saveButton.dataset.coordinatorName = coordinatorName;
        saveButton.dataset.coordinatorLastname = coordinatorLastName;
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modal-edit-career');
    const buttons = document.querySelectorAll('button[data-bs-toggle="modal"]');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            // Obtener los datos del botón
            const careerName = this.getAttribute('data-career-name');
            const departmentName = this.getAttribute('data-department-name');
            const departmentId = this.getAttribute('data-department-id');
            const coordinatorName = this.getAttribute('data-coordinator-name');
            const coordinatorLastname = this.getAttribute('data-coordinator-lastname');
            const coordinatorId = this.getAttribute('data-coordinator-id');

            // Pasar los datos al modal
            document.getElementById('career-name').textContent = careerName;
            document.getElementById('department-name').textContent = departmentName;
            document.getElementById('coordinator-name').textContent = coordinatorName;
            document.getElementById('coordinator-lastname').textContent = coordinatorLastname;

            // Rellenar los campos ocultos del formulario
            document.getElementById('modal-career-name').value = careerName;
            document.getElementById('department-id-select').value = departmentId;
            document.getElementById('coordinator-id-input').value = coordinatorId;

            // Actualizar la acción del formulario con la URL dinámica
            const form = document.getElementById('modal-edit-form');
            const careerId = this.getAttribute('data-id');
            form.action = `/careers/${careerId}`;
        });
    });
});
