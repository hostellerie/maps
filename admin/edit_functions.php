<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                        |
// +--------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | edit_functions.php                                                       |
// +--------------------------------------------------------------------------+

require_once '../../../lib-common.php';

if (!SEC_hasRights('maps.admin')) {
    exit;
}

/**
 * Return one Geeklog CSRF token for the overlay editor during this request.
 *
 * @return string
 */
function MAPS_overlayCsrfToken()
{
    static $token = null;

    if ($token === null) {
        $token = SEC_createToken();
    }

    return $token;
}

/**
 * JavaScript used by both the initial map editor and AJAX-refreshed overlay
 * lists. Event delegation avoids re-binding handlers after each replacement.
 *
 * @return string
 */
function MAPS_overlayAjaxScript()
{
    static $rendered = false;

    if ($rendered) {
        return '';
    }
    $rendered = true;

    $tokenName = htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8');
    $token = htmlspecialchars(MAPS_overlayCsrfToken(), ENT_QUOTES, 'UTF-8');

    return '<script type="text/javascript">'
        . 'jQuery(function($){'
        . '$(document).off("click.mapsOverlay", "#overlays_actions a.add, #overlays_actions a.delete")'
        . '.on("click.mapsOverlay", "#overlays_actions a.add, #overlays_actions a.delete", function(e){'
        . 'e.preventDefault();'
        . 'var link=$(this), action=link.hasClass("delete")?"delete":"add";'
        . '$.ajax({type:"POST",url:"ajax.php",data:{action:action,id:link.attr("id"),mid:link.attr("mid"),'
        . json_encode($tokenName) . ':' . json_encode($token) . '},cache:false})'
        . '.done(function(result){$("#overlays_actions").replaceWith(result);});'
        . '});'
        . '});'
        . '</script>';
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
        $label = isset($LANG_MAPS_1['remove_overlay']) ? $LANG_MAPS_1['remove_overlay'] : 'Remove';
        return COM_createLink(
            htmlspecialchars($label, ENT_QUOTES, 'UTF-8'),
            '#',
            array(
                'class' => 'delete maps-overlay-action maps-overlay-remove',
                'id' => (int) $a['mo_id'],
                'mid' => (int) $a['mo_mid'],
                'title' => $label
            )
        );
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
    $actionLabel = isset($LANG_MAPS_1['add_overlay']) ? $LANG_MAPS_1['add_overlay'] : 'Add';
    $header = array(
        array('text' => $actionLabel, 'field' => 'edit', 'sort' => false),
        array('text' => $LANG_MAPS_1['overlay_label'], 'field' => 'o_name', 'sort' => false)
    );
    $query = array(
        'sql' => "SELECT m.mid,o.*,mo.* FROM {$_TABLES['maps_maps']} m,{$_TABLES['maps_overlays']} o LEFT JOIN {$_TABLES['maps_map_overlay']} mo ON (o.oid=mo.mo_oid AND mo.mo_mid={$mid}) WHERE m.mid={$mid} AND mo.mo_id IS NULL"
    );
    $text = array('has_extras' => true);
    $sort = array('field' => 'o_name', 'direction' => 'asc');
    return '<h2>' . $LANG_MAPS_1['overlays_to_add'] . '</h2>'
        . ADMIN_list('maps_overlaysToAdd', 'MAPS_getListField_maps_displayOverlaysToAdd', $header, $text, $query, $sort)
        . MAPS_overlayAjaxScript();
}

/**
 * Backward-compatible alias used by older map editor code.
 *
 * @param int $mid
 * @return string
 */
function MAPS_displayAddOverlay($mid)
{
    return MAPS_displayOverlaysToAdd($mid);
}

function MAPS_getListField_maps_displayOverlaysToAdd($fieldname, $fieldvalue, $a, $icon_arr)
{
    global $LANG_MAPS_1, $_MAPS_CONF;

    if ($fieldname === 'edit') {
        $label = isset($LANG_MAPS_1['add_overlay']) ? $LANG_MAPS_1['add_overlay'] : 'Add';
        return COM_createLink(
            htmlspecialchars($label, ENT_QUOTES, 'UTF-8'),
            '#',
            array(
                'class' => 'add maps-overlay-action maps-overlay-add',
                'id' => (int) $a['oid'],
                'mid' => (int) $a['mid'],
                'title' => $label
            )
        );
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
