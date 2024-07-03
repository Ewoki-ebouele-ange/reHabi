<?php

namespace App\Entities;

class Postes
{
    public $id;
    public $code_poste;
    public $libelle_poste;
    public $employes = [];

    public function __construct($id, $code_poste, $libelle_poste)
    {
        $this->id = $id;
        $this->code_poste = $code_poste;
        $this->libelle_poste = $libelle_poste;
    }

    public function employes($employes)
    {
        $this->employes[] = $employes;
    }
}
