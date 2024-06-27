<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entite extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "code_entite",
        "libelle_entite"
    ];

    public function postes(){
        return $this->hasMany(Poste::class);
    }

}
