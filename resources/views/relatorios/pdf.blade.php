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
            background-color: #e53939;
            width: 100%;
            height: 10%;
            position: relative;
            bottom: 50;
        }

        h2 {
            background-color: #eee;
            padding: 10px;
            margin-top: 20px;
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
            background-color: #f2f2f2;
        }

        .no-turmas {
            color: #888;
        }
    </style>
</head>

<body>
    <header>
        <h1>Relatório Semestral de Turmas</h1>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </header>

    @foreach ($turmasPorMes as $nomeMes => $turmasDoMes)

    <h2>{{ $nomeMes }}</h2>

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