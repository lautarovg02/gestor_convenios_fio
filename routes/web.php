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



// Mostrar listado de empresas para seleccionar
Route::get('/convenio-individual-pasantia/create', [IndividualInternshipAgreementController::class, 'create'])
    ->name('individualInternshipAgreement.create');

// Guardar convenio individual (POST)
Route::post('/convenio-individual-pasantia/store', [IndividualInternshipAgreementController::class, 'store'])
    ->name('individualInternshipAgreement.store');



// Procesar selección de empresa (POST)
Route::post('/convenio-individual-pasantia/seleccionar', [IndividualInternshipAgreementController::class, 'seleccionarEmpresa'])
    ->name('empresa.seleccionar');
// Vista éxito
Route::get('/individual-internship-agreement/success/{id}', [IndividualInternshipAgreementController::class, 'success'])
    ->name('individualInternshipAgreement.success');

// Ruta para descargar el archivo (ajustar según cómo generes o guardes el PDF)
Route::get('/convenios-individuales/{id}/descargar', [IndividualInternshipAgreementController::class, 
'download'])->name('individualInternshipAgreement.download');
