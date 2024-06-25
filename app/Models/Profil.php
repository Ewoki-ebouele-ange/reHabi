<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Profil extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_profil",
        "libelle_profil",
        "application_id",
        "date_dernier_acces",
        "date_creation",
        "date_suppression"
    ];

    public function application(){
        return $this->belongsTo(Application::class,'application_id');
    }

    public function fonctionnalites(){
        return $this->belongsToMany(Fonctionnalite::class);
    }

    public function postes(){
        return $this->belongsToMany(Poste::class);
    }

    public function employes(){
        return $this->belongsToMany(Employe::class);
    }

}
