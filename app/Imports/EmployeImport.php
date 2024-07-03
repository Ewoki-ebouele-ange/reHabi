<?php


namespace App\Imports;

use App\Models\Employe;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeImport implements ToModel
{
    public function model(array $row)
    {
        return new Client([
            'id',
            'nom' => $row['nom'],
            'matricule' => $row['matricule'],
        ]);
    }
}