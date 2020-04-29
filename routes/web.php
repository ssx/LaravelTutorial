<?php

use Illuminate\Support\Facades\Route;

Route::resource('modules', 'ModuleController')
    ->except([
        'edit',
        'update',
        'destroy'
    ]);

Route::resource('modules.tutorials', 'ModuleTutorialController')
    ->only([
        'create',
        'store'
    ]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
