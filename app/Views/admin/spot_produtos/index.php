<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/spots'); ?>">Spots</a>
    <span class="breadcrumbs-separator">/</span>
    <a href="<?= site_url('admin/spots/edit/' . $spot['id']); ?>"><?= esc($spot['nome']); ?></a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current">Produtos</span>
</div>

<div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <h1>Produtos de <?= esc($spot['nome']); ?></h1>

    <p class="meta">
        <?php $total = is_countable($produtos) ? count($produtos) : 0; ?>
        Total: <?= $total; ?>
        <?php if (! empty($spot['max_produtos'])): ?>
            · Limite deste spot: <?= (int) $spot['max_produtos']; ?>
        <?php endif; ?>
    </p>

    <p>
        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos/create'); ?>" class="button">+ Novo produto</a>
        <a href="<?= site_url('admin/spots'); ?>" class="actions" style="margin-left:8px; font-size:13px;">Voltar para spots</a>
    </p>

    <?php if (session()->getFlashdata('message')): ?>
        <div data-flash-message data-flash-type="success" style="display: none;"><?= esc(session()->getFlashdata('message')); ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div data-flash-message data-flash-type="error" style="display: none;"><?= esc(session()->getFlashdata('error')); ?></div>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Ordem</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if (! empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <tr>
                    <td><?= esc($produto['nome']); ?></td>
                    <td><?= $produto['preco'] !== null && $produto['preco'] !== '' ? 'R$ ' . number_format((float) $produto['preco'], 2, ',', '.') : '-'; ?></td>
                    <td><?= esc($produto['ordem']); ?></td>
                    <td>
                        <?php if ((int) ($produto['ativo'] ?? 0) === 1): ?>
                            <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos/edit/' . $produto['id']); ?>">Editar</a>
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos/delete/' . $produto['id']); ?>" onclick="return confirm('Tem certeza que deseja excluir este produto?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhum produto cadastrado ainda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>

<?= $this->include('admin/layout/footer') ?>

