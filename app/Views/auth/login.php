<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Painel Goodex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; margin:0; background:#020617; color:#e5e7eb; }
        .wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px; }
        .card { width:100%; max-width:380px; background:#020617; border-radius:16px; padding:22px 20px 18px; box-shadow:0 20px 60px rgba(15,23,42,0.9); border:1px solid rgba(148,163,184,0.35); }
        h1 { margin:0 0 4px; font-size:20px; }
        p.subtitle { margin:0 0 14px; font-size:13px; color:#9ca3af; }
        label { display:block; font-size:13px; margin-bottom:4px; font-weight:500; }
        input[type=\"email\"], input[type=\"password\"] { width:100%; padding:8px 10px; border-radius:999px; border:1px solid #1f2933; font-size:14px; box-sizing:border-box; background:#020617; color:#e5e7eb; }
        input[type=\"email\"]:focus, input[type=\"password\"]:focus { outline:none; border-color:#22c55e; }
        .field { margin-bottom:12px; }
        .button-primary { width:100%; padding:9px 16px; background:#22c55e; color:#052e16; border:none; border-radius:999px; font-size:14px; font-weight:600; cursor:pointer; }
        .button-primary:hover { background:#16a34a; }
        .error { background:#fee2e2; color:#991b1b; padding:8px 10px; border-radius:8px; font-size:13px; margin-bottom:12px; }
        small { font-size:12px; color:#64748b; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <h1>Painel Goodex</h1>
        <p class="subtitle">Acesse com seu e-mail e senha.</p>

        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="error">
                <?= esc($error); ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <?= csrf_field(); ?>

            <div class="field">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" value="<?= old('email'); ?>" required>
            </div>

            <div class="field">
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
            </div>

            <p style="margin:4px 0 14px;">
                <small>Em caso de dúvidas, entre em contato com o administrador.</small>
            </p>

            <button type="submit" class="button-primary">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>


