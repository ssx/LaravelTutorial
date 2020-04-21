<?php

use Illuminate\Support\Facades\Route;

Route::resource('modules', 'ModuleController')
    ->except([
        'create',
        'store',
        'edit',
        'update',
        'destroy'
    ]);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
