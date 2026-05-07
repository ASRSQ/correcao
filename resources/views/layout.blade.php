@extends('adminlte::page')

@section('title', 'Sistema de Provas')

@section('meta_tags')

<meta name="csrf-token" content="{{ csrf_token() }}">

@stop

{{-- CSS EXTRA --}}
@section('css')

{{-- BOOTSTRAP --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet">

{{-- BOOTSTRAP ICONS --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet">

{{-- CROPPER --}}
<link href="https://unpkg.com/cropperjs/dist/cropper.min.css"
      rel="stylesheet"/>

<style>

    body {
        background: #f4f6f9;
    }

    .card {
        border-radius: 12px;
    }

    .main-header.navbar {
        border-bottom: none;
    }

    .content-wrapper {
        background: #f4f6f9;
    }

</style>

@stop

{{-- HEADER --}}
@section('content_header')


@stop

{{-- CONTEÚDO --}}
@section('content')

<div class="container-fluid">

    @yield('page-content')

</div>

@stop

{{-- JS EXTRA --}}
@section('js')

{{-- BOOTSTRAP --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- CROPPER --}}
<script src="https://unpkg.com/cropperjs"></script>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@stop