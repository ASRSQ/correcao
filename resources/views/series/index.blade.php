@extends('layout)

@section('title', 'Séries')

@section('content')

<div class="container-fluid pt-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="mb-1">
                Séries
            </h2>

            <p class="text-muted mb-0">
                Lista de séries
            </p>

        </div>

        <a href="{{ route('series.create') }}"
           class="btn btn-primary">

            <i class="bi bi-plus-circle"></i>
            Nova Série

        </a>

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle text-center">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Nome
                            </th>

                            <th width="220">
                                Ações
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($series as $serie)

                        <tr>

                            <td class="fw-semibold">

                                {{ $serie->nome }}

                            </td>

                            <td>

                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('series.edit', $serie->id) }}"
                                       class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form action="{{ route('series.destroy', $serie->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="2">

                                <div class="py-4">

                                    <h5 class="text-muted">

                                        Nenhuma série cadastrada

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