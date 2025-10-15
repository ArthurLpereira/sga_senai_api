<!DOCTYPE html>
<html>

<head>
    <title>Relatório Semestral de Turmas</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 0;
            margin: 0;
        }

        header {
            background-color: #9A1915;
            width: 100%;
            height: 12%;
            position: relative;
            bottom: 50;
            right: 35;
            color: #f1f5f9;
            width: 115%;
        }

        header div {
            margin-left: 20px;
            padding-top: 10px;
        }

        header div p {
            font-size: 17px;
        }

        hr {
            background-color: #9A1915;
            border: none;
            height: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background-color: #9A1915;
            color: #f1f5f9;
            border: none;
        }

        .no-turmas {
            color: #888;
        }
    </style>
</head>

<body>
    <header>
        <div>
            <h1>Planilha Semestral SGA</h1>
            <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </header>

    @foreach ($turmasPorMes as $nomeMes => $turmasDoMes)

    <h2>{{ $nomeMes }}</h2>
    <hr>
    @if ($turmasDoMes->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Turma</th>
                <th>Curso</th>
                <th>Turno</th>
                <th>Período</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($turmasDoMes as $turma)
            <tr>
                <td>{{ $turma->nome_turma }}</td>
                <td>{{ $turma->curso->nome_curso }}</td>
                <td>{{ $turma->turno->nome_turno }}</td>
                <td>{{ \Carbon\Carbon::parse($turma->data_inicio_turma)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($turma->data_termino_turma)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="no-turmas">Nenhuma turma ativa neste mês.</p>
    @endif

    @endforeach

</body>

</html>