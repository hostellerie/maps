<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                        |
// +--------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | Runtime configuration and table definitions                              |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

global $_CONF, $_TABLES, $_DB_table_prefix, $_MAPS_CONF;

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
 * Return a Google Maps JavaScript API URL suitable for the synchronous
 * initializers still used by Maps 1.x.
 *
 * Do not request Google's loading=async bootstrap here. Geeklog 2.2.x can
 * register the external API through Resource::setJavaScriptFile(), and with
 * loading=async the inline Maps initializers may run after window.google and
 * google.maps exist but before constants such as google.maps.MapTypeId are
 * ready. That race is visible on profile pages as an exception while reading
 * MapTypeId.ROADMAP.
 *
 * Keeping the API bootstrap synchronous preserves the behavior that already
 * works on Geeklog 2.1.1 and keeps the existing Maps 1.x rendering model
 * deterministic. A future major version can migrate all initializers to
 * importLibrary() and then safely restore asynchronous loading.
 *
 * On Geeklog 2.2.x, Resource requires external scripts to be registered with
 * setJavaScriptFile(). The historical MAPS_loadGoogleMapsApi() call remains
 * untouched for Geeklog 2.1.x; when this URL builder is called by that helper
 * on Geeklog 2.2.x, it additionally registers the same URL as an external
 * resource. This preserves the historical load point without preloading the
 * API from plugin bootstrap code or disturbing the admin gm_authFailure test.
 *
 * Kept PHP 5.6 compatible intentionally.
 *
 * @param array $libraries Optional libraries to request
 * @return string
 */
function MAPS_googleMapsApiUrl($libraries = array())
{
    global $_MAPS_CONF, $_SCRIPTS;

    $params = array(
        'key' => isset($_MAPS_CONF['google_api_key']) ? trim($_MAPS_CONF['google_api_key']) : '',
        'v' => 'weekly'
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

    $url = 'https://maps.googleapis.com/maps/api/js?' . http_build_query($params, '', '&');

    if (defined('VERSION') && version_compare(VERSION, '2.2.0', '>=')
        && isset($_SCRIPTS) && is_object($_SCRIPTS)
        && method_exists($_SCRIPTS, 'setJavaScriptFile')
    ) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        if (isset($trace[1]['function']) && $trace[1]['function'] === 'MAPS_loadGoogleMapsApi') {
            $_SCRIPTS->setJavaScriptFile('maps_google_api', $url, false);
        }
    }

    return $url;
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
$_TABLES['maps_overlays'] = $_DB_table_prefix . 'maps_overlays';
$_TABLES['maps_map_overlay'] = $_DB_table_prefix . 'maps_map_overlay';
$_TABLES['maps_map_icons'] = $_DB_table_prefix . 'maps_map_icons';
$_TABLES['maps_overlays_groups'] = $_DB_table_prefix . 'maps_overlays_groups';
$_TABLES['maps_service_operations'] = $_DB_table_prefix . 'maps_service_operations';

require_once $_CONF['path'] . 'plugins/maps/interoperability.php';
require_once $_CONF['path'] . 'plugins/maps/integrations.php';
require_once $_CONF['path'] . 'plugins/maps/distribution.php';