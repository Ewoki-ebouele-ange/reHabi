<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Employe;
use App\Models\Entite;
use App\Models\Poste;
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
        
        //$arrayligne = $rows->toArray();

        // Tableau pour stocker les valeurs de la colonne spécifique
        $matEmp = [];
        $nomEmp = [];

        $codeEnt = [];
        $libEnt = [];

        $codePoste = [];
        $libPoste = [];

        // Nom de la colonne que vous souhaitez récupérer
        $columnName1 = 'nom';
        $columnName2 = 'matricule';
        $columnName3 = 'code_entite';
        $columnName4 = 'libelle_entite';
        $columnName5 = 'code_poste';
        $columnName6 = 'libelle_poste';

        // Parcours des lignes et récupération des valeurs de la colonne spécifique
        foreach ($rows as $row) {
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName1])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $nomEmp[] = $row[$columnName1];
            }
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName2])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $matEmp[] = $row[$columnName2];
            }
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName3])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $codeEnt[] = $row[$columnName3];
            }
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName4])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $libEnt[] = $row[$columnName4];
            }
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName5])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $codePoste[] = $row[$columnName5];
            }
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName6])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $libPoste[] = $row[$columnName6];
            }
        }

$employeArray = array([$nomEmp, $matEmp]);       
$entiteArray = array([$codeEnt, $libEnt]);       
$posteArray = array([$codePoste, $libPoste]);       
        

    for($i=0; $i<count($employeArray[0][0]); $i++){
        $employeData[] = [
            'nom' => $employeArray[0][0][$i],
            'matricule' => $employeArray[0][1][$i],
        // Ajoutez d'autres colonnes selon votre structure de base de données
        ];
        $entiteData[] = [
            'code_entite' => $entiteArray[0][0][$i],
            'libelle_entite' => $entiteArray[0][1][$i],
            // Ajoutez d'autres colonnes selon votre structure de base de données
        ];
        $posteData[] = [
        'code_poste' => $posteArray[0][0][$i],
        'libelle_poste' => $posteArray[0][1][$i],
        // Ajoutez d'autres colonnes selon votre structure de base de données
        ];
    }

 $status = Employe::insert($employeData);
 $status = Entite::insert($entiteData);
 $status = Poste::insert($posteData);

if ($status) {
    $reader->close();
}else { abort(500); }

        return redirect()->route('dashboard')->with('success', "Importation reussie");


        // 5. On insère toutes les lignes dans la base de données
        //$status = Fonctionnalite::insert($rows->toArray());
        // Si toutes les lignes sont insérées
    	// if ($status) {
        //     // 6. On supprime le fichier uploadé
        //     $reader->close(); // On ferme le $reader
        //     File::delete($fichier);
        //     //unlink($fichier);
        //     // 7. Retour vers le formulaire avec un message $msg
        //     return redirect()->route('fonct')->with('success', "Importation reussie");
        // } else { abort(500); }

        // On prend 10 lignes
        /*$reader->take(10);

        // On filtre les lignes en s'assurant que l'adresse email est correcte
        //$rows = $reader->getRows()->filter(function ($ligne) {
            return filter_var($ligne['email'], FILTER_VALIDATE_EMAIL) === true;
        });*/
    }
}
