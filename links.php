<?php
/*
 * STEFAN'S CONTROL CENTER
 * Version 4.3
 *
 * Internes Monitoring über VPN + externe Cloudflare-Prüfung.
 *
 * Voraussetzungen:
 * - PHP 7.4 oder neuer
 * - PHP-Erweiterung cURL
 * - Ubuntu-Webserver erreicht NAS über VPN: 10.8.0.4
 *
 * WICHTIG:
 * - HTTP-Prüfung kontrolliert veröffentlichte Ports.
 * - Docker-Status ist der Stand der letzten manuellen Inventarisierung.
 * - Es erfolgt KEINE direkte Abfrage des Docker-Daemons.
 */

$nasIp = '10.8.0.4';

/* =========================================
   DIENSTE
========================================= */

$services = [

    // NETZWERK & MONITORING

    'adguard' => [
        'name' => 'AdGuard Home',
        'icon' => '🛡️',
        'url' => 'https://adguard.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8080',
        'group' => 'network'
    ],

    'smokeping' => [
        'name' => 'SmokePing',
        'icon' => '📈',
        'url' => 'https://smokeping.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8084',
        'group' => 'network'
    ],

    'grafana' => [
        'name' => 'Grafana',
        'icon' => '📊',
        'url' => 'https://grafana.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:3000',
        'group' => 'network'
    ],

    'glances' => [
        'name' => 'Glances',
        'icon' => '🖥️',
        'url' => 'https://glances.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:61208',
        'group' => 'network'
    ],

    'ntopng' => [
        'name' => 'ntopng',
        'icon' => '🔍',
        'url' => 'https://ntopng.stefan-wehner.de',
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'network'
    ],

    'checkmk' => [
        'name' => 'Checkmk',
        'icon' => '📡',
        'url' => 'https://checkmk.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:9101',
        'docker_state' => 'stopped',
        'group' => 'network'
    ],

    'prometheus' => [
        'name' => 'Prometheus',
        'icon' => '🔥',
        'url' => null,
        'internal' => null,
        'docker_state' => 'running',
        'uncheckable' => true,
        'group' => 'network'
    ],

    'node-exporter' => [
        'name' => 'Node Exporter',
        'icon' => '📟',
        'url' => null,
        'internal' => null,
        'docker_state' => 'running',
        'uncheckable' => true,
        'group' => 'network'
    ],

    'uptime-kuma' => [
        'name' => 'Uptime Kuma',
        'icon' => '⏱️',
        'url' => null,
        'internal' => 'http://10.8.0.4:3005',
        'docker_state' => 'stopped',
        'group' => 'network'
    ],

    'logs' => [
        'name' => 'Logs / Dozzle',
        'icon' => '📋',
        'url' => 'https://logs.stefan-wehner.de',
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'network'
    ],


    // FOTOS & BILDVERWALTUNG

    'immich' => [
        'name' => 'Immich',
        'icon' => '📸',
        'url' => 'https://immich.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:2299',
        'group' => 'photos'
    ],

    'immichframe' => [
        'name' => 'ImmichFrame',
        'icon' => '🖼️',
        'url' => 'https://immichframe.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8088',
        'group' => 'photos'
    ],

    'immich-kiosk' => [
        'name' => 'Immich Kiosk',
        'icon' => '🎞️',
        'url' => 'https://immich-kiosk.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8085',
        'group' => 'photos'
    ],

    'bildbearbeitung' => [
        'name' => 'Stefans Bildbearbeitung',
        'icon' => '🎨',
        'url' => 'https://stefan-wehner.de/bildbearbeitung/stefansbildbearbeitung.html',
        'internal' => null,
        'group' => 'photos'
    ],

    'diashow' => [
        'name' => 'Diashow / Uhr',
        'icon' => '🌄',
        'url' => 'https://diashow.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8086',
        'group' => 'photos'
    ],

    'sqoosh' => [
        'name' => 'Squoosh',
        'icon' => '✂️',
        'url' => 'https://sqoosh.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8151',
        'docker_state' => 'stopped',
        'group' => 'photos'
    ],

    'photoprism' => [
        'name' => 'PhotoPrism',
        'icon' => '🌅',
        'url' => 'https://photoprism.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:2342',
        'docker_state' => 'stopped',
        'group' => 'photos'
    ],


    // MEDIEN & BIBLIOTHEK

    'tv' => [
        'name' => 'TV',
        'icon' => '📺',
        'url' => 'https://tv.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8087',
        'group' => 'media'
    ],

    'navidrome' => [
        'name' => 'Navidrome',
        'icon' => '🎵',
        'url' => 'https://navidrome.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:4533',
        'group' => 'media'
    ],

    'plex' => [
        'name' => 'Plex',
        'icon' => '🎥',
        'url' => 'https://plex.stefan-wehner.de',
        'internal' => null,
        'group' => 'media'
    ],

    'jellyfin' => [
        'name' => 'Jellyfin',
        'icon' => '🍿',
        'url' => 'https://jellyfin.stefan-wehner.de',
        'internal' => null,
        'docker_state' => 'stopped',
        'disabled' => true,
        'group' => 'media'
    ],

    'calibre' => [
        'name' => 'Calibre',
        'icon' => '📚',
        'url' => 'https://calibre.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8083',
        'docker_state' => 'stopped',
        'group' => 'media'
    ],


    // SMART HOME

    'homeassistant' => [
        'name' => 'Home Assistant',
        'icon' => '🏠',
        'url' => null,
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'smarthome'
    ],

    'tapo-collector' => [
        'name' => 'Tapo Collector',
        'icon' => '💡',
        'url' => 'https://tapo.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8000',
        'group' => 'smarthome'
    ],


    // SERVER & ADMINISTRATION

    'ssh' => [
        'name' => 'SSH Terminal',
        'icon' => '⌨️',
        'url' => 'https://ssh.stefan-wehner.de',
        'internal' => null,
        'group' => 'admin'
    ],

    'desktop' => [
        'name' => 'Desktop',
        'icon' => '🖥️',
        'url' => 'https://desktop.stefan-wehner.de',
        'internal' => null,
        'group' => 'admin'
    ],

    'guacamole' => [
        'name' => 'Guacamole',
        'icon' => '🖱️',
        'url' => 'https://guacamole.stefan-wehner.de',
        'internal' => null,
        'group' => 'admin'
    ],

    'portainer' => [
        'name' => 'Portainer',
        'icon' => '🐳',
        'url' => 'https://portainer.stefan-wehner.de',
        'internal' => 'https://10.8.0.4:9443',
        'docker_state' => 'stopped',
        'group' => 'admin'
    ],

    'atlas' => [
        'name' => 'Atlas',
        'icon' => '🗺️',
        'url' => 'https://atlas.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:3011',
        'docker_state' => 'stopped',
        'group' => 'admin'
    ],

    'nas-center' => [
        'name' => 'NAS Center',
        'icon' => '💾',
        'url' => 'https://nas-center.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:8082',
        'group' => 'admin'
    ],

    'watchtower' => [
        'name' => 'Watchtower',
        'icon' => '🔭',
        'url' => null,
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'admin'
    ],


    // DATEIEN & AUTOMATISIERUNG

    'files' => [
        'name' => 'Files',
        'icon' => '📁',
        'url' => 'https://files.stefan-wehner.de',
        'internal' => null,
        'group' => 'files'
    ],

    'paperless' => [
        'name' => 'Paperless',
        'icon' => '📄',
        'url' => 'https://paperless.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:9999',
        'docker_state' => 'stopped',
        'group' => 'files'
    ],

    'syncthing' => [
        'name' => 'Syncthing',
        'icon' => '🔄',
        'url' => 'https://syncthing.stefan-wehner.de',
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'files'
    ],

    'jdownloader' => [
        'name' => 'JDownloader',
        'icon' => '⬇️',
        'url' => 'https://jdownloader.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:5800',
        'docker_state' => 'stopped',
        'group' => 'files'
    ],

    'makemkv' => [
        'name' => 'MakeMKV',
        'icon' => '💿',
        'url' => null,
        'internal' => null,
        'docker_state' => 'stopped',
        'group' => 'files'
    ],


    // CLOUD & ZUGANG

    'login' => [
        'name' => 'Login Portal',
        'icon' => '🔐',
        'url' => 'https://login.stefan-wehner.de',
        'internal' => 'http://10.8.0.4:9091',
        'group' => 'cloud'
    ],

    'cloudflare' => [
        'name' => 'Cloudflare Dashboard',
        'icon' => '☁️',
        'url' => 'https://dash.cloudflare.com/login',
        'internal' => null,
        'group' => 'cloud'
    ]
];


/* =========================================
   KATEGORIEN
========================================= */

$groups = [
    'network' => [
        'title' => '🌐 Netzwerk & Monitoring'
    ],
    'photos' => [
        'title' => '📷 Fotos & Bildverwaltung'
    ],
    'media' => [
        'title' => '🎬 Medien & Bibliothek'
    ],
    'smarthome' => [
        'title' => '🏠 Smart Home'
    ],
    'admin' => [
        'title' => '⚙️ Server & Administration'
    ],
    'files' => [
        'title' => '📂 Dateien & Automatisierung'
    ],
    'cloud' => [
        'title' => '☁️ Cloud & Zugang'
    ]
];


/* =========================================
   HTTP-STATUS AUSWERTEN
========================================= */

function evaluateHttpResult($http, $errno, $error, $location, $kind)
{
    $isAccess = false;

    if ($http >= 300 && $http < 400 && $location !== '') {
        $lower = strtolower($location);

        $isAccess =
            strpos($lower, '/cdn-cgi/access/login') !== false ||
            strpos($lower, 'cloudflareaccess.com') !== false;
    }

    if ($errno !== 0 || $http === 0) {
        return [
            'status' => 'offline',
            'message' => 'Verbindung fehlgeschlagen'
        ];
    }

    if ($http >= 500) {
        return [
            'status' => 'offline',
            'message' => 'HTTP-Serverfehler'
        ];
    }

    if ($isAccess) {
        return [
            'status' => 'warning',
            'message' => 'Cloudflare Access – Anmeldung erforderlich'
        ];
    }

    if ($kind === 'external' && ($http === 401 || $http === 403)) {
        return [
            'status' => 'warning',
            'message' => 'Authentifizierung erforderlich'
        ];
    }

    if ($http >= 200 && $http < 500) {
        return [
            'status' => 'online',
            'message' => ($http === 401 || $http === 403)
                ? 'Anwendung antwortet – Authentifizierung erforderlich'
                : 'Anwendung antwortet'
        ];
    }

    return [
        'status' => 'offline',
        'message' => 'Keine gültige Antwort'
    ];
}


/* =========================================
   PARALLELE HTTP-PRÜFUNG
========================================= */

function runChecks($targets, $kind)
{
    $multi = curl_multi_init();
    $handles = [];
    $locations = [];
    $results = [];

    foreach ($targets as $name => $url) {

        $locations[$name] = '';

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_USERAGENT => 'Stefan-Control-Center/4.3',
            CURLOPT_HTTPGET => true,

            CURLOPT_SSL_VERIFYPEER =>
                ($kind === 'internal' && strpos($url, 'https://') === 0)
                    ? false : true,

            CURLOPT_SSL_VERIFYHOST =>
                ($kind === 'internal' && strpos($url, 'https://') === 0)
                    ? 0 : 2,

            CURLOPT_WRITEFUNCTION => function ($curl, $data) {
                return strlen($data);
            },

            CURLOPT_HEADERFUNCTION =>
                function ($curl, $header) use (&$locations, $name) {

                    $length = strlen($header);
                    $parts = explode(':', $header, 2);

                    if (
                        count($parts) === 2 &&
                        strtolower(trim($parts[0])) === 'location'
                    ) {
                        $locations[$name] = trim($parts[1]);
                    }

                    return $length;
                }
        ]);

        curl_multi_add_handle($multi, $ch);

        $handles[$name] = [
            'handle' => $ch,
            'url' => $url
        ];
    }

    $running = null;

    do {
        $status = curl_multi_exec($multi, $running);

        if ($running) {
            $selected = curl_multi_select($multi, 0.5);

            if ($selected === -1) {
                usleep(100000);
            }
        }

    } while ($running && $status === CURLM_OK);


    foreach ($handles as $name => $item) {

        $ch = $item['handle'];

        $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $duration = round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        $location = $locations[$name];

        $evaluation = evaluateHttpResult(
            $http,
            $errno,
            $error,
            $location,
            $kind
        );

        $safeLocation = '';

        if ($location !== '') {
            $parts = parse_url($location);

            if ($parts !== false) {
                $safeLocation =
                    ($parts['scheme'] ?? '') . '://' .
                    ($parts['host'] ?? '') .
                    ($parts['path'] ?? '');
            } else {
                $safeLocation = preg_replace('/\?.*$/', '', $location);
            }
        }

        $results[$name] = [
            'status' => $evaluation['status'],
            'message' => $evaluation['message'],
            'http' => $http,
            'ms' => $duration,
            'error' => $error,
            'location' => $safeLocation,
            'errno' => $errno
        ];

        curl_multi_remove_handle($multi, $ch);
        curl_close($ch);
    }

    curl_multi_close($multi);

    return $results;
}


/* =========================================
   HEALTHCHECK API
========================================= */

if (isset($_GET['health'])) {

    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');

    if (!function_exists('curl_init')) {
        http_response_code(500);

        echo json_encode([
            'error' => 'PHP-cURL ist nicht installiert.'
        ]);

        exit;
    }

    $internalTargets = [];
    $externalTargets = [];

    foreach ($services as $name => $service) {

        $isStopped = (($service['docker_state'] ?? '') === 'stopped');
        $isDisabled = !empty($service['disabled']);

        if (
            !$isStopped &&
            !$isDisabled &&
            !empty($service['url'])
        ) {
            $externalTargets[$name] = $service['url'];
        }

        if (
            !$isStopped &&
            !$isDisabled &&
            !empty($service['internal'])
        ) {
            $internalTargets[$name] = $service['internal'];
        }
    }

    $internalResults = runChecks($internalTargets, 'internal');
    $externalResults = runChecks($externalTargets, 'external');

    $results = [];

    foreach ($services as $name => $service) {

        $internal = $internalResults[$name] ?? null;
        $external = $externalResults[$name] ?? null;

        $isStopped = (($service['docker_state'] ?? '') === 'stopped');
        $isDisabled = !empty($service['disabled']);
        $isUncheckable = !empty($service['uncheckable']);

        if ($isStopped) {

            $primary = [
                'status' => 'warning',
                'message' => 'Container laut letzter Docker-Abfrage gestoppt',
                'http' => 0,
                'ms' => 0,
                'error' => '',
                'location' => ''
            ];

        } elseif ($isDisabled) {

            $primary = [
                'status' => 'warning',
                'message' => 'Bewusst deaktiviert',
                'http' => 0,
                'ms' => 0,
                'error' => '',
                'location' => ''
            ];

        } elseif ($isUncheckable) {

            $primary = [
                'status' => 'warning',
                'message' => 'Container läuft – keine HTTP-Prüfung möglich',
                'http' => 0,
                'ms' => 0,
                'error' => '',
                'location' => ''
            ];

        } elseif ($name === 'cloudflare') {

            $primary = $external;

        } elseif ($internal !== null) {

            // Interne Prüfung hat Vorrang, wenn sie eingerichtet ist.
            $primary = $internal;

        } elseif ($external !== null) {

            // Ohne interne Prüfung zählt die externe Prüfung.
            $primary = $external;

        } else {

            $primary = [
                'status' => 'warning',
                'message' => 'Keine Prüfung möglich',
                'http' => 0,
                'ms' => 0,
                'error' => '',
                'location' => ''
            ];
        }

        $results[$name] = [
            'status' => $primary['status'],
            'message' => $primary['message'],
            'internal' => $internal,
            'external' => $external,
            'disabled' => $isDisabled,
            'docker_state' => $service['docker_state'] ?? null,
            'uncheckable' => $isUncheckable
        ];
    }

    echo json_encode([
        'timestamp' => date('c'),
        'version' => '4.3',
        'nas' => $nasIp,
        'vpn' => '10.8.0.0/24',
        'service_count' => count($services),
        'services' => $results
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    exit;
}

?>
<!DOCTYPE html>
<html lang="de">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#090d16">

<title>Stefan's Control Center</title>

<style>

:root {
    --bg: #090d16;
    --panel: #111827;
    --panel2: #172235;
    --border: rgba(255,255,255,.08);
    --text: #edf2fa;
    --muted: #8c9bb1;
    --accent: #6da8ff;
    --green: #38d996;
    --red: #ff6474;
    --yellow: #f3bd55;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    background:
        radial-gradient(ellipse at 50% -15%, #1b3456 0, transparent 48%),
        var(--bg);
    color: var(--text);
    font-family: Inter, "Segoe UI", Arial, sans-serif;
}

.container {
    max-width: 1450px;
    margin: auto;
    padding: 42px 28px 60px;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    margin-bottom: 36px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 17px;
}

.brand-icon {
    width: 58px;
    height: 58px;
    border-radius: 17px;
    background: linear-gradient(135deg, #438cff, #7357df);
    display: grid;
    place-items: center;
    font-size: 27px;
    box-shadow: 0 8px 30px #477fff40;
}

h1 {
    font-size: 27px;
    margin: 0 0 5px;
    letter-spacing: -.7px;
}

.subtitle {
    color: var(--muted);
    font-size: 13px;
}

.header-actions {
    display: flex;
    gap: 10px;
    align-items: center;
}

button {
    border: 1px solid var(--border);
    background: var(--panel2);
    color: var(--text);
    border-radius: 10px;
    padding: 11px 17px;
    cursor: pointer;
    font-size: 13px;
}

button:hover {
    border-color: var(--accent);
}

button:disabled {
    opacity: .6;
    cursor: wait;
}

/* STATISTIK */

.stats {
    display: grid;
    grid-template-columns: repeat(4,1fr);
    gap: 15px;
    margin-bottom: 25px;
}

.stat {
    background: linear-gradient(145deg, #172338, #111827);
    border: 1px solid var(--border);
    border-radius: 15px;
    padding: 19px 21px;
}

.stat-label {
    color: var(--muted);
    font-size: 12px;
    margin-bottom: 9px;
}

.stat-value {
    font-size: 27px;
    font-weight: 700;
}

.stat-value.green { color: var(--green); }
.stat-value.red { color: var(--red); }
.stat-value.yellow { color: var(--yellow); }

/* MONITORING-HINWEIS */

.monitor-info {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 35px;
    padding: 12px 15px;
    background: #111c2d;
    border: 1px solid var(--border);
    border-radius: 11px;
    color: var(--muted);
    font-size: 12px;
}

.monitor-info strong {
    color: var(--text);
}

/* KATEGORIEN */

.section {
    margin: 35px 0;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 16px;
    font-size: 15px;
    font-weight: 650;
}

/* DIENSTKARTEN */

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(245px,1fr));
    gap: 13px;
}

.service {
    position: relative;
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 17px;
    min-height: 96px;
    background: linear-gradient(145deg, var(--panel2), var(--panel));
    border: 1px solid var(--border);
    border-radius: 13px;
    text-decoration: none;
    color: var(--text);
    transition: .2s ease;
}

.service:hover {
    transform: translateY(-3px);
    border-color: #6da8ff80;
    background: #1b2b43;
    box-shadow: 0 9px 25px #0003;
}

.service-static {
    cursor: default;
}

.service-static:hover {
    transform: none;
    border-color: var(--border);
    background: linear-gradient(145deg, var(--panel2), var(--panel));
    box-shadow: none;
}

.service-icon {
    width: 43px;
    height: 43px;
    border-radius: 12px;
    background: #263953;
    display: grid;
    place-items: center;
    font-size: 21px;
    flex-shrink: 0;
}

.service-info {
    min-width: 0;
    flex: 1;
}

.service-name {
    font-size: 14px;
    font-weight: 650;
    margin-bottom: 5px;
}

.service-url {
    font-size: 11px;
    color: var(--muted);
    overflow-wrap: anywhere;
}

.status {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
    color: var(--muted);
    font-size: 10px;
}

.status-detail {
    margin-top: 4px;
    color: #71839d;
    font-size: 10px;
    line-height: 1.4;
}

.dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #68758a;
    flex-shrink: 0;
}

.dot.online {
    background: var(--green);
    box-shadow: 0 0 9px #38d99690;
}

.dot.offline {
    background: var(--red);
    box-shadow: 0 0 9px #ff647490;
}

.dot.warning {
    background: var(--yellow);
    box-shadow: 0 0 9px #f3bd5590;
}

.dot.checking {
    background: var(--accent);
    animation: pulse 1s infinite;
}

@keyframes pulse {
    50% { opacity: .25; }
}

/* FOOTER */

footer {
    border-top: 1px solid var(--border);
    margin-top: 48px;
    padding-top: 20px;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    color: var(--muted);
    font-size: 11px;
}

/* RESPONSIVE */

@media(max-width:700px) {
    .container {
        padding: 25px 16px 40px;
    }

    .stats {
        grid-template-columns: repeat(2,1fr);
    }

    h1 {
        font-size: 22px;
    }

    .grid {
        grid-template-columns: 1fr;
    }
}

</style>
</head>

<body>

<div class="container">

<header>

    <div class="brand">
        <div class="brand-icon">⌘</div>

        <div>
            <h1>Stefan's Control Center</h1>
            <div class="subtitle">
                Persönliches Server- und Service-Dashboard
            </div>
        </div>
    </div>

    <div class="header-actions">
        <button id="refresh" onclick="checkAll()">
            ↻ Jetzt prüfen
        </button>
    </div>

</header>


<div class="stats">

    <div class="stat">
        <div class="stat-label">Dienste gesamt</div>
        <div class="stat-value" id="total">38</div>
    </div>

    <div class="stat">
        <div class="stat-label">Erreichbar</div>
        <div class="stat-value green" id="online">–</div>
    </div>

    <div class="stat">
        <div class="stat-label">Nicht erreichbar</div>
        <div class="stat-value red" id="offline">–</div>
    </div>

    <div class="stat">
        <div class="stat-label">Gestoppt / Warnungen</div>
        <div class="stat-value yellow" id="warning">–</div>
    </div>

</div>


<div class="monitor-info">
    <span>🔗</span>
    <strong>Interne Prüfung:</strong>
    NAS 10.8.0.4 über VPN
    <span>·</span>
    <strong>Externe Prüfung:</strong>
    Cloudflare / öffentliche Domain
    <span>·</span>
    <strong>Docker-Status:</strong>
    letzter manueller Abgleich
</div>


<div id="dashboard">

<?php foreach ($groups as $groupId => $group): ?>

<section class="section">

    <div class="section-title">
        <?= htmlspecialchars($group['title'], ENT_QUOTES, 'UTF-8') ?>
    </div>

    <div class="grid">

        <?php foreach ($services as $name => $service): ?>

            <?php if ($service['group'] !== $groupId) continue; ?>

            <?php
                $hasUrl = !empty($service['url']);
                $tag = $hasUrl ? 'a' : 'div';
                $cardClass = $hasUrl ? 'service' : 'service service-static';
                $urlHost = $hasUrl
                    ? parse_url($service['url'], PHP_URL_HOST)
                    : 'Keine öffentliche URL hinterlegt';
            ?>

            <<?= $tag ?>
                class="<?= $cardClass ?>"
                data-name="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
                <?php if ($hasUrl): ?>
                    href="<?= htmlspecialchars($service['url'], ENT_QUOTES, 'UTF-8') ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                <?php endif; ?>
            >

                <div class="service-icon">
                    <?= htmlspecialchars($service['icon'], ENT_QUOTES, 'UTF-8') ?>
                </div>

                <div class="service-info">

                    <div class="service-name">
                        <?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div class="service-url">
                        <?= htmlspecialchars($urlHost, ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <div class="status">
                        <i class="dot"></i>
                        <span class="status-text">Warte auf Prüfung</span>
                    </div>

                    <div class="status-detail">
                        Intern: – · Cloudflare: –
                    </div>

                </div>

            </<?= $tag ?>>

        <?php endforeach; ?>

    </div>

</section>

<?php endforeach; ?>

</div>


<footer>

    <div>
        Stefan's Control Center · stefan-wehner.de · Version 4.3
    </div>

    <div id="lastcheck">
        Noch keine Statusprüfung durchgeführt
    </div>

</footer>

</div>


<script>

/* =========================================
   STATUSPRÜFUNG
========================================= */

async function checkAll() {

    const button = document.getElementById('refresh');

    button.disabled = true;
    button.textContent = '⟳ Prüfe Dienste ...';

    document.querySelectorAll('.service').forEach(card => {

        card.querySelector('.dot').className = 'dot checking';

        card.querySelector('.status-text').textContent =
            'Prüfung läuft ...';

        card.querySelector('.status-detail').textContent =
            'Interne und externe Prüfung ...';

    });

    try {

        const response = await fetch(
            '?health=1&t=' + Date.now(),
            { cache: 'no-store' }
        );

        if (!response.ok) {
            throw new Error('Healthcheck HTTP ' + response.status);
        }

        const data = await response.json();

        if (!data.services) {
            throw new Error('Ungültige Healthcheck-Antwort');
        }

        let online = 0;
        let offline = 0;
        let warning = 0;

        document.querySelectorAll('.service').forEach(card => {

            const name = card.dataset.name;
            const result = data.services[name];

            const dot = card.querySelector('.dot');
            const label = card.querySelector('.status-text');
            const detail = card.querySelector('.status-detail');

            if (!result) {

                dot.className = 'dot warning';
                label.textContent = 'Keine Prüfdaten';
                detail.textContent = 'Status unbekannt';

                warning++;
                return;
            }

            dot.className = 'dot ' + result.status;
            label.textContent = result.message;

            const internal = result.internal;
            const external = result.external;

            function describe(item) {

                if (!item) {
                    return 'nicht konfiguriert';
                }

                let text = item.message;

                if (item.http) {
                    text += ' · HTTP ' + item.http;
                } else if (item.error) {
                    text += ' · ' + item.error;
                }

                if (item.ms) {
                    text += ' · ' + item.ms + ' ms';
                }

                return text;
            }

            if (result.docker_state === 'stopped') {

                detail.textContent =
                    'Docker: laut letzter Inventarisierung gestoppt';

            } else if (result.disabled) {

                detail.textContent =
                    'Bewusst deaktiviert · keine Prüfung';

            } else if (result.uncheckable) {

                detail.textContent =
                    'Docker: läuft laut letzter Inventarisierung · keine HTTP-Prüfung';

            } else {

                detail.textContent =
                    'Intern: ' + describe(internal) +
                    ' | Cloudflare: ' + describe(external);
            }

            let tooltip = result.message;

            if (result.docker_state === 'stopped') {
                tooltip +=
                    '\n\nDocker-Status: Container war bei der letzten Inventarisierung gestoppt.' +
                    '\nDieser Status wird nicht live vom Docker-Daemon abgefragt.';
            }

            if (internal) {
                tooltip += '\n\nInterne Prüfung: ' + describe(internal);

                if (internal.location) {
                    tooltip += '\nWeiterleitung: ' + internal.location;
                }

                if (internal.error) {
                    tooltip += '\nFehler: ' + internal.error;
                }
            }

            if (external) {
                tooltip += '\n\nExterne Prüfung: ' + describe(external);

                if (external.location) {
                    tooltip += '\nWeiterleitung: ' + external.location;
                }

                if (external.error) {
                    tooltip += '\nFehler: ' + external.error;
                }
            }

            card.title = tooltip;

            if (result.status === 'online') {
                online++;
            } else if (result.status === 'offline') {
                offline++;
            } else {
                warning++;
            }

        });


        document.getElementById('online').textContent = online;
        document.getElementById('offline').textContent = offline;
        document.getElementById('warning').textContent = warning;

        document.getElementById('total').textContent =
            online + offline + warning;

        document.getElementById('lastcheck').textContent =
            'Letzte Prüfung: ' +
            new Date(data.timestamp).toLocaleString('de-DE') +
            ' · NAS ' + data.nas +
            ' · Version ' + data.version;

    } catch (error) {

        console.error('Healthcheck fehlgeschlagen:', error);

        document.querySelectorAll('.service').forEach(card => {

            card.querySelector('.dot').className = 'dot offline';

            card.querySelector('.status-text').textContent =
                'Monitor nicht erreichbar';

            card.querySelector('.status-detail').textContent =
                'Fehler beim Abrufen der Statusdaten';

        });

        document.getElementById('online').textContent = '0';
        document.getElementById('offline').textContent = '–';
        document.getElementById('warning').textContent = '–';

        document.getElementById('lastcheck').textContent =
            'Fehler beim Abrufen der Statusdaten: ' + error.message;

    } finally {

        button.disabled = false;
        button.textContent = '↻ Jetzt prüfen';

    }

}


/* =========================================
   ERSTPRÜFUNG BEIM SEITENAUFRUF
   Weitere Prüfungen nur per Button.
========================================= */

checkAll();

</script>

</body>
</html>