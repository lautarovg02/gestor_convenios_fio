document.addEventListener("DOMContentLoaded", () => {
    
    const selectStudent = document.getElementById("selectStudent");

    selectStudent.addEventListener("change", () => {
        const selectedOption = selectStudent.value;
        if (!selectedOption) return;

        fetch(`/api/student/${selectedOption}`)
            .then((response) => response.json())
            .then((student) => {
                document.querySelector('input[name="studentName"]').value =
                    student.name || "";
                document.querySelector('input[name="studentLastName"]').value =
                    student.last_name || "";
                document.querySelector('input[name="dniStudent"]').value =
                    student.dni || "";
                    document.querySelector('input[name="studentCuil"]').value =
                    student.cuil || "";
                    document.querySelector('input[name="studentEmail"]').value =
                    student.email || "";
                    document.querySelector('input[name="studentCelular"]').value =
                    student.phone_numb || "";
            })
            .catch((error) => {
                console.error(
                    "Error al obtener los datos del estudiante:", error);
            });
    });
});
