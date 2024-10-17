
<div class="row">
    <div class="col-12">
        <div>
            <h4>Agregar nueva empresa</h4>
        </div>

    </div>

</div>

<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detalles Empresas</h3>
            </div>
            <div class="card-body">
                <form method="POST" action=" {{route('companies.store')}} " id="" role="form">
                    @csrf

                    <input type="hidden" name="_token" value="" autocomplete="off">
                    <div class="form-group mb-3">
                        <label class="form-label required">
                            <label for="denomination" class="required">Denomination</label>
                        </label>
                        <div>
                            <input class="form-control" placeholder="Denomination" name="denomination" type="text" id="denomination">
                            <small class="form-hint">company <b>denomination</b> instruction.</small>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="cuit">Cuit</label></label>
                    <div>
                    <input class="form-control" placeholder="Cuit" name="cuit" type="text" id="cuit">

                    <small class="form-hint">company <b>cuit</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="company_name">Company Name</label></label>
                    <div>
                    <input class="form-control" placeholder="Company Name" name="company_name" type="text" id="company_name">

                    <small class="form-hint">company <b>company_name</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="sector">Sector</label></label>
                    <div>
                    <input class="form-control" placeholder="Sector" name="sector" type="text" id="sector">

                    <small class="form-hint">company <b>sector</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="entity">Entity</label></label>
                    <div>
                    <input class="form-control" placeholder="Entity" name="entity" type="text" id="entity">

                    <small class="form-hint">company <b>entity</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="company_category">Company Category</label></label>
                    <div>
                    <input class="form-control" placeholder="Company Category" name="company_category" type="text" id="company_category">

                    <small class="form-hint">company <b>company_category</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="scope">Scope</label></label>
                    <div>
                    <input class="form-control" placeholder="Scope" name="scope" type="text" id="scope">

                    <small class="form-hint">company <b>scope</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="street">Street</label></label>
                    <div>
                    <input class="form-control" placeholder="Street" name="street" type="text" id="street">

                    <small class="form-hint">company <b>street</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="number">Number</label></label>
                    <div>
                    <input class="form-control" placeholder="Number" name="number" type="text" id="number">

                    <small class="form-hint">company <b>number</b> instruction.</small>
                    </div>
                    </div>
                    <div class="form-group mb-3">
                    <label class="form-label">   <label for="city_id">City Id</label></label>
                    <div>
                    <input class="form-control" placeholder="City Id" name="city_id" type="text" id="city_id">

                    <small class="form-hint">company <b>city_id</b> instruction.</small>
                    </div>
                    </div>

                    <div class="form-footer">
                        <div class="text-end">
                            <div class="d-flex">
                                <a href="{{route('companies.index')}}" class="btn btn-danger">Cancelar</a>
                                <div>
                                    <button type="submit" class="btn btn-primary ms-auto ">Crear</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
