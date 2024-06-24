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
    Route::post('/add', 'store')->name('employe.add');
    Route::get('/{employe}/edit', 'edit')->name('employe.edit');
    Route::post('/{employe}', 'update')->name('employe.update');
    Route::get('/list', 'list')->name('employe.list');
    Route::post('/add/import', 'EmployeController@import')->name('employe.import');
    Route::post('/add/export', 'export')->name('employe.export');
    Route::delete('/delete/{employe}', 'destroy')->name('employe.destroy');
    Route::get('/{employe}/poste', 'poste')->name('employe.poste');

});

//Entité
Route::prefix('/entite')->middleware('auth')->controller(\EntiteController::class)->group(function () {
    Route::get('/', 'index')->name('entite');
    Route::post('/add', 'store')->name('entite.add');
    Route::get('/{entite}/edit', 'edit')->name('entite.edit');
    Route::post('/{entite}', 'update')->name('entite.update');
    Route::get('/list', 'list')->name('entite.list');
    Route::post('/add/import', 'EntiteController@import')->name('entite.import');
    Route::post('/add/export', 'export')->name('entite.export');
    Route::delete('/delete/{entite}', 'destroy')->name('entite.destroy');
    Route::get('/{entite}/postes', 'postes')->name('entite.postes');
    Route::get('/{entite}/employes', 'employes')->name('entite.employes');
});

//Poste
Route::prefix('/poste')->middleware('auth')->controller(\PosteController::class)->group(function () {
    Route::get('/', 'index')->name('poste');
    Route::post('/add', 'store')->name('poste.add');
    Route::get('/{poste}/edit', 'edit')->name('poste.edit');
    Route::post('/{poste}', 'update')->name('poste.update');
    Route::get('/list', 'list')->name('poste.list');
    Route::post('/add/import', 'PosteController@import')->name('poste.import');
    Route::post('/add/export', 'export')->name('poste.export');
    Route::delete('/delete/{poste}', 'destroy')->name('poste.destroy');
    Route::get('/{poste}/entite', 'entite')->name('poste.entite');
    Route::get('/{poste}/employes', 'employes')->name('poste.employes');
    Route::get('/{poste}/profils', 'profils')->name('poste.profils');
});

//Application
Route::prefix('/application')->middleware('auth')->controller(\ApplicationController::class)->group(function () {
    Route::get('/', 'index')->name('application');
    Route::post('/add', 'store')->name('application.add');
    Route::get('/{application}/edit', 'edit')->name('application.edit');
    Route::post('/{application}', 'update')->name('application.update');
    Route::get('/list', 'list')->name('application.list');
    Route::post('/add/import', 'ApplicationController@import')->name('application.import');
    Route::post('/add/export', 'export')->name('application.export');
    Route::delete('/delete/{application}', 'destroy')->name('application.destroy');
    Route::get('/{application}/modules', 'modules')->name('application.modules');
    Route::get('/{application}/fonctionnalites', 'fonctionnalites')->name('application.fonctionnalites');
});

//Module
Route::prefix('/module')->middleware('auth')->controller(\ModuleController::class)->group(function () {
    Route::get('/', 'index')->name('module');
    Route::post('/add', 'store')->name('module.add');
    Route::get('/{module}/edit', 'edit')->name('module.edit');
    Route::post('/{module}', 'update')->name('module.update');
    Route::get('/list', 'list')->name('module.list');
    Route::post('/add/import', 'ModuleController@import')->name('module.import');
    Route::post('/add/export', 'export')->name('module.export');
    Route::delete('/delete/{module}', 'destroy')->name('module.destroy');
    Route::get('/{module}/fonctionnalites', 'fonctionnalites')->name('module.fonctionnalites');
    Route::get('/{module}/application', 'app')->name('module.app');
});

//Fonctionnalité
Route::prefix('/fonct')->middleware('auth')->controller(\FonctController::class)->group(function () {
    Route::get('/', 'index')->name('fonct');
    Route::post('/add', 'store')->name('fonct.add');
    Route::get('/{fonct}/edit', 'edit')->name('fonct.edit');
    Route::post('/{fonct}', 'update')->name('fonct.update');
    Route::get('/list', 'list')->name('fonct.list');
    Route::post('/add/import', 'FonctController@import')->name('fonct.import');
    Route::post('/add/export', 'export')->name('fonct.export');
    Route::delete('/delete/{fonct}', 'destroy')->name('fonct.destroy');
    Route::get('/{fonct}/module', 'module')->name('fonct.module');
    Route::get('/{fonct}/profils', 'profils')->name('fonct.profils');
});

//Profil
Route::prefix('/profil')->middleware('auth')->controller(\ProfilController::class)->group(function () {
    Route::get('/', 'index')->name('profil');
    Route::post('/add', 'store')->name('profil.add');
    Route::get('/{profil}/edit', 'edit')->name('profil.edit');
    Route::post('/{profil}', 'update')->name('profil.update');
    Route::get('/list', 'list')->name('profil.list');
    Route::post('/add/import', 'ProfilController@import')->name('profil.import');
    Route::post('/add/export', 'export')->name('profil.export');
    Route::delete('/delete/{profil}', 'destroy')->name('profil.destroy');
    Route::get('/{profil}/fonctionnalites', 'fonctionnalites')->name('profil.fonctionnalites');
    Route::get('/{profil}/postes', 'postes')->name('profil.postes');
});

//importer fichier
Route::post('/poste/add/importEEP', 'ImporterFichier@import')->name('importEEP');
Route::post('/profil/add/importFP', 'ImporterFichier@importFonctProfil')->name('importFP');
Route::post('/poste/add/importPP', 'ImporterFichier@importProfilPoste')->name('importPP');
Route::post('/profil/add/importIC', 'ImporterFichier@importAndCompare')->name('importIC');
//Route::get('/importer', 'ImporterFichier@import')->name('import');
