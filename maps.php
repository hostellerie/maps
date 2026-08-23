<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.6                                                        |
// +--------------------------------------------------------------------------+
// | Runtime configuration and table definitions                              |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

global $_CONF, $_TABLES, $_DB_table_prefix, $_MAPS_CONF;

$modernLanguageFile = $_CONF['path'] . 'plugins/maps/language/modern.php';
if (file_exists($modernLanguageFile)) {
    require_once $modernLanguageFile;
}

if (!isset($_MAPS_CONF) || !is_array($_MAPS_CONF)) {
    $_MAPS_CONF = array();
}

// Maps 1.5.2 uses a fixed public folder. Preserve the configured 1.5.1
// value for the upgrader before normalizing all runtime URLs to /maps/.
$legacyMapsFolder = isset($_MAPS_CONF['maps_folder'])
    ? (string) $_MAPS_CONF['maps_folder']
    : (isset($_MAPS_CONF['legacy_maps_folder']) ? (string) $_MAPS_CONF['legacy_maps_folder'] : 'maps');
$_MAPS_CONF['_legacy_maps_folder'] = $legacyMapsFolder;
$_MAPS_CONF['maps_folder'] = 'maps';

// Legacy-folder configuration cleanup is handled by the 1.5.3 upgrade.

/*
 * Public plugin paths.
 * Icon and overlay images intentionally remain shared between multisite
 * installations, using Geeklog's common images directory.
 */
$_MAPS_CONF['path_html'] = rtrim($_CONF['path_html'], '/\\') . DIRECTORY_SEPARATOR
    . $_MAPS_CONF['maps_folder'] . DIRECTORY_SEPARATOR;
$_MAPS_CONF['site_url'] = rtrim($_CONF['site_url'], '/') . '/' . $_MAPS_CONF['maps_folder'];

$_MAPS_CONF['path_overlay_images'] = rtrim($_CONF['path_images'], '/\\')
    . DIRECTORY_SEPARATOR . 'maps' . DIRECTORY_SEPARATOR . 'overlays' . DIRECTORY_SEPARATOR;
$_MAPS_CONF['path_icons_images'] = rtrim($_CONF['path_images'], '/\\')
    . DIRECTORY_SEPARATOR . 'maps' . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR;

$imagesRelativePath = '';
if (isset($_CONF['path_images'], $_CONF['path_html'])
    && strpos($_CONF['path_images'], $_CONF['path_html']) === 0
) {
    $imagesRelativePath = substr($_CONF['path_images'], strlen($_CONF['path_html']));
}
$imagesRelativePath = trim(str_replace('\\', '/', $imagesRelativePath), '/');
$imagesBaseUrl = rtrim($_CONF['site_url'], '/');
if ($imagesRelativePath !== '') {
    $imagesBaseUrl .= '/' . $imagesRelativePath;
} else {
    $imagesBaseUrl .= '/images';
}
$_MAPS_CONF['images_overlay_url'] = $imagesBaseUrl . '/maps/overlays/';
$_MAPS_CONF['images_icons_url'] = $imagesBaseUrl . '/maps/icons/';

if (!isset($_MAPS_CONF['max_image_width'])) {
    $_MAPS_CONF['max_image_width'] = 2000;
}
if (!isset($_MAPS_CONF['max_image_height'])) {
    $_MAPS_CONF['max_image_height'] = 2000;
}
if (!isset($_MAPS_CONF['max_image_size'])) {
    $_MAPS_CONF['max_image_size'] = 4194304;
}

/**
 * Return a Google Maps JavaScript API URL suitable for modern browsers.
 *
 * Maps keeps deterministic script element ordering for Geeklog 2.1.1
 * compatibility, while using Google's loading=async URL hint. This removes
 * the current Google Maps performance warning without making legacy inline
 * initializers race an asynchronously inserted script element. A future major
 * version can move all rendering to importLibrary().
 *
 * Kept PHP 5.6 compatible intentionally.
 *
 * @param array $libraries Optional libraries to request
 * @return string
 */
function MAPS_googleMapsApiUrl($libraries = array())
{
    global $_MAPS_CONF;

    $params = array(
        'key' => isset($_MAPS_CONF['google_api_key']) ? trim($_MAPS_CONF['google_api_key']) : '',
        'v' => 'weekly',
        'loading' => 'async'
    );

    if (!empty($libraries)) {
        $cleanLibraries = array();
        foreach ($libraries as $library) {
            $library = preg_replace('/[^a-z0-9_-]/i', '', (string) $library);
            if ($library !== '') {
                $cleanLibraries[] = $library;
            }
        }
        if (!empty($cleanLibraries)) {
            $params['libraries'] = implode(',', array_unique($cleanLibraries));
        }
    }

    if (isset($_MAPS_CONF['google_language']) && trim($_MAPS_CONF['google_language']) !== '') {
        $params['language'] = trim($_MAPS_CONF['google_language']);
    }
    if (isset($_MAPS_CONF['google_region']) && trim($_MAPS_CONF['google_region']) !== '') {
        $params['region'] = trim($_MAPS_CONF['google_region']);
    }

    return 'https://maps.googleapis.com/maps/api/js?' . http_build_query($params, '', '&');
}

/**
 * Register the Google Maps JavaScript API on Geeklog 2.2.x and newer.
 *
 * Maps 1.5 keeps its historical setJavaScript() loader for Geeklog 2.1.x,
 * where that path is known to work with older Resource/theme combinations.
 * Geeklog 2.2.x expects remote JavaScript URLs to be registered through
 * setJavaScriptFile(); otherwise a complete <script src="..."> string passed
 * to setJavaScript() may be reduced to an empty inline script in the rendered
 * document. This compatibility shim deliberately leaves 2.1.x untouched.
 *
 * The Maps administration dashboard is excluded because it installs
 * gm_authFailure() before loading Google Maps so API-key/billing failures can
 * be diagnosed reliably.
 *
 * @return bool
 */
function MAPS_registerModernGoogleMapsApi()
{
    global $_MAPS_CONF, $_SCRIPTS;

    static $registered = false;

    if ($registered) {
        return true;
    }

    if (!defined('VERSION') || version_compare(VERSION, '2.2.0', '<')) {
        return false;
    }

    $scriptName = isset($_SERVER['SCRIPT_NAME'])
        ? str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME'])
        : '';
    if (preg_match('#/admin/plugins/maps/index\.php$#', $scriptName)) {
        return false;
    }

    $key = isset($_MAPS_CONF['google_api_key'])
        ? trim((string) $_MAPS_CONF['google_api_key'])
        : '';
    if ($key === '') {
        return false;
    }

    if (!isset($_SCRIPTS) || !is_object($_SCRIPTS)
        || !method_exists($_SCRIPTS, 'setJavaScriptFile')
    ) {
        return false;
    }

    $registered = (bool) $_SCRIPTS->setJavaScriptFile(
        'maps_google_api',
        MAPS_googleMapsApiUrl(),
        false
    );

    return $registered;
}

/**
 * Build a current Geocoding API URL.
 *
 * @param string $address
 * @return string
 */
function MAPS_googleGeocodeUrl($address)
{
    global $_MAPS_CONF;

    $key = '';
    if (isset($_MAPS_CONF['google_server_api_key'])
        && trim($_MAPS_CONF['google_server_api_key']) !== ''
    ) {
        $key = trim($_MAPS_CONF['google_server_api_key']);
    } elseif (isset($_MAPS_CONF['google_api_key'])) {
        $key = trim($_MAPS_CONF['google_api_key']);
    }

    $params = array(
        'address' => $address,
        'key' => $key
    );

    if (isset($_MAPS_CONF['google_language']) && trim($_MAPS_CONF['google_language']) !== '') {
        $params['language'] = trim($_MAPS_CONF['google_language']);
    }
    if (isset($_MAPS_CONF['google_region']) && trim($_MAPS_CONF['google_region']) !== '') {
        $params['region'] = trim($_MAPS_CONF['google_region']);
    }

    return 'https://maps.googleapis.com/maps/api/geocode/json?'
        . http_build_query($params, '', '&');
}

/** Maps plugin tables. */
$_TABLES['maps_geo'] = $_DB_table_prefix . 'maps_geo';
$_TABLES['maps_maps'] = $_DB_table_prefix . 'maps_maps';
$_TABLES['maps_markers'] = $_DB_table_prefix . 'maps_markers';
$_TABLES['maps_submission'] = $_DB_table_prefix . 'maps_submission';
$_TABLES['maps_markers_cat'] = $_DB_table_prefix . 'maps_markers_cat';
$_TABLES['maps_markers_fields'] = $_DB_table_prefix . 'maps_markers_fields';
$_TABLES['maps_markers_values'] = $_DB_table_prefix . 'maps_markers_values';
$_TABLES['maps_overlays'] = $_DB_table_prefix . 'maps_overlays';
$_TABLES['maps_map_overlay'] = $_DB_table_prefix . 'maps_map_overlay';
$_TABLES['maps_map_icons'] = $_DB_table_prefix . 'maps_map_icons';
$_TABLES['maps_overlays_groups'] = $_DB_table_prefix . 'maps_overlays_groups';

// Register the API early enough for Geeklog 2.2.x to include it in the page
// header. The legacy 2.1.x loader in functions.inc remains unchanged.
MAPS_registerModernGoogleMapsApi();
