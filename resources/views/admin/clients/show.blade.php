<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Snippet do Widget</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        code { background: #f4f4f4; padding: 2px 4px; border-radius: 4px; display: block; }
    </style>
</head>
<body>
    <h1>Snippet para o Cliente #{{ $client->id }} — {{ $client->name }}</h1>

    @if (session('status'))
        <p style="color:green">{{ session('status') }}</p>
    @endif

    @php
        $base = rtrim(config('app.url') ?: url('/'), '/');
        $widgetSrc = $base . '/widget.js';
        $scriptPublic = '<script async src="'.$widgetSrc.'" data-public-key="'.$client->public_key.'"></script>';
        $scriptId = '<script async src="'.$widgetSrc.'" data-chat-id="'.$client->id.'"></script>';
    @endphp

    <p>Copie e cole no site do cliente:</p>
    <code>{{ htmlentities($script) }}</code>

    <h3>Snippet recomendado (chave pública)</h3>
    <code>{{ htmlentities($scriptPublic) }}</code>

    <h3>Alternativo (por ID)</h3>
    <code>{{ htmlentities($scriptId) }}</code>

    <p>
        <a href="{{ route('clients.settings.edit', $client) }}">Configurar Widget</a> |
        <a href="{{ route('clients.index') }}">Voltar</a>
    </p>

    <h3>Preview Local</h3>
    <p>Abra <code>{{ url('/demo.html') }}</code> para ver o widget rodando.</p>
</body>
</html>