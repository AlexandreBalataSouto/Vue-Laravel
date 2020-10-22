<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('posts', 'PostController@index');
Route::get('post/{slug}', 'PostController@show');
Route::post('posts', 'PostController@store');
Route::get('posts', 'PostController@index');
Route::put('posts/{id}','PostController@update');
Route::delete('posts/{id}','PostController@destroy');

Route::post('notas', 'NotaController@store');
Route::get('notas', 'NotaController@index');
Route::put('notas/{id}','NotaController@update');
Route::delete('notas/{id}','NotaController@destroy');

Route::post('eventos', 'EventoController@store');
Route::get('eventos', 'EventoController@index');
Route::put('eventos/{id}','EventoController@update');
Route::delete('eventos/{id}','EventoController@destroy');
