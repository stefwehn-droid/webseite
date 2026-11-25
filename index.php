<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/reisen.php';

$visits = visitor_count(true);

$pageTitle = SITE_NAME;
$active    = 'home';
$noShopBand = true; // Auf der Startseite gibt es einen eigenen, größeren Shop-Abschnitt
require __DIR__ . '/inc/header.php';

// Hero-Bild
$heroUrl = '';
if (HERO_IMAGE === 'auto') {
    $heroUrl = hero_cached_url(PHOTO_ROOT . '/processed', '/processed');
} elseif (HERO_IMAGE !== '') {
    $heroUrl = HERO_IMAGE;
}

// Neueste Reiseberichte (die letzten drei Einträge in inc/reisen.php)
$latest = array_slice(REISEN, -3, 3, true);
$latest = array_reverse($latest, true);
?>

<section class="hero<?= $heroUrl ? ' has-image' : '' ?>" id="home"<?= $heroUrl ? ' style="--hero:url(\'' . h($heroUrl) . '\')"' : '' ?>>
    <div class="hero-inner">
        <p class="eyebrow">Fotografie · Reisen · Fotokalender</p>
        <h1>Stefan Wehner</h1>
        <p class="lead">Landschaften, Straßen und Orte zwischen Amerika und Afrika.<br class="br-d">Selbst bereist, selbst fotografiert.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= h(url(SHOP_URL)) ?>">Fotokalender 2027</a>
            <a class="btn btn-light" href="#galerien">Galerien entdecken</a>
        </div>
    </div>
</section>

<!-- ============ Shop ============ -->
<section class="section shop" id="shop">
    <div class="container">
        <div class="shop-head">
            <p class="eyebrow">Fotokalender 2027</p>
            <h2>Die Fotografie für Zuhause</h2>
            <p>Gedruckt auf Fine-Art-Papier mit brillanten Farben, wahlweise als Tischkalender oder DIN-A4-Wandkalender. Ab 16,99&nbsp;€ inklusive Versand.</p>
            <p class="notice-inline"><span class="dot"></span> Vorbestellung läuft · Auslieferung im November/Dezember 2026</p>
        </div>
        <div class="products">
            <?php foreach (PRODUCTS as $p): ?>
                <a class="product<?= $p['badge'] ? ' is-featured' : '' ?>" href="<?= h(url(SHOP_URL)) ?>">
                    <div class="product-img">
                        <img src="<?= h(url($p['img'])) ?>" alt="<?= h($p['title']) ?>" loading="lazy" width="900" height="675">
                        <?php if ($p['badge']): ?><span class="badge"><?= h($p['badge']) ?></span><?php endif; ?>
                    </div>
                    <div class="product-body">
                        <p class="meta"><?= h($p['meta']) ?></p>
                        <h3><?= h($p['title']) ?></h3>
                        <p><?= h($p['text']) ?></p>
                        <div class="buy"><span class="price"><?= h($p['price']) ?> <small>inkl. Versand</small></span><span class="go">Bestellen →</span></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <p class="shop-note">Sicher bezahlen mit PayPal, Kreditkarte, Klarna u. a. · Überweisung auf Anfrage per Mail</p>
    </div>
</section>

<!-- ============ Galerien ============ -->
<section class="section" id="galerien">
    <div class="container">
        <h2 class="section-title">Galerien</h2>
        <div class="tiles">
            <?php foreach (GALLERIES as $key => $g):
                $cover = cover_url($g[3], $g[4]); ?>
                <a class="tile tile-<?= h($key) ?>" href="<?= h(url($g[0])) ?>">
                    <span class="tile-img"<?= $cover ? ' style="background-image:url(\'' . h($cover) . '\')"' : '' ?>></span>
                    <span class="tile-text">
                        <span class="tile-title"><?= h($g[1]) ?></span>
                        <span class="tile-sub"><?= h($g[2]) ?></span>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ Reiseberichte ============ -->
<section class="section" id="reisen">
    <div class="container">
        <h2 class="section-title">Reiseberichte</h2>
        <div class="cards">
            <?php foreach ($latest as $slug => $r): ?>
                <a class="card" href="<?= h(url('/reisen/bericht.php?slug=' . $slug)) ?>">
                    <div class="card-img" style="background-image:url('<?= h(url($r['cover'])) ?>')"></div>
                    <div class="card-body">
                        <h2><?= h($r['title']) ?></h2>
                        <p><?= h($r['desc']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <p style="text-align:center;margin-top:44px"><a class="btn" href="<?= h(url('/reisen/index.php')) ?>">Alle Reiseberichte</a></p>
    </div>
</section>

<!-- ============ Kontakt ============ -->
<section class="section" id="contact" style="padding-top:0">
    <div class="container narrow">
        <div class="contact">
            <h2>Kontakt</h2>
            <p class="small" style="margin:0 0 10px">Fragen zu Bestellungen oder Bildern? Schreib mir.</p>
            <a href="mailto:<?= h(SITE_MAIL) ?>"><?= h(SITE_MAIL) ?></a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/inc/footer.php'; ?>
