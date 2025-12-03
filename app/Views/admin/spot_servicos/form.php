<?= $this->include('admin/layout/header') ?>

<div style="max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <h1><?= $servico ? 'Editar serviço' : 'Novo serviço'; ?> - <?= esc($spot['nome']); ?></h1>

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
            <label for="nome">Nome do serviço</label>
            <input type="text" name="nome" id="nome" value="<?= old('nome', $servico['nome'] ?? ''); ?>" required>
        </div>

        <div class="field">
            <label for="slug">Slug do serviço</label>
            <input type="text" name="slug" id="slug" value="<?= old('slug', $servico['slug'] ?? ''); ?>">
            <div class="help">Se deixar vazio, será gerado automaticamente a partir do nome.</div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="preco_a_partir">Preço a partir de</label>
                <input type="text" name="preco_a_partir" id="preco_a_partir" value="<?= old('preco_a_partir', $servico['preco_a_partir'] ?? ''); ?>">
                <div class="help">Opcional. Ex: 99.90</div>
            </div>
            <div class="field col">
                <label for="ordem">Ordem</label>
                <input type="text" name="ordem" id="ordem" value="<?= old('ordem', $servico['ordem'] ?? '0'); ?>">
            </div>
        </div>

        <div class="field">
            <label for="descricao_curta">Descrição curta</label>
            <input type="text" name="descricao_curta" id="descricao_curta" value="<?= old('descricao_curta', $servico['descricao_curta'] ?? ''); ?>">
        </div>

        <div class="field">
            <label for="descricao_longa">Descrição detalhada</label>
            <textarea name="descricao_longa" id="descricao_longa"><?= old('descricao_longa', $servico['descricao_longa'] ?? ''); ?></textarea>
        </div>

        <?php
            $imagensExistentes = [];
            if (! empty($servico['imagens'])) {
                $decoded = json_decode($servico['imagens'], true);
                if (is_array($decoded)) {
                    $imagensExistentes = $decoded;
                }
            }
        ?>

        <div class="field">
            <label>Imagens do serviço (até 3)</label>
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
                <input type="checkbox" name="ativo" value="1" <?= old('ativo', $servico['ativo'] ?? 1) ? 'checked' : ''; ?>>
                Serviço ativo
            </label>
        </div>

        <p>
            <a href="<?= site_url('admin/spots/' . $spot['id'] . '/servicos'); ?>" class="button-secondary">Voltar</a>
            <button type="submit" class="button-primary">Salvar</button>
        </p>
    </form>
    </div>

<?= $this->include('admin/layout/footer') ?>

