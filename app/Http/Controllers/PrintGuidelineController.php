<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PrintGuidelineController extends Controller
{
    public function PrintGuidelines()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.guidelines-pdf');
        $filename = "Policies and Guidelines.pdf";
        
        return $pdf->stream($filename);
    }
}
