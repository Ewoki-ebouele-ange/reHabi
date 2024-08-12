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

    public function show() {
        $periodTimestamp = Carbon::now()->subDays(3);

        $employes= Employe::all();
            
            $pdf = PDF::loadView('pdf.differences', [
                'employes' => $employes,
                'periodTimestamp' => $periodTimestamp,
            ]);
            
            return view("pdf.differences", [
                'employes' => $employes,
                'periodTimestamp' => $periodTimestamp,
            ]);
    }

    public function download(Rapport $rapport){

        $rap = Rapport::find($rapport);

        $periodTimestamp = Carbon::now()->subDays(3);

        $employes= Employe::all();
            
            $pdf = PDF::loadView('pdf.differences', [
                'employes' => $employes,
                'periodTimestamp' => $periodTimestamp,
            ]);
            
            return $pdf->download("Rapport du ".$rap->first()->created_at.".pdf");   
    }
}