 <!-- Modal -->
 <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
     <div class="modal-dialog">
         <div class="modal-content bg-dark text-white">
             <div class="modal-header">
                 <h5 class="modal-title" id="searchModalLabel">Buscar empresas con convenios Marcos De Residencia
                 </h5>
                 <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <label for="exampleSelect" class="form-label">Seleccionar empresa</label>
                 <select id="companySelect" name="company_id" class="form-select" style="width: 100%">
                     <option value="">-- Seleccionar empresa --</option>
                     @foreach ($companies as $company)
                         <option value="{{ $company->id }}">{{ $company->denomination }}</option>
                     @endforeach
                 </select>

             </div>
             <div class="mt-3 text-end">
                 <button id="confirmCompanyBtn" type="button" class="btn btn-info d-none" data-bs-dismiss="modal">
                     Confirmar empresa
                 </button>
             </div>
         </div>
     </div>
 </div>

    @vite('resources/js/modals/modalSelectCompany.js')