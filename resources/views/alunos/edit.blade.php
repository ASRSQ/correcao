@extends('layout')

@section('title', 'Editar Aluno')

@section('content')

<div class="container-fluid pt-3">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="mb-4">

                Editar Aluno

            </h3>

            <form action="{{ route('alunos.update', $aluno->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                {{-- REDIRECT --}}
                <input type="hidden"
                       name="redirect"
                       value="{{ request('redirect') }}">

                {{-- CONTEXTO --}}
                <input type="hidden"
                       name="cidade_id"
                       value="{{ $aluno->cidade_id }}">

                <input type="hidden"
                       name="escola_id"
                       value="{{ $aluno->escola_id }}">

                <input type="hidden"
                       name="serie_id"
                       value="{{ $aluno->serie_id }}">

                {{-- INFORMAÇÕES --}}
                <div class="alert alert-light border mb-4">

                    <div class="row">

                        <div class="col-md-4">

                            <strong>
                                Cidade
                            </strong>

                            <br>

                            {{ $aluno->cidade->nome ?? '-' }}

                        </div>

                        <div class="col-md-4">

                            <strong>
                                Escola
                            </strong>

                            <br>

                            {{ $aluno->escola->nome ?? '-' }}

                        </div>

                        <div class="col-md-4">

                            <strong>
                                Série
                            </strong>

                            <br>

                            {{ $aluno->serie->nome ?? '-' }}

                        </div>

                    </div>

                </div>

                <div class="row">

                    {{-- NOME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Nome

                        </label>

                        <input type="text"
                               name="nome"
                               value="{{ $aluno->nome }}"
                               class="form-control"
                               required>

                    </div>

                    {{-- MATRÍCULA --}}
                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Matrícula

                        </label>

                        <input type="text"
                               name="matricula"
                               value="{{ $aluno->matricula }}"
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="d-flex gap-2">

                    {{-- ATUALIZAR --}}
                    <button class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Atualizar

                    </button>

                    {{-- VOLTAR --}}
                    <button type="button"
                            onclick="window.location.href='{{ request('redirect') }}'"
                            class="btn btn-secondary">

                        <i class="bi bi-arrow-left"></i>
                        Voltar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection