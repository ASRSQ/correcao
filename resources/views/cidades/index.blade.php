@extends('layouts.app')

@section('content')

<div class="container">

    <a href="{{ route('cidades.create') }}"
       class="btn btn-primary mb-3">
        Nova Cidade
    </a>

    <table class="table table-bordered">

        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th width="200">Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach($cidades as $cidade)
            <tr>
                <td>{{ $cidade->id }}</td>
                <td>{{ $cidade->nome }}</td>

                <td>

                    <a href="{{ route('cidades.edit', $cidade->id) }}"
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <form action="{{ route('cidades.destroy', $cidade->id) }}"
                          method="POST"
                          style="display:inline-block">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-danger btn-sm">
                            Excluir
                        </button>

                    </form>

                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection