<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Buscar Empresas - Goodex</title>
    <meta name="description" content="Busque empresas por ramo de atuação, cidade ou nome. Encontre os melhores serviços e produtos na sua região.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        :root {
            --bg-page: #f8fafc;
            --bg-header-gradient: linear-gradient(135deg, #ffffff 0%, #e8f0fe 100%);
            --bg-card: #ffffff;
            --border-soft: rgba(218, 220, 224, 0.6);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --accent-primary: #2563eb;
            --accent-primary-dark: #1d4ed8;
            --accent-hover: #3b82f6;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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
            line-height: 1.6;
        }

        header {
            background: var(--bg-header-gradient);
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            padding: 40px 16px 36px;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 30%, rgba(37, 99, 235, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(29, 78, 216, 0.06) 0%, transparent 50%);
            pointer-events: none;
        }

        .header-inner {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 16px;
        }

        .header-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-primary-dark) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            flex-shrink: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .header-icon:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 12px 28px rgba(37, 99, 235, 0.4);
        }

        .header-icon .material-symbols-outlined {
            font-size: 36px;
            color: #ffffff;
        }

        .header-text {
            flex: 1;
        }

        header h1 {
            margin: 0 0 8px;
            font-size: 36px;
            font-weight: 700;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        header p {
            margin: 0;
            font-size: 16px;
            color: var(--text-muted);
            font-weight: 400;
            line-height: 1.5;
        }

        .header-stats {
            display: flex;
            gap: 24px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(148, 163, 184, 0.15);
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon .material-symbols-outlined {
            font-size: 20px;
            color: var(--accent-primary);
        }

        .stat-content {
            display: flex;
            flex-direction: column;
        }

        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }

        main {
            max-width: 1200px;
            margin: 32px auto 48px;
            padding: 0 16px;
        }

        .search-form {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 28px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 32px;
            border: 1px solid var(--border-soft);
            transition: box-shadow 0.3s ease;
        }

        .search-form:hover {
            box-shadow: var(--shadow-xl);
        }

        .search-form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
            letter-spacing: 0.01em;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            font-size: 14px;
            box-sizing: border-box;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background: #ffffff;
        }

        .btn-search {
            padding: 12px 24px;
            background: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-primary-dark) 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            align-self: flex-end;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        .btn-search:active {
            transform: translateY(0);
        }

        .btn-clear {
            padding: 12px 20px;
            background: #f1f5f9;
            color: #475569;
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .btn-clear:hover {
            background: #e2e8f0;
            color: var(--text-main);
        }

        .results-header {
            margin-bottom: 20px;
            font-size: 15px;
            color: var(--text-muted);
            padding: 12px 0;
        }

        .results-header strong {
            color: var(--text-main);
            font-weight: 600;
            font-size: 16px;
        }

        .results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .result-card {
            background: var(--bg-card);
            border-radius: 18px;
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-soft);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .result-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-primary) 0%, var(--accent-hover) 100%);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .result-card:hover::before {
            transform: scaleX(1);
        }

        .result-title {
            margin: 0 0 12px;
            font-size: 20px;
            font-weight: 700;
            line-height: 1.3;
        }

        .result-title a {
            color: var(--text-main);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .result-title a:hover {
            color: var(--accent-primary);
        }

        .result-meta {
            font-size: 13px;
            color: var(--text-muted);
            margin: 8px 0 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .result-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(29, 78, 216, 0.1) 100%);
            color: var(--accent-primary);
            font-size: 12px;
            font-weight: 500;
            border: 1px solid rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }

        .result-badge:hover {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.15) 0%, rgba(29, 78, 216, 0.15) 100%);
            transform: scale(1.05);
        }

        .result-desc {
            font-size: 14px;
            color: var(--text-main);
            line-height: 1.7;
            margin: 12px 0 16px;
            opacity: 0.9;
        }

        .result-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--accent-primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .result-link:hover {
            gap: 10px;
            color: var(--accent-primary-dark);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
            background: var(--bg-card);
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-soft);
        }

        .no-results-icon {
            font-size: 64px;
            margin-bottom: 16px;
            opacity: 0.4;
        }

        .no-results h2 {
            margin: 0 0 12px;
            font-size: 24px;
            color: var(--text-main);
            font-weight: 600;
        }

        .no-results p {
            font-size: 15px;
            line-height: 1.6;
            max-width: 500px;
            margin: 0 auto;
        }

        .pagination-container {
            margin-top: 40px;
            padding: 20px 0;
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            header {
                padding: 28px 16px 24px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .header-icon {
                width: 56px;
                height: 56px;
            }

            .header-icon .material-symbols-outlined {
                font-size: 32px;
            }

            header h1 {
                font-size: 28px;
            }

            header p {
                font-size: 14px;
            }

            .header-stats {
                flex-direction: column;
                gap: 16px;
                margin-top: 16px;
                padding-top: 16px;
            }

            .stat-item {
                width: 100%;
            }

            .search-form {
                padding: 20px;
            }

            .search-form-grid {
                grid-template-columns: 1fr;
            }

            .btn-search {
                width: 100%;
                justify-content: center;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }

            .result-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-inner">
        <div class="header-content">
            <div class="header-icon">
                <span class="material-symbols-outlined">search</span>
            </div>
            <div class="header-text">
                <h1>Buscar Empresas</h1>
                <p>Encontre empresas por ramo de atuação, cidade ou nome. Descubra os melhores serviços e produtos na sua região.</p>
            </div>
        </div>
        <div class="header-stats">
            <div class="stat-item">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">business</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Resultados</span>
                    <span class="stat-value"><?= isset($total) ? $total : (isset($spots) ? count($spots) : 0); ?></span>
                </div>
            </div>
            <?php if ($q !== '' || $ramo_id !== '' || $cidade_id !== ''): ?>
                <div class="stat-item">
                    <div class="stat-icon">
                        <span class="material-symbols-outlined">filter_alt</span>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">Filtros Ativos</span>
                        <span class="stat-value"><?= ($q !== '' ? 1 : 0) + ($ramo_id !== '' ? 1 : 0) + ($cidade_id !== '' ? 1 : 0); ?></span>
                    </div>
                </div>
            <?php endif; ?>
            <div class="stat-item">
                <div class="stat-icon">
                    <span class="material-symbols-outlined">location_on</span>
                </div>
                <div class="stat-content">
                    <span class="stat-label">Cidades Disponíveis</span>
                    <span class="stat-value">
                        <?php
                        $totalCidades = 0;
                        if (isset($cidades) && is_array($cidades)) {
                            foreach ($cidades as $uf => $cidadesUf) {
                                $totalCidades += count($cidadesUf);
                            }
                        }
                        echo $totalCidades;
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <form method="get" action="<?= site_url('busca'); ?>" class="search-form">
        <div class="search-form-grid">
            <div class="form-group">
                <label for="q">Buscar por texto</label>
                <input type="text" name="q" id="q" value="<?= esc($q ?? ''); ?>" placeholder="Digite o nome da empresa, serviço ou descrição...">
            </div>
            <div class="form-group">
                <label for="ramo_id">Ramo de atuação</label>
                <select name="ramo_id" id="ramo_id">
                    <option value="">-- Todos os ramos --</option>
                    <?php foreach ($ramos as $r): ?>
                        <option value="<?= esc($r['id']); ?>" <?= ($ramo_id ?? '') == $r['id'] ? 'selected' : ''; ?>>
                            <?= esc($r['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cidade_id">Cidade</label>
                <select name="cidade_id" id="cidade_id">
                    <option value="">-- Todas as cidades --</option>
                    <?php foreach ($cidades as $uf => $cidadesUf): ?>
                        <optgroup label="<?= esc($uf); ?>">
                            <?php foreach ($cidadesUf as $cidade): ?>
                                <option value="<?= esc($cidade['id']); ?>" <?= ($cidade_id ?? '') == $cidade['id'] ? 'selected' : ''; ?>>
                                    <?= esc($cidade['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div style="margin-top: 12px; display: flex; gap: 8px; justify-content: flex-end;">
            <button type="submit" class="btn-search">
                <span class="material-symbols-outlined" style="font-size:18px;vertical-align:middle;margin-right:4px;">search</span>
                Buscar
            </button>
            <?php if ($q !== '' || $ramo_id !== '' || $cidade_id !== ''): ?>
                <a href="<?= site_url('busca'); ?>" style="padding: 10px 20px; background: #e5e7eb; color: #111827; text-decoration: none; border-radius: 8px; font-size: 14px; align-self: flex-end;">
                    Limpar
                </a>
            <?php endif; ?>
        </div>
    </form>

    <?php if (! empty($spots)): ?>
        <div class="results-header">
            <strong><?= $total ?? count($spots); ?></strong> empresa(s) encontrada(s)
            <?php if ($q !== '' || $ramo_id !== '' || $cidade_id !== ''): ?>
                com os filtros aplicados
            <?php endif; ?>
        </div>

        <div class="results-grid">
            <?php foreach ($spots as $spot): ?>
                <article class="result-card">
                    <h2 class="result-title">
                        <a href="<?= site_url('spot/' . $spot['slug']); ?>">
                            <?= esc($spot['nome_fantasia'] ?: $spot['nome']); ?>
                        </a>
                    </h2>
                    <div class="result-meta">
                        <?php
                        // Mostra ramo (pode vir de ramo_id ou campo texto ramo)
                        $ramoNome = '';
                        if (! empty($spot['ramo_id']) && isset($ramosMap[$spot['ramo_id']])) {
                            $ramoNome = $ramosMap[$spot['ramo_id']]['nome'];
                        } elseif (! empty($spot['ramo'])) {
                            $ramoNome = $spot['ramo'];
                        }
                        if ($ramoNome !== ''):
                        ?>
                            <span class="result-badge"><?= esc($ramoNome); ?></span>
                        <?php endif; ?>
                        <?php if (! empty($spot['cidade_id']) && isset($cidadesMap[$spot['cidade_id']])): ?>
                            <span class="result-badge">
                                📍 <?= esc($cidadesMap[$spot['cidade_id']]['nome']); ?> - <?= esc($cidadesMap[$spot['cidade_id']]['uf']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if (! empty($spot['descricao'])): ?>
                        <p class="result-desc"><?= esc(mb_substr($spot['descricao'], 0, 150)); ?><?= mb_strlen($spot['descricao']) > 150 ? '...' : ''; ?></p>
                    <?php elseif (! empty($spot['texto_empresa'])): ?>
                        <p class="result-desc"><?= esc(mb_substr(strip_tags($spot['texto_empresa']), 0, 150)); ?><?= mb_strlen(strip_tags($spot['texto_empresa'])) > 150 ? '...' : ''; ?></p>
                    <?php endif; ?>
                    <a href="<?= site_url('spot/' . $spot['slug']); ?>" class="result-link">
                        Ver detalhes
                        <span class="material-symbols-outlined" style="font-size:18px;">arrow_forward</span>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
            <div style="margin-top: 32px; text-align: center; padding: 20px;">
                <?= $pager->links('default', 'default_full'); ?>
            </div>
        <?php endif; ?>
    <?php elseif ($q !== '' || $ramo_id !== '' || $cidade_id !== ''): ?>
        <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h2 style="margin: 0 0 8px;">Nenhum resultado encontrado</h2>
            <p>Tente ajustar os filtros de busca ou limpar os filtros para ver todas as empresas.</p>
        </div>
    <?php else: ?>
        <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h2 style="margin: 0 0 8px;">Busque empresas</h2>
            <p>Use os filtros acima para encontrar empresas por nome, ramo de atuação ou cidade.</p>
        </div>
    <?php endif; ?>
</main>
</body>
</html>

