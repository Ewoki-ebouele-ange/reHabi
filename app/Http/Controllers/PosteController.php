<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreatePosteRequest;
use Illuminate\View\View;
use App\Models\Poste;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class PosteController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $postes = \App\Models\Poste::all();
        return view("poste", [
            'postes' => $postes,
            'employes' => null,
        ]);
    }

    public function addi() : View {
        return view("poste.add-poste");
    }

    public function store(CreatePosteRequest $request){
        $poste = Poste::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le poste a bien été ajouté",
        ]);
    }

    public function edit($post){
        $poste = Poste::find($post);
        return response()->json($poste);
    }


    public function update(Poste $poste, CreatePosteRequest $request){
        $poste->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le poste a bien été modifié",
        ]);
    }

    public function list() : View {
        $postes = Poste::all();
        //return view('employe.list', compact('employes'))->render();
        return view("poste.list", [
            'postes' => $postes,
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
            return ! Poste::where('code_poste', $row['code_poste'])->exists();
        });

        //Insertion des lignes filtrées dans la base de données
        if($rows->isNotEmpty()){
            $status = Poste::insert($rows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                return redirect()->route('poste')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('poste')->with('info', "Aucun nouveau poste à importer");
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

    	// 3. On récupère données de la table "postes"
    	$postes = Poste::select("code_poste", "libelle_poste")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($postes->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    public function destroy ($poste) {
        $post = Poste::find($poste);
        if ($post) {
            $post->delete();
            return response()->json(['success' => true, 'message' => "Le poste a bien été supprimé"]);
        } else {
            return response()->json(['success' => false, 'message' => "Le poste n'existe pas"]);
        }
    }

    public function entite ($poste) {
        $post = Poste::find($poste);
        $entites = $post->entite()->get();

        return view("entite", [
            'entites' => $entites,
        ]);
    }

    public function employes ($poste) {
        $post = Poste::find($poste);
        $employes = $post->employes()->get();
        $entite = \App\Models\Entite::all();
        $apps = \App\Models\Application::all();

        return view("employe", [
            'employes' => $employes,
            'entite' => $entite,
            'apps' => $apps,
        ]);
    }

    public function profils ($poste) {
        $post = Poste::find($poste);
        $profils = $post->profils()->get();

        return view("profil", [
            'profils' => $profils,
            'employes' => null,
            'foncts' => null,
            'postes' => $post,
            'applications' => null,
            'apps' => null

        ]);
    }
}
