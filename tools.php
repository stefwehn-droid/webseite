<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle  = 'Tools';
$pageDesc   = 'Kleine Werkzeuge: Watchface und Bildbearbeitung.';
$active     = 'tools';
$noShopBand = true;
require __DIR__ . '/inc/header.php';

$tools = [
    ['/flipclock.html', 'Watchface', 'Eine Flip-Uhr als Vollbild-Anzeige, zum Beispiel für Tablet oder Bildschirm.'],
    ['/bildbearbeitung/stefansbildbearbeitung.html', 'Bildbearbeitung', 'Stefans Bildbearbeitung direkt im Browser.'],
];
?>
<div class="page-head">
    <p class="eyebrow">Extras</p>
    <h1>Tools</h1>
    <p>Kleine Werkzeuge von <?= h(SITE_AUTHOR) ?></p>
</div>
<div class="container section">
    <div class="tool-grid">
        <?php foreach ($tools as $t): ?>
            <a class="tool" href="<?= h(url($t[0])) ?>">
                <h2><?= h($t[1]) ?></h2>
                <p><?= h($t[2]) ?></p>
                <span class="go">Öffnen →</span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
