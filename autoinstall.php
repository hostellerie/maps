<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                         |
// +---------------------------------------------------------------------------+
// | autoinstall.php                                                           |
// +---------------------------------------------------------------------------+

/**
 * Return plugin installation metadata.
 *
 * @param string $pi_name
 * @return array
 */
function plugin_autoinstall_maps($pi_name)
{
    $pi_name = 'maps';
    $pi_display_name = 'Maps';
    $pi_admin = $pi_display_name . ' Admin';

    $info = array(
        'pi_name' => $pi_name,
        'pi_display_name' => $pi_display_name,
        'pi_version' => '1.5.0',
        'pi_gl_version' => '2.1.1',
        'pi_homepage' => 'https://github.com/Geeklog-Plugins/maps'
    );

    $groups = array(
        $pi_admin => 'Users in this group can administer the ' . $pi_display_name . ' plugin'
    );

    $features = array(
        $pi_name . '.admin' => 'Full access to the ' . $pi_display_name . ' plugin'
    );

    $mappings = array(
        $pi_name . '.admin' => array($pi_admin)
    );

    $tables = array(
        'maps_maps',
        'maps_geo',
        'maps_markers',
        'maps_submission',
        'maps_markers_cat',
        'maps_markers_fields',
        'maps_markers_values',
        'maps_overlays',
        'maps_map_overlay',
        'maps_map_icons',
        'maps_overlays_groups'
    );

    return array(
        'info' => $info,
        'groups' => $groups,
        'features' => $features,
        'mappings' => $mappings,
        'tables' => $tables
    );
}

/**
 * Check runtime compatibility before installation or upgrade.
 *
 * Official support target for Maps 1.5.0:
 * - Geeklog 2.1.1 through 2.2.2
 * - PHP 5.6 through 8.1
 * - MySQL-compatible DBMS as supported by this plugin's SQL installer
 *
 * @param string $pi_name
 * @return bool
 */
function plugin_compatible_with_this_version_maps($pi_name)
{
    global $_CONF, $_DB_dbms;

    if (version_compare(PHP_VERSION, '5.6.0', '<')
        || version_compare(PHP_VERSION, '8.2.0', '>=')) {
        return false;
    }

    if (defined('VERSION')) {
        $glVersion = preg_replace('/[^0-9.].*$/', '', VERSION);
        if ($glVersion !== '') {
            if (version_compare($glVersion, '2.1.1', '<')
                || version_compare($glVersion, '2.2.2', '>')) {
                return false;
            }
        }
    }

    $dbFile = $_CONF['path'] . 'plugins/' . $pi_name . '/sql/'
        . $_DB_dbms . '_install.php';

    return file_exists($dbFile);
}

/**
 * Load plugin configuration during installation.
 *
 * @param string $pi_name
 * @return bool
 */
function plugin_load_configuration_maps($pi_name)
{
    global $_CONF;

    $base_path = $_CONF['path'] . 'plugins/' . $pi_name . '/';

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $base_path . 'install_defaults.php';

    return plugin_initconfig_maps();
}

/**
 * Post-install hook.
 *
 * Maps 1.4 sent installation telemetry by email. Maps 1.5 deliberately does
 * not transmit the site URL, site name, version or any other installation data.
 *
 * @param string $pi_name
 * @return bool
 */
function plugin_postinstall_maps($pi_name)
{
    COM_errorLog('Maps plugin installation completed.', 1);
    return true;
}
