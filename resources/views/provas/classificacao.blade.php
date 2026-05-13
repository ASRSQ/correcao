@extends('layout')

@section('content')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">

    <h4 class="mb-0">

        {{ $prova->nome }}

    </h4>

  <a href="{{ route('serie.provas', [
        'escola' => $prova->escola_id,
        'serie' => $prova->serie_id
    ]) }}"
   class="btn btn-secondary">

    <i class="bi bi-arrow-left"></i>

    Voltar

</a>

</div>

            <h4 class="mb-4">

            </h4>

            <div class="row">

                {{-- CATEGORIAS --}}
                <div class="col-md-4">

                    <div class="card border">

                        <div class="card-body">

                            <h5>

                                Categorias

                            </h5>

                            <form method="POST"
                                  action="{{ route('provas.categoria', $prova->id) }}">

                                @csrf

                                <div class="input-group">

                                    <input type="text"
                                           name="nome"
                                           class="form-control">

                                    <button class="btn btn-primary">

                                        +

                                    </button>

                                </div>

                            </form>

                            <hr>

                            @foreach($categories as $category)

                            <div class="mb-2">

                                {{ $category->nome }}

                            </div>

                            @endforeach

                        </div>

                    </div>

                </div>

                {{-- SUBCATEGORIAS --}}
                <div class="col-md-4">

                    <div class="card border">

                        <div class="card-body">

                            <h5>

                                Habilidades

                            </h5>

                            <form method="POST"
                                  action="{{ route('provas.subcategoria', $prova->id) }}">

                                @csrf

                                <input type="text"
                                       name="nome"
                                       class="form-control mb-2">

                                <select name="category_id"
                                        class="form-control mb-2">

                                    @foreach($categories as $category)

                                    <option value="{{ $category->id }}">

                                        {{ $category->nome }}

                                    </option>

                                    @endforeach

                                </select>

                                <button class="btn btn-success w-100">

                                    Adicionar

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

                {{-- QUESTÕES --}}
                <div class="col-md-4">

                    <div class="card border">

                        <div class="card-body">

                            <h5>

                                Classificar Questões

                            </h5>

                            <form method="POST"
                                  action="{{ route('provas.salvar.classificacao', $prova->id) }}">

                                @csrf

                                @for($i = 1; $i <= $prova->qtd_questoes; $i++)

                                <div class="mb-2">

                                    <label>

                                        Questão {{ $i }}

                                    </label>

                                    <select name="questoes[{{ $i }}]"
                                            class="form-control">

                                        @foreach($categories as $category)

                                            @foreach($category->subcategories as $sub)

                                            <option
                                                value="{{ $sub->id }}"

                                                @if(
                                                    isset($questoes[$i]) &&
                                                    $questoes[$i]->subcategory_id == $sub->id
                                                )
                                                    selected
                                                @endif
                                            >

                                                {{ $category->nome }}
                                                -
                                                {{ $sub->nome }}

                                            </option>

                                            @endforeach

                                        @endforeach

                                    </select>

                                </div>

                                @endfor

                                <button class="btn btn-dark w-100">

                                    Salvar

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection