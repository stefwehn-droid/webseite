<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/reisen.php';

$slug = isset($_GET['slug']) ? (string) $_GET['slug'] : '';
if (!isset(REISEN[$slug])) {
    http_response_code(404);
    $pageTitle = 'Bericht nicht gefunden';
    $active    = 'reisen';
    require __DIR__ . '/../inc/header.php';
    echo '<div class="page-head"><h1>Bericht nicht gefunden</h1><p><a href="' . h(url('/reisen/index.php')) . '">Zur Übersicht der Reiseberichte</a></p></div>';
    require __DIR__ . '/../inc/footer.php';
    exit;
}

$r      = REISEN[$slug];
$keys   = array_keys(REISEN);
$pos    = array_search($slug, $keys, true);
$prev   = $pos > 0 ? $keys[$pos - 1] : null;
$next   = $pos < count($keys) - 1 ? $keys[$pos + 1] : null;
$docUrl = 'https://docs.google.com/document/d/' . rawurlencode($r['doc']) . '/preview';

$pageTitle = 'Reisebericht ' . $r['title'];
$pageDesc  = $r['desc'];
$active    = 'reisen';
require __DIR__ . '/../inc/header.php';
?>
<div class="trip-hero" style="background-image:url('<?= h(url($r['cover'])) ?>')"></div>

<div class="container" style="margin-top:-90px;position:relative;z-index:1">
    <div class="page-head" style="padding-top:0">
        <a class="crumb" href="<?= h(url('/reisen/index.php')) ?>">← Alle Reiseberichte</a>
        <h1><?= h($r['title']) ?></h1>
        <p><?= h($r['desc']) ?></p>
    </div>

    <div class="trip-actions">
        <?php if ($prev): ?><a class="btn" href="<?= h(url('/reisen/bericht.php?slug=' . $prev)) ?>">← <?= h(REISEN[$prev]['title']) ?></a><?php else: ?><span></span><?php endif; ?>
        <a class="btn btn-primary" href="<?= h($docUrl) ?>" target="_blank" rel="noopener">In neuem Tab öffnen</a>
        <?php if ($next): ?><a class="btn" href="<?= h(url('/reisen/bericht.php?slug=' . $next)) ?>"><?= h(REISEN[$next]['title']) ?> →</a><?php else: ?><span></span><?php endif; ?>
    </div>

    <iframe class="doc-frame" src="<?= h($docUrl) ?>" title="Reisebericht <?= h($r['title']) ?>" loading="lazy"></iframe>
    <p class="small" style="text-align:center;margin-top:10px">Der Bericht wird von Google Docs eingebettet.</p>
</div>
<?php require __DIR__ . '/../inc/footer.php'; ?>
