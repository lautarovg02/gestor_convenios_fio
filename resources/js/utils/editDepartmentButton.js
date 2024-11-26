document.getElementById('editDepartmentButton').addEventListener('click', function () {
    const selectElement = document.getElementById('name');
    const selectedOption = selectElement.options[selectElement.selectedIndex];

    // Obtener el ID del departamento seleccionado desde el atributo 'data-id'
    const departmentId = selectedOption.getAttribute('data-id');

    // Obtener el input oculto para asignarle el valor
    const departmentIdInput = document.getElementById('department-id-input');
    if (departmentIdInput) {
        departmentIdInput.value = departmentId;
    }
});
