"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const entitySelect = document.getElementById('entity');
    if (entitySelect) {
        entitySelect.addEventListener('change', function() {
            toggleOtherOption('entity', 'otherEntityInputWrapper', 'other_entity_input', 'other_entity');
        });
    }
});

export function toggleOtherOption(selectId, inputWrapperId, inputId, inputValueId) {
    const select = document.getElementById(selectId);
    const inputWrapper = document.getElementById(inputWrapperId);
    const input = document.getElementById(inputId);
    const hiddenInput = document.getElementById(inputValueId);

    if (select.value === 'other') {
        inputWrapper.style.display = 'block'; // Muestra el contenedor
        input.style.display = 'block'; // Muestra el input de texto
        hiddenInput.value = input.value; // Asigna el valor del input al campo oculto
    } else {
        inputWrapper.style.display = 'none'; // Oculta el contenedor
        input.style.display = 'none'; // Oculta el input de texto
        hiddenInput.value = ''; // Limpia el valor del input oculto
    }

    if (input.style.display === 'block' && input.value !== '') {
        hiddenInput.value = input.value; // Asigna el valor del input al campo oculto
    }
    // Mostrar el valor del campo oculto en la consola
    console.log('Valor de other_entity (campo oculto):', hiddenInput.value);

}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('yourFormId');  // Asegúrate de usar el ID correcto del formulario
    if (form) {
        form.addEventListener('submit', function (event) {
            // Verifica si el campo de texto "otra opción" existe
            if (document.getElementById('other_entity_input')) {
                // Asigna el valor del input al campo oculto
                document.getElementById('other_entity').value = document.getElementById('other_entity_input').value;
            }
        });
    }
});

