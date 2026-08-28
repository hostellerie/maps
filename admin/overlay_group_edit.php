<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | overlay_group_edit.php                                                    |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

MAPS_getheadercode();

$display = '';

if (!SEC_hasRights('maps.admin')) {
    $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
        . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
        . MAPS_compatSiteFooter();
    COM_accessLog("User {$_USER['username']} tried to illegally access the Maps plugin administration screen.");
    MAPS_compatOutput($display);
    exit;
}

function MAPS_getGroupOverlayForm($group = array())
{
    global $_CONF, $LANG_MAPS_1;

    $groupDefaults = array('o_group_id' => 0, 'o_group_name' => '');
    $group = array_merge($groupDefaults, is_array($group) ? $group : array());

    $safeName = htmlspecialchars((string) $group['o_group_name'], ENT_QUOTES, 'UTF-8');
    $display = '<h1 class="maps-admin-title">' . htmlspecialchars($LANG_MAPS_1['group_edit'], ENT_QUOTES, 'UTF-8') . ($safeName !== '' ? ': ' . $safeName : '') . '</h1>';

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('map' => 'group_overlay_form.thtml'));
    $template->set_var('site_admin_url', $_CONF['site_admin_url']);
    $token = SEC_createToken();
    $template->set_var(
        'csrf_token',
        '<input type="hidden" name="' . CSRF_TOKEN . '" value="'
        . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">'
    );

    $template->set_var('yes', $LANG_MAPS_1['yes']);
    $template->set_var('no', $LANG_MAPS_1['no']);
    $template->set_var('group_overlay_presentation', $LANG_MAPS_1['group_overlay_presentation']);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['group_overlay_name_label']);
    $template->set_var('name', $safeName);
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_action', (int) $group['o_group_id'] > 0 ? '<button type="submit" name="mode" value="delete" class="maps-danger-action">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>' : '');

    if ((int) $group['o_group_id'] > 0) {
        $template->set_var(
            'delete_button',
            '<option value="delete">'
            . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8')
            . '</option>'
        );
        $template->set_var(
            'o_group_id',
            '<input type="hidden" name="o_group_id" value="'
            . (int) $group['o_group_id'] . '">'
        );
    } else {
        $template->set_var('delete_button', '');
        $template->set_var('o_group_id', '');
    }

    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);
    $display .= $template->parse('output', 'map');
    return $display;
}

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$requestData = $requestMethod === 'POST' ? $_POST : $_GET;
$mode = isset($requestData['mode']) ? COM_applyFilter($requestData['mode']) : 'new';
$groupId = isset($requestData['o_group_id']) ? (int) $requestData['o_group_id'] : 0;

$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= MAPS_admin_menu();

if (in_array($mode, array('save', 'delete'), true)) {
    if ($requestMethod !== 'POST' || !SEC_checkToken()) {
        COM_accessLog('Rejected Maps overlay group mutation because of missing or invalid CSRF token.');
        $display .= MAPS_message('Invalid or expired security token.', $LANG_MAPS_1['error']);
        $mode = $groupId > 0 ? 'edit' : 'new';
    }
}

switch ($mode) {
    case 'delete':
        if ($groupId <= 0 || (int) DB_count($_TABLES['maps_overlays_groups'], 'o_group_id', $groupId) !== 1) {
            $msg = $LANG_MAPS_1['deletion_fail'];
        } else {
            DB_query(
                "UPDATE {$_TABLES['maps_overlays']} SET o_group=0 WHERE o_group=" . $groupId
            );
            DB_delete($_TABLES['maps_overlays_groups'], 'o_group_id', $groupId);
            $msg = (int) DB_count($_TABLES['maps_overlays_groups'], 'o_group_id', $groupId) === 0
                ? $LANG_MAPS_1['deletion_succes']
                : $LANG_MAPS_1['deletion_fail'];
        }
        echo COM_refresh(
            $_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups&msg=' . urlencode($msg)
        );
        exit;

    case 'save':
        $post = is_array($_POST) ? $_POST : array();
        $groupName = trim(isset($post['o_group_name']) ? (string) $post['o_group_name'] : '');

        if ($groupName === '') {
            $display .= COM_startBlock($LANG_MAPS_1['error'], '', 'blockheader-message.thtml');
            $display .= $LANG_MAPS_1['missing_field'];
            $display .= COM_endBlock('blockfooter-message.thtml');
            $display .= MAPS_getGroupOverlayForm(array_merge($post, array('o_group_id' => $groupId)));
            break;
        }

        $escapedName = MAPS_dbEscape($groupName);
        if ($groupId > 0) {
            if ((int) DB_count($_TABLES['maps_overlays_groups'], 'o_group_id', $groupId) !== 1) {
                $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $display .= MAPS_getGroupOverlayForm(array_merge($post, array('o_group_id' => $groupId)));
                break;
            }
            DB_query(
                "UPDATE {$_TABLES['maps_overlays_groups']} SET o_group_name='"
                . $escapedName . "' WHERE o_group_id=" . $groupId
            );
        } else {
            DB_query(
                "INSERT INTO {$_TABLES['maps_overlays_groups']} SET o_group_name='"
                . $escapedName . "'"
            );
        }

        $msg = DB_error() ? $LANG_MAPS_1['save_fail'] : $LANG_MAPS_1['save_success'];
        echo COM_refresh(
            $_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups&msg=' . urlencode($msg)
        );
        exit;

    case 'edit':
        if ($groupId > 0 && (int) DB_count($_TABLES['maps_overlays_groups'], 'o_group_id', $groupId) === 1) {
            $res = DB_query(
                "SELECT * FROM {$_TABLES['maps_overlays_groups']} WHERE o_group_id="
                . $groupId . ' LIMIT 1'
            );
            $group = DB_fetchArray($res);
            $display .= MAPS_getGroupOverlayForm($group);
        } else {
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups');
            exit;
        }
        break;

    case 'new':
    default:
        $display .= MAPS_getGroupOverlayForm(array());
        break;
}

$display .= MAPS_compatSiteFooter(0);
MAPS_compatOutput($display);
