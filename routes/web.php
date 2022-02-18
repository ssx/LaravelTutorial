<?php

use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleTutorialController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('modules', ModuleController::class)
    ->except([
        'edit',
        'update',
        'destroy'
    ]);

Route::resource('modules.tutorials', ModuleTutorialController::class)
    ->only([
        'create',
        'store'
    ]);
