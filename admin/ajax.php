<?php
// +--------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                        |
// +--------------------------------------------------------------------------+
// | ajax.php                                                                 |
// +--------------------------------------------------------------------------+
// | Copyright (C) 2011-2026 by the following authors:                        |
// |                                                                          |
// | Authors: ::Ben - cordiste AT free DOT fr                                 |
// +--------------------------------------------------------------------------+
// |                                                                          |
// | This program is free software; you can redistribute it and/or            |
// | modify it under the terms of the GNU General Public License              |
// | as published by the Free Software Foundation; either version 2           |
// | of the License, or (at your option) any later version.                   |
// |                                                                          |
// | This program is distributed in the hope that it will be useful,          |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
// | GNU General Public License for more details.                             |
// |                                                                          |
// | You should have received a copy of the GNU General Public License        |
// | along with this program; if not, write to the Free Software Foundation,  |
// | Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.          |
// |                                                                          |
// +--------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once 'edit_functions.php';

if (!SEC_hasRights('maps.admin')) {
    exit;
}

// Incoming variable filter
$vars = array(
    'action' => 'alpha',
    'id' => 'number',
    'mid' => 'number'
);
MAPS_filterVars($vars, $_POST);

/**
 * Mark a map as modified after a successful change to one of its rendered
 * dependencies and notify Geeklog consumers through the normal lifecycle API.
 *
 * @param int $mid
 * @return void
 */
function MAPS_touchMapAfterDependencyChange($mid)
{
    global $_TABLES;

    $mid = (int) $mid;
    if ($mid <= 0 || (int) DB_count($_TABLES['maps_maps'], 'mid', $mid) !== 1) {
        return;
    }

    $modified = MAPS_dbEscape(date('Y-m-d H:i:s'));
    DB_query(
        "UPDATE {$_TABLES['maps_maps']} SET modified='{$modified}' WHERE mid={$mid}"
    );

    if (function_exists('PLG_itemSaved')) {
        PLG_itemSaved($mid, 'maps');
    }
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$mid = isset($_POST['mid']) ? (int) $_POST['mid'] : 0;
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

switch ($action) {
    case 'delete':
        // Resolve the map from the stored relation instead of trusting the
        // client-provided mid. This also lets us distinguish a real deletion
        // from a stale/no-op request before emitting a lifecycle event.
        $storedMid = (int) DB_getItem(
            $_TABLES['maps_map_overlay'],
            'mo_mid',
            'mo_id=' . $id
        );
        if ($storedMid > 0) {
            DB_delete($_TABLES['maps_map_overlay'], 'mo_id', $id);
            if ((int) DB_count($_TABLES['maps_map_overlay'], 'mo_id', $id) === 0) {
                MAPS_touchMapAfterDependencyChange($storedMid);
                $mid = $storedMid;
            }
        }

        echo '<div id="overlays_actions"><div id="overlays_list">' . MAPS_displayOverlays($mid) . '</div>';
        echo "<script type=\"text/javascript\">jQuery(document).ready(function() {
        jQuery('#load').hide();
        });

        jQuery(function() {
            jQuery(\".delete\").click(function() {
                jQuery('#load').show();
                var id = jQuery(this).attr(\"id\");
                var mid = jQuery(this).attr(\"mid\");
                var oid = jQuery(this).attr(\"oid\");
                var action = jQuery(this).attr(\"class\");
                var string = 'id='+ id + '&action=' + action + '&mid=' + mid;

                jQuery.ajax({
                    type: \"POST\",
                    url: \"ajax.php\",
                    data: string,
                    cache: false,
                    async:false,
                    success: function(result){
                        jQuery(\"#overlays_actions\").replaceWith(result);
                    }
                });
                jQuery('#load').hide();
                return false;
            });
            jQuery(\".add\").click(function() {
                jQuery('#load').show();
                var id = jQuery(this).attr(\"id\");
                var mid = jQuery(this).attr(\"mid\");
                var oid = jQuery(this).attr(\"oid\");
                var action = jQuery(this).attr(\"class\");
                var string = 'id='+ id + '&action=' + action + '&mid=' + mid;

                jQuery.ajax({
                    type: \"POST\",
                    url: \"ajax.php\",
                    data: string,
                    cache: false,
                    async:false,
                    success: function(result){
                        jQuery(\"#overlays_actions\").replaceWith(result);
                    }
                });
                jQuery('#load').hide();
                return false;
            });
        });
    </script>";
        echo '<div id="overlays_list">' . MAPS_displayOverlaysToAdd($mid) . '</div>';
        break;

    case 'add':
        if ($mid > 0 && $id > 0) {
            $before = (int) DB_count(
                $_TABLES['maps_map_overlay'],
                array('mo_mid', 'mo_oid'),
                array($mid, $id)
            );
            if ($before === 0) {
                $sql = "INSERT INTO {$_TABLES['maps_map_overlay']} SET "
                    . "mo_mid={$mid}, mo_oid={$id}";
                DB_query($sql);
                $after = (int) DB_count(
                    $_TABLES['maps_map_overlay'],
                    array('mo_mid', 'mo_oid'),
                    array($mid, $id)
                );
                if ($after > 0) {
                    MAPS_touchMapAfterDependencyChange($mid);
                }
            }
        }

        echo '<div id="overlays_actions"><div id="overlays_list">' . MAPS_displayOverlays($mid) . '</div>';
        echo "<script type=\"text/javascript\">jQuery(document).ready(function() {
        jQuery('#load').hide();
        });

        jQuery(function() {
            jQuery(\".delete\").click(function() {
                jQuery('#load').show();
                var id = jQuery(this).attr(\"id\");
                var mid = jQuery(this).attr(\"mid\");
                var oid = jQuery(this).attr(\"oid\");
                var action = jQuery(this).attr(\"class\");
                var string = 'id='+ id + '&action=' + action + '&mid=' + mid;

                jQuery.ajax({
                    type: \"POST\",
                    url: \"ajax.php\",
                    data: string,
                    cache: false,
                    async:false,
                    success: function(result){
                        jQuery(\"#overlays_actions\").replaceWith(result);
                    }
                });
                jQuery('#load').hide();
                return false;
            });
            jQuery(\".add\").click(function() {
                jQuery('#load').show();
                var id = jQuery(this).attr(\"id\");
                var mid = jQuery(this).attr(\"mid\");
                var oid = jQuery(this).attr(\"oid\");
                var action = jQuery(this).attr(\"class\");
                var string = 'id='+ id + '&action=' + action + '&mid=' + mid;

                jQuery.ajax({
                    type: \"POST\",
                    url: \"ajax.php\",
                    data: string,
                    cache: false,
                    async:false,
                    success: function(result){
                        jQuery(\"#overlays_actions\").replaceWith(result);
                    }
                });
                jQuery('#load').hide();
                return false;
            });
        });
    </script>";
        echo '<div id="overlays_list">' . MAPS_displayOverlaysToAdd($mid) . '</div>';
        break;
}
