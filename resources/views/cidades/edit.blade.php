@extends('layout')

@section('content')

<div class="container">

    <form action="{{ route('cidades.update', $cidade->id) }}"
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
                   value="{{ $cidade->nome }}">

        </div>

        <button class="btn btn-primary">
            Atualizar
        </button>

    </form>

</div>

@endsection