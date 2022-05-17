<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CssController;
use App\Http\Controllers\BasicUiController;
use App\Http\Controllers\AdvanceUiController;
use App\Http\Controllers\ExtraComponentsController;
use App\Http\Controllers\BasicTableController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ChartController;

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
    //Auth::logout();
    return view('auth.login');	// view('welcome');
});

/* Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home'); */


//Auth::routes();

// ->middleware(['auth'])


//Route::get('/home/{id?}/{direction?}', 'HomeController@index')
//    ->middleware(['auth'])->name('home')->where(['id' => '[0-9]+', 'direction' => '[-1-9]+']);

require __DIR__.'/auth.php';

Route::get('/home/{id?}/{direction?}', 'CajaController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('home');
Route::get('/home/add', 'HomeController@add')
    ->name('home.add');
Route::post('/home/add', 'HomeController@store');
Route::get('/home/edit/{id?}', 'HomeController@edit')->name('home.edit')->where('id', '[0-9]+');
Route::post('/home/edit/{id}', 'HomeController@update');
Route::get('/home/search/', 'HomeController@search')->name('home.search');
Route::get('/home/{id?}/search/', 'HomeController@search')->name('search');
Route::get('/home/{id?}/{direction?}/search/', 'HomeController@search')->name('search');
Route::get('/home/delete/{id}', 'HomeController@delete');
Route::post('/home/delete/{id}', 'HomeController@baja');
Route::get('/home/ddjjdomicilio/{id?}', 'HomeController@ddjjdomicilio');
Route::get('/home/map/{id}', 'HomeController@map');
Route::get('/home/importar', 'HomeController@importar');
Route::get('/home/{id?}/importar', 'HomeController@importar');
Route::post('/home/importar', 'ImportController@importlegajos');


Route::get('/orden-add', 'CajaController@add')
    ->name('orden.add');
Route::post('/orden-add', 'CajaController@store');

// Apertura de caja
Route::post('/orden-abrir', 'HomeController@apertura');
// Cierre de Caja
Route::get('/caja-cerrar/{id}', 'CajaController@cerrar');


Route::get('/conceptos/{id?}/{direction?}', 'ConceptosController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('clientes');
Route::get('/conceptos/add', 'ConceptosController@add')
    ->name('clientes.add');
Route::post('/conceptos/add', 'ConceptosController@store');
Route::get('/conceptos/edit/{id?}', 'ConceptosController@edit')->name('conceptos.edit')->where('id', '[0-9]+');
Route::post('/conceptos/edit/{id}', 'ConceptosController@update');
Route::get('/conceptos/search/', 'ConceptosController@search')->name('conceptos.search');
Route::get('/conceptos/{id?}/search/', 'ConceptosController@search')->name('search');
Route::get('/conceptos/{id?}/{direction?}/search/', 'ConceptosController@search')->name('search');
Route::get('/conceptos/delete/{id}', 'ConceptosController@delete');
Route::post('/conceptos/delete/{id}', 'ConceptosController@baja');
Route::get('/conceptos/ddjjdomicilio/{id?}', 'ConceptosController@ddjjdomicilio');
Route::get('/conceptos/map/{id}', 'ConceptosController@map');
Route::get('/conceptos/importar', 'ConceptosController@importar');
Route::get('/conceptos/{id?}/importar', 'ConceptosController@importar');
Route::post('/conceptos/importar', 'ImportController@importlegajos');


Route::get('/api/clientes', 'OrdenesController@getClientes')
    ->name('clientes');
Route::get('/api/vehiculos/{idCliente?}', 'OrdenesController@getVehiculos')
    ->name('clientes');