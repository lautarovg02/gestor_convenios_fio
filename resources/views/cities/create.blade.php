<!-- resources/views/companies/create.blade.php -->
<!-- @extends('layouts.app') -->

@section('content')
    <div class="container-xl" style="display: flex; justify-content: center;">
        <div>

            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">

                    <div style="text-align: center; text-transform: uppercase; padding: 6% 0% 0% 0%;">
                        <h4>Agregar ciudad</h4>
                    </div>

                        <div class="card-body">
                            <form method="POST" action=" {{ route('cities.store') }} " id="" role="form">
                                @csrf
                                <div class="form-group mb-3 fs-6">
                                    <label class="form-label required-field">
                                        <label for="name" class="required-file">Ciudad</label>
                                    </label>
                                    <div>
                                        <input class="form-control " maxlength="40" placeholder="Ciudad a ingresar..."
                                            name="name" type="text" id="name">
                                        
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group mb-3 fs-6">

                                    <label class="form-label required-field">
                                        <label for="postal_code">Código Postal</label>
                                    </label>
                                    <div>
                                        <input class="form-control " maxlength="10" placeholder="Código Postal"
                                            name="postal_code" type="number" id="postal_code">
                                        @error('postal_code')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                                    <div class="form-group mb-3 fs-6">
                                        <label class="form-label required-field"> <label
                                                for="province_id">Provincia</label></label>
                                        <div>
                                            <select class=" form-control " name="province_id" id="">
                                                <option value="">Seleccionar</option>
                                                @foreach ($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                                @endforeach
                                            </select>
                                            <small class="form-hint" style="font-size: 12px">Seleccione la <b>provincia</b> a la cual pertenece la
                                                ciudad.</small>
                                            @error('province_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <div class="text-end">
                                            <div class="d-flex" style="justify-content: space-between">
                                                <a href="{{ route('companies.create') }}"
                                                    class="btn btn-danger m-2">Cancelar</a>
                                                <div>
                                                    <button type="submit"
                                                        class="btn btn-success ms-auto  m-2">Agregar ciudad</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
