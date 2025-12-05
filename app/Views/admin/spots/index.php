<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/spots'); ?>">Spots</a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current">Lista</span>
</div>

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
        <div data-flash-message data-flash-type="success" style="display: none;"><?= esc(session()->getFlashdata('message')); ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div data-flash-message data-flash-type="error" style="display: none;"><?= esc(session()->getFlashdata('error')); ?></div>
    <?php endif; ?>

    <div class="search-filters">
        <input 
            type="text" 
            id="search-input" 
            placeholder="Buscar por nome, slug ou categoria..." 
            onkeyup="filterTable()"
        >
        <select id="status-filter" onchange="filterTable()">
            <option value="">Todos os status</option>
            <option value="ativo">Ativo</option>
            <option value="inativo">Inativo</option>
        </select>
        <?php if (isset($isAdmin) && $isAdmin && !empty($vendedoresMap)): ?>
            <select id="vendedor-filter" onchange="filterTable()">
                <option value="">Todos os vendedores</option>
                <?php foreach ($vendedoresMap as $id => $nome): ?>
                    <option value="<?= esc($nome); ?>"><?= esc($nome); ?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <button type="button" class="btn-clear" onclick="clearFilters()">Limpar</button>
        <span class="result-count" id="result-count"></span>
    </div>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Slug</th>
            <th>Categoria</th>
            <?php if (isset($isAdmin) && $isAdmin): ?>
                <th>Vendedor</th>
            <?php endif; ?>
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
                    <?php if (isset($isAdmin) && $isAdmin): ?>
                        <td>
                            <?php
                            $vendedorId = (int) ($spot['vendedor_id'] ?? 0);
                            if ($vendedorId > 0 && isset($vendedoresMap[$vendedorId])) {
                                echo esc($vendedoresMap[$vendedorId]);
                            } else {
                                echo '<span style="color: #9ca3af;">Sem vendedor</span>';
                            }
                            ?>
                        </td>
                    <?php endif; ?>
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
                <td colspan="<?= (isset($isAdmin) && $isAdmin) ? '7' : '6'; ?>">Nenhum spot cadastrado ainda.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->include('admin/layout/footer') ?>
