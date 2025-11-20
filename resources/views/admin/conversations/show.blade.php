<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Conversa #{{ $conversation->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .msg { margin: 8px 0; }
        .me { text-align: right; }
        .sys { color: #666; }
    </style>
</head>
<body>
    <h1>Conversa #{{ $conversation->id }}</h1>
    <div id="messages">
        @foreach ($messages as $m)
            <div class="msg {{ $m->sender_type === 'operator' ? 'me' : '' }}">{{ $m->sender_type }}: {{ $m->content }}</div>
        @endforeach
    </div>

    <div style="margin-top: 12px;">
        <input id="content" type="text" placeholder="Digite sua resposta..." style="width: 70%;" />
        <button id="send">Enviar</button>
    </div>

    <script>
        const conversationId = {{ $conversation->id }};
        const clientId = {{ $conversation->client_id }};
        const sendBtn = document.getElementById('send');
        const input = document.getElementById('content');
        const messagesDiv = document.getElementById('messages');

        sendBtn.addEventListener('click', async function() {
            const val = input.value.trim();
            if (!val) return;
            input.value = '';
            const payload = {
                client_id: clientId,
                conversation_id: conversationId,
                sender_type: 'operator',
                content: val
            };
            const res = await fetch('/api/messages', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            if (res.ok) {
                const data = await res.json();
                const div = document.createElement('div');
                div.className = 'msg me';
                div.textContent = 'operator: ' + data.message.content;
                messagesDiv.appendChild(div);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            } else {
                alert('Falha ao enviar mensagem: ' + res.status);
            }
        });
    </script>

    <!-- Echo + Pusher (CDN) para escutar novas mensagens em tempo real -->
    <script src="https://unpkg.com/pusher-js@7/dist/web/pusher.min.js"></script>
    <script src="https://unpkg.com/laravel-echo@1/dist/echo.iife.js"></script>
    <script>
        const key = "{{ env('PUSHER_APP_KEY') }}";
        const host = "{{ env('PUSHER_HOST') }}";
        const port = Number("{{ env('PUSHER_PORT', 6001) }}");
        const useTLS = ("{{ env('PUSHER_USE_TLS', false) }}" === 'true');

        if (key) {
            const Echo = new window.Echo({
                broadcaster: 'pusher',
                key,
                wsHost: host || window.location.hostname,
                wsPort: port,
                wssPort: port,
                forceTLS: useTLS,
                disableStats: true,
                enabledTransports: ['ws', 'wss'],
            });

            Echo.channel('conversation.' + conversationId)
                .listen('.message.created', (e) => {
                    const div = document.createElement('div');
                    div.className = 'msg';
                    div.textContent = e.sender_type + ': ' + e.content;
                    messagesDiv.appendChild(div);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                });
        } else {
            console.warn('PUSHER_APP_KEY não definido. Eventos serão apenas logados.');
        }
    </script>

    <!-- Topbar com botão Sair -->
    <div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #eee; padding:8px 12px; display:flex; justify-content:flex-end; align-items:center;">
        <form method="POST" action="/logout" style="margin:0;">
            <?php echo csrf_field(); ?>
            <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Sair</button>
        </form>
    </div>
</body>
</html>