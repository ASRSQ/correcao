<!DOCTYPE html>
<html lang="pt-br">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
    background: #f5f7fb;
    color: #1e293b;
    font-family: Inter, Arial, sans-serif;
}

.topbar{
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}

.card-dashboard{
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    height: 100%;
}

.card-info{
    text-align: center;
}

.card-info .icone{
    font-size: 35px;
    margin-bottom: 10px;
    color: #2563eb;
}

.card-info .numero{
    font-size: 42px;
    font-weight: bold;
}

.card-info .titulo{
    color: #64748b;
    font-size: 15px;
}

.titulo-pagina{
    font-size: 32px;
    font-weight: 700;
}

.subtitulo{
    color: #64748b;
}

canvas{
    max-height: 500px;
}

.table-ranking{
    border-radius: 15px;
    overflow: hidden;
}

.table-ranking thead{
    background: #2563eb;
    color: white;
}

.badge-top{
    background: #16a34a;
    padding: 6px 10px;
    border-radius: 8px;
    color: white;
    font-size: 13px;
}

.info-extra{
    font-size: 15px;
    color: #475569;
    margin-top: 10px;
}

</style>

</head>

<body>

<div class="container py-4">

    <!-- TOPO -->
    <div class="topbar">

        <div class="d-flex justify-content-between align-items-center flex-wrap">

            <div>

                <div class="titulo-pagina">
                    Dashboard de Desempenho
                </div>

                <div class="subtitulo">
                    Análise pedagógica da prova
                </div>

            </div>
@auth

<div class="d-flex gap-2">

    <a href="{{ route('provas.index') }}"
       class="btn btn-outline-primary">

        <i class="bi bi-arrow-left"></i>
        Voltar

    </a>

    <button
        class="btn btn-outline-success"
        onclick="copiarLink()">

        <i class="bi bi-share"></i>
        Compartilhar

    </button>

</div>

@endauth
        </div>

    </div>

    <!-- CARDS -->
    <div class="row mb-4">

        <div class="col-md-3 mb-3">

            <div class="card-dashboard card-info">

                <div class="icone">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div class="numero">
                    {{ $geral['acertos'] }}
                </div>

                <div class="titulo">
                    Total de Acertos
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card-dashboard card-info">

                <div class="icone">
                    <i class="bi bi-x-circle"></i>
                </div>

                <div class="numero">
                    {{ $geral['erros'] }}
                </div>

                <div class="titulo">
                    Total de Erros
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card-dashboard card-info">

                <div class="icone">
                    <i class="bi bi-people"></i>
                </div>

                <div class="numero">
                    {{ count($ranking) }}
                </div>

                <div class="titulo">
                    Participantes
                </div>

            </div>

        </div>

        <div class="col-md-3 mb-3">

            <div class="card-dashboard card-info">

                <div class="icone">
                    <i class="bi bi-bar-chart"></i>
                </div>

                <div class="numero">
                    {{ $mediaTurma }}
                </div>

                <div class="titulo">
                    Média da Turma
                </div>

            </div>

        </div>

    </div>

    <!-- QUESTÕES -->
    <div class="row">

        <div class="col-md-12 mb-4">

            <div class="card-dashboard">

                <h4 class="mb-4">
                    Acertos por Questão
                </h4>

                <canvas id="graficoQuestoes"></canvas>

            </div>

        </div>

    </div>

    <!-- ALUNO X CATEGORIA -->
    <div class="row">

        <div class="col-md-12 mb-4">

            <div class="card-dashboard">

                <h4 class="mb-4">
                    Acertos por Categoria por Aluno
                </h4>

                <canvas id="graficoAlunoCategoria"></canvas>

            </div>

        </div>

    </div>

    <!-- CATEGORIA / SUBCATEGORIA -->
    <div class="row">

        <div class="col-md-6 mb-4">

            <div class="card-dashboard">

                <h4 class="mb-4">
                    Desempenho por Categoria
                </h4>

                <canvas id="graficoCategoria"></canvas>

            </div>

        </div>

        <div class="col-md-6 mb-4">

            <div class="card-dashboard">

                <h4 class="mb-4">
                    Desempenho por Subcategoria
                </h4>

                <canvas id="graficoSubcategoria"></canvas>

            </div>

        </div>

    </div>

    <!-- RANKING -->
   <div class="card-dashboard mt-3">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <h4 class="mb-0">
            Ranking de Alunos
        </h4>

        <span class="badge-top">
            {{ count($ranking) }} participantes
        </span>

    </div>

    <div class="table-responsive">

        <table class="table table-hover align-middle table-ranking">

            <thead>

                <tr>
                    <th>#</th>
                    <th>Aluno</th>
                    <th>Acertos</th>
                    <th>Erros</th>
                    <th>Percentual</th>
                </tr>

            </thead>

            <tbody>

                @foreach($ranking as $index => $aluno)

                    <!-- LINHA PRINCIPAL -->
                    <tr
                        data-bs-toggle="collapse"
                        data-bs-target="#detalhesAluno{{ $index }}"
                        style="cursor:pointer;"
                    >

                        <td>
                            <strong>
                                {{ $index + 1 }}
                            </strong>
                        </td>

                        <td>

                            <div class="d-flex align-items-center gap-2">

                                <i class="bi bi-chevron-down"></i>

                                {{ $aluno['nome'] }}

                            </div>

                        </td>

                        <td>

                            <span class="text-success fw-bold">
                                {{ $aluno['acertos'] }}
                            </span>

                        </td>

                        <td>

                            <span class="text-danger fw-bold">
                                {{ $aluno['erros'] }}
                            </span>

                        </td>

                        <td width="250">

                            <div class="progress">

                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: {{ $aluno['percentual'] }}%">

                                    {{ $aluno['percentual'] }}%

                                </div>

                            </div>

                        </td>

                    </tr>

                   <!-- DETALHES -->
<tr class="collapse bg-light"
    id="detalhesAluno{{ $index }}">

    <td colspan="5">

        <div class="p-3">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h6 class="mb-0">
                    Questões do aluno
                </h6>

                <small class="text-muted">
                    Clique novamente para fechar
                </small>

            </div>

            <div class="d-flex flex-wrap gap-2">

    @php

        $questoesAluno = [];

        foreach (
            $detalhesAlunos[$aluno['nome']]['acertos']
            as $acerto
        ) {

            $questoesAluno[] = [

                'questao' =>
                    $acerto['questao'],

                'categoria' =>
                    $acerto['categoria'],

                'subcategoria' =>
                    $acerto['subcategoria'],

                'tipo' => 'acerto'
            ];
        }

        foreach (
            $detalhesAlunos[$aluno['nome']]['erros']
            as $erro
        ) {

            $questoesAluno[] = [

                'questao' =>
                    $erro['questao'],

                'categoria' =>
                    $erro['categoria'],

                'subcategoria' =>
                    $erro['subcategoria'],

                'tipo' => 'erro'
            ];
        }

        $questoesAluno =
            collect($questoesAluno)
                ->sortBy('questao');

    @endphp

    @foreach($questoesAluno as $questao)

        <div
            class="rounded-circle d-flex flex-column justify-content-center align-items-center bg-white"

            style="
                width:70px;
                height:70px;
                font-size:12px;

                border:2px solid
                {{
                    $questao['tipo'] == 'acerto'
                    ? '#16a34a'
                    : '#dc2626'
                }};

                color:
                {{
                    $questao['tipo'] == 'acerto'
                    ? '#16a34a'
                    : '#dc2626'
                }};
            "

            title="
                {{ $questao['categoria'] }}
                -
                {{ $questao['subcategoria'] }}
            "
        >

            <div class="fw-bold">

                Q{{ $questao['questao'] }}

            </div>

            <div style="font-size:18px;">

                {{
                    $questao['tipo'] == 'acerto'
                    ? '●'
                    : '✕'
                }}

            </div>

        </div>

    @endforeach

</div>
            <!-- LEGENDA -->
            <div class="mt-4 d-flex gap-4 flex-wrap">

                <div class="d-flex align-items-center gap-2">

                    <div
                        class="rounded-circle bg-success"
                        style="
                            width:14px;
                            height:14px;
                        "
                    ></div>

                    <small>
                        Acertou
                    </small>

                </div>

                <div class="d-flex align-items-center gap-2">

                    <div
                        class="rounded-circle bg-danger"
                        style="
                            width:14px;
                            height:14px;
                        "
                    ></div>

                    <small>
                        Errou
                    </small>

                </div>

            </div>

        </div>

    </td>

</tr>
                @endforeach

            </tbody>

        </table>

    </div>

</div>

<script>

const labelsQuestoes =
    @json($labelsQuestoes);

const acertosQuestoes =
    @json($acertosQuestoes);

const categorias =
    @json($categorias);

const subcategorias =
    @json($subcategorias);

const desempenhoCategorias =
    @json($desempenhoCategorias);

// ========================================
// GRÁFICO QUESTÕES
// ========================================

new Chart(
    document.getElementById(
        'graficoQuestoes'
    ),
    {

        type: 'bar',

        data: {

            labels: labelsQuestoes,

            datasets: [{

                label: 'Acertos',

                data: acertosQuestoes,

                backgroundColor: '#2563eb',

                borderRadius: 8
            }]
        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    display: false
                }
            },

            scales: {

                x: {

                    ticks: {

                        maxRotation: 90,
                        minRotation: 90
                    }
                },

                y: {

                    beginAtZero: true
                }
            }
        }
    }
);

// ========================================
// GRÁFICO ALUNO X CATEGORIA
// ========================================

const alunos =
    Object.keys(desempenhoCategorias);

const categoriasNomes =
    [...new Set(

        Object.values(desempenhoCategorias)

            .flatMap(
                item => Object.keys(item)
            )
    )];

const cores = [

    '#2563eb',
    '#16a34a',
    '#dc2626',
    '#ca8a04',
    '#7c3aed',
    '#0891b2',
    '#ea580c',
    '#be123c'
];

const datasetsCategorias =
    categoriasNomes.map(
        (categoria, index) => {

            return {

                label: categoria,

                data:

                    alunos.map(
                        aluno =>

                        desempenhoCategorias
                        [aluno]
                        [categoria] ?? 0
                    ),

                backgroundColor:

                    cores[
                        index % cores.length
                    ],

                borderRadius: 8,

                borderWidth: 1
            };
        }
    );

new Chart(

    document.getElementById(
        'graficoAlunoCategoria'
    ),

    {

        type: 'bar',

        data: {

            labels: alunos,

            datasets: datasetsCategorias
        },

        options: {

            responsive: true,

            plugins: {

                legend: {

                    position: 'top',

                    labels: {

                        boxWidth: 20,
                        padding: 20
                    }
                }
            },

            scales: {

                x: {

                    ticks: {

                        maxRotation: 45,
                        minRotation: 45
                    },

                    grid: {

                        display: false
                    }
                },

                y: {

                    beginAtZero: true,

                    ticks: {

                        stepSize: 1
                    }
                }
            }
        }
    }
);

// ========================================
// CATEGORIAS
// ========================================

new Chart(
    document.getElementById(
        'graficoCategoria'
    ),
    {

        type: 'doughnut',

        data: {

            labels: Object.keys(categorias),

            datasets: [{

                data:
                    Object.values(categorias)
            }]
        }
    }
);

// ========================================
// SUBCATEGORIAS
// ========================================

new Chart(
    document.getElementById(
        'graficoSubcategoria'
    ),
    {

        type: 'bar',

        data: {

            labels:
                Object.keys(subcategorias),

            datasets: [{

                label: 'Acertos',

                data:
                    Object.values(subcategorias),

                backgroundColor: '#2563eb',

                borderRadius: 8
            }]
        },

        options: {

            indexAxis: 'y',

            responsive: true,

            scales: {

                x: {

                    beginAtZero: true
                }
            }
        }
    }
);

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>