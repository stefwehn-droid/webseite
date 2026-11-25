# stefan-wehner.de – Webseite

Quellcode von **www.stefan-wehner.de** (Stefan Wehner Fotografie). Stand: Export vom Server am 25.11.2026.

## Aufbau

| Pfad | Inhalt |
|---|---|
| `inc/config.php` | **Zentrale Einstellungen**: Navigation, Galerien, Shop-Texte, Produkte, Hero-Bild |
| `inc/header.php`, `inc/footer.php` | Gemeinsamer Kopf (Menü, Promo-Leiste) und Fuß aller Seiten |
| `inc/functions.php` | Galerie-Logik, Vorschaubilder, Hero-Bild-Cache |
| `inc/reisen.php` | Liste der Reiseberichte |
| `inc/shop-band.php` | Kalender-Werbeband |
| `index.php` | Startseite |
| `usa.php`, `afrika.php`, `botanical-garden.php`, `diverses.php` | Galerien |
| `reisen/index.php`, `reisen/bericht.php`, `reisen/*.html` | Reiseberichte (`bericht.php?slug=texas-2024` lädt `texas-2024.html`) |
| `tools.php` + `flipclock.html`, `bildbearbeitung/`, `CopperDreams*.html`, `RasterDreamsC64-1.html` | Tools und Retro-Demos |
| `shop.htm` | Kalender-Shop (PayPal / Stripe) |
| `impressum.php` | Impressum |
| `assets/` | CSS, JS, Schriften |
| `img/`, `kalender-*.jpg` | Bilder für Reiseberichte und Shop |
| `tools/generate_images.php` | Erzeugt `processed/`-Bilder mit Copyright aus den Originalen |

### Fotos liegen nicht im Repository
Die Galerie-Fotos kommen vom NAS (`PHOTO_ROOT = /mnt/fotoalbum`, siehe `inc/config.php`).
`cache/` (Vorschaubilder, Hero-Bild) erzeugt der Server selbst – nicht einchecken.

### Altlasten (vermutlich ungenutzt)
- `seite2.php`, `seite3.php`, `links.php`, `styles.css` – ältere Seitenversionen, nicht im Menü verlinkt
- `mail.php` – unfertiges Kontaktformular mit Platzhaltern, Syntaxfehler, PHPMailer (`vendor/`) fehlt
- `test.php` – Schreibrechte-Test für das NAS

## Lokal testen

```bash
php -S localhost:8000
```
Dann http://localhost:8000/index.php öffnen (Galerien bleiben ohne NAS leer).

## Wichtig
Keine Passwörter oder API-Schlüssel ins Repository schreiben. Zugangsdaten gehören in eine Datei `*.local.php`, die per `.gitignore` ausgeschlossen ist.
