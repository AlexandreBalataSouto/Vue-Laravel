<?php

use Illuminate\Support\Facades\Route;

Route::get('/{optional?}', function () {
    return view('index');
});

Route::get('/home', 'HomeController@index')->name('home');

/*
Route::middleware(['auth'])->group(function () {
    
});
*/

Auth::routes();
