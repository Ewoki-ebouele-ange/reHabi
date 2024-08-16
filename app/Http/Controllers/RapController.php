<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use PDF;
use Carbon\Carbon;
use App\Models\Employe;
use App\Models\Rapport;


class RapController extends Controller
{
    public function index() : View {
        $rapports = Rapport::all();

        return view("rapports", [
            'rapports' => $rapports,
        ]);
    }

    public function show(Rapport $rapport) {
        // $periodTimestamp = Carbon::now()->subDays(3);

        $rap = Rapport::find($rapport);
        $date = $rap->first()->created_at;
        $employes= Employe::all();
            
            $pdf = PDF::loadView('pdf.differences', [
                'employes' => $employes,
                'date' => $date->subHours(3),
            ]);
            
            return view("pdf.differences", [
                'employes' => $employes,
                'date' => $date->subHours(3),
            ]);
    }

    public function download(Rapport $rapport){

        $rap = Rapport::find($rapport);

        $date = $rap->first()->created_at;

        $employes= Employe::all();
            
            $pdf = PDF::loadView('pdf.differences', [
                'employes' => $employes,
                'date' => $date->subHours(3),
            ]);
            
            return $pdf->download("Rapport du ".$rap->first()->created_at.".pdf");   
    }
}