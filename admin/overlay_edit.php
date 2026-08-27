<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.2.2                                                         |
// +---------------------------------------------------------------------------+
// | overlay_edit.php                                                          |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2011-2012 by the following authors:                         |
// |                                                                           |
// | Authors: ::Ben  ben AT geeklog DOT fr                                     |
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

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

MAPS_getheadercode();

$display = '';

// Ensure user even has the rights to access this page
if (! SEC_hasRights('maps.admin')) {
    $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
             . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
             . MAPS_compatSiteFooter();

    // Log attempt to access.log
    COM_accessLog("User {$_USER['username']} tried to illegally access the Maps plugin administration screen.");

    MAPS_compatOutput($display);
    exit;
}


// Incoming variable filter
$vars = array('mode'     => 'alpha',
              'oid'      => 'alpha',
              'name'     => 'text',
			  'o_group'  => 'number',
			  'image'    => 'text',
			  'sw_lat'   => 'alpha',
			  'sw_lng'   => 'alpha',
			  'ne_lat'   => 'alpha',
			  'ne_lng'   => 'alpha',
			  'mid'      => 'number',
			  'active'   => 'number',
			  'zoom_min' => 'number',
			  'zoom_max' => 'number',
			  'order'    => 'number',
			  );

MAPS_filterVars($vars, $_REQUEST);

/**
 * This function creates a Overlay Form
 *
 * Creates a Form for an overlay using the supplied defaults (if specified).
 *
 * @param array $overlay array of values describing an overlay
 * @return string HTML string of overlay form
 */
function MAPS_getOverlayForm($overlay = array()) {

    global $_CONF, $_TABLES, $_MAPS_CONF, $LANG_MAPS_1, $LANG_configselects, $LANG_ACCESS, $_USER, $_GROUPS, $_SCRIPTS;
    
    $overlayDefaults = array(
        'oid' => 0, 'mid' => 0, 'name' => '', 'o_name' => '', 'o_group' => 0,
        'o_image' => '', 'o_sw_lat' => '', 'o_sw_lng' => '', 'o_ne_lat' => '',
        'o_ne_lng' => '', 'o_active' => 1, 'o_zoom_min' => 0, 'o_zoom_max' => 21
    );
    $overlay = array_merge($overlayDefaults, is_array($overlay) ? $overlay : array());

    
	$display = COM_startBlock('<h1>' . $LANG_MAPS_1['overlay_edit'] . ' ' . htmlspecialchars((string) $overlay['name'], ENT_QUOTES, 'UTF-8') . '</h1>');
	
	$map_options = MAPS_recurseMaps($overlay['mid']);

	if ($map_options == '') {
		$display .= COM_startBlock($LANG_MAPS_1['error'],'','blockheader-message.thtml');
        $display .= $LANG_MAPS_1['maps_empty'];
        $display .= COM_endBlock('blockfooter-message.thtml');

	} else {
		$template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
		$template->set_file(array('map' => 'overlay_form.thtml'));
        $token = SEC_createToken();
        $template->set_var(
            'csrf_token',
            '<input type="hidden" name="' . CSRF_TOKEN . '" value="'
            . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">'
        );
        $template->set_var('site_admin_url', $_CONF['site_admin_url']);

	    $template->set_var('edit_overlay_text', $LANG_MAPS_1['edit_overlay_text']);
		
		$template->set_var('yes', $LANG_MAPS_1['yes']);
		$template->set_var('no', $LANG_MAPS_1['no']);
		
		//informations
		$template->set_var('overlay_presentation', $LANG_MAPS_1['overlay_presentation']);
		$template->set_var('informations', $LANG_MAPS_1['informations']);
		$template->set_var('name_label', $LANG_MAPS_1['overlay_name_label']);
		$template->set_var('name', htmlspecialchars(stripslashes((string) $overlay['o_name']), ENT_QUOTES, 'UTF-8'));
		$template->set_var('group', MAPS_selectGroupOverlays($overlay['o_group']) );
		$template->set_var('sw_lat', $LANG_MAPS_1['sw_lat']);
		$template->set_var('sw_lat_value', htmlspecialchars((string) $overlay['o_sw_lat'], ENT_QUOTES, 'UTF-8'));
		$template->set_var('sw_lng', $LANG_MAPS_1['sw_lng']);
		$template->set_var('sw_lng_value', htmlspecialchars((string) $overlay['o_sw_lng'], ENT_QUOTES, 'UTF-8'));
		$template->set_var('ne_lat', $LANG_MAPS_1['ne_lat']);
		$template->set_var('ne_lat_value', htmlspecialchars((string) $overlay['o_ne_lat'], ENT_QUOTES, 'UTF-8'));
		$template->set_var('ne_lng', $LANG_MAPS_1['ne_lng']);
		$template->set_var('ne_lng_value', htmlspecialchars((string) $overlay['o_ne_lng'], ENT_QUOTES, 'UTF-8'));
		$template->set_var('required_field', $LANG_MAPS_1['required_field']);
				
		//active
		$template->set_var('active', $LANG_MAPS_1['overlay_active']);
		if ($overlay['o_active'] == '') {
			$overlay['o_active'] = $_MAPS_CONF['map_active'];
		}
		if ($overlay['o_active'] == 1) {
			$template->set_var('active_yes', ' selected');
			$template->set_var('active_no', '');
		} else {
			$template->set_var('active_yes', '');
			$template->set_var('active_no', ' selected');
		}
		//zoom
		$template->set_var('zoom_min_label', $LANG_MAPS_1['zoom_min_label']);
		$template->set_var('zoom_min', (int) $overlay['o_zoom_min']);
		$template->set_var('zoom_max_label', $LANG_MAPS_1['zoom_max_label']);
		$template->set_var('zoom_max', (int) $overlay['o_zoom_max']);
		
		//Image
		$template->set_var('image', $LANG_MAPS_1['image']);
		$template->set_var('image_message', $LANG_MAPS_1['image_message']);
		$overlay_image = $_MAPS_CONF['path_overlay_images'] . $overlay['o_image'];
		if (is_file($overlay_image)) {
			$overlayUrl = $_MAPS_CONF['images_overlay_url'] . rawurlencode($overlay['o_image']);
			$template->set_var('overlay_image', '<p>' . $LANG_MAPS_1['image_replace'] . '</p><p><img src="'
			    . htmlspecialchars($overlayUrl, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:350px;height:auto" /></p>');
		} else {
			$template->set_var('overlay_image', '');
		}
		
		//Form validation
		$template->set_var('save_button', $LANG_MAPS_1['save_button']);
		
		if ($overlay['oid'] > 0) {
    		$template->set_var('delete_button', '<option value="delete">' . $LANG_MAPS_1['delete_button'] . '</option>');
		} else {
		    $template->set_var('delete_button', '');
		}
		$template->set_var('ok_button', $LANG_MAPS_1['ok_button']);
		if (isset($overlay['oid'])) {
			$template->set_var('oid', '<input type="hidden" name="oid" value="' . (int) $overlay['oid'] .'" />');
		} else {
			$template->set_var('oid', '');
		}
		
		$display .= $template->parse('output', 'map');
	}

    $display .= COM_endBlock();

    return $display;
}

function MAPS_saveOverlayImage ($overlay, $FILES, $oid) {
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG24;
	
    $args = &$overlay;

    // Handle legacy slashes without using each(), removed in PHP 8.
    foreach ($args as $key => $value) {
        if (!is_array($value)) {
            $args[$key] = COM_stripslashes($value);
        } else {
            foreach ($value as $subkey => $subvalue) {
                $args[$key][$subkey] = COM_stripslashes($subvalue);
            }
        }
    }

	// OK, let's upload any pictures with the overlay
	require_once($_CONF['path_system'] . 'classes/upload.class.php');
	$upload = new upload();

	//Debug with story debug function
	if (isset ($_CONF['debug_image_upload']) && $_CONF['debug_image_upload']) {
		$upload->setLogFile ($_CONF['path'] . 'logs/error.log');
		$upload->setDebug (true);
	}
	$upload->setMaxFileUploads (1);
	if (!empty($_CONF['image_lib'])) {
		if ($_CONF['image_lib'] == 'imagemagick') {
			// Using imagemagick
			$upload->setMogrifyPath ($_CONF['path_to_mogrify']);
		} elseif ($_CONF['image_lib'] == 'netpbm') {
			// using netPBM
			$upload->setNetPBM ($_CONF['path_to_netpbm']);
		} elseif ($_CONF['image_lib'] == 'gdlib') {
			// using the GD library
			$upload->setGDLib ();
		}
		$upload->setAutomaticResize(true);
		$upload->keepOriginalImage (false);

		if (isset($_CONF['jpeg_quality'])) {
			$upload->setJpegQuality($_CONF['jpeg_quality']);
		}
	}
	$upload->setAllowedMimeTypes (array (
			'image/gif'   => '.gif',
			'image/jpeg'  => '.jpg,.jpeg',
			'image/pjpeg' => '.jpg,.jpeg',
			'image/x-png' => '.png',
			'image/png'   => '.png'
			));
	
	if (!$upload->setPath($_MAPS_CONF['path_overlay_images'])) {
		$output = COM_siteHeader ('menu', $LANG24[30]);
		$output .= COM_startBlock ($LANG24[30], '', COM_getBlockTemplate ('_msg_block', 'header'));
		$output .= $upload->printErrors (false);
		$output .= COM_endBlock (COM_getBlockTemplate ('_msg_block', 'footer'));
		$output .= COM_siteFooter ();
		MAPS_compatOutput($output);
		exit;
	}

	// NOTE: if $_CONF['path_to_mogrify'] is set, the call below will
	// force any images bigger than the passed dimensions to be resized.
	// If mogrify is not set, any images larger than these dimensions
	// will get validation errors
	$upload->setMaxDimensions($_MAPS_CONF['max_image_width'], $_MAPS_CONF['max_image_height']);
	$upload->setMaxFileSize($_MAPS_CONF['max_image_size']); // size in bytes, 1048576 = 1MB

	// Set file permissions on file after it gets uploaded (number is in octal)
	$upload->setPerms('0644');

    $filenames = '';
    $curfile = current($FILES);
    if (is_array($curfile) && !empty($curfile['name'])) {
        $fextension = strtolower(pathinfo((string) $curfile['name'], PATHINFO_EXTENSION));
        if (!in_array($fextension, array('gif', 'jpg', 'jpeg', 'png'), true)) {
            COM_errorLog('MAPS overlay upload rejected unsupported extension: ' . $fextension);
            return false;
        }
        $filenames = 'overlay_' . (int) $oid . '.' . $fextension;
    }
    if ($filenames != '') {
		$upload->setFileNames($filenames);
		reset($FILES);
		$upload->uploadFiles();

		if ($upload->areErrors()) {
			$retval = MAPS_compatSiteHeader('menu', $LANG24[30]);
			$retval .= COM_startBlock ($LANG24[30], '',
						COM_getBlockTemplate ('_msg_block', 'header'));
			$retval .= $upload->printErrors(false);
			$retval .= COM_endBlock(COM_getBlockTemplate ('_msg_block', 'footer'));
			$retval .= MAPS_compatSiteFooter();
			MAPS_compatOutput($retval);
			exit;
		}
		
		DB_query("UPDATE {$_TABLES['maps_overlays']} SET o_image = '" . $filenames . "' WHERE oid=" . $oid);
	}

	return true;
}


function MAPS_deleteOverlayImage($image)
{
    global $_MAPS_CONF;

    $image = basename((string) $image);
    if ($image === '') {
        return;
    }

    $path = $_MAPS_CONF['path_overlay_images'] . $image;
    if (is_file($path) && !@unlink($path)) {
        COM_errorLog('Unable to remove the following overlay image from maps plugin: ' . $image);
    }
}

function MAPS_selectGroupOverlays ($selected)
{
    global $_TABLES, $LANG_MAPS_1;
    
    $retval = '<b>' . $LANG_MAPS_1['group_label'] . '</b> <select name="o_group">' .
            '<option value="0">' . $LANG_MAPS_1['choose_group'] . '</option>' .
            COM_optionList( $_TABLES['maps_overlays_groups'], 'o_group_id,o_group_name', $selected) .
            '</select>'; 
    
    return $retval;
}

// MAIN
$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$requestData = $requestMethod === 'POST' ? $_POST : $_GET;
$mode = isset($requestData['mode']) ? COM_applyFilter($requestData['mode']) : 'new';
$oid = isset($requestData['oid']) ? (int) $requestData['oid'] : 0;

$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= maps_admin_menu();

if (in_array($mode, array('save', 'delete'), true)) {
    if ($requestMethod !== 'POST' || !SEC_checkToken()) {
        COM_accessLog('Rejected Maps overlay mutation because of missing or invalid CSRF token.');
        $display .= MAPS_message('Invalid or expired security token.', $LANG_MAPS_1['error']);
        $mode = $oid > 0 ? 'edit' : 'new';
    } else {
        $_REQUEST = $_POST;
    }
}

switch ($mode) {
    case 'delete':
        $affectedMaps = array();
        if ($oid > 0) {
            $affectedResult = DB_query("SELECT DISTINCT mo_mid FROM {$_TABLES['maps_map_overlay']} WHERE mo_oid=" . $oid);
            while ($affected = DB_fetchArray($affectedResult)) {
                $affectedMid = isset($affected['mo_mid']) ? (int) $affected['mo_mid'] : 0;
                if ($affectedMid > 0) {
                    $affectedMaps[$affectedMid] = true;
                }
            }
        }

        $oldImage = $oid > 0 ? DB_getItem($_TABLES['maps_overlays'], 'o_image', 'oid=' . $oid) : '';
        if ($oid > 0) {
            DB_delete($_TABLES['maps_map_overlay'], 'mo_oid', $oid);
            DB_delete($_TABLES['maps_overlays'], 'oid', $oid);
        }

        if ($oid > 0 && (int) DB_count($_TABLES['maps_overlays'], 'oid', $oid) === 0) {
            MAPS_deleteOverlayImage($oldImage);
            foreach (array_keys($affectedMaps) as $affectedMid) {
                updateMap((int) $affectedMid);
            }
            $msg = $LANG_MAPS_1['deletion_succes'];
        } else {
            $msg = $LANG_MAPS_1['deletion_fail'];
        }

        echo COM_refresh($_CONF['site_admin_url'] . "/plugins/maps/overlays.php?msg=" . urlencode($msg));
        exit;

    case 'save':
        $post = is_array($_POST) ? $_POST : array();
        $name = trim(isset($post['name']) ? (string) $post['name'] : '');
        if ($name === '') {
            $display .= COM_startBlock($LANG_MAPS_1['error'], '', 'blockheader-message.thtml');
            $display .= $LANG_MAPS_1['missing_field'];
            $display .= COM_endBlock('blockfooter-message.thtml');
            $display .= MAPS_getOverlayForm($post);
            break;
        }

        $swLat = MAPS_latitude(isset($post['sw_lat']) ? $post['sw_lat'] : 0, 0);
        $swLng = MAPS_longitude(isset($post['sw_lng']) ? $post['sw_lng'] : 0, 0);
        $neLat = MAPS_latitude(isset($post['ne_lat']) ? $post['ne_lat'] : 0, 0);
        $neLng = MAPS_longitude(isset($post['ne_lng']) ? $post['ne_lng'] : 0, 0);
        $active = !empty($post['active']) ? 1 : 0;
        $group = isset($post['o_group']) ? (int) $post['o_group'] : 0;
        $zoomMin = MAPS_zoom(isset($post['zoom_min']) ? $post['zoom_min'] : 0, 0);
        $zoomMax = MAPS_zoom(isset($post['zoom_max']) ? $post['zoom_max'] : 21, 21);
        if ($zoomMin > $zoomMax) {
            $tmp = $zoomMin;
            $zoomMin = $zoomMax;
            $zoomMax = $tmp;
        }

        $affectedMaps = array();
        if ($oid > 0) {
            $affectedResult = DB_query("SELECT DISTINCT mo_mid FROM {$_TABLES['maps_map_overlay']} WHERE mo_oid=" . $oid);
            while ($affected = DB_fetchArray($affectedResult)) {
                $affectedMid = isset($affected['mo_mid']) ? (int) $affected['mo_mid'] : 0;
                if ($affectedMid > 0) {
                    $affectedMaps[$affectedMid] = true;
                }
            }
        }

        $sqlValues = "o_name='" . MAPS_dbEscape($name) . "', "
            . "o_active=" . $active . ", "
            . "o_group=" . $group . ", "
            . "o_sw_lat='" . MAPS_dbEscape(MAPS_canonicalNumberString($swLat, '0')) . "', "
            . "o_sw_lng='" . MAPS_dbEscape(MAPS_canonicalNumberString($swLng, '0')) . "', "
            . "o_ne_lat='" . MAPS_dbEscape(MAPS_canonicalNumberString($neLat, '0')) . "', "
            . "o_ne_lng='" . MAPS_dbEscape(MAPS_canonicalNumberString($neLng, '0')) . "', "
            . "o_zoom_min=" . (int) $zoomMin . ", "
            . "o_zoom_max=" . (int) $zoomMax;

        if ($oid > 0) {
            if ((int) DB_count($_TABLES['maps_overlays'], 'oid', $oid) !== 1) {
                $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $display .= MAPS_getOverlayForm($post);
                break;
            }
            DB_query("UPDATE {$_TABLES['maps_overlays']} SET " . $sqlValues . " WHERE oid=" . $oid);
        } else {
            DB_query("INSERT INTO {$_TABLES['maps_overlays']} SET " . $sqlValues);
            $oid = (int) DB_insertId();
        }

        if (DB_error() || $oid <= 0) {
            $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
            $display .= MAPS_getOverlayForm(array_merge($post, array('oid' => $oid)));
            break;
        }

        if (!empty($_FILES) && isset($_FILES['file']) && !empty($_FILES['file']['name'])) {
            if (!MAPS_saveOverlayImage($post, $_FILES, $oid)) {
                $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $display .= MAPS_getOverlayForm(array_merge($post, array('oid' => $oid)));
                break;
            }
        }

        foreach (array_keys($affectedMaps) as $affectedMid) {
            updateMap((int) $affectedMid);
        }

        echo COM_refresh($_CONF['site_admin_url'] . "/plugins/maps/overlays.php?msg=" . urlencode($LANG_MAPS_1['save_success']));
        exit;

    case 'edit':
        if ($oid > 0 && (int) DB_count($_TABLES['maps_overlays'], 'oid', $oid) === 1) {
            $res = DB_query("SELECT * FROM {$_TABLES['maps_overlays']} WHERE oid=" . $oid . " LIMIT 1");
            $overlay = DB_fetchArray($res);
            $display .= MAPS_getOverlayForm($overlay);
        } else {
            echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/overlays.php');
            exit;
        }
        break;

    case 'new':
    default:
        $display .= MAPS_getOverlayForm(array());
        break;
}

$display .= MAPS_compatSiteFooter(0);
MAPS_compatOutput($display);
