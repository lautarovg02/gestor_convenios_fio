<div class="modal fade" id="modal-delete" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <img class="img-fluid" src="{{asset('images/trash-bin.png')}}" alt="Tarro de basura" style="width:100px">
                <div class="text-center mt-2">
                    <h1 class="modal-title fw-bold" style="font-size: 30px">Confirmar eliminación</h1>
                    <p class="mt-4">¿Está seguro que desea eliminar a "<span class="fw-bold" id="entity-name">"[entidad]"</span>"?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                <!-- El formulario que usaremos para enviar la solicitud de eliminación -->
                <!-- El 'action' del formulario cambia dinamicamente mediante 'modalDelete.js' dependiendo del botón eliminar apretado de la entidad que queremos eliminar--->
                <form action="" method="POST" id="modal-delete-form">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
