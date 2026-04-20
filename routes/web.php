<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProvaController;
use App\Http\Controllers\PdfController;

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