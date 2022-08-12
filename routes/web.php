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
use App\Http\Controllers\PostController;

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

//-----------------------------------------------------
//          Rutas usadas en componenes vue.js
//-----------------------------------------------------
Route::get('search', [PostController::class, 'index']);
Route::get('searchOoss', [PostController::class, 'searchOoss']);
Route::get('searchProfessional', [PostController::class, 'searchProfessional']);
Route::get('get-oss', [PostController::class, 'getobras']);
Route::get('get-prof', [PostController::class, 'getProfessionals']);
Route::get('searchPrecios/{id?}', [PostController::class, 'searchPrecios']);

/* Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home'); */


//Auth::routes();

// ->middleware(['auth'])


//Route::get('/home/{id?}/{direction?}', 'HomeController@index')
//    ->middleware(['auth'])->name('home')->where(['id' => '[0-9]+', 'direction' => '[-1-9]+']);

require __DIR__.'/auth.php';

//-------------------------------------------
//                Profesionales
//-------------------------------------------
Route::get('/profesionales/{id?}/{direction?}', 'ProfesionalesController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('profesionales');
Route::get('/profesionales/add', 'ProfesionalesController@add')
    ->name('profesionales.add');
Route::post('/profesionales/add', 'ProfesionalesController@store');
Route::get('/profesionales/edit/{id?}', 'ProfesionalesController@edit')->name('profesionales.edit')->where('id', '[0-9]+');
Route::post('/profesionales/edit/{id}', 'ProfesionalesController@update');

Route::get('/profesionales/search', 'ProfesionalesController@search')->name('profesionales.search');
Route::get('/profesionales/{id?}/search/', 'ProfesionalesController@search')->name('profesionales.search');
//Route::get('/profesionales/print/{id}', 'ProfesionalesController@printpdf')->name('profesionales.print');
Route::post('/profesionales/print/', 'ProfesionalesController@printpdf')->name('profesionales.print');
Route::post('/profesionales/excel/', 'ProfesionalesController@excel')->name('profesionales.excel');

Route::get('/profesionales/{id?}/{direction?}/search/', 'CajaController@search')->name('search');
Route::get('/profesionales/delete/{id}', 'ProfesionalesController@delete');
Route::post('/profesionales/delete/{id}', 'ProfesionalesController@baja');


//-------------------------------------------
//             Adm.Obras sociales
//-------------------------------------------
Route::get('/obras-admin/{id?}/{direction?}', 'ObrasadminController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('obras-admin');
Route::get('/obras-admin/add', 'ObrasadminController@add')
    ->name('obras-admin.add');
Route::post('/obras-admin/add', 'ObrasadminController@store');
Route::get('/obras-admin/edit/{id?}', 'ObrasadminController@edit')->name('obras-admin.edit')->where('id', '[0-9]+');
Route::post('/obras-admin/edit/{id}', 'ObrasadminController@update');

Route::get('/obras-admin/search', 'ObrasadminController@search')->name('obras-admin.search');
Route::get('/obras-admin/search', 'ObrasadminController@search')->name('obras-admin.search');
Route::get('/obras-admin/{id?}/search/', 'ObrasadminController@search')->name('obras-admin.search');
//Route::get('/obras-admin/print/{id}', 'ObrasadminController@printpdf')->name('obras-admin.print');
Route::post('/obras-admin/print/', 'ObrasadminController@printpdf')->name('obras-admin.print');
Route::post('/obras-admin/excel/', 'ObrasadminController@excel')->name('obras-admin.excel');

Route::get('/obras-admin/{id?}/{direction?}/search/', 'CajaController@search')->name('search');
Route::get('/obras-admin/delete/{id}', 'ObrasadminController@delete');
Route::post('/obras-admin/delete/{id}', 'ObrasadminController@baja');

//-------------------------------------------
//                Convenios
//-------------------------------------------
Route::get('/convenios/{id?}/{direction?}', 'ConveniosController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('convenios');
Route::get('/convenios/add', 'ConveniosController@add')
    ->name('convenios.add');
Route::post('/convenios/add', 'ConveniosController@store');
Route::get('/convenios/edit/{id?}', 'ConveniosController@edit')->name('convenios.edit')->where('id', '[0-9]+');
Route::post('/convenios/edit/{id}', 'ConveniosController@update');

Route::get('/convenios/search', 'ConveniosController@search')->name('convenios.search');
Route::get('/convenios/search', 'ConveniosController@search')->name('convenios.search');
Route::get('/convenios/{id?}/search/', 'ConveniosController@search')->name('convenios.search');
//Route::get('/convenios/print/{id}', 'ConveniosController@printpdf')->name('convenios.print');
Route::post('/convenios/print/', 'ConveniosController@printpdf')->name('convenios.print');
Route::post('/convenios/excel/', 'ConveniosController@excel')->name('convenios.excel');

Route::get('/convenios/{id?}/{direction?}/search/', 'ConveniosController@search')->name('search');
Route::get('/convenios/delete/{id}', 'ConveniosController@delete');
Route::post('/convenios/delete/{id}', 'ConveniosController@baja');

//-------------------------------------------
//                Nomencladores
//-------------------------------------------
Route::get('/nomenclador/{id?}/{direction?}', 'NomencladorController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('nomenclador');
Route::get('/nomenclador/add', 'NomencladorController@add')
    ->name('nomenclador.add');
Route::post('/nomenclador/add', 'NomencladorController@store');
Route::get('/nomenclador/edit/{id?}', 'NomencladorController@edit')->name('nomenclador.edit')->where('id', '[0-9]+');
Route::post('/nomenclador/edit/{id}', 'NomencladorController@update');

Route::get('/nomenclador/search', 'NomencladorController@search')->name('nomenclador.search');
Route::get('/nomenclador/search', 'NomencladorController@search')->name('nomenclador.search');
Route::get('/nomenclador/{id?}/search/', 'NomencladorController@search')->name('nomenclador.search');
//Route::get('/nomenclador/print/{id}', 'NomencladorController@printpdf')->name('nomenclador.print');
Route::post('/nomenclador/print/', 'NomencladorController@printpdf')->name('nomenclador.print');
Route::post('/nomenclador/excel/', 'NomencladorController@excel')->name('nomenclador.excel');

Route::get('/nomenclador/{id?}/{direction?}/search/', 'NomencladorController@search')->name('search');
Route::get('/nomenclador/delete/{id}', 'NomencladorController@delete');
Route::post('/nomenclador/delete/{id}', 'NomencladorController@baja');

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

//-------------------------------------------
//                Ordenes
//-------------------------------------------
Route::get('/carga-ordenes/{id?}/{direction?}', 'OrdenesController@index')
    ->where(['id' => '[0-9]+', 'direction' => '[-1-9]+'])
    ->name('convenios');
Route::get('/carga-ordenes/add', 'OrdenesController@add')
    ->name('convenios.add');
Route::post('/carga-ordenes/add', 'OrdenesController@store');
Route::get('/carga-ordenes/edit/{id?}', 'OrdenesController@edit')->name('convenios.edit')->where('id', '[0-9]+');
Route::post('/carga-ordenes/edit/{id}', 'OrdenesController@update');

Route::get('/carga-ordenes/search', 'OrdenesController@search')->name('convenios.search');
Route::get('/carga-ordenes/search', 'OrdenesController@search')->name('convenios.search');
Route::get('/carga-ordenes/{id?}/search/', 'OrdenesController@search')->name('convenios.search');
//Route::get('/carga-ordenes/print/{id}', 'OrdenesController@printpdf')->name('convenios.print');
Route::post('/carga-ordenes/print/', 'OrdenesController@printpdf')->name('convenios.print');
Route::post('/carga-ordenes/excel/', 'OrdenesController@excel')->name('convenios.excel');

Route::get('/carga-ordenes/{id?}/{direction?}/search/', 'OrdenesController@search')->name('search');
Route::get('/carga-ordenes/delete/{id}', 'OrdenesController@delete');
Route::post('/carga-ordenes/delete/{id}', 'OrdenesController@baja');

Route::get('/api/ordenes/{obra?}/{matricula?}/{periodo?}', 'OrdenesController@laodorders')
    ->where(['obra' => '[0-9]+', 'matricula' => '[-1-9]+'])
    ->name('apiorders');

Route::get('/print-orders', 'OrdenesController@print2')->name('orders.print');
Route::post('/print-orders/print/', 'OrdenesController@printpdf2')->name('orders.print');
Route::post('/print-orders/excel/', 'OrdenesController@excel2')->name('orders.excel');

Route::post('/carga-ordenes/print/', 'OrdenesController@printpdf')->name('convenios.print');
Route::post('/carga-ordenes/excel/', 'OrdenesController@excel')->name('convenios.excel');


// Route::get('/search', 'OrdenesController@search2');
// Route::post('/action', 'OrdenesController@search3');


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
//              Facturacion
//-------------------------------------
Route::get('/facturacion/{id?}', 'FacturarController@index')->name('facturar.index');

//-------------------------------------
//        Comprobantes de cajas
//-------------------------------------
Route::get('/orden-add/{id_caja?}', 'CajaController@add')->name('orden.add');
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



Route::get('/page-faq', 'ConceptosController@info');

// Route::get('/conceptos/ddjjdomicilio/{id?}', 'ConceptosController@ddjjdomicilio');
// Route::get('/conceptos/map/{id}', 'ConceptosController@map');
// Route::get('/conceptos/importar', 'ConceptosController@importar');
// Route::get('/conceptos/{id?}/importar', 'ConceptosController@importar');
// Route::post('/conceptos/importar', 'ImportController@importlegajos');


// Route::get('/api/clientes', 'OrdenesController@getClientes')
//     ->name('clientes');
// Route::get('/api/vehiculos/{idCliente?}', 'OrdenesController@getVehiculos')
//     ->name('clientes');