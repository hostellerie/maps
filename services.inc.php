<?php
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | services.inc.php                                                          |
// | Copyright (C) 2010-2026                                                   |
// | Maintainer: ::Ben                                                         |
// +---------------------------------------------------------------------------+
/**
 * Maps marker services for trusted inter-plugin calls through PLG_invokeService().
 * HTTP/Atom webservice calls are deliberately rejected: these services are an
 * internal plugin API, not a public remote mutation API.
 */

if (strpos(strtolower(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''), 'services.inc.php') !== false) {
    die('This file can not be used on its own.');
}

function MAPS_serviceRejectWeb($args, &$svc_msg)
{
    if (is_array($args) && !empty($args['gl_svc'])) {
        $svc_msg['error_desc'] = 'Maps marker services are available only to trusted internal plugin calls.';
        return true;
    }
    return false;
}

function MAPS_serviceMarkerRow($markerId, $publicOnly = true, $checkAccess = true)
{
    global $_TABLES;
    $markerId = (string) $markerId;
    if ($markerId === '') {
        return false;
    }
    $sql = "SELECT m.*, mp.name AS map_name FROM {$_TABLES['maps_markers']} m "
        . "LEFT JOIN {$_TABLES['maps_maps']} mp ON mp.mid=m.mid "
        . "WHERE m.mkid='" . MAPS_dbEscape($markerId) . "' LIMIT 1";
    $res = DB_query($sql);
    if (!$res || DB_numRows($res) === 0) {
        return false;
    }
    $row = DB_fetchArray($res);
    if ($checkAccess) {
        $access = SEC_hasAccess((int)$row['owner_id'], (int)$row['group_id'], (int)$row['perm_owner'], (int)$row['perm_group'], (int)$row['perm_members'], (int)$row['perm_anon']);
        if ($access < 2) {
            return false;
        }
    }
    if ($publicOnly && ((int)$row['active'] !== 1 || (int)$row['hidden'] === 1)) {
        return false;
    }
    return $row;
}

function MAPS_serviceMarkerData($row)
{
    return array(
        'id' => (string)$row['mkid'],
        'name' => MAPS_decodeStoredText($row['name']),
        'map_id' => (int)$row['mid'],
        'map_name' => isset($row['map_name']) ? MAPS_decodeStoredText($row['map_name']) : '',
        'address' => MAPS_decodeStoredText($row['address']),
        'lat' => (float)MAPS_latitude($row['lat'], 0),
        'lng' => (float)MAPS_longitude($row['lng'], 0),
        'url' => MAPS_markerContentUrl($row['mkid']),
        'active' => (int)$row['active'],
        'hidden' => (int)$row['hidden'],
        'payed' => (int)$row['payed'],
        'validity' => (int)$row['validity'],
        'validity_start' => (string)$row['validity_start'],
        'validity_end' => (string)$row['validity_end'],
        'modified' => (string)$row['modified']
    );
}

function MAPS_serviceOperationClaim($args, $action, $markerId, &$duplicate, &$svc_msg)
{
    global $_TABLES;
    $duplicate = false;
    $operationId = trim((string)MAPS_arrayGet($args, 'operation_id', ''));
    if ($operationId === '') {
        return true;
    }
    if (strlen($operationId) > 255) {
        $svc_msg['error_desc'] = 'operation_id is too long.';
        return false;
    }
    $key = hash('sha256', $operationId);
    $res = DB_query("SELECT action,marker_id FROM {$_TABLES['maps_service_operations']} WHERE operation_key='" . MAPS_dbEscape($key) . "' LIMIT 1");
    if ($res && DB_numRows($res) > 0) {
        $old = DB_fetchArray($res);
        if ((string)$old['action'] !== (string)$action || (string)$old['marker_id'] !== (string)$markerId) {
            $svc_msg['error_desc'] = 'operation_id was already used for another marker operation.';
            return false;
        }
        $duplicate = true;
        return true;
    }
    $source = substr(trim((string)MAPS_arrayGet($args, 'source', '')), 0, 64);
    $sourceId = substr(trim((string)MAPS_arrayGet($args, 'source_id', '')), 0, 255);
    DB_query("INSERT INTO {$_TABLES['maps_service_operations']} (operation_key,operation_id,action,marker_id,source,source_id,created) VALUES ('"
        . MAPS_dbEscape($key) . "','" . MAPS_dbEscape($operationId) . "','" . MAPS_dbEscape($action) . "','" . MAPS_dbEscape($markerId) . "','"
        . MAPS_dbEscape($source) . "','" . MAPS_dbEscape($sourceId) . "','" . date('Y-m-d H:i:s') . "')");
    if (DB_error()) {
        $svc_msg['error_desc'] = 'Unable to reserve marker service operation.';
        return false;
    }
    return true;
}

function MAPS_serviceOperationRollback($args)
{
    global $_TABLES;
    $operationId = trim((string)MAPS_arrayGet($args, 'operation_id', ''));
    if ($operationId !== '') {
        DB_query("DELETE FROM {$_TABLES['maps_service_operations']} WHERE operation_key='" . MAPS_dbEscape(hash('sha256', $operationId)) . "'");
    }
}

function service_marker_save_maps($args, &$output, &$svc_msg)
{
    global $_TABLES, $_USER, $_GROUPS;
    $output = array();
    $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $source = substr(trim((string)MAPS_arrayGet($args, 'source', '')), 0, 64);
    $sourceId = substr(trim((string)MAPS_arrayGet($args, 'source_id', '')), 0, 255);
    if ($source === '' || $sourceId === '') { $svc_msg['error_desc'] = 'source and source_id are required for marker_save.'; return PLG_RET_ERROR; }
    $markerId = preg_replace('/[^0-9]/', '', (string)MAPS_arrayGet($args, 'marker_id', ''));
    $existing = ($markerId !== '') ? MAPS_serviceMarkerRow($markerId, false, false) : false;
    if ($markerId !== '' && $existing === false) { $svc_msg['error_desc'] = 'Marker not found.'; return PLG_RET_ERROR; }
    $mapId = (int)MAPS_arrayGet($args, 'map_id', $existing ? $existing['mid'] : 0);
    if ($mapId <= 0 || DB_getItem($_TABLES['maps_maps'], 'mid', 'mid=' . $mapId) === '') { $svc_msg['error_desc'] = 'A valid map_id is required.'; return PLG_RET_ERROR; }
    $name = MAPS_normalizeMarkerText(MAPS_arrayGet($args, 'name', $existing ? $existing['name'] : ''));
    $address = MAPS_normalizeMarkerText(MAPS_arrayGet($args, 'address', $existing ? $existing['address'] : ''));
    if ($name === '' || $address === '') { $svc_msg['error_desc'] = 'Marker name and address are required.'; return PLG_RET_ERROR; }
    $lat = MAPS_arrayGet($args, 'lat', $existing ? $existing['lat'] : '');
    $lng = MAPS_arrayGet($args, 'lng', $existing ? $existing['lng'] : '');
    if (!MAPS_isValidCoordinatePair($lat, $lng)) {
        MAPS_getCoords($address, $lat, $lng);
        if (!MAPS_isValidCoordinatePair($lat, $lng)) { $svc_msg['error_desc'] = 'Unable to resolve valid marker coordinates.'; return PLG_RET_ERROR; }
    }
    $lat = MAPS_canonicalNumberString($lat, '0');
    $lng = MAPS_canonicalNumberString($lng, '0');
    $ownerId = (int)MAPS_arrayGet($args, 'owner_id', $existing ? $existing['owner_id'] : (isset($_USER['uid']) ? $_USER['uid'] : 2));
    $groupId = (int)MAPS_arrayGet($args, 'group_id', $existing ? $existing['group_id'] : (isset($_GROUPS['Maps Admin']) ? $_GROUPS['Maps Admin'] : 2));
    if ($ownerId <= 0) $ownerId = 2;
    if ($groupId <= 0) $groupId = 2;
    $permOwner = (int)MAPS_arrayGet($args, 'perm_owner', $existing ? $existing['perm_owner'] : 3);
    $permGroup = (int)MAPS_arrayGet($args, 'perm_group', $existing ? $existing['perm_group'] : 3);
    $permMembers = (int)MAPS_arrayGet($args, 'perm_members', $existing ? $existing['perm_members'] : 2);
    $permAnon = (int)MAPS_arrayGet($args, 'perm_anon', $existing ? $existing['perm_anon'] : 2);
    $active = (int)MAPS_arrayGet($args, 'active', $existing ? $existing['active'] : 1) ? 1 : 0;
    $hidden = (int)MAPS_arrayGet($args, 'hidden', $existing ? $existing['hidden'] : 0) ? 1 : 0;
    $sourceUrl = trim((string)MAPS_arrayGet($args, 'source_url', $existing ? $existing['url'] : ''));
    if ($markerId === '') {
        $markerId = preg_replace('/[^0-9]/', '', (string)COM_makeSid());
        if ($markerId === '') { $svc_msg['error_desc'] = 'Unable to allocate a marker id.'; return PLG_RET_ERROR; }
    }
    $duplicate = false;
    if (!MAPS_serviceOperationClaim($args, 'marker_save', $markerId, $duplicate, $svc_msg)) return PLG_RET_ERROR;
    if ($duplicate) {
        $row = MAPS_serviceMarkerRow($markerId, false, false);
        if ($row !== false) { $output = MAPS_serviceMarkerData($row); $output['idempotent'] = true; return PLG_RET_OK; }
        $svc_msg['error_desc'] = 'Idempotent marker operation has no marker.'; return PLG_RET_ERROR;
    }
    $now = date('YmdHis');
    $safeId = MAPS_dbEscape($markerId); $safeName = MAPS_dbEscape($name); $safeAddress = MAPS_dbEscape($address); $safeUrl = MAPS_dbEscape($sourceUrl); $safeSource = MAPS_dbEscape($source);
    if ($existing !== false) {
        DB_query("UPDATE {$_TABLES['maps_markers']} SET name='{$safeName}',modified='{$now}',address='{$safeAddress}',lat='{$lat}',lng='{$lng}',mid={$mapId},url='{$safeUrl}',type='{$safeSource}',active={$active},hidden={$hidden},owner_id={$ownerId},group_id={$groupId},perm_owner={$permOwner},perm_group={$permGroup},perm_members={$permMembers},perm_anon={$permAnon} WHERE mkid='{$safeId}'");
    } else {
        DB_query("INSERT INTO {$_TABLES['maps_markers']} SET mkid='{$safeId}',name='{$safeName}',created='{$now}',modified='{$now}',address='{$safeAddress}',lat='{$lat}',lng='{$lng}',mid={$mapId},url='{$safeUrl}',type='{$safeSource}',active={$active},hidden={$hidden},owner_id={$ownerId},group_id={$groupId},perm_owner={$permOwner},perm_group={$permGroup},perm_members={$permMembers},perm_anon={$permAnon},submission=0");
    }
    if (DB_error()) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc'] = 'Unable to save marker.'; return PLG_RET_ERROR; }
    if (function_exists('updateMap')) updateMap($mapId);
    MAPS_notifyMarkerSaved($markerId, $mapId);
    $row = MAPS_serviceMarkerRow($markerId, false, false);
    $output = ($row !== false) ? MAPS_serviceMarkerData($row) : array('id' => $markerId, 'map_id' => $mapId);
    $output['idempotent'] = false;
    return PLG_RET_OK;
}

function plugin_wsEnabled_maps() { return true; }

function service_marker_list_maps($args, &$output, &$svc_msg)
{
    global $_TABLES;
    $output = array(); $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $includeInactive = !empty($args['include_inactive']) && SEC_hasRights('maps.admin');
    $mapId = (int)MAPS_arrayGet($args, 'map_id', 0);
    $sql = "SELECT m.*,mp.name AS map_name FROM {$_TABLES['maps_markers']} m LEFT JOIN {$_TABLES['maps_maps']} mp ON mp.mid=m.mid WHERE 1=1";
    if (!$includeInactive) $sql .= ' AND m.active=1 AND m.hidden=0';
    if ($mapId > 0) $sql .= ' AND m.mid=' . $mapId;
    $sql .= ' ORDER BY mp.name,m.name';
    $res = DB_query($sql);
    while ($res && ($row = DB_fetchArray($res))) {
        if (SEC_hasAccess((int)$row['owner_id'], (int)$row['group_id'], (int)$row['perm_owner'], (int)$row['perm_group'], (int)$row['perm_members'], (int)$row['perm_anon']) >= 2) $output[] = MAPS_serviceMarkerData($row);
    }
    return PLG_RET_OK;
}

function service_marker_get_maps($args, &$output, &$svc_msg)
{
    $output = array(); $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $row = MAPS_serviceMarkerRow(MAPS_arrayGet($args, 'marker_id', ''), empty($args['include_inactive']) || !SEC_hasRights('maps.admin'));
    if ($row === false) { $svc_msg['error_desc'] = 'Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $output = MAPS_serviceMarkerData($row);
    return PLG_RET_OK;
}

function service_marker_render_maps($args, &$output, &$svc_msg)
{
    global $_SCRIPTS;
    $output = ''; $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $row = MAPS_serviceMarkerRow(MAPS_arrayGet($args, 'marker_id', ''), true);
    if ($row === false) { $svc_msg['error_desc'] = 'Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $width = MAPS_cssSize(MAPS_arrayGet($args, 'width', '100%'), '100%');
    $height = MAPS_cssSize(MAPS_arrayGet($args, 'height', '320px'), '320px');
    $zoom = MAPS_zoom(MAPS_arrayGet($args, 'zoom', 14), 14);
    $id = 'maps-service-marker-' . preg_replace('/[^a-zA-Z0-9_-]/', '-', (string)$row['mkid']) . '-' . substr(md5(uniqid('', true)), 0, 8);
    if (isset($_SCRIPTS) && is_object($_SCRIPTS) && method_exists($_SCRIPTS, 'setJavaScriptFile')) {
        $_SCRIPTS->setJavaScriptFile('maps_google_api_service', MAPS_googleMapsApiUrl(), false);
    }
    $js = "(function ready(attempt){var el=document.getElementById(" . MAPS_jsString($id) . ");if(!window.google||!google.maps||!el||el.offsetWidth===0||el.offsetHeight===0){if(attempt<200){setTimeout(function(){ready(attempt+1);},50);}return;}var p={lat:Number(" . MAPS_jsNumber($row['lat'], 0) . "),lng:Number(" . MAPS_jsNumber($row['lng'], 0) . ")};var m=new google.maps.Map(el,{center:p,zoom:" . (int)$zoom . "});new google.maps.Marker({position:p,map:m,title:" . MAPS_jsString(MAPS_decodeStoredText($row['name'])) . "});})(0);";
    if (isset($_SCRIPTS) && is_object($_SCRIPTS) && method_exists($_SCRIPTS, 'setJavaScript')) $_SCRIPTS->setJavaScript($js, true, true);
    $output = '<div class="maps-marker-service"><div id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '" style="width:' . htmlspecialchars($width, ENT_QUOTES, 'UTF-8') . ';height:' . htmlspecialchars($height, ENT_QUOTES, 'UTF-8') . '"></div></div>';
    return PLG_RET_OK;
}

function MAPS_serviceApplyValidity($args, $extend, &$output, &$svc_msg)
{
    global $_TABLES;
    $output = array(); $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $markerId = (string)MAPS_arrayGet($args, 'marker_id', '');
    $row = MAPS_serviceMarkerRow($markerId, false, false);
    if ($row === false) { $svc_msg['error_desc'] = 'Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $action = $extend ? 'marker_extend_validity' : 'marker_set_validity';
    $duplicate = false;
    if (!MAPS_serviceOperationClaim($args, $action, $markerId, $duplicate, $svc_msg)) return PLG_RET_ERROR;
    if ($duplicate) { $output = MAPS_serviceMarkerData($row); $output['idempotent'] = true; return PLG_RET_OK; }
    $now = time();
    if ($extend) {
        $days = (int)MAPS_arrayGet($args, 'days', 0);
        if ($days < 1 || $days > 36500) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc'] = 'days must be between 1 and 36500.'; return PLG_RET_ERROR; }
        $currentEnd = strtotime((string)$row['validity_end']);
        $base = ($currentEnd !== false && $currentEnd > $now) ? $currentEnd : $now;
        $start = ((int)$row['validity'] === 1 && strtotime((string)$row['validity_start']) !== false) ? strtotime((string)$row['validity_start']) : $now;
        $end = strtotime('+' . $days . ' days', $base);
    } else {
        $start = strtotime((string)MAPS_arrayGet($args, 'validity_start', ''));
        $end = strtotime((string)MAPS_arrayGet($args, 'validity_end', ''));
        if ($start === false || $end === false || $end <= $start) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc'] = 'A valid validity_start and later validity_end are required.'; return PLG_RET_ERROR; }
    }
    $paid = isset($args['payed']) ? ((int)$args['payed'] ? 1 : 0) : (int)$row['payed'];
    DB_query("UPDATE {$_TABLES['maps_markers']} SET validity=1,validity_start='" . date('Y-m-d H:i:s', $start) . "',validity_end='" . date('Y-m-d H:i:s', $end) . "',payed=" . $paid . ",modified='" . date('Y-m-d H:i:s') . "' WHERE mkid='" . MAPS_dbEscape($markerId) . "'");
    if (DB_error()) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc'] = 'Unable to update marker validity.'; return PLG_RET_ERROR; }
    MAPS_notifyMarkerSaved($markerId, (int)$row['mid']);
    $updated = MAPS_serviceMarkerRow($markerId, false, false);
    $output = MAPS_serviceMarkerData($updated);
    $output['idempotent'] = false;
    return PLG_RET_OK;
}

function service_marker_set_validity_maps($args, &$output, &$svc_msg)
{
    return MAPS_serviceApplyValidity($args, false, $output, $svc_msg);
}

function service_marker_extend_validity_maps($args, &$output, &$svc_msg)
{
    return MAPS_serviceApplyValidity($args, true, $output, $svc_msg);
}
