@extends('layout')

@section('content')

<div class="card shadow">
    <div class="mb-3 text-end">
    <a href="{{ route('resultados.avulso.form', $prova->id) }}" class="btn btn-primary">
        ➕ Cadastrar Resultado Avulso
    </a>
</div>
    <div class="card-body">

        <h4>Selecionar Aluno - {{ $prova->nome }}</h4>

        <table class="table table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th>Matrícula</th>
                    <th>Série</th>
                    <th>Ação</th>
                </tr>
            </thead>

            <tbody>
                @foreach($alunos as $aluno)
                <tr>
                    <td>{{ $aluno->nome }}</td>
                    <td>{{ $aluno->matricula }}</td>
                    <td>{{ $aluno->serie }}</td>
                    <td>
                    <a href="{{ route('pdf.individual', [$prova->id, $aluno->id]) }}"
                    class="btn btn-success btn-sm">
                        Gerar PDF
                    </a>

                    <a href="{{ route('prova.desempenho', [$prova->id, $aluno->id]) }}"
                    class="btn btn-info btn-sm">
                        Ver Desempenho
                    </a>
                </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            Voltar
        </a>

    </div>
</div>

@endsection