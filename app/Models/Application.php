<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_application",
        "libelle_application"
    ];

    public function modules(){
        return $this->hasMany(Module::class);
    }

    public function profils(){
        return $this->hasMany(Profil::class);
    }

    public function fonctionnalites(){
        return $this->hasManyThrough(Fonctionnalite::class, Module::class);
    }

}
