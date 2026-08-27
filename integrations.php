<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                        |
// +--------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | integrations.php                                                         |
// |                                                                          |
// | Geeklog integration, What's New and usage statistics.                    |
// +--------------------------------------------------------------------------+

if (!defined('VERSION')) {
    die('This file can not be used on its own.');
}

if (!isset($LANG_confignames['maps']) || !is_array($LANG_confignames['maps'])) {
    $LANG_confignames['maps'] = array();
}
if (!isset($LANG_fs['maps']) || !is_array($LANG_fs['maps'])) {
    $LANG_fs['maps'] = array();
}
if (!isset($LANG_MAPS_1) || !is_array($LANG_MAPS_1)) {
    $LANG_MAPS_1 = array();
}

$maps157French = isset($_CONF['language'])
    && strpos(strtolower($_CONF['language']), 'french') === 0;

if ($maps157French) {
    $LANG_fs['maps']['fs_integrations'] = 'Intégrations et statistiques';
    $LANG_confignames['maps']['whatsnew_enabled'] = 'Afficher les cartes récentes dans le bloc Quoi de neuf';
    $LANG_confignames['maps']['whatsnew_interval'] = 'Période Quoi de neuf (secondes)';
    $LANG_confignames['maps']['whatsnew_limit'] = 'Nombre maximal de cartes dans Quoi de neuf';
    $LANG_confignames['maps']['stats_admin_enabled'] = 'Afficher les statistiques dans l’administration';
    $LANG_confignames['maps']['stats_public_enabled'] = 'Afficher les statistiques sur la page publique';
    $LANG_MAPS_1['whatsnew_title'] = 'Cartes récemment mises à jour';
    $LANG_MAPS_1['whatsnew_none'] = 'Aucune carte récemment mise à jour.';
    $LANG_MAPS_1['stats_title'] = 'Statistiques Maps';
    $LANG_MAPS_1['stats_map_title'] = 'Statistiques de cette carte';
    $LANG_MAPS_1['stats_maps'] = 'cartes';
    $LANG_MAPS_1['stats_map_views'] = 'vues des cartes';
    $LANG_MAPS_1['stats_markers'] = 'marqueurs';
    $LANG_MAPS_1['stats_marker_views'] = 'vues des marqueurs';
    $LANG_MAPS_1['stats_top_maps'] = 'Cartes les plus consultées';
    $LANG_MAPS_1['stats_no_views'] = 'Aucune consultation enregistrée.';
} else {
    $LANG_fs['maps']['fs_integrations'] = 'Integrations and statistics';
    $LANG_confignames['maps']['whatsnew_enabled'] = 'Show recently updated maps in What’s New';
    $LANG_confignames['maps']['whatsnew_interval'] = 'What’s New period (seconds)';
    $LANG_confignames['maps']['whatsnew_limit'] = 'Maximum maps in What’s New';
    $LANG_confignames['maps']['stats_admin_enabled'] = 'Show statistics in administration';
    $LANG_confignames['maps']['stats_public_enabled'] = 'Show statistics on the public page';
    $LANG_MAPS_1['whatsnew_title'] = 'Recently updated maps';
    $LANG_MAPS_1['whatsnew_none'] = 'No recently updated maps.';
    $LANG_MAPS_1['stats_title'] = 'Maps statistics';
    $LANG_MAPS_1['stats_map_title'] = 'Map statistics';
    $LANG_MAPS_1['stats_maps'] = 'maps';
    $LANG_MAPS_1['stats_map_views'] = 'map views';
    $LANG_MAPS_1['stats_markers'] = 'markers';
    $LANG_MAPS_1['stats_marker_views'] = 'marker views';
    $LANG_MAPS_1['stats_top_maps'] = 'Most viewed maps';
    $LANG_MAPS_1['stats_no_views'] = 'No views recorded.';
}

/**
 * Add Maps 1.5.7 configuration rows on existing installations.
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
 * Advertise Maps support for Geeklog's What's New block.
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
 * Return recently modified visible maps for Geeklog's What's New block.
 *
 * The HTML renderer deliberately consumes the same normalized permission-aware
 * query layer as Item Info so structured consumers and What's New cannot drift.
 *
 * @return string
 */
function plugin_getwhatsnew_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1;

    if (empty($_MAPS_CONF['whatsnew_enabled'])) {
        return '';
    }

    $interval = max(60, (int) $_MAPS_CONF['whatsnew_interval']);
    $limit = max(1, min(50, (int) $_MAPS_CONF['whatsnew_limit']));
    $items = MAPS_contentQuery('*', 0, array(
        'since' => time() - $interval,
        'limit' => $limit,
        'order' => 'modified-desc'
    ));

    $links = array();
    foreach ($items as $item) {
        $title = isset($item['title']) ? $item['title'] : '';
        $url = isset($item['url']) ? $item['url'] : '';
        if ($title === '' || $url === '') {
            continue;
        }
        $links[] = COM_createLink(
            COM_truncate($title, 60, '...'),
            $url,
            array('title' => $title)
        ) . LB;
    }

    if (empty($links)) {
        return isset($LANG_MAPS_1['whatsnew_none'])
            ? $LANG_MAPS_1['whatsnew_none'] . '<br' . XHTML . '>' : '';
    }

    if (function_exists('PLG_getThemeItem')) {
        return COM_makeList($links, PLG_getThemeItem('core-css-list-new', 'core'));
    }

    return COM_makeList($links);
}

/**
 * Compute Maps usage statistics.
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
 * Compute statistics for one map.
 *
 * @param int  $mid
 * @param bool $public
 * @return array
 */
function MAPS_getMapStatistics($mid, $public = true)
{
    global $_TABLES;

    $mid = (int) $mid;
    $stats = array('map_views' => 0, 'markers' => 0, 'marker_views' => 0);
    if ($mid <= 0) {
        return $stats;
    }

    if ($public && empty(MAPS_contentQuery($mid, 0, array('limit' => 1)))) {
        return $stats;
    }

    $stats['map_views'] = (int) DB_getItem($_TABLES['maps_maps'], 'hits', 'mid=' . $mid);
    $where = 'mid=' . $mid;
    if ($public) {
        $where .= ' AND active=1 AND hidden=0';
    }

    $result = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE " . $where);
    while ($marker = DB_fetchArray($result)) {
        if (!is_array($marker)) {
            continue;
        }
        if ($public && !MAPS_checkMarkervalidity($marker)) {
            continue;
        }
        $stats['markers']++;
        $stats['marker_views'] += (int) $marker['hits'];
    }

    return $stats;
}

/**
 * Render statistics as responsive semantic cards.
 *
 * @param array  $stats
 * @param string $title
 * @return string
 */
function MAPS_renderStatisticCards($stats, $title)
{
    global $LANG_MAPS_1;

    $labels = array(
        'maps' => isset($LANG_MAPS_1['stats_maps']) ? $LANG_MAPS_1['stats_maps'] : 'maps',
        'map_views' => isset($LANG_MAPS_1['stats_map_views']) ? $LANG_MAPS_1['stats_map_views'] : 'map views',
        'markers' => isset($LANG_MAPS_1['stats_markers']) ? $LANG_MAPS_1['stats_markers'] : 'markers',
        'marker_views' => isset($LANG_MAPS_1['stats_marker_views']) ? $LANG_MAPS_1['stats_marker_views'] : 'marker views'
    );

    $html = '<section class="maps-statistics" aria-label="'
        . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">';
    $html .= '<h2 class="maps-statistics-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>';
    $html .= '<div class="maps-statistics-cards">';
    foreach ($stats as $key => $value) {
        if (!isset($labels[$key])) {
            continue;
        }
        $html .= '<div class="maps-stat-card">';
        $html .= '<strong class="maps-stat-value">' . COM_numberFormat((int) $value) . '</strong>';
        $html .= '<span class="maps-stat-label">' . htmlspecialchars($labels[$key], ENT_QUOTES, 'UTF-8') . '</span>';
        $html .= '</div>';
    }
    $html .= '</div></section>';

    return $html;
}

/**
 * Render global statistics.
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

    $title = isset($LANG_MAPS_1['stats_title']) ? $LANG_MAPS_1['stats_title'] : 'Maps statistics';
    return MAPS_renderStatisticCards(MAPS_getStatistics($public), $title);
}

/**
 * Render statistics for one public map.
 *
 * @param int  $mid
 * @param bool $public
 * @return string
 */
function MAPS_renderMapStatistics($mid, $public = true)
{
    global $_MAPS_CONF, $LANG_MAPS_1;

    $setting = $public ? 'stats_public_enabled' : 'stats_admin_enabled';
    if (empty($_MAPS_CONF[$setting])) {
        return '';
    }

    $title = isset($LANG_MAPS_1['stats_map_title']) ? $LANG_MAPS_1['stats_map_title'] : 'Map statistics';
    return MAPS_renderStatisticCards(MAPS_getMapStatistics($mid, $public), $title);
}

/**
 * Native Geeklog statistics summary callback.
 *
 * @return array
 */
function plugin_statssummary_maps()
{
    global $LANG_MAPS_1;

    $stats = MAPS_getStatistics(true);
    $label = isset($LANG_MAPS_1['plugin_name']) ? $LANG_MAPS_1['plugin_name'] : 'Maps';
    $value = COM_numberFormat($stats['maps']) . ' (' . COM_numberFormat($stats['map_views']) . ')';

    return array($label, $value);
}

/**
 * Native Geeklog detailed statistics callback for /stats.php.
 *
 * @return string
 */
function plugin_showstats_maps()
{
    global $_TABLES, $LANG_MAPS_1;

    $title = isset($LANG_MAPS_1['stats_top_maps']) ? $LANG_MAPS_1['stats_top_maps'] : 'Most viewed maps';
    $none = isset($LANG_MAPS_1['stats_no_views']) ? $LANG_MAPS_1['stats_no_views'] : 'No views recorded.';
    $result = DB_query(
        "SELECT mid,name,hits FROM {$_TABLES['maps_maps']} "
        . "WHERE active=1 AND hidden=0 AND hits>0"
        . COM_getPermSQL('AND', 0, 2)
        . " ORDER BY hits DESC, mid DESC LIMIT 10"
    );

    $rows = array();
    while ($row = DB_fetchArray($result)) {
        if (!is_array($row) || empty($row['mid'])) {
            continue;
        }
        $name = MAPS_decodeStoredText(isset($row['name']) ? $row['name'] : '');
        $rows[] = '<li>' . COM_createLink(
            htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            MAPS_contentUrl((int) $row['mid'])
        ) . ' <span class="maps-stats-hits">(' . COM_numberFormat((int) $row['hits']) . ')</span></li>';
    }

    $html = COM_startBlock($title);
    $html .= empty($rows) ? htmlspecialchars($none, ENT_QUOTES, 'UTF-8') : '<ol class="maps-stats-list">' . implode('', $rows) . '</ol>';
    $html .= COM_endBlock();

    return $html;
}
