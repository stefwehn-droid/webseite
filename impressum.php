<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Impressum';
$active    = '';
require __DIR__ . '/inc/header.php';
?>
<div class="page-head"><h1>Impressum</h1></div>
<div class="container narrow section">
    <div class="legal">
        <h2>Angaben gemäß § 5 DDG</h2>
        <p>Stefan Wehner<br>Kurstrasse 36<br>53175 Berlin</p>

        <h2>Kontakt</h2>
        <p>E-Mail: <a href="mailto:<?= h(SITE_MAIL) ?>"><?= h(SITE_MAIL) ?></a><br>Telefon: auf Anfrage</p>

        <h2>Verantwortlich für den Inhalt nach § 18 Abs. 2 MStV</h2>
        <p>Stefan Wehner<br>(Anschrift wie oben)</p>

        <h2>Haftungsausschluss</h2>
        <p class="small">Trotz sorgfältiger inhaltlicher Kontrolle übernehme ich keine Haftung für die Inhalte externer Links. Für den Inhalt der verlinkten Seiten sind ausschließlich deren Betreiber verantwortlich.</p>
    </div>
    <p style="text-align:center;margin-top:24px"><a class="btn" href="<?= h(url('/index.php')) ?>">← Zurück zur Startseite</a></p>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
