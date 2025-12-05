<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/spots'); ?>">Spots</a>
    <span class="breadcrumbs-separator">/</span>
    <a href="<?= site_url('admin/spots/edit/' . $spot['id']); ?>"><?= esc($spot['nome']); ?></a>
    <span class="breadcrumbs-separator">/</span>
    <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos'); ?>">Produtos</a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current"><?= $produto ? 'Editar' : 'Novo'; ?></span>
</div>

<div style="max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <h1><?= $produto ? 'Editar produto' : 'Novo produto'; ?> - <?= esc($spot['nome']); ?></h1>

    <?php if ($errors = session()->getFlashdata('errors')): ?>
        <div class="errors">
            <?php foreach ($errors as $error): ?>
                <div><?= esc($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="field">
            <label for="nome">Nome do produto</label>
            <input type="text" name="nome" id="nome" value="<?= old('nome', $produto['nome'] ?? ''); ?>" required>
        </div>

        <div class="field">
            <label for="slug">Slug do produto</label>
            <input type="text" name="slug" id="slug" value="<?= old('slug', $produto['slug'] ?? ''); ?>">
            <div class="help">Se deixar vazio, será gerado automaticamente a partir do nome.</div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="preco">Preço</label>
                <input type="text" name="preco" id="preco" value="<?= old('preco', $produto['preco'] ?? ''); ?>">
                <div class="help">Opcional. Ex: 199.90</div>
            </div>
            <div class="field col">
                <label for="ordem">Ordem</label>
                <input type="text" name="ordem" id="ordem" value="<?= old('ordem', $produto['ordem'] ?? '0'); ?>">
            </div>
        </div>

        <div class="field">
            <label for="descricao_curta">Descrição curta</label>
            <input type="text" name="descricao_curta" id="descricao_curta" value="<?= old('descricao_curta', $produto['descricao_curta'] ?? ''); ?>">
        </div>

        <div class="field">
            <label for="descricao_longa">Descrição detalhada</label>
            <textarea name="descricao_longa" id="descricao_longa"><?= old('descricao_longa', $produto['descricao_longa'] ?? ''); ?></textarea>
        </div>

        <?php
            $imagensExistentes = [];
            if (! empty($produto['imagens'])) {
                $decoded = json_decode($produto['imagens'], true);
                if (is_array($decoded)) {
                    $imagensExistentes = $decoded;
                }
            }
        ?>

        <div class="field">
            <label>Imagens do produto (até 3)</label>
        </div>

        <?php for ($i = 1; $i <= 3; $i++): ?>
            <?php $img = $imagensExistentes[$i - 1] ?? null; ?>
            <div class="field">
                <label for="imagem<?= $i; ?>">Imagem <?= $i; ?></label>
                <?php if ($img): ?>
                    <div style="margin-bottom:6px;">
                        <img src="<?= esc(base_url($img)); ?>" alt="Imagem <?= $i; ?>" style="max-height:70px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="imagem<?= $i; ?>" id="imagem<?= $i; ?>" accept="image/*">
            </div>
        <?php endfor; ?>

        <div class="field">
            <label class="checkbox-inline">
                <input type="checkbox" name="ativo" value="1" <?= old('ativo', $produto['ativo'] ?? 1) ? 'checked' : ''; ?>>
                Produto ativo
            </label>
        </div>

        <p>
            <a href="<?= site_url('admin/spots/' . $spot['id'] . '/produtos'); ?>" class="button-secondary">Voltar</a>
            <button type="submit" class="button-primary">Salvar</button>
        </p>
    </form>
    </div>

<?= $this->include('admin/layout/footer') ?>

