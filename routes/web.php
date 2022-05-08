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

Route::get('/home/{id?}/{direction?}', 'HomeController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('clientes');
Route::get('/home/add', 'HomeController@add')
    ->name('clientes.add');
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


Route::get('/clientes/{id?}/{direction?}', 'ClientesController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('clientes');
Route::get('/clientes/add', 'ClientesController@add')
    ->name('clientes.add');
Route::post('/clientes/add', 'ClientesController@store');
Route::get('/clientes/edit/{id?}', 'ClientesController@edit')->name('clientes.edit')->where('id', '[0-9]+');
Route::post('/clientes/edit/{id}', 'ClientesController@update');
Route::get('/clientes/search/', 'ClientesController@search')->name('clientes.search');
Route::get('/clientes/{id?}/search/', 'ClientesController@search')->name('search');
Route::get('/clientes/{id?}/{direction?}/search/', 'ClientesController@search')->name('search');
Route::get('/clientes/delete/{id}', 'ClientesController@delete');
Route::post('/clientes/delete/{id}', 'ClientesController@baja');
Route::get('/clientes/ddjjdomicilio/{id?}', 'ClientesController@ddjjdomicilio');
Route::get('/clientes/map/{id}', 'ClientesController@map');
Route::get('/clientes/importar', 'ClientesController@importar');
Route::get('/clientes/{id?}/importar', 'ClientesController@importar');
Route::post('/clientes/importar', 'ImportController@importlegajos');


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


Route::get('/vehiculos/{id?}/{direction?}', 'VehiculosController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('vehiculos');
Route::get('/vehiculos/add', 'VehiculosController@add')
    ->name('vehiculos.add');
Route::post('/vehiculos/add', 'VehiculosController@store');
Route::get('/vehiculos/edit/{id?}', 'VehiculosController@edit')->name('vehiculos.edit')->where('id', '[0-9]+');
Route::post('/vehiculos/edit/{id}', 'VehiculosController@update');
Route::get('/vehiculos/search/', 'VehiculosController@search')->name('vehiculos.search');
Route::get('/vehiculos/{id?}/search/', 'VehiculosController@search')->name('search');
Route::get('/vehiculos/{id?}/{direction?}/search/', 'VehiculosController@search')->name('search');
Route::get('/vehiculos/delete/{id}', 'VehiculosController@delete');
Route::post('/vehiculos/delete/{id}', 'VehiculosController@baja');
Route::get('/vehiculos/ddjjdomicilio/{id?}', 'VehiculosController@ddjjdomicilio');
Route::get('/vehiculos/map/{id}', 'VehiculosController@map');
Route::get('/vehiculos/importar', 'VehiculosController@importar');
Route::get('/vehiculos/{id?}/importar', 'VehiculosController@importar');
Route::post('/vehiculos/importar', 'ImportController@importlegajos');


Route::get('/repuestos/{id?}/{direction?}', 'RepuestosController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('repuestos');
Route::get('/repuestos/add', 'RepuestosController@add')
    ->name('repuestos.add');
Route::post('/repuestos/add', 'RepuestosController@store');
Route::get('/repuestos/edit/{id?}', 'RepuestosController@edit')->name('repuestos.edit')->where('id', '[0-9]+');
Route::post('/repuestos/edit/{id}', 'RepuestosController@update');
Route::get('/repuestos/search/', 'RepuestosController@search')->name('repuestos.search');
Route::get('/repuestos/{id?}/search/', 'RepuestosController@search')->name('search');
Route::get('/repuestos/{id?}/{direction?}/search/', 'RepuestosController@search')->name('search');
Route::get('/repuestos/delete/{id}', 'RepuestosController@delete');
Route::post('/repuestos/delete/{id}', 'RepuestosController@baja');
Route::get('/repuestos/ddjjdomicilio/{id?}', 'RepuestosController@ddjjdomicilio');
Route::get('/repuestos/map/{id}', 'RepuestosController@map');
Route::get('/repuestos/importar', 'RepuestosController@importar');
Route::get('/repuestos/{id?}/importar', 'RepuestosController@importar');
Route::post('/repuestos/importar', 'ImportController@importlegajos');


Route::get('/reparaciones/{id?}/{direction?}', 'ReparacionesController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('reparaciones');
Route::get('/reparaciones/add', 'ReparacionesController@add')
    ->name('reparaciones.add');
Route::post('/reparaciones/add', 'ReparacionesController@store');
Route::get('/reparaciones/edit/{id?}', 'ReparacionesController@edit')->name('reparaciones.edit')->where('id', '[0-9]+');
Route::post('/reparaciones/edit/{id}', 'ReparacionesController@update');
Route::get('/reparaciones/search/', 'ReparacionesController@search')->name('reparaciones.search');
Route::get('/reparaciones/{id?}/search/', 'ReparacionesController@search')->name('search');
Route::get('/reparaciones/{id?}/{direction?}/search/', 'ReparacionesController@search')->name('search');
Route::get('/reparaciones/delete/{id}', 'ReparacionesController@delete');
Route::post('/reparaciones/delete/{id}', 'ReparacionesController@baja');
Route::get('/reparaciones/ddjjdomicilio/{id?}', 'ReparacionesController@ddjjdomicilio');
Route::get('/reparaciones/map/{id}', 'ReparacionesController@map');
Route::get('/reparaciones/importar', 'ReparacionesController@importar');
Route::get('/reparaciones/{id?}/importar', 'ReparacionesController@importar');
Route::post('/reparaciones/importar', 'ImportController@importlegajos');


Route::get('/ordenes/{id?}/{direction?}', 'OrdenesController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('ordenes');
Route::get('/orden-add', 'OrdenesController@add')
    ->name('ordenes.add');
Route::post('/ordenes/add', 'OrdenesController@store');
Route::get('/ordenes/edit/{id?}', 'OrdenesController@edit')->name('ordenes.edit')->where('id', '[0-9]+');
Route::post('/ordenes/edit/{id}', 'OrdenesController@update');
Route::get('/ordenes/search/', 'OrdenesController@search')->name('ordenes.search');
Route::get('/ordenes/{id?}/search/', 'OrdenesController@search')->name('search');
Route::get('/ordenes/{id?}/{direction?}/search/', 'OrdenesController@search')->name('search');
Route::get('/ordenes/delete/{id}', 'OrdenesController@delete');
Route::post('/ordenes/delete/{id}', 'OrdenesController@baja');
Route::get('/ordenes/ddjjdomicilio/{id?}', 'OrdenesController@ddjjdomicilio');
Route::get('/ordenes/map/{id}', 'OrdenesController@map');
Route::get('/ordenes/importar', 'OrdenesController@importar');
Route::get('/ordenes/{id?}/importar', 'OrdenesController@importar');
Route::post('/ordenes/importar', 'ImportController@importlegajos');

Route::get('/api/clientes', 'OrdenesController@getClientes')
    ->name('clientes');
Route::get('/api/vehiculos/{idCliente?}', 'OrdenesController@getVehiculos')
    ->name('clientes');