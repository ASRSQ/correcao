@extends('layout')

@section('title', 'Selecionar Aluno')

@section('content')

<div class="container-fluid pt-3">

    {{-- TOPO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

        <div>

            <h3 class="mb-1">
                {{ $prova->nome }}
            </h3>

            <p class="text-muted mb-0">
                Selecione um aluno
            </p>

        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">

            <a href="{{ route('resultados.avulso.form', $prova->id) }}"
               class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Resultado Avulso

            </a>

            {{-- VOLTAR --}}
            <button onclick="history.back()"
                    class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Voltar

            </button>

        </div>

    </div>

    {{-- CARD --}}
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

                            <th>
                                Série
                            </th>

                            <th width="260">
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

                                {{ $aluno->serie->nome ?? '-' }}

                            </td>

                            <td>

                                <div class="d-flex justify-content-center gap-2 flex-wrap">

                                    {{-- PDF --}}
                                    <a href="{{ route('pdf.individual', [$prova->id, $aluno->id]) }}"
                                       class="btn btn-success btn-sm">

                                        <i class="bi bi-file-earmark-pdf"></i>

                                        PDF

                                    </a>

                                    {{-- DESEMPENHO --}}
                                    <a href="{{ route('prova.desempenho', [$prova->id, $aluno->id]) }}"
                                       class="btn btn-info btn-sm text-white">

                                        <i class="bi bi-bar-chart"></i>

                                        Desempenho

                                    </a>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4">

                                <div class="py-4">

                                    <h5 class="text-muted">

                                        Nenhum aluno encontrado

                                    </h5>

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