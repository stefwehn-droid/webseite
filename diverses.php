<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Dies & Das von hier und da';
$pageDesc  = 'Bildergalerie: Dies und das von hier und da.';
$active    = 'diverses';
require __DIR__ . '/inc/header.php';

$items = gallery_auto_thumbs(PHOTO_ROOT . '/other', '/fotos/other');
?>
<div class="page-head">
    <h1>Dies &amp; Das</h1>
    <p>Von hier und da</p>
</div>
<div class="container section">
    <?php render_gallery($items, false, true); ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
