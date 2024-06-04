<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateFonctRequest;
use Illuminate\View\View;
use App\Models\Fonctionnalite;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class FonctController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $foncts = \App\Models\Fonctionnalite::paginate(6);
        return view("fonct", [
            'foncts' => $foncts,
        ]);
    }

    public function addi() : View {
        return view("fonct.add-fonct");
    }

    public function store(CreateFonctRequest $request){
        $fonct = Fonctionnalite::create($request->validated());
            return redirect()->route('fonct')->with('success', "La fonctionnalité a bien été ajouté");
    }

    public function edit(Fonctionnalite $fonct): View{
        return view("fonct.edit-fonct",[
            'fonct'=> $fonct
        ]);
    }

    public function update(Fonctionnalite $fonct, CreateFonctRequest $request){
        $fonct->update($request->validated());
        return redirect()->route('fonct')->with('success', "La fonctionnalité a bien été modifié");
    }

    public function showFormImport(): View {
        return view('fonct.import-fonct');
    }

    public function showFormExport(): View {
        return view('fonct.export-fonct');
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

        // 5. On insère toutes les lignes dans la base de données
        $status = Fonctionnalite::insert($rows->toArray());
        // Si toutes les lignes sont insérées
    	if ($status) {
            // 6. On supprime le fichier uploadé
            $reader->close(); // On ferme le $reader
            
            //File::delete($fichier);
            //unlink($fichier);
            // 7. Retour vers le formulaire avec un message $msg
            return redirect()->route('fonct')->with('success', "Importation reussie");
        } else { abort(500); }

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
}
