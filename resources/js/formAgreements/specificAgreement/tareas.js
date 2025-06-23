document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('nuevaTareaInput');
    const addBtn = document.getElementById('addTareaBtn');
    const list = document.getElementById('tareasList');

    addBtn.addEventListener('click', function () {
        const valor = input.value.trim();

        if (valor !== '') {
            const wrapper = document.createElement('div');
            wrapper.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2', 'border', 'p-2', 'rounded');

            wrapper.innerHTML = `
                <input type="hidden" name="tareas[]" value="${valor}">
                <label class="mb-0 fw-bold fs-6  flex-grow-1 text-break form-label">${valor}</label>
                <button type="button" class="btn btn-outline-danger btn-sm ms-2 removeTarea">-</button>
            `;

            list.appendChild(wrapper);
            input.value = '';
        }
    });

    list.addEventListener('click', function (e) {
        if (e.target.classList.contains('removeTarea')) {
            e.target.closest('div').remove();
        }
    });
});
