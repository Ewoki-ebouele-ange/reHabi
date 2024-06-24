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

class ImporterFichier extends Controller
{
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
                $post_prof = Profil::where("code_profil", $row["code_profil"])->get()->toArray();

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
    
                // Insérer ou trouver les enregistrements de fonctionnalite et profil
                $profil = Profil::firstOrCreate(
                    ['code_profil' => $row['code_profil']],
                    ['libelle_profil' => $row['libelle_profil']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
                //dd($module);
                $entite->postes()->save($poste);

                
                $poste->employes()->save($employe);

                $profil = Profil::firstOrCreate(
                    ['code_profil' => $row['code_profil']],
                    ['libelle_profil' => $row['libelle_profil']],
                    ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
                );
    
                // Associer la fonctionnalite et le profil
                $profil->postes()->syncWithoutDetaching($poste->id);

                if($post_prof == null){
                    $poste->profils()->syncWithoutDetaching($profil->id);
                } else {
                    $poste->profils()->syncWithoutDetaching($post_prof[0]["id"]);
                }

                //dd($post_prof != null);
            }
            $reader->close();
            return redirect()->route('poste')->with('success', "Importation réussie");
        } else {
            return redirect()->route('poste')->with('info', "Aucune nouvelle information à ajouter");
        }
        
    }


    public function importFonctProfil(Request $request){
    // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    $this->validate($request, [
        'fichier' => 'bail|required|file|mimes:xlsx'
    ]);

    // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    $fichier = $request->fichier->move(public_path('storage/'), $request->fichier->hashName());

    // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    $reader = SimpleExcelReader::create($fichier);
    $rows = $reader->getRows();
    $currentTimestamp = Carbon::now(); // Récupérer le timestamp actuel

    $rows = $rows->map(function ($row) use ($currentTimestamp) {
        $row['created_at'] = $currentTimestamp;
        $row['updated_at'] = $currentTimestamp;
        return $row;
    });

    if ($rows->isNotEmpty()) {
        foreach ($rows as $row) {

            $fonct_prof = Profil::where("code_profil", $row["code_profil"])->get()->toArray();

            $app = Application::firstOrCreate(
                ['code_application' => $row['code_application']],
                ['libelle_application' => $row['libelle_application']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );

            $module = Module::firstOrCreate(
                ['code_module' => $row['code_module']],
                ['libelle_module' => $row['libelle_module']],
                ['code_application' => $row['code_application']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );
            $app->modules()->save($module);

            // Insérer ou trouver les enregistrements de fonctionnalite et profil
            $fonctionnalite = Fonctionnalite::firstOrCreate(
                ['code_fonct' => $row['code_fonct']],
                ['libelle_fonct' => $row['libelle_fonct']],
                ['code_module' => $row['code_module']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );
            //dd($module);
            
            $module->fonctionnalites()->save($fonctionnalite);

            $profil = Profil::firstOrCreate(
                ['code_profil' => $row['code_profil']],
                ['libelle_profil' => $row['libelle_profil']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );

            // Associer la fonctionnalite et le profil
            $profil->fonctionnalites()->syncWithoutDetaching($fonctionnalite->id);
            //$fonctionnalite->profils()->syncWithoutDetaching($profil->id);

            if($fonct_prof == null){
                $fonctionnalite->profils()->syncWithoutDetaching($profil->id);
            } else {
                $fonctionnalite->profils()->syncWithoutDetaching($fonct_prof[0]["id"]);
            }
        }

        $reader->close();
        return redirect()->route('profil')->with('success', "Importation réussie");
    } else {
        return redirect()->route('profil')->with('info', "Aucune nouvelle information à ajouter");
    }
}


public function importProfilPoste(Request $request)
{
    // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    $this->validate($request, [
        'fichier' => 'bail|required|file|mimes:xlsx'
    ]);

    // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    $fichier = $request->fichier->move(public_path('storage/'), $request->fichier->hashName());

    // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    $reader = SimpleExcelReader::create($fichier);
    $rows = $reader->getRows();
    $currentTimestamp = Carbon::now(); // Récupérer le timestamp actuel

    $rows = $rows->map(function ($row) use ($currentTimestamp) {
        $row['created_at'] = $currentTimestamp;
        $row['updated_at'] = $currentTimestamp;
        return $row;
    });

    // Filtrage des lignes existantes dans la base de données
    // $filteredRows = $rows->filter(function($row) {
    //     return !Fonctionnalite::where('code_fonct', $row['code_fonct'])->exists() && !Profil::where('code_profil', $row['code_profil'])->exists();
    // });

    if ($rows->isNotEmpty()) {
        foreach ($rows as $row) {
            // Insérer ou trouver les enregistrements de fonctionnalite et profil
            $postes = Poste::firstOrCreate(
                ['code_poste' => $row['code_poste']],
                ['libelle_poste' => $row['libelle_poste']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );

            $profil = Profil::firstOrCreate(
                ['code_profil' => $row['code_profil']],
                ['libelle_profil' => $row['libelle_profil']],
                ['created_at' => $row['created_at'], 'updated_at' => $row['updated_at']]
            );

            // Associer la fonctionnalite et le profil
            $profil->postes()->syncWithoutDetaching($postes->id);
            $postes->profils()->syncWithoutDetaching($profil->id);

        }

        $reader->close();
        return redirect()->route('poste')->with('success', "Importation réussie");
    } else {
        return redirect()->route('profil')->with('info', "Aucune nouvelle information à ajouter");
    }
}



//Fonction d'import et de comparaison
public function importAndCompare(Request $request)
{
    //--------------------------------------------------------------------------------------------------------------
    // 1. Validation du fichier uploadé. Extension ".xlsx" autorisée
    // $this->validate($request, [
    //     'fichier' => 'bail|required|file|mimes:xlsx'
    // ]);

    // // 2. On déplace le fichier uploadé vers le dossier "public" pour le lire
    // $fichier = $request->file('fichier')->move(public_path('storage/'), $request->file('fichier')->hashName());

    // // 3. $reader : L'instance Spatie\SimpleExcel\SimpleExcelReader
    // $reader = SimpleExcelReader::create($fichier);
    // $rows = $reader->getRows();
    // $currentTimestamp = Carbon::now();

    // // 4. Initialisation des lignes qui n'existent pas
    // $nonExistingRows = [];

    // foreach ($rows as $row) {
    //     $fonctionnaliteExists = Fonctionnalite::where('code_fonct', $row['code_fonct'])->exists();
    //     $profilExists = Profil::where('code_profil', $row['code_profil'])->exists();

    //     // 5. Stockage des lignes qui n'existent pas
    //     if (!$fonctionnaliteExists || !$profilExists) {
    //         $nonExistingRows[] = $row;
    //     }
    // }

    // // 6. Écriture des lignes qui n'existent pas dans un fichier texte
    // if (!empty($nonExistingRows)) {
    //     $diffFilePath = public_path('storage/fic_sorti.txt');
    //     $fileContent = "";

    //     foreach ($nonExistingRows as $row) {
    //         $fileContent .= implode("\t", $row) . "\n"; // Utilisation de tabulation comme séparateur
    //     }

    //     file_put_contents($diffFilePath, $fileContent);
    //     return response()->download($diffFilePath);
        //-----------------------------------------------------------------------------------------------------


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

    // 4. Initialisation des lignes qui n'existent pas
    $nonExistingRows = [];
    $existingRows = [];

    // 5. Création des listes de codes présents dans le fichier
    $codesFonctInFile = [];
    $codesProfilInFile = [];

    foreach ($rows as $row) {
        $codesFonctInFile[] = $row['code_fonct'];
        $codesProfilInFile[] = $row['code_profil'];

        $fonctionnaliteExists = Fonctionnalite::where('code_fonct', $row['code_fonct'])->exists();
        $profilExists = Profil::where('code_profil', $row['code_profil'])->exists();

        if (!$fonctionnaliteExists || !$profilExists) {
            $nonExistingRows[] = $row;
        }
    }

    // 6. Trouver les enregistrements dans la BD qui ne sont pas dans le fichier
    $fonctionsNotInFile = Fonctionnalite::whereNotIn('code_fonct', $codesFonctInFile)->get()->toArray();
    $profilsNotInFile = Profil::whereNotIn('code_profil', $codesProfilInFile)->get()->toArray();

    // Combiner les résultats pour créer le fichier de lignes absentes du fichier d'entrée
    foreach ($fonctionsNotInFile as $fonctionnalite) {
        $existingRows[] = [
            'code_fonct' => $fonctionnalite['code_fonct'],
            'code_profil' => '', // Vide car cette info n'existe pas
            'created_at' => $fonctionnalite['created_at'],
            'updated_at' => $fonctionnalite['updated_at']
        ];
    }

    foreach ($profilsNotInFile as $profil) {
        $existingRows[] = [
            'code_fonct' => '', // Vide car cette info n'existe pas
            'code_profil' => $profil['code_profil'],
            'created_at' => $profil['created_at'],
            'updated_at' => $profil['updated_at']
        ];
    }

    // 7. Écriture des lignes dans des fichiers texte
    if (!empty($nonExistingRows)) {
        $nonExistingFilePath = public_path('storage/non_existing_rows.txt');
        $fileContent = "";

        foreach ($nonExistingRows as $row) {
            $fileContent .= implode("\t", $row) . "\n"; // Utilisation de tabulation comme séparateur
        }

        file_put_contents($nonExistingFilePath, $fileContent);
    }

    if (!empty($existingRows)) {
        $existingFilePath = public_path('storage/existing_rows.txt');
        $fileContent = "";

        foreach ($existingRows as $row) {
            $fileContent .= implode("\t", $row) . "\n"; // Utilisation de tabulation comme séparateur
        }

        file_put_contents($existingFilePath, $fileContent);
    }

    if (!empty($nonExistingRows) || !empty($existingRows)) {
        // Retourne les fichiers générés en réponse
        $zip = new \ZipArchive();
        $zipFilePath = public_path('storage/comparison_result.zip');

        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            if (!empty($nonExistingRows)) {
                $zip->addFile($nonExistingFilePath, basename($nonExistingFilePath));
            }
            if (!empty($existingRows)) {
                $zip->addFile($existingFilePath, basename($existingFilePath));
            }
            $zip->close();
        }

        return response()->download($zipFilePath);
    } else {
        return redirect()->route('profil')->with('info', "Toutes les informations existent déjà dans la base de données");
    }
}


}
