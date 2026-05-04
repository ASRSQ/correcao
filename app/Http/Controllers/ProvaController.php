<?php

namespace App\Http\Controllers;

use App\Models\Prova;
use App\Models\Gabarito;
use App\Models\Aluno;
use App\Models\Resultado;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Escola;

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
    $escolas = Escola::all();
    $series = Serie::all(); // 🔥 agora certo

    return view('provas.create', compact('escolas', 'series'));
}
    // SALVAR PROVA (SEM GABARITO AINDA)
 public function store(Request $request)
{
    $request->validate([
        'nome' => 'required',
        'qtd_questoes' => 'required|integer|min:1',
        'qtd_alternativas' => 'required|integer|min:2',
        'escola_id' => 'required|exists:escolas,id',
        'serie_id' => 'required|exists:series,id'
    ]);

    $prova = Prova::create([
        'nome' => $request->nome,
        'qtd_questoes' => $request->qtd_questoes,
        'qtd_alternativas' => $request->qtd_alternativas,
        'escola_id' => $request->escola_id,
        'serie_id' => $request->serie_id
    ]);

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
public function corrigirLoteStep(Request $request, $provaId)
{
    \Log::info('CORRIGIR LOTE CHAMADO', [
    'prova_id' => $provaId,
    'tem_imagem' => $request->hasFile('imagem'),
    'headers' => $request->headers->all()
]);
    $prova = Prova::with('gabaritos')->findOrFail($provaId);

    // 🔥 recebe 1 imagem (não array)
    $imagem = $request->file('imagem');

    if (!$imagem) {
        return response()->json([
            'error' => true,
            'message' => 'Imagem não enviada'
        ]);
    }

    $imagemContent = file_get_contents($imagem);

    $response = Http::attach(
        'file',
        $imagemContent,
        $imagem->getClientOriginalName()
    )->post('http://127.0.0.1:8000/corrigir', [
        'qtd_questoes' => $prova->qtd_questoes,
        'qtd_alternativas' => $prova->qtd_alternativas
    ]);

    if (!$response->ok()) {
        return response()->json([
            'error' => true
        ]);
    }

    $dados = $response->json();

    $respostas = $dados['respostas'] ?? [];
    $invalidas = $dados['invalidas'] ?? [];

    // 🔥 aluno via QR
    $aluno = null;
    if (!empty($dados['matricula'])) {
        $qr = json_decode($dados['matricula'], true);
        if (isset($qr['aluno'])) {
            $aluno = Aluno::where('matricula', $qr['aluno'])->first();
        }
    }

    // 🔥 correção
    $gabaritos = $prova->gabaritos->pluck('resposta', 'questao');

    $acertos = 0;

    foreach ($respostas as $q => $resp) {
        if (
            isset($gabaritos[$q]) &&
            $gabaritos[$q] == $resp &&
            !in_array($q, $invalidas)
        ) {
            $acertos++;
        }
    }

    $total = $prova->qtd_questoes;
    $erros = ($total - count($invalidas)) - $acertos;

    Resultado::create([
        'prova_id' => $prova->id,
        'aluno_id' => $aluno->id ?? null,
        'qtd_questoes' => $total,
        'acertos' => $acertos,
        'erros' => $erros,
        'respostas' => json_encode($respostas)
    ]);

    return response()->json([
        'success' => true
    ]);
}
public function corrigir(Request $request, $id)
{
    $prova = Prova::with('gabaritos')->findOrFail($id);

    // 📷 imagem
    $imagemContent = file_get_contents($request->file('imagem'));

    // 🔥 envia pra API Python
    $response = Http::attach(
        'file',
        $imagemContent,
        'imagem.jpg'
    )->post('http://127.0.0.1:8000/corrigir', [
        'qtd_questoes' => $prova->qtd_questoes,
        'qtd_alternativas' => $prova->qtd_alternativas
    ]);

    if (!$response->ok()) {
        return back()->with('error', 'Erro ao processar a imagem.');
    }

    $dados = $response->json();

    $respostas = $dados['respostas'] ?? [];
    $matriculaRaw = $dados['matricula'] ?? null;
    $invalidas = $dados['invalidas'] ?? [];

    // =========================
    // 🎯 DECODIFICAR QR CODE
    // =========================
    $aluno = null;

    if ($matriculaRaw) {
        $qrData = json_decode($matriculaRaw, true);

        if (json_last_error() === JSON_ERROR_NONE && isset($qrData['aluno'])) {
            $aluno = \App\Models\Aluno::where('matricula', $qrData['aluno'])->first();
        }
    }

    // =========================
    // 📊 GABARITO
    // =========================
    $gabaritos = $prova->gabaritos->pluck('resposta', 'questao');

    $acertos = 0;

    foreach ($respostas as $q => $resp) {
        if (
            isset($gabaritos[$q]) &&
            $gabaritos[$q] == $resp &&
            !in_array($q, $invalidas)
        ) {
            $acertos++;
        }
    }

    // =========================
    // 📈 CÁLCULOS
    // =========================
    $totalQuestoes = $prova->qtd_questoes;
    $respondidas = count($respostas);
    $totalInvalidas = count($invalidas);

    $totalValidas = $totalQuestoes - $totalInvalidas;

    $erros = $totalValidas - $acertos;
    $brancos = $totalQuestoes - $respondidas;

    // =========================
    // 💾 SALVAR RESULTADO
    // =========================
   Resultado::updateOrCreate(
    [
        'prova_id' => $prova->id,
        'aluno_id' => $aluno->id ?? null
    ],
    [
        'qtd_questoes' => $total,
        'acertos' => $acertos,
        'erros' => $erros,
        'respostas' => json_encode($respostas)
    ]
);

    // =========================
    // 🔁 REDIRECIONAR
    // =========================
    return redirect()->route('provas.resultado', $resultado->id);
}
public function resultado($id)
{
    $resultado = Resultado::with('prova.gabaritos')->findOrFail($id);

    $respostas = json_decode($resultado->respostas, true);

    return view('provas.resultado', compact('resultado', 'respostas'));
}
public function resultados_update(Request $request, $id)
{
    $resultado = Resultado::with('prova.gabaritos')->findOrFail($id);

    $respostas = $request->input('respostas', []);

    $acertos = 0;
    $erros = 0;

    foreach ($respostas as $q => $resp) {

        $gabarito = $resultado->prova->gabaritos
            ->where('questao', $q)
            ->first();

        $gabaritoResposta = $gabarito->resposta ?? null;

        if (!empty($resp) && $resp == $gabaritoResposta) {
            $acertos++;
        } else {
            $erros++;
        }
    }

    $resultado->update([
        'respostas'     => json_encode($respostas),
        'acertos'       => $acertos,
        'erros'         => $erros,
        'qtd_questoes'  => count($respostas) // garante consistência
    ]);

    return redirect()
        ->route('provas.resultado', $resultado->id)
        ->with('success', 'Respostas atualizadas com sucesso!');
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
public function formAvulso($prova_id)
{
    $prova = Prova::with('gabaritos')->findOrFail($prova_id);
    $alunos = Aluno::where('escola_id', $prova->escola_id)
               ->where('serie_id', $prova->serie_id)
               ->get();

    return view('provas.avulso', compact('prova', 'alunos'));
}
public function storeAvulso(Request $request, $prova_id)
{
    $prova = Prova::with('gabaritos')->findOrFail($prova_id);

    $respostas = $request->input('respostas', []);
    $aluno_id = $request->input('aluno_id'); // 👈 pegando do form

    $acertos = 0;
    $erros = 0;

    foreach ($respostas as $q => $resp) {

        $gabarito = $prova->gabaritos
            ->where('questao', $q)
            ->first();

        $gabaritoResposta = $gabarito->resposta ?? null;

        if (!empty($resp) && $resp == $gabaritoResposta) {
            $acertos++;
        } else {
            $erros++;
        }
    }

    $resultado = Resultado::create([
        'prova_id'      => $prova_id,
        'aluno_id'      => $aluno_id ?: null, // 👈 salva ou null
        'qtd_questoes'  => count($respostas),
        'acertos'       => $acertos,
        'erros'         => $erros,
        'respostas'     => json_encode($respostas)
    ]);

    return redirect()->route('provas.resultado', $resultado->id);
}
}