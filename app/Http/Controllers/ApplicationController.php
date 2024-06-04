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

        $applications = \App\Models\Application::paginate(6);
        return view("application", [
            'applications' => $applications,
        ]);
    }

    public function addi() : View {
        return view("application.add-application");
    }

    public function store(CreateApplicationRequest $request){
        $application = Application::create($request->validated());
            return redirect()->route('application')->with('success', "Le application a bien été ajouté");
    }

    public function edit(Application $application): View{
        return view("application.edit-application",[
            'application'=> $application
        ]);
    }

    public function update(Application $application, CreateApplicationRequest $request){
        $application->update($request->validated());
        return redirect()->route('application')->with('success', "Le application a bien été modifié");
    }

    public function showFormImport(): View {
        return view('application.import-application');
    }

    public function showFormExport(): View {
        return view('application.export-application');
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
        if($filteredRows->isNotEmpty()){
            $status = Application::insert($filteredRows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                return redirect()->route('application')->with('success', "Importation reussie");
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

    public function destroy (Application $application) {
        $app = Application::findOrfail($application->id);
        $app->delete();

        return redirect('/application')->with('success','Application ' .$app->code_application. ' supprimée avec succès');
    }
}