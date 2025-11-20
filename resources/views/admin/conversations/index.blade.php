<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Conversas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Conversas</h1>
    <!-- Topbar com botão Sair -->
    <div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #eee; padding:8px 12px; display:flex; justify-content:flex-end; align-items:center;">
        <form method="POST" action="/logout" style="margin:0;">
            <?php echo csrf_field(); ?>
            <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Sair</button>
        </form>
    </div>
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