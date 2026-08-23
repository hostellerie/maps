<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                        |
// +--------------------------------------------------------------------------+
// | integrations.php                                                         |
// |                                                                          |
// | Geeklog What’s New integration and usage statistics.                     |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

/**
 * Add Maps 1.5.7 configuration rows on existing installations.
 *
 * This bootstrap is intentionally idempotent. It is loaded before Geeklog
 * calls plugin_upgrade_maps(), so an installed 1.5.6 site receives the new
 * configuration rows before the existing upgrade routine records 1.5.7.
 * Existing values are never overwritten.
 *
 * @return bool
 */
function MAPS_ensure157Configuration()
{
    global $_MAPS_CONF;

    $defaults = array(
        'whatsnew_enabled' => 1,
        'whatsnew_interval' => 1209600,
        'whatsnew_limit' => 10,
        'stats_admin_enabled' => 1,
        'stats_public_enabled' => 1
    );

    $missing = false;
    foreach ($defaults as $name => $value) {
        if (!array_key_exists($name, $_MAPS_CONF)) {
            $_MAPS_CONF[$name] = $value;
            $missing = true;
        }
    }

    if (!$missing || !class_exists('config')) {
        return true;
    }

    $config = config::get_instance();
    if (!$config->group_exists('maps')) {
        return true;
    }

    $existing = method_exists($config, 'get_config')
        ? $config->get_config('maps') : array();
    if (!is_array($existing)) {
        $existing = array();
    }

    $rows = array(
        'fs_integrations' => array(null, 'fieldset', null, 0),
        'whatsnew_enabled' => array(1, 'select', 3, 10),
        'whatsnew_interval' => array(1209600, 'text', null, 20),
        'whatsnew_limit' => array(10, 'text', null, 30),
        'stats_admin_enabled' => array(1, 'select', 3, 40),
        'stats_public_enabled' => array(1, 'select', 3, 50)
    );

    foreach ($rows as $name => $def) {
        if (array_key_exists($name, $existing)) {
            continue;
        }

        $config->add(
            $name,
            $def[0],
            $def[1],
            0,
            3,
            $def[2],
            $def[3],
            true,
            'maps',
            0
        );
    }

    return true;
}

MAPS_ensure157Configuration();

/**
 * Advertise Maps support for Geeklog's What’s New block.
 *
 * @return array|bool
 */
function plugin_whatsnewsupported_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1, $LANG_WHATSNEW;

    if (empty($_MAPS_CONF['whatsnew_enabled'])) {
        return false;
    }

    $title = isset($LANG_MAPS_1['whatsnew_title'])
        ? $LANG_MAPS_1['whatsnew_title'] : $LANG_MAPS_1['plugin_name'];
    $interval = max(60, (int) $_MAPS_CONF['whatsnew_interval']);
    $byline = isset($LANG_WHATSNEW['new_last'])
        ? COM_formatTimeString($LANG_WHATSNEW['new_last'], $interval) : '';

    return array($title, $byline);
}

/**
 * Return recently modified visible maps for Geeklog's What’s New block.
 *
 * @return string
 */
function plugin_getwhatsnew_maps()
{
    global $_TABLES, $_MAPS_CONF, $LANG_MAPS_1;

    if (empty($_MAPS_CONF['whatsnew_enabled'])) {
        return '';
    }

    $interval = max(60, (int) $_MAPS_CONF['whatsnew_interval']);
    $limit = max(1, min(50, (int) $_MAPS_CONF['whatsnew_limit']));
    $cutoff = date('Y-m-d H:i:s', time() - $interval);
    $cutoffSql = function_exists('DB_escapeString')
        ? DB_escapeString($cutoff) : addslashes($cutoff);

    $sql = "SELECT mid,name,modified FROM {$_TABLES['maps_maps']} "
        . "WHERE active=1 AND hidden=0 AND modified>='{$cutoffSql}' "
        . COM_getPermSQL('AND', 0, 2)
        . " ORDER BY modified DESC";

    $result = DB_query($sql);
    $items = array();
    while (count($items) < $limit && ($row = DB_fetchArray($result))) {
        if (!is_array($row) || empty($row['mid'])) {
            continue;
        }

        $title = trim(html_entity_decode(
            stripslashes(isset($row['name']) ? $row['name'] : ''),
            ENT_QUOTES,
            'UTF-8'
        ));
        if ($title === '') {
            $title = '#' . (int) $row['mid'];
        }

        $url = $_MAPS_CONF['site_url'] . '/index.php?mode=map&mid=' . (int) $row['mid'];
        $items[] = COM_createLink(
            COM_truncate($title, 60, '...'),
            $url,
            array('title' => $title)
        ) . LB;
    }

    if (empty($items)) {
        return isset($LANG_MAPS_1['whatsnew_none'])
            ? $LANG_MAPS_1['whatsnew_none'] . '<br' . XHTML . '>' : '';
    }

    if (function_exists('PLG_getThemeItem')) {
        return COM_makeList($items, PLG_getThemeItem('core-css-list-new', 'core'));
    }

    return COM_makeList($items);
}

/**
 * Compute Maps usage statistics.
 *
 * Public statistics only include maps and markers visible to the current
 * visitor. Administration statistics include all stored rows.
 *
 * @param bool $public
 * @return array
 */
function MAPS_getStatistics($public = true)
{
    global $_TABLES;

    $stats = array(
        'maps' => 0,
        'map_views' => 0,
        'markers' => 0,
        'marker_views' => 0
    );

    if (!$public) {
        $row = DB_fetchArray(DB_query(
            "SELECT COUNT(*) AS total, COALESCE(SUM(hits),0) AS views "
            . "FROM {$_TABLES['maps_maps']}"
        ));
        if (is_array($row)) {
            $stats['maps'] = (int) $row['total'];
            $stats['map_views'] = (int) $row['views'];
        }

        $row = DB_fetchArray(DB_query(
            "SELECT COUNT(*) AS total, COALESCE(SUM(hits),0) AS views "
            . "FROM {$_TABLES['maps_markers']}"
        ));
        if (is_array($row)) {
            $stats['markers'] = (int) $row['total'];
            $stats['marker_views'] = (int) $row['views'];
        }

        return $stats;
    }

    $mapIds = array();
    $maps = DB_query(
        "SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 AND hidden=0"
        . COM_getPermSQL('AND', 0, 2)
    );
    while ($map = DB_fetchArray($maps)) {
        if (!is_array($map) || empty($map['mid'])) {
            continue;
        }

        $stats['maps']++;
        $stats['map_views'] += (int) $map['hits'];
        $mapIds[(int) $map['mid']] = true;
    }

    if (empty($mapIds)) {
        return $stats;
    }

    $markers = DB_query(
        "SELECT * FROM {$_TABLES['maps_markers']} "
        . "WHERE active=1 AND hidden=0 AND mid IN (" . implode(',', array_keys($mapIds)) . ")"
    );
    while ($marker = DB_fetchArray($markers)) {
        if (!is_array($marker) || !MAPS_checkMarkervalidity($marker)) {
            continue;
        }

        $stats['markers']++;
        $stats['marker_views'] += (int) $marker['hits'];
    }

    return $stats;
}

/**
 * Render a compact statistics summary.
 *
 * @param bool $public
 * @return string
 */
function MAPS_renderStatistics($public = true)
{
    global $_MAPS_CONF, $LANG_MAPS_1;

    $setting = $public ? 'stats_public_enabled' : 'stats_admin_enabled';
    if (empty($_MAPS_CONF[$setting])) {
        return '';
    }

    $stats = MAPS_getStatistics($public);
    $title = isset($LANG_MAPS_1['stats_title'])
        ? $LANG_MAPS_1['stats_title'] : 'Maps statistics';

    $labels = array(
        'maps' => isset($LANG_MAPS_1['stats_maps']) ? $LANG_MAPS_1['stats_maps'] : 'maps',
        'map_views' => isset($LANG_MAPS_1['stats_map_views']) ? $LANG_MAPS_1['stats_map_views'] : 'map views',
        'markers' => isset($LANG_MAPS_1['stats_markers']) ? $LANG_MAPS_1['stats_markers'] : 'markers',
        'marker_views' => isset($LANG_MAPS_1['stats_marker_views']) ? $LANG_MAPS_1['stats_marker_views'] : 'marker views'
    );

    $html = '<div class="maps-statistics" style="margin:12px 0;padding:10px;border:1px solid #ccc">';
    $html .= '<strong>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . ':</strong> ';
    $html .= number_format($stats['maps'], 0, '.', ' ') . ' '
        . htmlspecialchars($labels['maps'], ENT_QUOTES, 'UTF-8') . ' &middot; ';
    $html .= number_format($stats['map_views'], 0, '.', ' ') . ' '
        . htmlspecialchars($labels['map_views'], ENT_QUOTES, 'UTF-8') . ' &middot; ';
    $html .= number_format($stats['markers'], 0, '.', ' ') . ' '
        . htmlspecialchars($labels['markers'], ENT_QUOTES, 'UTF-8') . ' &middot; ';
    $html .= number_format($stats['marker_views'], 0, '.', ' ') . ' '
        . htmlspecialchars($labels['marker_views'], ENT_QUOTES, 'UTF-8');
    $html .= '</div>';

    return $html;
}
