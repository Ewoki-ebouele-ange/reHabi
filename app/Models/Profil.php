<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_profil",
        "libelle_profil"
    ];

    public function fonctionnalites(){
        return $this->belongsToMany(Fonctionnalite::class);
    }
}
