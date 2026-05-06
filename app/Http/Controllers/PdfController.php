<?php

namespace App\Http\Controllers;

use App\Models\Prova;
use App\Models\Aluno;
use App\Models\Resultado;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use ZipArchive;


class PdfController extends Controller
{
    // 👁️ Tela de seleção
 public function selecionarAluno($provaId)
{
    $prova = Prova::findOrFail($provaId);

    $alunos = Aluno::where('escola_id', $prova->escola_id)
                   ->where('serie_id', $prova->serie_id)
                   ->get();

    return view('pdf.selecionar', compact('prova', 'alunos'));
}

    // 📄 Gerar PDF individual
    public function gerarIndividual($provaId, $alunoId)
    {
        $prova = Prova::findOrFail($provaId);
        $aluno = Aluno::findOrFail($alunoId);

        $prova->aluno = $aluno;

  
$cabecalho = 'data:image/png;base64,' . base64_encode(
    Http::get('https://spacesolutions.alphi.media/correcao/public/cabecalho.png')->body()
);

$footer = 'data:image/png;base64,' . base64_encode(
    Http::get('https://spacesolutions.alphi.media/correcao/public/footer.png')->body()
);

$html = view('pdf.cartao', compact('prova', 'cabecalho', 'footer'))->render();
        $response = Http::post('https://4969-2a02-c207-2316-6459-00-1.ngrok-free.app/pdf/gerar-pdf', [
            'html' => $html
        ]);

        return response($response->body(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename=gabarito_'.$aluno->matricula.'.pdf');
    }



public function gerarLote($provaId)
{
    $prova = Prova::findOrFail($provaId);
    $alunos = Aluno::all();

    Log::info("🚀 Iniciando geração em lote", [
        'prova_id' => $provaId,
        'total_alunos' => $alunos->count()
    ]);

    $zip = new ZipArchive();
    $zipFileName = storage_path('app/gabaritos.zip');

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

        foreach ($alunos as $index => $aluno) {

            Log::info("📄 Gerando PDF", [
                'aluno' => $aluno->nome,
                'matricula' => $aluno->matricula,
                'posicao' => ($index + 1) . '/' . $alunos->count()
            ]);

            try {

                $prova->aluno = $aluno;

                $html = view('pdf.cartao', compact('prova'))->render();

                $response = Http::timeout(60)->post('https://4969-2a02-c207-2316-6459-00-1.ngrok-free.app/pdf/gerar-pdf', [
                    'html' => $html
                ]);

                if (!$response->successful()) {
                    Log::error("❌ Erro ao gerar PDF", [
                        'aluno' => $aluno->nome
                    ]);
                    continue;
                }

                $fileName = 'gabarito_' . $aluno->matricula . '.pdf';

                $zip->addFromString($fileName, $response->body());

            } catch (\Exception $e) {

                Log::error("🔥 Exceção ao gerar PDF", [
                    'aluno' => $aluno->nome,
                    'erro' => $e->getMessage()
                ]);
            }
        }

        $zip->close();
    }

    Log::info("✅ Finalizado lote");

    return response()->download($zipFileName)->deleteFileAfterSend(true);
}

public function gerarLoteStep($provaId, $index)
{
    $prova = Prova::findOrFail($provaId);
    $alunos = Aluno::where('escola_id', $prova->escola_id)
               ->where('serie_id', $prova->serie_id)
               ->get()
               ->values(); // ✅ agora sim

    // 🔥 BASE64 IGUAL AO INDIVIDUAL
    $cabecalho = 'data:image/png;base64,' . base64_encode(
        Http::get('https://spacesolutions.alphi.media/correcao/public/cabecalho.png')->body()
    );

    $footer = 'data:image/png;base64,' . base64_encode(
        Http::get('https://spacesolutions.alphi.media/correcao/public/footer.png')->body()
    );


    $zipPath = storage_path("app/gabaritos_$provaId.zip");
    $zip = new ZipArchive();
    $zip->open($zipPath, ZipArchive::CREATE);

    // =========================
    // 🎯 GERAR ALUNOS
    // =========================
    if (isset($alunos[$index])) {

        $aluno = $alunos[$index];
        $prova->aluno = $aluno;

        // 🔥 IGUAL AO INDIVIDUAL
        $html = view('pdf.cartao', compact('prova', 'cabecalho', 'footer'))->render();

        $response = Http::timeout(60)->post('http://173.249.27.52:3000/gerar-pdf', [
            'html' => $html
        ]);

        if ($response->successful()) {
            $zip->addFromString(
                'gabarito_' . $aluno->matricula . '.pdf',
                $response->body()
            );
        }

        $zip->close();

        return response()->json([
            'finalizado' => false,
            'index' => $index + 1,
            'total' => count($alunos)
        ]);
    }

    // =========================
    // 📄 CARTÃO EM BRANCO
    // =========================

    $alunoFake = new \stdClass();
    $alunoFake->nome = '';
    $alunoFake->matricula = '';
    $alunoFake->serie_id = '';
    $alunoFake->escola = (object)['nome' => ''];

    $prova->aluno = $alunoFake;

    // QR genérico
    $qr = base64_encode(
        \QrCode::format('png')->size(120)->generate(json_encode([
            'prova' => $prova->id,
            'aluno' => '0000',
            'tipo' => 'blank'
        ]))
    );

    // 🔥 IMPORTANTE: mantém base64 aqui também
    $htmlBranco = view('pdf.cartao', [
        'prova' => $prova,
        'cabecalho' => $cabecalho,
        'footer' => $footer,
        'qr' => $qr
    ])->render();

    $responseBranco = Http::timeout(60)->post('http://173.249.27.52:3000/gerar-pdf', [
        'html' => $htmlBranco
    ]);

    if ($responseBranco->successful()) {
        $zip->addFromString(
            'cartao_branco.pdf',
            $responseBranco->body()
        );
    }

    $zip->close();

    return response()->json([
        'finalizado' => true,
        'download' => url("storage/gabaritos_$provaId.zip")
    ]);
}



public function download($provaId)
{
    $file = storage_path("app/gabaritos_$provaId.zip");

    Log::info("📥 Tentando download", [
        'prova_id' => $provaId,
        'caminho' => $file,
        'existe' => file_exists($file),
        'tamanho' => file_exists($file) ? filesize($file) : 0
    ]);

    if (!file_exists($file)) {

        Log::error("❌ Arquivo não encontrado", [
            'caminho' => $file
        ]);

        abort(404, 'Arquivo não encontrado');
    }

    return response()->download($file);
}
public function preview($provaId, $alunoId)
{
$prova = Prova::findOrFail($provaId);
$aluno = Aluno::findOrFail($alunoId);

$prova->aluno = $aluno;

return view('pdf.cartao', compact('prova'));
}
public function desempenho($provaId, $alunoId)
{
    $resultado = Resultado::where('prova_id', $provaId)
        ->where('aluno_id', $alunoId)
        ->with(['aluno.escola', 'prova.gabaritos'])
        ->first();
   

    // Se não tiver resultado ainda
    if (!$resultado) {
        return redirect()->back()
            ->with('error', 'Aluno ainda não possui resultado para essa prova.');
    }

    // Converter respostas
    $respostas = is_array($resultado->respostas)
        ? $resultado->respostas
        : json_decode($resultado->respostas, true);

    return view('provas.desempenho', compact('resultado', 'respostas'));
}
}