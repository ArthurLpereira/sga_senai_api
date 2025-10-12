<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Planilha Semestral SGA</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f0f0;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #d82b3a;
            color: #fff;
            padding: 15px 30px;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .senai-logo {
            font-size: 24px;
            font-weight: 700;
            padding: 5px 15px;
            background-color: #fff;
            color: #d82b3a;
            border-radius: 5px;
        }

        .month-header {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin: 30px 30px 15px 30px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }

        .table-container {
            margin: 0 30px;
            background-color: #3e3e3e;
            padding: 2px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 6px;
            overflow: hidden;
        }

        th {
            background-color: #d82b3a;
            color: #fff;
            font-weight: 700;
            text-align: left;
            padding: 15px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        th:last-child {
            border-right: none;
        }

        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
            font-weight: 400;
        }

        td:last-child {
            border-right: none;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <header>
        <h1>Planilha Semestral SGA</h1>
        <div class="senai-logo">SENAI</div>
    </header>

    <h2 class="month-header">Janeiro</h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Capacidade</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Curso</th>
                    <th>Ambiente</th>
                    <th>Status</th>
                    <th>Tempo Aula</th>
                    <th>Turno</th>
                </tr>
            </thead>
            <tbody>
                @foreach($turmas as $turma)
                <tr>
                    <td>{{ $turma->id }}</td>
                    <td>{{ $turma->nome_turma}}</td>
                    <td>{{ $turma->capacidade_turma}}</td>
                    <td>{{ $turma->data_inicio_turma}}</td>
                    <td>{{ $turma->data_termino_turma}}</td>
                    <td>{{ $turma->curso_id}}</td>
                    <td>{{ $turma->ambiente_id}}</td>
                    <td>{{ $turma->status_turma_id}}</td>
                    <td>{{ $turma->minutos_aula_id}}</td>
                    <td>{{ $turma->turno_id}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>