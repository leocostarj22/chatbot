<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Editar Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Editar Cliente #{{ $client->id }}</h1>

    @if (session('status'))
        <p style="color:green">{{ session('status') }}</p>
    @endif

    <form method="post" action="{{ route('clients.update', $client) }}">
        @csrf @method('PUT')
        <div>
            <label>Nome</label>
            <input type="text" name="name" value="{{ old('name', $client->name) }}" required />
            @error('name')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $client->slug) }}" required />
            @error('slug')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Ativo</label>
            <input type="checkbox" name="active" value="1" {{ $client->active ? 'checked' : '' }} />
        </div>
        <button type="submit">Salvar</button>
        <a href="{{ route('clients.index') }}">Voltar</a>
    </form>
</body>
</html>