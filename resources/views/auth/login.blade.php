<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Login - Painel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, sans-serif; background:#f7f7f9; display:flex; align-items:center; justify-content:center; min-height:100vh; }
        .card { background:#fff; padding:24px; border-radius:8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); width: 360px; }
        .mb { margin-bottom: 12px; }
        label { display:block; margin-bottom:6px; color:#333; font-size:14px; }
        input[type="email"], input[type="password"] { width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; }
        button { width:100%; padding:10px; background:#2f80ed; border:none; color:#fff; border-radius:6px; cursor:pointer; }
        .error { color:#b00020; font-size:13px; margin: 6px 0 0; }
        .top { margin-bottom:16px; text-align:center; }
        .muted { font-size:13px; color:#666; margin-top:8px; text-align:center; }
    </style>
</head>
<body>
    <div class="card">
        <div class="top">
            <h2>Entrar</h2>
            <div class="muted">Acesse o painel do cliente</div>
        </div>

        <form method="POST" action="/login">
            <?php echo csrf_field(); ?>

            <div class="mb">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required autofocus>
                <?php if($errors->has('email')): ?>
                    <div class="error"><?php echo e($errors->first('email')); ?></div>
                <?php endif; ?>
            </div>

            <div class="mb">
                <label for="password">Senha</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="mb" style="display:flex; align-items:center; gap:8px;">
                <input id="remember" name="remember" type="checkbox" value="1">
                <label for="remember" style="margin:0;">Lembrar-me</label>
            </div>

            <button type="submit">Entrar</button>

            <div class="muted" style="margin-top:12px;">
                Precisa de acesso? Fale com o administrador da sua conta.
            </div>
        </form>
    </div>
</body>
</html>