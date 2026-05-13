@extends('layout')

@section('content')

<div class="card shadow">

    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

    <div>

        <h4 class="mb-0">
            Provas
        </h4>

        @isset($escola)

        <small class="text-muted">

            {{ $escola->nome }}

        </small>

        @endisset

        @isset($serie)

        <small class="text-muted">

            • {{ $serie->nome }}

        </small>

        @endisset

    </div>

    <div class="d-flex gap-2 mt-2 mt-md-0">

        {{-- NOVA PROVA --}}
        <a href="{{ route('provas.create', [
            'escola_id' => $escola->id ?? null,
            'serie_id' => $serie->id ?? null,
            'redirect' => url()->current()
        ]) }}"
           class="btn btn-primary">

            <i class="bi bi-plus"></i>
            Nova Prova

        </a>

        {{-- VOLTAR --}}
        @isset($escola)

        <a href="{{ route('escola.serie', [
            'escola' => $escola->id,
            'serie' => $serie->id
        ]) }}"
           class="btn btn-secondary">

            <i class="bi bi-arrow-left"></i>
            Voltar

        </a>

        @endif

    </div>

</div>

        <table class="table table-striped table-hover text-center align-middle">

            <thead class="table-dark">

                <tr>
                    <th>Nome</th>
                    <th style="width: 300px;">
                        Ações
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach ($provas as $prova)

                <tr>

                    <td>
                        {{ $prova->nome }}
                    </td>

                    <td>

                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <a href="{{ route('provas.gabarito', $prova->id) }}"
                               class="btn btn-primary btn-sm">

                                <i class="bi bi-list-check"></i>

                            </a>

                            <a href="{{ route('pdf.selecionar', $prova->id) }}"
                               class="btn btn-info btn-sm">

                                <i class="bi bi-person"></i>

                            </a>

                            <button class="btn btn-warning btn-sm"
                                    onclick="abrirModalLote({{ $prova->id }})">

                                <i class="bi bi-file-earmark-zip"></i>

                            </button>

                            <button class="btn btn-danger btn-sm"
                                    onclick="abrirModalLoteCorrecao({{ $prova->id }})">

                                <i class="bi bi-camera"></i>

                            </button>
                            <a href="{{ route('provas.classificacao', $prova->id) }}"
                                class="btn btn-secondary btn-sm">

                                    <i class="bi bi-tags"></i>

                                </a>

                            <a href="{{ route('provas.dashboard', $prova->id) }}"
                               class="btn btn-dark btn-sm">

                                <i class="bi bi-bar-chart"></i>

                            </a>
                                <!-- EXCLUIR -->
                            <form
                                action="{{ route('provas.destroy', $prova->id) }}"
                                method="POST"
                                onsubmit="return confirm('Deseja excluir esta prova?')"
                            >

                                @csrf
                                @method('DELETE')

                                <button class="btn btn-outline-danger btn-sm">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

{{-- MODAL PDF --}}
<div class="modal fade"
     id="modalLote"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalLoteLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalLoteLabel">

                    Gerando PDFs

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>

            <div class="modal-body">

                <div class="progress"
                     style="height: 25px;">

                    <div id="barraLote"
                         class="progress-bar"
                         style="width: 0%">

                        0%

                    </div>

                </div>

                <p id="statusLote"
                   class="mt-3 text-center">

                    Iniciando...

                </p>

            </div>

        </div>

    </div>

</div>

{{-- MODAL CORREÇÃO --}}
<div class="modal fade"
     id="modalCorrecaoLote"
     tabindex="-1"
     role="dialog"
     aria-labelledby="modalCorrecaoLabel"
     aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"
                    id="modalCorrecaoLabel">

                    Corrigir Provas

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close">

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>

            </div>

            <div class="modal-body">

                <input type="file"
                       id="inputImagens"
                       multiple
                       class="form-control">

                <button onclick="iniciarCorrecao()"
                        class="btn btn-success mt-3 w-100">

                    Iniciar Correção

                </button>

                <div class="progress mt-3"
                     style="height: 25px;">

                    <div id="barraCorrecao"
                         class="progress-bar"
                         style="width: 0%">

                        0%

                    </div>

                </div>

                <p id="statusCorrecao"
                   class="mt-2 text-center">

                    Aguardando envio...

                </p>

            </div>

        </div>

    </div>

</div>

@stop

@section('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const csrfMeta =
        document.querySelector('meta[name="csrf-token"]');

    const csrfToken =
        csrfMeta
        ? csrfMeta.getAttribute('content')
        : '';

    const rotaCorrigir =
        "{{ route('corrigir.lote', ':id') }}";

    const rotaLoteStep =
        "{{ route('pdf.lote.step', ['prova' => ':prova', 'index' => ':index']) }}";

    const rotaDownload =
        "{{ route('download.zip', ':prova') }}";

    let index = 0;
    let provaAtual = null;

    let imagens = [];
    let indexCorrecao = 0;
    let provaCorrecao = null;

    window.abrirModalLote = function(provaId) {

        provaAtual = provaId;
        index = 0;

        $('#modalLote').modal('show');

        processarLote();
    }

    async function processarLote() {

        try {

            const url = rotaLoteStep
                .replace(':prova', provaAtual)
                .replace(':index', index);

            const response = await fetch(url);

            const data = await response.json();

            let percent =
                Math.round((data.index / data.total) * 100);

            $('#barraLote')
                .css('width', percent + '%')
                .text(percent + '%');

            $('#statusLote')
                .text(`Gerando ${data.index} de ${data.total}`);

            if (data.finalizado) {

                $('#barraLote')
                    .css('width', '100%')
                    .text('100%');

                $('#statusLote')
                    .text('Finalizado');

                $('#modalLote').modal('hide');

                setTimeout(() => {

                    window.location.href =
                        rotaDownload.replace(':prova', provaAtual);

                }, 500);

                return;
            }

            index = data.index;

            setTimeout(() => {
                processarLote();
            }, 100);

        } catch (err) {

            console.error(err);

            $('#statusLote')
                .text('Erro ao gerar PDFs');
        }
    }

    window.abrirModalLoteCorrecao = function(provaId) {

        provaCorrecao = provaId;

        $('#modalCorrecaoLote').modal('show');
    }

    window.iniciarCorrecao = function() {

        imagens =
            document.getElementById('inputImagens').files;

        if (imagens.length === 0) {

            alert('Selecione imagens');

            return;
        }

        indexCorrecao = 0;

        processarCorrecao();
    }

    function processarCorrecao() {

        if (!imagens[indexCorrecao]) {

            $('#statusCorrecao')
                .text('Finalizado');

            $('#modalCorrecaoLote').modal('hide');

            return;
        }

        $('#statusCorrecao')
            .text(`Enviando ${indexCorrecao + 1} de ${imagens.length}`);

        let formData = new FormData();

        formData.append(
            'imagem',
            imagens[indexCorrecao]
        );

        fetch(
            rotaCorrigir.replace(':id', provaCorrecao),
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },

                credentials: 'same-origin',

                body: formData
            }
        )
        .then(res => res.json())

        .then(data => {

            if (!data.success) {

                $('#statusCorrecao')
                    .text(`Erro na imagem ${indexCorrecao + 1}`);

                return;
            }

            indexCorrecao++;

            let percent =
                Math.round((indexCorrecao / imagens.length) * 100);

            $('#barraCorrecao')
                .css('width', percent + '%')
                .text(percent + '%');

            $('#statusCorrecao')
                .text(`Processando ${indexCorrecao} de ${imagens.length}`);

            setTimeout(() => {

                processarCorrecao();

            }, 50);

        })
        .catch(err => {

            console.error(err);

            $('#statusCorrecao')
                .text('Erro');

        });
    }

});

</script>

@stop