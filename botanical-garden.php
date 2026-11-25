<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Botanical Garden NYC, Bronx';
$pageDesc  = 'Fotogalerie aus dem New York Botanical Garden in der Bronx.';
$active    = 'botanik';
require __DIR__ . '/inc/header.php';

$items = gallery_auto_thumbs(PHOTO_ROOT . '/botanicalgarden', '/fotos/botanicalgarden');
?>
<div class="page-head">
    <h1>Botanical Garden</h1>
    <p>New York Botanical Garden, Bronx</p>
</div>
<div class="container section">
    <?php render_gallery($items); ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
