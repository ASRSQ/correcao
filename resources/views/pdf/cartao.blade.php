<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>
@page {
    size: A4;
    margin: 0;
}

body {
    margin: 0;
    font-family: Arial;
    background: white;
}

/* PÁGINA */
.page {
    width: 210mm;
    height: 297mm;
    padding: 10mm;
    box-sizing: border-box;
    position: relative;
}

/* HEADER */
.header {
    margin-bottom: 15px;
}

/* FORM */
.form-row {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}

.input-box {
    border: 1px solid #ccc;
    padding: 6px;
    flex: 1;
    font-size: 12px;
    position: relative;
    min-height: 20px;
}

.input-box.small {
    width: 120px;
    flex: none;
}

.input-label {
    position: absolute;
    top: -7px;
    left: 8px;
    background: white;
    font-size: 10px;
    font-weight: bold;
}

/* ASSINATURA */
.signature-box {
    border: 2px solid black;
    height: 35px;
    margin-bottom: 15px;
}

.signature-label {
    text-align: center;
    font-size: 12px;
    margin-bottom: 5px;
}

/* INSTRUÇÕES */
.instructions-container {
            display: flex;
            justify-content: space-between;
            font-family: "Courier New", Courier, monospace;
            font-size: 13px;
            margin-bottom: 30px;
        }
        .instructions h4 { margin: 0 0 5px 0; font-size: 14px;}
        .instructions ol { margin: 0; padding-left: 20px; }
        .example { margin-top: 15px; display: flex; align-items: center; }
        .filled-bubble {
            width: 16px; height: 16px;
            background-color: black;
            border-radius: 50%;
            margin-left: 10px;
        }
        

/* 🔥 SCAN AREA FIXA */
.scan-area {
    width: 150mm;
    height: 120mm;
    margin: 10px auto;
    position: relative;
}

/* MARCADORES */
.marker {
    position: absolute;
    width: 12mm;
    height: 12mm;
    border-style: solid;
}

.top-left { top: 0; left: 0; border-width: 4mm 0 0 4mm; }
.top-right { top: 0; right: 0; border-width: 4mm 4mm 0 0; }
.bottom-left { bottom: 0; left: 0; border-width: 0 0 4mm 4mm; }
.bottom-right { bottom: 0; right: 0; border-width: 0 4mm 4mm 0; }

/* GABARITO */
.gabarito {
    display: flex;
    justify-content: space-between;
    height: 100%;
    padding: 10mm;
}

/* COLUNA */
.coluna {
    display: flex;
    flex-direction: column;
    gap: 2mm;
}

/* LINHA */
.linha {
    display: flex;
    align-items: center;
    gap: 1.5mm;
}

/* NÚMERO */
.q {
    width: 6mm;
    font-size: 10px;
    text-align: right;
}

/* 🔥 BOLHAS DINÂMICAS */
.bubble {
    width: 4.5mm;
    height: 4.5mm;
    border: 1px solid black;
    border-radius: 50%;
    font-size: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* FOOTER */
.footer {
    position: absolute;
    bottom: 8mm;
    width: 100%;
    text-align: center;
}
 h1 {
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-size: 24px;
            margin-bottom: 30px;
        }
</style>
</head>

<body>

<div class="page">

<div class="header">
    <img src="{{ $cabecalho }}" style="width:100%;">
</div>

    <h1>{{ $prova->nome }}</h1>
<div class="form-row">
    <div class="input-box">
        <span class="input-label">ALUNO</span>
        {{ $prova->aluno->nome }}
    </div>
    <div class="input-box small">
        <span class="input-label">MATRÍCULA</span>
        {{ $prova->aluno->matricula }}
    </div>
</div>

<div class="form-row">
    <div class="input-box">
        <span class="input-label">ESCOLA</span>
        {{ $prova->aluno->escola->nome ?? '' }}
    </div>
    <div class="input-box small">
        <span class="input-label">TURMA</span>
        {{ $prova->aluno->serie->nome ?? '' }}
    </div>
</div>

<div class="signature-label">ASSINE CONFORME O DOCUMENTO</div>
<div class="signature-box"></div>
 <div class="instructions-container">
        <div class="instructions">
            <h4>Instruções</h4>
            <ol>
                <li>Use caneta de cor preta ou azul escuro.</li>
                <li>Preencha as bolhas completamente.</li>
                <li>Preencha apenas uma letra por questão.</li>
                <li>Não dobre ou amasse esta folha</li>
            </ol>
            <div class="example">
                Preenchimento correto: <div class="filled-bubble"></div>
            </div>
        </div>
        <div class="qr-code">
            {!! QrCode::size(100)->generate(json_encode([
                    'prova' => $prova->id,
                    'aluno' => $prova->aluno->matricula
                ])) !!}
        </div>
    </div>
@php

$total = $prova->qtd_questoes;

/*
|--------------------------------------------------------------------------
| REGRAS DE DISTRIBUIÇÃO
|--------------------------------------------------------------------------
|
| até 20  -> 2 colunas
| até 30  -> 3 colunas
| até 40  -> 4 colunas (10)
| até 44  -> 4 colunas (11)
| até 48  -> 4 colunas (12)
| até 52  -> 4 colunas (13)
| até 56  -> 4 colunas (14)
| até 60  -> 4 colunas (15)
| acima   -> automático
|
|--------------------------------------------------------------------------
*/

if ($total <= 20) {

    $colunas = 2;

} elseif ($total <= 30) {

    $colunas = 3;

} elseif ($total <= 60) {

    $colunas = 4;

} else {

    $colunas = 5;
}

/*
|--------------------------------------------------------------------------
| QUESTÕES POR COLUNA
|--------------------------------------------------------------------------
*/

if ($total <= 40) {

    $porColuna = 10;

} elseif ($total <= 44) {

    $porColuna = 11;

} elseif ($total <= 48) {

    $porColuna = 12;

} elseif ($total <= 52) {

    $porColuna = 13;

} elseif ($total <= 56) {

    $porColuna = 14;

} elseif ($total <= 60) {

    $porColuna = 15;

} else {

    $porColuna = ceil($total / $colunas);
}

/*
|--------------------------------------------------------------------------
| DISTRIBUIÇÃO
|--------------------------------------------------------------------------
*/

$questoes = range(1, $total);

$chunks = array_chunk(
    $questoes,
    $porColuna
);

@endphp

<div class="scan-area">

<div class="marker top-left"></div>
<div class="marker top-right"></div>
<div class="marker bottom-left"></div>
<div class="marker bottom-right"></div>

<div class="gabarito">
@foreach ($chunks as $coluna)
    <div class="coluna">
        @foreach ($coluna as $i)
            <div class="linha">
                <div class="q">{{ $i }}</div>

                @for ($j = 0; $j < $prova->qtd_alternativas; $j++)
                    <div class="bubble">{{ chr(65 + $j) }}</div>
                @endfor
            </div>
        @endforeach
    </div>
@endforeach
</div>

</div>

<div class="footer">
    <img src="{{ $footer }}" style="height:18px;">
</div>

</div>

</body>
</html>