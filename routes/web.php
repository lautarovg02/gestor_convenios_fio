<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AgreementController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SpecificAgreementController;
use App\Http\Controllers\FrameworkAgreementController;
use App\Http\Controllers\FrameworkInternshipAgreementController;
use App\Http\Controllers\FrameworkResidenceAgreementController;
use App\Http\Controllers\SpecificResidenceAgreementController;
use App\Http\Controllers\IndividualInternshipAgreementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PendingRequestController;
use App\Http\Controllers\RejectedRequestController;
use App\Http\Controllers\ApprovedRequestController;

/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => auth()->check() ? redirect('/home') : redirect('/login'));


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ======================================================================
    // GRUPO 1: ACCESO GENERAL (Docente incluido)
    // ======================================================================
    Route::middleware(['role:Admin|Secretaria|Director|Coordinador|Docente'])->group(function () {
        
        Route::resource('home', HomeController::class);

        // --- CRUD COMPLETO ---
        Route::resource('companies', CompanyController::class);
        Route::resource('companies.employees', EmployeeController::class)->shallow();
        Route::resource('students', StudentController::class);

        Route::resource('teachers', TeacherController::class);

        // --- CONVENIOS (Listado General) ---
        Route::resource('agreements', AgreementController::class)->name('index', 'agreements.index');

        // --- CREACIÓN Y EDICIÓN DE TODOS LOS CONVENIOS ---
        // IMPORTANTE: Usamos ->except(['destroy']) para que el Docente pueda hacer todo MENOS borrar.
        
        // 1. Marco General
        Route::get('/frameworkAgreement/download', [FrameworkAgreementController::class, 'download'])->name('agreement.download');
        Route::resource('frameworkAgreement', FrameworkAgreementController::class)->except(['destroy']);

        // 2. Marco Pasantía
        Route::get('/frameworkInternshipAgreement/download', [FrameworkInternshipAgreementController::class, 'download'])->name('frameworkInternshipAgreement.download');
        Route::resource('frameworkInternshipAgreement', FrameworkInternshipAgreementController::class)->except(['destroy']);

        // 3. Marco Residencia
        Route::resource('frameworkResidenceAgreement', FrameworkResidenceAgreementController::class)->except(['destroy']);
        Route::get('/buscarConvenio/{company}', [FrameworkResidenceAgreementController::class, 'searchAgreementByCompany']);

        // 4. Específicos
        Route::resource('specificResidenceAgreement', SpecificResidenceAgreementController::class)->except(['destroy']);
        Route::resource('specificAgreement', SpecificAgreementController::class)->except(['destroy']);
        Route::get('/specificAgreement/getFrameworkData/{id}', [SpecificAgreementController::class, 'getFrameworkAgreementData']);

        // 5. Pasantías Individuales
        Route::resource('individual-internship-agreements', IndividualInternshipAgreementController::class);
        Route::post('individual-internship-agreements/select-company', [IndividualInternshipAgreementController::class, 'selectCompany'])->name('individual-internship-agreements.select-company');
        Route::get('individual-internship-agreements/fill-form', [IndividualInternshipAgreementController::class, 'fillForm'])->name('individual-internship-agreements.fill-form');
        Route::get('individual-internship-agreements/{id}/download', [IndividualInternshipAgreementController::class, 'download'])->name('individual-internship-agreements.download');

        // --- UTILIDADES ---
        Route::get('/cities', [CityController::class, 'getCiudades'])->name('get.ciudades');
        Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
        Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

        Route::prefix('api')->group(function() {
            Route::get('/company/{id}', [CompanyController::class, 'getCompanyById']);
            Route::get('/employees/{companyId}', [EmployeeController::class, 'getEmployeesByCompany']);
            Route::get('/buscarConvenio/{company}', [FrameworkResidenceAgreementController::class, 'searchAgreementByCompany']);
            Route::get('/employee/{id}', [EmployeeController::class, 'getEmployeeById']);
            Route::get('/student/{dni}', [StudentController::class, 'getStudentByDni']);
            Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
        });

        // Lectura de Carreras (Restricción numérica para evitar conflicto con 'create' del Grupo 3)
        Route::resource('careers', CareerController::class)
            ->only(['index', 'show'])
            ->where(['career' => '[0-9]+']); 

                    //DESCARGA DE DOCUMENTOS DE CONTRATO
Route::get('/contract/{contract}/download-doc/{type}', [ContractController::class, 'downloadDocument'])
    ->name('contract.download.document');

// PENDING REQUESTS
    Route::get('pending-requests', [PendingRequestController::class, 'index'])->name('pending-requests.index');
    Route::post('contracts/{contract}/approve', [PendingRequestController::class, 'approve'])->name('contracts.approve')->middleware('can:aprobar rechazar solicitudes');
    Route::post('contracts/{contract}/reject', [PendingRequestController::class, 'reject'])->name('contracts.reject')->middleware('can:aprobar rechazar solicitudes');

// REJECTED REQUESTS
Route::get('rejected-requests', [RejectedRequestController::class, 'index'])->name('rejected-requests.index');

// APPROVED REQUESTS
Route::get('approved-requests', [ApprovedRequestController::class, 'index'])->name('approved-requests.index');
    });
    });



    // ======================================================================
    // GRUPO 2: GESTIÓN INTERMEDIA (Excluye Docente)
    // ======================================================================
    Route::middleware(['role:Admin|Secretaria|Director|Coordinador'])->group(function () {
        


        // Lectura Departamentos (Restricción numérica para evitar conflicto)
        Route::resource('departments', DepartmentController::class)
            ->only(['index', 'show'])
            ->where(['department' => '[0-9]+']);

        // --- BORRADO DE CONVENIOS ---
        // Aquí definimos SOLO las rutas DELETE. Esto complementa al 'except(['destroy'])' del Grupo 1.
        
        Route::delete('/frameworkAgreement/{frameworkAgreement}', [FrameworkAgreementController::class, 'destroy'])->name('frameworkAgreement.destroy');
        Route::delete('/frameworkInternshipAgreement/{frameworkInternshipAgreement}', [FrameworkInternshipAgreementController::class, 'destroy'])->name('frameworkInternshipAgreement.destroy');
        Route::delete('/frameworkResidenceAgreement/{frameworkResidenceAgreement}', [FrameworkResidenceAgreementController::class, 'destroy'])->name('frameworkResidenceAgreement.destroy');
        Route::delete('/specificResidenceAgreement/{specificResidenceAgreement}', [SpecificResidenceAgreementController::class, 'destroy'])->name('specificResidenceAgreement.destroy');
        Route::delete('/specificAgreement/{specificAgreement}', [SpecificAgreementController::class, 'destroy'])->name('specificAgreement.destroy');
    });


    // ======================================================================
    // GRUPO 3: ADMINISTRACIÓN CRÍTICA
    // ======================================================================
    Route::middleware(['role:Admin|Secretaria'])->group(function () {
        
        // GESTIÓN DE USUARIOS
        Route::resource('adminUsers', AdminUsersController::class)->parameters(['adminUsers' => 'user']);
        Route::get('/admin/users', [AdminUsersController::class, 'index'])->name('adminUsers.index');
        Route::get('/admin/users/{user}/edit', [AdminUsersController::class, 'edit'])->name('adminUsers.edit');
        Route::post('/users', [AdminUsersController::class, 'store'])->name('admin.users.store');
        Route::match(['put', 'patch'], '/admin/users/{user}', [AdminUsersController::class, 'update'])->name('adminUsers.update');  
        Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])->name('adminUsers.destroy');

        // ESTRUCTURA (ESCRITURA)
        Route::resource('departments', DepartmentController::class)->except(['index', 'show']);
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
        
        Route::resource('careers', CareerController::class)->except(['index', 'show']);




    // ======================================================================
    // RUTAS DE SEGURIDAD EXTRA
    // ======================================================================
    Route::delete('/secretaries/{secretary}', [SecretaryController::class, 'destroy'])
        ->name('secretaries.destroy')
        ->middleware(['role:Admin']); 

});
