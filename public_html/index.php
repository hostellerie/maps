<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | Public entry point                                                        |
// +---------------------------------------------------------------------------+

if (!defined('VERSION')) {
    require_once '../lib-common.php';
} else {
    global $_CONF, $_PLUGINS, $_MAPS_CONF, $_TABLES;
}

if (!in_array('maps', $_PLUGINS)) {
    if (function_exists('COM_handle404')) {
        COM_handle404();
    } else {
        echo COM_refresh($_CONF['site_url'] . '/index.php');
    }
    exit;
}

MAPS_getheadercode();

$vars = array('mid' => 'int', 'mkid' => 'alpha', 'mode' => 'alpha');
MAPS_filterVars($vars, $_REQUEST);
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$mid = isset($_REQUEST['mid']) ? (int) $_REQUEST['mid'] : 0;
$mkid = isset($_REQUEST['mkid']) ? $_REQUEST['mkid'] : '';

/**
 * Render the Maps landing page.
 *
 * @return string
 */
function MAPS_displayFrontPage()
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1, $_TABLES;

    $retval = '';
    if (MAPS_arrayGet($_MAPS_CONF, 'map_main_header', '') !== '') {
        $retval .= '<div>' . PLG_replaceTags($_MAPS_CONF['map_main_header']) . '</div>';
    }

    if ((int) MAPS_arrayGet($_MAPS_CONF, 'global_map', 1) === 1
        && !(COM_isAnonUser() && (int) MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) === 1)) {
        $retval .= MAPS_getGlobalMap('', '', true);
    }

    $retval .= '<p>' . $LANG_MAPS_1['user_maps_list'] . '</p>';
    $result = DB_query("SELECT mid,name,description,active,hidden,modified,hits FROM {$_TABLES['maps_maps']} ORDER BY name ASC");
    $count = 0;
    while ($map = DB_fetchArray($result)) {
        if ((int) $map['active'] !== 1 || (int) $map['hidden'] === 1) {
            continue;
        }
        $count++;
        $url = $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $map['mid'];
        $retval .= '<div class="maps_list_item">';
        $retval .= '<strong><a href="' . $url . '">' . htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8') . '</a></strong>';
        if ($map['description'] !== '') {
            $retval .= '<br>' . htmlspecialchars(stripslashes($map['description']), ENT_QUOTES, 'UTF-8');
        }
        $modified = COM_getUserDateTimeFormat($map['modified']);
        $retval .= '<br><small>' . $LANG_MAPS_1['last_modification'] . ' ' . $modified[0];
        if ((int) MAPS_arrayGet($_MAPS_CONF, 'stats_public_enabled', 1) === 1) {
            $markers = DB_count($_TABLES['maps_markers'], 'mid', $map['mid']);
            $retval .= ' | ' . (int) $markers . ' ' . $LANG_MAPS_1['records']
                . ' | ' . (int) $map['hits'] . ' ' . $LANG_MAPS_1['hits'];
        }
        $retval .= '</small>';
        if (SEC_hasRights('maps.admin')) {
            $retval .= ' | <a href="' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&amp;mid=' . (int) $map['mid'] . '">' . $LANG_MAPS_1['edit_button'] . '</a>';
        }
        $retval .= '</div>';
    }

    if ($count === 0) {
        $retval .= '<p>' . $LANG_MAPS_1['no_map_user'] . '</p>';
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'users_map', 1) === 1) {
        $retval .= '<p class="maps_list_item"><strong><a href="' . $_MAPS_CONF['site_url'] . '/users_map.php">'
            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</p>';
    }
    $retval .= MAPS_renderStatistics(true);
    if (SEC_hasRights('maps.admin')) {
        $retval .= '<p>' . $LANG_MAPS_1['admin_can'] . ' <a href="' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=new">' . $LANG_MAPS_1['create_map'] . '</a></p>';
    }
    if (MAPS_arrayGet($_MAPS_CONF, 'map_main_footer', '') !== '') {
        $retval .= '<div>' . PLG_replaceTags($_MAPS_CONF['map_main_footer']) . '</div>';
    }
    return $retval;
}

if (COM_isAnonUser()
    && ((int) MAPS_arrayGet($_CONF, 'loginrequired', 0) === 1 || (int) MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) === 1)
    && $mode !== '') {
    $content = MAPS_user_menu();
    $content .= MAPS_message($LANG_LOGIN[2], $LANG_LOGIN[1]);
    COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
    exit;
}

if (trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', '')) === '') {
    $content = MAPS_user_menu();
    $content .= MAPS_message($LANG_MAPS_1['need_google_api'], $LANG_MAPS_1['plugin_name']);
    COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
    exit;
}

/**
 * Build a concise plain-text search/social description.
 *
 * @param string $text
 * @param string $fallback
 * @return string
 */
function MAPS_publicDescription($text, $fallback = '')
{
    $text = trim((string) $text);
    if ($text !== '') {
        $text = PLG_replaceTags($text);
    }
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    if ($text === '') {
        $text = trim((string) $fallback);
    }
    if (function_exists('COM_truncate')) {
        return COM_truncate($text, 160, '...');
    }
    return (strlen($text) > 160) ? substr($text, 0, 157) . '...' : $text;
}

/**
 * Build canonical, description, robots, Open Graph, Twitter and JSON-LD tags.
 *
 * @param string $title
 * @param string $description
 * @param string $canonical
 * @param string $robots
 * @param array  $jsonLd
 * @return string
 */
function MAPS_publicSeoHeader($title, $description, $canonical, $robots = '', $jsonLd = array())
{
    $safeTitle = htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8');
    $safeDescription = htmlspecialchars((string) $description, ENT_QUOTES, 'UTF-8');
    $safeCanonical = htmlspecialchars((string) $canonical, ENT_QUOTES, 'UTF-8');
    $header = '<link rel="canonical" href="' . $safeCanonical . '">' . LB;
    if ($description !== '') {
        $header .= '<meta name="description" content="' . $safeDescription . '">' . LB;
    }
    if ($robots !== '') {
        $header .= '<meta name="robots" content="' . htmlspecialchars($robots, ENT_QUOTES, 'UTF-8') . '">' . LB;
    }
    $header .= '<meta property="og:title" content="' . $safeTitle . '">' . LB
        . '<meta property="og:url" content="' . $safeCanonical . '">' . LB
        . '<meta property="og:type" content="website">' . LB;
    if ($description !== '') {
        $header .= '<meta property="og:description" content="' . $safeDescription . '">' . LB;
    }
    $header .= '<meta name="twitter:card" content="summary">' . LB
        . '<meta name="twitter:title" content="' . $safeTitle . '">' . LB;
    if ($description !== '') {
        $header .= '<meta name="twitter:description" content="' . $safeDescription . '">' . LB;
    }
    if (!empty($jsonLd)) {
        $json = json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json !== false) {
            $header .= '<script type="application/ld+json">' . $json . '</script>' . LB;
        }
    }
    return $header;
}

$pageTitle = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_page_title', ''));
if ($pageTitle === '') {
    $pageTitle = $LANG_MAPS_1['maps_label'];
}
$pageH1 = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_page_h1', ''));
if ($pageH1 === '') {
    $pageH1 = $pageTitle;
}
$configuredMetaDescription = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_meta_description', ''));
$pageDescription = MAPS_publicDescription(
    $configuredMetaDescription !== '' ? $configuredMetaDescription : MAPS_arrayGet($_MAPS_CONF, 'map_main_header', ''),
    $pageTitle
);
$canonical = rtrim($_MAPS_CONF['site_url'], '/') . '/';
$robots = '';
$jsonLd = array();
$mapRow = array();
$markerRow = array();
$markerMapRow = array();

if ($mode === 'map' && $mid > 0) {
    $result = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . $mid . " LIMIT 1");
    $mapRow = DB_fetchArray($result);
    if (!is_array($mapRow) || (int) MAPS_arrayGet($mapRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($mapRow, 'hidden', 0) === 1 || !SEC_hasAccess2($mapRow)) {
        COM_handle404();
        exit;
    }
    $pageTitle = MAPS_decodeStoredText(MAPS_arrayGet($mapRow, 'name', ''));
    $pageDescription = MAPS_publicDescription(
        MAPS_arrayGet($mapRow, 'description', ''),
        $pageTitle
    );
    $canonical = MAPS_contentUrl($mid);
} elseif ($mode === 'marker' && $mkid !== '') {
    $safeMkid = MAPS_dbEscape($mkid);
    $result = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='" . $safeMkid . "' LIMIT 1");
    $markerRow = DB_fetchArray($result);
    if (!is_array($markerRow) || (int) MAPS_arrayGet($markerRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($markerRow, 'hidden', 0) === 1 || !SEC_hasAccess2($markerRow)) {
        COM_handle404();
        exit;
    }
    $markerMid = (int) MAPS_arrayGet($markerRow, 'mid', 0);
    $mapResult = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . $markerMid . " LIMIT 1");
    $markerMapRow = DB_fetchArray($mapResult);
    if (!is_array($markerMapRow) || (int) MAPS_arrayGet($markerMapRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($markerMapRow, 'hidden', 0) === 1 || !SEC_hasAccess2($markerMapRow)) {
        COM_handle404();
        exit;
    }

    $pageTitle = MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'name', ''));
    $addressFallback = trim(implode(', ', array_filter(array(
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'address', '')),
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'city', '')),
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'country', ''))
    ))));
    $pageDescription = MAPS_publicDescription(
        MAPS_arrayGet($markerRow, 'description', ''),
        $addressFallback !== '' ? $pageTitle . ' - ' . $addressFallback : $pageTitle
    );
    $canonical = MAPS_markerContentUrl($mkid);

    $place = array(
        '@context' => 'https://schema.org',
        '@type' => 'Place',
        'name' => $pageTitle,
        'url' => $canonical
    );
    if ($pageDescription !== '') {
        $place['description'] = $pageDescription;
    }
    $postal = array('@type' => 'PostalAddress');
    $postalFields = array(
        'streetAddress' => 'street',
        'postalCode' => 'code',
        'addressLocality' => 'city',
        'addressRegion' => 'state',
        'addressCountry' => 'country'
    );
    foreach ($postalFields as $schemaField => $dbField) {
        $value = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, $dbField, '')));
        if ($value !== '') {
            $postal[$schemaField] = $value;
        }
    }
    if (count($postal) > 1) {
        $place['address'] = $postal;
    } elseif ($addressFallback !== '') {
        $place['address'] = $addressFallback;
    }
    $markerLat = MAPS_arrayGet($markerRow, 'lat', '');
    $markerLng = MAPS_arrayGet($markerRow, 'lng', '');
    if (is_numeric($markerLat) && is_numeric($markerLng)
        && (float) $markerLat >= -90.0 && (float) $markerLat <= 90.0
        && (float) $markerLng >= -180.0 && (float) $markerLng <= 180.0) {
        $place['geo'] = array(
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $markerLat,
            'longitude' => (float) $markerLng
        );
    }
    $telephone = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'tel', '')));
    if ($telephone !== '') {
        $place['telephone'] = $telephone;
    }
    $website = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'web', '')));
    if (filter_var($website, FILTER_VALIDATE_URL)) {
        $place['sameAs'] = $website;
    }
    $jsonLd = $place;
} elseif ($mode === 'markers') {
    // Marker list views are navigation helpers rather than canonical landing pages.
    $robots = 'noindex,follow';
    $canonical = ($mid > 0) ? MAPS_contentUrl($mid) : rtrim($_MAPS_CONF['site_url'], '/') . '/';
}

$content = MAPS_user_menu();
if (isset($_REQUEST['msg']) && (int) $_REQUEST['msg'] > 0) {
    $content .= COM_showMessage((int) $_REQUEST['msg'], 'maps');
}

switch ($mode) {
    case 'map':
        if ($mid > 0) {
            $content .= MAPS_getMap($mid);
            $content .= MAPS_ListMarkers($mid);
            $content .= MAPS_renderMapStatistics($mid, true);
        } else {
            $content .= MAPS_getGlobalMap();
        }
        break;

    case 'markers':
        $content .= MAPS_ListMarkers($mid);
        break;

    case 'marker':
        if ($mkid !== '') {
            $content .= '<article class="maps-marker-detail">';
            $content .= '<h1>' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '</h1>';
            $content .= MAPS_ViewMarkerInfos($mkid);
            if (!empty($markerMapRow['mid'])) {
                $content .= MAPS_getMarkerDetail((int) $markerMapRow['mid'], $mkid);
            }
            if (!empty($markerMapRow['mid'])) {
                $mapName = MAPS_decodeStoredText(MAPS_arrayGet($markerMapRow, 'name', ''));
                $content .= '<p class="maps-marker-map-link"><a href="'
                    . htmlspecialchars(MAPS_contentUrl((int) $markerMapRow['mid']), ENT_QUOTES, 'UTF-8') . '">'
                    . htmlspecialchars($mapName, ENT_QUOTES, 'UTF-8') . '</a></p>';
            }
            $content .= '</article>';
        }
        break;

    default:
        $content .= '<h1>' . htmlspecialchars($pageH1, ENT_QUOTES, 'UTF-8') . '</h1>';
        $content .= MAPS_displayFrontPage();
        break;
}

$headercode = MAPS_publicSeoHeader($pageTitle, $pageDescription, $canonical, $robots, $jsonLd);
COM_output(COM_createHTMLDocument(
    $content,
    array(
        'pagetitle' => stripslashes($pageTitle),
        'headercode' => $headercode
    )
));
