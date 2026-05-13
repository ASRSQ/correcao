<?php

namespace App\Http\Controllers;

use App\Models\Prova;
use App\Models\Gabarito;
use App\Models\Aluno;
use App\Models\Resultado;
use App\Models\Serie;
use App\Models\CIdade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Escola;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Questao;

class ProvaController extends Controller
{
    // LISTAR PROVAS
    public function index()
{
    $cidades = Cidade::withCount('escolas')->get();

    return view('dashboard.index', compact('cidades'));
}

    // FORM DE CRIAÇÃO
public function create(Request $request)
{
    $escolas = Escola::all();
    $series = Serie::all();

    $escolaSelecionada =
        $request->escola_id;

    $serieSelecionada =
        $request->serie_id;

    return view(
        'provas.create',
        compact(
            'escolas',
            'series',
            'escolaSelecionada',
            'serieSelecionada'
        )
    );
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
public function salvarGabarito(Request $request, $id)
{
    // limpa gabarito antigo
    Gabarito::where('prova_id', $id)->delete();

    foreach ($request->gabarito as $questao => $resposta) {

        Gabarito::create([
            'prova_id' => $id,
            'questao'  => $questao,
            'resposta' => $resposta
        ]);
    }

    $prova = Prova::findOrFail($id);

    return redirect()->route('serie.provas', [
        'escola' => $prova->escola_id,
        'serie'  => $prova->serie_id
    ])->with('success', 'Gabarito salvo!');
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
    \Log::info('DADOS ENVIADOS PARA O OMR', [
    'prova_id' => $prova->id,
    'qtd_questoes' => $prova->qtd_questoes,
    'qtd_alternativas' => $prova->qtd_alternativas,
    'arquivo' => $imagem->getClientOriginalName(),
]);

    $response = Http::attach(
        'file',
        $imagemContent,
        $imagem->getClientOriginalName()
    )->post('https://4969-2a02-c207-2316-6459-00-1.ngrok-free.app/omr/corrigir', [
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

  Resultado::updateOrCreate(
    [
        'prova_id' => $prova->id,
        'aluno_id' => $aluno->id ?? null,
    ],
    [
        'qtd_questoes' => $total,
        'acertos' => $acertos,
        'erros' => $erros,
        'respostas' => json_encode($respostas)
    ]
);
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
    )->post('https://4969-2a02-c207-2316-6459-00-1.ngrok-free.app/omr/corrigir', [
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
public function dashboard(Prova $prova)
{
    // =========================================
    // RESULTADOS
    // =========================================
    $resultados = Resultado::with('aluno')
        ->where('prova_id', $prova->id)
        ->get();

    // =========================================
    // QUESTÕES
    // =========================================
    $questoesBanco = Questao::with(
            'subcategory.category'
        )
        ->where('prova_id', $prova->id)
        ->orderBy('numero')
        ->get();

    // =========================================
    // GABARITO
    // =========================================
    $gabaritos = Gabarito::where(
        'prova_id',
        $prova->id
    )->get();

    $gabarito = [];

    foreach ($gabaritos as $item) {

        $gabarito[$item->questao]
            = strtoupper($item->resposta);
    }

    // =========================================
    // LABELS DAS QUESTÕES
    // =========================================
    $labelsQuestoes = [];

    foreach ($questoesBanco as $questao) {

        $labelsQuestoes[] =

            'Q' . $questao->numero

            . ' - '

            . $questao->subcategory
                ->category
                ->nome

            . ' | '

            . $questao->subcategory
                ->nome;
    }

    // =========================================
    // ACERTOS POR QUESTÃO
    // =========================================
    $acertosQuestoes = [];

    foreach ($questoesBanco as $questao) {

        $contador = 0;

        foreach ($resultados as $resultado) {

            $respostasAluno = json_decode(
                $resultado->respostas,
                true
            );

            $respostaAluno =
                strtoupper(
                    $respostasAluno
                    [$questao->numero] ?? ''
                );

            $respostaCorreta =
                strtoupper(
                    $gabarito
                    [$questao->numero] ?? ''
                );

            if (
                $respostaAluno !== '' &&
                $respostaAluno === $respostaCorreta
            ) {

                $contador++;
            }
        }

        $acertosQuestoes[] = $contador;
    }

    // =========================================
    // CATEGORIAS
    // =========================================
    $categorias = [];

    // =========================================
    // SUBCATEGORIAS
    // =========================================
    $subcategorias = [];

    // =========================================
    // DETALHES DOS ALUNOS
    // =========================================
    $detalhesAlunos = [];

    foreach ($resultados as $resultado) {

        $nomeAluno =
            $resultado->aluno->nome;

        $detalhesAlunos[$nomeAluno] = [

            'acertos' => [],
            'erros' => []
        ];

        $respostasAluno = json_decode(
            $resultado->respostas,
            true
        );

        foreach ($questoesBanco as $questao) {

            $numeroQuestao =
                $questao->numero;

            $categoria =
                $questao->subcategory
                    ->category
                    ->nome;

            $subcategoria =
                '[' .
                $questao->subcategory
                    ->category
                    ->nome
                . '] '
                .
                $questao->subcategory
                    ->nome;

            // =================================
            // INICIALIZA
            // =================================
            if (!isset($categorias[$categoria])) {

                $categorias[$categoria] = 0;
            }

            if (!isset($subcategorias[$subcategoria])) {

                $subcategorias[$subcategoria] = 0;
            }

            // =================================
            // RESPOSTAS
            // =================================
            $respostaAluno =
                strtoupper(
                    $respostasAluno
                    [$numeroQuestao] ?? ''
                );

            $respostaCorreta =
                strtoupper(
                    $gabarito
                    [$numeroQuestao] ?? ''
                );

            // =================================
            // ACERTO
            // =================================
            if (
                $respostaAluno !== '' &&
                $respostaAluno === $respostaCorreta
            ) {

                $categorias[$categoria]++;
                $subcategorias[$subcategoria]++;

                $detalhesAlunos
                [$nomeAluno]
                ['acertos'][] = [

                    'questao' =>
                        $numeroQuestao,

                    'categoria' =>
                        $categoria,

                    'subcategoria' =>
                        $subcategoria
                ];
            }

            // =================================
            // ERRO
            // =================================
            else {

                $detalhesAlunos
                [$nomeAluno]
                ['erros'][] = [

                    'questao' =>
                        $numeroQuestao,

                    'categoria' =>
                        $categoria,

                    'subcategoria' =>
                        $subcategoria,

                    'resposta_aluno' =>
                        $respostaAluno,

                    'resposta_correta' =>
                        $respostaCorreta
                ];
            }
        }
    }

    // =========================================
    // ACERTOS POR CATEGORIA POR ALUNO
    // =========================================
   // =========================================
// ACERTOS POR CATEGORIA POR ALUNO
// =========================================
$desempenhoCategorias = [];

foreach ($resultados as $resultado) {

    $nomeAluno =
        $resultado->aluno->nome;

    $desempenhoCategorias[$nomeAluno] = [];

    // respostas do aluno
    $respostasAluno = json_decode(
        $resultado->respostas,
        true
    );

    foreach ($questoesBanco as $questao) {

        $numeroQuestao =
            $questao->numero;

        $categoria =
            $questao->subcategory
                ->category
                ->nome;

        // inicia categoria
        if (
            !isset(
                $desempenhoCategorias
                [$nomeAluno]
                [$categoria]
            )
        ) {

            $desempenhoCategorias
            [$nomeAluno]
            [$categoria] = 0;
        }

        // resposta marcada
        $respostaAluno =
            strtoupper(
                trim(
                    $respostasAluno
                    [$numeroQuestao] ?? ''
                )
            );

        // resposta correta
        $respostaCorreta =
            strtoupper(
                trim(
                    $gabarito
                    [$numeroQuestao] ?? ''
                )
            );

        // ignora sem resposta
        if (
            empty($respostaAluno)
        ) {
            continue;
        }

        // compara
        if (
            $respostaAluno ===
            $respostaCorreta
        ) {

            $desempenhoCategorias
            [$nomeAluno]
            [$categoria]++;
        }
    }

    // =====================================
    // GARANTE QUE BATA COM TOTAL
    // =====================================
    $somaCategorias = array_sum(
        $desempenhoCategorias
        [$nomeAluno]
    );

    // ajuste automático
    if (
        $somaCategorias >
        $resultado->acertos
    ) {

        $diferenca =
            $somaCategorias
            -
            $resultado->acertos;

        $ultimaCategoria =
            array_key_last(
                $desempenhoCategorias
                [$nomeAluno]
            );

        $desempenhoCategorias
        [$nomeAluno]
        [$ultimaCategoria]
        -= $diferenca;
    }
}

    // =========================================
    // RANKING
    // =========================================
    $ranking = $resultados
        ->sortByDesc('acertos')
        ->values()
        ->map(function ($resultado) {

            $percentual =
                $resultado->qtd_questoes > 0
                ? round(
                    (
                        $resultado->acertos
                        /
                        $resultado->qtd_questoes
                    ) * 100
                )
                : 0;

            return [

                'nome' =>
                    $resultado->aluno->nome ?? 'Aluno',

                'acertos' =>
                    $resultado->acertos,

                'erros' =>
                    $resultado->erros,

                'percentual' =>
                    $percentual
            ];
        });

    // =========================================
    // GERAL
    // =========================================
    $geral = [

        'acertos' =>
            $resultados->sum('acertos'),

        'erros' =>
            $resultados->sum('erros')
    ];

    // =========================================
    // MÉDIA DA TURMA
    // =========================================
    $mediaTurma = round(
        $resultados->avg('acertos'),
        1
    );

    // =========================================
    // QUESTÃO MAIS DIFÍCIL
    // =========================================
    $questaoMaisDificil = null;
    $questaoMaisFacil = null;

    if (!empty($acertosQuestoes)) {

        $menorAcerto = min($acertosQuestoes);

        $questaoMaisDificil =
            array_search(
                $menorAcerto,
                $acertosQuestoes
            ) + 1;

        // =====================================
        // QUESTÃO MAIS FÁCIL
        // =====================================
        $maiorAcerto = max($acertosQuestoes);

        $questaoMaisFacil =
            array_search(
                $maiorAcerto,
                $acertosQuestoes
            ) + 1;
    }

    // =========================================
    // MELHOR ALUNO
    // =========================================
    $melhorAluno =
        $ranking->first();

//     dd(

//     $resultado->aluno->nome,

//     $resultado->acertos,

//     $desempenhoCategorias
//     [$nomeAluno],
//      $questoesBanco->count(),

//     array_sum(
//         $desempenhoCategorias
//         [$nomeAluno]
//     )

// );

    // =========================================
    // VIEW
    // =========================================
    return view(
        'provas.dashboard',
        [

            'prova' => $prova,

            'ranking' => $ranking,

            'questoes' =>
                $questoesBanco
                    ->pluck('numero'),

            'labelsQuestoes' =>
                $labelsQuestoes,

            'acertosQuestoes' =>
                $acertosQuestoes,

            'geral' =>
                $geral,

            'categorias' =>
                $categorias,

            'subcategorias' =>
                $subcategorias,

            'mediaTurma' =>
                $mediaTurma,

            'questaoMaisDificil' =>
                $questaoMaisDificil,

            'questaoMaisFacil' =>
                $questaoMaisFacil,

            'melhorAluno' =>
                $melhorAluno,

            'desempenhoCategorias' =>
                $desempenhoCategorias,

            'detalhesAlunos' =>
                $detalhesAlunos
        ]
    );
}
public function formAvulso($prova_id)
{
    $prova = Prova::with('gabaritos')->findOrFail($prova_id);

    // 👇 somente alunos da série da prova
    $alunos = Aluno::where('serie_id', $prova->serie_id)
        ->orderBy('nome')
        ->get();

    return view(
        'provas.avulso',
        compact('prova', 'alunos')
    );
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

   return redirect()->route(
    'pdf.selecionar',
    $prova->id
);
}
public function serie(
    Escola $escola,
    Serie $serie
) {

    $provas = Prova::where(
        'escola_id',
        $escola->id
    )
    ->where(
        'serie_id',
        $serie->id
    )
    ->get();

    return view(
        'provas.index',
        compact(
            'provas',
            'escola',
            'serie'
        )
    );
}
public function destroy($id)
{
    $prova = Prova::findOrFail($id);

    // remove gabaritos
    Gabarito::where(
        'prova_id',
        $prova->id
    )->delete();

    // remove resultados
    Resultado::where(
        'prova_id',
        $prova->id
    )->delete();

    // remove questões
    Questao::where(
        'prova_id',
        $prova->id
    )->delete();

    // remove prova
    $prova->delete();

    return redirect()
        ->back()
        ->with(
            'success',
            'Prova excluída com sucesso!'
        );
}
}