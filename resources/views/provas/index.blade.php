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

                        <!-- PDF LOTE -->
                        <button class="btn btn-warning btn-sm"
                                onclick="abrirModalLote({{ $prova->id }})">
                            <i class="bi bi-file-zip"></i>
                        </button>

                        <!-- CORREÇÃO LOTE -->
                        <button class="btn btn-danger btn-sm"
                                onclick="abrirModalLoteCorrecao({{ $prova->id }})">
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

<!-- ========================= -->

<!-- 📦 MODAL PDF LOTE -->

<!-- ========================= -->

<div class="modal fade" id="modalLote" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">


        <div class="modal-header">
            <h5 class="modal-title">📦 Gerando PDFs</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <div class="progress" style="height: 25px;">
                <div id="barraLote" class="progress-bar" style="width: 0%">0%</div>
            </div>

            <p id="statusLote" class="mt-3 text-center">Iniciando...</p>

        </div>

    </div>
</div>


</div>

<!-- ========================= -->

<!-- 📸 MODAL CORREÇÃO -->

<!-- ========================= -->

<div class="modal fade" id="modalCorrecaoLote" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">


        <div class="modal-header">
            <h5 class="modal-title">📸 Corrigir Provas</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

            <input type="file" id="inputImagens" multiple class="form-control">

            <button onclick="iniciarCorrecao()" class="btn btn-success mt-3 w-100">
                Iniciar Correção
            </button>

            <div class="progress mt-3" style="height: 25px;">
                <div id="barraCorrecao" class="progress-bar" style="width: 0%">0%</div>
            </div>

            <p id="statusCorrecao" class="mt-2 text-center">
                Aguardando envio...
            </p>

        </div>

    </div>
</div>


</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    const rotaCorrigir = "{{ route('corrigir.lote', ':id') }}";
    const rotaLoteStep = "{{ route('pdf.lote.step', ['prova' => ':prova', 'index' => ':index']) }}";
    const rotaDownload = "{{ route('download.zip', ':prova') }}";

    var index = 0;
    var provaAtual = null;

    var imagens = [];
    var indexCorrecao = 0;
    var provaCorrecao = null;

    window.abrirModalLote = function(provaId) {
        provaAtual = provaId;
        index = 0;

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

            // 🔥 FINALIZOU
            if (data.finalizado) {

                document.getElementById('barraLote').style.width = '100%';
                document.getElementById('barraLote').innerText = '100%';

                document.getElementById('statusLote').innerText =
                    '✅ Finalizado!';

                window.location.href =
                    rotaDownload.replace(':prova', provaAtual);

                return;
            }

            // 🔥 progresso
            index = data.index;

            let percent = Math.round((index / data.total) * 100);

            document.getElementById('barraLote').style.width =
                percent + '%';

            document.getElementById('barraLote').innerText =
                percent + '%';

            document.getElementById('statusLote').innerText =
                `Gerando ${index} de ${data.total}`;

            // 🔥 pequeno delay
            setTimeout(() => {
                processarLote();
            }, 50);
        })
        .catch(err => {

            console.error(err);

            document.getElementById('statusLote').innerText =
                '❌ Erro ao gerar PDFs';
        });
}

    window.abrirModalLoteCorrecao = function(provaId) {
        provaCorrecao = provaId;

        const modal = new bootstrap.Modal(document.getElementById('modalCorrecaoLote'));
        modal.show();
    }

    window.iniciarCorrecao = function() {

        imagens = document.getElementById('inputImagens').files;

        if (imagens.length === 0) {
            alert('Selecione imagens');
            return;
        }

        indexCorrecao = 0;
        processarCorrecao();
    }

    function processarCorrecao() {

    if (!imagens[indexCorrecao]) {
        document.getElementById('statusCorrecao').innerText = "✅ Finalizado!";
        return;
    }

    // 🔥 ATUALIZA STATUS ANTES
    document.getElementById('statusCorrecao').innerText =
        `Enviando ${indexCorrecao + 1} de ${imagens.length}...`;

    let formData = new FormData();
    formData.append('imagem', imagens[indexCorrecao]);

    fetch(rotaCorrigir.replace(':id', provaCorrecao), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin',
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        console.log('RESPOSTA:', data); // 🔥 debug

        if (!data.success) {
            document.getElementById('statusCorrecao').innerText =
                `❌ Erro na imagem ${indexCorrecao + 1}`;
            return;
        }

        indexCorrecao++;

        let percent = Math.round((indexCorrecao / imagens.length) * 100);

        document.getElementById('barraCorrecao').style.width = percent + '%';
        document.getElementById('barraCorrecao').innerText = percent + '%';

        document.getElementById('statusCorrecao').innerText =
            `Processando ${indexCorrecao} de ${imagens.length}`;

        // 🔥 IMPORTANTE: pequeno delay evita travar UI
        setTimeout(() => {
            processarCorrecao();
        }, 50);

    })
    .catch(err => {
        console.error(err);
        document.getElementById('statusCorrecao').innerText = "❌ Erro...";
    });
}

});

</script>

@endsection
