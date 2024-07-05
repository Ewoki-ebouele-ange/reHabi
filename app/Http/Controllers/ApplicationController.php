<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateApplicationRequest;
use Illuminate\View\View;
use App\Models\Application;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $applications = \App\Models\Application::all();
        return view("application", [
            'applications' => $applications,
        ]);
    }

    public function addi() : View {
        return view("application.add-application");
    }


    public function store(CreateApplicationRequest $request){
        $application = Application::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => "L'application a bien été ajoutée",
        ]);
    }

    public function edit($app){

        $application = Application::find($app);
        return response()->json($application);
    
    }


    public function update(Application $application, CreateApplicationRequest $request){
        $application->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => "L'application a bien été modifiée",
        ]);
    }

    public function list() : View {
        $applications = Application::all();
        //return view('employe.list', compact('employes'))->render();
        return view("application.list", [
            'applications' => $applications,
        ]);
    }

    public function import(Request $request) {

        // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    	$this->validate($request, [
    		'fichier' => 'bail|required|file|mimes:xlsx'
    	]);
    	// 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    	$fichier = $request->fichier->move(public_path('storage/'), $request->fichier->hashName());
        // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    	$reader = SimpleExcelReader::create($fichier);
        // On récupère le contenu (les lignes) du fichier
        $rows = $reader->getRows();
        // $rows est une Illuminate\Support\LazyCollection

        // 4. Ajouter les colonnes `created_at` et `updated_at` à chaque ligne
        $currentTimestamp = Carbon::now(); // Récupérer le timestamp actuel
        $rows = $rows->map(function ($row) use ($currentTimestamp) {
            $row['created_at'] = $currentTimestamp;
            $row['updated_at'] = $currentTimestamp;
            return $row;
        });

        //Filtrage des lignes dans la base de données
        $filteredRows = $rows->filter(function($row){
            return ! Application::where('code_application', $row['code_application'])->exists();
        });

        //Insertion des lignes filtrées dans la base de données
        if($rows->isNotEmpty()){
            $status = Application::firstOrCreate($rows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                //return redirect()->route('application')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('application')->with('info', "Aucune nouvelle application à importer");
        }

        // On prend 10 lignes
        /*$reader->take(10);

        // On filtre les lignes en s'assurant que l'adresse email est correcte
        //$rows = $reader->getRows()->filter(function ($ligne) {
            return filter_var($ligne['email'], FILTER_VALIDATE_EMAIL) === true;
        });*/
    }

        // Exporter les données
    public function export (Request $request) {

    	// 1. Validation des informations du formulaire
    	$this->validate($request, [ 
    		'name' => 'bail|required|string',
    		'extension' => 'bail|required|string|in:xlsx,csv'
    	]);

    	// 2. Le nom du fichier avec l'extension : .xlsx ou .csv
    	$file_name = $request->name.".".$request->extension;

    	// 3. On récupère données de la table "clients"
    	$applications = Application::select("code_application", "libelle_application")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($applications->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    public function destroy ($application) {
        $app = Application::find($application);
        if ($app) {
            $app->delete();
            return response()->json(['success' => true, 'message' => "L'application a bien été supprimée"]);
        } else {
            return response()->json(['success' => false, 'message' => "L'application n'existe pas"]);
        }
    }

    public function modules ($application) {
        $app = Application::find($application);
        $modules = $app->modules()->get();

        return view("module", [
            'modules' => $modules,
        ]);
    }

    public function fonctionnalites ($application) {
        $app = Application::find($application);
        $foncts = $app->fonctionnalites()->get();

        return view("fonct", [
            'foncts' => $foncts,
        ]);
    }

    public function profils ($application) {
        $app = Application::find($application);
        $profils = $app->profils()->get();

        return view("profil", [
            'profils' => $profils,
            'employes' => null,
            'foncts' => null,
            'postes' => null,
            'applications' => $app
        ]);
    }
}
