<?php


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
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndividualInternshipAgreementController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SecretaryController;

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/', fn() => auth()->check() ? redirect('/home') : redirect('/login'));



//agrupa todas las rutas que requieran autenticación
Route::group(['middleware' => ['role:secretary|admin|teacher']], function () {


    //ADMIN USERS
    Route::resource('adminUsers', AdminUsersController::class)->parameters(['adminUsers' => 'user']);;

   
    // eliminar user (si lo necesitás)
    Route::delete('/admin/users/{user}', [AdminUsersController::class, 'destroy'])
        ->name('adminUsers.destroy');

    // eliminar teacher
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])
        ->name('teachers.destroy')
        ->middleware(['auth', 'role:admin']);

    // eliminar secretary
    Route::delete('/secretaries/{secretary}', [SecretaryController::class, 'destroy'])
        ->name('secretaries.destroy')
        ->middleware(['auth', 'role:admin']);

 
// ADMIN USERS

// Ruta para mostrar el listado de usuarios
Route::get('/admin/users', [AdminUsersController::class, 'index'])
    ->name('adminUsers.index');


// Ruta para mostrar el formulario de edición de usuario
Route::get('/admin/users/{user}/edit', [AdminUsersController::class, 'edit'])
    ->name('adminUsers.edit');
    
// Ruta para procesar el formulario de creación de usuario (POST)
Route::post('/users', [AdminUsersController::class, 'store'])
    ->name('admin.users.store');

// Falta la ruta PUT/PATCH para actualizar (UPDATE).






    // HOME
    Route::resource('home', HomeController::class);

    // COMPANIES
    Route::resource('companies', CompanyController::class);

    // EMPLOYEES
    Route::resource('companies.employees', EmployeeController::class)->shallow();

    //CITIES

    Route::get('/cities', [CityController::class, 'getCiudades'])->name('get.ciudades');
    Route::get('/cities/create', [CityController::class, 'create'])->name('cities.create');
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

    //TEACHERS
    Route::resource('/teachers', TeacherController::class);

    //CAREERS
    Route::resource('/careers', CareerController::class);

    //DEPARTMENTS
    route::resource('/departments', DepartmentController::class);
    Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    // AGREEMENTS
    Route::resource('/agreements', AgreementController::class)->name('index', 'agreements.index');
    Route::get('/frameworkAgreement/download', [FrameworkAgreementController::class, 'download'])->name('agreement.download');

    Route::resource('frameworkAgreement', FrameworkAgreementController::class);

    Route::get('/frameworkInternshipAgreement/download', [FrameworkInternshipAgreementController::class, 'download'])->name('agreement.download');

    Route::resource('frameworkInternshipAgreement', FrameworkInternshipAgreementController::class);

    Route::resource('frameworkResidenceAgreement', FrameworkResidenceAgreementController::class);



    Route::resource('specificResidenceAgreement', SpecificResidenceAgreementController::class);




    Route::resource('frameworkInternshipAgreement', FrameworkInternshipAgreementController::class);

    Route::resource('frameworkResidenceAgreement', FrameworkResidenceAgreementController::class);

    Route::get('/buscarConvenio/{company}', [FrameworkResidenceAgreementController::class, 'searchAgreementByCompany']);

    Route::resource('specificResidenceAgreement', SpecificResidenceAgreementController::class);


    Route::resource('specificAgreement', SpecificAgreementController::class);
    Route::get('/specificAgreement/getFrameworkData/{id}', [SpecificAgreementController::class, 'getFrameworkAgreementData']);


    // AJAX Routes
    Route::get('/api/company/{id}', [CompanyController::class, 'getCompanyById']);
    Route::get('/api/employees/{companyId}', [EmployeeController::class, 'getEmployeesByCompany']);
    Route::get('/api/buscarConvenio/{company}', [FrameworkResidenceAgreementController::class, 'searchAgreementByCompany']);
    Route::get('/api/employee/{id}', [EmployeeController::class, 'getEmployeeById']);
    Route::get('/api/student/{dni}', [StudentController::class, 'getStudentByDni']);
    // CONVENIOS DE PASANTIA INDIVIDUALES
    // Acción extra para seleccionar empresa y alumno (POST)
    Route::post('individual-internship-agreements/select-company', [IndividualInternshipAgreementController::class, 'selectCompany'])
        ->name('individual-internship-agreements.select-company');

    Route::get('individual-internship-agreements/fill-form', [IndividualInternshipAgreementController::class, 'fillForm'])
        ->name('individual-internship-agreements.fill-form');

    // Descargar archivo del convenio
    Route::get('individual-internship-agreements/{id}/download', [IndividualInternshipAgreementController::class, 'download'])
        ->name('individual-internship-agreements.download');

    Route::resource('individual-internship-agreements', IndividualInternshipAgreementController::class);

    // Buscar alumnos (para AJAX)
    Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
    //Ruta alumnos
    Route::resource('students', StudentController::class);
});
