<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.0                                                         |
// +---------------------------------------------------------------------------+
// | geocoder.php                                                              |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

MAPS_getheadercode();

if (!SEC_hasRights('maps.admin')) {
    COM_accessLog('Unauthorized access attempt to Maps geocoder administration.');
    $content = COM_showMessageText($MESSAGE[29], $MESSAGE[30]);
    COM_output(COM_createHTMLDocument($content, array('pagetitle' => $MESSAGE[30])));
    exit;
}

$apiUrl = MAPS_googleMapsApiUrl();
$_SCRIPTS->setJavaScript(
    '<script src="' . htmlspecialchars($apiUrl, ENT_QUOTES, 'UTF-8') . '"></script>',
    false,
    false
);

$js = "
var mapsGeocoder;
var mapsGeocoderMap;
var mapsGeocoderMarker;

function initializeGMap() {
    var element = document.getElementById('map_canvas');
    if (!element || typeof google === 'undefined' || !google.maps) {
        return;
    }
    mapsGeocoder = new google.maps.Geocoder();
    mapsGeocoderMap = new google.maps.Map(element, {
        center: {lat: 0, lng: 0},
        zoom: 1,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
}

function codeAddress() {
    if (!mapsGeocoder) {
        return;
    }
    var field = document.getElementById('address');
    var address = field ? field.value : '';
    mapsGeocoder.geocode({address: address}, function (results, status) {
        if ((status === 'OK' || status === google.maps.GeocoderStatus.OK) && results[0]) {
            var location = results[0].geometry.location;
            mapsGeocoderMap.setCenter(location);
            mapsGeocoderMap.setZoom(14);
            if (mapsGeocoderMarker) {
                mapsGeocoderMarker.setMap(null);
            }
            mapsGeocoderMarker = new google.maps.Marker({map: mapsGeocoderMap, position: location});
            new google.maps.InfoWindow({
                content: '<div style=\"width:220px\">Lat: ' + location.lat() + '<br>Lng: ' + location.lng() + '<br>' + results[0].formatted_address + '</div>'
            }).open({map: mapsGeocoderMap, anchor: mapsGeocoderMarker});
        } else {
            alert('Geocode error: ' + status);
        }
    });
}

if (window.addEventListener) {
    window.addEventListener('load', initializeGMap, false);
} else {
    window.attachEvent('onload', initializeGMap);
}
";
$_SCRIPTS->setJavaScript($js, true, true);

$content = MAPS_admin_menu();
$content .= MAPS_geocoding();
COM_output(COM_createHTMLDocument($content, array('pagetitle' => $LANG_MAPS_1['plugin_name'])));
