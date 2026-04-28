<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabarito - Avaliação SIMAD</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 20px 40px;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            margin-bottom: 30px;
        
        }
        .header-text {
            text-align: center;
        }
        .header-text h3 { margin: 0 0 5px 0; font-size: 14px; }
        .header-text p { margin: 0; font-size: 12px; font-weight: bold; }
        .logo-placeholder { font-weight: bold; font-size: 14px; text-align: center; }

        /* Title */
        h1 {
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-size: 24px;
            margin-bottom: 30px;
        }

        /* Forms */
        .form-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            gap: 10px;
        }
        .input-box {
            border: 1px solid #ccc;
            padding: 8px 10px;
            position: relative;
            font-family: "Courier New", Courier, monospace;
            color: #666;
            flex-grow: 1;
        }
        .input-box.small { width: 150px; flex-grow: 0; }
        .input-label {
            position: absolute;
            top: -8px;
            left: 10px;
            background: white;
            padding: 0 5px;
            font-size: 11px;
            font-weight: bold;
            color: #000;
        }

        /* Signature Box */
        .signature-box {
            border: 2px solid #000;
            height: 40px;
            margin-top: 5px;
            margin-bottom: 20px;
        }
        .signature-label {
            font-family: "Courier New", Courier, monospace;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            margin-bottom: 5px;
        }

        /* Instructions & QR */
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
        

        /* Main Grid Area */
        .grid-wrapper {
            border: 4px solid #000;
            padding: 20px;
            display: flex;
            justify-content: space-around;
        }
        .block {
            width: 45%;
        }
        .block-title {
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .columns-container {
            display: flex;
            justify-content: space-between;
        }
        .column {
            width: 45%;
        }
        
        /* Questions */
        .question-row {
            display: flex;
            align-items: center;
            margin-bottom: 6px;
        }
        .q-number {
            width: 25px;
            text-align: right;
            margin-right: 10px;
            font-family: "Courier New", Courier, monospace;
            font-size: 14px;
        }
        
        /* Footer Logo */
        .footer {
    position: absolute;
    bottom: -170px; /* distância da borda inferior */
    left: 0;
    width: 100%;
    text-align: center;
}
        /* ÁREA DO GABARITO */
.scan-area {
    position: absolute;
    top: 80%;
    left: 50%;
    transform: translate(-50%, -40%); /* 🔥 centraliza de verdade */
    padding: 12mm;
    display: inline-block;
}

/* MARCADORES */
.marker {
    position: absolute;
    width: 15mm;
    height: 15mm;
    border-style: solid;
}

.top-left { top: 0; left: 0; border-width: 4mm 0 0 4mm; }
.top-right { top: 0; right: 0; border-width: 4mm 4mm 0 0; }
.bottom-left { bottom: 0; left: 0; border-width: 0 0 4mm 4mm; }
.bottom-right { bottom: 0; right: 0; border-width: 0 4mm 4mm 0; }

/* GABARITO CENTRAL */
.gabarito {
    display: flex;
    gap: 10mm;
    justify-content: center;   /* 🔥 centraliza colunas */
    align-items: flex-start;
}

/* COLUNA */
.coluna {
    display: flex;
    flex-direction: column;
    gap: 3mm;
}

/* LINHA */
.linha {
    display: flex;
    align-items: center;
    gap: 2mm;
}

/* NUM */
.q {
    width: 6mm;
    text-align: right;
    font-weight: bold;
    font-size: 11px;
}

/* BOLHAS */
.bubble {
    width: 5mm;
    height: 5mm;
    border: 1px solid black;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 8px;
}
    </style>
</head>
<body>

<div class="page">
<div class="header">
    <img src="{{ asset('cabecalho.png') }}" style="width: 100%; height: auto;">
</div>

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
            {{ $prova->aluno->serie }}
        </div>
    </div>

    <div class="signature-label">ASSINE CONFORME O DOCUMENTO DE IDENTIDADE:</div>
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
        $porColuna = 10;

        $questoes = range(1, $total);
        $chunks = array_chunk($questoes, $porColuna);
    @endphp
    <div class="scan-area">

        <!-- MARCADORES -->
        <div class="marker top-left"></div>
        <div class="marker top-right"></div>
        <div class="marker bottom-left"></div>
        <div class="marker bottom-right"></div>

        <!-- COLUNAS -->
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
    <img src="{{ asset('footer.png') }}" style="height: 20px;">
</div>
</div>

</body>
</html>