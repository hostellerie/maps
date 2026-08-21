<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                        |
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

if (!isset($_MAPS_CONF['maps_folder']) || $_MAPS_CONF['maps_folder'] === '') {
    $_MAPS_CONF['maps_folder'] = 'maps';
}

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

$_MAPS_CONF['max_image_width'] = 2000;
$_MAPS_CONF['max_image_height'] = 2000;
$_MAPS_CONF['max_image_size'] = 4194304;

/**
 * Return a Google Maps JavaScript API URL suitable for modern browsers.
 *
 * Maps 1.5 intentionally uses deterministic script loading rather than the
 * async bootstrap option. The plugin has many legacy inline map initializers,
 * and deterministic loading keeps Geeklog 2.1.1 themes working while avoiding
 * race conditions. A future major version can move all rendering to
 * importLibrary() once the legacy templates are retired.
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

    return 'https://maps.googleapis.com/maps/api/js?' . http_build_query($params, '', '&');
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
