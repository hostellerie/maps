<?php
/**
 * Maps 1.5.x configuration labels loaded after the historical language file.
 */

if (!isset($LANG_confignames['maps']) || !is_array($LANG_confignames['maps'])) {
    $LANG_confignames['maps'] = array();
}
if (!isset($LANG_fs['maps']) || !is_array($LANG_fs['maps'])) {
    $LANG_fs['maps'] = array();
}
if (!isset($LANG_configsubgroups['maps']) || !is_array($LANG_configsubgroups['maps'])) {
    $LANG_configsubgroups['maps'] = array();
}
if (!isset($LANG_configtabs['maps']) || !is_array($LANG_configtabs['maps'])) {
    $LANG_configtabs['maps'] = array();
}
if (!isset($LANG_tab['maps']) || !is_array($LANG_tab['maps'])) {
    $LANG_tab['maps'] = array();
}

$isFrench = isset($_CONF['language']) && strpos(strtolower($_CONF['language']), 'french') === 0;

if ($isFrench) {
    /* Configuration subgroup and tabs */
    $LANG_configsubgroups['maps']['sg_main'] = 'Principaux';
    $LANG_configtabs['maps']['tab_general'] = 'Général';
    $LANG_tab['maps']['tab_general'] = 'Général';
    $LANG_configtabs['maps']['tab_google'] = 'Google Maps';
    $LANG_tab['maps']['tab_google'] = 'Google Maps';
    $LANG_configtabs['maps']['tab_maps'] = 'Cartes';
    $LANG_tab['maps']['tab_maps'] = 'Cartes';
    $LANG_configtabs['maps']['tab_markers'] = 'Marqueurs';
    $LANG_tab['maps']['tab_markers'] = 'Marqueurs';
    $LANG_configtabs['maps']['tab_fields'] = 'Champs des marqueurs';
    $LANG_tab['maps']['tab_fields'] = 'Champs des marqueurs';

    /* Fieldsets */
    $LANG_fs['maps']['fs_main'] = 'Accès et fonctions';
    $LANG_fs['maps']['fs_permissions'] = 'Permissions par défaut';
    $LANG_fs['maps']['fs_uploads'] = 'Images et uploads';
    $LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
    $LANG_fs['maps']['fs_display'] = 'Affichage général';
    $LANG_fs['maps']['fs_global_map'] = 'Carte globale et carte des membres';
    $LANG_fs['maps']['fs_display_profile'] = 'Carte du profil des membres';
    $LANG_fs['maps']['fs_display_geo'] = 'Autotag geo';
    $LANG_fs['maps']['fs_map_defaults'] = 'Valeurs par défaut des nouvelles cartes';
    $LANG_fs['maps']['fs_events_map'] = 'Carte des événements';
    $LANG_fs['maps']['fs_marker_defaults'] = 'Valeurs par défaut des marqueurs';
    $LANG_fs['maps']['fs_marker_editor'] = 'Carte de saisie / déplacement d’un marqueur';
    $LANG_fs['maps']['fs_marker_detail'] = 'Carte de détail d’un marqueur';
    $LANG_fs['maps']['fs_marker_popup'] = 'Infobulles des marqueurs';
    $LANG_fs['maps']['fs_marker_fields'] = 'Champs et libellés des marqueurs';

    /* General */
    $LANG_confignames['maps']['maps_login_required'] = 'Connexion requise pour consulter Maps';
    $LANG_confignames['maps']['hide_maps_menu'] = 'Masquer Maps dans le menu';
    $LANG_confignames['maps']['marker_submission'] = 'Autoriser la soumission de marqueurs';
    $LANG_confignames['maps']['submit_login_required'] = 'Connexion requise pour soumettre un marqueur';
    $LANG_confignames['maps']['marker_edition'] = 'Autoriser l’édition des marqueurs';
    $LANG_confignames['maps']['default_permissions'] = 'Permissions par défaut';
    $LANG_confignames['maps']['max_image_width'] = 'Largeur maximale des images (px)';
    $LANG_confignames['maps']['max_image_height'] = 'Hauteur maximale des images (px)';
    $LANG_confignames['maps']['max_image_size'] = 'Taille maximale d’une image (octets)';

    /* Google */
    $LANG_confignames['maps']['autofill_coord'] = 'Compléter automatiquement les coordonnées manquantes';
    $LANG_confignames['maps']['google_api_key'] = 'Clé API Google Maps (navigateur)';
    $LANG_confignames['maps']['google_server_api_key'] = 'Clé API Google Geocoding (serveur, optionnelle)';
    $LANG_confignames['maps']['google_map_id'] = 'Google Map ID (préparation Advanced Markers)';
    $LANG_confignames['maps']['google_language'] = 'Langue Google Maps (optionnelle, ex. fr)';
    $LANG_confignames['maps']['google_region'] = 'Région Google Maps (optionnelle, ex. FR)';
    $LANG_confignames['maps']['url_geocode'] = 'URL du service Google Geocoding';

    /* New map/display values */
    $LANG_confignames['maps']['map_primary_color'] = 'Couleur principale par défaut';
    $LANG_confignames['maps']['map_stroke_color'] = 'Couleur de contour par défaut';
    $LANG_confignames['maps']['map_label'] = 'Libellé de marqueur par défaut';
    $LANG_confignames['maps']['map_label_color'] = 'Couleur de libellé par défaut';
    $LANG_confignames['maps']['events_map_zoom'] = 'Zoom de la carte des événements';
    $LANG_confignames['maps']['events_map_height'] = 'Hauteur de la carte des événements';

    /* Markers */
    $LANG_confignames['maps']['marker_editor_type'] = 'Type de carte pour l’éditeur';
    $LANG_confignames['maps']['marker_editor_zoom'] = 'Zoom initial de l’éditeur';
    $LANG_confignames['maps']['marker_editor_width'] = 'Largeur de la carte de l’éditeur';
    $LANG_confignames['maps']['marker_editor_height'] = 'Hauteur de la carte de l’éditeur';
    $LANG_confignames['maps']['detail_width'] = 'Largeur de la carte de détail';
    $LANG_confignames['maps']['detail_height'] = 'Hauteur de la carte de détail';
    $LANG_confignames['maps']['detail_zoom'] = 'Zoom de la carte de détail';
    $LANG_confignames['maps']['popup_width'] = 'Largeur des infobulles';
    $LANG_confignames['maps']['popup_height'] = 'Hauteur des infobulles';

    /* Marker fields */
    $LANG_confignames['maps']['street'] = 'Afficher la rue';
    $LANG_confignames['maps']['code'] = 'Afficher le code postal';
    $LANG_confignames['maps']['city'] = 'Afficher la ville';
    $LANG_confignames['maps']['state'] = 'Afficher la région / l’État';
    $LANG_confignames['maps']['country'] = 'Afficher le pays';
    $LANG_confignames['maps']['tel'] = 'Afficher le téléphone';
    $LANG_confignames['maps']['fax'] = 'Afficher le contact complémentaire';
    $LANG_confignames['maps']['web'] = 'Afficher le site web';
} else {
    $LANG_configsubgroups['maps']['sg_main'] = 'Main';
    $LANG_configtabs['maps']['tab_general'] = 'General';
    $LANG_tab['maps']['tab_general'] = 'General';
    $LANG_configtabs['maps']['tab_google'] = 'Google Maps';
    $LANG_tab['maps']['tab_google'] = 'Google Maps';
    $LANG_configtabs['maps']['tab_maps'] = 'Maps';
    $LANG_tab['maps']['tab_maps'] = 'Maps';
    $LANG_configtabs['maps']['tab_markers'] = 'Markers';
    $LANG_tab['maps']['tab_markers'] = 'Markers';
    $LANG_configtabs['maps']['tab_fields'] = 'Marker fields';
    $LANG_tab['maps']['tab_fields'] = 'Marker fields';

    $LANG_fs['maps']['fs_main'] = 'Access and features';
    $LANG_fs['maps']['fs_permissions'] = 'Default permissions';
    $LANG_fs['maps']['fs_uploads'] = 'Images and uploads';
    $LANG_fs['maps']['fs_google'] = 'Google Maps Platform';
    $LANG_fs['maps']['fs_display'] = 'General display';
    $LANG_fs['maps']['fs_global_map'] = 'Global and users map';
    $LANG_fs['maps']['fs_display_profile'] = 'User profile map';
    $LANG_fs['maps']['fs_display_geo'] = 'Geo autotag';
    $LANG_fs['maps']['fs_map_defaults'] = 'New map defaults';
    $LANG_fs['maps']['fs_events_map'] = 'Events map';
    $LANG_fs['maps']['fs_marker_defaults'] = 'Marker defaults';
    $LANG_fs['maps']['fs_marker_editor'] = 'Marker editor map';
    $LANG_fs['maps']['fs_marker_detail'] = 'Marker detail map';
    $LANG_fs['maps']['fs_marker_popup'] = 'Marker info windows';
    $LANG_fs['maps']['fs_marker_fields'] = 'Marker fields and labels';

    $LANG_confignames['maps']['max_image_width'] = 'Maximum image width (px)';
    $LANG_confignames['maps']['max_image_height'] = 'Maximum image height (px)';
    $LANG_confignames['maps']['max_image_size'] = 'Maximum image size (bytes)';
    $LANG_confignames['maps']['google_api_key'] = 'Google Maps browser API key';
    $LANG_confignames['maps']['google_server_api_key'] = 'Google Geocoding server API key (optional)';
    $LANG_confignames['maps']['google_map_id'] = 'Google Map ID (Advanced Markers preparation)';
    $LANG_confignames['maps']['google_language'] = 'Google Maps language (optional, e.g. en)';
    $LANG_confignames['maps']['google_region'] = 'Google Maps region (optional, e.g. US)';
    $LANG_confignames['maps']['url_geocode'] = 'Google Geocoding service URL';
    $LANG_confignames['maps']['map_primary_color'] = 'Default map primary color';
    $LANG_confignames['maps']['map_stroke_color'] = 'Default map stroke color';
    $LANG_confignames['maps']['map_label'] = 'Default map marker label';
    $LANG_confignames['maps']['map_label_color'] = 'Default map label color';
    $LANG_confignames['maps']['events_map_zoom'] = 'Events map zoom';
    $LANG_confignames['maps']['events_map_height'] = 'Events map height';
    $LANG_confignames['maps']['marker_editor_type'] = 'Marker editor map type';
    $LANG_confignames['maps']['marker_editor_zoom'] = 'Marker editor initial zoom';
    $LANG_confignames['maps']['marker_editor_width'] = 'Marker editor map width';
    $LANG_confignames['maps']['marker_editor_height'] = 'Marker editor map height';
    $LANG_confignames['maps']['detail_width'] = 'Marker detail map width';
    $LANG_confignames['maps']['detail_height'] = 'Marker detail map height';
    $LANG_confignames['maps']['detail_zoom'] = 'Marker detail map zoom';
    $LANG_confignames['maps']['popup_width'] = 'Info window width';
    $LANG_confignames['maps']['popup_height'] = 'Info window height';
}
