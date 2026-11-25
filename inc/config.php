<?php
/**
 * Zentrale Einstellungen. Hier (und nur hier) Pfade, Navigation und Shop-Texte ändern.
 */

// Basis-URL automatisch ermitteln (z. B. "" im Hauptordner oder "/neueVersion" im Unterordner)
$__root = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
$__here = realpath(dirname(__DIR__));
define('BASE_URL', ($__root && $__here && strpos($__here, $__root) === 0)
    ? str_replace('\\', '/', substr($__here, strlen($__root)))
    : '');
unset($__root, $__here);

const SITE_NAME   = 'Stefan Wehner Fotografie';
const SITE_AUTHOR = 'Stefan Wehner';
const SITE_MAIL   = 'info@stefan-wehner.de';

// Hero-Bild der Startseite: 'auto' = erstes Querformat-Foto aus /processed,
// '' = kein Bild, oder eine URL wie '/processed/mein-foto-bearbeitet.jpg'
const HERO_IMAGE = 'auto';

// Ordner auf dem Server (NAS) -> Dateisystempfade
const PHOTO_ROOT = '/mnt/fotoalbum';

/**
 * Galerien: key => [Seite, Name, Kurztext, Ordner (Dateisystem), Web-Pfad der Bilder]
 * Das erste Bild jedes Ordners wird automatisch als Kachel-Bild der Startseite verwendet.
 */
const GALLERIES = [
    'usa'      => ['/usa.php',               'USA',              'Roadtrips, Wüstenstraßen und Küsten-Highways',  PHOTO_ROOT . '/processed',       '/processed'],
    'afrika'   => ['/afrika.php',            'Africa',           'Abenteuer abseits ausgetretener Pfade',         PHOTO_ROOT . '/Afrika2027',      '/fotos/Afrika2027'],
    'botanik'  => ['/botanical-garden.php',  'Botanical Garden', 'New York Botanical Garden, Bronx',              PHOTO_ROOT . '/botanicalgarden', '/fotos/botanicalgarden'],
    'diverses' => ['/diverses.php',          'Dies & Das',       'Von hier und da',                               PHOTO_ROOT . '/other',           '/fotos/other'],
];

// Navigation: key => [Link, Beschriftung, optional: Unterpunkte (Galerie-Keys)]
const NAV = [
    'home'     => ['/index.php',        'Home'],
    'galerien' => ['/index.php#galerien', 'Galerien', ['usa', 'afrika', 'botanik', 'diverses']],
    'reisen'   => ['/reisen/index.php', 'Reiseberichte'],
    'tools'    => ['/tools.php',        'Tools', [
        ['/flipclock.html',                              'Watchface'],
	['/bildbearbeitung/stefansbildbearbeitung.html', 'Bildbearbeitung'],
	['/CopperDreams-1.html',                           'Amiga500 Demo Copper Dreams'],
	['/CopperDreamsAmiga500-2.html',                           'Amiga500 Demo Neural Core'],
	['/RasterDreamsC64-1.html',                           'C64 Demo'],
    ]],
];

// Shop
const SHOP_URL     = '/shop.htm';
const SHOP_LABEL   = 'Kalender 2027';
const PROMO_TEXT   = 'Fotokalender 2027 jetzt vorbestellen – ab 16,99 € inkl. Versand';

// Produkte (nur zur Anzeige auf der Startseite; Bestellung läuft über shop.htm)
const PRODUCTS = [
    [
        'title' => 'Amerika – 50 Staaten USA',
        'meta'  => 'Tischkalender · 54 Blätter',
        'text'  => 'Jede Woche ein neuer Bundesstaat: 54 selbstbereiste Motive, von einsamen Wüstenstraßen bis zu legendären Küsten-Highways.',
        'price' => '16,99 €',
        'img'   => '/img/kalender/usa.jpg',
        'badge' => '',
    ],
    [
        'title' => 'Best of Afrika',
        'meta'  => 'Wandkalender DIN A4 · 13 Blätter',
        'text'  => 'Abenteuer pur: In jedem Monat ein Highlight aus einem anderen afrikanischen Land, abseits ausgetretener Pfade.',
        'price' => '16,99 €',
        'img'   => '/img/kalender/afrika.jpg',
        'badge' => '',
    ],
    [
        'title' => 'Das Sammler-Set',
        'meta'  => 'Beide Kalender · auf Wunsch signiert',
        'text'  => 'Beide Kalender im Vorteilspaket, das perfekte Geschenk für Reiselustige und Liebhaber echter Dokumentarfotografie.',
        'price' => '30,00 €',
        'img'   => '/img/kalender/set.jpg',
        'badge' => 'Spar-Tipp',
    ],
];
