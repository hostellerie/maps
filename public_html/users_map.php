<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                           |
// +---------------------------------------------------------------------------+
// | users_map.php                                                                 |
// |                                                                           |
// | Public plugin page                                                        |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026 by the following authors:                              |
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

require_once '../lib-common.php';

// take user back to the homepage if the plugin is not active
if (!in_array('maps', $_PLUGINS)) {
    echo COM_refresh($_CONF['site_url'] . '/index.php');
    exit;
}

MAPS_getheadercode();

$display = '';

// Ensure user has the rights to access this page
if (COM_isAnonUser() && (($_CONF['loginrequired'] == 1) || (MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) == 1))) {
	$display .= MAPS_compatSiteHeader('');
	$display .= MAPS_user_menu();
    $display .= COM_startBlock ($LANG_LOGIN[1], '',
                                COM_getBlockTemplate ('_msg_block', 'header'));
    $login = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $login->set_file (array ('login'=>'submitloginrequired.thtml'));
    $login->set_var ( 'xhtml', XHTML );
    $login->set_var ('login_message', $LANG_LOGIN[2]);
    $login->set_var ('site_url', $_CONF['site_url']);
    $login->set_var ('site_admin_url', $_CONF['site_admin_url']);
    $login->set_var ('layout_url', $_CONF['layout_url']);
    $login->set_var ('lang_login', $LANG_LOGIN[3]);
    $login->set_var ('lang_newuser', $LANG_LOGIN[4]);
    $login->parse ('output', 'login');
    $display .= $login->finish ($login->get_var('output'));
    $display .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
    $display .= MAPS_compatSiteFooter();
    MAPS_compatOutput($display);
    exit;
}

function getUsersMap () {

    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF, $_CONF, $_USER;

    $retval = '';
		
	// Ensure user has the rights to access this map
	if (MAPS_arrayGet($_MAPS_CONF, 'users_map', 0) == 0) {
		echo COM_refresh($_MAPS_CONF['site_url'] . '/index.php');
		exit ();
	}
	
	$T = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
	$T->set_file('page', 'map.thtml');
	MAPS_fillMapCommon(
		$T,
		0,
		0,
		0,
		MAPS_arrayGet($_MAPS_CONF, 'global_zoom', 2),
		MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'),
		MAPS_arrayGet($_MAPS_CONF, 'global_width', '100%'),
		MAPS_arrayGet($_MAPS_CONF, 'global_height', '600px')
	);
	$T->set_var('name', $LANG_MAPS_1['users_map']);
	$T->set_var('description', '<p>' . $LANG_MAPS_1['info_users_map'] . '</p>');
	$T->set_var('header', '');
	$T->set_var('footer', '');
	$T->set_var('address', '');
	$T->set_var('primaryColor', '');
	$T->set_var('stroke_color', '');
	if (MAPS_arrayGet($_MAPS_CONF, 'label_color', 0) == 1) {
		$label_color = '#FFFFFF';
	} else {
		$label_color = '#000000';
	}
	$T->set_var('label_color', $label_color);
	$T->set_var('label', '');

    $userAttributesTable = MAPS_userAttributesTable();
    if ($userAttributesTable === '') {
        return '<p>' . htmlspecialchars($LANG_MAPS_1['no_marker'], ENT_QUOTES, 'UTF-8') . '</p>';
    }

	$sql = "
	    SELECT info.uid, info.location, info.about, geo.lat, geo.lng, user.username, user.fullname, user.photo, user.regdate 
	    FROM {$userAttributesTable} AS info 
		INNER JOIN {$_TABLES['maps_geo']} AS geo 
		ON geo.geo = info.location 
		INNER JOIN {$_TABLES['users']} AS user 
		ON user.uid = info.uid 
		WHERE info.location <> ''
	";
	
	$user_marker = DB_query($sql);
	
    //Build markers  
    $nRows  = DB_numRows($user_marker);
	
	$markers = 'var markers = [];';	
	
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0) === 1) {
        $T->set_var('markerclusterer', MAPS_clusterScriptTag());
    } else {
        $T->set_var('markerclusterer', '');
    }
	
    for ( $i=0; $i < $nRows; $i++ ) {
        
		$marker = DB_fetchArray($user_marker);
		
	    $marker['mkid'] = $marker['uid'];

		$markers .= LB
			. '                var marker' . (int) $marker['mkid'] . ' = new google.maps.Marker({' . LB
			. '                    position: {lat: Number(' . MAPS_jsNumber(MAPS_arrayGet($marker, 'lat', 0), 0)
			. '), lng: Number(' . MAPS_jsNumber(MAPS_arrayGet($marker, 'lng', 0), 0) . ')},' . LB
			. '                    map: map0,' . LB
			. '                    title: ' . MAPS_jsString(MAPS_arrayGet($marker, 'username', '')) . ',' . LB
			. '                    animation: google.maps.Animation.DROP' . LB
			. '                });' . LB;


		//Infowindow link to user profile
		$bio = '';
		if ($marker['about'] != '') $bio = preg_replace( "/\r|\n/", "", nl2br(substr ( $marker['about'] ,0, 150 ))) . '...<br' .  XHTML . '>';
		$popupWidth = MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_width', '250px'), '250px');
        $popupHeight = MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_height', '150px'), '150px');
        $presentation = '<div style="overflow:auto;width:' . $popupWidth . ';height:' . $popupHeight . '"><p><strong><span style="text-transform:uppercase;">' . htmlspecialchars($marker['username'], ENT_QUOTES, 'UTF-8') . '</span></strong></p>' . $bio;
		$presentation .= '<a href="'. $_CONF['site_url'] .'/users.php?mode=profile&uid=' . $marker['uid'] . '">' . $LANG_MAPS_1['read_more'] . '</a>';
		$presentation .= '</div>';
		
		$markers .= '				var infowindow' . $marker['mkid'] . ' = new google.maps.InfoWindow({
			  content: \'' . addslashes($presentation) . '\'
		  });' . LB;
		
		// Adding a click-event to the marker  
		$markers .= '				google.maps.event.addListener(marker' . $marker['mkid'] . ', \'click\', function() {
			infowindow' . $marker['mkid'] . '.open(map0,marker' . $marker['mkid'] .');
		  });' . LB;

		// Add marker to map
		if ( MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0) == 1 ) {
		    $markers .= '				    markers.push(marker' . $marker['mkid'] .');' . LB;
		}
		
	}
	
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0) === 1) {
        $markers .= LB . MAPS_clusterInitJs('map0') . LB;
    }
	
	//Ads	
	$ads = MAPS_getAds (0);
	$T->set_var('ads', $ads);
							
	$T->set_var('markers', $markers);

	$T->set_var('edit_button', '');

	$T->parse('output','page');
	$retval .= $T->finish($T->get_var('output'));
	
	return $retval;
}

// MAIN

$display = '';

$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['users_map']);
$display .= MAPS_user_menu();

if (MAPS_arrayGet($_MAPS_CONF, 'users_map', 0) == 1) {
    //Display the Users Map 
	$display .= getUsersMap();
} else {
    echo COM_refresh($_MAPS_CONF['site_url'] . '/index.php');
}

$display .= MAPS_compatSiteFooter();

MAPS_compatOutput($display);

?>
