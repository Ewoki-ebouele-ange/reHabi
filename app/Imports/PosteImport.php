<?php

namespace App\Imports;

use App\Models\Poste;
use Maatwebsite\Excel\Concerns\ToModel;

class PosteImport implements ToModel
{
    public function model(array $row)
    {
        return new Poste([
            'id',
            'code_poste' => $row['code_poste'],
            'libelle_poste' => $row['libelle_poste'],
        ]);
    }
}