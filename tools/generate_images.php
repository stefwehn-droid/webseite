<?php
/**
 * Erzeugt aus den Originalen in /mnt/fotoalbum
 *   - processed/<name>-Bearbeitet.jpg  (mit Copyright-Text, 85 %)
 *   - thumbnails/<name>-Bearbeitet.jpg (300 px breit)
 * Nur per SSH aufrufen:  php tools/generate_images.php
 */
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Nur per Kommandozeile.');
}

$sourceDir    = '/mnt/fotoalbum';
$processedDir = $sourceDir . '/processed';
$thumbDir     = $sourceDir . '/thumbnails';
$fontPath     = '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf';
$text         = '© Stefan Wehner';

foreach ([$processedDir, $thumbDir] as $d) {
    if (!is_dir($d) && !mkdir($d, 0755, true)) {
        fwrite(STDERR, "Kann $d nicht anlegen\n");
        exit(1);
    }
}

foreach (glob($sourceDir . '/*.{jpg,jpeg,png}', GLOB_BRACE) as $path) {
    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $img  = ($ext === 'png') ? @imagecreatefrompng($path) : @imagecreatefromjpeg($path);
    if (!$img) {
        echo "Übersprungen: $path\n";
        continue;
    }
    imagettftext($img, 20, 0, 10, 30, imagecolorallocate($img, 255, 255, 255), $fontPath, $text);
    $base = pathinfo($path, PATHINFO_FILENAME);
    imagejpeg($img, "$processedDir/{$base}-Bearbeitet.jpg", 85);
    $thumb = imagescale($img, 300);
    imagejpeg($thumb, "$thumbDir/{$base}-Bearbeitet.jpg", 85);
    imagedestroy($img);
    imagedestroy($thumb);
    echo "OK: $base\n";
}
