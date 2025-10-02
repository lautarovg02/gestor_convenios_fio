"use strict";

const MODAL_DELETE = document.querySelector('#modal-delete');

MODAL_DELETE.addEventListener('show.bs.modal', function(e) {
    const BUTTON = e.relatedTarget;

    // Extraemos nombre
    const ENTITY_NAME = BUTTON.getAttribute('data-entity-name');
    const ENTITY_ID = BUTTON.getAttribute('data-entity-id');
    const ENTITY_TYPE = BUTTON.getAttribute('data-entity-type');
    const ACTION_URL = BUTTON.getAttribute('data-action');

    // Actualizamos el nombre en el modal
    const ENTITY_NAME_SPAN = MODAL_DELETE.querySelector('#entity-name');
    ENTITY_NAME_SPAN.textContent = ENTITY_NAME;

    // Actualizamos la acción del formulario según lo que exista
    const MODAL_DELETE_FORM = MODAL_DELETE.querySelector('#modal-delete-form');
    
    if (ACTION_URL) {
        // Si ya viene la URL completa, la usamos
        MODAL_DELETE_FORM.action = ACTION_URL;
    } else if (ENTITY_ID && ENTITY_TYPE) {
        // Si vienen id y tipo, construimos la URL dinámicamente
        MODAL_DELETE_FORM.action = `/${ENTITY_TYPE}/${ENTITY_ID}`;
    } else {
        console.error('No se pudo determinar la URL de eliminación para este modal');
        MODAL_DELETE_FORM.action = '#';
    }
});
