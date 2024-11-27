<!-- resources/views/layouts/modals/modal-edit.blade.php -->
<div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <img class="img-fluid" src="{{ asset('images/edit-icon.png') }}" alt="Logo editar" style="width:100px">
                <div class="text-center mt-2">
                    <h1 class="modal-title fw-bold" style="font-size: 30px">Confirmar modificación</h1>
                    <p class="mt-4"> ¿Confirma a  <span class="fw-bold" id="director-lastname"></span>
                        <span class="fw-bold" id="director-name"></span>
                        como Director del departamento
                        <span class="fw-bold" id="department-name"></span> ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <!-- El formulario enviará la acción dinámica -->
                <form method="POST" id="modal-edit-form" action="{{ route('departments.update', $department) }}" role="form" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PATCH') }}
                    <!-- Input oculto para el id del director -->
                    <input type="hidden" name="director_id" id="director-id-input">
                    <input type="hidden" name="name" id="modal-department-name">
                    <button type="submit" class="btn btn-danger">Confirmar modificación</button>
                </form>
            </div>
        </div>
    </div>
</div>
