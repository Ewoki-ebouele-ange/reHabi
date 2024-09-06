<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use PDF;

use App\Entities\Employes;
use App\Entities\Postes;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class CompareController extends Controller
{
    public function compare(Request $request){

        // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
        $this->validate($request, [
            'fichier' => 'bail|required|file|mimes:xlsx'
        ]);
    
        // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
        $fichier = $request->file('fichier')->move(public_path('storage/'), $request->file('fichier')->hashName());
    
        // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
        $reader = SimpleExcelReader::create($fichier);
        $rows = $reader->getRows();
        $currentTimestamp = Carbon::now();
        $periodTimestamp = Carbon::now()->subDays(3);
        $rows = $rows->map(function ($row) use ($currentTimestamp) {
            $row['created_at'] = $currentTimestamp;
            $row['updated_at'] = $currentTimestamp;
            return $row;
        });

        if ($rows->isNotEmpty()) {
            foreach ($rows as $row) {

        //dd(NULL);

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
                                'date_assignation' => $row['date_assignation'] != "" ? $row['date_assignation'] : NULL, 
                                'date_suspension' => $row['date_suspension'] != "" ? $row['date_suspension'] : NULL, 
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

                //dd($post_prof != null);
            }
            $employes= Employe::all();
            
             //dd($nvoFonct);

            $reader->close();

            $pdf = PDF::loadView('pdf.differences', [
                'employes' => $employes,
                'periodTimestamp' => $periodTimestamp,
                // 'dataInExcelNotInDB' => $dataInExcelNotInDB,
                // 'dataInDBNotInExcel' => $dataInDBNotInExcel,
            ]);
            
            //return $pdf->download('differences.pdf');

            return view("pdf.differences", [
                'employes' => $employes,
                'periodTimestamp' => $periodTimestamp,
                //'dataInExcelNotInDB' => $dataInExcelNotInDB,
                //'dataInDBNotInExcel' => $dataInDBNotInExcel,
            ]);
        } else {
            return redirect()->route('poste')->with('info', "Aucune nouvelle information à ajouter");
        }



    
        // Récupérer les données de la base de données
        // $employes= Employe::all();
        // $profils= Profil::all();
        // $postes=Poste::all();
        // $applications= Application::all();
        // $modules= Module::all();
        // $foncts= Fonctionnalite::all();
        // $entites= Entite::all();
    
        
        // $databaseProfil = $profils->toArray();
        // $databaseEmploye = $employes->toArray();
        // $databasePoste = $postes->toArray();
        // $databaseApp = $applications->toArray();
        // $databaseModule = $modules->toArray();
        // $databaseFonct = $foncts->toArray();
        // $databaseEntite = $entites->toArray();
    
        // $databaseData = array_merge($databaseProfil,$databaseApp,$databaseEmploye, $databaseFonct,$databaseModule,$databasePoste,$databaseEntite);
    
        // // Comparer les données
        // $excelData = $rows->toArray();
        // $dataInExcelNotInDB = array_udiff($excelData, $databaseData, function ($a, $b) {
        //     return strcmp(serialize($a), serialize($b));
        // });
    
        // $dataInDBNotInExcel = array_udiff($databaseData, $excelData, function ($a, $b) {
        //     return strcmp(serialize($a), serialize($b));
        // });
    
        //dd($dataInExcelNotInDB);
    
        // Générer le PDF
        $pdf = PDF::loadView('pdf.differences', [
            'employes' => $employes,
            // 'dataInExcelNotInDB' => $dataInExcelNotInDB,
            // 'dataInDBNotInExcel' => $dataInDBNotInExcel,
        ]);
    
        // return view("pdf.differences", [
        //     'employes' => $employes,
        //     // 'dataInExcelNotInDB' => $dataInExcelNotInDB,
        //     // 'dataInDBNotInExcel' => $dataInDBNotInExcel,
        // ]);
        //return $pdf->download('differences.pdf');
    }


    //Afficher les données stockées localement dans les entités
public function viewEntity(Request $request){
    $this->validate($request, [
        'fichier' => 'bail|required|file|mimes:xlsx'
    ]);

    // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    $fichier = $request->file('fichier')->move(public_path('storage/'), $request->file('fichier')->hashName());

    // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    $reader = SimpleExcelReader::create($fichier);
    $rows = $reader->getRows();
    $currentTimestamp = Carbon::now();

        $employes = [];
        $postes = [];

        // Compteurs pour les IDs
        $employeIdCounter = 1;
        $posteIdCounter = 1;
        $employeIdMap = [];
        $posteIdMap = [];

        // Créer les étudiants et les cours et établir les relations
        $rows->each(function (array $row) use (&$employes, &$postes, &$employeIdCounter, &$posteIdCounter, &$employeIdMap, &$posteIdMap) {

            $employe_nom = $row['nom'];
            $employe_mat = $row['matricule'];
            $code_poste = $row['code_poste'];
            $libelle_poste = $row['libelle_poste'];

            // Si l'étudiant n'existe pas encore, le créer avec un ID incrémenté
            if (!isset($employeIdMap[$employe_mat])) {
                $employe = new Employes($employeIdCounter, $employe_nom, $employe_mat);
                $employes[$employeIdCounter] = $employe;
                $employeIdMap[$employe_nom] = $employeIdCounter;
                $employeIdCounter++;
            } else {
                $employe = $employes[$employeIdCounter[$employe_nom]];
            }

            // Si le cours n'existe pas encore, le créer avec un ID incrémenté
            if (!isset($posteIdMap[$code_poste])) {
                $posteObj = new Postes($posteIdCounter, $code_poste, $libelle_poste);
                $postes[$posteIdCounter] = $posteObj;
                $posteIdMap[$code_poste] = $posteIdCounter;
                $posteIdCounter++;
            } else {
                $posteObj = $postes[$posteIdMap[$code_poste]];
            }

            // Établir la relation bidirectionnelle
            $employe->postes($posteObj);
            $posteObj->employes($employe);
        });

        dd($employes);
        
        return 'Import terminé, données manipulées en mémoire';
    }
}
