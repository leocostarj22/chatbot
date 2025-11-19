<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Conversas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Conversas</h1>
    <ul>
        @foreach ($conversations as $conv)
            <li>
                #{{ $conv->id }} — Visitor: {{ $conv->visitor_id }} — Status: {{ $conv->status }}
                <a href="{{ route('conversations.show', $conv) }}">Abrir</a>
            </li>
        @endforeach
    </ul>
    {{ $conversations->links() }}
</body>
</html>