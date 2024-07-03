<?php

namespace App\Entities;

class Employes
{
    public $id;
    public $nom;
    public $matricule;
    public $postes = [];

    public function __construct($id, $nom, $matricule)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->matricule = $matricule;
    }

    public function postes($postes)
    {
        $this->postes[] = $postes;
    }
}