<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';



// Admin 
Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function(){
    Route::namespace('Auth')->middleware('guest:admin')->group(function(){
        // login route
        Route::get('login','AuthenticatedSessionController@create')->name('login');
        Route::post('login','AuthenticatedSessionController@store')->name('adminlogin');
    });
    Route::middleware('admin')->group(function(){
        Route::get('dashboard','HomeController@index')->name('dashboard');

        Route::get('admin-test','HomeController@adminTest')->name('admintest');
        Route::get('editor-test','HomeController@editorTest')->name('editortest');

        Route::resource('posts','PostController');

    });
    Route::post('logout','Auth\AuthenticatedSessionController@destroy')->name('logout');



//users
    Route::prefix('/users')->middleware('auth:admin')->controller(\UsersController::class)->group(function () {
        Route::get('/','index')->name('user');
        Route::get('/add','addi')->name('user.add');
        Route::get('/{user}/edit', 'edit')->name('user.edit');
        Route::post('/{user}/edit', 'update');
        Route::post('/add/import', 'import')->name('user.import');
        Route::get('/add/import', 'showFormImport')->name('user.import');
        Route::post('/add/export', 'export')->name('user.export');
        Route::get('/add/export', 'showFormExport')->name('user.export');
        Route::post('/{user}/delete', 'destroy')->name('user.destroy');

        
    });

    
});

Route::post('/admin/users/add', 'Auth\RegisteredUserController@store')->middleware('auth:admin')->name('admin.user.add');



//Employé
Route::prefix('/employe')->middleware('auth')->controller(\EmployeController::class)->group(function () {
    Route::get('/', 'index')->name('employe');
    Route::get('/add','addi')->name('employe.add');
    Route::post('/add', 'store');
    Route::get('/{employe}/edit', 'edit')->name('employe.edit');
    Route::post('/{employe}/edit', 'update');
    Route::post('/add/import', 'import')->name('employe.import');
    Route::get('/add/import', 'showFormImport')->name('employe.import');
    Route::post('/add/export', 'export')->name('employe.export');
    Route::get('/add/export', 'showFormExport')->name('employe.export');
    Route::post('/{employe}/delete', 'destroy')->name('employe.destroy');

});

//Entité
Route::prefix('/entite')->middleware('auth')->controller(\EntiteController::class)->group(function () {
    Route::get('/', 'index')->name('entite');
    Route::get('/add','addi')->name('entite.add');
    Route::post('/add', 'store');
    Route::get('/{entite}/edit', 'edit')->name('entite.edit');
    Route::post('/{entite}/edit', 'update');
    Route::post('/add/import', 'import')->name('entite.import');
    Route::get('/add/import', 'showFormImport')->name('entite.import');
    Route::post('/add/export', 'export')->name('entite.export');
    Route::get('/add/export', 'showFormExport')->name('entite.export');
    Route::post('/{entite}/delete', 'destroy')->name('entite.destroy');

});

//Poste
Route::prefix('/poste')->middleware('auth')->controller(\PosteController::class)->group(function () {
    Route::get('/', 'index')->name('poste');
    Route::get('/add','addi')->name('poste.add');
    Route::post('/add', 'store');
    Route::get('/{poste}/edit', 'edit')->name('poste.edit');
    Route::post('/{poste}/edit', 'update');
    Route::post('/add/import', 'import')->name('poste.import');
    Route::get('/add/import', 'showFormImport')->name('poste.import');
    Route::post('/add/export', 'export')->name('poste.export');
    Route::get('/add/export', 'showFormExport')->name('poste.export');
    Route::post('/{poste}/delete', 'destroy')->name('poste.destroy');

});

//Application
Route::prefix('/application')->middleware('auth')->controller(\ApplicationController::class)->group(function () {
    Route::get('/', 'index')->name('application');
    Route::get('/add','addi')->name('application.add');
    Route::post('/add', 'store');
    Route::get('/{application}/edit', 'edit')->name('application.edit');
    Route::post('/{application}/edit', 'update');
    Route::post('/add/import', 'import')->name('application.import');
    Route::get('/add/import', 'showFormImport')->name('application.import');
    Route::post('/add/export', 'export')->name('application.export');
    Route::get('/add/export', 'showFormExport')->name('application.export');
    Route::post('/{application}/delete', 'destroy')->name('application.destroy');

});

//Module
Route::prefix('/module')->middleware('auth')->controller(\ModuleController::class)->group(function () {
    Route::get('/', 'index')->name('module');
    Route::get('/add','addi')->name('module.add');
    Route::post('/add', 'store');
    Route::get('/{module}/edit', 'edit')->name('module.edit');
    Route::post('/{module}/edit', 'update');
    Route::post('/add/import', 'import')->name('module.import');
    Route::get('/add/import', 'showFormImport')->name('module.import');
    Route::post('/add/export', 'export')->name('module.export');
    Route::get('/add/export', 'showFormExport')->name('module.export');
    Route::post('/{module}/delete', 'destroy')->name('module.destroy');

});

//Fonctionnalité
Route::prefix('/fonct')->middleware('auth')->controller(\FonctController::class)->group(function () {
    Route::get('/', 'index')->name('fonct');
    Route::get('/add','addi')->name('fonct.add');
    Route::post('/add', 'store');
    Route::get('/{fonct}/edit', 'edit')->name('fonct.edit');
    Route::post('/{fonct}/edit', 'update');
    Route::post('/add/import', 'import')->name('fonct.import');
    Route::get('/add/import', 'showFormImport')->name('fonct.import');
    Route::post('/add/export', 'export')->name('fonct.export');
    Route::get('/add/export', 'showFormExport')->name('fonct.export');
    Route::post('/{fonct}/delete', 'destroy')->name('fonct.destroy');
});

//Profil
Route::prefix('/profil')->middleware('auth')->controller(\ProfilController::class)->group(function () {
    Route::get('/', 'index')->name('profil');
    Route::get('/add','addi')->name('profil.add');
    Route::post('/add', 'store');
    Route::get('/{profil}/edit', 'edit')->name('profil.edit');
    Route::post('/{profil}/edit', 'update');
    Route::post('/add/import', 'import')->name('profil.import');
    Route::get('/add/import', 'showFormImport')->name('profil.import');
    Route::post('/add/export', 'export')->name('profil.export');
    Route::get('/add/export', 'showFormExport')->name('profil.export');
    Route::post('/{profil}/delete', 'destroy')->name('profil.destroy');
});

//importer fichier
Route::post('/employe', 'ImporterFichier@import')->name('importEEP');
Route::post('/profil', 'ImporterFichier@importFonctProfil')->name('importFP');
//Route::get('/importer', 'ImporterFichier@import')->name('import');
