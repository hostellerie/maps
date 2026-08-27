from pathlib import Path

branch_files = ['autoinstall.php','maps.php','functions.inc','sql/mysql_install.php','.github/workflows/package.yml']

# Version metadata
p=Path('autoinstall.php'); s=p.read_text(); s=s.replace('Maps Plugin 1.5.8','Maps Plugin 1.5.9').replace("'pi_version' => '1.5.8'","'pi_version' => '1.5.9'").replace('Official support target for Maps 1.5.8','Official support target for Maps 1.5.9'); s=s.replace("        'maps_overlays_groups'\n", "        'maps_overlays_groups',\n        'maps_service_operations'\n"); p.write_text(s)

p=Path('maps.php'); s=p.read_text(); s=s.replace('Maps Plugin 1.5.8','Maps Plugin 1.5.9'); s=s.replace("$_TABLES['maps_overlays_groups'] = $_DB_table_prefix . 'maps_overlays_groups';", "$_TABLES['maps_overlays_groups'] = $_DB_table_prefix . 'maps_overlays_groups';\n$_TABLES['maps_service_operations'] = $_DB_table_prefix . 'maps_service_operations';"); p.write_text(s)

# SQL install table
p=Path('sql/mysql_install.php'); s=p.read_text();
if 'maps_service_operations' not in s:
    s += '''\n\n$_SQL[] = "\nCREATE TABLE {$_TABLES['maps_service_operations']} (\n  operation_key char(64) NOT NULL,\n  operation_id varchar(255) NOT NULL default '',\n  action varchar(32) NOT NULL default '',\n  marker_id BIGINT NOT NULL,\n  source varchar(64) NOT NULL default '',\n  source_id varchar(255) NOT NULL default '',\n  created datetime NOT NULL,\n  PRIMARY KEY (operation_key),\n  KEY marker_id (marker_id)\n) ENGINE=MyISAM\n";\n'''
p.write_text(s)

# services implementation
services=r'''<?php
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

function MAPS_serviceMarkerRow($markerId, $publicOnly = true)
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
    $access = SEC_hasAccess((int)$row['owner_id'], (int)$row['group_id'], (int)$row['perm_owner'], (int)$row['perm_group'], (int)$row['perm_members'], (int)$row['perm_anon']);
    if ($access < 2) {
        return false;
    }
    if ($publicOnly && ((int)$row['active'] !== 1 || (int)$row['hidden'] === 1)) {
        return false;
    }
    return $row;
}

function MAPS_serviceMarkerData($row)
{
    global $_MAPS_CONF;
    return array(
        'id' => (string)$row['mkid'],
        'name' => MAPS_decodeStoredText($row['name']),
        'map_id' => (int)$row['mid'],
        'map_name' => isset($row['map_name']) ? MAPS_decodeStoredText($row['map_name']) : '',
        'address' => MAPS_decodeStoredText($row['address']),
        'lat' => (float)MAPS_latitude($row['lat'], 0),
        'lng' => (float)MAPS_longitude($row['lng'], 0),
        'url' => $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($row['mkid']) . '&mid=' . (int)$row['mid'],
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

function plugin_wsEnabled_maps()
{
    return true;
}

function service_marker_list_maps($args, &$output, &$svc_msg)
{
    global $_TABLES;
    $output = array();
    $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $includeInactive = !empty($args['include_inactive']) && SEC_hasRights('maps.admin');
    $mapId = (int)MAPS_arrayGet($args, 'map_id', 0);
    $sql = "SELECT m.*,mp.name AS map_name FROM {$_TABLES['maps_markers']} m LEFT JOIN {$_TABLES['maps_maps']} mp ON mp.mid=m.mid WHERE 1=1";
    if (!$includeInactive) $sql .= ' AND m.active=1 AND m.hidden=0';
    if ($mapId > 0) $sql .= ' AND m.mid=' . $mapId;
    $sql .= ' ORDER BY mp.name,m.name';
    $res = DB_query($sql);
    while ($res && ($row = DB_fetchArray($res))) {
        if (SEC_hasAccess((int)$row['owner_id'], (int)$row['group_id'], (int)$row['perm_owner'], (int)$row['perm_group'], (int)$row['perm_members'], (int)$row['perm_anon']) >= 2) {
            $output[] = MAPS_serviceMarkerData($row);
        }
    }
    return PLG_RET_OK;
}

function service_marker_get_maps($args, &$output, &$svc_msg)
{
    $output = array(); $svc_msg = array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $row = MAPS_serviceMarkerRow(MAPS_arrayGet($args, 'marker_id', ''), empty($args['include_inactive']) || !SEC_hasRights('maps.admin'));
    if ($row === false) { $svc_msg['error_desc']='Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $output = MAPS_serviceMarkerData($row);
    return PLG_RET_OK;
}

function service_marker_render_maps($args, &$output, &$svc_msg)
{
    global $_SCRIPTS;
    $output=''; $svc_msg=array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $row=MAPS_serviceMarkerRow(MAPS_arrayGet($args,'marker_id',''), true);
    if ($row===false) { $svc_msg['error_desc']='Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $width=MAPS_cssSize(MAPS_arrayGet($args,'width','100%'),'100%');
    $height=MAPS_cssSize(MAPS_arrayGet($args,'height','320px'),'320px');
    $zoom=MAPS_zoom(MAPS_arrayGet($args,'zoom',14),14);
    $id='maps-service-marker-' . preg_replace('/[^a-zA-Z0-9_-]/','-',(string)$row['mkid']) . '-' . substr(md5(uniqid('',true)),0,8);
    if (isset($_SCRIPTS) && is_object($_SCRIPTS) && method_exists($_SCRIPTS,'setJavaScriptFile')) {
        $_SCRIPTS->setJavaScriptFile('maps_google_api_service', MAPS_googleMapsApiUrl(), false);
    }
    $js="(function ready(){if(!window.google||!google.maps){setTimeout(ready,50);return;}var el=document.getElementById(" . MAPS_jsString($id) . ");if(!el)return;var p={lat:Number(" . MAPS_jsNumber($row['lat'],0) . "),lng:Number(" . MAPS_jsNumber($row['lng'],0) . ")};var m=new google.maps.Map(el,{center:p,zoom:" . (int)$zoom . "});new google.maps.Marker({position:p,map:m,title:" . MAPS_jsString(MAPS_decodeStoredText($row['name'])) . "});})();";
    if (isset($_SCRIPTS) && is_object($_SCRIPTS) && method_exists($_SCRIPTS,'setJavaScript')) $_SCRIPTS->setJavaScript($js,true,true);
    $output='<div class="maps-marker-service"><div id="'.htmlspecialchars($id,ENT_QUOTES,'UTF-8').'" style="width:'.htmlspecialchars($width,ENT_QUOTES,'UTF-8').';height:'.htmlspecialchars($height,ENT_QUOTES,'UTF-8').'"></div></div>';
    return PLG_RET_OK;
}

function MAPS_serviceApplyValidity($args, $extend, &$output, &$svc_msg)
{
    global $_TABLES;
    $output=array(); $svc_msg=array();
    if (MAPS_serviceRejectWeb($args, $svc_msg)) return PLG_RET_AUTH_FAILED;
    $markerId=(string)MAPS_arrayGet($args,'marker_id','');
    $row=MAPS_serviceMarkerRow($markerId,false);
    if ($row===false) { $svc_msg['error_desc']='Marker not found or not accessible.'; return PLG_RET_ERROR; }
    $action=$extend?'marker_extend_validity':'marker_set_validity';
    $duplicate=false;
    if (!MAPS_serviceOperationClaim($args,$action,$markerId,$duplicate,$svc_msg)) return PLG_RET_ERROR;
    if ($duplicate) { $output=MAPS_serviceMarkerData($row); $output['idempotent']=true; return PLG_RET_OK; }
    $now=time();
    if ($extend) {
        $days=(int)MAPS_arrayGet($args,'days',0);
        if ($days<1 || $days>36500) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc']='days must be between 1 and 36500.'; return PLG_RET_ERROR; }
        $currentEnd=strtotime((string)$row['validity_end']);
        $base=($currentEnd!==false && $currentEnd>$now)?$currentEnd:$now;
        $start=((int)$row['validity']===1 && strtotime((string)$row['validity_start'])!==false)?strtotime((string)$row['validity_start']):$now;
        $end=strtotime('+' . $days . ' days',$base);
    } else {
        $start=strtotime((string)MAPS_arrayGet($args,'validity_start',''));
        $end=strtotime((string)MAPS_arrayGet($args,'validity_end',''));
        if ($start===false || $end===false || $end<=$start) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc']='A valid validity_start and later validity_end are required.'; return PLG_RET_ERROR; }
    }
    $paid = isset($args['payed']) ? ((int)$args['payed'] ? 1 : 0) : (int)$row['payed'];
    DB_query("UPDATE {$_TABLES['maps_markers']} SET validity=1,validity_start='".date('Y-m-d H:i:s',$start)."',validity_end='".date('Y-m-d H:i:s',$end)."',payed=".$paid.",modified='".date('Y-m-d H:i:s')."' WHERE mkid='".MAPS_dbEscape($markerId)."'");
    if (DB_error()) { MAPS_serviceOperationRollback($args); $svc_msg['error_desc']='Unable to update marker validity.'; return PLG_RET_ERROR; }
    updateMap((int)$row['mid']);
    $updated=MAPS_serviceMarkerRow($markerId,false);
    $output=MAPS_serviceMarkerData($updated);
    $output['idempotent']=false;
    return PLG_RET_OK;
}

function service_marker_set_validity_maps($args, &$output, &$svc_msg)
{
    return MAPS_serviceApplyValidity($args,false,$output,$svc_msg);
}

function service_marker_extend_validity_maps($args, &$output, &$svc_msg)
{
    return MAPS_serviceApplyValidity($args,true,$output,$svc_msg);
}
'''
Path('services.inc.php').write_text(services)

# include services + upgrade 1.5.9 + uninstall table
p=Path('functions.inc'); s=p.read_text();
inc="require_once $_CONF['path'] . 'plugins/maps/services.inc.php';"
if inc not in s:
    anchor="require_once $_CONF['path'] . 'plugins/maps/maps.php';"
    s=s.replace(anchor,anchor+'\n'+inc,1)
# add upgrade helper before plugin_upgrade_maps
anchor='function plugin_upgrade_maps()\n{'
helper=r'''function MAPS_upgrade159()
{
    global $_TABLES;
    if (!isset($_TABLES['maps_service_operations'])) {
        return false;
    }
    DB_query("CREATE TABLE IF NOT EXISTS {$_TABLES['maps_service_operations']} (operation_key char(64) NOT NULL, operation_id varchar(255) NOT NULL default '', action varchar(32) NOT NULL default '', marker_id BIGINT NOT NULL, source varchar(64) NOT NULL default '', source_id varchar(255) NOT NULL default '', created datetime NOT NULL, PRIMARY KEY (operation_key), KEY marker_id (marker_id)) ENGINE=MyISAM");
    return !DB_error();
}

'''
if 'function MAPS_upgrade159()' not in s: s=s.replace(anchor,helper+anchor,1)
needle="    if (version_compare($installed, $code, '<')) {"
step="    /* Maps 1.5.9 adds the internal marker service operation ledger. */\n    if (version_compare($installed, '1.5.9', '<')) {\n        if (!MAPS_upgrade159()) {\n            COM_errorLog('Maps 1.5.9 upgrade stopped: marker service operation table creation failed.');\n            return false;\n        }\n    }\n\n"
if step not in s: s=s.replace(needle,step+needle,1)
s=s.replace("'maps_overlays_groups'),", "'maps_overlays_groups', 'maps_service_operations'),")
p.write_text(s)

# Release notes and service documentation
Path('RELEASE-NOTES-1.5.9.md').write_text('''# Maps 1.5.9 release notes\n\n## Marker Service API\n\nMaps 1.5.9 introduces a trusted inter-plugin marker service API for Documents, Store and other Geeklog plugins.\n\nServices available through `PLG_invokeService()`:\n\n- `marker_list` — list accessible markers for selectors;\n- `marker_get` — retrieve normalized marker data;\n- `marker_render` — render an embeddable Google map focused on one marker;\n- `marker_set_validity` — set an explicit marker validity period;\n- `marker_extend_validity` — extend a marker from the later of now or its current expiry.\n\nValidity mutations support `operation_id` idempotency so payment callbacks cannot apply the same purchased duration twice. `source` and `source_id` are recorded for traceability.\n\nThese services are deliberately internal. Requests arriving through Geeklog Webservices (`gl_svc`) are rejected. Maps remains the owner of marker storage, permissions, rendering and validity rules; consumer plugins must not write Maps tables directly.\n''')

# package workflow version
p=Path('.github/workflows/package.yml'); s=p.read_text(); s=s.replace('1.5.8','1.5.9'); p.write_text(s)
