@vite('resources\css\login.css')

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

    <div class="container">

        <div class="card">

            <div class="containerImgAndText">
                <div class="containerLogoAndTitle">

                    <img class="logo" src="{{ asset('images/logo-facultad.png') }}" alt="Logo de la Facultad">

                    <h1>Acceder</h1>
                    <small>Usá tu cuenta institucional</small>
                </div>

                <small class="text-muted">Sistema de gestión de convenios</small>
            </div>
            <div class="containerForm">

                <div class="containerCard">

                    <h2>Iniciar sesión</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="containerInputs">

                            <div class="inputGroup">
                                <input type="email" name="email" required placeholder="Email"
                                    value="{{ old('email') }}" autofocus>
                            </div>


                            <div class="inputGroup password">
                                <input type="password" name="password" required placeholder="Contraseña">

                            </div>


                            @error('email')
                                <p class="msjError">{{ $message }}</p>
                            @enderror


                            <button class="btn" type="submit">Ingresar</button>
                    </form>


                </div>

            </div>
        </div>
    </div>
</body>

</html>
