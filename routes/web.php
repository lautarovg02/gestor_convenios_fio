<?php


use App\Http\Controllers\CareerController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\EmployeeController;
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
Route::resource('/companies' , CompanyController::class);

// EMPLOYEES
Route::resource('companies.employees', EmployeeController::class)->shallow();

//CITIES
Route::get('/cities/create' , [CityController::class , 'create' ])->name('cities.create');
Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

//TEACHERS
Route::resource('/teachers', TeacherController::class);

//CAREERS
Route::resource('/careers' , CareerController::class);

//DEPARTMENTS
route::resource('/departments', DepartmentController::class);
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

