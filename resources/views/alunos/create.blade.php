@extends('layout')

@section('title', 'Novo Aluno')

@section('content')

<div class="container-fluid pt-3">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="mb-4">

                Novo Aluno

            </h3>

            <form action="{{ route('alunos.store') }}"
                  method="POST">

                @csrf

                {{-- REDIRECT --}}
                <input type="hidden"
                       name="redirect"
                       value="{{ request('redirect') }}">

                {{-- CONTEXTO --}}
                <input type="hidden"
                       name="cidade_id"
                       value="{{ request('cidade_id') }}">

                <input type="hidden"
                       name="escola_id"
                       value="{{ request('escola_id') }}">

                <input type="hidden"
                       name="serie_id"
                       value="{{ request('serie_id') }}">

                {{-- INFORMAÇÕES --}}
                <div class="alert alert-light border mb-4">

                    <div class="row">

                        <div class="col-md-4">

                            <strong>
                                Cidade
                            </strong>

                            <br>

                            {{ \App\Models\Cidade::find(
                                request('cidade_id')
                            )->nome ?? '-' }}

                        </div>

                        <div class="col-md-4">

                            <strong>
                                Escola
                            </strong>

                            <br>

                            {{ \App\Models\Escola::find(
                                request('escola_id')
                            )->nome ?? '-' }}

                        </div>

                        <div class="col-md-4">

                            <strong>
                                Série
                            </strong>

                            <br>

                            {{ \App\Models\Serie::find(
                                request('serie_id')
                            )->nome ?? '-' }}

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
                               class="form-control"
                               required>

                    </div>

                </div>

                <div class="d-flex gap-2">

                    {{-- SALVAR --}}
                    <button class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Salvar

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