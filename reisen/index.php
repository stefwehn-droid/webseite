<?php
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/reisen.php';

$pageTitle = 'Reiseberichte';
$pageDesc  = 'Reiseberichte 2020–2024 aus USA, Kanada und Mexiko.';
$active    = 'reisen';
require __DIR__ . '/../inc/header.php';
?>
<div class="page-head">
    <h1>Reiseberichte</h1>
    <p>My travel blogs 2020 – 2024 (USA, Canada, Mexico)</p>
</div>
<div class="container section">
    <div class="cards">
        <?php foreach (REISEN as $slug => $r): ?>
            <a class="card" href="<?= h(url('/reisen/bericht.php?slug=' . $slug)) ?>">
                <div class="card-img" style="background-image:url('<?= h(url($r['cover'])) ?>')"></div>
                <div class="card-body">
                    <h2><?= h($r['title']) ?></h2>
                    <p><?= h($r['desc']) ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php require __DIR__ . '/../inc/footer.php'; ?>
