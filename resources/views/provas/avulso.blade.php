@extends('layout')

@section('content')

<div class="container">

    <h3 class="mb-4">➕ Resultado Avulso - {{ $prova->nome }}</h3>

    <form action="{{ route('resultados.avulso.store', $prova->id) }}" method="POST">
        @csrf

        <!-- 👤 SELEÇÃO DE ALUNO -->
        <div class="card shadow mb-3">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6">
                        <label><strong>Aluno</strong></label>
                        <select name="aluno_id" class="form-select">
                            <option value="">-- Sem aluno (avulso) --</option>

                            @foreach($alunos as $aluno)
                                <option value="{{ $aluno->id }}">
                                    {{ $aluno->nome }} - {{ $aluno->matricula }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- 📝 QUESTÕES -->
        <div class="card shadow">
            <div class="card-body">

                <h5 class="mb-3">Respostas</h5>

                <!-- ⚠️ ALERTA SE NÃO TIVER GABARITO -->
                @if($prova->gabaritos->isEmpty())
                    <div class="alert alert-danger">
                        ❌ Essa prova não possui gabarito cadastrado.
                    </div>
                @else

                    <div class="row">
                        @foreach($prova->gabaritos as $gabarito)

                            <div class="col-6 col-md-2 mb-3">
                                <div class="card text-center">
                                    <div class="card-body p-2">

                                        <small><strong>Q{{ $gabarito->questao }}</strong></small>

                                        <!-- SELECT -->
                                        <select name="respostas[{{ $gabarito->questao }}]"
                                                class="form-select form-select-sm mt-2 text-center"
                                                required>

                                            <option value="">-</option>
                                            @foreach(['A','B','C','D','E'] as $alt)
                                                <option value="{{ $alt }}">{{ $alt }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>

                @endif

                <!-- BOTÃO -->
                <div class="text-end mt-3">
                    <button class="btn btn-success">
                        💾 Salvar Resultado
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

@endsection