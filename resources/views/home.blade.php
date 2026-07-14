@extends('layouts.app')

@vite('resources/css/welcome.css')

@section('content')
    <main class="content">

        <section class="intro">
            <h1 class="fw-bold">Bienvenido al Sistema de Gestión de Convenios</h1>
            <p class=" textWelcome text-muted">
                Este sistema permite gestionar de manera centralizada los convenios de la Facultad de Ingeniería.
                Aquí podrás administrar convenios activos y pendientes, realizar el registro y seguimiento de alumnos,
                así como consultar y mantener actualizada la información de las empresas vinculadas.
            </p>
        </section>

        <div class="cards">
            <a class="cardLink" href="{{ route('agreements.index') }}">
            <div class="card">
                <h3>📑 Convenios</h3>
                <p class="textCard">Gestión de convenios activos y pendientes.</p>
            </div>
            </a>
            <a class="cardLink" href="{{ route('students.index') }}">
            <div class="card">
                <h3>🎓 Alumnos</h3>
                <p class="textCard">Registro y administración de alumnos.</p>
            </div>
            </a>
            <a class="cardLink" href="{{ route('companies.index') }}">
            <div class="card">
                <h3>🏢 Empresas</h3>
                <p class="textCard">Listado de empresas vinculadas.</p>
            </div>
            </a>
        </div>
    </main>
@endsection
