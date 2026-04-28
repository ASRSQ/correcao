@extends('layout')

@section('content')

<div class="container">

    <!-- TÍTULO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📊 Desempenho do Aluno</h3>

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            ← Voltar
        </a>
    </div>
    </div>

    <!-- 👤 DADOS DO ALUNO -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="row">

                <div class="col-md-3">
                    <strong>Aluno:</strong><br>
                    {{ $resultado->aluno->nome ?? 'Não identificado' }}
                </div>

                <div class="col-md-3">
                    <strong>Matrícula:</strong><br>
                    {{ $resultado->aluno->matricula ?? '-' }}
                </div>

                <div class="col-md-3">
                    <strong>Escola:</strong><br>
                    {{ $resultado->aluno->escola->nome ?? '-' }}
                </div>

                <div class="col-md-3">
                    <strong>Série:</strong><br>
                    {{ $resultado->aluno->serie ?? '-' }}
                </div>

            </div>

        </div>
    </div>

    <!-- CARDS -->
    @php
        $total = ($resultado->acertos + $resultado->erros);
        $percentual = $total > 0 ? round(($resultado->acertos / $total) * 100) : 0;
    @endphp

    <div class="row text-center mb-4">

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Acertos</h6>
                    <h2 class="text-success">{{ $resultado->acertos }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Erros</h6>
                    <h2 class="text-danger">{{ $resultado->erros }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h6 class="text-muted">Aproveitamento</h6>
                    <h2 class="text-primary">{{ $percentual }}%</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- RESPOSTAS -->
    <div class="card shadow">
        <div class="card-body">

            <h5 class="mb-3">📝 Respostas Marcadas</h5>

            <div class="row">
                @foreach ($respostas as $q => $resp)

                    @php
                        $gabarito = $resultado->prova->gabaritos
                            ->where('questao', $q)
                            ->first()->resposta ?? null;

                        $cor = $resp == $gabarito ? 'success' : 'danger';
                    @endphp

                    <div class="col-6 col-md-2 mb-3">
                        <div class="card text-center border-{{ $cor }}">
                            <div class="card-body p-2">

                                <small>Q{{ $q }}</small>

                                <h5 class="mb-0 text-{{ $cor }}">
                                    {{ $resp ?? '-' }}
                                </h5>

                                @if($resp != $gabarito)
                                    <small class="text-muted">
                                        ✔ {{ $gabarito }}
                                    </small>
                                @endif

                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>
    </div>

</div>

@endsection