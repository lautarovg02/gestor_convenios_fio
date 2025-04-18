<!-- resources/views/careers/filters.blade.php-->

<!-- Filtro por departamento -->
<form action="{{ route('careers.index') }}" method="GET" class="row g-3 mb-4  justify-content-center">
    <div class=" d-flex align-content-center col-md-auto mt-0">
        <select name="department" class="form-select ms-2 font-size" style="min-width: 200px;">
            <option value="">Selecciona un departamento</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}"
                    {{ request()->input('department') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-auto d-flex align-items-end mt-0 font-size">
        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-loading">Filtrar</button>
        <a href="{{ route('careers.index') }}" class="btn btn-secondary ms-2">Limpiar</a>
    </div>
</form>
