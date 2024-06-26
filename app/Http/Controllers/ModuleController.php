<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateModuleRequest;
use Illuminate\View\View;
use App\Models\Module;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $modules = \App\Models\Module::all();
        return view("module", [
            'modules' => $modules,
        ]);
    }

    public function addi() : View {
        return view("module.add-module");
    }

    public function store(CreateModuleRequest $request){
        $module = Module::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le module a bien été ajouté",
        ]);
    }

    public function edit($mod){
        $module = Module::find($mod);
        return response()->json($module);
    }


    public function update(Module $module, CreateModuleRequest $request){
        $module->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le module a bien été modifié",
        ]);
    }

    public function list() : View{
        $modules = Module::all();
        //return view('employe.list', compact('employes'))->render();
        return view("module.list", [
            'modules' => $modules,
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
            return ! Module::where('code_module', $row['code_module'])->exists();
        });

        // $rowmod = $rows->map(function($row){
        //     return $row["code_fonct"];
        // });

        //Insertion des lignes filtrées dans la base de données
        if($rows->isNotEmpty()){
            $status = Module::firstOrCreate($rows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                return redirect()->route('module')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('module')->with('info', "Aucun nouveau module à importer");
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
    	$modules = Module::select("code_module", "libelle_module")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($modules->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    public function destroy ($module) {
        $mod = Module::find($module);
        if ($mod) {
            $mod->delete();
            return response()->json(['success' => true, 'message' => "Le module a bien été supprimé"]);
        } else {
            return response()->json(['success' => false, 'message' => "Le module n'existe pas"]);
        }
    }

    public function app ($module) {
        $mod = Module::find($module);
        $applications = $mod->application()->get();

        return view("application", [
            'applications' => $applications,
        ]);
    }

    public function fonctionnalites ($module) {
        $mod = Module::find($module);
        $foncts = $mod->fonctionnalites()->get();

        return view("fonct", [
            'foncts' => $foncts,
        ]);
    }
}
