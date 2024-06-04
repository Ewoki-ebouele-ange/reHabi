<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProfilRequest;
use Illuminate\View\View;
use App\Models\Profil;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $profils = \App\Models\Profil::paginate(6);
        return view("profil", [
            'profils' => $profils,
        ]);
    }

    public function addi() : View {
        return view("profil.add-profil");
    }

    public function store(CreateProfilRequest $request){
        $profil = Profil::create($request->validated());
            return redirect()->route('profil')->with('success', "Le profil a bien été ajouté");
    }

    public function edit(Profil $profil): View{
        return view("profil.edit-profil",[
            'profil'=> $profil
        ]);
    }

    public function update(Profil $profil, CreateProfilRequest $request){
        $profil->update($request->validated());
        return redirect()->route('profil')->with('success', "Le profil a bien été modifié");
    }

    public function showFormImport(): View {
        return view('profil.import-profil');
    }

    public function showFormExport(): View {
        return view('profil.export-profil');
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
            return ! Profil::where('code_profil', $row['code_profil'])->exists() ;
        });

        //Insertion des lignes filtrées dans la base de données
        if($filteredRows->isNotEmpty()){
            $status = Profil::insert($filteredRows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                // unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                return redirect()->route('profil')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('profil')->with('info', "Aucun nouveau profil à importer");
        }

        // 5. On insère toutes les lignes dans la base de données
        //$status = Profil::insert($rows->toArray());
        // Si toutes les lignes sont insérées
    	

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
    	$profils = Profil::select("code_profil", "libelle_profil")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($profils->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();
    }

    public function destroy (Profil $profil) {
        $prof = Profil::findOrfail($profil->id);
        $prof->delete();

        return redirect('/profil')->with('success','Profil ' .$prof->code_profil. ' supprimé avec succès');
    }
}