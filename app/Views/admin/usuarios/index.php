<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/usuarios'); ?>">Usuários</a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current">Lista</span>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h1 class="admin-card-title">Usuários</h1>
            <p class="admin-card-subtitle">
                Gerencie vendedores e administradores do sistema.
            </p>
        </div>
        <a href="<?= site_url('admin/usuarios/create'); ?>" class="button">+ Novo Usuário</a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <div data-flash-message data-flash-type="success" style="display: none;"><?= esc(session()->getFlashdata('message')); ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div data-flash-message data-flash-type="error" style="display: none;"><?= esc(session()->getFlashdata('error')); ?></div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if (! empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= esc($usuario['id']); ?></td>
                    <td><?= esc($usuario['nome']); ?></td>
                    <td><?= esc($usuario['email']); ?></td>
                    <td>
                        <?php if (($usuario['perfil'] ?? '') === 'admin'): ?>
                            <span class="badge" style="background: #dbeafe; color: #1e40af;">Admin</span>
                        <?php else: ?>
                            <span class="badge" style="background: #fef3c7; color: #92400e;">Vendedor</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ((int) ($usuario['ativo'] ?? 0) === 1): ?>
                            <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= site_url('admin/usuarios/edit/' . $usuario['id']); ?>">Editar</a>
                        <?php if ((int) ($usuario['id']) !== (int) (session('user_id'))): ?>
                            <a href="<?= site_url('admin/usuarios/delete/' . $usuario['id']); ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum usuário cadastrado ainda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/layout/footer') ?>

