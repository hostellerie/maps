from pathlib import Path
import re

# --- interoperability.php -------------------------------------------------
p = Path('interoperability.php')
text = p.read_text()

insert_after = "function MAPS_markerContentUrl($mkid)\n{\n    global $_MAPS_CONF;\n\n    $mkid = trim((string) $mkid);\n    if ($mkid === '') {\n        return '';\n    }\n\n    return rtrim($_MAPS_CONF['site_url'], '/') . '/index.php?mode=marker&mkid='\n        . rawurlencode($mkid);\n}\n"
addition = r'''

/**
 * Normalize a marker row to the same Item Info contract used for maps.
 *
 * @param array $row
 * @return array
 */
function MAPS_markerContentNormalizeRow($row)
{
    if (!is_array($row) || !isset($row['mkid']) || trim((string) $row['mkid']) === '') {
        return array();
    }

    $mkid = trim((string) $row['mkid']);
    $title = trim(MAPS_decodeStoredText(isset($row['name']) ? $row['name'] : ''));
    $description = trim(MAPS_decodeStoredText(isset($row['description']) ? $row['description'] : ''));
    if ($title === '') {
        $title = '#' . $mkid;
    }
    $excerpt = trim(strip_tags($description));
    if (function_exists('COM_truncate')) {
        $excerpt = COM_truncate($excerpt, 255, '...');
    } elseif (strlen($excerpt) > 255) {
        $excerpt = substr($excerpt, 0, 252) . '...';
    }
    $created = isset($row['created']) ? strtotime($row['created']) : false;
    $modified = isset($row['modified']) ? strtotime($row['modified']) : false;
    $uid = isset($row['owner_id']) ? (int) $row['owner_id'] : 0;

    return array(
        'id' => 'marker:' . $mkid,
        'title' => $title,
        'url' => MAPS_markerContentUrl($mkid),
        'description' => $description,
        'excerpt' => $excerpt,
        'date-created' => ($created === false ? 0 : $created),
        'date-modified' => ($modified === false ? 0 : $modified),
        'uid' => $uid,
        'author' => ($uid > 0 && function_exists('COM_getDisplayName')) ? COM_getDisplayName($uid) : '',
        'type' => 'maps',
        'subtype' => 'marker'
    );
}

/**
 * Return one public/accessible marker for Item Info consumers.
 *
 * @param string $mkid
 * @param int    $uid
 * @return array
 */
function MAPS_markerContentQuery($mkid, $uid = 0)
{
    global $_TABLES;

    $mkid = trim((string) $mkid);
    if ($mkid === '') {
        return array();
    }
    $sql = "SELECT mk.mkid,mk.mid,mk.name,mk.description,mk.created,mk.modified,mk.owner_id "
        . "FROM {$_TABLES['maps_markers']} mk "
        . "INNER JOIN {$_TABLES['maps_maps']} m ON m.mid=mk.mid "
        . "WHERE mk.mkid='" . MAPS_dbEscape($mkid) . "' "
        . "AND mk.active=1 AND mk.hidden=0 AND m.active=1 AND m.hidden=0"
        . COM_getPermSQL('AND', (int) $uid, 2, 'mk')
        . COM_getPermSQL('AND', (int) $uid, 2, 'm')
        . " LIMIT 1";
    $result = DB_query($sql);
    if (!$result || DB_numRows($result) === 0) {
        return array();
    }
    return MAPS_markerContentNormalizeRow(DB_fetchArray($result));
}

/**
 * Emit a marker-save lifecycle event and refresh every affected parent map.
 * The marker reference is namespaced so it can never collide with a map ID.
 *
 * @param string $mkid
 * @param int    $mid
 * @param int    $previousMid
 * @param bool   $notifyParents
 * @return void
 */
function MAPS_notifyMarkerSaved($mkid, $mid = 0, $previousMid = 0, $notifyParents = true)
{
    $mkid = trim((string) $mkid);
    if ($mkid === '') {
        return;
    }
    PLG_itemSaved('marker:' . $mkid, 'maps');
    if (!$notifyParents) {
        return;
    }
    $mid = (int) $mid;
    $previousMid = (int) $previousMid;
    if ($mid > 0) {
        updateMap($mid);
    }
    if ($previousMid > 0 && $previousMid !== $mid) {
        updateMap($previousMid);
    }
}

/**
 * Emit a marker-delete lifecycle event and refresh its former parent map.
 * URL consumers can still resolve marker:<id> after deletion because the
 * canonical URL is deterministic and does not require a database lookup.
 *
 * @param string $mkid
 * @param int    $mid
 * @return void
 */
function MAPS_notifyMarkerDeleted($mkid, $mid = 0)
{
    $mkid = trim((string) $mkid);
    if ($mkid === '') {
        return;
    }
    PLG_itemDeleted('marker:' . $mkid, 'maps');
    $mid = (int) $mid;
    if ($mid > 0) {
        updateMap($mid);
    }
}
'''
if 'function MAPS_markerContentNormalizeRow' not in text:
    if insert_after not in text:
        raise SystemExit('marker URL helper insertion point not found')
    text = text.replace(insert_after, insert_after + addition, 1)

# Replace Item Info callback.
start = text.index('function plugin_getiteminfo_maps(')
end = text.index('/**\n * Resolve a Maps item ID', start)
new_callback = r'''function plugin_getiteminfo_maps($id, $what, $uid = 0, $options = array())
{
    $fields = MAPS_contentRequestedFields($what);
    if (empty($fields)) {
        return array();
    }

    $idText = trim((string) $id);
    if (strncmp($idText, 'marker:', 7) === 0) {
        $marker = MAPS_markerContentQuery(substr($idText, 7), $uid);
        if (empty($marker)) {
            return array();
        }
        return MAPS_contentSelectFields($marker, $fields, true);
    }

    $items = MAPS_contentQuery($id, $uid, $options);
    if ((string) $id !== '*') {
        if (empty($items)) {
            return array();
        }
        return MAPS_contentSelectFields($items[0], $fields, true);
    }

    $result = array();
    foreach ($items as $item) {
        $result[] = MAPS_contentSelectFields($item, $fields);
    }
    return $result;
}

'''
text = text[:start] + new_callback + text[end:]

# Replace ID-to-URL callback with one/two-argument compatible resolver.
start = text.index('function plugin_idtourl_maps(')
new_idtourl = r'''function plugin_idtourl_maps($sub_type = '', $item_id = null)
{
    // Some Geeklog/plugin consumers call the callback with one identifier,
    // while subtype-aware cores pass ($sub_type, $item_id). Support both.
    if ($item_id === null) {
        $item_id = $sub_type;
        $sub_type = '';
    }

    $subType = strtolower(trim((string) $sub_type));
    $itemText = trim((string) $item_id);

    if (strncmp($itemText, 'marker:', 7) === 0) {
        return MAPS_markerContentUrl(substr($itemText, 7));
    }
    if ($subType === 'marker') {
        return MAPS_markerContentUrl($itemText);
    }
    if ($subType !== '' && $subType !== 'map' && $subType !== 'maps') {
        return '';
    }

    return MAPS_contentUrl((int) $itemText);
}
'''
text = text[:start] + new_idtourl + '\n'
p.write_text(text)

# --- admin/marker_edit.php -------------------------------------------------
p = Path('admin/marker_edit.php')
text = p.read_text()
text = text.replace(
    "$markerMapBefore = 0;\nif ($requestMode === 'delete' && !empty($mkid)) {",
    "$markerMapBefore = 0;\nif (in_array($requestMode, array('save', 'delete'), true) && !empty($mkid)) {",
    1
)
text = text.replace(
    "                if ($markerMapBefore > 0) {\n                    updateMap($markerMapBefore);\n                }\n\t\t\t\t$msg = $LANG_MAPS_1['deletion_succes'];",
    "                MAPS_notifyMarkerDeleted($mkid, $markerMapBefore);\n\t\t\t\t$msg = $LANG_MAPS_1['deletion_succes'];",
    1
)
text = text.replace(
    "        DB_query($sql);\n\t\tupdateMap($_REQUEST['mid']);\n\t\tif ($_REQUEST['submission'] == 0 ) {",
    "        DB_query($sql);\n        $savedMarkerId = !empty($mkid) ? $mkid : (isset($newmkid) ? $newmkid : '');\n\t\tif ($_REQUEST['submission'] == 0 ) {",
    1
)
text = text.replace(
    "        } else {\n            $msg = $LANG_MAPS_1['save_success'];\n\t\t\t//Delete marker submission",
    "        } else {\n            MAPS_notifyMarkerSaved($savedMarkerId, (int) $_REQUEST['mid'], $markerMapBefore);\n            $msg = $LANG_MAPS_1['save_success'];\n\t\t\t//Delete marker submission",
    1
)
p.write_text(text)

# --- public_html/markers.php ----------------------------------------------
p = Path('public_html/markers.php')
text = p.read_text()
text = text.replace("'/markers.php?mode=show&mkid=' . $A['mkid'] . '&mid=' . $A['mid']", "'/index.php?mode=marker&mkid=' . $A['mkid']")
text = text.replace("'/markers.php?mode=show&mkid=' . $_REQUEST['mkid'] . '&mid=' . $_REQUEST['mid'] . '&msg='", "'/index.php?mode=marker&mkid=' . $_REQUEST['mkid'] . '&msg='")
text = text.replace(
    "        DB_query($sql);\n\t\tupdateMap($_REQUEST['mid']);",
    "        DB_query($sql);\n        if (!DB_error()) {\n            MAPS_notifyMarkerSaved($safeMkid, (int) $_REQUEST['mid']);\n        }",
    1
)
p.write_text(text)

# --- services.inc.php ------------------------------------------------------
p = Path('services.inc.php')
text = p.read_text()
text = text.replace(
    "'url' => $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($row['mkid']) . '&mid=' . (int)$row['mid'],",
    "'url' => MAPS_markerContentUrl($row['mkid']),",
    1
)
text = text.replace(
    "    updateMap((int)$row['mid']);\n    $updated=MAPS_serviceMarkerRow($markerId,false,false);",
    "    MAPS_notifyMarkerSaved($markerId, (int)$row['mid']);\n    $updated=MAPS_serviceMarkerRow($markerId,false,false);",
    1
)
p.write_text(text)

# --- admin/import_export.php ----------------------------------------------
p = Path('admin/import_export.php')
text = p.read_text()
text = text.replace(
    "    $inserted = 0;\n    $now = date('Y-m-d H:i:s');",
    "    $inserted = 0;\n    $insertedMarkerIds = array();\n    $now = date('Y-m-d H:i:s');",
    1
)
text = text.replace(
    "    foreach ($rows as $marker) {\n        $columns = array(",
    "    foreach ($rows as $marker) {\n        $importMarkerId = MAPS_importMarkerId();\n        $columns = array(",
    1
)
text = text.replace(
    "            \"'\" . MAPS_dbEscape(MAPS_importMarkerId()) . \"'\",",
    "            \"'\" . MAPS_dbEscape($importMarkerId) . \"'\",",
    1
)
text = text.replace(
    "        if (!DB_error()) {\n            $inserted++;\n        }",
    "        if (!DB_error()) {\n            $inserted++;\n            $insertedMarkerIds[] = $importMarkerId;\n        }",
    1
)
text = text.replace(
    "    if ($inserted > 0) {\n        updateMap($mid);\n    }",
    "    if ($inserted > 0) {\n        foreach ($insertedMarkerIds as $insertedMarkerId) {\n            MAPS_notifyMarkerSaved($insertedMarkerId, 0, 0, false);\n        }\n        updateMap($mid);\n    }",
    1
)
p.write_text(text)

# --- release notes ---------------------------------------------------------
p = Path('RELEASE-NOTES-1.5.10.md')
text = p.read_text()
section = r'''

## Generic content lifecycle notifications

- marker create/update now emits `PLG_itemSaved('marker:<mkid>', 'maps')`;
- marker deletion emits `PLG_itemDeleted('marker:<mkid>', 'maps')`;
- the parent map is also refreshed through its existing `PLG_itemSaved(<mid>, 'maps')` lifecycle;
- moving a marker between maps refreshes both the old and new parent maps;
- CSV imports emit one marker lifecycle event per inserted marker and one parent-map event per batch;
- `plugin_idtourl_maps()` resolves both map IDs and namespaced `marker:<mkid>` IDs, including after deletion;
- `plugin_getiteminfo_maps()` exposes public marker metadata for namespaced marker IDs;
- marker services now return the canonical `/maps/index.php?mode=marker&mkid=...` URL.

This keeps Maps independent from IndexNow: IndexNow, Hub, Hello and other listeners can consume native Geeklog lifecycle events and resolve canonical Maps URLs through the interoperability callbacks.
'''
if '## Generic content lifecycle notifications' not in text:
    p.write_text(text.rstrip() + section + '\n')
