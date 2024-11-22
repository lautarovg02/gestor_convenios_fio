// public/js/gestionAcademica.js


document.addEventListener("DOMContentLoaded", function () {
    var academicoLink = document.querySelector("#academicoToggle");
    var academicoSubmenu = document.getElementById("academicoSubmenu");

    function toggleHoverEffect() {
        if (academicoSubmenu.classList.contains("show")) {
            academicoLink.classList.add("no-hover");
        } else {
            academicoLink.classList.remove("no-hover");
        }
    }

    academicoLink.addEventListener("click", function (e) {
        e.preventDefault();
        academicoSubmenu.classList.toggle("show");
        toggleHoverEffect();
    });
});
