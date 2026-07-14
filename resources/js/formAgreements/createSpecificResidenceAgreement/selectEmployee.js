document.addEventListener("DOMContentLoaded", () => {
    const selectEmployee = document.getElementById("selectEmployee");

    selectEmployee.addEventListener("change", () => {
        const selectedOption = selectEmployee.value;
        if (!selectedOption) return;

        fetch(`/api/employee/${selectedOption}`)
            .then((response) => response.json())
            .then((employee) => {
                document.querySelector('input[name="tutorName"]').value =
                    employee.name || "";
                document.querySelector('input[name="tutorLastName"]').value =
                    employee.lastname || "";
                document.querySelector('input[name="tutorDni"]').value =
                    employee.dni || "";
            })
            .catch((error) => {
                console.error(
                    "Error al obtener los datos del empleado:",
                    error
                );
            });
    });
});
