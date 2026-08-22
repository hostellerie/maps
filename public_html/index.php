<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.6                                                         |
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
        $markers = DB_count($_TABLES['maps_markers'], 'mid', $map['mid']);
        $modified = COM_getUserDateTimeFormat($map['modified']);
        $retval .= '<br><small>' . $LANG_MAPS_1['last_modification'] . ' ' . $modified[0]
            . ' | ' . (int) $markers . ' ' . $LANG_MAPS_1['records']
            . ' | ' . (int) $map['hits'] . ' ' . $LANG_MAPS_1['hits'] . '</small>';
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

$pageTitle = $LANG_MAPS_1['maps_label'];
if ($mode === 'map' && $mid > 0) {
    $pageTitle = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . $mid);
} elseif ($mode === 'marker' && $mkid !== '') {
    $pageTitle = DB_getItem($_TABLES['maps_markers'], 'name', "mkid='" . MAPS_dbEscape($mkid) . "'");
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
        } else {
            $content .= MAPS_getGlobalMap();
        }
        break;

    case 'markers':
        $content .= MAPS_ListMarkers($mid);
        break;

    case 'marker':
        if ($mkid !== '') {
            $content .= MAPS_ViewMarkerInfos($mkid);
        }
        break;

    default:
        $content .= MAPS_displayFrontPage();
        break;
}

COM_output(COM_createHTMLDocument($content, array('pagetitle' => stripslashes($pageTitle))));
