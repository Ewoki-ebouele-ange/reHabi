<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\Employe;
use App\Models\Entite;
use App\Models\Poste;
use App\Models\Fonctionnalite;
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

        //Filtrage des lignes dans la base de données
        $filteredRows = $rows->filter(function($row){
            return !Employe::where('matricule', $row['matricule'])->exists() & !Entite::where('code_entite', $row['code_entite'])->exists() & !Poste::where('code_poste', $row['code_poste'])->exists();
        });
        
        if($filteredRows->isNotEmpty()){
            // Tableau pour stocker les valeurs de la colonne spécifique
        $matEmp = [];
        $nomEmp = [];

        $codeEnt = [];
        $libEnt = [];

        $codePoste = [];
        $libPoste = [];
        $createdAt = [];
        $updatedAt = [];

        // Nom de la colonne que vous souhaitez récupérer
        $columnName1 = 'nom';
        $columnName2 = 'matricule';
        $columnName3 = 'code_entite';
        $columnName4 = 'libelle_entite';
        $columnName5 = 'code_poste';
        $columnName6 = 'libelle_poste';
        $columnName7 = 'created_at';
        $columnName8 = 'updated_at';

        

        // Parcours des lignes et récupération des valeurs de la colonne spécifique
        foreach ($filteredRows as $row) {
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
            if (isset($row[$columnName7])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $createdAt[] = $row[$columnName7];
            }
            if (isset($row[$columnName8])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $updatedAt[] = $row[$columnName8];
            }
        }

        $employeArray = array([$nomEmp, $matEmp, $createdAt, $updatedAt]);       
        $entiteArray = array([$codeEnt, $libEnt, $createdAt, $updatedAt]);       
        $posteArray = array([$codePoste, $libPoste, $createdAt, $updatedAt]);  


        for($i=0; $i<count($employeArray[0][0]); $i++){
            $employeData[] = [
                'nom' => $employeArray[0][0][$i],
                'matricule' => $employeArray[0][1][$i],
                'created_at' => $employeArray[0][2][$i],
                'updated_at' => $employeArray[0][3][$i],
            // Ajoutez d'autres colonnes selon votre structure de base de données
            ];
            $entiteData[] = [
                'code_entite' => $entiteArray[0][0][$i],
                'libelle_entite' => $entiteArray[0][1][$i],            
                'created_at' => $entiteArray[0][2][$i],
                'updated_at' => $entiteArray[0][3][$i],

                // Ajoutez d'autres colonnes selon votre structure de base de données
            ];
            $posteData[] = [
            'code_poste' => $posteArray[0][0][$i],
            'libelle_poste' => $posteArray[0][1][$i],            
            'created_at' => $posteArray[0][2][$i],
            'updated_at' => $posteArray[0][3][$i],

            // Ajoutez d'autres colonnes selon votre structure de base de données
            ];
        }

    

        $status1 = Employe::insert($employeData);
        $status2 = Entite::insert($entiteData);
        $status3 = Poste::insert($posteData);

        if ($status1 & $status2 & $status3) {
            $reader->close();
        }else { abort(500); }

        $routeName = request()->route()->getName();

        return redirect()->route('import')->with('success', "Importation reussie");

        }else{
            return redirect()->route('importEEP')->with('info', "Aucune nouvelle information à ajouter");
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

        //Filtrage des lignes dans la base de données
        $filterRows = $rows->filter(function($row){
            return !Fonctionnalite::where('code_fonct', $row['code_fonct'])->exists() & !Profil::where('code_profil', $row['code_profil'])->exists();
        });

        if($filterRows->isNotEmpty()){
            // Tableau pour stocker les valeurs de la colonne spécifique
        $codeFonct = [];
        $codeProf = [];

        // Nom de la colonne que vous souhaitez récupérer
        $columnName1 = 'code_fonct';
        $columnName2 = 'code_profil';

        

        // Parcours des lignes et récupération des valeurs de la colonne spécifique
        foreach ($filterRows as $row) {
            // Vérifie si la colonne spécifique existe dans la ligne
            if (isset($row[$columnName1])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $codeFonct[] = $row[$columnName1];
            }
            if (isset($row[$columnName2])) {
                // Ajoute la valeur de la colonne spécifique au tableau
                $codeProf[] = $row[$columnName2];
            }
        }

        $fonct_prof = array([$codeFonct, $codeProf]);    
        
        for($i=0; $i<count($fonct_prof[0][0]); $i++){
            $fonctData[] = [
                'code_fonct' => $fonct_prof[0][0][$i],
            //     'code_profil' => $fonct_prof[0][1][$i],
            // // Ajoutez d'autres colonnes selon votre structure de base de données
            ];
            $profData[]= [
                'code_profil' => $fonct_prof[0][1][$i],
            ];
        }

        // $fonct = Fonctionnalite::where('code_fonct', $fonctData[3]['code_fonct'])->first()->profils();
        // dd($fonct);

        //dd($profData);

        $status1 = Fonctionnalite::insert($fonctData);
        $status2 = Profil::insert($profData);

        $fon = Fonctionnalite::orderBy('id','asc')->get();
        $pro = Profil::orderBy('id','asc')->get();

        //dd($fon[4]);

        for($i=1; $i<count($fon)+1; $i++){

            $fonction = $fon[$i-1];
            $profil = $pro[$i-1];

            $fonction::whereId($i)->first()->profils()->attach($profil->id);
        }

        //dd($fon);

        // for($i=0; $i<count($fonctData); $i++){
        //     $fonct = Fonctionnalite::where('code_fonct', $fonctData[$i]['code_fonct']);

        //     //dd($fonct);
        //     for($j=0; $j<count($profData); $j++){
        //         $fonct->first()->profils()->attach($profData[$j]['code_profil']);
        //     }            
        // }
        

        if ($status1 & $status2) {
            
            $reader->close();
        }else { abort(500); }

        return redirect()->route('importFP')->with('success', "Importation reussie");

        }
        
        else{
            return redirect()->route('importFP')->with('info', "Aucune nouvelle information à ajouter");
        }



        
        
    }
}
