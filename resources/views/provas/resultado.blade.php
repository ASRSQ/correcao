@extends('layout')

@section('content')

<div class="container">

    <!-- TÍTULO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">📊 Resultado da Correção</h3>

        <a href="{{ route('provas.index') }}" class="btn btn-secondary">
            ← Voltar
        </a>
    </div>

    <!-- CARDS -->
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
                    <h2 class="text-primary">
                        {{ round(($resultado->acertos / ($resultado->acertos + $resultado->erros)) * 100) }}%
                    </h2>
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