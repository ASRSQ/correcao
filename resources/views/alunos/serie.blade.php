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

                Alunos da série

            </p>

        </div>

        <div class="d-flex gap-2">

            {{-- NOVO ALUNO --}}
            <a href="{{ route('alunos.create', [
                'cidade_id' => $escola->cidade_id,
                'escola_id' => $escola->id,
                'serie_id' => $serie->id,
                'redirect' => url()->current()
            ]) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Novo Aluno

            </a>

            {{-- VOLTAR --}}
            <a href="{{ route('escola.series', $escola->id) }}"
   class="btn btn-secondary">

    <i class="bi bi-arrow-left"></i>
    Voltar

</a>
        </div>

    </div>

    {{-- TABELA --}}
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle text-center">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Nome
                            </th>

                            <th>
                                Matrícula
                            </th>

                            <th width="220">
                                Ações
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($alunos as $aluno)

                        <tr>

                            <td class="fw-semibold">

                                {{ $aluno->nome }}

                            </td>

                            <td>

                                {{ $aluno->matricula }}

                            </td>

                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    {{-- EDITAR --}}
                                    <a href="{{ route('alunos.edit', [
                                        'aluno' => $aluno->id,
                                        'redirect' => url()->current()
                                    ]) }}"
                                       class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    {{-- EXCLUIR --}}
                                    <form action="{{ route('alunos.destroy', $aluno->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden"
                                               name="redirect"
                                               value="{{ url()->current() }}">

                                        <button class="btn btn-danger btn-sm">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="3">

                                <div class="py-5">

                                    <i class="fas fa-user-graduate fa-3x text-secondary mb-3"></i>

                                    <h5 class="text-muted mb-3">

                                        Nenhum aluno encontrado

                                    </h5>

                                    <a href="{{ route('alunos.create', [
                                        'cidade_id' => $escola->cidade_id,
                                        'escola_id' => $escola->id,
                                        'serie_id' => $serie->id,
                                        'redirect' => url()->current()
                                    ]) }}"
                                       class="btn btn-primary">

                                        <i class="bi bi-plus-circle"></i>
                                        Cadastrar Primeiro Aluno

                                    </a>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection