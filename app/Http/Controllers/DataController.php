<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Employe;
use App\Models\Entite;
use App\Models\Poste;
use App\Models\Fonctionnalite;
use App\Models\Module;
use App\Models\Application;
use App\Models\Profil;
use Carbon\Carbon;
use App\Models\Rapport;


use PDF;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function index() : View {
        return view("addData");
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
        //dd($rows->toArray());

        // 4. Ajouter les colonnes `created_at` et `updated_at` à chaque ligne
        $currentTimestamp = Carbon::now(); // Récupérer le timestamp actuel
        $rows = $rows->map(function ($row) use ($currentTimestamp) {
            $row['created_at'] = $currentTimestamp;
            $row['updated_at'] = $currentTimestamp;
            return $row;
        });


        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {

        //dd(NULL);

                $prof = Profil::where("code_profil", $row["code_profil"])->get()->toArray();
                $emp = Employe::where("matricule", $row["matricule"])->get()->toArray();
                $post = Poste::where("code_poste", $row["code_poste"])->get()->toArray();

                $employe = Employe::firstOrCreate(
                    ['nom' => $row['nom']],
                    ['matricule' => $row['matricule']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                $entite = Entite::firstOrCreate(
                    ['code_entite' => $row['code_entite']],
                    ['libelle_entite' => $row['libelle_entite']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );

                $poste = Poste::firstOrCreate(
                    ['code_poste' => $row['code_poste']],
                    ['libelle_poste' => $row['libelle_poste']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                //dd($module);
                $entite->postes()->save($poste);

                $profil = Profil::firstOrCreate(
                    ['code_profil' => $row['code_profil']],
                    ['libelle_profil' => $row['libelle_profil']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                // Associer la fonctionnalite et le profil
                //$profil->postes()->syncWithoutDetaching($poste->id);
                

                if($prof == null){
                    $poste->profils()->syncWithoutDetaching($profil->id);
                    $employe->profils()->syncWithoutDetaching([
                        $profil->id => [
                            'date_assignation' => $row['date_assignation'] != "" ? $row['date_assignation'] : NULL, 
                            'date_suspension' => $row['date_suspension'] != "" ? $row['date_suspension'] : NULL, 
                            'date_derniere_modification' => $row['date_derniere_modification'] != "" ? $row['date_derniere_modification'] : NULL, 
                            'date_derniere_connexion' => $row['date_derniere_connexion'] != "" ? $row['date_derniere_connexion'] : NULL,
                            'created_at' => $row['created_at'],
                            'updated_at' => $row['updated_at']
                        ],
                    ]);
                } else {
                    $poste->profils()->syncWithoutDetaching($prof[0]["id"]);
                    $employe->profils()->syncWithoutDetaching([
                        $prof[0]["id"] => [
                            'date_assignation' => $row['date_assignation'] != "" ? $row['date_assignation'] : NULL, 
                            'date_suspension' => $row['date_suspension'] != "" ? $row['date_suspension'] : NULL, 
                            'date_derniere_modification' => $row['date_derniere_modification'] != "" ? $row['date_derniere_modification'] : NULL, 
                            'date_derniere_connexion' => $row['date_derniere_connexion'] != "" ? $row['date_derniere_connexion'] : NULL,
                            'created_at' => $row['created_at'],
                            'updated_at' => $row['updated_at']
                        ],
                    ]);
                }

                if($post == null){
                    //$profil->postes()->syncWithoutDetaching($poste->id);
                    $employe->postes()->syncWithoutDetaching([
                        $poste->id => [
                            'date_debut_fonction' => $row['date_debut_fonction'] != "" ? $row['date_debut_fonction'] : NULL, 
                            'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                            'created_at' => $row['created_at'],
                            'updated_at' => $row['updated_at']
                        ],
                    ]);
                } else {
                   // $profil->postes()->syncWithoutDetaching($post[0]["id"]);
                    $employe->postes()->syncWithoutDetaching([
                        $post[0]["id"] => [
                            'date_debut_fonction' => $row['date_debut_fonction'] != "" ? $row['date_debut_fonction'] : NULL, 
                            'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                            'created_at' => $row['created_at'],
                            'updated_at' => $row['updated_at']
                        ],
                    ]);
                }

                //dd($post_prof != null);
            }
            $reader->close();
            return redirect()->route('addData')->with('success', "Ajout effectué avec succès");
        } else {
            return redirect()->route('poste')->with('info', "Aucune nouvelle information à ajouter");
        }
        
    }

    public function extraction(Request $request){

        // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
        $this->validate($request, [
            'fichier1' => 'bail|nullable|file|mimes:xlsx',
            'fichier2' => 'bail|nullable|file|mimes:xlsx',
            'fichier3' => 'bail|nullable|file|mimes:xlsx',
            'fichier4' => 'bail|nullable|file|mimes:xlsx',
            'fichier5' => 'bail|nullable|file|mimes:xlsx',
            'fichier6' => 'bail|nullable|file|mimes:xlsx'
        ]);

        $rows = collect();
        
        // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
        if($request->hasFile('fichier1')){
            $fichier1 = $request->file('fichier1')->move(public_path('storage/'), $request->file('fichier1')->hashName());
            $reader1 = SimpleExcelReader::create($fichier1)->getRows();
            $rows = $rows->concat($reader1);
        }

        if($request->hasFile('fichier2')){
            $fichier2 = $request->file('fichier2')->move(public_path('storage/'), $request->file('fichier2')->hashName());
            $reader2 = SimpleExcelReader::create($fichier2)->getRows();
            $rows = $rows->concat($reader2);
        }

        if($request->hasFile('fichier3')){
            $fichier3 = $request->file('fichier3')->move(public_path('storage/'), $request->file('fichier3')->hashName());
            $reader3 = SimpleExcelReader::create($fichier3)->getRows();
            $rows = $rows->concat($reader3);
        }

        if($request->hasFile('fichier4')){
            $fichier4 = $request->file('fichier4')->move(public_path('storage/'), $request->file('fichier4')->hashName());
            $reader4 = SimpleExcelReader::create($fichier4)->getRows();
            $rows = $rows->concat($reader4);
        }

        if($request->hasFile('fichier5')){
            $fichier5 = $request->file('fichier5')->move(public_path('storage/'), $request->file('fichier5')->hashName());
            $reader5 = SimpleExcelReader::create($fichier5)->getRows();
            $rows = $rows->concat($reader5);
        }

        if($request->hasFile('fichier6')){
            $fichier6 = $request->file('fichier6')->move(public_path('storage/'), $request->file('fichier6')->hashName());
            $reader6 = SimpleExcelReader::create($fichier6)->getRows();
            $rows = $rows->concat($reader6);
        }

         $currentTimestamp = Carbon::now();
        $periodTimestamp = Carbon::now()->subDays(3);
        $rows = $rows->map(function ($row) use ($currentTimestamp) {
            $row['created_at'] = $currentTimestamp;
            $row['updated_at'] = $currentTimestamp;
            return $row;
        });

        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {

                $prof = Profil::where("code_profil", $row["code_profil"])->get()->toArray();
                $post = Poste::where("code_poste", $row["code_poste"])->get()->toArray();

                $employe = Employe::updateOrCreate(
                    ['nom' => $row['nom']],
                    ['matricule' => $row['matricule']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                $entite = Entite::updateOrCreate(
                    ['code_entite' => $row['code_entite']],
                    ['libelle_entite' => $row['libelle_entite']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );

                $poste = Poste::updateOrCreate(
                    ['code_poste' => $row['code_poste']],
                    ['libelle_poste' => $row['libelle_poste']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );

                $app = Application::updateOrCreate(
                    ['code_application' => $row['code_application']],
                    ['libelle_application' => $row['libelle_application']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                $module = Module::updateOrCreate(
                    ['code_module' => $row['code_module']],
                    ['libelle_module' => $row['libelle_module']],
                    ['code_application' => $row['code_application']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
                
    
                // Insérer ou trouver les enregistrements de fonctionnalite et profil
                $fonctionnalite = Fonctionnalite::updateOrCreate(
                    ['code_fonct' => $row['code_fonct']],
                    ['libelle_fonct' => $row['libelle_fonct']],
                    ['code_module' => $row['code_module']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
                //dd($module);
                
    
                $profil = Profil::updateOrCreate(
                    ['code_profil' => $row['code_profil']],
                    ['libelle_profil' => $row['libelle_profil']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );

                $app->modules()->save($module);
                $entite->postes()->save($poste);
                $module->fonctionnalites()->save($fonctionnalite);               
                $app->profils()->save($profil);

                //dd($module);

                // Associer la fonctionnalite et le profil
                //$profil->postes()->syncWithoutDetaching($poste->id);

                if($prof == null){

                    // Vérifier l'existence de l'association dans la table pivot
                    $fp = DB::table('fonctionnalite_profil')
                    ->where('fonctionnalite_id', $fonctionnalite->id)
                    ->where('profil_id', $profil->id)
                    ->first();

                    $pp = DB::table('poste_profil')
                    ->where('poste_id', $poste->id)
                    ->where('profil_id', $profil->id)
                    ->first();

                    $ep = DB::table('employe_profil')
                    ->where('employe_id', $employe->id)
                    ->where('profil_id', $profil->id)
                    ->first();

                    if ($fp) {
                        // Mise à jour de updated_at uniquement
                        DB::table('fonctionnalite_profil')
                            ->where('fonctionnalite_id', $fonctionnalite->id)
                            ->where('profil_id', $profil->id)
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $fonctionnalite->profils()->syncWithoutDetaching([
                            $profil->id => [
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }

                    if ($pp) {
                        // Mise à jour de updated_at uniquement
                        DB::table('poste_profil')
                            ->where('poste_id', $poste->id)
                            ->where('profil_id', $profil->id)
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $poste->profils()->syncWithoutDetaching([
                            $profil->id => [
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }

                    if ($ep) {
                        // Mise à jour de updated_at uniquement
                        DB::table('employe_profil')
                            ->where('employe_id', $employe->id)
                            ->where('profil_id', $profil->id)
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $employe->profils()->syncWithoutDetaching([
                            $profil->id => [
                                'date_assignation' => $row['date_assignation'] != "" ? $row['date_assignation'] : NULL, 
                                'date_suspension' => $row['date_suspension'] != "" ? $row['date_suspension'] : NULL, 
                                'date_derniere_modification' => $row['date_derniere_modification'] != "" ? $row['date_derniere_modification'] : NULL, 
                                'date_derniere_connexion' => $row['date_derniere_connexion'] != "" ? $row['date_derniere_connexion'] : NULL,
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }
                } else {

                    // Vérifier l'existence de l'association dans la table pivot
                    $fp = DB::table('fonctionnalite_profil')
                    ->where('fonctionnalite_id', $fonctionnalite->id)
                    ->where('profil_id', $prof[0]["id"])
                    ->first();

                    $pp = DB::table('poste_profil')
                    ->where('poste_id', $poste->id)
                    ->where('profil_id', $prof[0]["id"])
                    ->first();

                    $ep = DB::table('employe_profil')
                    ->where('employe_id', $employe->id)
                    ->where('profil_id', $prof[0]["id"])
                    ->first();

                    if ($fp) {
                        // Mise à jour de updated_at uniquement
                        DB::table('fonctionnalite_profil')
                            ->where('fonctionnalite_id', $fonctionnalite->id)
                            ->where('profil_id', $prof[0]["id"])
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $fonctionnalite->profils()->syncWithoutDetaching([
                            $prof[0]["id"] => [
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }

                    if ($pp) {
                        // Mise à jour de updated_at uniquement
                        DB::table('poste_profil')
                            ->where('poste_id', $poste->id)
                            ->where('profil_id', $prof[0]["id"])
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $poste->profils()->syncWithoutDetaching([
                            $prof[0]["id"] => [
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }

                    if ($ep) {
                        // Mise à jour de updated_at uniquement
                        DB::table('employe_profil')
                            ->where('employe_id', $employe->id)
                            ->where('profil_id', $prof[0]["id"])
                            ->update([
                                'updated_at' => $row['updated_at'],
                            ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $employe->profils()->syncWithoutDetaching([
                            $prof[0]["id"] => [
                                'date_assignation' => $row['date_assignation'] != "" ? $row['date_assignation'] : NULL, 
                                'date_suspension' => $row['date_suspension'] != "" ? $row['date_suspension'] : NULL, 
                                'date_derniere_modification' => $row['date_derniere_modification'] != "" ? $row['date_derniere_modification'] : NULL, 
                                'date_derniere_connexion' => $row['date_derniere_connexion'] != "" ? $row['date_derniere_connexion'] : NULL,
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }

                }

                if($post == null){

                    $ep = DB::table('employe_poste')
                    ->where('employe_id', $employe->id)
                    ->where('poste_id', $poste->id)
                    ->first();
                    if ($ep) {
                        // Mise à jour de updated_at uniquement
                        DB::table('employe_poste')
                            ->where('employe_id', $employe->id)
                            ->where('poste_id', $poste->id)
                            ->update([
                                'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                                'updated_at' => $row['updated_at'],
                        ]);
                        $employe->postes()->syncWithoutDetaching([
                            $poste->id => [
                                'date_debut_fonction' => $row['date_debut_fonction'] != "" ? $row['date_debut_fonction'] : NULL, 
                                'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $employe->postes()->syncWithoutDetaching([
                            $poste->id => [
                                'date_debut_fonction' => $row['date_debut_fonction'] != "" ? $row['date_debut_fonction'] : NULL, 
                                'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }
                } else {
                    $ep = DB::table('employe_poste')
                    ->where('employe_id', $employe->id)
                    ->where('poste_id', $post[0]["id"])
                    ->first();
                    if ($ep) {
                        // Mise à jour de updated_at uniquement
                        DB::table('employe_poste')
                            ->where('employe_id', $employe->id)
                            ->where('poste_id', $post[0]["id"])
                            ->update([
                                'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                                'updated_at' => $row['updated_at'],
                        ]);
                    } else {
                        // Création de l'association avec syncWithoutDetaching
                        $employe->postes()->syncWithoutDetaching([
                            $post[0]["id"] => [
                                'date_debut_fonction' => $row['date_debut_fonction'] != "" ? $row['date_debut_fonction'] : NULL, 
                                'date_fin_fonction' => $row['date_fin_fonction'] != "" ? $row['date_fin_fonction'] : NULL,
                                'created_at' => $row['created_at'],
                                'updated_at' => $row['updated_at'],
                            ],
                        ]);
                    }
                }
            }

            Auth::user()->rapports()->create();

            return redirect()->route('addData')->with('success', "Informations ajouter avec succès");

        } else {
            return redirect()->route('addData')->with('info', "Aucune nouvelle information à ajouter");
        }
    }
}

