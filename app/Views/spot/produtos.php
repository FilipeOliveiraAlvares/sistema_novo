<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= esc($titulo_seo); ?></title>
    <meta name="description" content="<?= esc($descricao_seo); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        :root {
            --bg-page: #f1f3f4;
            --bg-header-gradient: linear-gradient(135deg, #ffffff, #e8f0fe);
            --bg-card: #ffffff;
            --border-soft: rgba(218, 220, 224, 0.9);
            --text-main: #202124;
            --text-muted: #5f6368;
            --accent-primary: #1a73e8;
            --accent-primary-dark: #1558b0;
            --accent-secondary: #1a73e8;
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
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            padding: 20px 16px 18px;
        }

        .header-inner {
            max-width: 1024px;
            margin: 0 auto;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: -0.02em;
        }

        header p {
            margin: 4px 0 0;
            font-size: 13px;
            color: var(--text-muted);
        }

        main {
            max-width: 1024px;
            margin: 22px auto 32px;
            padding: 0 16px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 18px;
            margin-top: 12px;
        }

        .product-card {
            background: var(--bg-card);
            border-radius: 18px;
            padding: 14px 14px 16px;
            border: 1px solid var(--border-soft);
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.12);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            background: rgba(22, 163, 74, 0.08);
            color: #15803d;
            font-size: 11px;
            margin-bottom: 6px;
        }

        .product-title {
            font-size: 15px;
            font-weight: 600;
            margin: 0 0 4px;
            color: var(--text-main);
        }

        .product-price {
            font-size: 13px;
            margin: 0 0 6px;
            color: #15803d;
            font-weight: 500;
        }

        .product-desc {
            font-size: 13px;
            margin: 0;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .back-link {
            font-size: 13px;
            color: var(--accent-secondary);
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .media-slider {
            width: 100%;
            margin-bottom: 8px;
        }

        .media-slider-main {
            width: 100%;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.4);
            margin-bottom: 8px;
            background: #f3f4f6;
        }

        .media-slider-main img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .product-card:hover .media-slider-main img {
            transform: scale(1.03);
        }

        .media-slider-thumbs {
            display: flex;
            gap: 6px;
        }

        .media-thumb {
            border: 1px solid transparent;
            padding: 0;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            background: transparent;
        }

        .media-thumb img {
            display: block;
            width: 52px;
            height: 52px;
            object-fit: cover;
        }

        .media-thumb.is-active {
            border-color: var(--accent-secondary);
        }

        @media (max-width: 768px) {
            .header-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            main {
                margin-top: 18px;
            }

            .media-slider-main img {
                height: 220px;
            }
        }
    </style>
</head>
<body>
<?php
    $nomePublico = $spot['nome_fantasia'] ?: $spot['nome'];
?>
<header>
    <div class="header-inner">
        <div>
            <h1>Produtos de <?= esc($nomePublico); ?></h1>
            <p>
                <?= esc($spot['categoria'] ?? 'Produtos'); ?>
            </p>
        </div>
        <div>
            <a href="<?= site_url('spot/' . $spot['slug']); ?>" class="back-link">
                <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:4px;">arrow_back</span>
                Voltar para a página principal
            </a>
        </div>
    </div>
</header>

<main>
    <?php
        $cidadePrincipal = $cidades[0] ?? null;
    ?>
    <section style="background:#ffffff;border-radius:16px;padding:14px 16px;margin-bottom:16px;border:1px solid rgba(218,220,224,0.9);display:flex;align-items:center;gap:12px;">
        <?php if (! empty($spot['logo'])): ?>
            <div style="flex-shrink:0;">
                <img src="<?= esc(base_url($spot['logo'])); ?>" alt="Logo <?= esc($nomePublico); ?>" style="max-height:48px;max-width:140px;object-fit:contain;">
            </div>
        <?php endif; ?>
        <div style="flex:1;min-width:0;">
            <div style="font-size:14px;font-weight:500;color:#202124;margin-bottom:2px;">
                <?= esc($nomePublico); ?>
                <?php if ($cidadePrincipal): ?>
                    <span style="color:#5f6368;font-weight:400;">
                        em <?= esc($cidadePrincipal['cidade']); ?><?= isset($cidadePrincipal['estado']) ? ' - ' . esc($cidadePrincipal['estado']) : ''; ?>
                    </span>
                <?php endif; ?>
            </div>
            <div style="font-size:12px;color:#5f6368;">
                <?= esc($spot['categoria'] ?? 'Produtos'); ?>
            </div>
        </div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;justify-content:flex-end;">
            <?php if (! empty($spot['whatsapp'])): ?>
                <a href="https://wa.me/<?= preg_replace('/\D+/', '', $spot['whatsapp']); ?>" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:4px;padding:6px 10px;border-radius:999px;background:#25d366;color:#fff;font-size:12px;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">chat</span>
                    Whats
                </a>
            <?php endif; ?>
            <?php if (! empty($spot['telefone'])): ?>
                <a href="tel:<?= esc($spot['telefone']); ?>"
                   style="display:inline-flex;align-items:center;gap:4px;padding:6px 10px;border-radius:999px;background:#1a73e8;color:#fff;font-size:12px;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">call</span>
                    Ligar
                </a>
            <?php endif; ?>
            <?php if (! empty($spot['site'])): ?>
                <a href="<?= esc($spot['site']); ?>" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:4px;padding:6px 10px;border-radius:999px;border:1px solid rgba(189,193,198,0.9);background:#fff;color:#202124;font-size:12px;text-decoration:none;">
                    <span class="material-symbols-outlined" style="font-size:16px;">language</span>
                    Site
                </a>
            <?php endif; ?>
        </div>
    </section>

    <p class="subtitle">
        Confira os produtos disponíveis. Para estoque, condições especiais e entrega, fale diretamente com a empresa.
    </p>

    <?php if (! empty($produtos)): ?>
        <div class="products-grid">
            <?php foreach ($produtos as $produto): ?>
                <?php
                    $imagens = [];
                    if (! empty($produto['imagens'])) {
                        $tmp = json_decode($produto['imagens'], true);
                        if (is_array($tmp)) {
                            $imagens = array_values(array_filter($tmp));
                        }
                    }
                    $thumb = $imagens[0] ?? null;
                ?>
                <article class="product-card">
                    <?php if (! empty($imagens)): ?>
                        <div class="media-slider" data-slider-id="produto-<?= esc($produto['id']); ?>">
                            <div class="media-slider-main">
                                <img src="<?= esc(base_url($thumb)); ?>" alt="Produto <?= esc($produto['nome'] ?? ''); ?>">
                            </div>
                            <?php if (count($imagens) > 1): ?>
                                <div class="media-slider-thumbs">
                                    <?php foreach ($imagens as $idx => $img): ?>
                                        <button type="button"
                                                class="media-thumb <?= $idx === 0 ? 'is-active' : ''; ?>"
                                                data-index="<?= $idx; ?>"
                                                data-src="<?= esc(base_url($img)); ?>">
                                            <img src="<?= esc(base_url($img)); ?>" alt="Thumb produto <?= esc($produto['nome'] ?? ''); ?>">
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="badge">Produto</div>
                    <h2 class="product-title"><?= esc($produto['nome'] ?? 'Produto'); ?></h2>
                    <?php if ($produto['preco'] !== null && $produto['preco'] !== ''): ?>
                        <p class="product-price">Preço: R$ <?= number_format((float) $produto['preco'], 2, ',', '.'); ?></p>
                    <?php endif; ?>
                    <?php if (! empty($produto['descricao_curta'])): ?>
                        <p class="product-desc"><?= esc($produto['descricao_curta']); ?></p>
                    <?php elseif (! empty($produto['descricao_longa'])): ?>
                        <p class="product-desc"><?= nl2br(esc($produto['descricao_longa'])); ?></p>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="subtitle">Os produtos deste cliente ainda não foram cadastrados. Utilize os contatos para saber mais.</p>
    <?php endif; ?>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.media-slider').forEach(function (slider) {
            var mainImg = slider.querySelector('.media-slider-main img');
            var thumbs = slider.querySelectorAll('.media-thumb');

            thumbs.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var src = btn.getAttribute('data-src');
                    if (src && mainImg) {
                        mainImg.src = src;
                    }
                    thumbs.forEach(function (t) {
                        t.classList.remove('is-active');
                    });
                    btn.classList.add('is-active');
                });
            });
        });
    });
</script>
</body>
</html>


