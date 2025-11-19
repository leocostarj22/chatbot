<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Novo Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h1>Novo Cliente</h1>
    <form method="post" action="{{ route('clients.store') }}">
        @csrf
        <div>
            <label>Nome</label>
            <input type="text" name="name" value="{{ old('name') }}" required />
            @error('name')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required />
            @error('slug')<div style="color:red">{{ $message }}</div>@enderror
        </div>
        <div>
            <label>Ativo</label>
            <input type="checkbox" name="active" value="1" checked />
        </div>
        <button type="submit">Criar</button>
        <a href="{{ route('clients.index') }}">Voltar</a>
    </form>
</body>
</html>