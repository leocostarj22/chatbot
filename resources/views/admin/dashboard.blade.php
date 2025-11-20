<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Painel Admin - Chatbot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin: 2rem; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; }
        .title { font-weight: 600; }
        .muted { color: #666; font-size: 0.9rem; }
    </style>
</head>
<body>
    <h1>Painel Administrativo</h1>
    <p class="muted">MVP: Lista de últimas conversas</p>

    @foreach ($conversations as $conv)
        <div class="card">
            <div class="title">Conversa #{{ $conv->id }} — Visitor: {{ $conv->visitor_id }} — Status: {{ $conv->status }}</div>
            <div class="muted">Criada em {{ $conv->created_at }}</div>
            @php $messages = $conv->messages()->orderBy('created_at','desc')->limit(3)->get(); @endphp
            <ul>
                @foreach ($messages as $msg)
                    <li>[{{ $msg->sender_type }}] {{ $msg->content }} <span class="muted">({{ $msg->created_at }})</span></li>
                @endforeach
            </ul>
        </div>
    @endforeach
</body>
</html>
<!-- Topbar com botão Sair -->
<div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #eee; padding:8px 12px; display:flex; justify-content:flex-end; align-items:center;">
    <form method="POST" action="/logout" style="margin:0;">
        <?php echo csrf_field(); ?>
        <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Sair</button>
    </form>
</div>