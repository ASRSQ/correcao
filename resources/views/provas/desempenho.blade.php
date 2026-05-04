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
                    {{ $resultado->prova->serie->nome ?? '-' }}
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
<form action="{{ route('resultados.update', $resultado->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="card shadow">
        <div class="card-body">

            <h5 class="mb-3">📝 Respostas Marcadas (Editável)</h5>

            <div class="row">
                @foreach ($respostas as $q => $resp)

                    @php
                        $gabarito = $resultado->prova->gabaritos
                            ->where('questao', $q)
                            ->first()->resposta ?? null;

                        // 🎨 Definição de cor
                        if ($resp === null || $resp === '') {
                            $cor = 'secondary'; // branco
                        } elseif ($resp === 'MULT') {
                            $cor = 'warning'; // múltipla marcação
                        } elseif ($resp == $gabarito) {
                            $cor = 'success'; // acerto
                        } else {
                            $cor = 'danger'; // erro
                        }
                    @endphp

                    <div class="col-6 col-md-2 mb-3">
                        <div class="card text-center border-{{ $cor }}">
                            <div class="card-body p-2">

                                <small>Q{{ $q }}</small>

                                <!-- SELECT EDITÁVEL -->
                                <select name="respostas[{{ $q }}]"
                                        class="form-select form-select-sm text-center mt-1 border-{{ $cor }}">

                                    <!-- Branco -->
                                    <option value="" {{ ($resp === null || $resp === '') ? 'selected' : '' }}>
                                        Branco
                                    </option>

                                    <!-- Múltipla -->
                                    <option value="MULT" {{ $resp === 'MULT' ? 'selected' : '' }}>
                                        MULT
                                    </option>

                                    <!-- Alternativas -->
                                    @foreach(['A','B','C','D','E'] as $alt)
                                        <option value="{{ $alt }}" {{ $resp == $alt ? 'selected' : '' }}>
                                            {{ $alt }}
                                        </option>
                                    @endforeach

                                </select>

                                <!-- EXIBIÇÃO DO VALOR -->
                                <div class="mt-1">
                                    @if($resp === null || $resp === '')
                                        <small class="text-muted">⭕ Branco</small>
                                    @elseif($resp === 'MULT')
                                        <small class="text-warning">⚠️ MULT</small>
                                    @else
                                        <small class="text-{{ $cor }}">{{ $resp }}</small>
                                    @endif
                                </div>

                                <!-- GABARITO -->
                                @if($resp != $gabarito && $resp !== null && $resp !== '' && $resp !== 'MULT')
                                    <small class="text-muted d-block">
                                        ✔ {{ $gabarito }}
                                    </small>
                                @endif

                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

            <!-- BOTÃO -->
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">
                    💾 Salvar alterações
                </button>
            </div>

        </div>
    </div>
</form>
</div>

@endsection