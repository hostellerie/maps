<?php
/**
 * Maps 1.5 configuration labels added without replacing the historical
 * language files. Loaded after the regular language file.
 */

if (!isset($LANG_confignames['maps']) || !is_array($LANG_confignames['maps'])) {
    $LANG_confignames['maps'] = array();
}
if (!isset($LANG_fs['maps']) || !is_array($LANG_fs['maps'])) {
    $LANG_fs['maps'] = array();
}

$isFrench = isset($_CONF['language']) && strpos(strtolower($_CONF['language']), 'french') === 0;

if ($isFrench) {
    $LANG_confignames['maps']['google_api_key'] = 'Clé API Google Maps (navigateur)';
    $LANG_confignames['maps']['google_server_api_key'] = 'Clé API Google Geocoding (serveur, optionnelle)';
    $LANG_confignames['maps']['google_map_id'] = 'Google Map ID (préparation Advanced Markers)';
    $LANG_confignames['maps']['google_language'] = 'Langue Google Maps (optionnelle, ex. fr)';
    $LANG_confignames['maps']['google_region'] = 'Région Google Maps (optionnelle, ex. FR)';
    $LANG_confignames['maps']['url_geocode'] = 'URL du service Google Geocoding';
    $LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
    $LANG_fs['maps']['fs_map_defaults'] = 'Réglages par défaut des cartes';
    $LANG_fs['maps']['fs_marker_defaults'] = 'Réglages par défaut des marqueurs';
    $LANG_fs['maps']['fs_marker_fields'] = 'Champs des marqueurs';
} else {
    $LANG_confignames['maps']['google_api_key'] = 'Google Maps browser API key';
    $LANG_confignames['maps']['google_server_api_key'] = 'Google Geocoding server API key (optional)';
    $LANG_confignames['maps']['google_map_id'] = 'Google Map ID (Advanced Markers preparation)';
    $LANG_confignames['maps']['google_language'] = 'Google Maps language (optional, e.g. en)';
    $LANG_confignames['maps']['google_region'] = 'Google Maps region (optional, e.g. US)';
    $LANG_confignames['maps']['url_geocode'] = 'Google Geocoding service URL';
    $LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
    $LANG_fs['maps']['fs_map_defaults'] = 'Map default settings';
    $LANG_fs['maps']['fs_marker_defaults'] = 'Marker default settings';
    $LANG_fs['maps']['fs_marker_fields'] = 'Marker fields';
}
