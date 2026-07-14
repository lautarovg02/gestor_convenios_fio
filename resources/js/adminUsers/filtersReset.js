document.addEventListener('DOMContentLoaded', function() {
    const selects = ['#name', '#email'];
    const searchInput = document.querySelector('#search');

    // Cuando cambia un select, limpia los otros filtros y el buscador
    selects.forEach(selector => {
        const select = document.querySelector(selector);
        if (select) {
            select.addEventListener('change', () => {
                selects.forEach(other => {
                    if (other !== selector) document.querySelector(other).value = '';
                });
                if (searchInput) searchInput.value = '';
                select.form.submit();
            });
        }
    });

    // Cuando se hace submit con el buscador, limpia los selects
    if (searchInput) {
        searchInput.form.addEventListener('submit', () => {
            selects.forEach(sel => document.querySelector(sel).value = '');
        });
    }
});
