<?php

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
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;



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

