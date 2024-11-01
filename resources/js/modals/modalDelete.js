"use strict";

const MODAL_DELETE = document.querySelector('#modal-delete');

MODAL_DELETE.addEventListener('show.bs.modal', function(e) {
    //Agarramos al botón que activó el modal
    const BUTTON = e.relatedTarget;

    //Extraemos la información de los atributos data
    const ENTITY_ID = BUTTON.getAttribute('data-entity-id');
    const ENTITY_NAME = BUTTON.getAttribute('data-entity-name');


    //Actualizamos el contenido del modal haciendo referencia a la entidad que queremos eliminar
    const ENTITY_NAME_SPAN = MODAL_DELETE.querySelector('#entity-name');
    ENTITY_NAME_SPAN.textContent = ENTITY_NAME;

    //Actualizamos la acción del formulario haciendo referencia a la entidad que queremos eliminar para que lo pueda borrar correctamente
    const MODAL_DELETE_FORM = MODAL_DELETE.querySelector('#modal-delete-form');
    MODAL_DELETE_FORM.action = `/companies/${ENTITY_ID}`;
});
