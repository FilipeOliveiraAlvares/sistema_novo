<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/usuarios'); ?>">Usuários</a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current"><?= isset($usuario) && $usuario ? 'Editar' : 'Novo'; ?></span>
</div>

<div style="max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <h1><?= isset($usuario) && $usuario ? 'Editar Usuário' : 'Novo Usuário'; ?></h1>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="errors">
            <?php if (is_array($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div><?= esc($error); ?></div>
                <?php endforeach; ?>
            <?php else: ?>
                <div><?= esc($errors); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <?= csrf_field(); ?>

        <div class="field">
            <label for="nome">Nome completo</label>
            <input type="text" name="nome" id="nome" value="<?= old('nome', $usuario['nome'] ?? ''); ?>" required>
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="<?= old('email', $usuario['email'] ?? ''); ?>" required>
            <div class="help">O email será usado para fazer login no sistema.</div>
        </div>

        <div class="field">
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" value="" <?= ! isset($usuario) ? 'required' : ''; ?>>
            <div class="help">
                <?php if (isset($usuario)): ?>
                    Deixe em branco para manter a senha atual. Mínimo de 6 caracteres.
                <?php else: ?>
                    Mínimo de 6 caracteres.
                <?php endif; ?>
            </div>
        </div>

        <div class="field">
            <label for="perfil">Perfil</label>
            <select name="perfil" id="perfil" style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 14px; box-sizing: border-box;">
                <option value="vendedor" <?= old('perfil', $usuario['perfil'] ?? 'vendedor') === 'vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                <option value="admin" <?= old('perfil', $usuario['perfil'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrador</option>
            </select>
            <div class="help">
                <strong>Vendedor:</strong> Pode criar e gerenciar apenas seus próprios spots.<br>
                <strong>Administrador:</strong> Pode ver e gerenciar todos os spots e usuários.
            </div>
        </div>

        <div class="field">
            <label class="checkbox-inline">
                <input type="checkbox" name="ativo" value="1" <?= old('ativo', $usuario['ativo'] ?? 1) ? 'checked' : ''; ?>>
                Usuário ativo
            </label>
            <div class="help">Usuários inativos não conseguem fazer login no sistema.</div>
        </div>

        <?php if (isset($usuario) && $usuario && ($usuario['perfil'] ?? '') === 'vendedor' && isset($totalSpots) && $totalSpots > 0): ?>
            <div style="margin-top: 24px; padding: 16px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">
                <h3 style="margin-top: 0; font-size: 16px;">Reatribuir Spots</h3>
                <p style="margin: 8px 0; font-size: 14px;">
                    Este vendedor possui <strong><?= $totalSpots; ?></strong> spot(s) cadastrado(s).
                    Se este vendedor sair da empresa, você pode reatribuir todos os spots dele para outro vendedor.
                </p>
                <?php if (! empty($vendedores)): ?>
                    <form method="post" style="margin-top: 12px;" onsubmit="return confirm('Tem certeza que deseja reatribuir todos os <?= $totalSpots; ?> spot(s) deste vendedor para outro? Esta ação não pode ser desfeita.');">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="acao" value="reatribuir_spots">
                        <div style="display: flex; gap: 8px; align-items: flex-end;">
                            <div style="flex: 1;">
                                <label for="novo_vendedor_id" style="display: block; margin-bottom: 4px; font-size: 13px; font-weight: 600;">Reatribuir para:</label>
                                <select name="novo_vendedor_id" id="novo_vendedor_id" required style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 14px; box-sizing: border-box;">
                                    <option value="">-- Selecione um vendedor --</option>
                                    <?php foreach ($vendedores as $vend): ?>
                                        <option value="<?= esc($vend['id']); ?>"><?= esc($vend['nome']); ?> (<?= esc($vend['email']); ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" style="padding: 8px 16px; background: #f59e0b; color: #fff; border: none; border-radius: 4px; font-size: 14px; cursor: pointer; white-space: nowrap;">Reatribuir Spots</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p style="margin: 8px 0; font-size: 13px; color: #92400e;">
                        Não há outros vendedores cadastrados para reatribuir os spots.
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <p style="margin-top: 24px;">
            <a href="<?= site_url('admin/usuarios'); ?>" class="button-secondary">Voltar</a>
            <button type="submit" class="button-primary">Salvar</button>
        </p>
    </form>
</div>

<?= $this->include('admin/layout/footer') ?>

