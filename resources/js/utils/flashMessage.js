document.addEventListener("DOMContentLoaded", function () {
    const flashMessage = document.getElementById("flash-message");
    
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.opacity = "0"; 
            setTimeout(() => {
                flashMessage.style.display = "none"; 
            }, 500); 
        }, 3000); 
    }
});
