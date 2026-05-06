@extends('layout')

@section('content')

<div class="container">

    <form action="{{ route('cidades.store') }}"
          method="POST">

        @csrf

        <div class="mb-3">
            <label class="form-label">
                Nome
            </label>

            <input type="text"
                   name="nome"
                   class="form-control">
        </div>

        <button class="btn btn-success">
            Salvar
        </button>

    </form>

</div>

@endsection