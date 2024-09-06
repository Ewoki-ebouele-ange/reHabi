<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateProfilRequest;
use Illuminate\View\View;
use App\Models\Profil;
use App\Models\Fonctionnalite;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function index() : View {
        //dd(Auth::user());

        $profils = \App\Models\Profil::all();
        $apps = \App\Models\Application::all();
        return view("profil", [
            'profils' => $profils,
            'employes' => null,
            'foncts' => null,
            'postes' => null,
            'applications' => null,
            'apps' => $apps,
        ]);
    }

    public function addi() : View {
        return view("profil.add-profil");
    }

    public function store(CreateProfilRequest $request){
        $profil = Profil::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le profil a bien été ajouté",
        ]);
    }

    public function edit($prof){

        $profil = Profil::find($prof);
        return response()->json($profil);
    
    }

    public function update(Profil $profil, CreateProfilRequest $request){
        $profil->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => "Le profil a bien été modifié",
        ]);
    }

    public function assignRole(Profil $profil, Request $request){
        // dd('bonjo');
        $role_input = $request->role_input;
        $ass_role = $request->ass_role;

        $role = Fonctionnalite::findOrFail($role_input);

        $mod = $role->module()->first();

        $app = $mod->application()->first()->code_application;

        // dd($prf);

        if($app == $profil->application()->first()->code_application){
            $profil->fonctionnalites()->syncWithoutDetaching([
                $role_input => [
                    'date_assignation' => $ass_role, 
                    'date_suspension' => NULL, 
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
            // $profil->fonctionnalites()->syncWithoutDetaching($role_input);

            return response()->json([
                'success' => true,
                'message' => "Le role ".$role->libelle_fonct." a bien été assigné au profil ".$profil->libelle_profil,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Erreur sur l'application choisie",
            ]);
        }

        
    }

    public function susRole(Profil $profil, Fonctionnalite $fonct, Request $request){
        

        $dt_sus = $request->dt_sus;

        // $role = Fonctionnalite::findOrFail($fonct->id);

         // Mise à jour de updated_at uniquement
         DB::table('fonctionnalite_profil')
         ->where('fonctionnalite_id', $fonct->id)
         ->where('profil_id', $profil->id)
         ->update([
             'date_suspension' => $dt_sus,
         ]);

         return response()->json([
            'success' => true,
            'message' => "Le rôle ".$fonct->libelle_fonct." a bien été suspendu du profil ".$profil->libelle_profil,
        ]);
    }

    public function list() : View
    {
        $profils = Profil::all();
        //return view('employe.list', compact('employes'))->render();
        return view("profil.list", [
            'profils' => $profils,
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
            return ! Profil::where('code_profil', $row['code_profil'])->exists() ;
        });

        //Insertion des lignes filtrées dans la base de données
        if($rows->isNotEmpty()){
            $status = Profil::insert($rows->toArray());

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

    public function destroy ($profil) {
        $prof = Profil::find($profil);
        if ($prof) {
            $prof->delete();
            return response()->json(['success' => true, 'message' => "Le profil a bien été supprimé"]);
        } else {
            return response()->json(['success' => false, 'message' => "Le profil n'existe pas"]);
        }
    }

    public function fonctionnalites ($profil) {
        $prof = Profil::find($profil);
        $foncts = $prof->fonctionnalites()->get();
        $app = $prof->application()->first();

        return view('fonct.role', [
            'foncts' => $foncts,
            'app' => $app,
            'prof' => $profil
        ]);
    }

    public function postes ($profil) {
        $prof = Profil::find($profil);
        $postes = $prof->postes()->get();

        return view("poste", [
            'postes' => $postes,
            'employes' => null,

        ]);
    }

    public function employes ($profil) {
        $prof = Profil::find($profil);
        $employes = $prof->employes()->get();
        $entite = \App\Models\Entite::all();
        $apps = \App\Models\Application::all();
        
        return view("employe", [
            'employes' => $employes,
            'entite' => $entite,
            'apps' => $apps,
        ]);
    }

    public function application ($profil) {
        $prof = Profil::find($profil);
        $applications = $prof->application()->get();

        return view("application", [
            'applications' => $applications,
        ]);
    }
}
