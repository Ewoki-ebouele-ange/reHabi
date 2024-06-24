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
        "poste_id",
        "code_poste"
    ];

    public function poste(){
        return $this->belongsTo(Poste::class,'poste_id');
    }
}
