<!-- resources/views/layouts/modals/modal-edit-career.blade.php -->
<div class="modal fade" id="modal-edit-career" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex flex-column align-items-center">
                <img class="img-fluid" src="{{ asset('images/edit.png') }}" alt="Logo editar" style="width:100px">
                <div class="text-center mt-2">
                    <h1 class="modal-title fw-bold" style="font-size: 30px">Confirmar modificación</h1>
                    <p class="mt-4">
                        ¿Confirma a <span class="fw-bold" id="coordinator-lastname"></span>
                        <span class="fw-bold" id="coordinator-name"></span>
                        como Coordinador de la carrera <span class="fw-bold" id="career-name"></span>
                        perteneciente al Departamento de <span class="fw-bold" id="department-name"></span>?
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" id="modal-edit-form" action="{{ route('careers.update', $career) }}" role="form">
                    @csrf
                    {{ method_field('PATCH') }}
                    <!-- Campos ocultos para los valores -->
                    <input type="hidden" name="name" id="modal-career-name">
                    <input type="hidden" name="department_id" id="department-id-select">
                    <input type="hidden" name="coordinator_id" id="coordinator-id-input">
                    <button type="submit" class="btn btn-danger">Confirmar modificación</button>
                </form>
            </div>
        </div>
    </div>
</div>
