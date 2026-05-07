@extends('layout')

@section('title', 'Cidades')

@section('content')

<div class="container-fluid pt-3">

    {{-- TOPO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">
            Cidades
        </h2>

        <a href="{{ route('cidades.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Nova Cidade

        </a>

    </div>

    {{-- SEM CIDADES --}}
    @if($cidades->isEmpty())

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <div class="mb-4">

                    <i class="fas fa-city fa-4x text-secondary"></i>

                </div>

                <h3 class="mb-3">
                    Nenhuma cidade cadastrada
                </h3>

                <p class="text-muted mb-4">
                    Cadastre a primeira cidade para começar a usar o sistema.
                </p>

                <a href="{{ route('cidades.create') }}"
                   class="btn btn-primary btn-lg">

                    <i class="bi bi-plus-circle"></i>
                    Cadastrar Cidade

                </a>

            </div>

        </div>

    @else

    {{-- LISTA --}}
    <div class="row">

        @foreach($cidades as $cidade)

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card shadow-sm h-100 border-0">

                <div class="card-body text-center d-flex flex-column justify-content-center">

                    <div class="mb-3">

                        <i class="fas fa-city fa-4x text-primary"></i>

                    </div>

                    <h3 class="mb-2">

                        {{ $cidade->nome }}

                    </h3>

                    <p class="text-muted mb-4">

                        {{ $cidade->escolas_count }} escolas

                    </p>

              <div class="d-flex justify-content-center gap-2 flex-wrap">

    {{-- ENTRAR --}}
    <a href="{{ route('cidade.escolas', $cidade->id) }}"
       class="btn btn-primary">

        <i class="bi bi-box-arrow-in-right"></i>
        Entrar

    </a>

    {{-- EDITAR --}}
    <a href="{{ route('cidades.edit', $cidade->id) }}"
       class="btn btn-warning">

        <i class="bi bi-pencil"></i>

    </a>

    {{-- EXCLUIR --}}
    <form action="{{ route('cidades.destroy', $cidade->id) }}"
          method="POST">

        @csrf
        @method('DELETE')

        <button class="btn btn-danger">

            <i class="bi bi-trash"></i>

        </button>

    </form>

</div>
                </div>

            </div>

        </div>

        @endforeach

    </div>

    @endif

</div>

@endsection