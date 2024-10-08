<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_poste",
        "libelle_poste",
        "entite_id",
    ];

    
    public function profils(){
        return $this->belongsToMany(Profil::class)->withPivot('created_at', 'updated_at');
    }

    public function entite(){
        return $this->belongsTo(Entite::class,'entite_id');
    }

    public function employes()
    {
        return $this->belongsToMany(Employe::class)->withPivot('date_debut_fonction', 'date_fin_fonction', 'created_at', 'updated_at');
    }
}
