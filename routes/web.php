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
    Auth::logout();
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

//--------------------------------------
//              OOSS
//--------------------------------------
Route::get('/home/{id?}/{direction?}', 'CajaController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('home');
Route::get('/home/add', 'CajaController@add')
    ->name('home.add');
Route::post('/home/add', 'CajaController@store');
Route::get('/home/edit/{id?}', 'CajaController@edit')->name('home.edit')->where('id', '[0-9]+');
Route::post('/home/edit/{id}', 'CajaController@update');
Route::get('/home/search', 'CajaController@search')->name('obras.search');

Route::get('/obras/search', 'CajaController@search')->name('obras.search');
Route::get('/obras/{id?}/search/', 'CajaController@search')->name('obras.search');
Route::get('/obras/{id?}/{direction?}/search/', 'CajaController@search')->name('search');

//Route::get('/obras/print/{id}', 'CajaController@printpdf')->name('obras.print');
Route::post('/obras/print/', 'CajaController@printpdf')->name('obras.print');
Route::post('/obras/excel/', 'CajaController@excel')->name('obras.excel');
Route::get('/obras/delete/{id}', 'CajaController@delete');
Route::post('/obras/delete/{id}', 'CajaController@baja');

//--------------------------------------
//              OOSS admin
//--------------------------------------
Route::get('/obras-admin/{id?}/{direction?}', 'ObrasAdminController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('obras-admin');
Route::get('/obras-admin/add', 'ObrasAdminController@add')
    ->name('obras-admin.add');
Route::post('/obras-admin/add', 'ObrasAdminController@store');
Route::get('/obras-admin/edit/{id?}', 'ObrasAdminController@edit')->name('obras-admin.edit')->where('id', '[0-9]+');
Route::post('/obras-admin/edit/{id}', 'ObrasAdminController@update');

Route::get('/obras-admin/search', 'ObrasAdminController@search')->name('obras-admin.search');
Route::get('/obras-admin/search', 'ObrasAdminController@search')->name('obras-admin.search');
Route::get('/obras-admin/{id?}/search/', 'ObrasAdminController@search')->name('obras-admin.search');
//Route::get('/obras-admin/print/{id}', 'ObrasAdminController@printpdf')->name('obras-admin.print');
Route::post('/obras-admin/print/', 'ObrasAdminController@printpdf')->name('obras-admin.print');
Route::post('/obras-admin/excel/', 'ObrasAdminController@excel')->name('obras-admin.excel');

Route::get('/obras-admin/{id?}/{direction?}/search/', 'ObrasAdminController@search')->name('search');
Route::get('/obras-admin/delete/{id}', 'ObrasAdminController@delete');
Route::post('/obras-admin/delete/{id}', 'ObrasAdminController@baja');


//-------------------------------------------
//                Cajas
//-------------------------------------------
Route::get('/cajas/{id?}/{direction?}', 'CajaController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('cajas');
Route::get('/cajas/add', 'HomeController@add')
    ->name('cajas.add');
Route::post('/cajas/add', 'HomeController@store');
Route::get('/cajas/edit/{id?}', 'HomeController@edit')->name('cajas.edit')->where('id', '[0-9]+');
Route::post('/cajas/edit/{id}', 'HomeController@update');

Route::get('/cajas/search', 'CajaController@search')->name('cajas.search');
Route::get('/cajas/search', 'CajaController@search')->name('cajas.search');
Route::get('/cajas/{id?}/search/', 'CajaController@search')->name('cajas.search');
//Route::get('/cajas/print/{id}', 'CajaController@printpdf')->name('cajas.print');
Route::post('/cajas/print/', 'CajaController@printpdf')->name('cajas.print');
Route::post('/cajas/excel/', 'CajaController@excel')->name('cajas.excel');

Route::get('/cajas/{id?}/{direction?}/search/', 'CajaController@search')->name('search');
Route::get('/cajas/delete/{id}', 'HomeController@delete');
Route::post('/cajas/delete/{id}', 'HomeController@baja');

//-------------------------------------
//         Comprobantes de cajas
//-------------------------------------
Route::get('/orden-add', 'CajaController@add')->name('orden.add');
Route::post('/orden-add', 'CajaController@store');
Route::get('/orden-add/edit/{id}', 'CajaController@edit')->name('orden.edit');
Route::post('/orden-add/edit/{id}', 'CajaController@update')->name('orden.update');
Route::get('/orden/delete/{id}', 'CajaController@delete')->name('orden.delete');
Route::post('/orden/delete/{id}', 'CajaController@borrar')->name('orden.borrar');

// Apertura de caja
Route::get('/orden-abrir', 'CajaController@preapertura');
Route::post('/orden-abrir', 'CajaController@apertura');
// Cierre de Caja
Route::get('/caja-cerrar/{id}', 'CajaController@cerrar');
Route::post('/caja-cerrar/{id}', 'CajaController@close');

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
Route::get('/conceptos/{id?}/{direction?}/search/', 'ConceptosController@search')->name('conceptos.search');
Route::get('/conceptos/delete/{id}', 'ConceptosController@delete');
Route::post('/conceptos/delete/{id}', 'ConceptosController@baja');
Route::get('/print-concepts', 'ConceptosController@print')->name('conceptos.print');
Route::post('/conceptos/print', 'ConceptosController@printpdf');
Route::post('/conceptos/excel', 'ConceptosController@excel');

// Route::get('/conceptos/ddjjdomicilio/{id?}', 'ConceptosController@ddjjdomicilio');
// Route::get('/conceptos/map/{id}', 'ConceptosController@map');
// Route::get('/conceptos/importar', 'ConceptosController@importar');
// Route::get('/conceptos/{id?}/importar', 'ConceptosController@importar');
// Route::post('/conceptos/importar', 'ImportController@importlegajos');


Route::get('/api/clientes', 'OrdenesController@getClientes')
    ->name('clientes');
Route::get('/api/vehiculos/{idCliente?}', 'OrdenesController@getVehiculos')
    ->name('clientes');