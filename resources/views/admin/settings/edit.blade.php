<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Configurações do Widget</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Widget — Cliente #{{ $client->id }} ({{ $client->name }})</h1>

    <form method="post" action="{{ route('clients.settings.update', $client) }}">
        @csrf @method('PUT')
        <div>
            <label>Cor primária</label>
            <input type="text" name="primary_color" value="{{ old('primary_color', $settings->primary_color ?? '#2f80ed') }}" required />
            @error('primary_color')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Cor secundária</label>
            <input type="text" name="secondary_color" value="{{ old('secondary_color', $settings->secondary_color ?? '#ffffff') }}" required />
            @error('secondary_color')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Mensagem de boas-vindas</label>
            <input type="text" name="welcome_message" value="{{ old('welcome_message', $settings->welcome_message ?? 'Olá! Como posso ajudar?') }}" required />
            @error('welcome_message')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Mensagem online</label>
            <input type="text" name="online_message" value="{{ old('online_message', $settings->online_message ?? 'Estamos online') }}" required />
            @error('online_message')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Mensagem offline</label>
            <input type="text" name="offline_message" value="{{ old('offline_message', $settings->offline_message ?? 'Estamos offline, deixe sua mensagem') }}" required />
            @error('offline_message')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Avatar URL</label>
            <input type="url" name="avatar_url" value="{{ old('avatar_url', $settings->avatar_url ?? '') }}" />
            @error('avatar_url')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Horários (JSON ou texto ex: 09:00-18:00)</label>
            <input type="text" name="open_hours" value="{{ old('open_hours', isset($settings->open_hours) ? json_encode($settings->open_hours) : '') }}" />
            @error('open_hours')<div style="color:red">{{ $message }}</div>@enderror
        </div>

        <button type="submit">Salvar</button>
        <a href="{{ route('clients.show', $client) }}">Voltar</a>
    </form>
</body>
</html>