<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                        |
// +--------------------------------------------------------------------------+
// | ajax.php                                                                 |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2011-2026 by the following authors:                        |
// |                                                                          |
// | Authors: ::Ben - cordiste AT free DOT fr                                 |
// +--------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once 'edit_functions.php';

if (!SEC_hasRights('maps.admin')) {
    http_response_code(403);
    exit;
}

// Incoming variable filter
$vars = array(
    'action' => 'alpha',
    'id' => 'number',
    'mid' => 'number'
);
MAPS_filterVars($vars, $_POST);

$action = isset($_POST['action']) ? $_POST['action'] : '';
$mid = isset($_POST['mid']) ? (int) $_POST['mid'] : 0;
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if (!in_array($action, array('add', 'delete'), true) || !SEC_checkToken()) {
    http_response_code(403);
    exit;
}

if ($action === 'delete') {
    // Resolve the map from the stored relation instead of trusting the
    // client-provided mid. This also lets us distinguish a real deletion
    // from a stale/no-op request before emitting a lifecycle event.
    $storedMid = (int) DB_getItem(
        $_TABLES['maps_map_overlay'],
        'mo_mid',
        'mo_id=' . $id
    );
    if ($storedMid > 0) {
        DB_delete($_TABLES['maps_map_overlay'], 'mo_id', $id);
        if ((int) DB_count($_TABLES['maps_map_overlay'], 'mo_id', $id) === 0) {
            updateMap($storedMid);
            $mid = $storedMid;
        }
    }
} elseif ($mid > 0 && $id > 0) {
    $mapExists = (int) DB_count($_TABLES['maps_maps'], 'mid', $mid) === 1;
    $overlayExists = (int) DB_count($_TABLES['maps_overlays'], 'oid', $id) === 1;
    if ($mapExists && $overlayExists) {
        $before = (int) DB_count(
            $_TABLES['maps_map_overlay'],
            array('mo_mid', 'mo_oid'),
            array($mid, $id)
        );
        if ($before === 0) {
            DB_query(
                "INSERT INTO {$_TABLES['maps_map_overlay']} SET mo_mid={$mid}, mo_oid={$id}"
            );
            $after = (int) DB_count(
                $_TABLES['maps_map_overlay'],
                array('mo_mid', 'mo_oid'),
                array($mid, $id)
            );
            if ($after > 0) {
                updateMap($mid);
            }
        }
    }
}

echo '<div id="overlays_actions">'
    . '<div id="overlays_list">' . MAPS_displayOverlays($mid) . '</div>'
    . '<div id="add_overlay">' . MAPS_displayOverlaysToAdd($mid) . '</div>'
    . '</div>';
