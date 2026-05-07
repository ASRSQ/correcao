@extends('layout')

@section('title', $escola->nome)

@section('content')

<div class="container-fluid pt-3">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>

            <h2 class="mb-1">

                {{ $escola->nome }}

            </h2>

            <p class="text-muted mb-0">

                Séries da escola

            </p>

        </div>

      <div class="d-flex gap-2">

    {{-- NOVA SÉRIE --}}
    <a href="{{ route('series.create', [
        'escola_id' => $escola->id
    ]) }}"
       class="btn btn-primary">

        <i class="bi bi-plus-circle"></i>
        Nova Série

    </a>

    {{-- VOLTAR --}}
    <a href="{{ route('cidade.escolas', $escola->cidade_id) }}"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>
        Voltar

    </a>

</div>

    </div>

    <div class="row">

        @forelse($series as $serie)

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-body text-center">

                    <div class="mb-3">

                        <i class="fas fa-layer-group fa-4x text-primary"></i>

                    </div>

                    <h3 class="mb-3">

                        {{ $serie->nome }}

                    </h3>

                 <div class="d-flex justify-content-center gap-2 flex-wrap">

    {{-- ENTRAR --}}
    <a href="{{ route('escola.serie', [
        'escola' => $escola->id,
        'serie' => $serie->id
    ]) }}"
       class="btn btn-primary">

        <i class="bi bi-box-arrow-in-right"></i>
        Entrar

    </a>

    {{-- EDITAR --}}
    <a href="{{ route('series.edit', $serie->id) }}"
       class="btn btn-warning">

        <i class="bi bi-pencil"></i>

    </a>

    {{-- EXCLUIR --}}
    <form action="{{ route('series.destroy', $serie->id) }}"
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

        @empty

        <div class="col-12">

            <div class="card shadow-sm border-0">

                <div class="card-body text-center py-5">

                    <h4 class="text-muted">

                        Nenhuma série encontrada

                    </h4>

                </div>

            </div>

        </div>

        @endforelse

    </div>

</div>

@endsection