@extends('layout')

@section('content')

<div class="container">

    <div class="card shadow">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">📝 Gabarito - {{ $prova->nome }}</h4>

                <a href="{{ route('provas.index') }}" class="btn btn-secondary">
                    ← Voltar
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('provas.salvarGabarito', $prova->id) }}">
                @csrf

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Questão</th>
                                <th>Resposta</th>
                            </tr>
                        </thead>

                        <tbody>
                            @for ($i = 1; $i <= $prova->qtd_questoes; $i++)

                                @php
                                    $respostaAtual = $prova->gabaritos
                                        ->where('questao', $i)
                                        ->first()->resposta ?? null;
                                @endphp

                                <tr>
                                    <td><strong>Q{{ $i }}</strong></td>

                                    <td>
                                        <select name="gabarito[{{ $i }}]" class="form-select" required>

                                            <option value="">-- Selecione --</option>

                                            @for ($j = 0; $j < $prova->qtd_alternativas; $j++)
                                                @php $letra = chr(65 + $j); @endphp

                                                <option value="{{ $letra }}"
                                                    {{ $respostaAtual == $letra ? 'selected' : '' }}>
                                                    {{ $letra }}
                                                </option>
                                            @endfor

                                        </select>
                                    </td>
                                </tr>

                            @endfor
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check"></i> Salvar Gabarito
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection