"use strict";

function toggleNewPhone() {
    const field = document.getElementById('newPhoneField');
    const button = document.getElementById('togglePhoneBtn');
    const isHidden = field.style.display === 'none';

    field.style.display = isHidden ? 'block' : 'none';

    if (isHidden) {
        button.textContent = 'Cancelar';
        button.classList.remove('btn-outline-primary');
        button.classList.add('btn-outline-danger');
    } else {
        button.textContent = '+ Agregar nuevo celular';
        button.classList.remove('btn-outline-danger');
        button.classList.add('btn-outline-primary');

        const input = field.querySelector('input');
        if (input) input.value = '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('togglePhoneBtn');
    const form = document.querySelector('form');

    document.querySelector("form").addEventListener("submit", function (event) {
        let newPhoneInput = document.querySelector("input[name='phones[new][number]']");
        if (newPhoneInput && newPhoneInput.value.trim() === "") {
            newPhoneInput.remove(); //Elimina el campo vacío antes de enviar el formulario
        }
    });

    if (btn) {
        btn.addEventListener('click', toggleNewPhone);
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            const field = document.getElementById('newPhoneField');
            const input = field.querySelector('input');
            const isVisible = field.style.display !== 'none';

            if (isVisible && input && input.value.trim() === '') {
                e.preventDefault();
                alert('Por favor, complete el nuevo número de celular o cierre el campo.');
                input.focus();
            }
        });
    }
});
