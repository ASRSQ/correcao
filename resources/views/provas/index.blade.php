@extends('layout')

@section('content')

<div class="card shadow">
    <div class="card-body">

        <!-- TOPO -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📄 Provas</h4>

            <a href="{{ route('provas.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Nova Prova
            </a>
        </div>

        <!-- TABELA -->
        <table class="table table-striped table-hover text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nome</th>
                    <th style="width: 250px;">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($provas as $prova)
                <tr>
                    <td>{{ $prova->nome }}</td>

                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a href="{{ route('provas.gabarito', $prova->id) }}"
                               class="btn btn-primary btn-sm"
                               title="Gabarito">
                                <i class="bi bi-list-check"></i>
                            </a>

                            <a href="{{ route('provas.pdf', $prova->id) }}"
                               class="btn btn-success btn-sm"
                               title="PDF">
                                <i class="bi bi-file-earmark-pdf"></i>
                            </a>

                            <button class="btn btn-danger btn-sm"
                                    onclick="abrirModal({{ $prova->id }})"
                                    title="Corrigir">
                                <i class="bi bi-camera"></i>
                            </button>

                            <a href="{{ route('provas.dashboard', $prova->id) }}"
                                class="btn btn-dark btn-sm">
                                    <i class="bi bi-bar-chart"></i>
                                </a>


                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<!-- MODAL BOOTSTRAP -->
<div class="modal fade" id="modalCorrigir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">📸 Enviar imagem da prova</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formCorrigir" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <input type="file" name="imagem" class="form-control" accept="image/*" required>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check"></i> Corrigir
                    </button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
const rotaCorrigir = "{{ route('provas.corrigir', ':id') }}";

function abrirModal(id) {
    const form = document.getElementById('formCorrigir');

    form.action = rotaCorrigir.replace(':id', id);

    const modal = new bootstrap.Modal(document.getElementById('modalCorrigir'));
    modal.show();
}
</script>

@endsection