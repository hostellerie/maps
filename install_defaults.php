<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.6                                                         |
// +---------------------------------------------------------------------------+
// | install_defaults.php                                                      |
// +---------------------------------------------------------------------------+

if (strpos(strtolower(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''), 'install_defaults.php') !== false) {
    die('This file can not be used on its own!');
}

global $_MAPS_DEFAULT;

$_MAPS_DEFAULT = array();
$_MAPS_DEFAULT['pi_name'] = 'maps';

/* General */
$_MAPS_DEFAULT['maps_login_required'] = 0;
$_MAPS_DEFAULT['hide_maps_menu'] = 0;
$_MAPS_DEFAULT['marker_submission'] = 1;
$_MAPS_DEFAULT['marker_edition'] = 1;
$_MAPS_DEFAULT['submit_login_required'] = 1;
$_MAPS_DEFAULT['default_permissions'] = array(3, 3, 2, 2);

/* Image uploads */
$_MAPS_DEFAULT['max_image_width'] = 2000;
$_MAPS_DEFAULT['max_image_height'] = 2000;
$_MAPS_DEFAULT['max_image_size'] = 4194304;

/* Google Maps Platform */
$_MAPS_DEFAULT['autofill_coord'] = 1;
$_MAPS_DEFAULT['google_api_key'] = '';
$_MAPS_DEFAULT['google_server_api_key'] = '';
$_MAPS_DEFAULT['google_map_id'] = '';
$_MAPS_DEFAULT['google_language'] = '';
$_MAPS_DEFAULT['google_region'] = '';
$_MAPS_DEFAULT['url_geocode'] = 'https://maps.googleapis.com/maps/api/geocode/json';

/* Maps display */
$_MAPS_DEFAULT['map_main_header'] = '';
$_MAPS_DEFAULT['map_main_footer'] = '';
$_MAPS_DEFAULT['use_cluster'] = 1;
$_MAPS_DEFAULT['display_events_map'] = 1;

/* Global/users map */
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

/* Defaults for newly created maps */
$_MAPS_DEFAULT['map_type'] = 'ROADMAP';
$_MAPS_DEFAULT['map_zoom'] = '6';
$_MAPS_DEFAULT['map_width'] = '100%';
$_MAPS_DEFAULT['map_height'] = '600px';
$_MAPS_DEFAULT['map_active'] = 1;
$_MAPS_DEFAULT['map_hidden'] = 0;
$_MAPS_DEFAULT['free_markers'] = 1;
$_MAPS_DEFAULT['paid_markers'] = 1;
$_MAPS_DEFAULT['map_primary_color'] = '#666666';
$_MAPS_DEFAULT['map_stroke_color'] = '#333333';
$_MAPS_DEFAULT['map_label'] = '';
$_MAPS_DEFAULT['map_label_color'] = 0;

/* Event map */
$_MAPS_DEFAULT['events_map_zoom'] = 8;
$_MAPS_DEFAULT['events_map_height'] = '300px';

/* Marker defaults */
$_MAPS_DEFAULT['marker_active'] = 1;
$_MAPS_DEFAULT['marker_hidden'] = 0;
$_MAPS_DEFAULT['marker_payed'] = 0;
$_MAPS_DEFAULT['marker_validity'] = 0;
$_MAPS_DEFAULT['star_primary_color'] = '#FFFF00';
$_MAPS_DEFAULT['star_stroke_color'] = '#333333';
$_MAPS_DEFAULT['label_color'] = 0;

/* Marker editor/detail/popup */
$_MAPS_DEFAULT['marker_editor_zoom'] = 10;
$_MAPS_DEFAULT['marker_editor_type'] = 'ROADMAP';
$_MAPS_DEFAULT['marker_editor_width'] = '100%';
$_MAPS_DEFAULT['marker_editor_height'] = '400px';
$_MAPS_DEFAULT['detail_zoom'] = '8';
$_MAPS_DEFAULT['detail_width'] = '100%';
$_MAPS_DEFAULT['detail_height'] = '300px';
$_MAPS_DEFAULT['popup_width'] = '250px';
$_MAPS_DEFAULT['popup_height'] = '150px';

/* Marker fields */
$_MAPS_DEFAULT['street'] = 1;
$_MAPS_DEFAULT['code'] = 1;
$_MAPS_DEFAULT['city'] = 1;
$_MAPS_DEFAULT['state'] = 1;
$_MAPS_DEFAULT['country'] = 1;
$_MAPS_DEFAULT['tel'] = 1;
$_MAPS_DEFAULT['fax'] = 1;
$_MAPS_DEFAULT['web'] = 1;

for ($i = 1; $i <= 10; $i++) {
    $_MAPS_DEFAULT['item_' . $i] = 'Custom field ' . $i;
}

/**
 * Return the complete Maps 1.5.8 configuration presentation definition.
 *
 * Each entry is: value, type, subgroup, fieldset, selectionArray, sort_order, tab.
 * Geeklog renders entries of type 'tab' as the visible configuration tabs.
 *
 * @return array
 */
function MAPS_configDefinition155()
{
    global $_MAPS_DEFAULT;

    $rows = array(
        /* Tab 0: General */
        'sg_main' => array(null, 'subgroup', 0, 0, null, 0, 0),
        'tab_general' => array(null, 'tab', 0, 0, null, 0, 0),
        'fs_main' => array(null, 'fieldset', 0, 0, null, 0, 0),
        'maps_login_required' => array($_MAPS_DEFAULT['maps_login_required'], 'select', 0, 0, 3, 10, 0),
        'hide_maps_menu' => array($_MAPS_DEFAULT['hide_maps_menu'], 'select', 0, 0, 3, 20, 0),
        'marker_submission' => array($_MAPS_DEFAULT['marker_submission'], 'select', 0, 0, 3, 30, 0),
        'submit_login_required' => array($_MAPS_DEFAULT['submit_login_required'], 'select', 0, 0, 3, 40, 0),
        'marker_edition' => array($_MAPS_DEFAULT['marker_edition'], 'select', 0, 0, 3, 50, 0),
        'fs_permissions' => array(null, 'fieldset', 0, 1, null, 0, 0),
        'default_permissions' => array($_MAPS_DEFAULT['default_permissions'], '@select', 0, 1, 12, 10, 0),
        'fs_uploads' => array(null, 'fieldset', 0, 2, null, 0, 0),
        'max_image_width' => array($_MAPS_DEFAULT['max_image_width'], 'text', 0, 2, 0, 10, 0),
        'max_image_height' => array($_MAPS_DEFAULT['max_image_height'], 'text', 0, 2, 0, 20, 0),
        'max_image_size' => array($_MAPS_DEFAULT['max_image_size'], 'text', 0, 2, 0, 30, 0),

        /* Tab 1: Google Maps */
        'tab_google' => array(null, 'tab', 0, 0, null, 0, 1),
        'fs_google' => array(null, 'fieldset', 0, 0, null, 0, 1),
        'autofill_coord' => array($_MAPS_DEFAULT['autofill_coord'], 'select', 0, 0, 3, 10, 1),
        'google_api_key' => array($_MAPS_DEFAULT['google_api_key'], 'text', 0, 0, 0, 20, 1),
        'google_server_api_key' => array($_MAPS_DEFAULT['google_server_api_key'], 'text', 0, 0, 0, 30, 1),
        'google_map_id' => array($_MAPS_DEFAULT['google_map_id'], 'text', 0, 0, 0, 40, 1),
        'google_language' => array($_MAPS_DEFAULT['google_language'], 'text', 0, 0, 0, 50, 1),
        'google_region' => array($_MAPS_DEFAULT['google_region'], 'text', 0, 0, 0, 60, 1),
        'url_geocode' => array($_MAPS_DEFAULT['url_geocode'], 'text', 0, 0, 0, 70, 1),

        /* Tab 2: Maps */
        'tab_maps' => array(null, 'tab', 0, 0, null, 0, 2),
        'fs_display' => array(null, 'fieldset', 0, 0, null, 0, 2),
        'map_main_header' => array($_MAPS_DEFAULT['map_main_header'], 'text', 0, 0, 0, 10, 2),
        'map_main_footer' => array($_MAPS_DEFAULT['map_main_footer'], 'text', 0, 0, 0, 20, 2),
        'use_cluster' => array($_MAPS_DEFAULT['use_cluster'], 'select', 0, 0, 3, 30, 2),
        'display_events_map' => array($_MAPS_DEFAULT['display_events_map'], 'select', 0, 0, 3, 40, 2),
        'fs_global_map' => array(null, 'fieldset', 0, 1, null, 0, 2),
        'users_map' => array($_MAPS_DEFAULT['users_map'], 'select', 0, 1, 3, 10, 2),
        'global_map' => array($_MAPS_DEFAULT['global_map'], 'select', 0, 1, 3, 20, 2),
        'global_type' => array($_MAPS_DEFAULT['global_type'], 'select', 0, 1, 20, 30, 2),
        'global_width' => array($_MAPS_DEFAULT['global_width'], 'text', 0, 1, 0, 40, 2),
        'global_height' => array($_MAPS_DEFAULT['global_height'], 'text', 0, 1, 0, 50, 2),
        'global_zoom' => array($_MAPS_DEFAULT['global_zoom'], 'text', 0, 1, 0, 60, 2),
        'fs_display_profile' => array(null, 'fieldset', 0, 2, null, 0, 2),
        'display_geo_profile' => array($_MAPS_DEFAULT['display_geo_profile'], 'select', 0, 2, 3, 10, 2),
        'map_type_profile' => array($_MAPS_DEFAULT['map_type_profile'], 'select', 0, 2, 20, 20, 2),
        'map_width_profile' => array($_MAPS_DEFAULT['map_width_profile'], 'text', 0, 2, 0, 30, 2),
        'map_height_profile' => array($_MAPS_DEFAULT['map_height_profile'], 'text', 0, 2, 0, 40, 2),
        'zoom_profile' => array($_MAPS_DEFAULT['zoom_profile'], 'text', 0, 2, 0, 50, 2),
        'show_directions_profile' => array($_MAPS_DEFAULT['show_directions_profile'], 'select', 0, 2, 3, 60, 2),
        'fs_display_geo' => array(null, 'fieldset', 0, 3, null, 0, 2),
        'map_type_geotag' => array($_MAPS_DEFAULT['map_type_geotag'], 'select', 0, 3, 20, 10, 2),
        'map_width_geotag' => array($_MAPS_DEFAULT['map_width_geotag'], 'text', 0, 3, 0, 20, 2),
        'map_height_geotag' => array($_MAPS_DEFAULT['map_height_geotag'], 'text', 0, 3, 0, 30, 2),
        'map_zoom_geotag' => array($_MAPS_DEFAULT['map_zoom_geotag'], 'text', 0, 3, 0, 40, 2),
        'show_directions_geo' => array($_MAPS_DEFAULT['show_directions_geo'], 'select', 0, 3, 3, 50, 2),
        'fs_map_defaults' => array(null, 'fieldset', 0, 4, null, 0, 2),
        'map_type' => array($_MAPS_DEFAULT['map_type'], 'select', 0, 4, 20, 10, 2),
        'map_width' => array($_MAPS_DEFAULT['map_width'], 'text', 0, 4, 0, 20, 2),
        'map_height' => array($_MAPS_DEFAULT['map_height'], 'text', 0, 4, 0, 30, 2),
        'map_zoom' => array($_MAPS_DEFAULT['map_zoom'], 'text', 0, 4, 0, 40, 2),
        'map_active' => array($_MAPS_DEFAULT['map_active'], 'select', 0, 4, 3, 50, 2),
        'map_hidden' => array($_MAPS_DEFAULT['map_hidden'], 'select', 0, 4, 3, 60, 2),
        'free_markers' => array($_MAPS_DEFAULT['free_markers'], 'select', 0, 4, 3, 70, 2),
        'paid_markers' => array($_MAPS_DEFAULT['paid_markers'], 'select', 0, 4, 3, 80, 2),
        'map_primary_color' => array($_MAPS_DEFAULT['map_primary_color'], 'text', 0, 4, 0, 90, 2),
        'map_stroke_color' => array($_MAPS_DEFAULT['map_stroke_color'], 'text', 0, 4, 0, 100, 2),
        'map_label' => array($_MAPS_DEFAULT['map_label'], 'text', 0, 4, 0, 110, 2),
        'map_label_color' => array($_MAPS_DEFAULT['map_label_color'], 'select', 0, 4, 30, 120, 2),
        'fs_events_map' => array(null, 'fieldset', 0, 5, null, 0, 2),
        'events_map_zoom' => array($_MAPS_DEFAULT['events_map_zoom'], 'text', 0, 5, 0, 10, 2),
        'events_map_height' => array($_MAPS_DEFAULT['events_map_height'], 'text', 0, 5, 0, 20, 2),

        /* Tab 3: Markers */
        'tab_markers' => array(null, 'tab', 0, 0, null, 0, 3),
        'fs_marker_defaults' => array(null, 'fieldset', 0, 0, null, 0, 3),
        'marker_active' => array($_MAPS_DEFAULT['marker_active'], 'select', 0, 0, 3, 10, 3),
        'marker_hidden' => array($_MAPS_DEFAULT['marker_hidden'], 'select', 0, 0, 3, 20, 3),
        'marker_payed' => array($_MAPS_DEFAULT['marker_payed'], 'select', 0, 0, 3, 30, 3),
        'marker_validity' => array($_MAPS_DEFAULT['marker_validity'], 'select', 0, 0, 3, 40, 3),
        'star_primary_color' => array($_MAPS_DEFAULT['star_primary_color'], 'text', 0, 0, 0, 50, 3),
        'star_stroke_color' => array($_MAPS_DEFAULT['star_stroke_color'], 'text', 0, 0, 0, 60, 3),
        'label_color' => array($_MAPS_DEFAULT['label_color'], 'select', 0, 0, 30, 70, 3),
        'fs_marker_editor' => array(null, 'fieldset', 0, 1, null, 0, 3),
        'marker_editor_type' => array($_MAPS_DEFAULT['marker_editor_type'], 'select', 0, 1, 20, 10, 3),
        'marker_editor_zoom' => array($_MAPS_DEFAULT['marker_editor_zoom'], 'text', 0, 1, 0, 20, 3),
        'marker_editor_width' => array($_MAPS_DEFAULT['marker_editor_width'], 'text', 0, 1, 0, 30, 3),
        'marker_editor_height' => array($_MAPS_DEFAULT['marker_editor_height'], 'text', 0, 1, 0, 40, 3),
        'fs_marker_detail' => array(null, 'fieldset', 0, 2, null, 0, 3),
        'detail_width' => array($_MAPS_DEFAULT['detail_width'], 'text', 0, 2, 0, 10, 3),
        'detail_height' => array($_MAPS_DEFAULT['detail_height'], 'text', 0, 2, 0, 20, 3),
        'detail_zoom' => array($_MAPS_DEFAULT['detail_zoom'], 'text', 0, 2, 0, 30, 3),
        'fs_marker_popup' => array(null, 'fieldset', 0, 3, null, 0, 3),
        'popup_width' => array($_MAPS_DEFAULT['popup_width'], 'text', 0, 3, 0, 10, 3),
        'popup_height' => array($_MAPS_DEFAULT['popup_height'], 'text', 0, 3, 0, 20, 3),

        /* Tab 4: Marker fields */
        'tab_fields' => array(null, 'tab', 0, 0, null, 0, 4),
        'fs_marker_fields' => array(null, 'fieldset', 0, 0, null, 0, 4)
    );

    $order = 10;
    foreach (array('street', 'code', 'city', 'state', 'country', 'tel', 'fax', 'web') as $name) {
        $rows[$name] = array($_MAPS_DEFAULT[$name], 'select', 0, 0, 3, $order, 4);
        $order += 10;
    }
    for ($i = 1; $i <= 10; $i++) {
        $name = 'item_' . $i;
        $rows[$name] = array($_MAPS_DEFAULT[$name], 'text', 0, 0, 0, $order, 4);
        $order += 10;
    }

    return $rows;
}

/**
 * Backward-compatible alias used by the 1.5.4 step when upgrading directly
 * from an older Maps release. The following 1.5.6 step then enforces tab
 * metadata in the database.
 *
 * @return array
 */
function MAPS_configDefinition154()
{
    return MAPS_configDefinition155();
}

/**
 * Initialize Maps configuration.
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
    foreach (MAPS_configDefinition155() as $name => $def) {
        $c->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group, $def[6]);
    }

    return true;
}
