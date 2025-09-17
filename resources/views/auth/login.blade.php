<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    {{-- Simples estilização com Bootstrap para o formulário parecer bom --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login de Colaborador</div>
                    <div class="card-body">

                        <form method="POST" action="{{ route('login') }}">
                            @csrf {{-- Proteção CSRF, essencial em formulários Laravel --}}

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email_colaborador" id="email" class="form-control @error('email_colaborador') is-invalid @enderror" value="{{ old('email_colaborador') }}" required autofocus>

                                @error('email_colaborador')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" name="senha_colaborador" id="password" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>