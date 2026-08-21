<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                         |
// +---------------------------------------------------------------------------+
// | install_defaults.php                                                      |
// +---------------------------------------------------------------------------+

if (strpos(strtolower(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''), 'install_defaults.php') !== false) {
    die('This file can not be used on its own!');
}

global $_MAPS_DEFAULT;

$_MAPS_DEFAULT = array();
$_MAPS_DEFAULT['pi_name'] = 'maps';

/* Main settings */
$_MAPS_DEFAULT['maps_folder'] = 'maps';
$_MAPS_DEFAULT['maps_login_required'] = 0;
$_MAPS_DEFAULT['hide_maps_menu'] = 0;
$_MAPS_DEFAULT['marker_submission'] = 1;
$_MAPS_DEFAULT['marker_edition'] = 1;
$_MAPS_DEFAULT['submit_login_required'] = 1;

/* Google Maps Platform */
$_MAPS_DEFAULT['autofill_coord'] = 1;
$_MAPS_DEFAULT['google_api_key'] = '';
$_MAPS_DEFAULT['google_server_api_key'] = '';
$_MAPS_DEFAULT['google_map_id'] = '';
$_MAPS_DEFAULT['google_language'] = '';
$_MAPS_DEFAULT['google_region'] = '';
$_MAPS_DEFAULT['url_geocode'] = 'https://maps.googleapis.com/maps/api/geocode/json';

/* Permissions */
$_MAPS_DEFAULT['default_permissions'] = array(3, 3, 2, 2);

/* Global map */
$_MAPS_DEFAULT['users_map'] = 1;
$_MAPS_DEFAULT['global_map'] = 1;
$_MAPS_DEFAULT['global_type'] = 'ROADMAP';
$_MAPS_DEFAULT['global_zoom'] = '2';
$_MAPS_DEFAULT['global_width'] = '100%';
$_MAPS_DEFAULT['global_height'] = '600px';

/* Profile map */
$_MAPS_DEFAULT['display_geo_profile'] = 1;
$_MAPS_DEFAULT['map_type_profile'] = 'ROADMAP';
$_MAPS_DEFAULT['map_width_profile'] = '100%';
$_MAPS_DEFAULT['map_height_profile'] = '400px';
$_MAPS_DEFAULT['show_directions_profile'] = 0;
$_MAPS_DEFAULT['zoom_profile'] = 10;

/* Geo autotag */
$_MAPS_DEFAULT['map_type_geotag'] = 'ROADMAP';
$_MAPS_DEFAULT['map_zoom_geotag'] = '10';
$_MAPS_DEFAULT['map_width_geotag'] = '100%';
$_MAPS_DEFAULT['map_height_geotag'] = '400px';
$_MAPS_DEFAULT['show_directions_geo'] = 0;

/* Map defaults */
$_MAPS_DEFAULT['map_type'] = 'ROADMAP';
$_MAPS_DEFAULT['map_zoom'] = '6';
$_MAPS_DEFAULT['map_width'] = '100%';
$_MAPS_DEFAULT['map_height'] = '600px';
$_MAPS_DEFAULT['map_active'] = 1;
$_MAPS_DEFAULT['map_hidden'] = 0;
$_MAPS_DEFAULT['free_markers'] = 1;
$_MAPS_DEFAULT['paid_markers'] = 1;
$_MAPS_DEFAULT['use_cluster'] = 1;
$_MAPS_DEFAULT['display_events_map'] = 1;
$_MAPS_DEFAULT['map_main_header'] = '';
$_MAPS_DEFAULT['map_main_footer'] = '';

/* Marker defaults */
$_MAPS_DEFAULT['marker_active'] = 1;
$_MAPS_DEFAULT['marker_hidden'] = 0;
$_MAPS_DEFAULT['marker_payed'] = 0;
$_MAPS_DEFAULT['marker_validity'] = 0;
$_MAPS_DEFAULT['star_primary_color'] = '#FFFF00';
$_MAPS_DEFAULT['star_stroke_color'] = '#333333';
$_MAPS_DEFAULT['label_color'] = 0;
$_MAPS_DEFAULT['detail_zoom'] = '8';

$_MAPS_DEFAULT['street'] = 1;
$_MAPS_DEFAULT['code'] = 1;
$_MAPS_DEFAULT['city'] = 1;
$_MAPS_DEFAULT['state'] = 1;
$_MAPS_DEFAULT['country'] = 1;
$_MAPS_DEFAULT['tel'] = 1;
$_MAPS_DEFAULT['fax'] = 1;
$_MAPS_DEFAULT['web'] = 1;

for ($i = 1; $i <= 10; $i++) {
    $_MAPS_DEFAULT['item_' . $i] = 'Ressource #' . $i;
}
$_MAPS_DEFAULT['infos_label'] = 'Infos';

/**
 * Initialize Maps configuration.
 *
 * The config API calls used here are supported by Geeklog 2.1.1 through 2.2.2.
 *
 * @return bool
 */
function plugin_initconfig_maps()
{
    global $_MAPS_DEFAULT;

    $c = config::get_instance();
    if ($c->group_exists('maps')) {
        return true;
    }

    $group = $_MAPS_DEFAULT['pi_name'];

    $c->add('sg_main', null, 'subgroup', 0, 0, null, 0, true, $group);
    $c->add('fs_main', null, 'fieldset', 0, 0, null, 0, true, $group);
    $c->add('maps_folder', $_MAPS_DEFAULT['maps_folder'], 'text', 0, 0, 0, 10, true, $group);
    $c->add('maps_login_required', $_MAPS_DEFAULT['maps_login_required'], 'select', 0, 0, 3, 20, true, $group);
    $c->add('hide_maps_menu', $_MAPS_DEFAULT['hide_maps_menu'], 'select', 0, 0, 3, 30, true, $group);
    $c->add('marker_submission', $_MAPS_DEFAULT['marker_submission'], 'select', 0, 0, 3, 40, true, $group);
    $c->add('submit_login_required', $_MAPS_DEFAULT['submit_login_required'], 'select', 0, 0, 3, 50, true, $group);
    $c->add('marker_edition', $_MAPS_DEFAULT['marker_edition'], 'select', 0, 0, 3, 60, true, $group);

    $c->add('fs_google', null, 'fieldset', 0, 1, null, 0, true, $group);
    $c->add('autofill_coord', $_MAPS_DEFAULT['autofill_coord'], 'select', 0, 1, 3, 100, true, $group);
    $c->add('google_api_key', $_MAPS_DEFAULT['google_api_key'], 'text', 0, 1, 0, 110, true, $group);
    $c->add('google_server_api_key', $_MAPS_DEFAULT['google_server_api_key'], 'text', 0, 1, 0, 120, true, $group);
    $c->add('google_map_id', $_MAPS_DEFAULT['google_map_id'], 'text', 0, 1, 0, 130, true, $group);
    $c->add('google_language', $_MAPS_DEFAULT['google_language'], 'text', 0, 1, 0, 140, true, $group);
    $c->add('google_region', $_MAPS_DEFAULT['google_region'], 'text', 0, 1, 0, 150, true, $group);
    $c->add('url_geocode', $_MAPS_DEFAULT['url_geocode'], 'text', 0, 1, 0, 160, true, $group);

    $c->add('fs_permissions', null, 'fieldset', 0, 2, null, 0, true, $group);
    $c->add('default_permissions', $_MAPS_DEFAULT['default_permissions'], '@select', 0, 2, 12, 200, true, $group);

    $c->add('sg_display', null, 'subgroup', 1, 0, null, 0, true, $group);
    $c->add('fs_display', null, 'fieldset', 1, 0, null, 0, true, $group);
    $c->add('map_main_header', $_MAPS_DEFAULT['map_main_header'], 'text', 1, 0, 0, 10, true, $group);
    $c->add('map_main_footer', $_MAPS_DEFAULT['map_main_footer'], 'text', 1, 0, 0, 20, true, $group);
    $c->add('use_cluster', $_MAPS_DEFAULT['use_cluster'], 'select', 1, 0, 3, 30, true, $group);
    $c->add('display_events_map', $_MAPS_DEFAULT['display_events_map'], 'select', 1, 0, 3, 40, true, $group);

    $c->add('fs_global_map', null, 'fieldset', 1, 1, null, 0, true, $group);
    $c->add('users_map', $_MAPS_DEFAULT['users_map'], 'select', 1, 1, 3, 10, true, $group);
    $c->add('global_map', $_MAPS_DEFAULT['global_map'], 'select', 1, 1, 3, 20, true, $group);
    $c->add('global_type', $_MAPS_DEFAULT['global_type'], 'select', 1, 1, 20, 30, true, $group);
    $c->add('global_width', $_MAPS_DEFAULT['global_width'], 'text', 1, 1, 0, 40, true, $group);
    $c->add('global_height', $_MAPS_DEFAULT['global_height'], 'text', 1, 1, 0, 50, true, $group);
    $c->add('global_zoom', $_MAPS_DEFAULT['global_zoom'], 'text', 1, 1, 0, 60, true, $group);

    $c->add('fs_display_profile', null, 'fieldset', 1, 2, null, 0, true, $group);
    $c->add('display_geo_profile', $_MAPS_DEFAULT['display_geo_profile'], 'select', 1, 2, 3, 10, true, $group);
    $c->add('map_type_profile', $_MAPS_DEFAULT['map_type_profile'], 'select', 1, 2, 20, 20, true, $group);
    $c->add('map_width_profile', $_MAPS_DEFAULT['map_width_profile'], 'text', 1, 2, 0, 30, true, $group);
    $c->add('map_height_profile', $_MAPS_DEFAULT['map_height_profile'], 'text', 1, 2, 0, 40, true, $group);
    $c->add('zoom_profile', $_MAPS_DEFAULT['zoom_profile'], 'text', 1, 2, 0, 50, true, $group);
    $c->add('show_directions_profile', $_MAPS_DEFAULT['show_directions_profile'], 'select', 1, 2, 3, 60, true, $group);

    $c->add('fs_display_geo', null, 'fieldset', 1, 3, null, 0, true, $group);
    $c->add('map_type_geotag', $_MAPS_DEFAULT['map_type_geotag'], 'select', 1, 3, 20, 10, true, $group);
    $c->add('map_width_geotag', $_MAPS_DEFAULT['map_width_geotag'], 'text', 1, 3, 0, 20, true, $group);
    $c->add('map_height_geotag', $_MAPS_DEFAULT['map_height_geotag'], 'text', 1, 3, 0, 30, true, $group);
    $c->add('map_zoom_geotag', $_MAPS_DEFAULT['map_zoom_geotag'], 'text', 1, 3, 0, 40, true, $group);
    $c->add('show_directions_geo', $_MAPS_DEFAULT['show_directions_geo'], 'select', 1, 3, 3, 50, true, $group);

    $c->add('fs_map_defaults', null, 'fieldset', 1, 4, null, 0, true, $group);
    $c->add('map_type', $_MAPS_DEFAULT['map_type'], 'select', 1, 4, 20, 10, true, $group);
    $c->add('map_width', $_MAPS_DEFAULT['map_width'], 'text', 1, 4, 0, 20, true, $group);
    $c->add('map_height', $_MAPS_DEFAULT['map_height'], 'text', 1, 4, 0, 30, true, $group);
    $c->add('map_zoom', $_MAPS_DEFAULT['map_zoom'], 'text', 1, 4, 0, 40, true, $group);
    $c->add('map_active', $_MAPS_DEFAULT['map_active'], 'select', 1, 4, 3, 50, true, $group);
    $c->add('map_hidden', $_MAPS_DEFAULT['map_hidden'], 'select', 1, 4, 3, 60, true, $group);
    $c->add('free_markers', $_MAPS_DEFAULT['free_markers'], 'select', 1, 4, 3, 70, true, $group);
    $c->add('paid_markers', $_MAPS_DEFAULT['paid_markers'], 'select', 1, 4, 3, 80, true, $group);

    $c->add('fs_marker_defaults', null, 'fieldset', 1, 5, null, 0, true, $group);
    $c->add('marker_active', $_MAPS_DEFAULT['marker_active'], 'select', 1, 5, 3, 10, true, $group);
    $c->add('marker_hidden', $_MAPS_DEFAULT['marker_hidden'], 'select', 1, 5, 3, 20, true, $group);
    $c->add('marker_payed', $_MAPS_DEFAULT['marker_payed'], 'select', 1, 5, 3, 30, true, $group);
    $c->add('marker_validity', $_MAPS_DEFAULT['marker_validity'], 'select', 1, 5, 3, 40, true, $group);
    $c->add('star_primary_color', $_MAPS_DEFAULT['star_primary_color'], 'text', 1, 5, 0, 50, true, $group);
    $c->add('star_stroke_color', $_MAPS_DEFAULT['star_stroke_color'], 'text', 1, 5, 0, 60, true, $group);
    $c->add('label_color', $_MAPS_DEFAULT['label_color'], 'select', 1, 5, 0, 70, true, $group);
    $c->add('detail_zoom', $_MAPS_DEFAULT['detail_zoom'], 'text', 1, 5, 0, 80, true, $group);

    $c->add('fs_marker_fields', null, 'fieldset', 1, 6, null, 0, true, $group);
    $order = 10;
    foreach (array('street', 'code', 'city', 'state', 'country', 'tel', 'fax', 'web') as $key) {
        $c->add($key, $_MAPS_DEFAULT[$key], 'select', 1, 6, 3, $order, true, $group);
        $order += 10;
    }
    for ($i = 1; $i <= 10; $i++) {
        $key = 'item_' . $i;
        $c->add($key, $_MAPS_DEFAULT[$key], 'text', 1, 6, 0, $order, true, $group);
        $order += 10;
    }
    $c->add('infos_label', $_MAPS_DEFAULT['infos_label'], 'text', 1, 6, 0, $order, true, $group);

    return true;
}
