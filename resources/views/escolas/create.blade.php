@extends('layout')

@section('title', $cidade?->nome ?? 'Nova Escola')

@section('content')

<div class="container-fluid pt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">

                @if($cidade)

                    Nova Escola - {{ $cidade->nome }}

                @else

                    Nova Escola

                @endif

            </h2>

            <p class="text-muted mb-0">
                Cadastro de escola
            </p>

        </div>

        @if($cidade)

        <a href="{{ route('cidade.escolas', $cidade->id) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Voltar

        </a>

        @else

        <button onclick="history.back()"
                class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Voltar

        </button>

        @endif

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form action="{{ route('escolas.store') }}"
                  method="POST">

                @csrf

                {{-- NOME --}}
                <div class="mb-3">

                    <label class="form-label">
                        Nome
                    </label>

                    <input type="text"
                           name="nome"
                           class="form-control"
                           required>

                </div>

                {{-- CIDADE TRAVADA --}}
                @if($cidade)

                    <input type="hidden"
                           name="cidade_id"
                           value="{{ $cidade->id }}">

                    <div class="mb-4">

                        <label class="form-label">
                            Cidade
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $cidade->nome }}"
                               disabled>

                    </div>

                @else

                {{-- SELECT NORMAL --}}
                <div class="mb-4">

                    <label class="form-label">
                        Cidade
                    </label>

                    <select name="cidade_id"
                            class="form-control"
                            required>

                        <option value="">
                            Selecione
                        </option>

                        @foreach($cidades as $cidade)

                        <option value="{{ $cidade->id }}">

                            {{ $cidade->nome }}

                        </option>

                        @endforeach

                    </select>

                </div>

                @endif

                <button class="btn btn-primary">

                    <i class="bi bi-check-circle"></i>
                    Salvar

                </button>

            </form>

        </div>

    </div>

</div>

@endsection