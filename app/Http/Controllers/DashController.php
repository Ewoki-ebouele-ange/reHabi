<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashController extends Controller
{
    public function index() : View {

        $employes = \App\Models\Employe::all();
        $profils = \App\Models\Profil::all();
        $apps = \App\Models\Application::all();
        $dateDash = Carbon::today();
        
        return view("dashboard", [
            'employes' => $employes,
            'profils' => $profils,
            'apps' => $apps,
            'date' => $dateDash->subWeek(),
        ]);
    }
}
