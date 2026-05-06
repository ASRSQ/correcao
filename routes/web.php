<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvaController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\AlunoController;

Route::get('/', [ProvaController::class, 'index'])->name('provas.index');

Route::get('/prova/create', [ProvaController::class, 'create'])->name('provas.create');
Route::post('/prova', [ProvaController::class, 'store'])->name('provas.store');

Route::get('/prova/{id}/gabarito', [ProvaController::class, 'gabarito'])->name('provas.gabarito');
Route::post('/prova/{id}/gabarito', [ProvaController::class, 'salvarGabarito'])->name('provas.salvarGabarito');

Route::get('/prova/{id}/pdf', [PdfController::class, 'gerar'])->name('provas.pdf');

Route::post('/prova/{id}/corrigir', [ProvaController::class, 'corrigir'])->name('provas.corrigir');
Route::get('/resultado/{id}', [ProvaController::class, 'resultado'])
    ->name('provas.resultado');
Route::get('/dashboard', [ProvaController::class, 'dashboard'])
    ->name('provas.dashboard');

Route::get('/provas', [PdfController::class, 'index']);


Route::get('/pdf/{prova}/selecionar', [PdfController::class, 'selecionarAluno'])->name('pdf.selecionar');

Route::get('/pdf/{prova}/{aluno}', [PdfController::class, 'gerarIndividual'])->name('pdf.individual');

Route::get('/pdf-lote/{prova}', [PdfController::class, 'gerarLote'])->name('pdf.lote');

Route::get('/pdf-preview/{prova}/{aluno}', [PdfController::class, 'preview'])
    ->name('pdf.preview');

Route::get('/prova/{prova}/aluno/{aluno}/desempenho',
    [PdfController::class, 'desempenho'])
    ->name('prova.desempenho');

Route::put('/resultados/{id}', [ProvaController::class, 'resultados_update'])
    ->name('resultados.update');
Route::get('/provas/{prova}/resultado-avulso', [ProvaController::class, 'formAvulso'])
    ->name('resultados.avulso.form');

Route::post('/provas/{prova}/resultado-avulso', [ProvaController::class, 'storeAvulso'])
    ->name('resultados.avulso.store');
Route::get('/pdf/lote-step/{prova}/{index}', [PdfController::class, 'gerarLoteStep'])
    ->name('pdf.lote.step');

Route::get('/download-zip/{prova}', [PdfController::class, 'download'])
    ->name('download.zip');
Route::post('/prova/{id}/corrigir-lote', [ProvaController::class, 'corrigirLoteStep'])
    ->name('corrigir.lote');
Route::resource('cidades', CidadeController::class);
Route::resource('escolas', EscolaController::class);
Route::resource('series', SerieController::class);
Route::resource('alunos', AlunoController::class);