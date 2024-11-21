<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TeacherController;
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
    return view('welcome');
});

// COMPANIES
Route::resource('/companies' , CompanyController::class);

//CITIES
Route::get('/cities/create' , [CityController::class , 'create' ])->name('cities.create');
Route::post('/cities', [CityController::class, 'store'])->name('cities.store');

//TEACHERS
Route::resource('/teachers', TeacherController::class);

//DEPARTMENTS
Route::resource('/departments', DepartmentController::class);
