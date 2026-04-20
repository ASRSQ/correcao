<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>
@page {
    margin: 40px;
}

body {
    font-family: Arial, sans-serif;
}

/* BORDA PRINCIPAL */
.page {
    border: 2px solid black;
    padding: 20px;
}

/* HEADER */
.header {
    text-align: center;
    font-size: 14px;
    margin-bottom: 10px;
}

/* INFO */
.info {
    font-size: 12px;
    margin-bottom: 5px;
}

.line {
    border-bottom: 1px solid black;
    width: 200px;
    display: inline-block;
}

/* COLUNAS */
.layout-table {
    width: 100%;
    margin-top: 20px;
    table-layout: fixed;   /* 🔥 ESSENCIAL */
    border-collapse: collapse;
}

.layout-col {
    width: 50%;
    vertical-align: top;
}

/* BLOCO INTERNO */
.sheet {
    position: relative;
    padding: 60px 25px 25px 25px;
    min-height: 580px;

}

/* MARCADORES */
.marker {
    position: absolute;
    width: 50px;
    height: 50px;
    border-style: solid;
}

/* TOPO */
.top-left {
    top: 10px;
    left: 10px;
    border-width: 12px 0 0 12px;
}

.top-right {
    top: 10px;
    right: 10px;
    border-width: 12px 12px 0 0;
}

/* BASE (AJUSTE PRINCIPAL AQUI) */
.bottom-left {
    top: 400px; /* 🔥 posição fixa confiável */
    left: 10px;
    border-width: 0 0 12px 12px;
}

.bottom-right {
    top: 400px; /* 🔥 posição fixa confiável */
    right: 10px;
    border-width: 0 12px 12px 0;
}

/* QUESTÕES */
.questions-table {
    width: auto;
    margin: 0 auto;
    transform: translateX(10px); /* 🔥 empurra pra direita */
}

.questions-table td {
    padding-bottom: 14px;
}

/* NUM */
.q-num {
    width: 30px;
    font-weight: bold;
}

/* BOLHAS */
.bubble {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 1.5px solid black;
    border-radius: 50%;
    margin-right: 8px;
    text-align: center;
    line-height: 18px;
    font-size: 11px;
}
</style>
</head>

<body>

<div class="page">

    <div class="header">
        Marque preenchendo completamente a bolha correta.
    </div>

    <div class="info">Nome: <span class="line"></span></div>
    <div class="info">Turma: <span class="line"></span></div>
    <div class="info">Data: <span class="line"></span></div>

    @php
        $total = $prova->qtd_questoes;
        $metade = ceil($total / 2);
    @endphp

    <table class="layout-table">
        <tr>

            <!-- COLUNA 1 -->
            <td class="layout-col">
                <div class="sheet">

                    <div class="marker top-left"></div>
                    <div class="marker top-right"></div>
                    <div class="marker bottom-left"></div>
                    <div class="marker bottom-right"></div>

                    <table class="questions-table">
                        @for ($i = 1; $i <= $metade; $i++)
                        <tr>
                            <td class="q-num">{{ $i }}</td>
                            <td>
                                @for ($j = 0; $j < $prova->qtd_alternativas; $j++)
                                    <span class="bubble">{{ chr(65 + $j) }}</span>
                                @endfor
                            </td>
                        </tr>
                        @endfor
                    </table>

                </div>
            </td>

            <!-- COLUNA 2 -->
            <td class="layout-col">
                <div class="sheet">

                    <div class="marker top-left"></div>
                    <div class="marker top-right"></div>
                    <div class="marker bottom-left"></div>
                    <div class="marker bottom-right"></div>

                    <table class="questions-table">
                        @for ($i = $metade + 1; $i <= $total; $i++)
                        <tr>
                            <td class="q-num">{{ $i }}</td>
                            <td>
                                @for ($j = 0; $j < $prova->qtd_alternativas; $j++)
                                    <span class="bubble">{{ chr(65 + $j) }}</span>
                                @endfor
                            </td>
                        </tr>
                        @endfor
                    </table>

                </div>
            </td>

        </tr>
    </table>

</div>

</body>
</html>