<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Afrika 2027';
$pageDesc  = 'Fotogalerie Afrika von ' . SITE_AUTHOR;
$active    = 'afrika';
require __DIR__ . '/inc/header.php';

$items = gallery_auto_thumbs(PHOTO_ROOT . '/Afrika2027', '/fotos/Afrika2027');
?>
<div class="page-head">
    <h1>Afrika 2027</h1>
    <p>Eine Auswahl der Aufnahmen. Bild anklicken für die große Ansicht.</p>
</div>
<div class="container section">
    <?php render_gallery($items, false, true); ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
