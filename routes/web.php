<?php

use App\Http\Controllers\AgreementController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FrameworkAgreementController;
use App\Http\Controllers\FrameworkInternshipAgreementController;
use App\Http\Controllers\FrameworkResidenceAgreementController;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return redirect('/companies');
});

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
Route::resource('/agreements', AgreementController::class);

Route::get('/frameworkAgreement/download', [FrameworkAgreementController::class, 'download'])->name('agreement.download');

Route::resource('/frameworkAgreement', FrameworkAgreementController::class);
Route::get('/frameworkInternshipAgreement/download', [FrameworkInternshipAgreementController::class, 'download'])->name('agreement.download');

Route::resource('frameworkInternshipAgreement', FrameworkInternshipAgreementController::class);
Route::resource('frameworkResidenceAgreement', FrameworkResidenceAgreementController::class);

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



