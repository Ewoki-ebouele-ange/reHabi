<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        "code_module",
        "libelle_module",
        'applicattion_id',
        'code_application',
    ];

    public function application(){
        return $this->belongsTo(Application::class,'application_id');
    }

    public function fonctionnalites()
    {
        return $this->hasMany(Fonctionnalite::class);
    }
}
