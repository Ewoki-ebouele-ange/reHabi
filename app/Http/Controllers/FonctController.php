<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateFonctRequest;
use Illuminate\View\View;
use App\Models\Fonctionnalite;
use App\Models\Module;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class FonctController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $foncts = \App\Models\Fonctionnalite::all();
        return view("fonct", [
            'foncts' => $foncts,
        ]);
    }

    public function addi() : View {
        return view("fonct.add-fonct");
    }

    public function store(CreateFonctRequest $request){
        $fonct = Fonctionnalite::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => "La fonctionnalité a bien été ajoutée",
        ]);
    }

    public function edit($fonction){

        $fonct = Fonctionnalite::find($fonction);
        return response()->json($fonct);
    
    }


    public function update(Fonctionnalite $fonct, CreateFonctRequest $request){
        $fonct->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => "La fonctionnalité a bien été modifiée",
        ]);
    }

    public function list() : View
    {
        $foncts = Fonctionnalite::all();
        return view("fonct.list", [
            'foncts' => $foncts,
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
            return ! Fonctionnalite::where('code_fonct', $row['code_fonct'])->exists();
        });

        $rowmod = $rows->map(function($row){
            return $row["code_module"];
        });

        //dd($rowmod->toArray());

        

        //Insertion des lignes filtrées dans la base de données
        if($filteredRows->isNotEmpty()){
            $status = Fonctionnalite::insert($filteredRows->toArray());
            
            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                return redirect()->route('fonct')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('fonct')->with('info', "Aucune nouvelle fonctionnalité à importer");
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
    	$foncts = Fonctionnalite::select("code_fonct", "libelle_fonct")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($foncts->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    public function destroy ($fonct) {
        $fonc = Fonctionnalite::find($fonct);
        if ($fonc) {
            $fonc->delete();
            return response()->json(['success' => true, 'message' => "La fonctionnalité a bien été supprimée"]);
        } else {
            return response()->json(['success' => false, 'message' => "La fonctionnalité n'existe pas"]);
        }
    }
}
