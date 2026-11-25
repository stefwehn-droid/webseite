<?php
require_once __DIR__ . '/config.php';

/** HTML-Escape */
function h($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Interne URL relativ zur Basis der Seite */
function url(string $path): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/** URL einer Asset-Datei mit Änderungszeit (Cache-Busting) */
function asset(string $path): string
{
    $file = dirname(__DIR__) . '/' . $path;
    $v = is_file($file) ? filemtime($file) : 0;
    return url($path) . '?v=' . $v;
}

/** Dateinamen -> lesbarer Titel ("rocky-mountain_np-bearbeitet.jpg" -> "Rocky mountain np") */
function image_title(string $filename): string
{
    $name = pathinfo($filename, PATHINFO_FILENAME);
    $name = str_ireplace('-bearbeitet', '', $name);
    $name = trim(str_replace(['-', '_'], ' ', $name));
    if ($name === '') {
        return '';
    }
    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($name, 1, null, 'UTF-8');
    }
    return ucfirst($name);
}

/** Bilddateien eines Ordners (nur Dateien, natürlich sortiert) */
function list_images(string $dir, array $exts = ['jpg', 'jpeg', 'png', 'gif', 'webp']): array
{
    if (!is_dir($dir)) {
        return [];
    }
    $files = @scandir($dir);
    if ($files === false) {
        return [];
    }
    $out = [];
    foreach ($files as $f) {
        if ($f[0] === '.') {
            continue;
        }
        if (!is_file($dir . '/' . $f)) {
            continue;
        }
        if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), $exts, true)) {
            $out[] = $f;
        }
    }
    natcasesort($out);
    return array_values($out);
}

/** Kleines Vorschaubild erzeugen (GD). Gibt true zurück, wenn $dest danach existiert. */
function make_thumbnail(string $src, string $dest, int $maxWidth = 640, int $quality = 80): bool
{
    if (!function_exists('imagecreatetruecolor')) {
        return false;
    }
    $info = @getimagesize($src);
    if ($info === false) {
        return false;
    }
    switch ($info['mime']) {
        case 'image/jpeg': $img = @imagecreatefromjpeg($src); break;
        case 'image/png':  $img = @imagecreatefrompng($src);  break;
        case 'image/gif':  $img = @imagecreatefromgif($src);  break;
        case 'image/webp': $img = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($src) : false; break;
        default: return false;
    }
    if (!$img) {
        return false;
    }
    $w = imagesx($img);
    $h = imagesy($img);
    $nw = min($w, $maxWidth);
    $nh = (int) floor($h * ($nw / $w));
    $new = imagecreatetruecolor($nw, $nh);
    if ($info['mime'] === 'image/png' || $info['mime'] === 'image/gif') {
        imagealphablending($new, false);
        imagesavealpha($new, true);
    }
    imagecopyresampled($new, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
    $ok = false;
    switch ($info['mime']) {
        case 'image/png':  $ok = @imagepng($new, $dest, 7); break;
        case 'image/gif':  $ok = @imagegif($new, $dest); break;
        case 'image/webp': $ok = function_exists('imagewebp') ? @imagewebp($new, $dest, $quality) : false; break;
        default:           $ok = @imagejpeg($new, $dest, $quality);
    }
    imagedestroy($img);
    imagedestroy($new);
    return $ok && is_file($dest);
}

/** Ein Galerie-Eintrag */
function gallery_item(string $thumbUrl, string $fullUrl, string $title, ?string $thumbFile): array
{
    $w = 0; $h = 0;
    if ($thumbFile && is_file($thumbFile) && ($size = @getimagesize($thumbFile))) {
        $w = (int) $size[0];
        $h = (int) $size[1];
    }
    return [
        'thumb'  => $thumbUrl,
        'full'   => $fullUrl,
        'title'  => $title,
        'w'      => $w,
        'h'      => $h,
        'orient' => ($w && $h) ? ($h > $w ? 'portrait' : 'landscape') : 'landscape',
    ];
}

/**
 * Galerie aus vorbereiteten Vorschaubildern (index.php):
 * Thumbnails und Großbilder liegen in getrennten Ordnern und heißen gleich.
 */
function gallery_from_pairs(string $thumbDir, string $thumbWeb, string $fullWeb): array
{
    $items = [];
    foreach (list_images($thumbDir, ['jpg', 'jpeg', 'png']) as $f) {
        $items[] = gallery_item(
            $thumbWeb . '/' . rawurlencode($f),
            $fullWeb . '/' . rawurlencode($f),
            image_title($f),
            $thumbDir . '/' . $f
        );
    }
    return $items;
}

/**
 * Galerie aus einem Ordner. Die Vorschaubilder kommen aus dem lokalen Cache
 * (/var/www/html/cache/thumbs/<ordnername>, erzeugt von /opt/webtools/build_cache.php).
 * Fehlt eine lokale Kopie, wird das Original als Vorschau verwendet.
 */
function gallery_auto_thumbs(string $dir, string $webPath, int $maxWidth = 640): array
{
    $key      = basename($dir);
    $localDir = '/var/www/html/cache/thumbs/' . $key;
    $items = [];
    foreach (list_images($dir) as $f) {
        $local = $localDir . '/' . $f;
        $has   = is_file($local);
        $items[] = gallery_item(
            $has ? '/cache/thumbs/' . rawurlencode($key) . '/' . rawurlencode($f)
                 : $webPath . '/' . rawurlencode($f),
            $webPath . '/' . rawurlencode($f),
            image_title($f),
            $has ? $local : null
        );
    }
    return $items;
}

/**
 * Titelbild: das erste Querformat aus $dir als verkleinerte lokale Kopie
 * (/var/www/html/cache/hero). Die Auswahl wird in hero.json gemerkt, damit die
 * Startseite das NAS nicht bei jedem Aufruf abfragen muss. Zum Neuwaehlen den
 * Ordner /var/www/html/cache/hero leeren.
 */
function hero_cached_url(string $dir, string $webPath, int $width = 1920): string
{
    $cacheDir = '/var/www/html/cache/hero';
    $meta     = $cacheDir . '/hero.json';
    if (is_file($meta)) {
        $m = json_decode((string) file_get_contents($meta), true);
        if (is_array($m) && !empty($m['file']) && is_file($cacheDir . '/' . $m['file'])) {
            return '/cache/hero/' . rawurlencode($m['file']);
        }
    }
    $orig = first_landscape_url($dir, $webPath);
    if ($orig === '') {
        return '';
    }
    $name = rawurldecode(basename($orig));
    if (!is_dir($cacheDir)) {
        @mkdir($cacheDir, 0775, true);
    }
    $dest = $cacheDir . '/' . $name;
    if (is_dir($cacheDir) && is_writable($cacheDir) && make_thumbnail($dir . '/' . $name, $dest, $width, 82)) {
        @file_put_contents($meta, json_encode(['file' => $name]));
        return '/cache/hero/' . rawurlencode($name);
    }
    return $orig;
}

/** Galerie ausgeben */
function render_gallery(array $items, bool $watermark = false, bool $filters = false): void
{
    if (!$items) {
        echo '<p class="notice">Zurzeit sind keine Bilder verfügbar.</p>';
        return;
    }
    if ($filters) {
        echo '<div class="filters" role="group" aria-label="Bilder filtern">'
           . '<button type="button" class="chip is-active" data-filter="all">Alle</button>'
           . '<button type="button" class="chip" data-filter="landscape">Querformat</button>'
           . '<button type="button" class="chip" data-filter="portrait">Hochformat</button>'
           . '</div>';
    }
    echo '<div class="gallery" id="gallery">';
    foreach ($items as $i) {
        $dim = ($i['w'] && $i['h']) ? ' width="' . $i['w'] . '" height="' . $i['h'] . '"' : '';
        echo '<a class="g-item" href="' . h($i['full']) . '" data-lightbox="album" data-title="' . h($i['title']) . '" data-orient="' . $i['orient'] . '">';
        echo '<img src="' . h($i['thumb']) . '" alt="' . h($i['title']) . '" loading="lazy" decoding="async"' . $dim . '>';
        if ($watermark) {
            echo '<span class="wm" aria-hidden="true">© ' . h(SITE_AUTHOR) . '</span>';
        }
        if ($i['title'] !== '') {
            echo '<span class="cap">' . h($i['title']) . '</span>';
        }
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Besucherzähler (Datei data/counter.txt, mit Dateisperre).
 * $increment = true zählt einen Aufruf (nur auf der Startseite), Bots werden ignoriert.
 */
function visitor_count(bool $increment = false): int
{
    $file = dirname(__DIR__) . '/data/counter.txt';
    $fh = @fopen($file, 'c+');
    if (!$fh) {
        return 0;
    }
    flock($fh, LOCK_EX);
    $n = (int) trim((string) stream_get_contents($fh));
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if ($increment && !preg_match('/bot|crawl|spider|slurp|preview|facebookexternalhit/i', $ua)) {
        $n++;
        ftruncate($fh, 0);
        rewind($fh);
        fwrite($fh, (string) $n);
    }
    flock($fh, LOCK_UN);
    fclose($fh);
    return $n;
}

/** Erstes Bild eines Ordners als Kachelbild (bevorzugt Vorschau aus <ordner>/thumbs). Gibt Web-URL oder '' zurück. */
function cover_url(string $dir, string $webPath): string
{
    $files = list_images($dir);
    if (!$files) {
        return '';
    }
    $f = $files[0];
    if (is_file($dir . '/thumbs/' . $f)) {
        return $webPath . '/thumbs/' . rawurlencode($f);
    }
    return $webPath . '/' . rawurlencode($f);
}

/** Erstes Querformat-Foto aus einem Ordner (prüft höchstens $limit Dateien) – für das Hero-Bild. */
function first_landscape_url(string $dir, string $webPath, int $limit = 15): string
{
    $n = 0;
    foreach (list_images($dir) as $f) {
        if ($n++ >= $limit) {
            break;
        }
        $size = @getimagesize($dir . '/' . $f);
        if ($size && $size[0] > $size[1]) {
            return $webPath . '/' . rawurlencode($f);
        }
    }
    return '';
}
