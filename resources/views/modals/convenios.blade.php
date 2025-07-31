<div class="modal fade" id="crearConvenioModal" tabindex="-1" aria-labelledby="crearConvenioModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="crearConvenioModalLabel">Seleccionar tipo de convenio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body d-flex flex-column gap-3">
                <a href="{{ route('frameworkAgreement.create', ['type' => 'marco']) }}"
                    class="btn btn-outline-primary"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Convenio Marco
                </a>

                <a href="{{ route('frameworkInternshipAgreement.create', ['type' => 'marco de pasantia']) }}"A
                    class="btn btn-outline-success"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Convenio de Marco de Pasantía
                </a>

                <a href="{{ route('specificResidenceAgreement.create', ['type' => 'especifico_residencia']) }}"
                    class="btn btn-outline-warning"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Acuerdo Individual de Residencia
                </a>

                <a href="{{ route('specificAgreement.create', ['type' => 'especifico']) }}"
                    class="btn btn-outline-secondary"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Convenio Específico
                </a>

                <a href="{{ route('agreements.create', ['type' => 'indPasantia']) }}"
                    class="btn btn-outline-info"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Convenio Individual de Pasantía
                </a>

                <a href="{{ route('frameworkResidenceAgreement.create', ['type' => 'espPasantia']) }}"
                    class="btn btn-outline-danger"
                    style="font-weight: 600; border-width: 2px; padding: 10px 16px; font-size: 1rem;">
                    Convenio Marco de Residencia
                </a>

            </div>
        </div>
    </div>
</div>