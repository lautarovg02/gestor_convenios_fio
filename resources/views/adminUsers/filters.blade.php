<form action="{{ route('adminUsers.index') }}" method="GET" class="p-3 rounded border shadow-sm bg-white">
    <div class="row g-3 align-items-end">

        <!-- Nombre de usuario -->
        <div class="col-md-3">
            <label for="name" class="form-label">Usuario</label>
            <select class="form-select font-size" id="name" name="name">
                <option value="">Todos</option>
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}" {{ request('name') == $user->id ? 'selected' : '' }}>
                        {{ $user->teacher->name ?? $user->secretary->username ?? $user->name ?? '—' }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Email -->
        <div class="col-md-3">
            <label for="email" class="form-label">Email</label>
            <select class="form-select font-size" id="email" name="email">
                <option value="">Todos</option>
                @foreach($allUsers as $user)
                    <option value="{{ $user->id }}" {{ request('email') == $user->id ? 'selected' : '' }}>
                        {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Buscador general -->
        <div class="col-md-5">
            <label for="search" class="form-label">Buscar</label>
            <div class="input-group">
                <input type="text"
                       id="search"
                       name="search"
                       class="font-size form-control"
                       placeholder="Buscar por nombre o email"
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="{{ route('adminUsers.index') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </div>

    </div>
</form>

@vite('resources/js/adminUsers/filtersReset.js')
