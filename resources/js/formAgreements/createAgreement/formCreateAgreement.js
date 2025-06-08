document.addEventListener('DOMContentLoaded', () => {
  // Buscar todos los botones que controlan las secciones colapsables
  const toggleButtons = document.querySelectorAll('.toggleSectionBtn');

  toggleButtons.forEach(btn => {
    // Obtener el contenedor padre colapsable
    const section = btn.closest('.collapse');

    // Inicializar texto según estado
    if (section.classList.contains('show')) {
      btn.textContent = btn.textContent.replace('Cargar', 'Ocultar');
    } else {
      btn.textContent = btn.textContent.replace('Ocultar', 'Cargar');
    }

    btn.addEventListener('click', () => {
      const bsCollapse = bootstrap.Collapse.getInstance(section);

      if (bsCollapse) {
        bsCollapse.toggle();
      } else {
        new bootstrap.Collapse(section, { toggle: true });
      }
    });

    // Cambiar texto cuando se muestra la sección
    section.addEventListener('shown.bs.collapse', () => {
      btn.textContent = btn.textContent.replace('Cargar', 'Ocultar');
    });

    // Cambiar texto cuando se oculta la sección
    section.addEventListener('hidden.bs.collapse', () => {
      btn.textContent = btn.textContent.replace('Ocultar', 'Cargar');
    });
  });
});
