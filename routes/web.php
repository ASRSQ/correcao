<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProvaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\ClassificacaoController;
use Illuminate\Support\Facades\Auth;

Auth::routes([
    'register' => false
]);

Route::middleware('auth')->group(function () {
/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get(
    '/register',
    [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm']
)->name('register');

Route::post(
    '/register',
    [App\Http\Controllers\Auth\RegisterController::class, 'register']
);


/*
|--------------------------------------------------------------------------
| PROVAS
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [ProvaController::class, 'index']
)->name('provas.index');

Route::get(
    '/prova/create',
    [ProvaController::class, 'create']
)->name('provas.create');

Route::post(
    '/prova',
    [ProvaController::class, 'store']
)->name('provas.store');

Route::get(
    '/prova/{id}/gabarito',
    [ProvaController::class, 'gabarito']
)->name('provas.gabarito');

Route::post(
    '/prova/{id}/gabarito',
    [ProvaController::class, 'salvarGabarito']
)->name('provas.salvarGabarito');

Route::post(
    '/prova/{id}/corrigir',
    [ProvaController::class, 'corrigir']
)->name('provas.corrigir');

Route::post(
    '/prova/{id}/corrigir-lote',
    [ProvaController::class, 'corrigirLoteStep']
)->name('corrigir.lote');

Route::get(
    '/resultado/{id}',
    [ProvaController::class, 'resultado']
)->name('provas.resultado');

Route::put(
    '/resultados/{id}',
    [ProvaController::class, 'resultados_update']
)->name('resultados.update');

Route::get(
    '/provas/{prova}/resultado-avulso',
    [ProvaController::class, 'formAvulso']
)->name('resultados.avulso.form');

Route::post(
    '/provas/{prova}/resultado-avulso',
    [ProvaController::class, 'storeAvulso']
)->name('resultados.avulso.store');

Route::get(
    '/escolas/{escola}/series/{serie}/provas',
    [ProvaController::class, 'serie']
)->name('serie.provas');
Route::get(
    '/dashboard/provas',
    [ProvaController::class, 'dashboard']
)->name('provas.dashboard');



/*
|--------------------------------------------------------------------------
| PDFS
|--------------------------------------------------------------------------
*/

Route::get(
    '/prova/{id}/pdf',
    [PdfController::class, 'gerar']
)->name('provas.pdf');

Route::get(
    '/pdf/{prova}/selecionar',
    [PdfController::class, 'selecionarAluno']
)->name('pdf.selecionar');

Route::get(
    '/pdf/{prova}/{aluno}',
    [PdfController::class, 'gerarIndividual']
)->name('pdf.individual');

Route::get(
    '/pdf-preview/{prova}/{aluno}',
    [PdfController::class, 'preview']
)->name('pdf.preview');

Route::get(
    '/pdf-lote/{prova}',
    [PdfController::class, 'gerarLote']
)->name('pdf.lote');

Route::get(
    '/pdf/lote-step/{prova}/{index}',
    [PdfController::class, 'gerarLoteStep']
)->name('pdf.lote.step');

Route::get(
    '/download-zip/{prova}',
    [PdfController::class, 'download']
)->name('download.zip');

Route::get(
    '/prova/{prova}/aluno/{aluno}/desempenho',
    [PdfController::class, 'desempenho']
)->name('prova.desempenho');



/*
|--------------------------------------------------------------------------
| CIDADES
|--------------------------------------------------------------------------
*/

Route::resource(
    'cidades',
    CidadeController::class
);

Route::get(
    '/cidades/{cidade}/escolas',
    [EscolaController::class, 'cidade']
)->name('cidade.escolas');



/*
|--------------------------------------------------------------------------
| ESCOLAS
|--------------------------------------------------------------------------
*/

Route::resource(
    'escolas',
    EscolaController::class
);



/*
|--------------------------------------------------------------------------
| SÉRIES
|--------------------------------------------------------------------------
*/

Route::resource(
    'series',
    SerieController::class
);

Route::get(
    '/escolas/{escola}/series',
    [SerieController::class, 'escola']
)->name('escola.series');

Route::get(
    '/escolas/{escola}/series/{serie}',
    [SerieController::class, 'area']
)->name('escola.serie');



/*
|--------------------------------------------------------------------------
| ALUNOS
|--------------------------------------------------------------------------
*/

Route::resource(
    'alunos',
    AlunoController::class
);

Route::get(
    '/escolas/{escola}/series/{serie}/alunos',
    [AlunoController::class, 'serie']
)->name('serie.alunos');

Route::get(
    '/provas/{prova}/classificacao',
    [ClassificacaoController::class, 'index']
)->name('provas.classificacao');

Route::post(
    '/provas/{prova}/categoria',
    [ClassificacaoController::class, 'storeCategoria']
)->name('provas.categoria');

Route::post(
    '/provas/{prova}/subcategoria',
    [ClassificacaoController::class, 'storeSubcategoria']
)->name('provas.subcategoria');

Route::post(
    '/provas/{prova}/salvar-classificacao',
    [ClassificacaoController::class, 'salvarClassificacao']
)->name('provas.salvar.classificacao');
});