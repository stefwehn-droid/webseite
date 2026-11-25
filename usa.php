<?php
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'fifty slides of USA';
$pageDesc  = 'Fotogalerie USA von ' . SITE_AUTHOR . ': Roadtrips, Wüstenstraßen, Städte und Küsten.';
$active    = 'usa';
require __DIR__ . '/inc/header.php';

$items = gallery_from_pairs('/var/www/html/cache/thumbs/usa', '/cache/thumbs/usa', '/processed');
?>
<div class="page-head">
    <p class="eyebrow">Galerie</p>
    <h1>fifty slides of <em>USA</em></h1>
    <p>Fotografien von <?= h(SITE_AUTHOR) ?></p>
</div>

<section class="section" style="padding-bottom:0">
    <div class="container narrow">
        <div class="about">
            <p>Ein mehrjähriger Aufenthalt in den USA eröffnet die seltene Gelegenheit, ein so facettenreiches Land fernab oberflächlicher Klischees zu erfahren. Meine beruflichen Verpflichtungen boten den Rahmen für eine tiefgehende Erkundung, die mich durch die unterschiedlichsten Regionen des Landes führte. Der stetige Wandel zwischen den urbanen Zentren des Ostens, den endlosen Weiten des Südwestens, der rauen Natur Colorados und der kalifornischen Pazifikküste prägte meinen fotografischen Blick nachhaltig.</p>
            <p>Mit diesem Portfolio möchte ich diese facettenreiche Reise in ihrer ganzen Tiefe widerspiegeln. Die Aufnahmen entstanden aus dem künstlerischen und dokumentarischen Anspruch heraus, mehr als nur flüchtige Ansichten festzuhalten: Es geht um authentische Momentaufnahmen, die geografische Dimensionen spürbar machen, architektonische und landschaftliche Kontraste einfangen und den Betrachter auf eine anspruchsvolle visuelle Entdeckungsreise mitnehmen.</p>
        </div>
    </div>
</section>

<section class="section" id="galerie">
    <div class="container">
        <?php
        if (!is_dir('/var/www/html/cache/thumbs/usa')) {
            echo '<p class="notice">Die Galerie ist gerade nicht erreichbar. Bitte später noch einmal versuchen.</p>';
        } else {
            render_gallery($items, true);
        }
        ?>
    </div>
</section>
<?php require __DIR__ . '/inc/footer.php'; ?>
