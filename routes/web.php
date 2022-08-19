<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\OcupacionController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\FamiliarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

Route::get('/home', [ResidenteController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('residentes', ResidenteController::class);
    Route::resource('ocupacions', OcupacionController::class);
    Route::resource('personals', PersonalController::class);
    Route::resource('familiars', FamiliarController::class);
});
