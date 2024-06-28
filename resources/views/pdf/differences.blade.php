<!DOCTYPE html>
<html>
<head>
    <title>Différences entre Excel et la Base de Données</title>
</head>
<body>
    <h1>Différences entre Excel et la Base de Données</h1>
    
    <h2>Données présentes dans le fichier Excel mais pas dans la Base de Données</h2>
    <ul>
        @foreach ($dataInExcelNotInDB as $item)
            <li style="background-color: yellow">Le profil {{ json_encode($item["code_profil"]) }} a pour libelle {{ json_encode($item["libelle_profil"]) }}</li>
        @endforeach
    </ul>

    <h2>Données présentes dans la Base de Données mais pas dans le fichier Excel</h2>
    <ul>
        @foreach ($dataInDBNotInExcel as $item)
            <li> Le profil {{ json_encode($item["code_profil"]) }} a pour libelle {{ json_encode($item["libelle_profil"]) }}</li>
        @endforeach
    </ul>
</body>
</html>
