<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Meu Painel</a>

            {{-- Formulário de Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf {{-- Proteção CSRF obrigatória! --}}
                <button type="submit" class="btn btn-outline-light">Sair</button>
            </form>

        </div>
    </nav>

    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1>
                    {{-- A função Auth::user() pega os dados do usuário logado --}}
                    Bem-vindo(a), {{ Auth::user()->nome_colaborador }}!
                </h1>
                <p>Você está em uma página protegida. Somente usuários autenticados podem ver isso.</p>
            </div>
        </div>
    </div>

</body>

</html>