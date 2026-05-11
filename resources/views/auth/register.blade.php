@extends('layout')

@section('content')

<style>
    .register-page {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .register-card {
        width: 100%;
        max-width: 520px;
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 35px rgba(0,0,0,0.08);
    }

    .register-header {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        color: white;
        padding: 25px;
        text-align: center;
    }

    .register-header h3 {
        margin: 0;
        font-weight: 700;
    }

    .register-body {
        padding: 35px;
        background: white;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #dcdcdc;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    .btn-register {
        width: 100%;
        border-radius: 12px;
        padding: 12px;
        font-weight: 600;
        font-size: 16px;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 8px;
    }

    body {
        background: #f4f6f9;
    }
</style>

<div class="container register-page">
    <div class="card register-card">

        <div class="register-header">
            <h3>Criar Conta</h3>
            <p class="mb-0 mt-2">Preencha os dados para acessar o sistema</p>
        </div>

        <div class="register-body">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="form-label">
                        Nome
                    </label>

                    <input
                        id="name"
                        type="text"
                        class="form-control @error('name') is-invalid @enderror"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        autofocus
                    >

                    @error('name')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">
                        E-mail
                    </label>

                    <input
                        id="email"
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >

                    @error('email')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">
                        Senha
                    </label>

                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        name="password"
                        required
                        autocomplete="new-password"
                    >

                    @error('password')
                        <span class="invalid-feedback d-block">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password-confirm" class="form-label">
                        Confirmar Senha
                    </label>

                    <input
                        id="password-confirm"
                        type="password"
                        class="form-control"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    >
                </div>

                <button type="submit" class="btn btn-primary btn-register">
                    Criar Conta
                </button>

            </form>

        </div>
    </div>
</div>

@endsection