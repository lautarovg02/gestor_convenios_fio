<div class="modal fade" id="crearConvenioModal" tabindex="-1" aria-labelledby="crearConvenioModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-3 shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold fs-4 mb-2" id="crearConvenioModalLabel">Seleccionar tipo de convenio</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body d-flex flex-column gap-3 mb-3">
        
        <a href="{{ route('frameworkAgreement.create', ['type' => 'marco']) }}" 
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio Marco
        </a>

        <a href="{{ route('frameworkInternshipAgreement.create', ['type' => 'marco de pasantia']) }}"
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio de Marco de Pasantía
        </a>

        <a href="{{ route('agreements.create', ['type' => 'residencia']) }}"
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio de Residencia
        </a>

        <a href="{{ route('agreements.create', ['type' => 'especifico']) }}"
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio Específico
        </a>

        <a href="{{ route('individual-internship-agreements.create')}}" 
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio Individual de Pasantía
        </a>

        <a href="{{ route('frameworkResidenceAgreement.create', ['type' => 'espPasantia']) }}"
           class="btn btn-outline-primary fw-semibold btn-lg" role="button">
          Convenio Marco de Residencia
        </a>

      </div>
    </div>
  </div>
</div>
