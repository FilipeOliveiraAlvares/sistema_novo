<?= $this->include('admin/layout/header') ?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h1 class="admin-card-title">Spots</h1>
            <p class="admin-card-subtitle">
                Gerencie os spots dos seus clientes e acesse produtos e serviços vinculados.
            </p>
        </div>
        <a href="<?= site_url('admin/spots/create'); ?>" class="button">+ Novo Spot</a>
    </div>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')); ?></p>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Slug</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if (! empty($spots)): ?>
            <?php foreach ($spots as $spot): ?>
                <tr>
                    <td><?= esc($spot['id']); ?></td>
                    <td><?= esc($spot['nome']); ?></td>
                    <td><?= esc($spot['slug']); ?></td>
                    <td><?= esc($spot['categoria'] ?? '-'); ?></td>
                    <td>
                        <?php if ((int) ($spot['ativo'] ?? 0) === 1): ?>
                            <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= site_url('admin/spots/edit/' . $spot['id']); ?>">Editar</a>
                        <a href="<?= site_url('admin/spots/delete/' . $spot['id']); ?>" onclick="return confirm('Tem certeza que deseja excluir este spot?');">Excluir</a>
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos'); ?>">Produtos</a>
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/servicos'); ?>">Serviços</a>
                        <a href="<?= site_url('spot/' . $spot['slug']); ?>" target="_blank">Ver página</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum spot cadastrado ainda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/layout/footer') ?>
