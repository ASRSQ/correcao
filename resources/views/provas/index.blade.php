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
                    <th style="width: 300px;">Ações</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($provas as $prova)
                <tr>
                    <td>{{ $prova->nome }}</td>

                    <td>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <!-- GABARITO -->
                            <a href="{{ route('provas.gabarito', $prova->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-list-check"></i>
                            </a>

                            <!-- ÁREA DO ALUNO -->
                            <a href="{{ route('pdf.selecionar', $prova->id) }}"
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-person"></i>
                            </a>

                            <!-- NOVO BOTÃO LOTE -->
                            <button class="btn btn-warning btn-sm"
                                    onclick="abrirModalLote({{ $prova->id }})">
                                <i class="bi bi-file-zip"></i>
                            </button>

                            <!-- CORRIGIR -->
                            <button class="btn btn-danger btn-sm"
                                    onclick="abrirModal({{ $prova->id }})">
                                <i class="bi bi-camera"></i>
                            </button>

                            <!-- DASHBOARD -->
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

<!-- MODAL CORRIGIR -->
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
                        Corrigir
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL LOTE -->
<div class="modal fade" id="modalLote" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">📦 Gerando PDFs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="progress" style="height: 25px;">
                    <div id="barraLote" class="progress-bar" style="width: 0%">
                        0%
                    </div>
                </div>

                <p id="statusLote" class="mt-3 text-center">
                    Iniciando...
                </p>

            </div>

        </div>
    </div>
</div>

<script>
const rotaCorrigir = "{{ route('provas.corrigir', ':id') }}";

// ✅ ROTAS DO LOTE (Laravel)
const rotaLoteStep = "{{ route('pdf.lote.step', ['prova' => ':prova', 'index' => ':index']) }}";
const rotaDownload = "{{ route('download.zip', ':prova') }}";

function abrirModal(id) {
    const form = document.getElementById('formCorrigir');
    form.action = rotaCorrigir.replace(':id', id);

    const modal = new bootstrap.Modal(document.getElementById('modalCorrigir'));
    modal.show();
}

/* ===== LOTE ===== */

let index = 0;
let provaAtual = null;

function abrirModalLote(provaId) {

    provaAtual = provaId;
    index = 0;

    document.getElementById('barraLote').style.width = '0%';
    document.getElementById('barraLote').innerText = '0%';
    document.getElementById('statusLote').innerText = 'Iniciando...';

    const modal = new bootstrap.Modal(document.getElementById('modalLote'));
    modal.show();

    processarLote();
}

function processarLote() {

    const url = rotaLoteStep
        .replace(':prova', provaAtual)
        .replace(':index', index);

    fetch(url)
        .then(r => r.json())
        .then(data => {

            if (data.finalizado) {

                document.getElementById('barraLote').style.width = '100%';
                document.getElementById('barraLote').innerText = '100%';
                document.getElementById('statusLote').innerText = '✅ Finalizado! Baixando...';

                window.location.href = rotaDownload.replace(':prova', provaAtual);
                return;
            }

            index = data.index;

            let percent = Math.round((index / data.total) * 100);

            document.getElementById('barraLote').style.width = percent + '%';
            document.getElementById('barraLote').innerText = percent + '%';

            document.getElementById('statusLote').innerText =
                `Processando ${index} de ${data.total}`;

            processarLote();
        })
        .catch(() => {
            document.getElementById('statusLote').innerText = "❌ Erro ao processar...";
        });
}
</script>

@endsection