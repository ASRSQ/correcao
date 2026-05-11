<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .login-header {
            background: white;
            text-align: center;
            padding: 35px 30px 20px;
        }

        .login-header h2 {
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #777;
            margin: 0;
        }

        .login-body {
            background: white;
            padding: 30px;
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

        .btn-login {
            width: 100%;
            border-radius: 12px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
        }

        .forgot-link {
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="mx-auto login-card">

            <div class="login-header">

                <h2>
                    Entrar
                </h2>

                <p>
                    Faça login para acessar o sistema
                </p>

            </div>

            <div class="login-body">

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">

                        <label
                            for="email"
                            class="form-label"
                        >
                            E-mail
                        </label>

                        <input
                            id="email"
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                        @error('email')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="mb-4">

                        <label
                            for="password"
                            class="form-label"
                        >
                            Senha
                        </label>

                        <input
                            id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                        >

                        @error('password')
                            <span class="invalid-feedback d-block">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="remember"
                            >
                                Lembrar-me
                            </label>

                        </div>

                        @if (Route::has('password.request'))

                            <a
                                class="forgot-link"
                                href="{{ route('password.request') }}"
                            >
                                Esqueceu a senha?
                            </a>

                        @endif

                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-login"
                    >
                        Entrar
                    </button>

                    <div class="text-center mt-4">

                        <span class="text-muted">
                            Não possui conta?
                        </span>

                        <a href="{{ route('register') }}">
                            Criar conta
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>

</html>