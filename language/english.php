<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.4                                                           |
// +---------------------------------------------------------------------------+
// | english.php                                                               |
// |                                                                           |
// | English language file                                                     |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2014 by the following authors:                         |
// |                                                                           |
// | Authors: ::Ben                                                            |
// +---------------------------------------------------------------------------+
// | Created with the Geeklog Plugin Toolkit.                                  |
// +---------------------------------------------------------------------------+
// |                                                                           |
// | This program is free software; you can redistribute it and/or             |
// | modify it under the terms of the GNU General Public License               |
// | as published by the Free Software Foundation; either version 2            |
// | of the License, or (at your option) any later version.                    |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,           |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
// | GNU General Public License for more details.                              |
// |                                                                           |
// | You should have received a copy of the GNU General Public License         |
// | along with this program; if not, write to the Free Software Foundation,   |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.           |
// |                                                                           |
// +---------------------------------------------------------------------------+

/**
* @package Maps
*/

/**
* Import Geeklog plugin messages for reuse
*
* @global array $LANG32
*/
global $LANG32;

// +---------------------------------------------------------------------------+
// | Array Format:                                                             |
// | $LANGXX[YY]:  $LANG - variable name                                       |
// |               XX    - specific array name                                 |
// |               YY    - phrase id or number                                 |
// +---------------------------------------------------------------------------+

$LANG_MAPS_1 = array(
    'plugin_name'           => 'Maps',
    'plugin_conf'           => 'Plugin configuration',
    'map'                   => 'map',
    'need_google_api'       => 'No Google Maps browser API key is configured. Google maps cannot be displayed until this key is added.',
    'api_status_title' => 'Google Maps API status',
    'api_status_missing' => 'No Google Maps browser API key is configured.',
    'api_status_testing' => 'Testing the Google Maps JavaScript API…',
    'api_status_key' => 'Browser key',
    'api_status_ok' => 'Google Maps JavaScript API loaded successfully: the browser key is accepted for this page.',
    'api_status_auth' => 'Google Maps rejected the browser key or its configuration. Check the browser console for Google\'s exact error code, then verify HTTP referrer restrictions, enabled APIs and Google Cloud billing.',
    'api_status_load' => 'The Google Maps JavaScript API script could not be loaded. Check the network, CSP policy, content blockers and the browser console.',
    'api_status_timeout' => 'The Google Maps JavaScript API did not answer. Check the browser console and Network panel.',
    'admin_help_title'      => 'Getting started with Maps',
    'admin_help_intro'      => 'Maps lets you create multiple maps, add markers and, when needed, use custom icons or overlays.',
    'admin_help_google'     => 'Configure Google Maps',
    'admin_help_google_1'   => 'Open Google Cloud Console, create or select a project, and attach a billing account for production use.',
    'admin_help_google_2'   => 'Enable at least Maps JavaScript API. Also enable Geocoding API if you use automatic conversion of addresses to latitude/longitude.',
    'admin_help_google_3'   => 'Create an API key for browser use. Restrict it to authorized websites (HTTP referrers), for example https://www.example.com/*, then restrict this key to Maps JavaScript API.',
    'admin_help_google_4'   => 'For server-side geocoding, creating a second key is recommended. Restrict it to your server IP address and to Geocoding API only.',
    'admin_help_google_5'   => 'Copy the browser key into Google Maps API key and, if used, the server key into Google Maps server API key in the Geeklog Maps configuration.',
    'admin_help_security'   => 'Never leave a Google Maps key unrestricted in production. Separate browser and server keys reduce the risk of unauthorized use.',
    'admin_help_create'     => 'Create your first map',
    'admin_help_create_1'   => 'Click Create a new map, give it a name, and choose its center, zoom level and display type.',
    'admin_help_create_2'   => 'Save the map, then add markers from the Maps administration. Each marker can use an address or precise coordinates.',
    'admin_help_create_3'   => 'Icons and overlays are optional. Start with a simple map and a few markers to validate your Google Maps configuration.',
    'admin_help_trouble'    => 'If a map is greyed out or shows “For development purposes only”, check Google Cloud billing, enabled APIs and API-key restrictions.',
    'admin_help_official'   => 'Official Google Maps Platform documentation',
    'admin_help_geo_title'  => 'What does “Check user geolocation” do?',
    'admin_help_geo_intro'  => 'This command scans members who filled in the Location field in their profile and prepares coordinates for the users map.',
    'admin_help_geo_1'      => 'Maps sends each unresolved text location to Google Geocoding API, for example “Nantes, France”.',
    'admin_help_geo_2'      => "The returned latitude and longitude are cached in the Maps geocoding table. The member's Geeklog profile is not modified.",
    'admin_help_geo_3'      => 'This is mainly useful after installation, migration, or when many members added or changed their location. It requires Geocoding API and a key allowed for server-side geocoding.',
    'admin_help_overlays_title' => 'What are overlays for?',
    'admin_help_overlays_intro' => 'An overlay is a georeferenced image placed over the Google map between south-west and north-east coordinates. It adds visual information that is not part of the Google Maps base layer.',
    'admin_help_overlays_1' => 'Display a site, campsite, park, estate, building or festival plan on top of the real-world map.',
    'admin_help_overlays_2' => 'Overlay a historical, cadastral, geological or tourist map, or an old plan, for comparison.',
    'admin_help_overlays_3' => 'Show a thematic area such as an illustrated route, work zone, natural area, project footprint or other graphical information.',
    'admin_help_overlays_4' => 'Set minimum and maximum zoom levels so the overlay is only shown when it is useful.',
    'admin_help_overlays_how' => 'To create one: prepare a suitable image, open Overlays, enter its south-west and north-east bounds, then attach it to a map from the Overlays tab in the map editor. Overlays are optional: for a normal map with points, markers are enough.',
    'admin_help_concepts_title' => 'Maps concepts at a glance',
    'admin_help_concept_map' => 'Map',
    'admin_help_concept_map_text' => 'The main container: center, zoom, display type, dimensions, permissions and general options.',
    'admin_help_concept_marker' => 'Marker',
    'admin_help_concept_marker_text' => 'A geographical point placed on a map, with a name, description, address and optional additional information.',
    'admin_help_concept_icon' => 'Icon',
    'admin_help_concept_icon_text' => 'An optional image replacing the standard Google marker to distinguish categories of points.',
    'admin_help_concept_users' => 'Users map',
    'admin_help_concept_users_text' => 'A map generated from the Geeklog profile Location field. Coordinates are resolved and cached by the Maps geocoding system.',
    'admin_help_trouble_title' => 'Quick troubleshooting',
    'profile_title'         => 'Geolocalisation',
    'buy_marker'            => 'Buy a marker',
    'menu_label'            => 'Maps administration',
    'admin_home'            => 'Home', // In admin menu
    'user_home'             => 'All maps', //In user menu
    'maps'                  => 'Maps',
    'markers'               => 'Markers',
    'maps_label'            => 'Maps', // For user  menu
    'create_map'            => 'create a new map',
    'create_marker'         => 'create a new marker',
    'map_edit'              => 'Map edition:',
    'marker_edit'           => 'Marker edition:',
    'deletion_succes'       => 'Deletion successful',
    'deletion_fail'         => 'Deletion failed',
    'error'                 => 'Error',
    'save_fail'             => 'Save failed',
    'save_success'          => 'Save succeeded',
    'missing_field'         => 'Missing required field...',
    'geocoder'              => 'Geocoder',
    'geocoder_text'         => 'Enter an address, and then drag the marker to tweak the location. The latitude/longitude will appear in the infowindow after each geocode/drag.',
    'geocode_failed'         => 'The address could not be geocoded. Check the Google Maps API key, Geocoding API activation and address, then try again.',
    'go'                    => 'Go!',
    'name_label'            => 'Map Name: ',
    'marker_name_label'     => 'Marker Name: ',
    'description_label'     => 'Description:',
    'ok_button'             => 'Ok',
    'edit_button'           => 'Edit',
    'save_button'           => 'Save',
    'delete_button'         => 'Delete',
    'yes'                   => 'Yes',
    'no'                    => 'No',
    'required_field'        => 'Indicates required field',
    'address_label'         => 'Address: ',
    'message'               => 'Message',
    'general_settings'      => 'General settings',
    'map_width'             => 'Map width (% or px, mini 550px): ',
    'map_height'             => 'Map height (px only, mini 350px): ',
    'map_zoom'              => 'Map zoom (0-21): ',
    'map_type'              => 'Map type: ',
    'active'                => 'Map is active: ',
    'hidden'                => 'Map is hidden: ',
    'marker_active'         => 'Marker is active: ',
    'marker_hidden'         => 'Marker is hidden: ',
    'free_marker'           => 'Map accept free markers: ',
    'paid_marker'           => 'Map accept paid markers: ',
    'error_address_empty'   => 'Please enter a valid address first.',
    'error_invalid_address' => 'This address is invalid. Make sure to enter your street number and city as well?',
    'error_google_error'    => 'There was a problem processing your request, please try again.',
    'error_no_map_info'     => 'Sorry! Map information is not available for this address.',
    'need_directions'       => 'Need directions? Enter your address:',
    'get_directions'        => '  Get Directions  ',
    'maps_list'             => 'Maps list',
    'you_can'               => 'You can ',
    'user_maps_list'        => 'List of the maps recorded in our database :',
    'markers_list'          => 'Markers list',
    'no_map'                => 'There is no map in our database. You must create one to add markers',
    'no_map_user'           => 'Oups... There is no active map in our database.',
    'value_directions'      => 'e.g. number street name, city, country', // No quote here please
    'id'                    => 'ID',
    'name'                  => 'Name',
    'description'           => 'Description',
    'active_field'          => 'Active',
    'hidden_field'          => 'Hidden',
    'marker_count'          => 'Markers',
    'status_active'         => 'Active',
    'status_inactive'       => 'Inactive',
    'status_visible'        => 'Visible',
    'status_hidden'         => 'Hidden',
    'title_display'         => 'Display map page',
    'map_header_label'      => 'Facultative map header',
    'map_footer_label'      => 'Facultative map footer',
    'header_footer'         => 'Header and footer',
    'informations'          => 'Informations',
    'must_belong_to'        => 'To access this map you must belong to group:',
    'private_access'        => 'Private access',
    'marker_label'          => 'Marker',
    'primary_color_label'   => 'Primary color',
    'stroke_color_label'    => 'Stroke color',
    'label'                 => 'Label',
    'label_color'           => 'Label color',
    'black'                 => 'Black',
    'white'                 => 'White',
    'payed'                 => 'Payed marker:',
    'lat'                   => 'Latitude:',
    'lng'                   => 'Longitude:',
    'ressources_tab'        => 'Resources tab',
    'presentation'          => 'Presentation',
    'ressources'            => 'Resources',
    'presentation_tab'      => 'Presentation tab',
    'empty_ressources'      => 'Resources labels are empty. You need to set one at least to use resources. See the config aera. Thanks.',
    'empty_for_geo'         => 'Leave blank latitude and longitude if you need auto geolocalisation on the address value above.',
    'select_marker_map'     => 'Select the map on witch you want the marker appear.',
    'remark'                => 'Notes',
    'marker_created'        => 'Marker created on:',
    'map_created'           => 'Map created on:',
    'modified'              => 'Last modification:',
    'marker_validity'       => 'Use validity date:',
    'maps_empty'            => 'Please create a map first.',
    'from'                  => 'From:',
    'to'                    => 'To:',
    'date_issue'            => 'End validity is before start validity. Please check it now!',
    'max_char'              => 'maximum characters.',
    'street_label'          => 'Street:',
    'code_label'            => 'Postal code:',
    'city_label'            => 'City:',
    'state_label'           => 'State:',
    'country_label'         => 'Country:',
    'tel_label'             => 'Tel:',
    'fax_label'             => 'Additional contact:',
    'web_label'             => 'Web:',
    'not_use_see_config'    => 'Not use. See config',
    //global maps
    'global_map'            => 'Global Map',
    'info_global_map'       => 'This is all maps in one.',
    'users_map'             => 'Map of site users',
    'info_users_map'        => 'This is the map of site users. You can add yourself by setting your location in your profile.',
    //Submission
    'address'               => 'Address',
    'created'               => 'Date',
    'submit_marker'         => 'Submit a marker',
    'submit_marker_text'    => '<p><ol><li>Set the location marker<li>Fill in all the fields<li>Validate</ol></p>',
    'markers_submissions'   => 'Markers submissions',
    'submission_disabled'   => 'Submission queue disabled for markers',
    'go'                    => 'Show me this address',
    //date and hits
    'last_modification'     => 'Last modification:',
    'hits'                  => 'hits',
    //user marker
    'member'                => 'Member',
    'location'              => 'Location: ',
    'regdate'               => 'Member since: ',
    'about'                 => 'About',
    'my_markers'            => 'My markers',
    'payed_label'           => 'Payed',
    'from_label'            => 'Validity from',
    'to_label'              => 'Validity to',
    'no_marker'             => 'You do not have any marker or they are not yet been approve. If you think it is a mistake, you can try to contact the site admin.',
    'marker_detail'         => 'Marker detail',
    'admin_can'             => 'As a map admin you can',
    'create_map'            => 'create a new map',
    'set_user_geo'          => 'Set user geo',
    'set_geo_location'      => 'Ok system check and set all geolocations.',
    'records'               => 'records',
    'report'                => 'Report this marker',
    'report_subject'        => 'Report about marker ',
    'edit_marker_text'      => '<p><ol><li>Set the location marker<li>Fill in all the required fields<li>Then validate</ol></p>',
    'admin'                 => 'Admin',
    'category_label'        => 'Category:',
    'choose_category'       => '-- Choose category --',
    'categories'            => 'Categories',
    'categories_list'       => 'Categories list',
    'cat_edit'              => 'Category edition:',
    'cat_name_label'        => 'Category name:',
    'create_cat'            => 'create a new category',
    'field_list'            => 'Fields list',
    'addfield'              => 'Add a field',
    'field_name'            => 'Field name',
    'field_order'           => 'Order',
    'field_autotag'         => 'Autotag',
    'field_rights'          => 'Permissions',
    'field_edit'            => 'Edit',
    'valid'                 => 'Valid',
    'editing_field'         => 'Editing field',
    'category'              => 'Category',
    'map_label'             => 'Map',
    'colon'                 => ':', //Add space before and after if needed
    'view_map'              => 'View map',
    'view_markers'          => 'Display Markers list',
    'code'                  => 'Postal code',
    'city'                  => 'City',
    'viewing_markers'       => 'Display the list of the markers',
    'details'               => 'Details',
    'view_details'          => 'View details',
    'print'                 => 'Print',
	'to_complete'           => 'To complete',
	'autotag_desc_maps'     => '[maps: xx zoom:ZZ location] - Displays the map with id=XX. Options are zoom level (between 0 to 21) and center the map on location.',
	'autotag_desc_geo'      => '[geo: map width:XX height:YY zoom:ZZ location] - Displays a map center on the location (street, city, country). Options are width and height in pixels (e.g 400px) and zoom level (between 0 to 21).',
	'autotag_desc_marker'   => '[marker: xx] - Displays the marker with id=XX',
	//v1.1
	'marker_customisation'  => 'Marker customisation',
	'mk_default'            => 'Use marker default',
	'overlays'              => 'Overlays',
	'overlays_list'         => 'List of the overlays',
	'create_overlay'        => 'Create an new overlay',
	'edit_overlay_text'     => 'Edit overlay:',
	'overlay_edit'          => 'Overlay edition:',
	'overlay_name_label'    => 'Overlay name:',
	'overlay_presentation'  => 'Overlays are objects on the map that are tied to latitude/longitude coordinates, so they move when you drag or zoom the map. Overlays reflect objects that you "add" to the map to designate points, lines, or areas. Here you can add an image as an overlay',
	'overlay_active'        => 'This overlay is active:',
	'zoom_min_label'        => 'Zoom min:',
	'zoom_max_label'        => 'Zoom max:',
	'image_message'         => 'Select an image from your hard drive.',
	'image_replace'         => 'Uploading a new image will replace this one:',
	'image'                 => 'Image',
	'sw_lat'                => 'SW Latitude:',
    'sw_lng'                => 'SW Longitude:',
	'ne_lat'                => 'NE Latitude:',
    'ne_lng'                => 'NE Longitude:',
	'overlay_not_writable'  => 'Overlays folder is not writable: Please create this folder first and make it writable before using this feature.',
	'map_tab'               => 'Map',
	'overlays_tab'          => 'Overlays',
	'add_overlay'           => 'Add overlay',
	'remove_overlay'        => 'Remove overlay',
	'overlay_label'         => 'Overlay',
	'import_export'         => 'Import/Export',
	'import'                => 'Import',
	'export'                => 'Export',
	'select_file'           => 'Select a .csv file',
	'import_message'        => 'Select the map you want to add markers to, select the csv file to import your markers from your hard drive, select the delimiter for the datas and select the fields you want to import.',
	'markers_added'         => 'Markers added on map:',
	'export_message'        => 'Choose the map you want to export the markers, select the delimiter for the datas and select the fields you want to export.',
	'no_marker_to_export'   => 'Sorry, but there is no marker to export from this map.',
	'icons'                 => 'Icons',
	'icons_not_writable'    => 'Icons folder is not writable: Please create this folder first and make it writable before using this feature.',
	'icons_list'            => 'Icons list',
	'create_icon'           => 'create a new icon',
	'icon_edit'             => 'Icon edit',
	'icon_presentation'     => 'Here you can upload a new icon for use with markers', 
	'icon_name_label'       => 'Icon name',
	'xmarkers'              => 'markers',
	'1marker'               => 'marker',
	'choose_icon'           => 'You can choose an icon for this marker. The priority icons are on colors.',
	'no_icon'               => 'No icon',
	'no_custom_icons'        => 'No custom icon is registered yet.',
	'manage_icons'           => 'Manage icons',
	'separator'             => 'Choose delimiter',
	'markers_to_add'        => 'Please check all field/value pairs and confirm you want to add all the markers below on the map:',
	'choose_fields_import'  => 'Choose fields to import',
	'choose_fields_export'  => 'Choose fields to export',
	'checkall'              => 'Check all',
	'order'                 => 'Order',
	'move'                  => 'Move',
	'name_missing'          => 'A least a name is missing. Please check your csv file.',
	'need_address'          => 'We need at least an adresse or coordinates to build a marker. Please check your csv file something is missing.',
	'manage_groups'         => 'Manage groups of overlays',
	'create_group'          => 'Create a new group of overlays',
	'group_edit'            => 'Group edition:',
	'group_overlay_presentation' => 'Here you can choose or edit the name of your group of overlays',
	'group_overlay_name_label'   => 'Name of the group',
	'group_label'           => 'Group (optional)',
	'choose_group'          => 'Choose a group',
	'group'                 => 'Group',
	
	//v1.3
	'geo_fail'              => 'The address you entered does not seem to be valid',
	'on_map'                => 'On the map',
	'read_more'             => 'Read more',
	'from_map'              => 'On map',
	'show_hide_overlays'    => 'Show / hide overlays',
	'fields_presentation'   => 'Edit an existing category to add or edit a field.',
	'overlays_added'        => 'Overlays present on this map',
	'overlays_to_add'       => 'Overlays you can add to this map',
	'marker_modification'   => 'Marker modification',
	'from_owner'            => 'from',
	'marker_limited'        => 'Sorry but access to this marker is limited...',
	'events_map'            => 'Map of the next events',
	'info_events_map'       => '',
	'from_cal'              => 'From',
	'to_cal'                => 'to',
	'on_cal'                => 'On',
    //v1.4
    'admin_menu_maps' => 'Maps',
    'admin_menu_markers' => 'Markers',
    'admin_menu_icons' => 'Icons',
    'admin_menu_overlays' => 'Overlays',
    'admin_menu_import_export' => 'Import/Export',
    'admin_menu_geocoder' => 'Geocoder',
    'admin_menu_geolocation' => 'Geolocation',
    'admin_menu_configuration' => 'Configuration',
    'section_location' => 'Location',
    'section_content_contact' => 'Content and contact',
    'section_appearance' => 'Appearance',
    'section_publication' => 'Publication',
    'section_resources' => 'Resources',
    'section_ownership' => 'Owner',
    'section_permissions' => 'Permissions',
    'delete_confirm' => 'Permanently delete this marker?',
    'marker_not_found' => 'Marker not found or you do not have permission to view it.',
    'delete_map_confirm' => 'Permanently delete this map?',
    'technical_coordinates' => 'Technical coordinates',
    'configuration'         => 'Configuration',
    // Maps 1.5.6 map editor
    'map_section_display' => 'Display',
    'map_section_center' => 'Center & zoom',
    'map_section_markers' => 'Markers',
    'map_section_advanced' => 'Advanced options',
    'map_center_search' => 'Search an address',
    'map_center_search_button' => 'Locate',
    'map_center_use_button' => 'Use displayed center',
    'map_center_help' => 'Search an address, click the map or drag the marker to choose the map center precisely.',
    'latitude_label' => 'Latitude',
    'longitude_label' => 'Longitude',
    'map_center_marker' => 'Map center',

);

$LANG_MAPS_MESSAGE = array(
    'message'               => 'Message from the system',
    'add_new_field'         => 'Your new field was successfully created',
    'save_field'            => 'Your field was successfully saved',
    'delete_field'          => 'Your field was successfully deleted'
);

$LANG_MAPS_EMAIL = array(
    'hello_admin'           => 'Hello admin,',
    'new_marker'            => 'You\'ve got a new marker waiting for approval.',
    'name'                  => 'Name:',
    'on_map'                => 'On map:',
    'submissions'           => 'Submissions : ',
    'marker_submissions'    => 'Marker Submissions',
	'marker_modification'   => 'Marker modification',
	'description'           => 'Description:',
);

// Messages for the plugin upgrade
$PLG_maps_MESSAGE3002 = $LANG32[9]; // "requires a newer version of Geeklog"

$PLG_maps_MESSAGE1  = "Thank-you for submitting a marker to {$_CONF['site_name']}.  It has been submitted to our staff for approval.";
$PLG_maps_MESSAGE2  = "Marker submission is close.";
$PLG_maps_MESSAGE3  = "Oups... There was an error. I can't save your marker.";

/**
*   Localization of the Admin Configuration UI
*   @global array $LANG_configsections['maps']
*/
$LANG_configsections['maps'] = array(
    'label' => 'Maps',
    'title' => 'Maps Configuration'
);

/**
*   Configuration system prompt strings
*   @global array $LANG_confignames['maps']
*/
$LANG_confignames['maps'] = array(
    'hide_maps_menu'        => 'Hide Maps menu',
    'maps_login_required'   => 'Maps login required',
    'autofill_coord'        => 'Automatically fill undefined coordinates',
    'display_geo_profile'   => 'Profile geolocalisation',
    'map_type_profile'      => 'Profile map type',
    'map_type_geotag'       => 'geo autotag map type',
    'show_directions_geo'   => 'geo autotag show directions',
    'show_directions_profile' => 'Profile show directions',
    'map_width_geotag'      => 'geo autotag map width (with % or px)',
    'map_height_geotag'     => 'geo autotag map height (with px only)',
    'map_zoom_geotag'       => 'geo autotag zoom (0-21)',
    'map_width_profile'     => 'Profile map width (with % or px)',
    'map_height_profile'    => 'Profile map height (with px only)',
    'show_map'              => 'Show Google Map',
    'google_api_key'        => 'Google Maps API Key',
    'url_geocode'           => 'URL to Google Geocoding Service',
    'map_width'             => 'Maps width by default(with % or px)',
    'map_height'            => 'Maps height by default(with px only)',
    'map_zoom'              => 'Maps zoom by default(0-21)',
    'map_type'              => 'Maps type by default',
    'default_permissions'   => 'Permissions by default',
    'map_main_header'       => 'Main page header, autotag welcome',
    'map_main_footer'       => 'Main page footer, autotag welcome too',
    'map_geo'               => 'Create a map with all profiles',
    'map_markers'           => 'Create a map with all markers',
    'map_active'            => 'Map is active',
    'map_hidden'            => 'Map is hidden',
    'free_markers'          => 'Map accept free markers',
    'paid_markers'          => 'Map accept paid markers (need paypal plugin)',
    'street'                => 'Use street info',
    'code'                  => 'Use code info',
    'city'                  => 'Use city info',
    'state'                 => 'Use state info',
    'country'               => 'Use country info',
    'tel'                   => 'Use tel info',
    'fax'                   => 'Use additional contact info',
    'web'                   => 'Use web info',
    'item_1'                => 'Resource #1 label',
    'item_2'                => 'Resource #2 label',
    'item_3'                => 'Resource #3 label',
    'item_4'                => 'Resource #4 label',
    'item_5'                => 'Resource #5 label',
    'item_6'                => 'Resource #6 label',
    'item_7'                => 'Resource #7 label',
    'item_8'                => 'Resource #8 label',
    'item_9'                => 'Resource #9 label',
    'item_10'               => 'Resource #10 label',
    'label_color'           => 'Label color',
    'star_primary_color'    => 'Star primary color',
    'star_stroke_color'     => 'Star stroke color',
    'marker_active'         => 'Marker is active by default',
    'marker_hidden'         => 'Marker is hidden by default',
    'marker_payed'          => 'Marker payed by default',
    'marker_validity'       => 'Marker validy by default',
    'marker_submission'     => 'Allow markers submission',
    'users_map'             => 'Active map of site users',
    'global_map' 	        => 'Active global map',
    'global_type'           => 'Global map type',	
    'global_width'  	    => 'Global map width',
    'global_height' 	    => 'Global map height',
    'global_zoom'           => 'Global map zoom (0-21)',
    'detail_zoom'           => 'Marker detail zoom (0-21)',
    'submit_login_required' => 'Login require for markers submissions',
    'marker_edition'        => 'Marker edition',
    'infos_label'           => 'Infos label (Pro version)',
	'use_cluster'           => 'Use markers cluster',
	'zoom_profile'          => 'Zoom for map on user\'s profile (0-21)',
	'display_events_map'    => 'Display events map',
);

/**
*   Configuration system subgroup strings
*   @global array $LANG_configsubgroups['maps']
*/
$LANG_configsubgroups['maps'] = array(
    'sg_main' => 'Main Settings',
    'sg_display' => 'Display Settings'
);

/**
*   Configuration system tab names
*   @global array $LANG_configtabs['maps']
*/
$LANG_configtabs['maps'] = array(
    'tab_general' => 'General',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Maps',
    'tab_markers' => 'Markers',
    'tab_fields' => 'Marker fields',
);

/** Geeklog configuration tab labels (used by config::_UI_get_tab). */
$LANG_tab['maps'] = array(
    'tab_general' => 'General',
    'tab_google' => 'Google Maps',
    'tab_maps' => 'Maps',
    'tab_markers' => 'Markers',
    'tab_fields' => 'Marker fields',
);

/**
*   Configuration system fieldset names
*   @global array $LANG_fs['maps']
*/
$LANG_fs['maps'] = array(
    'fs_main'            => 'General Settings',
    'fs_ads'             => 'Google Ads Settings',
    'fs_google'          => 'Google API Settings',
    'fs_permissions'     => 'Default Permissions',
    'fs_display'         => 'Maps',
    'fs_global_map'      => 'Global Maps',
    'fs_display_profile' => 'Profile',
    'fs_display_geo'     => 'geo autotag',
    'fs_map_default'     => 'Map default settings',
    'fs_marker_default'  => 'Marker default settings',
 );

/**
*   Configuration system selection strings
*   Note: entries 0, 1, and 12 are the same as in 
*   $LANG_configselects['Core']
*
*   @global array $LANG_configselects['maps']
*/
$LANG_configselects['maps'] = array(
    0 => array('True' => 1, 'False' => 0),
    1 => array('True' => TRUE, 'False' => FALSE),
    3 => array('Yes' => 1, 'No' => 0),
    4 => array('On' => 1, 'Off' => 0),
    5 => array('Top of Page' => 1, 'Below Featured Article' => 2, 'Bottom of Page' => 3),
    10 => array('5' => 5, '10' => 10, '25' => 25, '50' => 50),
    11 => array('Miles' => 'miles', 'Kilometres' => 'km'),
    12 => array('No access' => 0, 'Read-Only' => 2, 'Read-Write' => 3),
	// changed in v1.3
    20 => array('Normal street map' => 'ROADMAP', 'Satellite images' => 'SATELLITE', 'Terrain map' => 'TERRAIN', 'Transparent layer of major streets on satellite images' => 'HYBRID'),
    30 => array('White' => 1, 'Black' => 0),
    31 => array('Temporary' => 1, 'Permanent' => 0),
);

$LANG_MAPS_1['location_search_label'] = 'Search for an address';
$LANG_MAPS_1['location_search_help'] = 'Search for an address, click the map or drag the marker to fine-tune its position.';
$LANG_MAPS_1['use_map_click_help'] = 'Click the map to move the marker.';
?>
