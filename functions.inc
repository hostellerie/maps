<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                         |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// |                                                                           |
// | Geeklog plugin API and common Maps functions.                             |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026                                                   |
// | Original author: Ben                                                      |
// +---------------------------------------------------------------------------+

if (strpos(strtolower(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''), 'functions.inc') !== false) {
    die('This file can not be used on its own.');
}

$plugin_path = $_CONF['path'] . 'plugins/maps/';
$langfile = $plugin_path . 'language/' . $_CONF['language'] . '.php';
if (file_exists($langfile)) {
    require_once $langfile;
} else {
    require_once $plugin_path . 'language/english.php';
}

require_once $_CONF['path_system'] . 'classes/config.class.php';
$c = config::get_instance();
$_MAPS_CONF = $c->get_config('maps');
if (!is_array($_MAPS_CONF)) {
    $_MAPS_CONF = array();
}
require_once $_CONF['path'] . 'plugins/maps/maps.php';

/**
 * Escape a value for SQL while keeping compatibility with older Geeklog.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_dbEscape($value)
{
    if (function_exists('DB_escapeString')) {
        return DB_escapeString((string) $value);
    }
    return addslashes((string) $value);
}

/**
 * Return a JavaScript-safe quoted string.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_jsString($value)
{
    $json = json_encode((string) $value);
    return ($json === false) ? '""' : $json;
}

/**
 * Safe array getter for PHP 5.6 through 8.1.
 *
 * @param array  $array
 * @param string $key
 * @param mixed  $default
 * @return mixed
 */
function MAPS_arrayGet($array, $key, $default = '')
{
    return (is_array($array) && isset($array[$key])) ? $array[$key] : $default;
}

/**
 * Build a compact SVG pin as a data URI. This replaces the retired Google
 * Image Charts marker service while preserving configurable marker colors.
 *
 * @param string $fill
 * @param string $label
 * @param string $labelColor
 * @return string
 */
function MAPS_svgMarkerUri($fill, $label = '', $labelColor = '#ffffff')
{
    if (!preg_match('/^#[0-9a-f]{6}$/i', $fill)) {
        $fill = '#666666';
    }
    if (!preg_match('/^#[0-9a-f]{6}$/i', $labelColor)) {
        $labelColor = '#ffffff';
    }
    $label = function_exists('mb_substr') ? mb_substr((string) $label, 0, 2, 'UTF-8') : substr((string) $label, 0, 2);
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="44" viewBox="0 0 32 44">'
         . '<path fill="' . $fill . '" stroke="#333" stroke-width="1.5" d="M16 1C7.7 1 1 7.7 1 16c0 11 15 27 15 27s15-16 15-27C31 7.7 24.3 1 16 1z"/>'
         . '<circle cx="16" cy="16" r="9" fill="rgba(255,255,255,.18)"/>';
    if ($label !== '') {
        $svg .= '<text x="16" y="20" text-anchor="middle" font-family="Arial,sans-serif" font-size="11" font-weight="bold" fill="' . $labelColor . '">' . $label . '</text>';
    }
    $svg .= '</svg>';
    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}

/**
 * Return the pinned modern MarkerClusterer UMD script.
 *
 * @return string
 */
function MAPS_clusterScriptTag()
{
    return '<script src="https://unpkg.com/@googlemaps/markerclusterer@2.6.2/dist/index.min.js"></script>';
}

/**
 * Generate safe clustering initialization code.
 *
 * @param string $mapVariable
 * @return string
 */
function MAPS_clusterInitJs($mapVariable)
{
    return "if (window.markerClusterer && markerClusterer.MarkerClusterer && markers.length) {\n"
        . "    new markerClusterer.MarkerClusterer({map: " . $mapVariable . ", markers: markers});\n"
        . "}\n";
}

/**
 * Load Google Maps once and inject Calendar integration where requested.
 */
function plugin_getheadercode_maps()
{
    global $_MAPS_CONF, $_SCRIPTS, $_PLUGINS;

    $key = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', ''));
    if ($key === '') {
        return;
    }

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    $_SCRIPTS->setJavaScript(
        '<script src="' . htmlspecialchars(MAPS_googleMapsApiUrl(), ENT_QUOTES, 'UTF-8') . '"></script>',
        false,
        false
    );

    $str = '';
    $url = COM_getCurrentURL();
    $eid = isset($_GET['eid']) ? COM_applyFilter($_GET['eid']) : '';
    $calendarEnabled = in_array('calendar', $_PLUGINS)
        && (int) MAPS_arrayGet($_MAPS_CONF, 'display_events_map', 0) === 1;

    if ($calendarEnabled && stripos($url, 'calendar/index.php') !== false) {
        $str .= MAPS_displayEventsMap(false);
        $str .= "jQuery(function(){jQuery('.cal-header').after('<div id=\"map_contener_mevents\"><div id=\"map_canvas_mevents\"></div></div>');});\n";
    } elseif ($calendarEnabled && stripos($url, 'event.php') !== false && $eid !== '') {
        $str .= MAPS_displayEventsMap(false, $eid);
        $str .= "jQuery(function(){jQuery('.location').append('<div style=\"margin:10px 0\" id=\"map_contener_mevents\"><div id=\"map_canvas_mevents\"></div></div>');});\n";
    }

    if ($str !== '') {
        $_SCRIPTS->setJavaScript($str, true, true);
    }
}

/** Load plugin CSS/legacy color helper. */
function MAPS_getheadercode()
{
    global $_CONF, $_MAPS_CONF, $_SCRIPTS;

    $_SCRIPTS->setCSSFile('maps_css', '/' . MAPS_arrayGet($_MAPS_CONF, 'maps_folder', 'maps') . '/maps.css');
    if (isset($_CONF['theme'])) {
        $_SCRIPTS->setCSSFile('datepicker_ui', '/layout/' . $_CONF['theme'] . '/jquery_ui/jquery.ui.datepicker.css');
    }
    $_SCRIPTS->setJavaScriptFile('maps_iconmaker', '/' . MAPS_arrayGet($_MAPS_CONF, 'maps_folder', 'maps') . '/js/mapiconmaker.js', false);
}

function plugin_getdocumentationurl_maps()
{
    return 'https://github.com/Geeklog-Plugins/maps';
}

function plugin_getmenuitems_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1;

    $menuitems = array();
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'hide_maps_menu', 0) === 0) {
        $menuitems[$LANG_MAPS_1['plugin_name']] = $_MAPS_CONF['site_url'] . '/index.php';
    }
    return $menuitems;
}

function plugin_searchtypes_maps()
{
    global $LANG_MAPS_1;
    return array('maps' => $LANG_MAPS_1['plugin_name']);
}

function plugin_dopluginsearch_maps($query, $datestart, $dateend, $topic, $type, $author, $keyType, $page, $perpage)
{
    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF;

    $query = trim(MAPS_dbEscape($query));
    $sql = "SELECT mkid AS id, name AS title, description, UNIX_TIMESTAMP(created) AS date, owner_id AS uid, mid, "
         . "CONCAT('/" . MAPS_dbEscape($_MAPS_CONF['maps_folder']) . "/markers.php?mode=show&mkid=', mkid, '&mid=', mid) AS url "
         . "FROM {$_TABLES['maps_markers']} WHERE 1=1 "
         . COM_getPermSQL('AND') . ' ';

    if (!empty($datestart) && !empty($dateend)) {
        $delim = substr($datestart, 4, 1);
        if ($delim !== '') {
            $ds = explode($delim, $datestart);
            $de = explode($delim, $dateend);
            if (count($ds) >= 3 && count($de) >= 3) {
                $startdate = mktime(0, 0, 0, $ds[1], $ds[2], $ds[0]);
                $enddate = mktime(23, 59, 59, $de[1], $de[2], $de[0]);
                $sql .= "AND UNIX_TIMESTAMP(created) BETWEEN " . (int) $startdate . " AND " . (int) $enddate . ' ';
            }
        }
    }
    if (!empty($author)) {
        $sql .= 'AND owner_id=' . (int) $author . ' ';
    }

    $search = new SearchCriteria('maps', $LANG_MAPS_1['plugin_name']);
    list($sql, $ftsql) = $search->buildSearchSQL($keyType, $query, array('name', 'description'), $sql);
    $search->setSQL($sql);
    $search->setFTSQL($ftsql);
    $search->setRank(3);
    return $search;
}

function plugin_autotags_maps($op, $content = '', $autotag = '')
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_MAPS_1;

    if ($op === 'tagname') {
        return array('geo', 'maps', 'marker');
    }
    if ($op === 'description') {
        return array(
            'maps' => $LANG_MAPS_1['autotag_desc_maps'],
            'geo' => $LANG_MAPS_1['autotag_desc_geo'],
            'marker' => $LANG_MAPS_1['autotag_desc_marker']
        );
    }
    if ($op !== 'parse' || !is_array($autotag)) {
        return $content;
    }

    MAPS_getheadercode();
    $html = '';
    $tag = MAPS_arrayGet($autotag, 'tag', '');
    $parm1 = MAPS_arrayGet($autotag, 'parm1', '');
    $parm2 = MAPS_arrayGet($autotag, 'parm2', '');

    if ($tag === 'geo' && COM_applyFilter($parm1) === 'map') {
        $zoom = '';
        $width = '';
        $height = '';
        $addressParts = array();
        foreach (preg_split('/\s+/', trim($parm2)) as $part) {
            if (strpos($part, 'zoom:') === 0) {
                $zoom = substr($part, 5);
            } elseif (strpos($part, 'width:') === 0) {
                $width = substr($part, 6);
            } elseif (strpos($part, 'height:') === 0) {
                $height = substr($part, 7);
            } elseif ($part !== '') {
                $addressParts[] = $part;
            }
        }
        $address = trim(implode(' ', $addressParts));
        if ($address !== '') {
            $gid = '';
            $lat = 0;
            $lng = 0;
            MAPS_getDBCoords($gid, $address, $lat, $lng);
            if ($lat != 0 || $lng != 0) {
                $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
                $t->set_file('page', 'geo.thtml');
                $rid = mt_rand(1000, 999999);
                $t->set_var('gid', $rid);
                $t->set_var('address', htmlspecialchars($address, ENT_QUOTES, 'UTF-8'));
                $t->set_var('lat', (float) $lat);
                $t->set_var('lng', (float) $lng);
                $t->set_var('map_type', MAPS_arrayGet($_MAPS_CONF, 'map_type_geotag', 'ROADMAP'));
                $t->set_var('map_width_geotag', $width !== '' ? $width : MAPS_arrayGet($_MAPS_CONF, 'map_width_geotag', '100%'));
                $t->set_var('map_height_geotag', $height !== '' ? $height : MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', '400px'));
                $t->set_var('zoom', is_numeric($zoom) ? (int) $zoom : (int) MAPS_arrayGet($_MAPS_CONF, 'map_zoom_geotag', 10));
                $t->set_var('show_directions_geo', (int) MAPS_arrayGet($_MAPS_CONF, 'show_directions_geo', 0) ? 'block' : 'none');
                $t->set_var('error_address_empty', $LANG_MAPS_1['error_address_empty']);
                $t->set_var('error_invalid_address', $LANG_MAPS_1['error_invalid_address']);
                $t->set_var('error_google_error', $LANG_MAPS_1['error_google_error']);
                $t->set_var('error_no_map_info', $LANG_MAPS_1['error_no_map_info']);
                $t->set_var('need_directions', $LANG_MAPS_1['need_directions']);
                $t->set_var('get_directions', $LANG_MAPS_1['get_directions']);
                $t->set_var('value', htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8'));
                $t->parse('output', 'page');
                $html = $t->finish($t->get_var('output'));
            }
        }
    } elseif ($tag === 'maps') {
        $mid = (int) COM_applyFilter($parm1, true);
        $zoom = '';
        $addressParts = array();
        foreach (preg_split('/\s+/', trim($parm2)) as $part) {
            if (strpos($part, 'zoom:') === 0) {
                $zoom = substr($part, 5);
            } elseif ($part !== '') {
                $addressParts[] = $part;
            }
        }
        $address = trim(implode(' ', $addressParts));
        $html = $mid > 0 ? MAPS_getMap($mid, $zoom, $address) : MAPS_getGlobalMap($zoom, $address);
    } elseif ($tag === 'marker') {
        $mkid = COM_applyFilter($parm1);
        if ($mkid !== '') {
            $safe = MAPS_dbEscape($mkid);
            $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='{$safe}' LIMIT 1");
            $a = DB_fetchArray($res);
            if (is_array($a) && SEC_hasAccess2($a) && MAPS_arrayGet($a, 'mid', '') !== '') {
                $html = MAPS_getMarkerDetail((int) $a['mid'], $mkid, 1);
            }
        }
    }

    return str_replace(MAPS_arrayGet($autotag, 'tagstr', ''), $html, $content);
}

/** Compatibility no-op: Maps AdSense integration was removed by Google. */
function MAPS_getAds($mid)
{
    return '';
}

function plugin_profileblocksdisplay_maps($uid)
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_MAPS_1, $_SCRIPTS;

    if ((int) MAPS_arrayGet($_MAPS_CONF, 'display_geo_profile', 0) === 0
        || trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', '')) === '') {
        return '';
    }

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    $_SCRIPTS->setJavaScript('<script src="' . htmlspecialchars(MAPS_googleMapsApiUrl(), ENT_QUOTES, 'UTF-8') . '"></script>', false, false);

    $uid = (int) $uid;
    $address = DB_getItem($_TABLES['userinfo'], 'location', 'uid=' . $uid);
    if ($address === '') {
        return '';
    }

    $gid = 'profile' . $uid;
    $lat = 0;
    $lng = 0;
    MAPS_getDBCoords($cacheId, $address, $lat, $lng);
    if ($lat == 0 && $lng == 0) {
        return '';
    }

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'geo_profile.thtml');
    $t->set_var('gid', $gid);
    $t->set_var('map_width_geotag', MAPS_arrayGet($_MAPS_CONF, 'map_width_profile', '100%'));
    $t->set_var('map_height_geotag', MAPS_arrayGet($_MAPS_CONF, 'map_height_profile', '400px'));
    $t->set_var('show_directions_geo', (int) MAPS_arrayGet($_MAPS_CONF, 'show_directions_profile', 0) ? 'block' : 'none');
    $t->set_var('need_directions', $LANG_MAPS_1['need_directions']);
    $t->set_var('get_directions', $LANG_MAPS_1['get_directions']);
    $t->set_var('value', htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8'));
    $t->parse('output', 'page');

    $safeAddress = MAPS_jsString($address);
    $mapType = preg_replace('/[^A-Z_]/', '', strtoupper(MAPS_arrayGet($_MAPS_CONF, 'map_type_profile', 'ROADMAP')));
    $zoom = (int) MAPS_arrayGet($_MAPS_CONF, 'zoom_profile', 10);
    $js = "jQuery(function(){\n"
        . "var el=document.getElementById('map_canvas{$gid}'); if(!el||!window.google){return;}\n"
        . "var m=new google.maps.Map(el,{center:{lat:" . (float) $lat . ",lng:" . (float) $lng . "},zoom:{$zoom},mapTypeId:google.maps.MapTypeId.{$mapType}});\n"
        . "new google.maps.Marker({position:{lat:" . (float) $lat . ",lng:" . (float) $lng . "},map:m,title:{$safeAddress}});\n"
        . "var renderer=new google.maps.DirectionsRenderer({map:m,panel:document.getElementById('directions{$gid}')});\n"
        . "window.get_directions{$gid}=function(){var i=document.getElementById('from_address{$gid}');if(!i||!i.value){return;}new google.maps.DirectionsService().route({origin:i.value,destination:{$safeAddress},travelMode:google.maps.TravelMode.DRIVING},function(r,s){if(s==='OK'||s===google.maps.DirectionsStatus.OK){renderer.setDirections(r);}else{alert(" . MAPS_jsString($LANG_MAPS_1['error_google_error']) . "+' ('+s+')');}});};\n"
        . "});";
    $_SCRIPTS->setJavaScript($js, true, true);

    return COM_startBlock($LANG_MAPS_1['profile_title'])
        . $t->finish($t->get_var('output'))
        . COM_endBlock();
}

function plugin_cclabel_maps()
{
    global $_CONF, $LANG_MAPS_1;
    if (SEC_hasRights('maps.admin')) {
        return array($LANG_MAPS_1['plugin_name'], $_CONF['site_admin_url'] . '/plugins/maps/index.php', plugin_geticon_maps());
    }
    return array();
}

function plugin_getadminoption_maps()
{
    global $_CONF, $LANG_MAPS_1, $_TABLES;
    if (SEC_hasRights('maps.admin')) {
        return array($LANG_MAPS_1['plugin_name'], $_CONF['site_admin_url'] . '/plugins/maps/index.php', DB_count($_TABLES['maps_maps']));
    }
    return array();
}

function plugin_geticon_maps()
{
    global $_CONF;
    return $_CONF['site_admin_url'] . '/plugins/maps/images/maps.png';
}

function plugin_chkVersion_maps()
{
    global $_CONF;
    require_once $_CONF['path'] . 'plugins/maps/autoinstall.php';
    $p = plugin_autoinstall_maps('maps');
    return $p['info']['pi_version'];
}

/**
 * Upgrade Maps 1.4.x to 1.5.0 without destructive data migration.
 */
function plugin_upgrade_maps()
{
    global $_CONF, $_TABLES, $_MAPS_CONF;

    $installed = DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name='maps'");
    $code = plugin_chkVersion_maps();
    if ($installed === $code) {
        return true;
    }

    require_once $_CONF['path'] . 'plugins/maps/autoinstall.php';
    require_once $_CONF['path_system'] . 'classes/config.class.php';
    if (!plugin_compatible_with_this_version_maps('maps')) {
        return 3002;
    }

    if (version_compare($installed, '1.4.0', '<')) {
        COM_errorLog('Maps 1.5 upgrade requires Maps 1.4.0 or newer. Upgrade to 1.4.0 first.');
        return false;
    }

    $config = config::get_instance();
    $group = 'maps';
    $newSettings = array(
        'google_server_api_key' => array('', 'text', 0, 1, 0, 120),
        'google_map_id' => array('', 'text', 0, 1, 0, 130),
        'google_language' => array('', 'text', 0, 1, 0, 140),
        'google_region' => array('', 'text', 0, 1, 0, 150)
    );
    foreach ($newSettings as $name => $def) {
        if (!array_key_exists($name, $_MAPS_CONF)) {
            $config->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group);
        }
    }
    if (isset($_MAPS_CONF['url_geocode'])) {
        $config->set('url_geocode', 'https://maps.googleapis.com/maps/api/geocode/json', $group);
    }

    DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='" . MAPS_dbEscape($code) . "', pi_gl_version='2.1.1' WHERE pi_name='maps'");
    COM_errorLog('Updated Maps plugin from v' . $installed . ' to v' . $code, 1);
    return true;
}

function MAPS_delTree($dir)
{
    if (!is_dir($dir)) {
        return true;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path) && !is_link($path)) {
            MAPS_delTree($path);
        } else {
            @unlink($path);
        }
    }
    return @rmdir($dir);
}

function plugin_autouninstall_maps()
{
    return array(
        'tables' => array('maps_maps', 'maps_geo', 'maps_markers', 'maps_submission', 'maps_overlays', 'maps_map_overlay', 'maps_map_icons', 'maps_overlays_groups'),
        'groups' => array('Maps Admin'),
        'features' => array('maps.admin'),
        'php_blocks' => array(),
        'vars' => array('globalMapHits')
    );
}

/**
 * Resolve an address with Google Geocoding API.
 */
function MAPS_getCoords($address, &$lat, &$lng)
{
    global $_MAPS_CONF;

    $lat = 0;
    $lng = 0;
    $browserKey = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', ''));
    $serverKey = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_server_api_key', ''));
    if ($browserKey === '' && $serverKey === '') {
        COM_errorLog('MAPS geocoder: no Google API key configured.');
        return false;
    }

    $url = MAPS_googleGeocodeUrl(stripslashes((string) $address));
    $response = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        if ($response === false) {
            COM_errorLog('MAPS geocoder cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
    } elseif ((bool) ini_get('allow_url_fopen')) {
        $context = stream_context_create(array('http' => array('timeout' => 20), 'ssl' => array('verify_peer' => true, 'verify_peer_name' => true)));
        $response = @file_get_contents($url, false, $context);
    }

    if ($response === false || $response === '') {
        COM_errorLog('MAPS geocoder: unable to contact Google Geocoding API.');
        return false;
    }

    $json = json_decode($response, true);
    if (!is_array($json) || MAPS_arrayGet($json, 'status', '') !== 'OK'
        || !isset($json['results'][0]['geometry']['location']['lat'], $json['results'][0]['geometry']['location']['lng'])) {
        $status = is_array($json) ? MAPS_arrayGet($json, 'status', 'INVALID_RESPONSE') : 'INVALID_JSON';
        COM_errorLog('MAPS geocoder returned status: ' . $status);
        return false;
    }

    $lat = (float) $json['results'][0]['geometry']['location']['lat'];
    $lng = (float) $json['results'][0]['geometry']['location']['lng'];
    return true;
}

function MAPS_getDBCoords(&$gid, $address, &$lat, &$lng)
{
    global $_TABLES;

    $gid = '';
    $lat = 0;
    $lng = 0;
    $plainAddress = trim(stripslashes((string) $address));
    if ($plainAddress === '') {
        return;
    }
    $safeAddress = MAPS_dbEscape($plainAddress);
    $table = $_TABLES['maps_geo'];
    $res = DB_query("SELECT gid,lat,lng FROM {$table} WHERE geo='{$safeAddress}' LIMIT 1");
    if (DB_numRows($res) > 0) {
        $row = DB_fetchArray($res);
        $gid = $row['gid'];
        $lat = (float) $row['lat'];
        $lng = (float) $row['lng'];
        if ($lat != 0 || $lng != 0) {
            return;
        }
    }

    if (!MAPS_getCoords($plainAddress, $lat, $lng)) {
        return;
    }

    if ($gid !== '') {
        DB_query("UPDATE {$table} SET lat='" . (float) $lat . "',lng='" . (float) $lng . "' WHERE gid=" . (int) $gid);
    } else {
        DB_query("INSERT INTO {$table} (geo,lat,lng) VALUES ('{$safeAddress}','" . (float) $lat . "','" . (float) $lng . "')");
        $gid = DB_insertId();
    }
}

function MAPS_user_menu()
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    $menu = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $menu->set_file(array('menu' => 'user_menu.thtml'));
    $menu->set_var('maps_url', $_MAPS_CONF['site_url']);
    $menu->set_var('site_url', $_CONF['site_url']);
    $menu->set_var('maps', $LANG_MAPS_1['user_home']);
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) === 1) {
        $menu->set_var('submit_marker', ' | <a href="' . $_CONF['site_url'] . '/submit.php?type=maps">' . $LANG_MAPS_1['submit_marker'] . '</a>');
        $menu->set_var('my_markers', COM_isAnonUser() ? '' : ' | <a href="' . $_MAPS_CONF['site_url'] . '/markers.php">' . $LANG_MAPS_1['my_markers'] . '</a>');
    } else {
        $menu->set_var('submit_marker', '');
        $menu->set_var('my_markers', '');
    }
    $menu->set_var('admin', SEC_hasRights('maps.admin') ? ' | <a href="' . $_CONF['site_admin_url'] . '/plugins/maps/index.php">' . $LANG_MAPS_1['admin'] . '</a>' : '');
    return '<p>' . $menu->parse('output', 'menu') . '</p>';
}

function MAPS_admin_menu()
{
    global $_CONF, $LANG_MAPS_1;

    $menu = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $menu->set_file(array('menu' => 'admin_menu.thtml'));
    $menu->set_var('site_admin_url', $_CONF['site_admin_url']);
    $menu->set_var('site_url', $_CONF['site_url']);
    $menu->set_var('admin_home', $LANG_MAPS_1['maps']);
    $menu->set_var('markers', $LANG_MAPS_1['markers']);
    $menu->set_var('icons', $LANG_MAPS_1['icons']);
    $menu->set_var('overlays', $LANG_MAPS_1['overlays']);
    $menu->set_var('import_export', $LANG_MAPS_1['import_export']);
    $menu->set_var('geocoder', $LANG_MAPS_1['geocoder']);
    $menu->set_var('set_user_geo', $LANG_MAPS_1['set_user_geo']);
    $menu->set_var('configuration', isset($LANG_MAPS_1['configuration']) ? $LANG_MAPS_1['configuration'] : 'Configuration');
    return '<p>' . $menu->parse('output', 'menu') . '</p>';
}

function MAPS_filterVars($vars, &$arr)
{
    foreach ($vars as $var => $type) {
        $value = isset($arr[$var]) ? $arr[$var] : '';
        switch ($type) {
            case 'number':
                $arr[$var] = COM_applyFilter($value, true);
                break;
            case 'html':
                $arr[$var] = COM_checkHTML($value, 'maps.admin');
                break;
            case 'text':
                $arr[$var] = htmlspecialchars(strip_tags((string) $value), ENT_QUOTES, 'UTF-8');
                break;
            case 'int':
                $arr[$var] = (int) $value;
                break;
            default:
                $arr[$var] = COM_applyFilter($value, false);
                break;
        }
    }
}

function MAPS_geocoding()
{
    global $_CONF, $LANG_MAPS_1;
    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('block', 'geocoder.thtml');
    $t->set_var('geocoder', $LANG_MAPS_1['geocoder']);
    $t->set_var('geocoder_text', $LANG_MAPS_1['geocoder_text']);
    $t->set_var('go', $LANG_MAPS_1['go']);
    $t->parse('output', 'block');
    return $t->finish($t->get_var('output'));
}

function MAPS_recurseMaps($sel)
{
    global $_TABLES, $LANG_MAPS_1;
    $str = '';
    $result = DB_query("SELECT mid,name FROM {$_TABLES['maps_maps']} ORDER BY name");
    while ($row = DB_fetchArray($result, false)) {
        $count = DB_count($_TABLES['maps_markers'], 'mid', $row['mid']);
        $unit = $count == 1 ? $LANG_MAPS_1['1marker'] : $LANG_MAPS_1['xmarkers'];
        $selected = ((string) $row['mid'] === (string) $sel) ? ' selected="selected"' : '';
        $str .= '<option value="' . (int) $row['mid'] . '"' . $selected . '>'
            . (int) $row['mid'] . '. ' . htmlspecialchars(stripslashes($row['name']), ENT_QUOTES, 'UTF-8')
            . ' (' . (int) $count . ' ' . $unit . ')</option>' . LB;
    }
    return $str;
}

/** Fill shared map template variables. */
function MAPS_fillMapCommon($t, $id, $lat, $lng, $zoom, $type, $width, $height)
{
    global $_MAPS_CONF, $LANG_MAPS_1;
    $t->set_var('mid', $id);
    $t->set_var('gid', $id);
    $t->set_var('lat', (float) $lat);
    $t->set_var('lng', (float) $lng);
    $t->set_var('zoom', (int) $zoom);
    $t->set_var('map_type', preg_replace('/[^A-Z_]/', '', strtoupper($type)));
    $t->set_var('map_width', $width);
    $t->set_var('map_height', $height);
    $t->set_var('current_address', '');
    $t->set_var('directions_input', '');
    $t->set_var('overlays_js', '');
    $t->set_var('overlays', '');
    $t->set_var('overlays_checkboxes', '');
    $t->set_var('date_and_hits', '');
    $t->set_var('edit_button', '');
    $t->set_var('error_address_empty', $LANG_MAPS_1['error_address_empty']);
    $t->set_var('error_invalid_address', $LANG_MAPS_1['error_invalid_address']);
    $t->set_var('error_google_error', $LANG_MAPS_1['error_google_error']);
    $t->set_var('error_no_map_info', $LANG_MAPS_1['error_no_map_info']);
    $t->set_var('markerclusterer', (int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0) ? MAPS_clusterScriptTag() : '');
}

function MAPS_getMap($mid, $zoom = '', $address = '')
{
    global $_CONF, $_TABLES, $_MAPS_CONF, $LANG_MAPS_1, $LANG_LOGIN, $_SCRIPTS;

    $mid = (int) $mid;
    if ($mid < 1) {
        return '';
    }
    $res = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid={$mid} LIMIT 1");
    if (DB_numRows($res) !== 1) {
        return '';
    }
    $a = DB_fetchArray($res);
    if ((int) $a['active'] === 0 && !SEC_hasRights('maps.admin')) {
        return '';
    }
    if (SEC_hasAccess($a['owner_id'], $a['group_id'], $a['perm_owner'], $a['perm_group'], $a['perm_members'], $a['perm_anon']) < 2) {
        return MAPS_message($LANG_MAPS_1['private_access'], $LANG_MAPS_1['private_access']);
    }

    $lat = (float) $a['lat'];
    $lng = (float) $a['lng'];
    if (trim($address) !== '') {
        MAPS_getDBCoords($gid, $address, $lat, $lng);
    }
    $mapZoom = is_numeric($zoom) ? (int) $zoom : (int) $a['zoom'];

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'map.thtml');
    MAPS_fillMapCommon($t, $mid, $lat, $lng, $mapZoom, $a['type'], $a['width'], $a['height']);
    $t->set_var('name', htmlspecialchars(stripslashes($a['name']), ENT_QUOTES, 'UTF-8'));
    $t->set_var('description', $a['description'] !== '' ? '<p>' . PLG_replaceTags(stripslashes($a['description'])) . '</p>' : '');
    $t->set_var('header', $a['header'] !== '' ? PLG_replaceTags($a['header']) : '');
    $t->set_var('footer', $a['footer'] !== '' ? PLG_replaceTags($a['footer']) : '');
    $t->set_var('address', htmlspecialchars($address !== '' ? $address : $a['geo'], ENT_QUOTES, 'UTF-8'));

    $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE mid={$mid} AND active=1 AND hidden=0";
    $markers = 'var markers = [];' . LB
        . MAPS_getMarkers($sql, $a['primary_color'], $a['stroke_color'], $a['label'], ((int) $a['label_color'] ? '#FFFFFF' : '#000000'), 0, $a['name'], $mid, $a);
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $markers .= MAPS_clusterInitJs('map' . $mid);
    }
    $t->set_var('markers', $markers);

    $overlays = '';
    $checks = '';
    $or = DB_query("SELECT o.* FROM {$_TABLES['maps_map_overlay']} mo LEFT JOIN {$_TABLES['maps_overlays']} o ON mo.mo_oid=o.oid WHERE mo.mo_mid={$mid} AND o.o_active=1 ORDER BY o.o_order");
    $oi = 0;
    while ($o = DB_fetchArray($or)) {
        $oi++;
        $image = $_MAPS_CONF['images_overlay_url'] . rawurlencode($o['o_image']);
        $overlays .= "var bounds{$oi}=new google.maps.LatLngBounds({lat:" . (float) $o['o_sw_lat'] . ",lng:" . (float) $o['o_sw_lng'] . "},{lat:" . (float) $o['o_ne_lat'] . ",lng:" . (float) $o['o_ne_lng'] . "});\n"
            . "var overlay{$oi}=new google.maps.GroundOverlay(" . MAPS_jsString($image) . ",bounds{$oi});\n"
            . "overlay{$oi}.setMap(map{$mid});\n";
        $checks .= '<div><strong>' . htmlspecialchars($o['o_name'], ENT_QUOTES, 'UTF-8') . '</strong><br><img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:200px;height:auto"></div>';
    }
    $t->set_var('overlays', $overlays);
    $t->set_var('overlays_checkboxes', $checks);

    $modified = COM_getUserDateTimeFormat($a['modified']);
    $t->set_var('date_and_hits', $LANG_MAPS_1['last_modification'] . ' ' . $modified[0] . ' | ' . (int) $a['hits'] . ' ' . $LANG_MAPS_1['hits']);
    if (SEC_hasRights('maps.admin')) {
        $t->set_var('edit_button', '<p><a href="' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&amp;mid=' . $mid . '">' . $LANG_MAPS_1['edit_button'] . '</a></p>');
    }
    $t->parse('output', 'page');
    hitMap($mid);
    return $t->finish($t->get_var('output'));
}

function MAPS_getGlobalMap($zoom = '', $address = '', $pagelist = false)
{
    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF, $_CONF;

    if ((int) MAPS_arrayGet($_MAPS_CONF, 'global_map', 1) !== 1) {
        return '';
    }
    $first = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 ORDER BY mid ASC LIMIT 1");
    if (DB_numRows($first) < 1) {
        return '';
    }
    $a = DB_fetchArray($first);
    $lat = (float) $a['lat'];
    $lng = (float) $a['lng'];
    if ($address !== '') {
        MAPS_getDBCoords($gid, $address, $lat, $lng);
    }
    $mapZoom = is_numeric($zoom) ? (int) $zoom : (int) MAPS_arrayGet($_MAPS_CONF, 'global_zoom', 2);

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'map.thtml');
    MAPS_fillMapCommon($t, 0, $lat, $lng, $mapZoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), MAPS_arrayGet($_MAPS_CONF, 'global_width', '100%'), MAPS_arrayGet($_MAPS_CONF, 'global_height', '600px'));
    $t->set_var('name', $pagelist ? '' : $LANG_MAPS_1['global_map']);
    $t->set_var('description', $pagelist ? '' : '<p>' . $LANG_MAPS_1['info_global_map'] . '</p>');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('address', htmlspecialchars($address !== '' ? $address : $a['geo'], ENT_QUOTES, 'UTF-8'));

    $js = 'var markers = [];' . LB;
    $maps = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 AND hidden=0 ORDER BY mid ASC");
    while ($m = DB_fetchArray($maps)) {
        if (!SEC_hasAccess2($m)) {
            continue;
        }
        $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE mid=" . (int) $m['mid'] . " AND active=1 AND hidden=0";
        $js .= MAPS_getMarkers($sql, $m['primary_color'], $m['stroke_color'], $m['label'], ((int) $m['label_color'] ? '#FFFFFF' : '#000000'), 1, $m['name'], 0, $m);
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('map0');
    }
    $t->set_var('markers', $js);
    $t->parse('output', 'page');
    hitMap(0);
    return $t->finish($t->get_var('output'));
}

function MAPS_getMarkers($sql, $primaryColor, $strokecolor, $label, $label_color, $global, $mapname = '', $mapid = '', $map = array())
{
    $markers = '';
    if ($sql === '') {
        return $markers;
    }
    $res = DB_query($sql);
    while ($marker = DB_fetchArray($res)) {
        if (SEC_hasAccess2($marker)) {
            $markers .= MAPS_renderMarker($marker, $primaryColor, $strokecolor, $label, $label_color, $mapname, '', '', $mapid, true, $map);
        }
    }
    return $markers;
}

function MAPS_renderMarker($marker, $primaryColor, $strokecolor, $label, $label_color, $mapname = '', $field_tab = '', $category = '', $mapid = 0, $infos = true, $map3 = array())
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1, $_TABLES, $_USER;

    if (!is_array($marker) || !MAPS_checkMarkervalidity($marker)) {
        return '';
    }
    if ((int) MAPS_arrayGet($marker, 'active', 1) === 0
        || ((int) MAPS_arrayGet($marker, 'hidden', 0) === 1 && !SEC_hasRights('maps.admin'))) {
        return '';
    }

    $mkid = preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $marker['mkid']);
    $var = 'marker' . $mkid;
    $mapVar = 'map' . $mapid;
    $name = stripslashes((string) MAPS_arrayGet($marker, 'name', ''));
    $lat = (float) MAPS_arrayGet($marker, 'lat', 0);
    $lng = (float) MAPS_arrayGet($marker, 'lng', 0);
    $iconUrl = '';

    $markerIconId = (int) MAPS_arrayGet($marker, 'mk_icon', 0);
    $useDefault = (int) MAPS_arrayGet($marker, 'mk_default', 1);
    $mapDefault = (int) MAPS_arrayGet($map3, 'mmk_default', 1);
    $mapIconId = (int) MAPS_arrayGet($map3, 'mmk_icon', 0);
    $selectedIcon = $useDefault === 0 ? $markerIconId : ($mapDefault === 0 ? $mapIconId : 0);

    if ($selectedIcon > 0) {
        $icon = DB_getItem($_TABLES['maps_map_icons'], 'icon_image', 'icon_id=' . $selectedIcon);
        $path = $_MAPS_CONF['path_icons_images'] . $icon;
        if ($icon !== '' && file_exists($path) && @getimagesize($path) !== false) {
            $iconUrl = $_MAPS_CONF['images_icons_url'] . rawurlencode($icon);
        }
    }
    if ($iconUrl === '') {
        $fill = $useDefault === 0 ? MAPS_arrayGet($marker, 'mk_pcolor', $primaryColor) : $primaryColor;
        $text = $useDefault === 0 ? MAPS_arrayGet($marker, 'mk_label', $label) : $label;
        $textColor = $useDefault === 0 && (int) MAPS_arrayGet($marker, 'mk_label_color', 0) ? '#FFFFFF' : $label_color;
        $iconUrl = MAPS_svgMarkerUri($fill, $text, $textColor);
    }

    $js = "var {$var}=new google.maps.Marker({position:{lat:{$lat},lng:{$lng}},map:{$mapVar},title:" . MAPS_jsString($name) . ",icon:" . MAPS_jsString($iconUrl) . "});\n";

    if ($infos) {
        $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
        if ((string) $mapid === 'events') {
            $template->set_file(array('presentation' => 'presentation_tab_event.thtml'));
            $template->set_var('name', '<strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>');
            $description = (string) MAPS_arrayGet($marker, 'description', '');
            if (function_exists('mb_substr')) {
                $description = mb_substr($description, 0, 150, 'UTF-8');
            } else {
                $description = substr($description, 0, 150);
            }
            $template->set_var('description', $description !== '' ? '<p>' . $description . '</p>' : '');
            $template->set_var('read_more', '<a href="' . $_CONF['site_url'] . '/calendar/event.php?eid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['read_more'] . '</a>');
            $template->set_var('map', '');
        } else {
            $template->set_file(array('presentation' => 'presentation_tab.thtml'));
            $template->set_var('name', '<strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>');
            $description = stripslashes((string) MAPS_arrayGet($marker, 'description', ''));
            if (function_exists('mb_substr')) {
                $short = mb_substr($description, 0, 150, 'UTF-8');
            } else {
                $short = substr($description, 0, 150);
            }
            $template->set_var('description', $short !== '' ? '<p>' . nl2br(htmlspecialchars($short, ENT_QUOTES, 'UTF-8')) . (strlen($description) > strlen($short) ? '…' : '') . '</p>' : '');
            $resources = '';
            for ($i = 1; $i <= 10; $i++) {
                $cfg = MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, '');
                $val = MAPS_arrayGet($marker, 'item_' . $i, '');
                $template->set_var('item_' . $i . '_label', $cfg);
                $template->set_var('item_' . $i, PLG_replaceTags(stripslashes($val)));
                if ($cfg !== '' && $val !== '') {
                    $resources .= '<h3>' . htmlspecialchars($cfg, ENT_QUOTES, 'UTF-8') . '</h3><p>' . PLG_replaceTags(stripslashes($val)) . '</p>';
                }
            }
            $template->set_var('ressources', $resources);
            $readMore = MAPS_arrayGet($marker, 'url', '') !== '' ? $_CONF['site_url'] . '/' . ltrim($marker['url'], '/') : $_MAPS_CONF['site_url'] . '/markers.php?mode=show&amp;mkid=' . rawurlencode($marker['mkid']) . '&amp;mid=' . (int) $marker['mid'];
            $template->set_var('read_more', '<a href="' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '">' . $LANG_MAPS_1['read_more'] . '</a>');
            $template->set_var('map', '<p>' . $LANG_MAPS_1['on_map'] . ' <a href="' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $marker['mid'] . '">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></p>');
            $ownerUid = isset($_USER['uid']) ? (int) $_USER['uid'] : 1;
            $template->set_var('edit', SEC_hasRights('maps.admin') || $ownerUid === (int) MAPS_arrayGet($marker, 'owner_id', 0) ? '<a href="' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');
        }
        $presentation = $template->parse('output', 'presentation');
        $infoVar = 'infowindow' . $mkid;
        $js .= "var {$infoVar}=new google.maps.InfoWindow({content:" . MAPS_jsString($presentation) . "});\n"
            . "{$var}.addListener('click',function(){ {$infoVar}.open({map:{$mapVar},anchor:{$var}}); });\n";
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= "markers.push({$var});\n";
    }
    return $js;
}

function MAPS_checkMarkervalidity($marker)
{
    if (!is_array($marker) || !SEC_hasAccess2($marker)) {
        return false;
    }
    if ((int) MAPS_arrayGet($marker, 'validity', 0) === 1 && !SEC_hasRights('maps.admin')) {
        $now = time();
        $start = strtotime(MAPS_arrayGet($marker, 'validity_start', ''));
        $end = strtotime(MAPS_arrayGet($marker, 'validity_end', ''));
        if (($start && $now < $start) || ($end && $now > $end)) {
            return false;
        }
    }
    return true;
}

function MAPS_selectedMarkers($width, $height, $zoom, $mkids = array())
{
    global $_TABLES;
    if (!is_array($mkids) || empty($mkids)) {
        return '';
    }
    $ids = array();
    foreach ($mkids as $id) {
        $ids[] = "'" . MAPS_dbEscape($id) . "'";
    }
    $res = DB_query("SELECT mk.*,ma.mmk_default,ma.mmk_icon,ma.name AS mapname,ma.primary_color,ma.stroke_color,ma.label,ma.label_color FROM {$_TABLES['maps_markers']} mk LEFT JOIN {$_TABLES['maps_maps']} ma ON mk.mid=ma.mid WHERE mk.mkid IN (" . implode(',', $ids) . ')');
    $markers = array();
    $lat = 0;
    $lng = 0;
    while ($a = DB_fetchArray($res)) {
        $markers[] = $a;
        $lat += (float) $a['lat'];
        $lng += (float) $a['lng'];
    }
    if (empty($markers)) {
        return '';
    }
    $lat /= count($markers);
    $lng /= count($markers);
    return MAPS_getMarkerDetail(0, $markers, 1, $width, $height, $zoom, $lat, $lng);
}

function MAPS_getMarkerDetail($mid = 0, $mkid = '', $autotag = 0, $mapwidth = '100%', $mapheight = '300px', $mapzoom = '10', $lat = '0', $lng = '0')
{
    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF, $_CONF;

    $list = array();
    $map = array('mmk_default' => 1, 'mmk_icon' => 0, 'name' => '', 'primary_color' => '#666666', 'stroke_color' => '#333333', 'label' => '', 'label_color' => 0);
    if ((int) $mid > 0) {
        $resMap = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . (int) $mid . ' LIMIT 1');
        if (DB_numRows($resMap) < 1) {
            return '';
        }
        $map = DB_fetchArray($resMap);
        if (!SEC_hasAccess2($map)) {
            return '';
        }
        $safe = MAPS_dbEscape($mkid);
        $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='{$safe}' LIMIT 1");
        if (DB_numRows($res) < 1) {
            return '';
        }
        $marker = DB_fetchArray($res);
        if (!MAPS_checkMarkervalidity($marker)) {
            return $LANG_MAPS_1['marker_limited'];
        }
        $list[] = $marker;
        $lat = $marker['lat'];
        $lng = $marker['lng'];
        $mapwidth = MAPS_arrayGet($_MAPS_CONF, 'global_width', $mapwidth);
        $mapheight = MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', $mapheight);
        $mapzoom = MAPS_arrayGet($_MAPS_CONF, 'detail_zoom', $mapzoom);
    } elseif (is_array($mkid)) {
        $list = $mkid;
    }
    if (empty($list)) {
        return '';
    }

    $id = (int) $mid > 0 ? preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $list[0]['mkid']) : mt_rand(10000, 999999);
    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', $autotag ? 'map_autotag.thtml' : 'map.thtml');
    MAPS_fillMapCommon($t, $id, $lat, $lng, $mapzoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), $mapwidth, $mapheight);
    $t->set_var('name', '');
    $t->set_var('description', '');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('address', '');

    $js = 'var markers = [];' . LB;
    foreach ($list as $marker) {
        if (!MAPS_checkMarkervalidity($marker)) {
            continue;
        }
        $js .= MAPS_renderMarker($marker, MAPS_arrayGet($map, 'primary_color', '#666666'), MAPS_arrayGet($map, 'stroke_color', '#333333'), MAPS_arrayGet($map, 'label', ''), (int) MAPS_arrayGet($map, 'label_color', 0) ? '#FFFFFF' : '#000000', MAPS_arrayGet($map, 'name', MAPS_arrayGet($marker, 'mapname', '')), '', '', $id, true, $map);
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('map' . $id);
    }
    $t->set_var('markers', $js);

    if (!$autotag && count($list) === 1) {
        $gid = $id;
        $address = MAPS_arrayGet($list[0], 'address', '');
        $value = htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8');
        $t->set_var('current_address', htmlspecialchars($address, ENT_QUOTES, 'UTF-8'));
        $t->set_var('directions_input', '<div id="map_directions"><small>' . $LANG_MAPS_1['need_directions'] . '<br><input type="text" id="from_address' . $gid . '" value="' . $value . '" size="30"><input type="button" value="' . htmlspecialchars($LANG_MAPS_1['get_directions'], ENT_QUOTES, 'UTF-8') . '" onclick="get_directions' . $gid . '();"></small></div><a id="directions_' . $gid . '_table"></a><div id="directions' . $gid . '"></div>');
    }
    $t->parse('output', 'page');
    return $t->finish($t->get_var('output'));
}

function plugin_ismoderator_maps()
{
    return SEC_hasRights('maps.admin');
}

function plugin_submissioncount_maps()
{
    global $_TABLES;
    return DB_count($_TABLES['maps_submission']);
}

function plugin_submit_maps($marker = array())
{
    global $_CONF, $_MAPS_CONF, $LANG12, $LANG_MAPS_1, $LANG_LOGIN, $_SCRIPTS;

    if (!is_array($marker)) {
        $marker = array();
    }
    if (SEC_hasRights('maps.admin')) {
        return COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/marker_edit.php');
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) !== 1) {
        return $LANG_MAPS_1['submission_disabled'];
    }
    if (COM_isAnonUser() && ((int) MAPS_arrayGet($_CONF, 'loginrequired', 0) || (int) MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) || (int) MAPS_arrayGet($_MAPS_CONF, 'submit_login_required', 0))) {
        return COM_startBlock($LANG_LOGIN[1]) . $LANG_LOGIN[2] . COM_endBlock();
    }

    $defaults = array(
        'name' => '', 'address' => '', 'lat' => '37.4217913', 'lng' => '-122.0837139', 'mid' => '',
        'created' => time(), 'modified' => time(), 'description' => '', 'street' => '', 'code' => '', 'city' => '',
        'state' => '', 'country' => '', 'tel' => '', 'fax' => '', 'web' => '', 'remark' => '', 'validity' => 0,
        'from' => '', 'to' => '', 'group_id' => 2, 'mkid' => ''
    );
    for ($i = 1; $i <= 10; $i++) {
        $defaults['item_' . $i] = '';
    }
    $marker = array_merge($defaults, $marker);

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('form' => 'marker_submit_form.thtml'));
    $template->set_var('site_url', $_CONF['site_url']);
    $template->set_var('go', $LANG_MAPS_1['go']);
    $template->set_var('submit_marker', $LANG_MAPS_1['submit_marker']);
    $template->set_var('submit_marker_text', $LANG_MAPS_1['submit_marker_text']);
    $template->set_var('lang_preview', $LANG12[32]);
    $template->set_var('lang_save', $LANG12[8]);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['marker_name_label']);
    $template->set_var('name', htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('address_label', $LANG_MAPS_1['address_label']);
    $template->set_var('address', htmlspecialchars($marker['address'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('empty_for_geo', $LANG_MAPS_1['empty_for_geo']);
    $template->set_var('lat', $LANG_MAPS_1['lat']);
    $template->set_var('lat_value', $marker['lat']);
    $template->set_var('lng', $LANG_MAPS_1['lng']);
    $template->set_var('lng_value', $marker['lng']);
    $template->set_var('select_marker_map', $LANG_MAPS_1['select_marker_map']);
    $template->set_var('mid_label', $LANG_MAPS_1['name_label']);
    $template->set_var('mid', $marker['mid']);
    $template->set_var('map_options', MAPS_recurseMaps($marker['mid']));
    $template->set_var('created_label', $LANG_MAPS_1['marker_created']);
    $template->set_var('modified_label', $LANG_MAPS_1['modified']);
    $template->set_var('created', is_numeric($marker['created']) ? date('Y-m-d H:i', $marker['created']) : $marker['created']);
    $template->set_var('modified', is_numeric($marker['modified']) ? date('Y-m-d H:i', $marker['modified']) : $marker['modified']);
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('presentation_tab', $LANG_MAPS_1['presentation_tab']);
    $template->set_var('description_label', $LANG_MAPS_1['description_label']);
    $template->set_var('description', htmlspecialchars($marker['description'], ENT_QUOTES, 'UTF-8'));

    foreach (array('street','code','city','state','country','tel','fax','web') as $field) {
        $labelKey = $field . '_label';
        $template->set_var($labelKey, $LANG_MAPS_1[$labelKey]);
        $template->set_var($field, '<input type="text" name="' . $field . '" value="' . htmlspecialchars($marker[$field], ENT_QUOTES, 'UTF-8') . '">');
    }
    $template->set_var('ressources_tab', $LANG_MAPS_1['ressources_tab']);
    $template->set_var('max_char', $LANG_MAPS_1['max_char']);
    $resources = '';
    for ($i = 1; $i <= 10; $i++) {
        $key = 'item_' . $i;
        $label = MAPS_arrayGet($_MAPS_CONF, $key, '');
        $template->set_var($key . '_label', $label);
        $template->set_var($key, htmlspecialchars($marker[$key], ENT_QUOTES, 'UTF-8'));
        if ($label !== '') {
            $resources .= '<p>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ' <input type="text" name="' . $key . '" value="' . htmlspecialchars($marker[$key], ENT_QUOTES, 'UTF-8') . '"></p>';
        }
    }
    $template->set_var('ressources', $resources !== '' ? $resources : $LANG_MAPS_1['empty_ressources']);
    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_button', $LANG_MAPS_1['delete_button']);
    $template->set_var('mkid', $marker['mkid'] !== '' ? '<input type="hidden" name="mkid" value="' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '">' : '');
    $template->set_var('arrow', '');

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    $js = "jQuery(function(){var el=document.getElementById('map_canvas');if(!el||!window.google){return;}var map=new google.maps.Map(el,{center:{lat:" . (float) $marker['lat'] . ",lng:" . (float) $marker['lng'] . "},zoom:10,mapTypeId:google.maps.MapTypeId.ROADMAP});var mk=new google.maps.Marker({map:map,position:{lat:" . (float) $marker['lat'] . ",lng:" . (float) $marker['lng'] . "},draggable:true});mk.addListener('dragend',function(e){document.getElementById('lat').value=e.latLng.lat().toFixed(6);document.getElementById('lng').value=e.latLng.lng().toFixed(6);});window.codeAddress=function(){var g=new google.maps.Geocoder();g.geocode({address:document.getElementById('geoaddress').value},function(r,s){if(s==='OK'&&r[0]){map.setCenter(r[0].geometry.location);mk.setPosition(r[0].geometry.location);document.getElementById('lat').value=r[0].geometry.location.lat();document.getElementById('lng').value=r[0].geometry.location.lng();}else{alert('Geocode: '+s);}});};});";
    $_SCRIPTS->setJavaScript($js, true, true);
    return $template->parse('output', 'form');
}

function plugin_savesubmission_maps($a)
{
    global $_TABLES, $_USER, $LANG_MAPS_1, $_MAPS_CONF;

    if (!is_array($a) || empty($a['name']) || empty($a['address'])) {
        COM_resetSpeedlimit('submit');
        return MAPS_message($LANG_MAPS_1['missing_field'], $LANG_MAPS_1['error']) . plugin_submit_maps(is_array($a) ? $a : array());
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) !== 1) {
        return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=2');
    }

    $ownerId = isset($_USER['uid']) && (int) $_USER['uid'] > 0 ? (int) $_USER['uid'] : 1;
    $lat = isset($a['lat']) ? (float) $a['lat'] : 0;
    $lng = isset($a['lng']) ? (float) $a['lng'] : 0;
    if ($lat == 0 && $lng == 0) {
        MAPS_getCoords($a['address'], $lat, $lng);
    }
    $mkid = COM_makeSid();
    $now = date('YmdHis');
    $from = !empty($a['from']) ? date('YmdHis', strtotime($a['from'])) : $now;
    $to = !empty($a['to']) ? date('YmdHis', strtotime($a['to'])) : $now;
    if (strtotime($from) > strtotime($to)) {
        return MAPS_message($LANG_MAPS_1['date_issue'], $LANG_MAPS_1['error']) . plugin_submit_maps($a);
    }

    $fields = array('name','description','address','remark','street','code','city','state','country','tel','fax','web');
    for ($i = 1; $i <= 10; $i++) {
        $fields[] = 'item_' . $i;
    }
    foreach ($fields as $field) {
        $a[$field] = MAPS_dbEscape(MAPS_arrayGet($a, $field, ''));
    }
    $mid = (int) MAPS_arrayGet($a, 'mid', 0);
    $groupId = (int) MAPS_arrayGet($a, 'group_id', 2);
    $perm = MAPS_arrayGet($_MAPS_CONF, 'default_permissions', array(3,3,2,2));
    if (!is_array($perm) || count($perm) < 4) {
        $perm = array(3,3,2,2);
    }

    $columns = 'mkid,name,description,created,modified,payed,validity,validity_start,validity_end,active,hidden,address,lat,lng,mid,remark,street,city,code,state,country,tel,fax,web,item_1,item_2,item_3,item_4,item_5,item_6,item_7,item_8,item_9,item_10,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon,submission,mk_default';
    $values = array("'" . MAPS_dbEscape($mkid) . "'", "'{$a['name']}'", "'{$a['description']}'", "'{$now}'", "'{$now}'", '0', (int) MAPS_arrayGet($a, 'validity', 0), "'{$from}'", "'{$to}'", '1', '0', "'{$a['address']}'", (float) $lat, (float) $lng, $mid, "'{$a['remark']}'", "'{$a['street']}'", "'{$a['city']}'", "'{$a['code']}'", "'{$a['state']}'", "'{$a['country']}'", "'{$a['tel']}'", "'{$a['fax']}'", "'{$a['web']}'");
    for ($i = 1; $i <= 10; $i++) {
        $values[] = "'{$a['item_{$i}']}'";
    }
    $values = array_merge($values, array($ownerId, $groupId, (int) $perm[0], (int) $perm[1], (int) $perm[2], (int) $perm[3], 1, 1));
    DB_save($_TABLES['maps_submission'], $columns, implode(',', $values));
    if (DB_error()) {
        return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=3');
    }
    $a['mkid'] = $mkid;
    MAPS_sendNotification($a);
    return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=1');
}

function MAPS_sendNotification($data, $modification = false)
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_EMAIL, $_TABLES;
    $name = stripslashes(MAPS_arrayGet($data, 'name', ''));
    $description = stripslashes(MAPS_arrayGet($data, 'description', ''));
    $map = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) MAPS_arrayGet($data, 'mid', 0));
    $action = $modification ? $LANG_MAPS_EMAIL['marker_modification'] : $LANG_MAPS_EMAIL['new_marker'];
    $body = $action . "\n\n" . $LANG_MAPS_EMAIL['name'] . ' ' . $name . "\n" . $LANG_MAPS_EMAIL['description'] . ' ' . $description . "\n\n" . $LANG_MAPS_EMAIL['on_map'] . ' ' . $map . "\n\n";
    if ($modification) {
        $body .= $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode(MAPS_arrayGet($data, 'mkid', '')) . '&mid=' . (int) MAPS_arrayGet($data, 'mid', 0);
        $subject = $_CONF['site_name'] . ' ' . $LANG_MAPS_EMAIL['marker_modification'];
    } else {
        $body .= $LANG_MAPS_EMAIL['submissions'] . ' >> ' . $_CONF['site_admin_url'] . '/moderation.php';
        $subject = $_CONF['site_name'] . ' ' . $LANG_MAPS_EMAIL['marker_submissions'];
    }
    COM_mail($_CONF['site_mail'], $subject, $body);
}

function plugin_moderationvalues_maps()
{
    global $_TABLES;
    return array('mkid', $_TABLES['maps_markers'], 'mkid,name,address,lat,lng,mid,mk_default,payed,created,modified,validity,validity_start,validity_end,active,hidden,remark,description,street,code,city,state,country,tel,fax,web,item_1,item_2,item_3,item_4,item_5,item_6,item_7,item_8,item_9,item_10,hits,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon,submission', $_TABLES['maps_submission']);
}

function plugin_itemlist_maps()
{
    global $_TABLES, $LANG_MAPS_1;
    if (!plugin_ismoderator_maps()) {
        return null;
    }
    $plugin = new Plugin();
    $plugin->submissionlabel = $LANG_MAPS_1['markers_submissions'];
    $plugin->getsubmissionssql = "SELECT mkid AS id,name,created,address FROM {$_TABLES['maps_submission']}";
    $plugin->addSubmissionHeading($LANG_MAPS_1['name']);
    $plugin->addSubmissionHeading($LANG_MAPS_1['created']);
    $plugin->addSubmissionHeading($LANG_MAPS_1['address']);
    return $plugin;
}

function plugin_moderationapprove_maps($id)
{
    global $_TABLES;
    $gid = DB_getItem($_TABLES['groups'], 'grp_id', "grp_name='Maps Admin'");
    DB_query("UPDATE {$_TABLES['maps_markers']} SET active=1,submission=0,group_id=" . (int) $gid . " WHERE mkid='" . MAPS_dbEscape($id) . "'");
    return DB_error() ? 'Error' : '';
}

function plugin_moderationdelete_maps($id)
{
    return '';
}

function hitMap($map)
{
    global $_TABLES;
    if ((int) $map === 0) {
        DB_query("UPDATE {$_TABLES['vars']} SET value=value+1 WHERE name='globalMapHits'");
    } else {
        DB_query("UPDATE {$_TABLES['maps_maps']} SET hits=hits+1 WHERE mid=" . (int) $map);
    }
}

function hitMarker($mkid)
{
    global $_TABLES;
    DB_query("UPDATE {$_TABLES['maps_markers']} SET hits=hits+1 WHERE mkid='" . MAPS_dbEscape($mkid) . "'");
}

function updateMap($map)
{
    global $_TABLES;
    DB_query("UPDATE {$_TABLES['maps_maps']} SET modified='" . date('YmdHis') . "' WHERE mid=" . (int) $map);
}

function plugin_getuseroption_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1;
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) === 1) {
        return array($LANG_MAPS_1['my_markers'], $_MAPS_CONF['site_url'] . '/markers.php', 0);
    }
    return array();
}

function MAPS_message($message, $title = '')
{
    global $LANG_MAPS_MESSAGE;
    if ($message === '') {
        return '';
    }
    $defaultTitle = isset($LANG_MAPS_MESSAGE['message']) ? $LANG_MAPS_MESSAGE['message'] : 'Message';
    return COM_startBlock($title === '' ? $defaultTitle : $title) . stripslashes($message) . COM_endBlock();
}

function MAPS_ViewMarkerInfos($mkid)
{
    global $LANG_MAPS_1, $_TABLES, $_CONF, $_MAPS_CONF, $_USER;
    if ($mkid === '') {
        return '';
    }
    $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='" . MAPS_dbEscape($mkid) . "' LIMIT 1");
    if (DB_numRows($res) < 1) {
        return '';
    }
    $marker = DB_fetchArray($res);
    if (!MAPS_checkMarkervalidity($marker)) {
        return '';
    }

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('presentation' => 'marker.thtml'));
    $template->set_var('name', '<span style="text-transform:uppercase">' . htmlspecialchars(stripslashes($marker['name']), ENT_QUOTES, 'UTF-8') . '</span>');
    $template->set_var('description', $marker['description'] !== '' ? '<p>' . nl2br(htmlspecialchars(stripslashes($marker['description']), ENT_QUOTES, 'UTF-8')) . '</p>' : '');
    foreach (array('street','code','city','state','country','tel','fax') as $field) {
        $value = MAPS_arrayGet($marker, $field, '');
        $template->set_var($field, $value !== '' && (int) MAPS_arrayGet($_MAPS_CONF, $field, 1) ? '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8') . '</p>' : '');
    }
    $template->set_var('web', MAPS_arrayGet($marker, 'web', '') !== '' ? '<p><strong>' . $LANG_MAPS_1['web_label'] . '</strong> ' . MAPS_convertLinkToUrl($marker['web']) . '</p>' : '');
    $template->set_var('space', '');
    $resources = '';
    for ($i = 1; $i <= 10; $i++) {
        $label = MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, '');
        $value = MAPS_arrayGet($marker, 'item_' . $i, '');
        if ($label !== '' && $value !== '') {
            $resources .= '<p><strong>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</strong></p><p>' . PLG_replaceTags(stripslashes($value)) . '</p>';
        }
    }
    $template->set_var('ressources', $resources);
    $uid = isset($_USER['uid']) ? (int) $_USER['uid'] : 1;
    $template->set_var('edit', SEC_hasRights('maps.admin') || $uid === (int) $marker['owner_id'] ? ' | <a href="' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');
    $mapname = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $marker['mid']);
    $template->set_var('map', ' | ' . $LANG_MAPS_1['from_map'] . ' <a href="' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $marker['mid'] . '">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a>');
    $profileUrl = $_CONF['site_url'] . '/users.php?mode=profile&amp;uid=' . (int) $marker['owner_id'];
    $template->set_var('owner', $LANG_MAPS_1['from_owner'] . ' ' . COM_createLink(COM_getDisplayName($marker['owner_id']), $profileUrl));
    $update = COM_getUserDateTimeFormat($marker['modified']);
    $template->set_var('update', '<br>' . $LANG_MAPS_1['last_modification'] . ' ' . $update[0] . ' | ' . (int) $marker['hits'] . ' ' . $LANG_MAPS_1['hits']);
    $template->set_var('report', '<a href="' . $_CONF['site_url'] . '/profiles.php?uid=2&amp;subject=' . rawurlencode($LANG_MAPS_1['report_subject'] . $marker['mkid']) . '">' . $LANG_MAPS_1['report'] . '</a>');
    $template->set_var('print', '<a href="' . $_MAPS_CONF['site_url'] . '/markers.php?mode=print&amp;mid=' . (int) $marker['mid'] . '&amp;mkid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['print'] . '</a> | ');
    $template->set_var('directions_table_result', '<a id="directions_' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '_table"></a><div id="directions' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '"></div>');
    hitMarker($mkid);
    return $template->parse('output', 'presentation');
}

/** PHP 7/8-safe replacement for the old preg_replace /e implementation. */
function MAPS_convertLinkToUrl($url, $length = 35)
{
    global $_CONF;
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('~^https?://~i', $url)) {
        $url = 'http://' . $url;
    }
    $parts = parse_url($url);
    $name = isset($parts['host']) ? $parts['host'] : $url;
    $display = strlen($name) > (int) $length ? substr($name, 0, (int) $length) . '…' : $name;
    $target = strpos($url, $_CONF['site_url']) === 0 ? '' : ' target="_blank" rel="noopener noreferrer"';
    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"' . $target . '>' . htmlspecialchars($display, ENT_QUOTES, 'UTF-8') . '</a>';
}

function MAPS_ListMarkers($mid = '')
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_ADMIN, $LANG_MAPS_1;
    if ($mid === '') {
        return '';
    }
    require_once $_CONF['path_system'] . 'lib-admin.php';
    $mid = (int) $mid;
    $header = array(
        array('text' => $LANG_MAPS_1['name'], 'field' => 'name', 'sort' => true),
        array('text' => $LANG_MAPS_1['code'], 'field' => 'code', 'sort' => true),
        array('text' => $LANG_MAPS_1['city'], 'field' => 'city', 'sort' => true)
    );
    $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE active=1 AND hidden=0" . ($mid > 0 ? ' AND mid=' . $mid : '');
    $query = array('table' => 'maps_markers', 'sql' => $sql, 'query_fields' => array('name','code','city'), 'default_filter' => COM_getPermSQL('AND', 0, 2));
    $text = array('has_extras' => true, 'form_url' => $_MAPS_CONF['site_url'] . '/index.php?mode=markers&amp;mid=' . $mid);
    $sort = array('field' => 'code', 'direction' => 'asc');
    return ADMIN_list('markers', 'MAPS_getListField_markersList', $header, $text, $query, $sort);
}

function MAPS_getListField_markersList($fieldname, $fieldvalue, $a, $icon_arr)
{
    global $_MAPS_CONF;
    if ($fieldname === 'name') {
        return COM_createLink(htmlspecialchars(stripslashes($a['name']), ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&amp;mkid=' . rawurlencode($a['mkid']) . '&amp;mid=' . (int) $a['mid']);
    }
    return htmlspecialchars(stripslashes((string) $fieldvalue), ENT_QUOTES, 'UTF-8');
}

function MAPS_setGeoLocation()
{
    global $_TABLES;
    $locations = DB_query("SELECT location FROM {$_TABLES['userinfo']} WHERE location<>''");
    while ($geo = DB_fetchArray($locations)) {
        $gid = '';
        $lat = 0;
        $lng = 0;
        MAPS_getDBCoords($gid, $geo['location'], $lat, $lng);
    }
}

function MAPS_displayEventsMap($output = true, $event_id = '')
{
    global $_TABLES, $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    if (!isset($_TABLES['events'])) {
        return '';
    }
    $first = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 ORDER BY mid ASC LIMIT 1");
    if (DB_numRows($first) < 1) {
        return '';
    }
    $a = DB_fetchArray($first);
    $lat = (float) $a['lat'];
    $lng = (float) $a['lng'];
    $zoom = (int) MAPS_arrayGet($_MAPS_CONF, 'global_zoom', 2);
    $height = MAPS_arrayGet($_MAPS_CONF, 'global_height', '600px');

    if ($event_id === '') {
        $events = DB_query("SELECT * FROM {$_TABLES['events']} WHERE dateend>=CURDATE()");
    } else {
        $events = DB_query("SELECT * FROM {$_TABLES['events']} WHERE eid='" . MAPS_dbEscape($event_id) . "' LIMIT 1");
        $zoom = 8;
        $height = '300px';
    }

    $js = 'var markers = [];' . LB;
    while ($event = DB_fetchArray($events)) {
        if (SEC_hasAccess($event['owner_id'], $event['group_id'], $event['perm_owner'], $event['perm_group'], $event['perm_members'], $event['perm_anon']) < 2 || trim($event['location']) === '') {
            continue;
        }
        $gid = '';
        $elat = 0;
        $elng = 0;
        MAPS_getDBCoords($gid, $event['location'], $elat, $elng);
        if ($event_id !== '') {
            $lat = $elat;
            $lng = $elng;
        }
        $marker = array(
            'mkid' => $event['eid'], 'hidden' => 0, 'active' => 1, 'name' => $event['title'],
            'description' => $event_id === '' ? MAPS_arrayGet($event, 'description', '') : '',
            'payed' => 0, 'mk_default' => 1, 'validity' => 0,
            'perm_owner' => 2, 'perm_group' => 2, 'perm_members' => 2, 'perm_anon' => 2,
            'owner_id' => $event['owner_id'], 'group_id' => $event['group_id'], 'lat' => $elat, 'lng' => $elng,
            'mid' => 0, 'mk_icon' => 0, 'mk_pcolor' => '#666666', 'mk_label' => '', 'mk_label_color' => 0
        );
        $js .= MAPS_renderMarker($marker, '#666666', '#333333', '', '#ffffff', $LANG_MAPS_1['events_map'], '', '', 'events', $event_id === '', array('mmk_default' => 1, 'mmk_icon' => 0));
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('mapevents');
    }

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', $output ? 'map_autotag.thtml' : 'map_only_script.thtml');
    MAPS_fillMapCommon($t, 'events', $lat, $lng, $zoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), MAPS_arrayGet($_MAPS_CONF, 'global_width', '100%'), $height);
    $t->set_var('name', $LANG_MAPS_1['events_map']);
    $t->set_var('description', '<p>' . $LANG_MAPS_1['info_events_map'] . '</p>');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('markers', $js);
    $t->parse('result', 'page');
    return $t->finish($t->get_var('result'));
}
