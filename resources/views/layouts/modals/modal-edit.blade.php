<!-- resources/views/layouts/modals/modal-edit.blade.php -->
<div class="modal fade" id="modal-edit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <img class="img-fluid" src="{{asset('images/edit-icon.png')}}" alt="Logo editar" style="width:100px">
                <div class="text-center mt-2">
                    <h1 class="modal-title fw-bold" style="font-size: 30px">Confirmar modificación</h1>
                    <p class="mt-4">¿Está seguro que quiere confirmar como Director de departamento a "
                        <span class="fw-bold" id="director-name"></span>
                        <span class="fw-bold" id="director-lastname"></span>"?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <!-- El 'action' del formulario cambia dinamicamente mediante 'modalEdit.js' dependiendo del botón eliminar apretado de la entidad que queremos eliminar--->
                <form  method="POST" id="modal-edit-form"
                    action="{{ route('departments.update', $department) }}"  role="form" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PATCH') }}
                    <!-- Campo para el director, este campo debe ser el id del director -->
                    <input type="hidden" name="director_id" id="director-id-input">
                    <button type="submit" class="btn btn-danger">MODIFICAR</button>
                </form>
            </div>
        </div>
    </div>
</div>
