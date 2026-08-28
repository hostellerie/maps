<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | markers.php                                                               |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2014-2026 by the following authors:                              |
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

/**
* List all markers that the user has access to
*
* @retun    string      HTML for the list
*
*/
function MAPS_listMarkersAdmin()
{
    global $_CONF, $_TABLES, $_IMAGE_TYPE, $LANG_ADMIN, $LANG_MAPS_1;

    require_once $_CONF['path_system'] . 'lib-admin.php';

    $retval = '';
	
	if (DB_count($_TABLES['maps_markers']) == 0){
	return $retval = '';
	}

    $header_arr = array(      // display 'text' and use table field 'field'
        array('text' => $LANG_MAPS_1['id'], 'field' => 'mkid', 'sort' => true),
        array('text' => $LANG_MAPS_1['name'], 'field' => 'sort_name', 'sort' => true),
		array('text' => $LANG_MAPS_1['map_label'], 'field' => 'mapname', 'sort' => true),
        array('text' => $LANG_MAPS_1['active_field'], 'field' => 'active', 'sort' => true),
        array('text' => $LANG_MAPS_1['hidden_field'], 'field' => 'hidden', 'sort' => true),
        array('text' => $LANG_ADMIN['edit'], 'field' => 'edit', 'sort' => false),
    );
    
	$defsort_arr = array('field' => 'modified', 'direction' => 'desc');

    $text_arr = array(
        'has_extras' => true,
        'form_url' => $_CONF['site_admin_url'] . '/plugins/maps/markers.php'
    );
	
	$sql = "SELECT
	            a.*, LOWER(TRIM(a.name)) AS sort_name, b.name as mapname
            FROM {$_TABLES['maps_markers']} AS a
			LEFT JOIN
			     {$_TABLES['maps_maps']} AS b
			ON a.mid = b.mid
			WHERE 1=1";
	
    $query_arr = array(
        'sql'            => $sql,
        'query_fields'   => array('a.name', 'a.address', 'b.name'),
        'default_filter' => COM_getPermSQL ('AND', 0, 3)
    );

    $retval .= ADMIN_list('markers', 'plugin_getListField_markers',
                          $header_arr, $text_arr, $query_arr, $defsort_arr);

    return $retval;
}

/**
*   Get an individual field for the markers screen.
*
*   @param  string  $fieldname  Name of field (from the array, not the db)
*   @param  mixed   $fieldvalue Value of the field
*   @param  array   $A          Array of all fields from the database
*   @param  array   $icon_arr   System icon array
*   @param  object  $EntryList  This entry list object
*   @return string              HTML for field display in the table
*/
function plugin_getListField_markers($fieldname, $fieldvalue, $A, $icon_arr)
{
    global $_CONF, $_MAPS_CONF, $LANG_ADMIN, $LANG_STATIC, $LANG_MAPS_1, $_TABLES;

    switch($fieldname) {
        case "edit":
            $retval = COM_createLink($icon_arr['edit'],
                "{$_CONF['site_admin_url']}/plugins/maps/marker_edit.php?mode=edit&mkid={$A['mkid']}");
            break;
        case "sort_name":
            $map_title = MAPS_normalizeMarkerText($A['name']);
            $url = $_MAPS_CONF['site_url'] .
                                 '/markers.php?mode=show&mkid=' . $A['mkid'] . '&mid=' . $A['mid'];
            $retval = COM_createLink($map_title, $url, array('title'=>$LANG_MAPS_1['title_display']));
            break;

        case "id":
            $retval = $A['mkid'];
            break;
        case "active":
            $retval = MAPS_adminStatusBadge(
                $fieldvalue,
                $LANG_MAPS_1['status_active'],
                $LANG_MAPS_1['status_inactive']
            );
            break;
        case "hidden":
            $retval = MAPS_adminStatusBadge(
                $fieldvalue,
                $LANG_MAPS_1['status_hidden'],
                $LANG_MAPS_1['status_visible'],
                'is-warning',
                'is-positive'
            );
            break;
        default:
            $retval = htmlspecialchars(MAPS_decodeStoredText($fieldvalue), ENT_QUOTES, 'UTF-8');
            break;
    }
    return $retval;
}

// MAIN
$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= MAPS_admin_menu();

$display .= '<h1 class="maps-admin-title">' . $LANG_MAPS_1['markers_list'] . '</h1>';
$display .= '<p class="maps-list-actions"><a class="maps-primary-action" href="' . $_CONF['site_admin_url'] . '/plugins/maps/marker_edit.php">' . htmlspecialchars($LANG_MAPS_1['create_marker'], ENT_QUOTES, 'UTF-8') . '</a></p>';

$display .= MAPS_listMarkersAdmin();

$display .= MAPS_compatSiteFooter(0);

MAPS_compatOutput($display);

?>
