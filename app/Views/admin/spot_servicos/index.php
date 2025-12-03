<?= $this->include('admin/layout/header') ?>

<div style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);max-width:960px;margin:0 auto;">
    <h1>Serviços de <?= esc($spot['nome']); ?></h1>

    <p class="meta">
        <?php $total = is_countable($servicos) ? count($servicos) : 0; ?>
        Total: <?= $total; ?>
        <?php if (! empty($spot['max_servicos'])): ?>
            · Limite deste spot: <?= (int) $spot['max_servicos']; ?>
        <?php endif; ?>
    </p>

    <p>
        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/servicos/create'); ?>" class="button">+ Novo serviço</a>
        <a href="<?= site_url('admin/spots'); ?>" class="actions" style="margin-left:8px; font-size:13px;">Voltar para spots</a>
    </p>

    <?php if (session()->getFlashdata('message')): ?>
        <p><?= esc(session()->getFlashdata('message')); ?></p>
    <?php endif; ?>

    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th>Preço a partir de</th>
            <th>Ordem</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if (! empty($servicos)): ?>
            <?php foreach ($servicos as $servico): ?>
                <tr>
                    <td><?= esc($servico['nome']); ?></td>
                    <td><?= $servico['preco_a_partir'] !== null && $servico['preco_a_partir'] !== '' ? 'R$ ' . number_format((float) $servico['preco_a_partir'], 2, ',', '.') : '-'; ?></td>
                    <td><?= esc($servico['ordem']); ?></td>
                    <td>
                        <?php if ((int) ($servico['ativo'] ?? 0) === 1): ?>
                            <span class="badge badge-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/servicos/edit/' . $servico['id']); ?>">Editar</a>
                        <a href="<?= site_url('admin/spots/' . $spot['id'] . '/servicos/delete/' . $servico['id']); ?>" onclick="return confirm('Tem certeza que deseja excluir este serviço?');">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhum serviço cadastrado ainda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>

<?= $this->include('admin/layout/footer') ?>

