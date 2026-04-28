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
        $alunos = Aluno::all();

        return view('pdf.selecionar', compact('prova', 'alunos'));
    }

    // 📄 Gerar PDF individual
    public function gerarIndividual($provaId, $alunoId)
    {
        $prova = Prova::findOrFail($provaId);
        $aluno = Aluno::findOrFail($alunoId);

        $prova->aluno = $aluno;

        $html = view('pdf.cartao', compact('prova'))->render();

        $response = Http::post('http://173.249.27.52:3000/gerar-pdf', [
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

                $response = Http::timeout(60)->post('http://173.249.27.52:3000/gerar-pdf', [
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
    $alunos = Aluno::all();

    if (!isset($alunos[$index])) {
        return response()->json(['finalizado' => true]);
    }

    $aluno = $alunos[$index];

    $zipPath = storage_path("app/gabaritos_$provaId.zip");

    $zip = new ZipArchive();
    $zip->open($zipPath, ZipArchive::CREATE);

    $prova->aluno = $aluno;

    $html = view('pdf.cartao', compact('prova'))->render();

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