@extends('layout')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="mb-4 text-center">📝 Criar Prova</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('provas.store') }}">
                        @csrf

                        <!-- NOME -->
                        <div class="mb-3">
                            <label class="form-label">Nome da Prova</label>
                            <input type="text"
                                   name="nome"
                                   class="form-control"
                                   placeholder="Ex: Prova de Matemática"
                                   required>
                        </div>

                        <!-- ESCOLA -->
                        <div class="mb-3">
                            <label class="form-label">Escola</label>
                            <select name="escola_id" class="form-select" required>
                                <option value="">Selecione a escola</option>
                                @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}">
                                        {{ $escola->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Série</label>
                            <select name="serie_id" class="form-select" required>
                                <option value="">Selecione a série</option>
                                @foreach($series as $serie)
                                    <option value="{{ $serie->id }}">
                                        {{ $serie->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- QUESTÕES -->
                        <div class="mb-3">
                            <label class="form-label">Quantidade de Questões</label>
                            <input type="number"
                                   name="qtd_questoes"
                                   class="form-control"
                                   min="1"
                                   placeholder="Ex: 10"
                                   required>
                        </div>

                        <!-- ALTERNATIVAS -->
                        <div class="mb-3">
                            <label class="form-label">Quantidade de Alternativas</label>
                            <select name="qtd_alternativas" class="form-select" required>
                                <option value="">Selecione</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>

                        <!-- BOTÕES -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('provas.index') }}" class="btn btn-secondary">
                                ← Voltar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Criar Prova
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

@endsection