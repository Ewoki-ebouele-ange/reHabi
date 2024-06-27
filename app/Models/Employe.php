<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $fillable = [
        "nom",
        "matricule",
    ];

    public function postes(){
        return $this->belongsToMany(Poste::class)->withPivot('date_debut_fonction', 'date_fin_fonction');
    }

    public function profils(){
        return $this->belongsToMany(Profil::class)->withPivot('date_assignation', 'date_suspension', 'date_derniere_modification', 'date_derniere_connexion');
    }
}
