<?php
/** Schmales Shop-Banner am Ende jeder Unterseite. Abschalten mit $noShopBand = true; */
if (!empty($noShopBand)) { return; }
?>
<section class="shop-band">
    <div class="container shop-band-inner">
        <img src="<?= h(url('/img/kalender/set.jpg')) ?>" alt="Fotokalender 2027: Amerika und Afrika" loading="lazy" width="900" height="675">
        <div class="shop-band-text">
            <p class="eyebrow">Fotokalender 2027</p>
            <h2>Diese Motive für jeden Tag an der Wand</h2>
            <p>Limitierte Tisch- und Wandkalender auf Fine-Art-Papier, signiert auf Wunsch. Ab 16,99 € inklusive Versand.</p>
            <a class="btn btn-primary" href="<?= h(url(SHOP_URL)) ?>">Zum Kalender-Shop</a>
        </div>
    </div>
</section>
