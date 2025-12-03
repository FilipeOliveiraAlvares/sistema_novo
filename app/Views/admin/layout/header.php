<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Goodex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 0;
            background: #f3f4f6;
            color: #111827;
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-header {
            background: #020617;
            color: #e5e7eb;
            padding: 10px 16px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.4);
        }

        .admin-header-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .admin-brand {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .admin-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .admin-user {
            font-size: 13px;
            color: #9ca3af;
            margin: 0;
        }

        .admin-nav {
            display: flex;
            align-items: center;
            gap: 14px;
            font-size: 13px;
        }

        .admin-nav-link {
            color: #e5e7eb;
            text-decoration: none;
            padding-bottom: 2px;
        }

        .admin-nav-link:hover {
            color: #f9fafb;
        }

        .admin-nav-link--active {
            color: #38bdf8;
            font-weight: 600;
            position: relative;
        }

        .admin-nav-link--active::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -4px;
            width: 100%;
            height: 2px;
            background: #38bdf8;
            border-radius: 999px;
        }

        .admin-main {
            max-width: 1100px;
            margin: 18px auto 32px;
            padding: 0 16px 24px;
            flex: 1;
        }

        .button {
            display: inline-block;
            padding: 8px 14px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .button:hover {
            background: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th,
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f9fafb;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
        }

        .badge-success {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .actions a {
            margin-right: 8px;
            font-size: 13px;
        }

        .errors {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 14px;
        }

        .field {
            margin-bottom: 14px;
        }

        .row {
            display: flex;
            gap: 12px;
        }

        .col {
            flex: 1;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 8px 10px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        .button-primary {
            display: inline-block;
            padding: 8px 16px;
            background: #16a34a;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .button-secondary {
            display: inline-block;
            padding: 8px 16px;
            background: #e5e7eb;
            color: #111827;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 8px;
        }

        .checkbox-inline {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .help {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .admin-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .admin-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .admin-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .admin-card-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="admin-shell">
    <header class="admin-header">
        <div class="admin-header-inner">
            <div class="admin-brand">
                <p class="admin-title">Goodex Admin</p>
                <p class="admin-user">
                    <?= esc(session('user_nome') ?? ''); ?>
                    <?php if (session('user_perfil')): ?>
                        · <?= esc(session('user_perfil')); ?>
                    <?php endif; ?>
                </p>
            </div>
            <?php
            /** @var \CodeIgniter\HTTP\URI $uri */
            $uri = service('uri');
            $segment1 = $uri->getSegment(1);
            $segment2 = $uri->getSegment(2);
            $isSpots = $segment1 === 'admin' && $segment2 === 'spots';
            ?>
            <nav class="admin-nav">
                <a href="<?= site_url('admin/spots'); ?>"
                   class="admin-nav-link <?= $isSpots ? 'admin-nav-link--active' : ''; ?>">
                    Spots
                </a>
                <a href="<?= site_url('logout'); ?>" class="admin-nav-link">
                    Sair
                </a>
            </nav>
        </div>
    </header>
    <main class="admin-main">
