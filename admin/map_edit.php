<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                         |
// +---------------------------------------------------------------------------+
// | map_edit.php                                                              |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';
require_once 'edit_functions.php';

MAPS_getheadercode();

if (!SEC_hasRights('maps.admin')) {
    COM_accessLog('Unauthorized access attempt to Maps map editor.');
    COM_output(COM_createHTMLDocument(COM_showMessageText($MESSAGE[29], $MESSAGE[30]), array('pagetitle' => $MESSAGE[30])));
    exit;
}

function MAPS_mapEditorDefaults($map)
{
    global $_MAPS_CONF, $_USER, $_GROUPS;
    $defaults = array(
        'mid' => '', 'name' => '', 'description' => '', 'geo' => '', 'lat' => 0, 'lng' => 0,
        'created' => time(), 'modified' => time(), 'free_marker' => MAPS_arrayGet($_MAPS_CONF, 'free_markers', 1),
        'paid_marker' => MAPS_arrayGet($_MAPS_CONF, 'paid_markers', 1), 'active' => MAPS_arrayGet($_MAPS_CONF, 'map_active', 1),
        'hidden' => MAPS_arrayGet($_MAPS_CONF, 'map_hidden', 0), 'width' => MAPS_arrayGet($_MAPS_CONF, 'map_width', '100%'),
        'height' => MAPS_arrayGet($_MAPS_CONF, 'map_height', '600px'), 'zoom' => MAPS_arrayGet($_MAPS_CONF, 'map_zoom', 6),
        'type' => MAPS_arrayGet($_MAPS_CONF, 'map_type', 'ROADMAP'), 'header' => '', 'footer' => '',
        'primary_color' => '#666666', 'stroke_color' => '#333333', 'label' => '', 'label_color' => 0,
        'mmk_default' => 1, 'mmk_icon' => 0, 'owner_id' => isset($_USER['uid']) ? $_USER['uid'] : 2,
        'group_id' => isset($_GROUPS['Maps Admin']) ? $_GROUPS['Maps Admin'] : 2,
        'perm_owner' => 3, 'perm_group' => 3, 'perm_members' => 2, 'perm_anon' => 2
    );
    return array_merge($defaults, is_array($map) ? $map : array());
}

function getMapForm($map = array())
{
    global $_CONF, $_TABLES, $_MAPS_CONF, $LANG_MAPS_1, $LANG_configselects, $LANG_ACCESS, $_USER;

    $map = MAPS_mapEditorDefaults($map);
    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('map' => 'map_form.thtml'));

    $template->set_var('site_admin_url', $_CONF['site_admin_url']);
    $template->set_var('arrow', '');
    $template->set_var('map_tab', $LANG_MAPS_1['map_tab']);
    $template->set_var('overlays_tab', $LANG_MAPS_1['overlays_tab']);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['name_label']);
    $template->set_var('name', htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('address_label', $LANG_MAPS_1['address_label']);
    $template->set_var('geo', htmlspecialchars(stripslashes($map['geo']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('description_label', $LANG_MAPS_1['description_label']);
    $template->set_var('description', htmlspecialchars(stripslashes($map['description']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('created_label', $LANG_MAPS_1['map_created']);
    $template->set_var('modified_label', $LANG_MAPS_1['modified']);
    $created = COM_getUserDateTimeFormat($map['created']);
    $modified = COM_getUserDateTimeFormat($map['modified']);
    $template->set_var('created', $created[0]);
    $template->set_var('modified', $modified[0]);
    $template->set_var('general_settings', $LANG_MAPS_1['general_settings']);
    $template->set_var('map_width', $LANG_MAPS_1['map_width']);
    $template->set_var('width', htmlspecialchars($map['width'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_height', $LANG_MAPS_1['map_height']);
    $template->set_var('height', htmlspecialchars($map['height'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_zoom', $LANG_MAPS_1['map_zoom']);
    $template->set_var('zoom', (int) $map['zoom']);
    $template->set_var('map_type', $LANG_MAPS_1['map_type']);

    $options = '';
    foreach ($LANG_configselects['maps'][20] as $label => $value) {
        $options .= '<option value="' . $value . '"' . ($value === $map['type'] ? ' selected="selected"' : '') . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
    }
    $template->set_var('options', $options);
    $template->set_var('yes', $LANG_MAPS_1['yes']);
    $template->set_var('no', $LANG_MAPS_1['no']);

    foreach (array('active','hidden','free_marker','paid_marker') as $field) {
        $labelKey = $field;
        if (isset($LANG_MAPS_1[$labelKey])) {
            $template->set_var($field, $LANG_MAPS_1[$labelKey]);
        }
        $template->set_var($field . '_yes', (int) $map[$field] === 1 ? ' selected="selected"' : '');
        $template->set_var($field . '_no', (int) $map[$field] === 0 ? ' selected="selected"' : '');
    }

    $template->set_var('mk_default', $LANG_MAPS_1['mk_default']);
    $template->set_var('mk_default_yes', (int) $map['mmk_default'] === 1 ? ' selected="selected"' : '');
    $template->set_var('mk_default_no', (int) $map['mmk_default'] === 0 ? ' selected="selected"' : '');
    $radio = '<p>' . $LANG_MAPS_1['choose_icon'] . '</p>';
    $radio .= '<label><input type="radio" name="mk_icon" value="0"' . ((int) $map['mmk_icon'] === 0 ? ' checked="checked"' : '') . '> ' . $LANG_MAPS_1['no_icon'] . '</label> ';
    $icons = DB_query("SELECT * FROM {$_TABLES['maps_map_icons']} ORDER BY icon_name");
    while ($icon = DB_fetchArray($icons)) {
        $radio .= '<label><input type="radio" name="mk_icon" value="' . (int) $icon['icon_id'] . '"' . ((int) $map['mmk_icon'] === (int) $icon['icon_id'] ? ' checked="checked"' : '') . '> <img src="' . $_MAPS_CONF['images_icons_url'] . rawurlencode($icon['icon_image']) . '" alt="" style="max-width:32px;max-height:32px"></label> ';
    }
    $template->set_var('icon', $radio);

    $template->set_var('marker_label', $LANG_MAPS_1['marker_label']);
    $template->set_var('primary_color_label', $LANG_MAPS_1['primary_color_label']);
    $template->set_var('primary_color', htmlspecialchars($map['primary_color'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('stroke_color_label', $LANG_MAPS_1['stroke_color_label']);
    $template->set_var('stroke_color', htmlspecialchars($map['stroke_color'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('label_label', $LANG_MAPS_1['label']);
    $template->set_var('label', htmlspecialchars($map['label'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('label_color_label', $LANG_MAPS_1['label_color']);
    $template->set_var('label_color_white', (int) $map['label_color'] === 1 ? ' selected="selected"' : '');
    $template->set_var('label_color_black', (int) $map['label_color'] === 0 ? ' selected="selected"' : '');
    $template->set_var('black', $LANG_MAPS_1['black']);
    $template->set_var('white', $LANG_MAPS_1['white']);

    /* Plain textareas deliberately replace the removed FCKeditor dependency. */
    $template->set_var('header_footer', $LANG_MAPS_1['header_footer']);
    $template->set_var('map_header_label', $LANG_MAPS_1['map_header_label']);
    $template->set_var('map_header', htmlspecialchars(stripslashes($map['header']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_footer_label', $LANG_MAPS_1['map_footer_label']);
    $template->set_var('map_footer', htmlspecialchars(stripslashes($map['footer']), ENT_QUOTES, 'UTF-8'));

    $template->set_var('lang_accessrights', $LANG_ACCESS['accessrights']);
    $template->set_var('lang_owner', $LANG_ACCESS['owner']);
    $template->set_var('owner_select', COM_optionList($_TABLES['users'], 'uid,username', $map['owner_id'], 1, 'uid<>1'));
    $template->set_var('owner_username', DB_getItem($_TABLES['users'], 'username', 'uid=' . (int) $map['owner_id']));
    $template->set_var('owner_name', COM_getDisplayName($map['owner_id']));
    $template->set_var('owner', COM_getDisplayName($map['owner_id']));
    $template->set_var('owner_id', (int) $map['owner_id']);
    $template->set_var('lang_group', $LANG_ACCESS['group']);
    $access = 3;
    $template->set_var('group_dropdown', SEC_getGroupDropdown($map['group_id'], $access));
    $template->set_var('permissions_editor', SEC_getPermissionsHTML($map['perm_owner'], $map['perm_group'], $map['perm_members'], $map['perm_anon']));
    $template->set_var('lang_permissions', $LANG_ACCESS['permissions']);
    $template->set_var('lang_perm_key', $LANG_ACCESS['permissionskey']);
    $template->set_var('permissions_msg', $LANG_ACCESS['permmsg']);
    $template->set_var('lang_permissions_msg', $LANG_ACCESS['permmsg']);
    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_button', $LANG_MAPS_1['delete_button']);
    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);
    $template->set_var('mid', $map['mid'] !== '' ? '<input type="hidden" name="mid" value="' . (int) $map['mid'] . '">' : '');
    $template->set_var('overlays', $map['mid'] !== '' ? MAPS_displayOverlays($map['mid']) : '');
    $template->set_var('add_overlay', $map['mid'] !== '' ? MAPS_displayOverlaysToAdd($map['mid']) : '<p>' . $LANG_MAPS_1['add_overlay'] . '</p>');

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    $_SCRIPTS->setJavaScriptFile('maps_simplecolor', '/' . $_MAPS_CONF['maps_folder'] . '/js/simple-color.js');
    $_SCRIPTS->setJavaScript("jQuery(function(){if(jQuery.fn.simpleColor){jQuery('#primary_color,#stroke_color').simpleColor({displayColorCode:true});}});", true, true);

    return COM_startBlock($LANG_MAPS_1['map_edit'] . ' ' . htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8'))
        . $template->parse('output', 'map') . COM_endBlock();
}

$mode = isset($_REQUEST['mode']) ? COM_applyFilter($_REQUEST['mode']) : 'new';
$mid = isset($_REQUEST['mid']) ? (int) $_REQUEST['mid'] : 0;
$content = MAPS_admin_menu();

if ($mode === 'delete' && $mid > 0) {
    DB_delete($_TABLES['maps_maps'], 'mid', $mid);
    DB_delete($_TABLES['maps_markers'], 'mid', $mid);
    DB_delete($_TABLES['maps_map_overlay'], 'mo_mid', $mid);
    echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/index.php');
    exit;
}

if ($mode === 'save') {
    $name = trim(isset($_REQUEST['name']) ? $_REQUEST['name'] : '');
    $geo = trim(isset($_REQUEST['geo']) ? $_REQUEST['geo'] : '');
    $lat = 0;
    $lng = 0;
    if ($name === '' || $geo === '' || !MAPS_getCoords($geo, $lat, $lng)) {
        $content .= MAPS_message($LANG_MAPS_1['missing_field'], $LANG_MAPS_1['error']);
        $content .= getMapForm($_REQUEST);
    } else {
        $permOwner = isset($_REQUEST['perm_owner']) ? $_REQUEST['perm_owner'] : 3;
        $permGroup = isset($_REQUEST['perm_group']) ? $_REQUEST['perm_group'] : 3;
        $permMembers = isset($_REQUEST['perm_members']) ? $_REQUEST['perm_members'] : 2;
        $permAnon = isset($_REQUEST['perm_anon']) ? $_REQUEST['perm_anon'] : 2;
        if (is_array($permOwner) || is_array($permGroup) || is_array($permMembers) || is_array($permAnon)) {
            list($permOwner, $permGroup, $permMembers, $permAnon) = SEC_getPermissionValues($permOwner, $permGroup, $permMembers, $permAnon);
        }
        $data = array(
            'name' => MAPS_dbEscape($name), 'description' => MAPS_dbEscape(isset($_REQUEST['description']) ? $_REQUEST['description'] : ''),
            'geo' => MAPS_dbEscape($geo), 'lat' => (float) $lat, 'lng' => (float) $lng,
            'free_marker' => (int) (isset($_REQUEST['free_marker']) ? $_REQUEST['free_marker'] : 1),
            'paid_marker' => (int) (isset($_REQUEST['paid_marker']) ? $_REQUEST['paid_marker'] : 1),
            'active' => (int) (isset($_REQUEST['active']) ? $_REQUEST['active'] : 1), 'hidden' => (int) (isset($_REQUEST['hidden']) ? $_REQUEST['hidden'] : 0),
            'width' => MAPS_dbEscape(isset($_REQUEST['width']) ? $_REQUEST['width'] : '100%'), 'height' => MAPS_dbEscape(isset($_REQUEST['height']) ? $_REQUEST['height'] : '600px'),
            'zoom' => (int) (isset($_REQUEST['zoom']) ? $_REQUEST['zoom'] : 6), 'type' => MAPS_dbEscape(isset($_REQUEST['type']) ? $_REQUEST['type'] : 'ROADMAP'),
            'header' => MAPS_dbEscape(isset($_REQUEST['map_header']) ? $_REQUEST['map_header'] : ''), 'footer' => MAPS_dbEscape(isset($_REQUEST['map_footer']) ? $_REQUEST['map_footer'] : ''),
            'primary_color' => MAPS_dbEscape(isset($_REQUEST['primary_color']) ? $_REQUEST['primary_color'] : '#666666'), 'stroke_color' => MAPS_dbEscape(isset($_REQUEST['stroke_color']) ? $_REQUEST['stroke_color'] : '#333333'),
            'label' => MAPS_dbEscape(isset($_REQUEST['label']) ? $_REQUEST['label'] : ''), 'label_color' => (int) (isset($_REQUEST['label_color']) ? $_REQUEST['label_color'] : 0),
            'mmk_default' => (int) (isset($_REQUEST['mk_default']) ? $_REQUEST['mk_default'] : 1), 'mmk_icon' => (int) (isset($_REQUEST['mk_icon']) ? $_REQUEST['mk_icon'] : 0),
            'owner_id' => (int) (isset($_REQUEST['owner_id']) ? $_REQUEST['owner_id'] : $_USER['uid']), 'group_id' => (int) (isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : 2),
            'perm_owner' => (int) $permOwner, 'perm_group' => (int) $permGroup, 'perm_members' => (int) $permMembers, 'perm_anon' => (int) $permAnon
        );
        $modified = date('YmdHis');
        if ($mid > 0) {
            $sets = array();
            foreach ($data as $field => $value) {
                $sets[] = $field . "='" . MAPS_dbEscape($value) . "'";
            }
            $sets[] = "modified='{$modified}'";
            DB_query("UPDATE {$_TABLES['maps_maps']} SET " . implode(',', $sets) . ' WHERE mid=' . $mid);
        } else {
            $created = $modified;
            $fields = array_keys($data);
            $values = array();
            foreach ($data as $value) {
                $values[] = "'" . MAPS_dbEscape($value) . "'";
            }
            $fields[] = 'created'; $values[] = "'{$created}'";
            $fields[] = 'modified'; $values[] = "'{$modified}'";
            $fields[] = 'hits'; $values[] = '0';
            DB_query("INSERT INTO {$_TABLES['maps_maps']} (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')');
            $mid = DB_insertId();
        }
        echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&mid=' . (int) $mid);
        exit;
    }
} else {
    $map = array();
    if ($mode === 'edit' && $mid > 0) {
        $res = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid={$mid} LIMIT 1");
        if (DB_numRows($res) > 0) {
            $map = DB_fetchArray($res);
        }
    }
    $content .= getMapForm($map);
}

COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
