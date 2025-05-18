document.addEventListener("DOMContentLoaded", function () {

function getVisiblePhones() {
    return Array.from(document.querySelectorAll(".phone-group"))
        .filter(phone => phone.style.display !== "none").length;
}

document.querySelectorAll(".remove-phone").forEach((button) => {
    button.addEventListener("click", function () {
        const phoneGroup = this.closest(".phone-group");
        const deleteInput = phoneGroup.querySelector(".delete-input");

        if (getVisiblePhones() > 1) {
            if (deleteInput) {
                deleteInput.value = "1"; // ✅ Marcar como eliminado
            }

            phoneGroup.style.display = "none"; //Ocultar visualmente pero mantener en el DOM
            
            //Verificar si queda solo un número y ocultar los botones de eliminación
            const removeButtons = document.querySelectorAll(".remove-phone");

            if (getVisiblePhones() === 1) {
                removeButtons.forEach(button => button.style.display = "none");
            } else {
                removeButtons.forEach(button => button.style.display = "inline-block");
            }
        }
    });
});


});
