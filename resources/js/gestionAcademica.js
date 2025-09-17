// public/js/gestionAcademica.js


document.addEventListener("DOMContentLoaded", function () {
    var academicoLink = document.querySelector("#academicoToggle");
    var academicoSubmenu = document.getElementById("academicoSubmenu");

    academicoLink.addEventListener("click", function (e) {
        e.preventDefault();
        academicoSubmenu.classList.toggle("show");
    });
});
