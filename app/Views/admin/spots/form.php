<?= $this->include('admin/layout/header') ?>

<div class="breadcrumbs">
    <a href="<?= site_url('admin/spots'); ?>">Spots</a>
    <span class="breadcrumbs-separator">/</span>
    <span class="breadcrumbs-current"><?= isset($spot) && $spot ? 'Editar' : 'Novo'; ?></span>
</div>

<div style="max-width:900px;margin:0 auto;background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
    <h1><?= isset($spot) && $spot ? 'Editar Spot' : 'Novo Spot'; ?></h1>

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
            <label for="nome">Nome do cliente</label>
            <input type="text" name="nome" id="nome" value="<?= old('nome', $spot['nome'] ?? ''); ?>" required>
        </div>

        <div class="row">
            <div class="field col">
                <label for="razao_social">Razão social</label>
                <input type="text" name="razao_social" id="razao_social" value="<?= old('razao_social', $spot['razao_social'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="nome_fantasia">Nome fantasia</label>
                <input type="text" name="nome_fantasia" id="nome_fantasia" value="<?= old('nome_fantasia', $spot['nome_fantasia'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="cpf_cnpj">CPF/CNPJ</label>
                <input type="text" name="cpf_cnpj" id="cpf_cnpj" value="<?= old('cpf_cnpj', $spot['cpf_cnpj'] ?? ''); ?>">
            </div>
        </div>

        <div class="field">
            <label for="slug">Slug do cliente</label>
            <input type="text" name="slug" id="slug" value="<?= old('slug', $spot['slug'] ?? ''); ?>">
            <div class="help">Se deixar vazio, será gerado automaticamente a partir do nome.</div>
        </div>

        <?php if (isset($isAdmin) && $isAdmin): ?>
            <div class="field">
                <label for="vendedor_id">Vendedor responsável</label>
                <select name="vendedor_id" id="vendedor_id" style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 14px; box-sizing: border-box;">
                    <option value="">-- Sem vendedor atribuído --</option>
                    <?php foreach ($vendedores as $vendedor): ?>
                        <?php $vendedorIdAtual = (isset($spot) && isset($spot['vendedor_id'])) ? $spot['vendedor_id'] : ''; ?>
                        <option value="<?= esc($vendedor['id']); ?>" <?= old('vendedor_id', $vendedorIdAtual) == $vendedor['id'] ? 'selected' : ''; ?>>
                            <?= esc($vendedor['nome']); ?> (<?= esc($vendedor['email']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="help">Escolha qual vendedor será responsável por este spot. Deixe em branco se não houver vendedor atribuído.</div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="field col">
                <label for="contrato">Nº contrato</label>
                <input type="text" name="contrato" id="contrato" value="<?= old('contrato', $spot['contrato'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="data_contrato">Data do contrato</label>
                <input type="text" name="data_contrato" id="data_contrato" placeholder="YYYY-MM-DD" value="<?= old('data_contrato', $spot['data_contrato'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="vigencia_contrato">Vigência do contrato</label>
                <input type="text" name="vigencia_contrato" id="vigencia_contrato" placeholder="YYYY-MM-DD" value="<?= old('vigencia_contrato', $spot['vigencia_contrato'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="categoria">Categoria</label>
                <input type="text" name="categoria" id="categoria" value="<?= old('categoria', $spot['categoria'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="ramo_id">Ramo de atuação</label>
                <select name="ramo_id" id="ramo_id" style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 14px; box-sizing: border-box;">
                    <option value="">-- Selecione um ramo --</option>
                    <?php if (isset($ramos) && ! empty($ramos)): ?>
                        <?php foreach ($ramos as $ramo): ?>
                            <?php $ramoIdAtual = (isset($spot) && isset($spot['ramo_id'])) ? $spot['ramo_id'] : ''; ?>
                            <option value="<?= esc($ramo['id']); ?>" <?= old('ramo_id', $ramoIdAtual) == $ramo['id'] ? 'selected' : ''; ?>>
                                <?= esc($ramo['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="help">Selecione o ramo de atuação principal da empresa.</div>
            </div>
            <div class="field col">
                <label for="cidade_id">Cidade</label>
                <select name="cidade_id" id="cidade_id" style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #d1d5db; font-size: 14px; box-sizing: border-box;">
                    <option value="">-- Selecione uma cidade --</option>
                    <?php if (isset($cidades) && ! empty($cidades)): ?>
                        <?php
                        // Agrupa cidades por UF
                        $cidadesPorUf = [];
                        foreach ($cidades as $cidade) {
                            $uf = $cidade['uf'] ?? '';
                            if (! isset($cidadesPorUf[$uf])) {
                                $cidadesPorUf[$uf] = [];
                            }
                            $cidadesPorUf[$uf][] = $cidade;
                        }
                        ksort($cidadesPorUf);
                        ?>
                        <?php foreach ($cidadesPorUf as $uf => $cidadesUf): ?>
                            <optgroup label="<?= esc($uf); ?>">
                                <?php foreach ($cidadesUf as $cidade): ?>
                                    <?php $cidadeIdAtual = (isset($spot) && isset($spot['cidade_id'])) ? $spot['cidade_id'] : ''; ?>
                                    <option value="<?= esc($cidade['id']); ?>" <?= old('cidade_id', $cidadeIdAtual) == $cidade['id'] ? 'selected' : ''; ?>>
                                        <?= esc($cidade['nome']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <div class="help">Cidade principal onde a empresa está localizada.</div>
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="servico_principal">Serviço principal</label>
                <input type="text" name="servico_principal" id="servico_principal" value="<?= old('servico_principal', $spot['servico_principal'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="telefone">Telefone</label>
                <input type="text" name="telefone" id="telefone" value="<?= old('telefone', $spot['telefone'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="whatsapp">WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" value="<?= old('whatsapp', $spot['whatsapp'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="instagram">Instagram</label>
                <input type="text" name="instagram" id="instagram" value="<?= old('instagram', $spot['instagram'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="facebook">Facebook</label>
                <input type="text" name="facebook" id="facebook" value="<?= old('facebook', $spot['facebook'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="site">Site</label>
                <input type="text" name="site" id="site" value="<?= old('site', $spot['site'] ?? ''); ?>">
            </div>
        </div>

        <div class="field">
            <label for="descricao">Descrição curta do negócio</label>
            <textarea name="descricao" id="descricao"><?= old('descricao', $spot['descricao'] ?? ''); ?></textarea>
        </div>

        <div class="field">
            <label for="texto_empresa">Texto sobre a empresa (SEO)</label>
            <textarea name="texto_empresa" id="texto_empresa"><?= old('texto_empresa', $spot['texto_empresa'] ?? ''); ?></textarea>
            <div class="help">Texto institucional, falando da empresa, experiência, missão, valores.</div>
        </div>

        <div class="field">
            <label for="texto_servicos">Texto sobre produtos / serviços</label>
            <textarea name="texto_servicos" id="texto_servicos"><?= old('texto_servicos', $spot['texto_servicos'] ?? ''); ?></textarea>
        </div>

        <div class="field">
            <label for="texto_diferenciais">Diferenciais</label>
            <textarea name="texto_diferenciais" id="texto_diferenciais"><?= old('texto_diferenciais', $spot['texto_diferenciais'] ?? ''); ?></textarea>
        </div>

        <div class="field">
            <label for="palavras_chave_principais">Palavras-chave principais (anotação interna)</label>
            <textarea name="palavras_chave_principais" id="palavras_chave_principais"><?= old('palavras_chave_principais', $spot['palavras_chave_principais'] ?? ''); ?></textarea>
            <div class="help">Use apenas para controle interno; não será enviado como meta keywords.</div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="max_produtos">Máx. produtos</label>
                <input type="text" name="max_produtos" id="max_produtos" value="<?= old('max_produtos', $spot['max_produtos'] ?? ''); ?>">
                <div class="help">Deixe vazio para ilimitado. Exemplo: 10</div>
            </div>
            <div class="field col">
                <label for="max_servicos">Máx. serviços</label>
                <input type="text" name="max_servicos" id="max_servicos" value="<?= old('max_servicos', $spot['max_servicos'] ?? ''); ?>">
                <div class="help">Deixe vazio para ilimitado. Exemplo: 5</div>
            </div>
        </div>

        <div class="field">
            <label>Endereço da sede</label>
        </div>

        <div class="row">
            <div class="field col">
                <label for="cep">CEP</label>
                <input type="text" name="cep" id="cep" value="<?= old('cep', $spot['cep'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="logradouro">Logradouro</label>
                <input type="text" name="logradouro" id="logradouro" value="<?= old('logradouro', $spot['logradouro'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="numero">Número</label>
                <input type="text" name="numero" id="numero" value="<?= old('numero', $spot['numero'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="complemento">Complemento</label>
                <input type="text" name="complemento" id="complemento" value="<?= old('complemento', $spot['complemento'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="bairro">Bairro</label>
                <input type="text" name="bairro" id="bairro" value="<?= old('bairro', $spot['bairro'] ?? ''); ?>">
            </div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="cidade_sede">Cidade (sede)</label>
                <input type="text" name="cidade_sede" id="cidade_sede" value="<?= old('cidade_sede', $spot['cidade_sede'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="uf_sede">UF</label>
                <input type="text" name="uf_sede" id="uf_sede" value="<?= old('uf_sede', $spot['uf_sede'] ?? ''); ?>">
            </div>
        </div>

        <div class="field">
            <label for="cidades_atendidas">Lista de cidades atendidas</label>
            <textarea name="cidades_atendidas" id="cidades_atendidas"><?= old('cidades_atendidas', isset($spot['cidades_atendidas']) ? implode(', ', array_map(static function ($c) { return ($c['cidade'] ?? '') . '/' . ($c['estado'] ?? ''); }, json_decode($spot['cidades_atendidas'] ?? '[]', true) ?: [])) : ''); ?></textarea>
            <div class="help">Use o formato: <strong>Cidade/UF, Cidade/UF</strong> (ex: Ribeirão Preto/SP, Sertãozinho/SP)</div>
        </div>

        <div class="row">
            <div class="field col">
                <label for="dias_funcionamento">Dias de funcionamento</label>
                <input type="text" name="dias_funcionamento" id="dias_funcionamento" value="<?= old('dias_funcionamento', $spot['dias_funcionamento'] ?? ''); ?>">
            </div>
            <div class="field col">
                <label for="horarios_funcionamento">Horários de funcionamento</label>
                <input type="text" name="horarios_funcionamento" id="horarios_funcionamento" value="<?= old('horarios_funcionamento', $spot['horarios_funcionamento'] ?? ''); ?>">
            </div>
        </div>

        <div class="field">
            <label for="obs_extras">Observações extras</label>
            <textarea name="obs_extras" id="obs_extras"><?= old('obs_extras', $spot['obs_extras'] ?? ''); ?></textarea>
        </div>

        <div class="field">
            <label for="mapa_embed">Link do Google Maps (opcional)</label>
            <textarea name="mapa_embed" id="mapa_embed"><?= old('mapa_embed', $spot['mapa_embed'] ?? ''); ?></textarea>
            <div class="help">
                Cole aqui apenas o <strong>link</strong> de compartilhamento do Google Maps, por exemplo:<br>
                <code>https://maps.app.goo.gl/qYn3zfNPiUyUjmdR7</code><br>
                O sistema usa esse link apenas como botão "Ver no Google Maps", sem incorporar o iframe original.
            </div>
        </div>

        <div class="field">
            <label for="logo">Logo do cliente</label>
            <?php if (! empty($spot['logo'])): ?>
                <div id="logo-preview-container" style="margin-bottom:8px;">
                    <img id="logo-preview" src="<?= esc(base_url($spot['logo'])); ?>" alt="Logo atual" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
            <?php else: ?>
                <div id="logo-preview-container" style="margin-top: 10px; display: none;">
                    <img id="logo-preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #d1d5db;">
                </div>
            <?php endif; ?>
            <input type="file" name="logo" id="logo" accept="image/*">
            <input type="hidden" name="logo_atual" value="<?= esc($spot['logo'] ?? ''); ?>">
            <div class="help">Envie um arquivo de logo em PNG ou JPG. Se não selecionar nada, o logo atual é mantido.</div>
        </div>

        <div class="field">
            <label class="checkbox-inline">
                <input type="checkbox" name="ativo" value="1" <?= old('ativo', $spot['ativo'] ?? 1) ? 'checked' : ''; ?>>
                Spot ativo
            </label>
        </div>

        <p>
            <a href="<?= site_url('admin/spots'); ?>" class="button-secondary">Voltar</a>
            <button type="submit" class="button-primary">Salvar</button>
        </p>
    </form>
</div>

<?= $this->include('admin/layout/footer') ?>
