<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prova;

class PdfController extends Controller
{
    public function gerar($id)
    {
        $prova = Prova::findOrFail($id);

        $pdf = Pdf::loadView('pdf.cartao', [
            'prova' => $prova
        ]);

        return $pdf->download('cartao_resposta.pdf');
    }
}