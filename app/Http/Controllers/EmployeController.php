<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateEmployeRequest;
use Illuminate\View\View;
use App\Models\Employe;
use App\Models\Poste;
use App\Models\Profil;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EmployeController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $employes = \App\Models\Employe::all();
        $entite = \App\Models\Entite::all();
        $apps = \App\Models\Application::all();
        return view("employe", [
            'employes' => $employes,
            'entite' => $entite,
            'apps' => $apps,
        ]);
    }

    public function store(CreateEmployeRequest $request){
        $employe = Employe::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => "L'employé a bien été modifié",
        ]);
            //return redirect()->route('employe')->with('success', "L'employé a bien été ajouté");
    }

    public function edit($employ){
        $employe = Employe::find($employ);
        return response()->json($employe);
    }

    public function update(Employe $employe, CreateEmployeRequest $request){
        $employe->update($request->validated());
       // $nom = $request->validated()->nom;

        return response()->json([
            'success' => true,
            'message' => "L'employé ".$employe->nom." a bien été modifié",
        ]);
        //return redirect()->route('employe')->with('success', "L'employé a bien été modifié");
    }

    public function assignPoste (Employe $employe, Request $request){
        
            $poste_input = $request->poste_input;
            $deb_fonct = $request->deb_fonct;
            $fin_fonct = $request->fin_fonct;

            $pos = Poste::findOrFail($poste_input);

            $employe->postes()->syncWithoutDetaching([
                $poste_input => [
                    'date_debut_fonction' => $deb_fonct, 
                    'date_fin_fonction' => NULL,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);

            DB::table('employe_poste')
                ->where('employe_id', $employe->id)
                ->where('poste_id', $employe->postePrecedent()->id)
                ->update([
                    'date_fin_fonction' => $fin_fonct,
                    'updated_at' => Carbon::now(),
            ]);
    
            return response()->json([
                'success' => true,
                'message' => "Le poste ".$pos->libelle_poste." a bien été assigné à ".$employe->nom,
            ]);
    }

    public function assignProfil (Employe $employe, Request $request){
        
        $profil_input = $request->profil_input;
        $ass_profil = $request->ass_profil;

        $prf = Profil::findOrFail($profil_input);

        // dd($prf);

        $employe->profils()->syncWithoutDetaching([
            $profil_input => [
                'date_assignation' => $ass_profil, 
                'date_suspension' => NULL, 
                'date_derniere_modification' => $ass_profil, 
                'date_derniere_connexion' => NULL,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => "Le profil ".$prf->libelle_profil." a bien été assigné à ".$employe->nom,
        ]);
}

    public function list() : View
    {
        $employes = Employe::all();
        //return view('employe.list', compact('employes'))->render();
        return view("employe.list", [
            'employes' => $employes,
        ]);
    }

    public function import(Request $request) {

        // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    	$this->validate($request, [
    		'fichier' => 'bail|required|file|mimes:xlsx'
    	]);
    	// 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    	$fichier = $request->fichier->move(public_path("/storage"), $request->fichier->hashName());
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
            return ! Employe::where('matricule', $row['matricule'])->exists();
        });

        //Insertion des lignes filtrées dans la base de données
        if($rows->isNotEmpty()){
            $status = Employe::firstOrCreate($rows->toArray());

            if ($status) {
                // 6. On supprime le fichier uploadé
                $reader->close(); // On ferme le $reader
                //File::delete($fichier);
                //gc_collect_cycles();
                //unlink($fichier);
                // 7. Retour vers le formulaire avec un message $msg
                // return response()->json([
                //     'success' => true,
                //     'message' => "Employé(s) bien ajouté(s)",
                // ]);
                return redirect()->route('employe')->with('success', "Importation reussie");
            } else { abort(500); }
        }
        else {
            return redirect()->route('employe')->with('info', "Aucun nouvel employé à importer");
        }
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
    	$employes = Employe::select("nom", "matricule")->get();

    	// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
    	$writer = SimpleExcelWriter::streamDownload($file_name);

 		// 5. On insère toutes les lignes au fichier Excel $file_name
    	$writer->addRows($employes->toArray());

        // 6. Lancer le téléchargement du fichier
        $writer->toBrowser();

    }

    public function destroy ($employe) {

        $emp = Employe::find($employe);

        if ($emp) {
            $emp->delete();
            return response()->json(['success' => true, 'message' => "L'employé a bien été supprimé"]);
        } else {
            return response()->json(['success' => false, 'message' => "L'employé n'existe pas"]);
        }

        //return redirect()->route('employe')->with('success','Employé ' .$emp->nom. ' supprimé avec succès');
    }

    public function postes ($employe) {
        $employ = Employe::find($employe);
        $postes = $employ->postes()->get();

        return view("poste", [
            'postes' => $postes,
            'employes' => $employ,
        ]);
    }

    public function profils ($employe) {
        $employ = Employe::find($employe);
        $profils = $employ->profils()->get();
        $apps = \App\Models\Application::all();

        return view("profil", [
            'profils' => $profils,
            'employes' => $employ,
            'foncts' => null,
            'postes' => null,
            'applications' => null,
            'apps' => $apps
        ]);
    }
}
