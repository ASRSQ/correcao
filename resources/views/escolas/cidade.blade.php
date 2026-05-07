@extends('layout')

@section('title', $cidade->nome)

@section('content')

<div class="container-fluid pt-3">

    {{-- TOPO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>

            <h2 class="mb-1">

                {{ $cidade->nome }}

            </h2>

            <p class="text-muted mb-0">

                Escolas da cidade

            </p>

        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">

            {{-- NOVA ESCOLA --}}
            <a href="{{ route('escolas.create', [
                'cidade_id' => $cidade->id
            ]) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Nova Escola

            </a>

            {{-- VOLTAR --}}
            <button onclick="history.back()"
                    class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Voltar

            </button>

        </div>

    </div>

    {{-- LISTA --}}
    <div class="row">

        @forelse($escolas as $escola)

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center d-flex flex-column justify-content-center">

                    {{-- ÍCONE --}}
                    <div class="mb-3">

                        <i class="fas fa-school fa-4x text-primary"></i>

                    </div>

                    {{-- NOME --}}
                    <h3 class="mb-2">

                        {{ $escola->nome }}

                    </h3>

                    {{-- QUANTIDADE --}}
                    <p class="text-muted mb-4">

                        {{ $escola->alunos_count ?? 0 }} alunos

                    </p>

                    {{-- ENTRAR --}}
                    <a href="{{ route('escola.series', $escola->id) }}"
                       class="btn btn-primary">

                        Entrar

                    </a>

                </div>

            </div>

        </div>

        @empty

        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center py-5">

                    <div class="mb-4">

                        <i class="fas fa-school fa-4x text-secondary"></i>

                    </div>

                    <h4 class="text-muted mb-3">

                        Nenhuma escola cadastrada

                    </h4>

                    <p class="text-muted mb-4">

                        Cadastre a primeira escola desta cidade.

                    </p>

                    <a href="{{ route('escolas.create', [
                        'cidade_id' => $cidade->id
                    ]) }}"
                       class="btn btn-primary btn-lg">

                        <i class="bi bi-plus-circle"></i>
                        Cadastrar Escola

                    </a>

                </div>

            </div>

        </div>

        @endforelse

    </div>

</div>

@endsection