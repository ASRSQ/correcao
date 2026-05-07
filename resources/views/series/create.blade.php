@extends('layout')

@section('title', 'Nova Série')

@section('content')

<div class="container-fluid pt-3">

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <h3 class="mb-4">

                Nova Série

            </h3>

            <form action="{{ route('series.store') }}"
                  method="POST">

                @csrf

                <div class="mb-4">

                    <label class="form-label">

                        Nome

                    </label>

                    <input type="text"
                           name="nome"
                           class="form-control"
                           required>

                </div>

                <div class="d-flex gap-2">

                    <button class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>
                        Salvar

                    </button>

                    <button type="button"
                            onclick="history.back()"
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