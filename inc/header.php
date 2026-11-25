<?php
/**
 * Gemeinsamer Seitenkopf.
 * Vorher setzen: $pageTitle (string), $active (Nav-Key), optional $pageDesc, $bodyClass, $extraHead
 */
require_once __DIR__ . '/functions.php';

$pageTitle = isset($pageTitle) ? $pageTitle : SITE_NAME;
$pageDesc  = isset($pageDesc)  ? $pageDesc  : 'Fotografie, Reiseberichte und Fotokalender 2027 von ' . SITE_AUTHOR . ': USA, Afrika und Orte dazwischen.';
$active    = isset($active)    ? $active    : '';
$bodyClass = isset($bodyClass) ? $bodyClass : '';
$fullTitle = ($pageTitle === SITE_NAME) ? SITE_NAME : $pageTitle . ' · ' . SITE_NAME;
?><!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($fullTitle) ?></title>
    <meta name="description" content="<?= h($pageDesc) ?>">
    <meta name="theme-color" content="#0d0d0e">
    <script>document.documentElement.className += " js";</script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🇺🇸</text></svg>">
    <link rel="stylesheet" href="<?= h(asset('assets/css/site.css')) ?>">
    <?= isset($extraHead) ? $extraHead : '' ?>
</head>
<body class="<?= h($bodyClass) ?>">
<a class="skip" href="#main">Zum Inhalt springen</a>

<?php if (PROMO_TEXT !== ''): ?>
<div class="promo-bar"><a href="<?= h(url(SHOP_URL)) ?>"><span class="promo-tag">NEU</span> <?= h(PROMO_TEXT) ?> <span aria-hidden="true">→</span></a></div>
<?php endif; ?>

<header class="site-nav" id="top">
    <div class="nav-inner">
        <a class="brand" href="<?= h(url('/index.php')) ?>"><?= h(SITE_AUTHOR) ?> <em>Fotografie</em></a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="Menü öffnen">
            <span></span><span></span><span></span>
        </button>
        <nav id="nav-links" class="nav-links" aria-label="Hauptnavigation">
            <?php foreach (NAV as $key => $item):
                $children = isset($item[2]) ? $item[2] : [];
                $isActive = ($key === $active) || in_array($active, array_filter($children, 'is_string'), true);
            ?>
                <?php if ($children): ?>
                <div class="has-sub">
                    <a href="<?= h(url($item[0])) ?>"<?= $isActive ? ' aria-current="true"' : '' ?>><?= h($item[1]) ?> <span class="caret" aria-hidden="true">▾</span></a>
                    <div class="subnav">
                        <?php foreach ($children as $ck): $g = is_string($ck) ? GALLERIES[$ck] : $ck; ?>
                            <a href="<?= h(url($g[0])) ?>"<?= $ck === $active ? ' aria-current="page"' : '' ?>><?= h($g[1]) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php else: ?>
                    <a href="<?= h(url($item[0])) ?>"<?= $key === $active ? ' aria-current="page"' : '' ?>><?= h($item[1]) ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            <a class="nav-cta" href="<?= h(url(SHOP_URL)) ?>"><?= h(SHOP_LABEL) ?> <span aria-hidden="true">→</span></a>
        </nav>
    </div>
</header>

<main id="main">
