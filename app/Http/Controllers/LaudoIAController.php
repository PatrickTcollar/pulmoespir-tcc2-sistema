<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaudoIAController extends Controller
{
    public function gerar(Request $request)
    {
        $dados = $request->input('dados');

        // Aqui entraria a lógica de chamada à API do ChatGPT com os dados fornecidos
        $laudo = "Laudo gerado com base nos dados: " . $dados;

        return response()->json([
            'success' => true,
            'laudo' => $laudo,
        ]);
    }
}
