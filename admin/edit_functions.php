<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                        |
// +--------------------------------------------------------------------------+
// | edit_functions.php                                                       |
// +--------------------------------------------------------------------------+

require_once '../../../lib-common.php';

if (!SEC_hasRights('maps.admin')) {
    exit;
}

function MAPS_displayOverlays($mid)
{
    global $_CONF, $_TABLES, $LANG_MAPS_1, $LANG_ADMIN;

    require_once $_CONF['path_system'] . 'lib-admin.php';
    $mid = (int) $mid;
    $header = array(
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
        array('text' => $LANG_MAPS_1['overlay_label'], 'field' => 'o_name', 'sort' => false),
        array('text' => $LANG_MAPS_1['image'], 'field' => 'o_image', 'sort' => false)
    );
    $query = array(
        'sql' => "SELECT DISTINCT * FROM {$_TABLES['maps_map_overlay']} mo LEFT JOIN {$_TABLES['maps_overlays']} o ON mo.mo_oid=o.oid WHERE mo.mo_mid={$mid}"
    );
    $text = array('has_extras' => true);
    $sort = array('field' => 'o_name', 'direction' => 'asc');
    return '<h2>' . $LANG_MAPS_1['overlays_added'] . '</h2>'
        . ADMIN_list('maps_overlays', 'MAPS_getListField_maps_displayOverlays', $header, $text, $query, $sort);
}

function MAPS_getListField_maps_displayOverlays($fieldname, $fieldvalue, $a, $icon_arr)
{
    global $LANG_MAPS_1, $_MAPS_CONF;

    if ($fieldname === 'edit') {
        return COM_createLink($icon_arr['enabled'], '#', array(
            'class' => 'delete',
            'id' => $a['mo_id'],
            'mid' => $a['mo_mid'],
            'title' => $LANG_MAPS_1['remove_overlay']
        ));
    }
    if ($fieldname === 'o_image') {
        $url = $_MAPS_CONF['images_overlay_url'] . rawurlencode($a['o_image']);
        return '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:75px;max-height:75px;width:auto;height:auto">';
    }
    return htmlspecialchars(stripslashes((string) $fieldvalue), ENT_QUOTES, 'UTF-8');
}

function MAPS_displayOverlaysToAdd($mid)
{
    global $_CONF, $_TABLES, $LANG_MAPS_1, $LANG_ADMIN;

    require_once $_CONF['path_system'] . 'lib-admin.php';
    $mid = (int) $mid;
    $header = array(
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
        array('text' => $LANG_MAPS_1['overlay_label'], 'field' => 'o_name', 'sort' => false)
    );
    $query = array(
        'sql' => "SELECT m.mid,o.*,mo.* FROM {$_TABLES['maps_maps']} m,{$_TABLES['maps_overlays']} o LEFT JOIN {$_TABLES['maps_map_overlay']} mo ON (o.oid=mo.mo_oid AND mo.mo_mid={$mid}) WHERE m.mid={$mid} AND mo.mo_id IS NULL"
    );
    $text = array('has_extras' => true);
    $sort = array('field' => 'o_name', 'direction' => 'asc');
    return '<h2>' . $LANG_MAPS_1['overlays_to_add'] . '</h2>'
        . ADMIN_list('maps_overlaysToAdd', 'MAPS_getListField_maps_displayOverlaysToAdd', $header, $text, $query, $sort);
}

function MAPS_getListField_maps_displayOverlaysToAdd($fieldname, $fieldvalue, $a, $icon_arr)
{
    global $LANG_MAPS_1, $_MAPS_CONF;

    if ($fieldname === 'edit') {
        return COM_createLink($icon_arr['disabled'], '#', array(
            'class' => 'add',
            'id' => $a['oid'],
            'mid' => $a['mid'],
            'title' => $LANG_MAPS_1['add_overlay']
        ));
    }
    if ($fieldname === 'o_name') {
        $url = $_MAPS_CONF['images_overlay_url'] . rawurlencode($a['o_image']);
        if ($a['o_image'] !== '' && is_file($_MAPS_CONF['path_overlay_images'] . $a['o_image'])) {
            return COM_getTooltip(
                htmlspecialchars($a['o_name'], ENT_QUOTES, 'UTF-8'),
                '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:200px;height:auto">',
                '',
                $a['o_name'],
                'help'
            );
        }
    }
    return htmlspecialchars(stripslashes((string) $fieldvalue), ENT_QUOTES, 'UTF-8');
}

/**
 * Historical PayPal custom-overlay helper retained as a no-op for API
 * compatibility. It referenced tables and TimThumb assets unrelated to Maps.
 */
function MAPS_displayCustomOverlays($mid)
{
    return '';
}
