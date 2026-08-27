<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                        |
// +--------------------------------------------------------------------------+
// | distribution.php                                                         |
// |                                                                          |
// | Native Geeklog distribution and discovery callbacks.                     |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

/**
 * Translate a Geeklog syndication limit into Maps collection options.
 *
 * Geeklog feed limits may be an item count (for example "10") or an hour
 * window (for example "24h"). Maps keeps the common convention and bounds
 * item-count queries so a feed request cannot become unbounded accidentally.
 *
 * @param mixed $limit
 * @return array
 */
function MAPS_feedCollectionOptions($limit)
{
    $options = array('limit' => 10, 'order' => 'modified-desc');
    $limit = trim((string) $limit);

    if ($limit === '') {
        return $options;
    }

    if (substr($limit, -1) === 'h') {
        $hours = (int) substr($limit, 0, -1);
        if ($hours > 0) {
            $options['since'] = time() - ($hours * 3600);
            $options['limit'] = 100;
        }
        return $options;
    }

    if (ctype_digit($limit)) {
        $options['limit'] = max(1, min(100, (int) $limit));
    }

    return $options;
}

/**
 * Expose Maps as a source in Geeklog Content Syndication.
 *
 * @return array
 */
function plugin_getfeednames_maps()
{
    global $LANG_MAPS_1;

    $name = isset($LANG_MAPS_1['plugin_name']) ? $LANG_MAPS_1['plugin_name'] : 'Maps';

    return array(
        array('id' => 'all', 'name' => $name)
    );
}

/**
 * Supply Maps entries to Geeklog's RSS/Atom generator.
 *
 * Geeklog's dispatcher also supplies the feed type and version. Older plugin
 * examples often omit them, but accepting them lets Maps expose extension tags
 * correctly while remaining compatible with Geeklog 2.1.1 through 2.2.2.
 *
 * @param int|string $feed
 * @param string     $link
 * @param string     $update
 * @param string     $feedType
 * @param string     $feedVersion
 * @return array
 */
function plugin_getfeedcontent_maps($feed, &$link, &$update, $feedType = '', $feedVersion = '')
{
    global $_TABLES, $_MAPS_CONF;

    $link = rtrim($_MAPS_CONF['site_url'], '/') . '/';
    $update = '';

    $feedId = (int) $feed;
    if ($feedId <= 0) {
        return array();
    }

    $result = DB_query(
        "SELECT topic,limits,content_length FROM {$_TABLES['syndication']} WHERE fid=" . $feedId
    );
    $syndication = DB_fetchArray($result);
    if (!is_array($syndication)) {
        return array();
    }

    $source = isset($syndication['topic']) ? (string) $syndication['topic'] : 'all';
    if ($source !== '' && $source !== 'all') {
        return array();
    }

    $options = MAPS_feedCollectionOptions(isset($syndication['limits']) ? $syndication['limits'] : '');
    $items = MAPS_contentQuery('*', 1, $options);
    $contentLength = isset($syndication['content_length']) ? (int) $syndication['content_length'] : 0;
    $entries = array();
    $ids = array();

    foreach ($items as $item) {
        if (empty($item['id']) || empty($item['url'])) {
            continue;
        }

        $summary = isset($item['description']) ? $item['description'] : '';
        $summary = PLG_replaceTags($summary);
        if ($contentLength > 1 && function_exists('COM_truncateHTML')) {
            $summary = COM_truncateHTML($summary, $contentLength, ' ...');
        }

        $entry = array(
            'title' => isset($item['title']) ? $item['title'] : '',
            'summary' => $summary,
            'text' => $summary,
            'link' => $item['url'],
            'uid' => isset($item['uid']) ? (int) $item['uid'] : 0,
            'author' => isset($item['author']) ? $item['author'] : '',
            'date' => isset($item['date-modified']) ? (int) $item['date-modified'] : 0,
            'format' => 'html'
        );

        if (function_exists('PLG_getFeedElementExtensions')) {
            $entry['extensions'] = PLG_getFeedElementExtensions(
                'maps',
                (string) $item['id'],
                (string) $feedType,
                (string) $feedVersion,
                'all',
                $feedId
            );
        }

        $entries[] = $entry;
        $ids[] = (string) $item['id'];
    }

    $update = implode(',', $ids);

    return $entries;
}

/**
 * Tell Geeklog whether an existing Maps feed still represents current data.
 *
 * @param int|string $feed
 * @param string     $topic
 * @param string     $updateData
 * @param string     $limit
 * @param string     $updatedType
 * @param string     $updatedTopic
 * @param string     $updatedId
 * @return bool true when current, false when regeneration is required
 */
function plugin_feedupdatecheck_maps(
    $feed,
    $topic,
    $updateData,
    $limit,
    $updatedType = '',
    $updatedTopic = '',
    $updatedId = ''
) {
    if ($updatedType === 'maps' && $updatedId !== '') {
        return false;
    }

    $items = MAPS_contentQuery('*', 1, MAPS_feedCollectionOptions($limit));
    $ids = array();
    foreach ($items as $item) {
        if (isset($item['id'])) {
            $ids[] = (string) $item['id'];
        }
    }

    return implode(',', $ids) === (string) $updateData;
}

/**
 * Native Geeklog XML Sitemap collector.
 *
 * General metadata remains owned by plugin_getiteminfo_maps(); this callback
 * supplies the optimized sitemap-specific representation requested by core.
 *
 * @param int $uid   User ID, normally 1 for anonymous sitemap generation
 * @param int $limit Maximum number of items, 0 for no explicit limit
 * @return array
 */
function plugin_collectSitemapItems_maps($uid = 1, $limit = 0)
{
    global $_TABLES;

    $uid = (int) $uid;
    $limit = (int) $limit;

    $sql = "SELECT mid,modified FROM {$_TABLES['maps_maps']} "
        . "WHERE active=1 AND hidden=0"
        . COM_getPermSQL('AND', $uid, 2)
        . " ORDER BY modified DESC, mid DESC";
    if ($limit > 0) {
        $sql .= ' LIMIT ' . $limit;
    }

    $result = DB_query($sql);
    $items = array();
    while ($row = DB_fetchArray($result)) {
        if (!is_array($row) || empty($row['mid'])) {
            continue;
        }
        $modified = isset($row['modified']) ? strtotime($row['modified']) : false;
        $items[] = array(
            'url' => MAPS_contentUrl((int) $row['mid']),
            'date-modified' => ($modified === false ? 0 : $modified)
        );
    }

    // Marker pages are indexable content too. Use the same canonical URL as
    // public links and search results, and require both marker and parent-map
    // visibility/permissions so the sitemap never advertises an inaccessible URL.
    $markerSql = "SELECT mk.mkid,mk.modified FROM {$_TABLES['maps_markers']} mk "
        . "INNER JOIN {$_TABLES['maps_maps']} m ON m.mid=mk.mid "
        . "WHERE mk.active=1 AND mk.hidden=0 AND m.active=1 AND m.hidden=0"
        . COM_getPermSQL('AND', $uid, 2, 'mk')
        . COM_getPermSQL('AND', $uid, 2, 'm')
        . " ORDER BY mk.modified DESC, mk.mkid DESC";
    $markerResult = DB_query($markerSql);
    while ($marker = DB_fetchArray($markerResult)) {
        if (!is_array($marker) || !isset($marker['mkid']) || trim((string) $marker['mkid']) === '') {
            continue;
        }
        $modified = isset($marker['modified']) ? strtotime($marker['modified']) : false;
        $items[] = array(
            'url' => MAPS_markerContentUrl($marker['mkid']),
            'date-modified' => ($modified === false ? 0 : $modified)
        );
        if ($limit > 0 && count($items) >= $limit) {
            break;
        }
    }

    if ($limit > 0 && count($items) > $limit) {
        $items = array_slice($items, 0, $limit);
    }

    return $items;
}

/**
 * Native Geeklog related-items callback.
 *
 * Geeklog's current related-items contract is topic based. Maps therefore uses
 * the shared topic_assignments table when Maps topic assignments exist and
 * returns no invented relationship when none exists.
 *
 * @param array $tids Topic IDs
 * @param int   $max  Maximum number of items
 * @param int   $trim Maximum title length
 * @return array Unix timestamp => clickable related-item link
 */
function plugin_getrelateditems_maps($tids, $max, $trim)
{
    global $_TABLES;

    if (!is_array($tids) || empty($tids)) {
        return array();
    }

    $safeTids = array();
    foreach ($tids as $tid) {
        $tid = trim((string) $tid);
        if ($tid !== '') {
            $safeTids[] = "'" . MAPS_dbEscape($tid) . "'";
        }
    }
    if (empty($safeTids)) {
        return array();
    }

    $max = max(1, min(100, (int) $max));
    $trim = max(1, (int) $trim);

    $sql = "SELECT DISTINCT m.mid,m.name,m.modified "
        . "FROM {$_TABLES['maps_maps']} m "
        . "INNER JOIN {$_TABLES['topic_assignments']} ta "
        . "ON ta.type='maps' AND ta.id=m.mid "
        . "WHERE ta.tid IN (" . implode(',', $safeTids) . ") "
        . "AND m.active=1 AND m.hidden=0"
        . COM_getPermSQL('AND', 0, 2, 'm')
        . " ORDER BY m.modified DESC, m.mid DESC LIMIT " . $max;

    $result = DB_query($sql);
    $related = array();
    while ($row = DB_fetchArray($result)) {
        if (!is_array($row) || empty($row['mid'])) {
            continue;
        }
        $title = MAPS_decodeStoredText(isset($row['name']) ? $row['name'] : '');
        $title = COM_truncate($title, $trim, '...');
        $timestamp = isset($row['modified']) ? strtotime($row['modified']) : false;
        if ($timestamp === false || $timestamp <= 0) {
            $timestamp = time();
        }
        while (isset($related[$timestamp])) {
            $timestamp--;
        }
        $related[$timestamp] = COM_createLink(
            htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            MAPS_contentUrl((int) $row['mid'])
        );
    }

    return $related;
}
