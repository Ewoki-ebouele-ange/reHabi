<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fonctionnalite extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_fonct',
        'libelle_fonct',
        'module_id',
    ];

    public function module(){
        return $this->belongsTo(Module::class,'module_id');
    }

    public function profils(){
        return $this->belongsToMany(Profil::class)->withPivot('date_assignation','date_suspension','created_at', 'updated_at');
    }
}
