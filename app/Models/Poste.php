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
        "code_entite"
    ];

    
    public function profils(){
        return $this->belongsToMany(Profil::class);
    }

    public function entite(){
        return $this->belongsTo(Entite::class,'entite_id');
    }

    public function employes()
    {
        return $this->hasMany(Employe::class);
    }
}
