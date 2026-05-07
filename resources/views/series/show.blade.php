@extends('layout')

@section('title', $serie->nome)

@section('content')

<div class="container-fluid pt-3">

    {{-- TOPO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>

            <h2 class="mb-1">

                {{ $serie->nome }}

            </h2>

            <p class="text-muted mb-0">

                Área da série

            </p>

        </div>

        <button onclick="history.back()"
                class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Voltar

        </button>

    </div>

    <div class="row">

        {{-- ALUNOS --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center py-5">

                    <div class="mb-4">

                        <i class="fas fa-user-graduate fa-4x text-primary"></i>

                    </div>

                    <h3 class="mb-3">

                        Alunos

                    </h3>

                    <p class="text-muted mb-4">

                        Gerencie os alunos da série

                    </p>

                    <a href="{{ route('serie.alunos', [
                        'escola' => $escola->id,
                        'serie' => $serie->id
                    ]) }}"
                       class="btn btn-primary btn-lg">

                        Entrar

                    </a>

                </div>

            </div>

        </div>

        {{-- PROVAS --}}
        <div class="col-lg-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center py-5">

                    <div class="mb-4">

                        <i class="fas fa-file-alt fa-4x text-success"></i>

                    </div>

                    <h3 class="mb-3">

                        Provas

                    </h3>

                    <p class="text-muted mb-4">

                        Gerencie as provas da série

                    </p>

                    <a href="{{ route('serie.provas', [
                        'escola' => $escola->id,
                        'serie' => $serie->id
                    ]) }}"
                       class="btn btn-success btn-lg">

                        Entrar

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection