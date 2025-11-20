<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Clientes - Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; margin: 2rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        a.button { display: inline-block; padding: 8px 12px; border: 1px solid #333; border-radius: 6px; text-decoration: none; }
    </style>
</head>
<body>
    <h1>Clientes</h1>
    <p><a href="{{ route('clients.create') }}" class="button">Novo Cliente</a></p>

    @if (session('status'))
        <p style="color:green">{{ session('status') }}</p>
    @endif

    <!-- Topbar com botão Sair -->
    <div style="position:sticky; top:0; background:#fff; border-bottom:1px solid #eee; padding:8px 12px; display:flex; justify-content:flex-end; align-items:center;">
        <form method="POST" action="/logout" style="margin:0;">
            <?php echo csrf_field(); ?>
            <button type="submit" style="background:#ef4444; color:#fff; border:none; padding:8px 12px; border-radius:6px; cursor:pointer;">Sair</button>
        </form>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Slug</th>
                <th>Ativo</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->slug }}</td>
                <td>{{ $client->active ? 'Sim' : 'Não' }}</td>
                <td>
                    <a href="{{ route('clients.show', $client) }}">Snippet</a> |
                    <a href="{{ route('clients.edit', $client) }}">Editar</a> |
                    <a href="{{ route('clients.settings.edit', $client) }}">Widget</a> |
                    <form action="{{ route('clients.destroy', $client) }}" method="post" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Remover cliente?')">Remover</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $clients->links() }}
</body>
</html>