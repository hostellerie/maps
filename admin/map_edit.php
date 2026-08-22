<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.6                                                         |
// +---------------------------------------------------------------------------+
// | map_edit.php                                                              |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';
require_once 'edit_functions.php';

MAPS_getheadercode();

if (!SEC_hasRights('maps.admin')) {
    COM_accessLog('Unauthorized access attempt to Maps map editor.');
    COM_output(COM_createHTMLDocument(COM_showMessageText($MESSAGE[29], $MESSAGE[30]), array('pagetitle' => $MESSAGE[30])));
    exit;
}

function MAPS_mapEditorDefaults($map)
{
    global $_MAPS_CONF, $_USER, $_GROUPS;
    $defaults = array(
        'mid' => '', 'name' => '', 'description' => '', 'geo' => '', 'lat' => 0, 'lng' => 0,
        'created' => time(), 'modified' => time(), 'free_marker' => MAPS_arrayGet($_MAPS_CONF, 'free_markers', 1),
        'paid_marker' => MAPS_arrayGet($_MAPS_CONF, 'paid_markers', 1), 'active' => MAPS_arrayGet($_MAPS_CONF, 'map_active', 1),
        'hidden' => MAPS_arrayGet($_MAPS_CONF, 'map_hidden', 0), 'width' => MAPS_arrayGet($_MAPS_CONF, 'map_width', '100%'),
        'height' => MAPS_arrayGet($_MAPS_CONF, 'map_height', '600px'), 'zoom' => MAPS_arrayGet($_MAPS_CONF, 'map_zoom', 6),
        'type' => MAPS_arrayGet($_MAPS_CONF, 'map_type', 'ROADMAP'), 'header' => '', 'footer' => '',
        'primary_color' => MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666'), 'stroke_color' => MAPS_arrayGet($_MAPS_CONF, 'map_stroke_color', '#333333'), 'label' => MAPS_arrayGet($_MAPS_CONF, 'map_label', ''), 'label_color' => MAPS_arrayGet($_MAPS_CONF, 'map_label_color', 0),
        'mmk_default' => 1, 'mmk_icon' => 0, 'owner_id' => isset($_USER['uid']) ? $_USER['uid'] : 2,
        'group_id' => isset($_GROUPS['Maps Admin']) ? $_GROUPS['Maps Admin'] : 2,
        'perm_owner' => 3, 'perm_group' => 3, 'perm_members' => 2, 'perm_anon' => 2
    );
    return array_merge($defaults, is_array($map) ? $map : array());
}

function getMapForm($map = array())
{
    global $_CONF, $_TABLES, $_MAPS_CONF, $LANG_MAPS_1, $LANG_configselects, $LANG_ACCESS, $_USER, $_SCRIPTS;

    $map = MAPS_mapEditorDefaults($map);
    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('map' => 'map_form.thtml'));

    $template->set_var('site_admin_url', $_CONF['site_admin_url']);
    $template->set_var('map_tab', $LANG_MAPS_1['map_tab']);
    $template->set_var('overlays_tab', $LANG_MAPS_1['overlays_tab']);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['name_label']);
    $template->set_var('name', htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('address_label', $LANG_MAPS_1['address_label']);
    $template->set_var('geo', htmlspecialchars(stripslashes($map['geo']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('description_label', $LANG_MAPS_1['description_label']);
    $template->set_var('description', htmlspecialchars(stripslashes($map['description']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('created_label', $LANG_MAPS_1['map_created']);
    $template->set_var('modified_label', $LANG_MAPS_1['modified']);
    $created = COM_getUserDateTimeFormat($map['created']);
    $modified = COM_getUserDateTimeFormat($map['modified']);
    $template->set_var('created', $created[0]);
    $template->set_var('modified', $modified[0]);
    $template->set_var('general_settings', $LANG_MAPS_1['general_settings']);
    $template->set_var('map_width', $LANG_MAPS_1['map_width']);
    $template->set_var('width', htmlspecialchars($map['width'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_height', $LANG_MAPS_1['map_height']);
    $template->set_var('height', htmlspecialchars($map['height'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_zoom', $LANG_MAPS_1['map_zoom']);
    $template->set_var('zoom', (int) $map['zoom']);
    $template->set_var('map_type', $LANG_MAPS_1['map_type']);
    $safeLat = MAPS_latitude($map['lat'], 0);
    $safeLng = MAPS_longitude($map['lng'], 0);
    $safeZoom = MAPS_zoom($map['zoom'], 6);
    $hasSavedCenter = ($map['mid'] !== '' && MAPS_isValidCoordinatePair($map['lat'], $map['lng']));
    $template->set_var('lat', $hasSavedCenter ? htmlspecialchars(MAPS_canonicalNumberString($safeLat, 0), ENT_QUOTES, 'UTF-8') : '');
    $template->set_var('lng', $hasSavedCenter ? htmlspecialchars(MAPS_canonicalNumberString($safeLng, 0), ENT_QUOTES, 'UTF-8') : '');
    $template->set_var('section_display', isset($LANG_MAPS_1['map_section_display']) ? $LANG_MAPS_1['map_section_display'] : 'Display');
    $template->set_var('section_center', isset($LANG_MAPS_1['map_section_center']) ? $LANG_MAPS_1['map_section_center'] : 'Center & zoom');
    $template->set_var('section_markers', isset($LANG_MAPS_1['map_section_markers']) ? $LANG_MAPS_1['map_section_markers'] : 'Markers');
    $template->set_var('section_advanced', isset($LANG_MAPS_1['map_section_advanced']) ? $LANG_MAPS_1['map_section_advanced'] : 'Advanced');
    $template->set_var('center_search_label', isset($LANG_MAPS_1['map_center_search']) ? $LANG_MAPS_1['map_center_search'] : 'Search an address');
    $template->set_var('center_search_button', isset($LANG_MAPS_1['map_center_search_button']) ? $LANG_MAPS_1['map_center_search_button'] : 'Locate');
    $template->set_var('center_use_button', isset($LANG_MAPS_1['map_center_use_button']) ? $LANG_MAPS_1['map_center_use_button'] : 'Use displayed center');
    $template->set_var('center_help', isset($LANG_MAPS_1['map_center_help']) ? $LANG_MAPS_1['map_center_help'] : 'Search, click the map or drag the marker to choose the map center.');
    $template->set_var('technical_coordinates', isset($LANG_MAPS_1['technical_coordinates']) ? $LANG_MAPS_1['technical_coordinates'] : 'Technical coordinates');
    $template->set_var('latitude_label', isset($LANG_MAPS_1['latitude_label']) ? $LANG_MAPS_1['latitude_label'] : 'Latitude');
    $template->set_var('longitude_label', isset($LANG_MAPS_1['longitude_label']) ? $LANG_MAPS_1['longitude_label'] : 'Longitude');

    $options = '';
    foreach ($LANG_configselects['maps'][20] as $label => $value) {
        $options .= '<option value="' . $value . '"' . ($value === $map['type'] ? ' selected="selected"' : '') . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
    }
    $template->set_var('options', $options);
    $template->set_var('yes', $LANG_MAPS_1['yes']);
    $template->set_var('no', $LANG_MAPS_1['no']);

    foreach (array('active','hidden','free_marker','paid_marker') as $field) {
        $labelKey = $field;
        if (isset($LANG_MAPS_1[$labelKey])) {
            $template->set_var($field, $LANG_MAPS_1[$labelKey]);
        }
        $template->set_var($field . '_yes', (int) $map[$field] === 1 ? ' selected="selected"' : '');
        $template->set_var($field . '_no', (int) $map[$field] === 0 ? ' selected="selected"' : '');
    }

    $template->set_var('mk_default', $LANG_MAPS_1['mk_default']);
    $template->set_var('mk_default_yes', (int) $map['mmk_default'] === 1 ? ' selected="selected"' : '');
    $template->set_var('mk_default_no', (int) $map['mmk_default'] === 0 ? ' selected="selected"' : '');
    $radio = '<p>' . $LANG_MAPS_1['choose_icon'] . '</p>';
    $radio .= '<label><input type="radio" name="mk_icon" value="0"' . ((int) $map['mmk_icon'] === 0 ? ' checked="checked"' : '') . '> ' . $LANG_MAPS_1['no_icon'] . '</label> ';
    $icons = DB_query("SELECT * FROM {$_TABLES['maps_map_icons']} ORDER BY icon_name");
    while ($icon = DB_fetchArray($icons)) {
        $radio .= '<label><input type="radio" name="mk_icon" value="' . (int) $icon['icon_id'] . '"' . ((int) $map['mmk_icon'] === (int) $icon['icon_id'] ? ' checked="checked"' : '') . '> <img src="' . $_MAPS_CONF['images_icons_url'] . rawurlencode($icon['icon_image']) . '" alt="" style="max-width:32px;max-height:32px"></label> ';
    }
    $template->set_var('icon', $radio);

    $template->set_var('marker_label', $LANG_MAPS_1['marker_label']);
    $template->set_var('primary_color_label', $LANG_MAPS_1['primary_color_label']);
    $template->set_var('primary_color', htmlspecialchars(MAPS_htmlColor($map['primary_color'], MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666')), ENT_QUOTES, 'UTF-8'));
    $template->set_var('stroke_color_label', $LANG_MAPS_1['stroke_color_label']);
    $template->set_var('stroke_color', htmlspecialchars(MAPS_htmlColor($map['stroke_color'], MAPS_arrayGet($_MAPS_CONF, 'map_stroke_color', '#333333')), ENT_QUOTES, 'UTF-8'));
    $template->set_var('label_label', $LANG_MAPS_1['label']);
    $template->set_var('label', htmlspecialchars($map['label'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('label_color_label', $LANG_MAPS_1['label_color']);
    $template->set_var('label_color_white', (int) $map['label_color'] === 1 ? ' selected="selected"' : '');
    $template->set_var('label_color_black', (int) $map['label_color'] === 0 ? ' selected="selected"' : '');
    $template->set_var('black', $LANG_MAPS_1['black']);
    $template->set_var('white', $LANG_MAPS_1['white']);

    /* Plain textareas deliberately replace the removed FCKeditor dependency. */
    $template->set_var('header_footer', $LANG_MAPS_1['header_footer']);
    $template->set_var('map_header_label', $LANG_MAPS_1['map_header_label']);
    $template->set_var('map_header', htmlspecialchars(stripslashes($map['header']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('map_footer_label', $LANG_MAPS_1['map_footer_label']);
    $template->set_var('map_footer', htmlspecialchars(stripslashes($map['footer']), ENT_QUOTES, 'UTF-8'));

    $template->set_var('lang_accessrights', $LANG_ACCESS['accessrights']);
    $template->set_var('lang_owner', $LANG_ACCESS['owner']);
    $template->set_var('owner_select', COM_optionList($_TABLES['users'], 'uid,username', $map['owner_id'], 1, 'uid<>1'));
    $template->set_var('owner_username', DB_getItem($_TABLES['users'], 'username', 'uid=' . (int) $map['owner_id']));
    $template->set_var('owner_name', COM_getDisplayName($map['owner_id']));
    $template->set_var('owner', COM_getDisplayName($map['owner_id']));
    $template->set_var('owner_id', (int) $map['owner_id']);
    $template->set_var('lang_group', $LANG_ACCESS['group']);
    $access = 3;
    $template->set_var('group_dropdown', SEC_getGroupDropdown($map['group_id'], $access));
    $template->set_var('permissions_editor', SEC_getPermissionsHTML($map['perm_owner'], $map['perm_group'], $map['perm_members'], $map['perm_anon']));
    $template->set_var('lang_permissions', $LANG_ACCESS['permissions']);
    $template->set_var('lang_perm_key', $LANG_ACCESS['permissionskey']);
    $template->set_var('permissions_msg', $LANG_ACCESS['permmsg']);
    $template->set_var('lang_permissions_msg', $LANG_ACCESS['permmsg']);
    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_button', $LANG_MAPS_1['delete_button']);
    $template->set_var(
        'delete_action',
        $map['mid'] !== ''
            ? '<button type="submit" name="mode" value="delete" class="maps-danger-action maps-delete-map">'
                . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>'
            : ''
    );
    $deleteConfirm = isset($LANG_MAPS_1['delete_map_confirm']) ? $LANG_MAPS_1['delete_map_confirm'] : 'Delete this map?';
    $template->set_var('delete_confirm_js', MAPS_jsString($deleteConfirm));
    $deleteConfirmJs = MAPS_jsString($deleteConfirm);
    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);
    $template->set_var('mid', $map['mid'] !== '' ? '<input type="hidden" name="mid" value="' . (int) $map['mid'] . '">' : '');
    $template->set_var('overlays', $map['mid'] !== '' ? MAPS_displayOverlays($map['mid']) : '');
    $template->set_var('add_overlay', $map['mid'] !== '' ? MAPS_displayOverlaysToAdd($map['mid']) : '<p>' . $LANG_MAPS_1['add_overlay'] . '</p>');


    $editorScript = "<script type=\"text/javascript\">\n"
        . '(function(){'
        . 'function ready(fn){if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",fn);}else{fn();}}'
        . 'ready(function(){'
        . 'var tabs=document.querySelector(".maps-map-editor-tabs");'
        . 'if(tabs){var links=tabs.querySelectorAll(".tabNavigation a"),allPanels=tabs.querySelectorAll(".maps-map-editor-panel"),panels=[];for(var p=0;p<allPanels.length;p++){if(allPanels[p].parentNode===tabs){panels.push(allPanels[p]);}}'
        . 'function showTab(hash){var target=null;try{target=tabs.querySelector(hash);}catch(ignore){}if(!target&&panels.length){target=panels[0];hash="#"+target.id;}for(var i=0;i<panels.length;i++){panels[i].style.display=(panels[i]===target)?"":"none";}for(var j=0;j<links.length;j++){var active=links[j].getAttribute("href")===hash;links[j].classList.toggle("selected",active);links[j].setAttribute("aria-selected",active?"true":"false");}}'
        . 'for(var i=0;i<links.length;i++){links[i].addEventListener("click",function(e){e.preventDefault();showTab(this.getAttribute("href"));});}showTab("#map_infos");}'
        . 'var deleteButtons=document.querySelectorAll(".maps-delete-map");for(var d=0;d<deleteButtons.length;d++){deleteButtons[d].addEventListener("click",function(e){if(!window.confirm(' . $deleteConfirmJs . ')){e.preventDefault();}});}'
        . 'var canvas=document.getElementById("maps-map-center-editor");if(!canvas){return;}'
        . 'function initMapEditor(attempt){if(typeof google==="undefined"||!google.maps){if(attempt<40){window.setTimeout(function(){initMapEditor(attempt+1);},100);}return;}'
        . 'var latInput=document.getElementById("map_center_lat"),lngInput=document.getElementById("map_center_lng"),zoomInput=document.getElementById("zoom"),typeInput=document.getElementById("type"),geoInput=document.getElementById("geo");'
        . 'var lat=Number(' . MAPS_jsString(MAPS_canonicalNumberString($safeLat, 0)) . '),lng=Number(' . MAPS_jsString(MAPS_canonicalNumberString($safeLng, 0)) . '),zoom=Number(' . MAPS_jsString((string) $safeZoom) . ');'
        . 'if(!isFinite(lat)){lat=0;}if(!isFinite(lng)){lng=0;}if(!isFinite(zoom)){zoom=6;}'
        . 'var type=(typeInput&&typeInput.value)?typeInput.value:"ROADMAP";'
        . 'var editorMap=new google.maps.Map(canvas,{center:{lat:lat,lng:lng},zoom:zoom,mapTypeId:google.maps.MapTypeId[type]||google.maps.MapTypeId.ROADMAP});'
        . 'var centerMarker=new google.maps.Marker({position:{lat:lat,lng:lng},map:editorMap,draggable:true,title:' . MAPS_jsString(isset($LANG_MAPS_1['map_center_marker']) ? $LANG_MAPS_1['map_center_marker'] : 'Map center') . '});'
        . 'function numberString(v){return Number(v).toFixed(6).replace(/\\.?0+$/,"\");}'
        . 'function sync(position,moveMap){if(!position){return;}var la=position.lat(),ln=position.lng();if(latInput){latInput.value=numberString(la);}if(lngInput){lngInput.value=numberString(ln);}centerMarker.setPosition(position);if(moveMap){editorMap.panTo(position);}}'
        . 'centerMarker.addListener("dragend",function(e){sync(e.latLng,true);});editorMap.addListener("click",function(e){sync(e.latLng,true);});editorMap.addListener("zoom_changed",function(){if(zoomInput){zoomInput.value=editorMap.getZoom();}});'
        . 'if(typeInput){typeInput.addEventListener("change",function(){editorMap.setMapTypeId(google.maps.MapTypeId[this.value]||google.maps.MapTypeId.ROADMAP);});}'
        . 'if(zoomInput){zoomInput.addEventListener("change",function(){var z=parseInt(this.value,10);if(isFinite(z)){editorMap.setZoom(z);}});}'
        . 'var useButton=document.getElementById("maps-center-use");if(useButton){useButton.addEventListener("click",function(e){e.preventDefault();sync(editorMap.getCenter(),false);});}'
        . 'var searchButton=document.getElementById("maps-center-search");if(searchButton){searchButton.addEventListener("click",function(e){e.preventDefault();var address=geoInput?geoInput.value.replace(/^\\s+|\\s+$/g,""):"";if(!address){return;}var geocoder=new google.maps.Geocoder();geocoder.geocode({address:address},function(results,status){if(status===google.maps.GeocoderStatus.OK&&results&&results[0]){sync(results[0].geometry.location,true);if(geoInput&&results[0].formatted_address){geoInput.value=results[0].formatted_address;}}});});}'
        . '}'
        . 'initMapEditor(0);'
        . '});'
        . "})();\n"
        . '</script>';

    return COM_startBlock($LANG_MAPS_1['map_edit'] . ' ' . htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8'))
        . $template->parse('output', 'map') . $editorScript . COM_endBlock();
}

$mode = isset($_REQUEST['mode']) ? COM_applyFilter($_REQUEST['mode']) : 'new';
$mid = isset($_REQUEST['mid']) ? (int) $_REQUEST['mid'] : 0;
$content = MAPS_admin_menu();

if ($mode === 'delete' && $mid > 0) {
    DB_delete($_TABLES['maps_maps'], 'mid', $mid);
    DB_delete($_TABLES['maps_markers'], 'mid', $mid);
    DB_delete($_TABLES['maps_map_overlay'], 'mo_mid', $mid);
    echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/index.php');
    exit;
}

if ($mode === 'save') {
    /*
     * Read editor values from POST only. Using $_REQUEST here can merge GET
     * and COOKIE values depending on PHP request_order and may overwrite form
     * values on some installations.
     */
    $post = is_array($_POST) ? $_POST : array();
    $mid = isset($post['mid']) ? (int) $post['mid'] : $mid;
    $name = trim(isset($post['name']) ? (string) $post['name'] : '');
    $geo = trim(isset($post['geo']) ? (string) $post['geo'] : '');
    $lat = isset($post['lat']) ? MAPS_normalizeNumber($post['lat'], null) : null;
    $lng = isset($post['lng']) ? MAPS_normalizeNumber($post['lng'], null) : null;

    if ($name === '' || $geo === '') {
        $content .= MAPS_message($LANG_MAPS_1['missing_field'], $LANG_MAPS_1['error']);
        $content .= getMapForm($post);
    } else {
        /*
         * Preserve the center explicitly chosen in the visual editor. Legacy
         * forms/installations without valid coordinates still fall back to
         * server-side geocoding of the address.
         */
        if (!MAPS_isValidCoordinatePair($lat, $lng)) {
            $lat = 0;
            $lng = 0;
            if (!MAPS_getCoords($geo, $lat, $lng)) {
                $geocodeMessage = isset($LANG_MAPS_1['geocode_save_fail'])
                    ? $LANG_MAPS_1['geocode_save_fail']
                    : 'The address could not be geocoded. Check the Google Maps API configuration and the address.';
                $content .= MAPS_message($geocodeMessage, $LANG_MAPS_1['error']);
                $content .= getMapForm($post);
                COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
                exit;
            }
        }
        $permOwner = isset($post['perm_owner']) ? $post['perm_owner'] : 3;
        $permGroup = isset($post['perm_group']) ? $post['perm_group'] : 3;
        $permMembers = isset($post['perm_members']) ? $post['perm_members'] : 2;
        $permAnon = isset($post['perm_anon']) ? $post['perm_anon'] : 2;
        if (is_array($permOwner) || is_array($permGroup) || is_array($permMembers) || is_array($permAnon)) {
            list($permOwner, $permGroup, $permMembers, $permAnon) = SEC_getPermissionValues($permOwner, $permGroup, $permMembers, $permAnon);
        }

        /* Keep raw values here and escape exactly once when building SQL. */
        $data = array(
            'name' => $name,
            'description' => isset($post['description']) ? (string) $post['description'] : '',
            'geo' => $geo,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'free_marker' => (int) (isset($post['free_marker']) ? $post['free_marker'] : 1),
            'paid_marker' => (int) (isset($post['paid_marker']) ? $post['paid_marker'] : 1),
            'active' => (int) (isset($post['active']) ? $post['active'] : 1),
            'hidden' => (int) (isset($post['hidden']) ? $post['hidden'] : 0),
            'width' => isset($post['width']) ? (string) $post['width'] : MAPS_arrayGet($_MAPS_CONF, 'map_width', '100%'),
            'height' => isset($post['height']) ? (string) $post['height'] : MAPS_arrayGet($_MAPS_CONF, 'map_height', '600px'),
            'zoom' => (int) (isset($post['zoom']) ? $post['zoom'] : 6),
            'type' => isset($post['type']) ? (string) $post['type'] : MAPS_arrayGet($_MAPS_CONF, 'map_type', 'ROADMAP'),
            'header' => isset($post['map_header']) ? (string) $post['map_header'] : '',
            'footer' => isset($post['map_footer']) ? (string) $post['map_footer'] : '',
            'primary_color' => MAPS_htmlColor(isset($post['primary_color']) ? $post['primary_color'] : '', MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666')),
            'stroke_color' => MAPS_htmlColor(isset($post['stroke_color']) ? $post['stroke_color'] : '', MAPS_arrayGet($_MAPS_CONF, 'map_stroke_color', '#333333')),
            'label' => isset($post['label']) ? (string) $post['label'] : '',
            'label_color' => (int) (isset($post['label_color']) ? $post['label_color'] : 0),
            'mmk_default' => (int) (isset($post['mk_default']) ? $post['mk_default'] : 1),
            'mmk_icon' => (int) (isset($post['mk_icon']) ? $post['mk_icon'] : 0),
            'owner_id' => (int) (isset($post['owner_id']) ? $post['owner_id'] : $_USER['uid']),
            'group_id' => (int) (isset($post['group_id']) ? $post['group_id'] : 2),
            'perm_owner' => (int) $permOwner,
            'perm_group' => (int) $permGroup,
            'perm_members' => (int) $permMembers,
            'perm_anon' => (int) $permAnon
        );

        $modified = date('YmdHis');
        $sets = array();
        foreach ($data as $field => $value) {
            $sets[] = $field . "='" . MAPS_dbEscape($value) . "'";
        }
        $sets[] = "modified='" . MAPS_dbEscape($modified) . "'";

        if ($mid > 0) {
            $exists = (int) DB_count($_TABLES['maps_maps'], 'mid', $mid);
            if ($exists <= 0) {
                COM_errorLog('MAPS map editor: update requested for missing mid ' . $mid);
                $content .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $content .= getMapForm($post);
                COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
                exit;
            }
            DB_query("UPDATE {$_TABLES['maps_maps']} SET " . implode(',', $sets) . ' WHERE mid=' . $mid);
        } else {
            $created = $modified;
            $fields = array_keys($data);
            $values = array();
            foreach ($data as $value) {
                $values[] = "'" . MAPS_dbEscape($value) . "'";
            }
            $fields[] = 'created';
            $values[] = "'" . MAPS_dbEscape($created) . "'";
            $fields[] = 'modified';
            $values[] = "'" . MAPS_dbEscape($modified) . "'";
            $fields[] = 'hits';
            $values[] = '0';
            DB_query("INSERT INTO {$_TABLES['maps_maps']} (" . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')');
            $mid = (int) DB_insertId();
            if ($mid <= 0) {
                $mid = (int) DB_getItem(
                    $_TABLES['maps_maps'],
                    'MAX(mid)',
                    "name='" . MAPS_dbEscape($name) . "' AND owner_id=" . (int) $data['owner_id']
                );
            }
        }

        /* Verify the row before redirecting so a failed write never looks like success. */
        $verify = array();
        if ($mid > 0) {
            $verifyResult = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . (int) $mid . ' LIMIT 1');
            if (DB_numRows($verifyResult) > 0) {
                $verify = DB_fetchArray($verifyResult);
            }
        }
        if (empty($verify) || !isset($verify['name']) || (string) $verify['name'] !== $name) {
            COM_errorLog('MAPS map editor: database write verification failed for mid ' . (int) $mid);
            $content .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
            $content .= getMapForm(array_merge($post, array('mid' => $mid)));
            COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
            exit;
        }

        echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&mid=' . (int) $mid);
        exit;
    }
} else {
    $map = array();
    if ($mode === 'edit' && $mid > 0) {
        $res = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid={$mid} LIMIT 1");
        if (DB_numRows($res) > 0) {
            $map = DB_fetchArray($res);
        }
    }
    $content .= getMapForm($map);
}

COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
