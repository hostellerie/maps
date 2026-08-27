<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                         |
// +---------------------------------------------------------------------------+
// | overlays.php                                                              |
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

$mode = isset($_REQUEST['mode']) ? COM_applyFilter($_REQUEST['mode']) : '';
$msg = isset($_REQUEST['msg']) ? (string) $_REQUEST['msg'] : '';

/**
 * List all overlays.
 *
 * @return string
 */
function MAPS_listOverlays()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_MAPS_1;

    require_once $_CONF['path_system'] . 'lib-admin.php';

    $header = array(
        array('text' => $LANG_MAPS_1['id'], 'field' => 'oid', 'sort' => true),
        array('text' => $LANG_MAPS_1['name'], 'field' => 'o_name', 'sort' => true),
        array('text' => $LANG_MAPS_1['group'], 'field' => 'o_group_name', 'sort' => true),
        array('text' => $LANG_MAPS_1['order'], 'field' => 'o_order', 'sort' => true),
        array('text' => $LANG_MAPS_1['move'], 'field' => 'move', 'sort' => false),
        array('text' => $LANG_MAPS_1['active_field'], 'field' => 'o_active', 'sort' => true),
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
    );
    $sort = array('field' => 'o_order', 'direction' => 'asc');
    $text = array(
        'has_extras' => true,
        'form_url' => $_CONF['site_admin_url'] . '/plugins/maps/overlays.php'
    );
    $query = array(
        'sql' => "SELECT o.*, og.o_group_name "
            . "FROM {$_TABLES['maps_overlays']} AS o "
            . "LEFT JOIN {$_TABLES['maps_overlays_groups']} AS og ON o.o_group = og.o_group_id "
            . "WHERE 1=1",
        'query_fields' => array('o_name'),
        'default_filter' => ''
    );

    return ADMIN_list('overlays', 'MAPS_getListField_overlays', $header, $text, $query, $sort);
}

/**
 * Render one field in the overlay list.
 *
 * @return string
 */
function MAPS_getListField_overlays($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    switch ($fieldname) {
        case 'edit':
            return COM_createLink(
                $icon_arr['edit'],
                $_CONF['site_admin_url'] . '/plugins/maps/overlay_edit.php?mode=edit&amp;oid=' . (int) $A['oid']
            );

        case 'o_name':
            $name = htmlspecialchars((string) $A['o_name'], ENT_QUOTES, 'UTF-8');
            $imageName = basename((string) $A['o_image']);
            $overlayImage = $_MAPS_CONF['path_overlay_images'] . $imageName;
            if ($imageName !== '' && is_file($overlayImage)) {
                $overlayUrl = $_MAPS_CONF['images_overlay_url'] . rawurlencode($imageName);
                return COM_getTooltip(
                    $name,
                    '<img src="' . htmlspecialchars($overlayUrl, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:200px;height:auto">',
                    '',
                    $name,
                    'help'
                );
            }
            return $name;

        case 'move':
            $oid = (int) $A['oid'];
            $token = SEC_createToken();
            $action = htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlays.php', ENT_QUOTES, 'UTF-8');
            $tokenName = htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8');
            $safeToken = htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
            $upLabel = htmlspecialchars(isset($LANG_MAPS_1['move_up']) ? $LANG_MAPS_1['move_up'] : 'Move up', ENT_QUOTES, 'UTF-8');
            $downLabel = htmlspecialchars(isset($LANG_MAPS_1['move_down']) ? $LANG_MAPS_1['move_down'] : 'Move down', ENT_QUOTES, 'UTF-8');

            return '<form action="' . $action . '" method="post" class="maps-inline-form">'
                . '<input type="hidden" name="mode" value="move">'
                . '<input type="hidden" name="oid" value="' . $oid . '">'
                . '<input type="hidden" name="' . $tokenName . '" value="' . $safeToken . '">'
                . '<button type="submit" name="where" value="up" title="' . $upLabel . '" aria-label="' . $upLabel . '">&#8593;</button> '
                . '<button type="submit" name="where" value="dn" title="' . $downLabel . '" aria-label="' . $downLabel . '">&#8595;</button>'
                . '</form>';

        case 'o_active':
            return MAPS_adminStatusBadge(
                $fieldvalue,
                $LANG_MAPS_1['status_active'],
                $LANG_MAPS_1['status_inactive']
            );

        default:
            return htmlspecialchars((string) $fieldvalue, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Normalize overlay ordering to increments of ten.
 *
 * This function performs writes and must only be called from an already
 * authorized, CSRF-validated mutation path.
 *
 * @return void
 */
function MAPS_reorderOverlays()
{
    global $_TABLES;

    $result = DB_query(
        "SELECT oid, o_order FROM {$_TABLES['maps_overlays']} ORDER BY o_order ASC, oid ASC"
    );
    $order = 10;
    while ($A = DB_fetchArray($result)) {
        $oid = (int) $A['oid'];
        if ((int) $A['o_order'] !== $order) {
            DB_query(
                "UPDATE {$_TABLES['maps_overlays']} SET o_order={$order} WHERE oid={$oid}"
            );
        }
        $order += 10;
    }
}

/**
 * List overlay groups.
 *
 * @return string
 */
function MAPS_listOverlaysGroups()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_MAPS_1;

    require_once $_CONF['path_system'] . 'lib-admin.php';

    $header = array(
        array('text' => $LANG_MAPS_1['id'], 'field' => 'o_group_id', 'sort' => true),
        array('text' => $LANG_MAPS_1['name'], 'field' => 'o_group_name', 'sort' => true),
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
    );
    $sort = array('field' => 'o_group_name', 'direction' => 'asc');
    $text = array(
        'has_extras' => true,
        'form_url' => $_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups'
    );
    $query = array(
        'sql' => "SELECT * FROM {$_TABLES['maps_overlays_groups']} WHERE 1=1"
    );

    return ADMIN_list('overlays', 'MAPS_getListField_overlaysGroups', $header, $text, $query, $sort);
}

/**
 * Render one field in the overlay group list.
 *
 * @return string
 */
function MAPS_getListField_overlaysGroups($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF;

    if ($fieldname === 'edit') {
        return COM_createLink(
            $icon_arr['edit'],
            $_CONF['site_admin_url'] . '/plugins/maps/overlay_group_edit.php?mode=edit&amp;o_group_id=' . (int) $A['o_group_id']
        );
    }

    return htmlspecialchars((string) $fieldvalue, ENT_QUOTES, 'UTF-8');
}

// MAIN
$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= maps_admin_menu();

if ($msg !== '') {
    $display .= MAPS_message(htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'));
}

if (!file_exists($_MAPS_CONF['path_overlay_images']) || !is_writable($_MAPS_CONF['path_overlay_images'])) {
    $display .= COM_showMessageText(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_overlay_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . htmlspecialchars($LANG_MAPS_1['overlay_not_writable'], ENT_QUOTES, 'UTF-8') . '</p>'
    );
} else {
    $display .= '<br><h1>' . htmlspecialchars($LANG_MAPS_1['overlays_list'], ENT_QUOTES, 'UTF-8') . '</h1>';
    $display .= '<ul>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_edit.php', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_overlay'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['manage_groups'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_group_edit.php?mode=new', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_group'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '</ul>';

    if ($mode === 'move') {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !SEC_checkToken()) {
            COM_accessLog('Invalid CSRF token on Maps overlay ordering action.');
            $display .= COM_showMessageText($MESSAGE[29], $MESSAGE[30]);
        } else {
            $oid = isset($_POST['oid']) ? (int) $_POST['oid'] : 0;
            $where = isset($_POST['where']) ? COM_applyFilter($_POST['where']) : '';

            if ($oid > 0 && in_array($where, array('up', 'dn'), true)
                && (int) DB_count($_TABLES['maps_overlays'], 'oid', $oid) === 1
            ) {
                $delta = ($where === 'up') ? -11 : 11;
                DB_query(
                    "UPDATE {$_TABLES['maps_overlays']} SET o_order=o_order+({$delta}) WHERE oid={$oid}"
                );
                if (!DB_error()) {
                    MAPS_reorderOverlays();
                }
            }
        }
        $display .= MAPS_listOverlays();
    } elseif ($mode === 'groups') {
        $display .= MAPS_listOverlaysGroups();
    } else {
        $display .= MAPS_listOverlays();
    }
}

$display .= MAPS_compatSiteFooter(0);
MAPS_compatOutput($display);
