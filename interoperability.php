<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                        |
// +--------------------------------------------------------------------------+
// | interoperability.php                                                     |
// |                                                                          |
// | Structured content interoperability for Geeklog consumers.               |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

/**
 * Return the canonical public URL for a map.
 *
 * @param int $mid
 * @return string
 */
function MAPS_contentUrl($mid)
{
    global $_MAPS_CONF;

    $mid = (int) $mid;
    if ($mid <= 0) {
        return '';
    }

    return rtrim($_MAPS_CONF['site_url'], '/') . '/index.php?mode=map&mid=' . $mid;
}

/**
 * Normalize a requested Item Info field list.
 *
 * @param string|array $what
 * @return array
 */
function MAPS_contentRequestedFields($what)
{
    $allowed = array(
        'id', 'title', 'url', 'description', 'excerpt',
        'date-created', 'date-modified', 'uid', 'author', 'type', 'subtype'
    );

    if (is_array($what)) {
        $requested = $what;
    } else {
        $what = trim((string) $what);
        if ($what === '' || $what === '*' || strtolower($what) === 'all') {
            return $allowed;
        }
        $requested = explode(',', $what);
    }

    $fields = array();
    foreach ($requested as $field) {
        $field = strtolower(trim((string) $field));
        if ($field !== '' && in_array($field, $allowed, true) && !in_array($field, $fields, true)) {
            $fields[] = $field;
        }
    }

    return $fields;
}

/**
 * Convert a stored map row to normalized interoperability metadata.
 *
 * Dates are exposed as Unix timestamps so consumers can compare them directly
 * with the timestamp accepted by the collection `since` option.
 *
 * @param array $row
 * @return array
 */
function MAPS_contentNormalizeRow($row)
{
    if (!is_array($row) || empty($row['mid'])) {
        return array();
    }

    $mid = (int) $row['mid'];
    $title = MAPS_decodeStoredText(isset($row['name']) ? $row['name'] : '');
    $description = MAPS_decodeStoredText(isset($row['description']) ? $row['description'] : '');
    $title = trim($title);
    $description = trim($description);

    if ($title === '') {
        $title = '#' . $mid;
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
        'id' => $mid,
        'title' => $title,
        'url' => MAPS_contentUrl($mid),
        'description' => $description,
        'excerpt' => $excerpt,
        'date-created' => ($created === false ? 0 : $created),
        'date-modified' => ($modified === false ? 0 : $modified),
        'uid' => $uid,
        'author' => ($uid > 0 && function_exists('COM_getDisplayName')) ? COM_getDisplayName($uid) : '',
        'type' => 'maps',
        'subtype' => 'map'
    );
}

/**
 * Return public/accessible Maps content rows through one permission-aware query.
 *
 * Supported options:
 * - since: Unix timestamp (or parseable date string)
 * - limit: 1..100, default 20 for collections
 * - order: modified-desc or created-desc
 *
 * @param int|string $id
 * @param int        $uid
 * @param array      $options
 * @return array
 */
function MAPS_contentQuery($id = '*', $uid = 0, $options = array())
{
    global $_TABLES;

    if (!is_array($options)) {
        $options = array();
    }

    $collection = ((string) $id === '*');
    $limit = isset($options['limit']) ? (int) $options['limit'] : ($collection ? 20 : 1);
    $limit = max(1, min(100, $limit));

    $order = isset($options['order']) ? strtolower(trim((string) $options['order'])) : 'modified-desc';
    $orderSql = ($order === 'created-desc') ? 'created DESC, mid DESC' : 'modified DESC, mid DESC';

    $where = array('active=1', 'hidden=0');
    if (!$collection) {
        $mid = (int) $id;
        if ($mid <= 0) {
            return array();
        }
        $where[] = 'mid=' . $mid;
    }

    if (isset($options['since']) && $options['since'] !== '' && $options['since'] !== null) {
        $since = $options['since'];
        if (is_numeric($since)) {
            $since = (int) $since;
        } else {
            $since = strtotime((string) $since);
        }
        if ($since !== false && $since > 0) {
            $sinceSql = MAPS_dbEscape(date('Y-m-d H:i:s', $since));
            $where[] = "(created>='{$sinceSql}' OR modified>='{$sinceSql}')";
        }
    }

    $sql = "SELECT mid,name,description,created,modified,owner_id "
        . "FROM {$_TABLES['maps_maps']} WHERE " . implode(' AND ', $where)
        . COM_getPermSQL('AND', (int) $uid, 2)
        . ' ORDER BY ' . $orderSql
        . ' LIMIT ' . $limit;

    $result = DB_query($sql);
    if (!$result) {
        return array();
    }

    $items = array();
    while ($row = DB_fetchArray($result)) {
        $item = MAPS_contentNormalizeRow($row);
        if (!empty($item)) {
            $items[] = $item;
        }
    }

    return $items;
}

/**
 * Filter normalized metadata to the fields requested through Item Info.
 *
 * @param array $item
 * @param array $fields
 * @return array
 */
function MAPS_contentSelectFields($item, $fields, $numeric = false)
{
    $selected = array();
    foreach ($fields as $field) {
        if (array_key_exists($field, $item)) {
            if ($numeric) {
                $selected[] = $item[$field];
            } else {
                $selected[$field] = $item[$field];
            }
        }
    }
    return $selected;
}

/**
 * Geeklog structured Item Info callback.
 *
 * `$id = '*'` returns a collection of normalized records. A concrete map ID
 * returns one normalized record. Permissions and visibility remain owned by
 * Maps rather than by consumers such as Hub, Hello, IndexNow or Sitemap.
 *
 * @param int|string   $id
 * @param string|array $what
 * @param int          $uid
 * @param array        $options
 * @return array
 */
function plugin_getiteminfo_maps($id, $what, $uid = 0, $options = array())
{
    $fields = MAPS_contentRequestedFields($what);
    if (empty($fields)) {
        return array();
    }

    $items = MAPS_contentQuery($id, $uid, $options);
    if ((string) $id !== '*') {
        if (empty($items)) {
            return array();
        }
        // Native Geeklog consumers (including XMLSitemap) expect a concrete
        // PLG_getItemInfo() response to be numerically indexed in the exact
        // order requested through $what. Collection records remain associative
        // for the Maps interoperability contract.
        return MAPS_contentSelectFields($items[0], $fields, true);
    }

    $result = array();
    foreach ($items as $item) {
        $result[] = MAPS_contentSelectFields($item, $fields);
    }
    return $result;
}

/**
 * Resolve a Maps item ID to its canonical URL on subtype-aware Geeklog cores.
 *
 * @param string|null $sub_type
 * @param int|string  $item_id
 * @return string
 */
function plugin_idtourl_maps($sub_type, $item_id)
{
    $subType = strtolower(trim((string) $sub_type));
    if ($subType !== '' && $subType !== 'map' && $subType !== 'maps') {
        return '';
    }

    return MAPS_contentUrl((int) $item_id);
}
