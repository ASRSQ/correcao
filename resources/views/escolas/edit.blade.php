@extends('layout')

@section('title', 'Editar Escola')

@section('content')

<div class="container-fluid pt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">
                Editar Escola
            </h2>

            <p class="text-muted mb-0">

                {{ $escola->nome }}

            </p>

        </div>

        <button onclick="history.back()"
                class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Voltar

        </button>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form action="{{ route('escolas.update', $escola->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Nome
                    </label>

                    <input type="text"
                           name="nome"
                           class="form-control"
                           value="{{ $escola->nome }}"
                           required>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Cidade
                    </label>

                    <select name="cidade_id"
                            class="form-control"
                            required>

                        @foreach($cidades as $cidade)

                        <option value="{{ $cidade->id }}"
                            @selected($cidade->id == $escola->cidade_id)>

                            {{ $cidade->nome }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <button class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    Atualizar

                </button>

            </form>

        </div>

    </div>

</div>

@endsection