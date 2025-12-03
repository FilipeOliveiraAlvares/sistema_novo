<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo_seo); ?></title>
    <meta name="description" content="<?= esc($descricao_seo); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <!-- Schema.org LocalBusiness básico (uma sede, várias cidades atendidas) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "<?= esc($spot['nome_fantasia'] ?: $spot['nome']); ?>",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?= esc(trim(($spot['logradouro'] ?? '') . ' ' . ($spot['numero'] ?? ''))); ?>",
        "addressLocality": "<?= esc($spot['cidade_sede'] ?: (!empty($cidades[0]['cidade']) ? $cidades[0]['cidade'] : '')); ?>",
        "addressRegion": "<?= esc($spot['uf_sede'] ?: (!empty($cidades[0]['estado']) ? $cidades[0]['estado'] : '')); ?>",
        "postalCode": "<?= esc($spot['cep'] ?? ''); ?>",
        "addressCountry": "BR"
      },
      "telephone": "<?= esc($spot['telefone'] ?? ''); ?>",
      "url": "<?= current_url(); ?>"
    }
    </script>

    <style>
        :root {
            --bg-page: #f1f3f4; /* cinza Google claro */
            --bg-header-gradient: linear-gradient(135deg, #ffffff, #e8f0fe);
            --bg-card: #ffffff;
            --bg-chip: #e8f0fe;
            --border-soft: rgba(218, 220, 224, 0.9);
            --text-main: #202124;
            --text-muted: #5f6368;
            --accent-primary: #1a73e8;          /* azul Google */
            --accent-primary-dark: #1558b0;
            --accent-secondary: #1a73e8;
            --color-whatsapp: #25d366;
            --color-whatsapp-dark: #1ebe5b;
            --color-call: #1a73e8;
            --color-maps: #4285f4;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            color: var(--text-main);
            background: var(--bg-page);
        }

        header {
            background: var(--bg-header-gradient);
            color: #0f172a;
            padding: 26px 16px 30px;
        }

        .header-content {
            max-width: 1024px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .header-main {
            flex: 1;
            min-width: 0;
        }

        header h1 {
            margin: 0 0 6px;
            font-size: 26px;
            letter-spacing: -0.02em;
        }

        header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            max-height: 96px;
            max-width: 260px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 16px;
            padding: 10px 18px;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.25);
        }

        .header-cta {
            margin-top: 14px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 999px;
            background: var(--accent-primary);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            box-shadow: 0 6px 18px rgba(26, 115, 232, 0.35);
        }

        .btn-primary:hover {
            background: var(--accent-primary-dark);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.7);
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.9);
            font-size: 13px;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: #ffffff;
        }

        .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            border-radius: 999px;
            background: var(--color-whatsapp);
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            box-shadow: 0 6px 18px rgba(37, 211, 102, 0.4);
            gap: 6px;
        }

        .btn-whatsapp:hover {
            background: var(--color-whatsapp-dark);
        }

        .btn-call {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            border-radius: 999px;
            background: var(--color-call);
            color: #ffffff;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            border: none;
            gap: 6px;
        }

        main {
            max-width: 1024px;
            margin: 26px auto 40px;
            padding: 0 16px;
        }

        .card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 22px 22px 18px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            margin-bottom: 20px;
            border: 1px solid var(--border-soft);
        }

        h2 {
            font-size: 18px;
            margin: 0 0 8px;
            color: var(--text-main);
            letter-spacing: -0.01em;
        }

        p {
            font-size: 14px;
            line-height: 1.7;
            margin: 0 0 8px;
            color: var(--text-main);
        }

        .grid {
            display: grid;
            grid-template-columns: 1.9fr 1.1fr;
            gap: 24px;
        }

        .meta {
            font-size: 13px;
            color: var(--text-muted);
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .contact p {
            margin: 0 0 6px;
        }

        .contact a {
            color: var(--accent-secondary);
            text-decoration: none;
            font-size: 14px;
        }

        .contact a:hover {
            text-decoration: underline;
        }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.16);
            color: var(--accent-secondary);
            font-size: 11px;
        }

        ul.cidades {
            list-style: none;
            padding: 0;
            margin: 8px 0 0;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        ul.cidades li {
            font-size: 12px;
            background: var(--bg-chip);
            color: #111827;
            padding: 4px 11px;
            border-radius: 999px;
            border: 1px solid var(--border-soft);
        }

        .chip-list {
            list-style: none;
            padding: 0;
            margin: 4px 0 0;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .chip {
            font-size: 12px;
            background: var(--bg-chip);
            color: #111827;
            padding: 4px 11px;
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            white-space: nowrap;
        }

        .section-divider {
            border: none;
            border-top: 1px solid var(--border-soft);
            margin: 16px 0 12px;
        }

        .section-link {
            margin-top: 8px;
            font-size: 13px;
        }

        .section-link a {
            color: var(--accent-secondary);
            text-decoration: none;
        }

        .section-link a:hover {
            text-decoration: underline;
        }

        .map-full {
            width: 100%;
            margin: 0;
            padding: 0;
            background: #e5e7eb;
        }

        .map-full-inner {
            max-width: 1024px;
            margin: 0 auto;
            padding: 0 16px 20px;
        }

        .map-card {
            background: linear-gradient(to bottom right, #ffffff, #e5f3ff);
            border-radius: 18px;
            padding: 18px 20px 22px;
            color: var(--text-main);
            border: 1px solid var(--border-soft);
        }

        .map-card h2 {
            margin: 0 0 6px;
            font-size: 16px;
        }

        .map-card p {
            margin: 0 0 10px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .map-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 9px 18px;
            border-radius: 999px;
            background: var(--color-maps);
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            border: none;
        }

        .map-button:hover {
            background: var(--accent-primary-dark);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }

        .gallery-item {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border-soft);
            background: #ffffff;
        }

        .gallery-img-wrapper {
            width: 100%;
            overflow: hidden;
        }

        .gallery-img-wrapper img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
        }

        .gallery-meta {
            padding: 8px 10px 9px;
        }

        .gallery-meta-title {
            font-size: 13px;
            font-weight: 500;
            margin: 0 0 2px;
            color: var(--text-main);
        }

        .gallery-meta-type {
            font-size: 11px;
            color: var(--text-muted);
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-main {
                width: 100%;
            }

            header h1 {
                font-size: 22px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .card {
                padding: 18px 16px 14px;
            }

            .logo {
                max-width: 180px;
                max-height: 56px;
            }
        }

        @media (min-width: 1024px) {
            header {
                padding-top: 34px;
                padding-bottom: 36px;
            }
        }
    </style>
</head>
<body>
<?php
    $cidadePrincipal = $cidades[0] ?? null;
    $nomePublico = $spot['nome_fantasia'] ?: $spot['nome'];

    // Monta endereço para o mapa
    $cidadeMapa = $spot['cidade_sede'] ?: ($cidadePrincipal['cidade'] ?? '');
    $ufMapa     = $spot['uf_sede'] ?: ($cidadePrincipal['estado'] ?? '');
    $partesEndereco = array_filter([
        $spot['logradouro'] ?? '',
        $spot['numero'] ?? '',
        $spot['bairro'] ?? '',
        $cidadeMapa,
        $ufMapa,
        $spot['cep'] ?? '',
    ]);
    $enderecoMapa = implode(', ', $partesEndereco);
    $temEnderecoMapa = $enderecoMapa !== '';

    // Link opcional para abrir direto no Google Maps (sempre usamos esse link se existir)
    $mapLinkCustom = trim($spot['mapa_embed'] ?? '');

    // Nomes de serviços/produtos em destaque para reforçar texto SEO
    $nomesServicosSeo = [];
    if (! empty($servicos_destaque)) {
        foreach (array_slice($servicos_destaque, 0, 3) as $s) {
            if (! empty($s['nome'])) {
                $nomesServicosSeo[] = $s['nome'];
            }
        }
    }

    $nomesProdutosSeo = [];
    if (! empty($produtos_destaque)) {
        foreach (array_slice($produtos_destaque, 0, 3) as $p) {
            if (! empty($p['nome'])) {
                $nomesProdutosSeo[] = $p['nome'];
            }
        }
    }
?>
<header>
    <div class="header-content">
        <?php if (! empty($spot['logo'])): ?>
            <div class="logo-wrapper">
                <img src="<?= esc(base_url($spot['logo'])); ?>" alt="Logo <?= esc($nomePublico); ?>" class="logo">
            </div>
        <?php endif; ?>
        <div class="header-main">
            <h1><?= esc($nomePublico); ?><?= $cidadePrincipal ? ' em ' . esc($cidadePrincipal['cidade']) . ' - ' . esc($cidadePrincipal['estado']) : ''; ?></h1>
            <p>
                <?= esc($spot['categoria'] ?? 'Serviços'); ?>
                <?php if ($cidadePrincipal): ?>
                    em <?= esc($cidadePrincipal['cidade']); ?> e região.
                <?php endif; ?>
            </p>
            <div class="header-cta">
                <?php if (! empty($spot['whatsapp'])): ?>
                    <a class="btn-whatsapp" href="https://wa.me/<?= preg_replace('/\D+/', '', $spot['whatsapp']); ?>" target="_blank" rel="noopener">
                        <span class="material-symbols-outlined" style="font-size:18px;">chat</span>
                        WhatsApp
                    </a>
                    <?php if (! empty($spot['telefone'])): ?>
                        <a class="btn-call" href="tel:<?= esc($spot['telefone']); ?>">
                            <span class="material-symbols-outlined" style="font-size:18px;">call</span>
                            Ligar
                        </a>
                    <?php endif; ?>
                <?php elseif (! empty($spot['telefone'])): ?>
                    <a class="btn-call" href="tel:<?= esc($spot['telefone']); ?>">
                        <span class="material-symbols-outlined" style="font-size:18px;">call</span>
                        Ligar agora
                    </a>
                <?php endif; ?>

                <?php if (! empty($spot['site'])): ?>
                    <a class="btn-outline" href="<?= esc($spot['site']); ?>" target="_blank" rel="noopener">
                        <span class="material-symbols-outlined" style="font-size:18px;margin-right:4px;">language</span>
                        Visitar site oficial
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<main>
    <section class="card grid">
        <div>
            <div class="label">Sobre o negócio</div>
            <h2><?= esc($nomePublico); ?></h2>
            <?php if (! empty($spot['texto_empresa'])): ?>
                <p><?= nl2br(esc($spot['texto_empresa'])); ?></p>
            <?php elseif (! empty($spot['descricao'])): ?>
                <p><?= esc($spot['descricao']); ?></p>
            <?php else: ?>
                <p>Atendimento profissional com foco em qualidade e satisfação do cliente.</p>
            <?php endif; ?>

            <?php if (! empty($cidades)): ?>
                <p class="meta">
                    Atendemos clientes em:
                </p>
                <ul class="cidades">
                    <?php foreach ($cidades as $c): ?>
                        <li><?= esc(($c['cidade'] ?? '') . (isset($c['estado']) ? ' - ' . $c['estado'] : '')); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <aside>
            <div class="label">Contato</div>
            <div class="contact">
                <?php if (! empty($spot['telefone'])): ?>
                    <p>
                        <strong>Telefone:</strong>
                        <a href="tel:<?= esc($spot['telefone']); ?>">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:2px;">call</span>
                            <?= esc($spot['telefone']); ?>
                        </a>
                    </p>
                <?php endif; ?>
                <?php if (! empty($spot['whatsapp'])): ?>
                    <p>
                        <strong>WhatsApp:</strong>
                        <a href="https://wa.me/<?= preg_replace('/\D+/', '', $spot['whatsapp']); ?>" target="_blank" rel="noopener">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:2px;">chat</span>
                            Falar no Whats
                        </a>
                    </p>
                <?php endif; ?>
                <?php if (! empty($spot['instagram'])): ?>
                    <p>
                        <strong>Instagram:</strong>
                        <a href="<?= esc($spot['instagram']); ?>" target="_blank" rel="noopener">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:2px;">photo_camera</span>
                            Ver perfil
                        </a>
                    </p>
                <?php endif; ?>
                <?php if (! empty($spot['facebook'])): ?>
                    <p>
                        <strong>Facebook:</strong>
                        <a href="<?= esc($spot['facebook']); ?>" target="_blank" rel="noopener">
                            <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:2px;">group</span>
                            Ver página
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </aside>
    </section>

    <section class="card">
        <div class="label">Conteúdo otimizado</div>
        <p>
            <?= esc($nomePublico); ?>
            <?php if ($cidadePrincipal): ?>
                atende <?= esc($cidadePrincipal['cidade']); ?> e região,
            <?php else: ?>
                oferece atendimento na região,
            <?php endif; ?>
            com foco em <?= esc($spot['servico_principal'] ?: ($spot['categoria'] ?? 'serviços especializados')); ?>.
        </p>
        <?php if (! empty($spot['texto_servicos'])): ?>
            <p><?= nl2br(esc($spot['texto_servicos'])); ?></p>
        <?php endif; ?>
        <?php if (! empty($spot['texto_diferenciais'])): ?>
            <p><strong>Diferenciais:</strong> <?= nl2br(esc($spot['texto_diferenciais'])); ?></p>
        <?php endif; ?>

        <?php if (! empty($nomesServicosSeo)): ?>
            <p>
                Entre os serviços mais procurados estão
                <strong><?= esc(implode(', ', $nomesServicosSeo)); ?></strong>
                <?php if ($cidadePrincipal): ?>
                    para clientes em <?= esc($cidadePrincipal['cidade']); ?> e região.
                <?php else: ?>
                    para clientes da região.
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if (! empty($nomesProdutosSeo)): ?>
            <p>
                Nos produtos, ganham destaque
                <strong><?= esc(implode(', ', $nomesProdutosSeo)); ?></strong>,
                sempre com foco em qualidade e ótimo custo-benefício.
            </p>
        <?php endif; ?>
        <?php if (! empty($cidades) && count($cidades) > 1): ?>
            <p>
                Entre as principais cidades atendidas estão:
                <?php
                    $nomes = array_map(static function ($c) {
                        return ($c['cidade'] ?? '') . (isset($c['estado']) ? ' - ' . $c['estado'] : '');
                    }, $cidades);
                    echo esc(implode(', ', $nomes));
                ?>.
            </p>
        <?php endif; ?>
        <?php if (! empty($spot['dias_funcionamento']) || ! empty($spot['horarios_funcionamento'])): ?>
            <p>
                <strong>Funcionamento:</strong>
                <?= esc(trim(($spot['dias_funcionamento'] ?? '') . ' ' . ($spot['horarios_funcionamento'] ?? ''))); ?>
            </p>
        <?php endif; ?>
        <p>
            Fale conosco para saber mais detalhes sobre horários, condições de atendimento e serviços disponíveis na sua região.
        </p>
        <?php if (! empty($servicos_destaque) || ! empty($produtos_destaque)): ?>
            <hr style="border:none;border-top:1px solid rgba(148,163,184,0.35);margin:14px 0;">
        <?php endif; ?>

        <?php if (! empty($servicos_destaque)): ?>
            <div style="margin-bottom:10px;">
                <div class="label">Serviços em destaque</div>
                <ul class="chip-list">
                    <?php foreach ($servicos_destaque as $s): ?>
                        <li class="chip">
                            <?= esc($s['nome']); ?>
                            <?php if (! empty($s['preco_a_partir'])): ?>
                                · a partir de R$ <?= number_format((float) $s['preco_a_partir'], 2, ',', '.'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="section-link">
                    <a href="<?= site_url('spot/' . $spot['slug'] . '/servicos'); ?>">
                        Ver todos os serviços oferecidos →
                    </a>
                </p>
            </div>
        <?php endif; ?>

        <?php if (! empty($produtos_destaque)): ?>
            <div style="margin-top:6px;">
                <div class="label">Produtos em destaque</div>
                <ul class="chip-list">
                    <?php foreach ($produtos_destaque as $p): ?>
                        <li class="chip">
                            <?= esc($p['nome']); ?>
                            <?php if (! empty($p['preco'])): ?>
                                · R$ <?= number_format((float) $p['preco'], 2, ',', '.'); ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <p class="section-link">
                    <a href="<?= site_url('spot/' . $spot['slug'] . '/produtos'); ?>">
                        Ver todos os produtos →
                    </a>
                </p>
            </div>
        <?php endif; ?>

        <?php
        // Monta pequena galeria com imagens de serviços/produtos em destaque
        $galeriaItens = [];
        if (! empty($servicos_destaque)) {
            foreach ($servicos_destaque as $s) {
                if (! empty($s['imagens'])) {
                    $imgs = json_decode($s['imagens'], true);
                    if (is_array($imgs)) {
                        $imgs = array_values(array_filter($imgs));
                        if (! empty($imgs[0])) {
                            $galeriaItens[] = [
                                'tipo' => 'Serviço',
                                'nome' => $s['nome'] ?? '',
                                'src'  => base_url($imgs[0]),
                            ];
                        }
                    }
                }
            }
        }
        if (! empty($produtos_destaque)) {
            foreach ($produtos_destaque as $p) {
                if (! empty($p['imagens'])) {
                    $imgs = json_decode($p['imagens'], true);
                    if (is_array($imgs)) {
                        $imgs = array_values(array_filter($imgs));
                        if (! empty($imgs[0])) {
                            $galeriaItens[] = [
                                'tipo' => 'Produto',
                                'nome' => $p['nome'] ?? '',
                                'src'  => base_url($imgs[0]),
                            ];
                        }
                    }
                }
            }
        }
        $galeriaItens = array_slice($galeriaItens, 0, 4);
        ?>

        <?php if (! empty($galeriaItens)): ?>
            <div style="margin-top:10px;">
                <div class="label">Alguns exemplos em imagens</div>
                <div class="gallery-grid">
                    <?php foreach ($galeriaItens as $item): ?>
                        <article class="gallery-item">
                            <div class="gallery-img-wrapper">
                                <img
                                    src="<?= esc($item['src']); ?>"
                                    alt="<?= esc(trim(($item['tipo'] ?? '') . ' ' . ($item['nome'] ?? '') . ' de ' . $nomePublico . ($cidadePrincipal ? ' em ' . $cidadePrincipal['cidade'] : ''))); ?>"
                                    loading="lazy"
                                >
                            </div>
                            <div class="gallery-meta">
                                <p class="gallery-meta-title"><?= esc($item['nome']); ?></p>
                                <p class="gallery-meta-type"><?= esc($item['tipo']); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <?php if ($mapLinkCustom): ?>
        </main>
        <section class="map-full">
            <div class="map-full-inner">
                <div class="map-card">
                    <h2>Como chegar</h2>
                    <?php if ($temEnderecoMapa): ?>
                        <p><?= esc($enderecoMapa); ?></p>
                    <?php endif; ?>
                    <a href="<?= esc($mapLinkCustom); ?>" target="_blank" rel="noopener" class="map-button">
                        <span class="material-symbols-outlined" style="font-size:18px;margin-right:4px;">pin_drop</span>
                        Abrir no Google Maps
                    </a>
                </div>
            </div>
        </section>
    <?php else: ?>
        </main>
    <?php endif; ?>
</body>
</html>

