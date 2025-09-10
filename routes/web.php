<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
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


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', fn() => redirect('/companies'));

// --- Dashboard (sin 'verified') ---
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth'])
    ->name('dashboard');

// --- Perfil protegido ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- APP (podés envolver todo en 'auth' si querés exigir login global) ---
Route::middleware('auth')->group(function () {


    // COMPANIES
    Route::resource('/companies', CompanyController::class);

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

    Route::get('/buscarConvenio/{company}', [FrameworkResidenceAgreementController::class, 'searchAgreementByCompany']);


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


require __DIR__ . '/auth.php';
