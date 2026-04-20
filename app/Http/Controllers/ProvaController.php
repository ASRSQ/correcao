<?php

namespace App\Http\Controllers;

use App\Models\Prova;
use App\Models\Gabarito;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProvaController extends Controller
{
    // LISTAR PROVAS
    public function index()
    {
        $provas = Prova::all();
        return view('provas.index', compact('provas'));
    }

    // FORM DE CRIAÇÃO
    public function create()
    {
        return view('provas.create');
    }

    // SALVAR PROVA (SEM GABARITO AINDA)
    public function store(Request $request)
    {
        $prova = Prova::create([
            'nome' => $request->nome,
            'qtd_questoes' => $request->qtd_questoes,
            'qtd_alternativas' => $request->qtd_alternativas
        ]);

        // Redireciona para cadastrar gabarito
        return redirect("/prova/{$prova->id}/gabarito");
    }

    // TELA DE GABARITO
    public function gabarito($id)
    {
        $prova = Prova::findOrFail($id);
        return view('provas.gabarito', compact('prova'));
    }

    // SALVAR GABARITO
    public function salvarGabarito(Request $request, $id)
    {
        // limpa gabarito antigo (caso edite)
        Gabarito::where('prova_id', $id)->delete();

        foreach ($request->gabarito as $questao => $resposta) {
            Gabarito::create([
                'prova_id' => $id,
                'questao' => $questao,
                'resposta' => $resposta
            ]);
        }

        return redirect('/')->with('success', 'Gabarito salvo!');
    }

public function corrigir(Request $request, $id)
{
    $prova = Prova::findOrFail($id);

    $imagemContent = file_get_contents($request->file('imagem'));

    $response = Http::attach(
        'file',
        $imagemContent,
        'imagem.jpg'
    )->post('http://127.0.0.1:8000/corrigir');

    $respostas = $response->json()['respostas'];

    $gabaritos = $prova->gabaritos->pluck('resposta', 'questao');

    $acertos = 0;

    foreach ($respostas as $q => $resp) {
        if (isset($gabaritos[$q]) && $gabaritos[$q] == $resp) {
            $acertos++;
        }
    }

    $resultado = Resultado::create([
        'prova_id' => $prova->id,
        'acertos' => $acertos,
        'erros' => count($respostas) - $acertos,
        'respostas' => json_encode($respostas)
    ]);

    // 🔥 REDIRECIONA PRA OUTRA PÁGINA
    return redirect()->route('provas.resultado', $resultado->id);
}
public function resultado($id)
{
    $resultado = Resultado::with('prova.gabaritos')->findOrFail($id);

    $respostas = json_decode($resultado->respostas, true);

    return view('provas.resultado', compact('resultado', 'respostas'));
}
public function dashboard()
{
    return view('provas.dashboard', [
        'questoes' => range(1, 10),
        'acertosQuestoes' => [10,8,7,9,6,5,10,9,8,7],

        'geral' => [
            'acertos' => 75,
            'erros' => 25
        ],

        'categorias' => [
            'Matemática' => 30,
            'Português' => 25,
            'Ciências' => 20
        ],

        'subcategorias' => [
            'Álgebra' => 15,
            'Geometria' => 15,
            'Gramática' => 10,
            'Interpretação' => 15
        ]
    ]);
}
}