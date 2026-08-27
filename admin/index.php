<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | index.php                                                                 |
// |                                                                           |
// | Plugin administration page                                                |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026 by the following authors:                              |
// |                                                                           |
// | Authors: ::Ben                                                            |
// +---------------------------------------------------------------------------+
// | Created with the Geeklog Plugin Toolkit.                                  |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

/**
* @package Maps
*/

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

/*
 * Configuration and public-folder migrations are executed by
 * plugin_upgrade_maps(). Run the normal Geeklog plugin upgrade from
 * /admin/plugins.php before opening this page after installing new files.
 */

MAPS_getheadercode();

$display = '';

// Ensure user even has the rights to access this page
if (! SEC_hasRights('maps.admin')) {
    $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
             . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
             . MAPS_compatSiteFooter();

    // Log attempt to access.log
    COM_accessLog("User {$_USER['username']} tried to illegally access the Maps plugin administration screen.");

    MAPS_compatOutput($display);
    exit;
}

// Incoming variable filter
$vars = array('mode' => 'alpha',
               'cid' => 'number',
               'id'  => 'number',
               'msg' => 'text'
              );

MAPS_filterVars($vars, $_REQUEST);

/**
* List all maps that the user has access to
*
* @retun    string      HTML for the list
*
*/
function MAPS_listmaps()
{
    global $_CONF, $_TABLES, $_IMAGE_TYPE, $LANG_ADMIN, $LANG_MAPS_1, $_MAPS_CONF;

    require_once $_CONF['path_system'] . 'lib-admin.php';

    $retval = '';

    if (DB_count($_TABLES['maps_maps']) == 0){
        return $retval = '';
    }
    $header_arr = array(      // display 'text' and use table field 'field'
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
        array('text' => $LANG_MAPS_1['id'], 'field' => 'mid', 'sort' => true),
        array('text' => $LANG_MAPS_1['name'], 'field' => 'name', 'sort' => true)
    );
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'stats_admin_enabled', 1) === 1) {
        $header_arr[] = array('text' => $LANG_MAPS_1['marker_count'], 'field' => 'marker_count', 'sort' => true);
        $header_arr[] = array('text' => $LANG_MAPS_1['hits'], 'field' => 'hits', 'sort' => true);
    }
    $header_arr[] = array('text' => $LANG_MAPS_1['active_field'], 'field' => 'active', 'sort' => true);
    $header_arr[] = array('text' => $LANG_MAPS_1['hidden_field'], 'field' => 'hidden', 'sort' => true);
    $defsort_arr = array('field' => 'mid', 'direction' => 'asc');

    $text_arr = array(
        'has_extras' => true,
        'form_url' => $_CONF['site_admin_url'] . '/plugins/maps/index.php'
    );

    $sql = "SELECT m.*,
                   (SELECT COUNT(*) FROM {$_TABLES['maps_markers']} AS mm WHERE mm.mid = m.mid) AS marker_count
            FROM {$_TABLES['maps_maps']} AS m
            WHERE 1=1";

    $query_arr = array(
        'table'          => 'maps_maps',
        'sql'            => $sql,
        'query_fields'   => array('name', 'description'),
        'default_filter' => COM_getPermSQL ('AND', 0, 3)
    );

    $retval .= ADMIN_list('maps', 'plugin_getListField_maps',
                          $header_arr, $text_arr, $query_arr, $defsort_arr);

    return $retval;
}

/**
*   Get an individual field for the maps screen.
*
*   @param  string  $fieldname  Name of field (from the array, not the db)
*   @param  mixed   $fieldvalue Value of the field
*   @param  array   $A          Array of all fields from the database
*   @param  array   $icon_arr   System icon array
*   @param  object  $EntryList  This entry list object
*   @return string              HTML for field display in the table
*/
function plugin_getListField_maps($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF, $LANG_ADMIN, $LANG_STATIC, $LANG_MAPS_1, $_TABLES, $_MAPS_CONF;

    switch($fieldname) {
        case "edit":
            $retval = COM_createLink($icon_arr['edit'],
                "{$_CONF['site_admin_url']}/plugins/maps/map_edit.php?mode=edit&mid={$A['mid']}");
            break;
        case "name":
            $map_title = MAPS_decodeStoredText($A['name']);
            $safeTitle = htmlspecialchars($map_title, ENT_QUOTES, 'UTF-8');
            $url = $_MAPS_CONF['site_url']
                . '/index.php?mode=map&mid=' . (int) $A['mid'];
            $linkTitle = $LANG_MAPS_1['title_display'];
            if ($A['description'] != '') {
                $description = trim(MAPS_decodeStoredText($A['description']));
                if ($description !== '') {
                    $linkTitle .= ' — ' . $description;
                }
            }
            $retval = COM_createLink(
                $safeTitle,
                $url,
                array('title' => htmlspecialchars($linkTitle, ENT_QUOTES, 'UTF-8'))
            );
            break;
        case "id":
            $retval = $A['mid'];
            break;
        case "active":
            $retval = MAPS_adminStatusBadge(
                $fieldvalue,
                $LANG_MAPS_1['status_active'],
                $LANG_MAPS_1['status_inactive']
            );
            break;
        case "hidden":
            $retval = MAPS_adminStatusBadge(
                $fieldvalue,
                $LANG_MAPS_1['status_hidden'],
                $LANG_MAPS_1['status_visible'],
                'is-warning',
                'is-positive'
            );
            break;
        default:
            $retval = htmlspecialchars(stripslashes((string) $fieldvalue), ENT_QUOTES, 'UTF-8');
            break;
    }
    return $retval;
}

// MAIN

/**
 * Render and run a browser-side Google Maps JavaScript API diagnostic.
 *
 * A browser key can be valid yet unusable because of HTTP referrer
 * restrictions, disabled APIs or Google Cloud billing. Testing it from PHP
 * would therefore produce misleading results. The diagnostic deliberately
 * loads the JavaScript API in the administrator's browser after installing
 * gm_authFailure().
 *
 * @return string
 */
function MAPS_adminGoogleApiStatus()
{
    global $_MAPS_CONF, $_SCRIPTS, $LANG_MAPS_1;

    $key = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', ''));
    $title = isset($LANG_MAPS_1['api_status_title']) ? $LANG_MAPS_1['api_status_title'] : 'Google Maps API status';

    $html = COM_startBlock($title);
    if ($key === '') {
        $message = isset($LANG_MAPS_1['api_status_missing'])
            ? $LANG_MAPS_1['api_status_missing']
            : 'No Google Maps browser API key is configured.';
        $html .= '<p><strong>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</strong></p>';
        $html .= COM_endBlock();
        return $html;
    }

    $masked = strlen($key) > 10
        ? substr($key, 0, 6) . str_repeat('&bull;', 6) . substr($key, -4)
        : str_repeat('&bull;', max(4, strlen($key)));

    $testing = isset($LANG_MAPS_1['api_status_testing']) ? $LANG_MAPS_1['api_status_testing'] : 'Testing the Google Maps JavaScript API...';
    $html .= '<p id="maps-google-api-status"><strong>'
        . htmlspecialchars($testing, ENT_QUOTES, 'UTF-8')
        . '</strong></p>';
    $html .= '<p><small>'
        . (isset($LANG_MAPS_1['api_status_key']) ? htmlspecialchars($LANG_MAPS_1['api_status_key'], ENT_QUOTES, 'UTF-8') : 'Browser key')
        . ': <code>' . $masked . '</code></small></p>';
    $html .= COM_endBlock();

    $apiUrl = MAPS_googleMapsApiUrl();
    $apiUrl .= (strpos($apiUrl, '?') === false ? '?' : '&') . 'callback=MAPS_googleApiReady';

    $messages = array(
        'ok' => isset($LANG_MAPS_1['api_status_ok']) ? $LANG_MAPS_1['api_status_ok'] : 'Google Maps JavaScript API loaded successfully.',
        'auth' => isset($LANG_MAPS_1['api_status_auth']) ? $LANG_MAPS_1['api_status_auth'] : 'Google Maps rejected the browser API key or its configuration. Check the browser console for the exact Google error code.',
        'load' => isset($LANG_MAPS_1['api_status_load']) ? $LANG_MAPS_1['api_status_load'] : 'The Google Maps JavaScript API script could not be loaded. Check the network, CSP/content blocker and browser console.',
        'timeout' => isset($LANG_MAPS_1['api_status_timeout']) ? $LANG_MAPS_1['api_status_timeout'] : 'The Google Maps JavaScript API did not answer. Check the browser console and network requests.',
    );

    $js = "(function () {\n"
        . "    var mapsApiFinished = false;\n"
        . "    var mapsApiAuthFailed = false;\n"
        . "    function mapsSetApiStatus(message, ok) {\n"
        . "        var el = document.getElementById('maps-google-api-status');\n"
        . "        if (!el) { return; }\n"
        . "        el.innerHTML = '<strong>' + message + '</strong>';\n"
        . "        el.style.borderLeft = '4px solid ' + (ok ? '#3c763d' : '#a94442');\n"
        . "        el.style.paddingLeft = '10px';\n"
        . "    }\n"
        . "    window.gm_authFailure = function () {\n"
        . "        mapsApiFinished = true;\n"
        . "        mapsApiAuthFailed = true;\n"
        . "        mapsSetApiStatus(" . MAPS_jsString($messages['auth']) . ", false);\n"
        . "    };\n"
        . "    window.MAPS_googleApiReady = function () {\n"
        . "        if (mapsApiAuthFailed) { return; }\n"
        . "        mapsApiFinished = true;\n"
        . "        if (window.google && google.maps && google.maps.Map) {\n"
        . "            mapsSetApiStatus(" . MAPS_jsString($messages['ok']) . ", true);\n"
        . "        } else {\n"
        . "            mapsSetApiStatus(" . MAPS_jsString($messages['load']) . ", false);\n"
        . "        }\n"
        . "    };\n"
        . "    var script = document.createElement('script');\n"
        . "    script.src = " . MAPS_jsString($apiUrl) . ";\n"
        . "    script.async = true;\n"
        . "    script.defer = true;\n"
        . "    script.onerror = function () {\n"
        . "        mapsApiFinished = true;\n"
        . "        mapsSetApiStatus(" . MAPS_jsString($messages['load']) . ", false);\n"
        . "    };\n"
        . "    document.head.appendChild(script);\n"
        . "    window.setTimeout(function () {\n"
        . "        if (!mapsApiFinished) {\n"
        . "            mapsSetApiStatus(" . MAPS_jsString($messages['timeout']) . ", false);\n"
        . "        }\n"
        . "    }, 10000);\n"
        . "}());";

    $_SCRIPTS->setJavaScript($js, true, true);

    return $html;
}

/**
 * Render integrated administration help.
 *
 * @return string
 */
function MAPS_adminDocumentation($collapsible = false)
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    $browserKey = isset($_MAPS_CONF['google_api_key']) ? trim((string) $_MAPS_CONF['google_api_key']) : '';
    $serverKey = isset($_MAPS_CONF['google_server_api_key']) ? trim((string) $_MAPS_CONF['google_server_api_key']) : '';
    $configUrl = $_CONF['site_admin_url'] . '/configuration.php?conf_group=maps';
    $createMapUrl = $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php';

    if ($collapsible) {
        $html = '<details class="maps-admin-help" style="margin-top:20px">';
        $html .= '<summary style="cursor:pointer;font-weight:bold">'
            . htmlspecialchars($LANG_MAPS_1['admin_help_title'], ENT_QUOTES, 'UTF-8') . '</summary>';
        $html .= '<div style="margin-top:12px">';
    } else {
        $html = COM_startBlock($LANG_MAPS_1['admin_help_title']);
    }

    $html .= '<p>' . $LANG_MAPS_1['admin_help_intro'] . '</p>';

    if ($browserKey === '') {
        $html .= '<p><strong>' . $LANG_MAPS_1['need_google_api'] . '</strong></p>';
    }

    $html .= '<h3>' . $LANG_MAPS_1['admin_help_google'] . '</h3>';
    $html .= '<ol>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_google_1'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_google_2'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_google_3'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_google_4'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_google_5'] . '</li>';
    $html .= '</ol>';
    $html .= '<p><strong>' . $LANG_MAPS_1['admin_help_security'] . '</strong></p>';
    $html .= '<p><a href="' . htmlspecialchars($configUrl, ENT_QUOTES, 'UTF-8') . '">' . $LANG_MAPS_1['plugin_conf'] . '</a>';
    $html .= ' &middot; <a href="https://console.cloud.google.com/google/maps-apis/credentials" target="_blank" rel="noopener noreferrer">Google Cloud Console</a>';
    $html .= ' &middot; <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener noreferrer">' . $LANG_MAPS_1['admin_help_official'] . '</a></p>';

    $html .= '<h3>' . $LANG_MAPS_1['admin_help_create'] . '</h3>';
    $html .= '<ol>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_create_1'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_create_2'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_create_3'] . '</li>';
    $html .= '</ol>';
    $html .= '<p><a href="' . htmlspecialchars($createMapUrl, ENT_QUOTES, 'UTF-8') . '"><strong>' . ucfirst($LANG_MAPS_1['create_map']) . '</strong></a></p>';

    $html .= '<h3 id="maps-user-geolocation">' . $LANG_MAPS_1['admin_help_geo_title'] . '</h3>';
    $html .= '<p>' . $LANG_MAPS_1['admin_help_geo_intro'] . '</p>';
    $html .= '<ul>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_geo_1'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_geo_2'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_geo_3'] . '</li>';
    $html .= '</ul>';
    $geoToken = SEC_createToken();
    $html .= '<form method="post" action="' . htmlspecialchars($_CONF['site_admin_url'], ENT_QUOTES, 'UTF-8')
        . '/plugins/maps/index.php" class="maps-inline-action">'
        . '<input type="hidden" name="mode" value="setgeolocation">'
        . '<input type="hidden" name="' . CSRF_TOKEN . '" value="'
        . htmlspecialchars($geoToken, ENT_QUOTES, 'UTF-8') . '">'
        . '<button type="submit"><strong>'
        . htmlspecialchars($LANG_MAPS_1['set_user_geo'], ENT_QUOTES, 'UTF-8')
        . '</strong></button></form>';

    $html .= '<h3>' . $LANG_MAPS_1['admin_help_overlays_title'] . '</h3>';
    $html .= '<p>' . $LANG_MAPS_1['admin_help_overlays_intro'] . '</p>';
    $html .= '<ul>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_overlays_1'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_overlays_2'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_overlays_3'] . '</li>';
    $html .= '<li>' . $LANG_MAPS_1['admin_help_overlays_4'] . '</li>';
    $html .= '</ul>';
    $html .= '<p>' . $LANG_MAPS_1['admin_help_overlays_how'] . '</p>';

    $html .= '<h3>' . $LANG_MAPS_1['admin_help_concepts_title'] . '</h3>';
    $html .= '<dl>';
    $html .= '<dt><strong>' . $LANG_MAPS_1['admin_help_concept_map'] . '</strong></dt><dd>' . $LANG_MAPS_1['admin_help_concept_map_text'] . '</dd>';
    $html .= '<dt><strong>' . $LANG_MAPS_1['admin_help_concept_marker'] . '</strong></dt><dd>' . $LANG_MAPS_1['admin_help_concept_marker_text'] . '</dd>';
    $html .= '<dt><strong>' . $LANG_MAPS_1['admin_help_concept_icon'] . '</strong></dt><dd>' . $LANG_MAPS_1['admin_help_concept_icon_text'] . '</dd>';
    $html .= '<dt><strong>' . $LANG_MAPS_1['admin_help_concept_users'] . '</strong></dt><dd>' . $LANG_MAPS_1['admin_help_concept_users_text'] . '</dd>';
    $html .= '</dl>';

    $html .= '<h3>' . $LANG_MAPS_1['admin_help_trouble_title'] . '</h3>';
    $html .= '<p>' . $LANG_MAPS_1['admin_help_trouble'] . '</p>';

    if ($browserKey !== '' && $serverKey === '') {
        $html .= '<p><small>Geocoding: Google Maps server API key is not configured; Maps will fall back to the browser key for compatibility.</small></p>';
    }

    if ($collapsible) {
        $html .= '</div></details>';
    } else {
        $html .= COM_endBlock();
    }

    return $html;
}

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$requestData = $requestMethod === 'POST' ? $_POST : $_GET;
$mode = isset($requestData['mode']) ? COM_applyFilter($requestData['mode']) : '';

switch ($mode) {
    case 'edit':
        echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/marker_edit.php');
        exit;

    case 'editsubmission':
        $id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
        echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/marker_edit.php?mode=editsubmission&amp;mkid=' . $id);
        exit;

    case 'setgeolocation':
        if ($requestMethod !== 'POST' || !SEC_checkToken()) {
            COM_accessLog('Rejected Maps geolocation mutation because of missing or invalid CSRF token.');
            echo COM_refresh(
                $_CONF['site_admin_url'] . '/plugins/maps/index.php?msg='
                . urlencode('Invalid or expired security token.')
            );
            exit;
        }
        MAPS_setGeoLocation();
        echo COM_refresh(
            $_CONF['site_admin_url'] . '/plugins/maps/index.php?msg='
            . urlencode($LANG_MAPS_1['set_geo_location'])
        );
        exit;

    default:
        $display = MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
        $display .= MAPS_admin_menu();

        if (!empty($requestData['msg'])) {
            $display .= MAPS_message(
                htmlspecialchars((string) $requestData['msg'], ENT_QUOTES, 'UTF-8'),
                $LANG_MAPS_1['message']
            );
        }

        $display .= MAPS_adminGoogleApiStatus();
        $display .= MAPS_renderStatistics(false);
        $display .= '<h1>' . $LANG_MAPS_1['maps_list'] . '</h1>';
        $display .= '<p>' . $LANG_MAPS_1['you_can'] . '<a href="' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';
        $display .= MAPS_listmaps();
        $display .= MAPS_adminDocumentation(true);
        $display .= MAPS_compatSiteFooter(0);
        break;
}

MAPS_compatOutput($display);
