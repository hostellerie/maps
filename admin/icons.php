<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maintainer: ::Ben                                                         |
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | icons.php                                                                 |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';

$display = '';

if (!SEC_hasRights('maps.admin')) {
    $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
        . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
        . MAPS_compatSiteFooter();
    COM_accessLog("User {$_USER['username']} tried to illegally access the Maps plugin administration screen.");
    MAPS_compatOutput($display);
    exit;
}

MAPS_getheadercode();

function MAPS_listIcons()
{
    global $_CONF, $_TABLES, $LANG_ADMIN, $LANG_MAPS_1;

    require_once $_CONF['path_system'] . 'lib-admin.php';

    $header = array(
        array('text' => $LANG_MAPS_1['id'], 'field' => 'icon_id', 'sort' => true),
        array('text' => $LANG_MAPS_1['icons'], 'field' => 'icon_name', 'sort' => true),
        array('text' => $LANG_MAPS_1['image'], 'field' => 'icon_image', 'sort' => false)
    );
    $text = array(
        'has_extras' => true,
        'form_url' => $_CONF['site_admin_url'] . '/plugins/maps/icons.php'
    );
    $query = array(
        'sql' => "SELECT * FROM {$_TABLES['maps_map_icons']} WHERE 1=1",
        'query_fields' => array('icon_name'),
        'default_filter' => ''
    );
    $sort = array('field' => 'icon_name', 'direction' => 'asc');

    return ADMIN_list('icons', 'MAPS_getListField_icons', $header, $text, $query, $sort);
}

function MAPS_getListField_icons($fieldname, $fieldvalue, $icon, $icon_arr)
{
    global $_CONF, $_MAPS_CONF;

    switch ($fieldname) {
        case 'icon_id':
            return COM_createLink(
                $icon_arr['edit'],
                $_CONF['site_admin_url'] . '/plugins/maps/icons.php?mode=edit&id=' . (int) $icon['icon_id']
            );

        case 'icon_name':
            return htmlspecialchars((string) $fieldvalue, ENT_QUOTES, 'UTF-8');

        case 'icon_image':
            $filename = basename((string) $fieldvalue);
            $path = $_MAPS_CONF['path_icons_images'] . $filename;
            if ($filename !== '' && is_file($path)) {
                $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename)
                    . '?v=' . (int) @filemtime($path);
                return '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
                    . '" alt="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '">';
            }
            return '';

        default:
            return htmlspecialchars((string) $fieldvalue, ENT_QUOTES, 'UTF-8');
    }
}

function MAPS_getIconForm($icon = array())
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    $defaults = array('icon_id' => 0, 'icon_name' => '', 'icon_image' => '');
    $icon = array_merge($defaults, is_array($icon) ? $icon : array());

    $display = '<h1 class="maps-admin-title">' . htmlspecialchars($LANG_MAPS_1['icon_edit'], ENT_QUOTES, 'UTF-8') . ($icon['icon_name'] !== '' ? ': ' . htmlspecialchars($icon['icon_name'], ENT_QUOTES, 'UTF-8') : '') . '</h1>';
    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('icon' => 'icon_form.thtml'));
    $template->set_var('site_admin_url', $_CONF['site_admin_url']);

    $token = SEC_createToken();
    $template->set_var(
        'csrf_token',
        '<input type="hidden" name="' . CSRF_TOKEN . '" value="'
        . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">'
    );

    $template->set_var('yes', $LANG_MAPS_1['yes']);
    $template->set_var('no', $LANG_MAPS_1['no']);
    $template->set_var('icon_presentation', $LANG_MAPS_1['icon_presentation']);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['icon_name_label']);
    $template->set_var('name', htmlspecialchars(stripslashes((string) $icon['icon_name']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('image', $LANG_MAPS_1['image']);
    $isFrench = isset($_CONF['language']) && strpos(strtolower($_CONF['language']), 'french') === 0;
    $uploadHelp = $isFrench
        ? 'Formats autorisés : GIF, JPG/JPEG, PNG et WebP. Dimensions maximales : 128 × 128 px. Les images plus grandes sont redimensionnées automatiquement si une bibliothèque d\'images Geeklog est configurée.'
        : 'Allowed formats: GIF, JPG/JPEG, PNG and WebP. Maximum dimensions: 128 × 128 px. Larger images are resized automatically when a Geeklog image library is configured.';
    $template->set_var(
        'image_message',
        htmlspecialchars($LANG_MAPS_1['image_message'], ENT_QUOTES, 'UTF-8')
        . '<br><small>' . htmlspecialchars($uploadHelp, ENT_QUOTES, 'UTF-8') . '</small>'
    );

    $filename = basename((string) $icon['icon_image']);
    $path = $_MAPS_CONF['path_icons_images'] . $filename;
    if ($filename !== '' && is_file($path)) {
        $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename)
            . '?v=' . (int) @filemtime($path);
        $template->set_var(
            'icon_image',
            '<p>' . htmlspecialchars($LANG_MAPS_1['image_replace'], ENT_QUOTES, 'UTF-8') . '</p>'
            . '<p><img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
            . '" alt="' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '"></p>'
        );
    } else {
        $template->set_var('icon_image', '');
    }

    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_action', (int) $icon['icon_id'] > 0 ? '<button type="submit" name="mode" value="delete" class="maps-danger-action">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>' : '');
    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);
    if ((int) $icon['icon_id'] > 0) {
        $template->set_var(
            'delete_button',
            '<option value="delete">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</option>'
        );
        $template->set_var(
            'id',
            '<input type="hidden" name="id" value="' . (int) $icon['icon_id'] . '">'
        );
    } else {
        $template->set_var('delete_button', '');
        $template->set_var('id', '');
    }
    $template->set_var('xhtml', XHTML);

    $display .= $template->parse('output', 'icon');
    return $display;
}

function MAPS_getMapsUsingIcon($iconId)
{
    global $_TABLES;

    $maps = array();
    $iconId = (int) $iconId;
    if ($iconId <= 0) {
        return $maps;
    }

    $result = DB_query(
        "SELECT DISTINCT mid FROM {$_TABLES['maps_markers']} WHERE mk_icon=" . $iconId
    );
    while ($row = DB_fetchArray($result)) {
        $mid = isset($row['mid']) ? (int) $row['mid'] : 0;
        if ($mid > 0) {
            $maps[$mid] = true;
        }
    }

    $result = DB_query(
        "SELECT mid FROM {$_TABLES['maps_maps']} WHERE mmk_icon=" . $iconId
    );
    while ($row = DB_fetchArray($result)) {
        $mid = isset($row['mid']) ? (int) $row['mid'] : 0;
        if ($mid > 0) {
            $maps[$mid] = true;
        }
    }

    return $maps;
}

function MAPS_deleteIconImage($image)
{
    global $_MAPS_CONF;

    $image = basename((string) $image);
    if ($image === '') {
        return;
    }

    $path = $_MAPS_CONF['path_icons_images'] . $image;
    if (is_file($path) && !@unlink($path)) {
        COM_errorLog('Unable to remove the following icon image from Maps plugin: ' . $image);
    }
}

function MAPS_saveIconImage($files, $id, &$errorMessage)
{
    global $_CONF, $_MAPS_CONF, $_TABLES;

    $errorMessage = '';
    $id = (int) $id;
    if ($id <= 0 || !isset($files['file']) || empty($files['file']['name'])) {
        return true;
    }

    $extension = strtolower(pathinfo((string) $files['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, array('gif', 'jpg', 'jpeg', 'png', 'webp'), true)) {
        $errorMessage = 'Unsupported image format. Allowed formats: GIF, JPG/JPEG, PNG, WebP.';
        COM_errorLog('MAPS icon upload rejected unsupported extension: ' . $extension);
        return false;
    }

    require_once $_CONF['path_system'] . 'classes/upload.class.php';
    $upload = new upload();
    if (!empty($_CONF['debug_image_upload'])) {
        $upload->setLogFile($_CONF['path'] . 'logs/error.log');
        $upload->setDebug(true);
    }
    $upload->setMaxFileUploads(1);

    if (!empty($_CONF['image_lib'])) {
        if ($_CONF['image_lib'] === 'imagemagick') {
            $upload->setMogrifyPath($_CONF['path_to_mogrify']);
        } elseif ($_CONF['image_lib'] === 'netpbm') {
            $upload->setNetPBM($_CONF['path_to_netpbm']);
        } elseif ($_CONF['image_lib'] === 'gdlib') {
            $upload->setGDLib();
        }
        $upload->setAutomaticResize(true);
        $upload->keepOriginalImage(false);
        if (isset($_CONF['jpeg_quality'])) {
            $upload->setJpegQuality($_CONF['jpeg_quality']);
        }
    }

    $upload->setAllowedMimeTypes(array(
        'image/gif' => '.gif',
        'image/jpeg' => '.jpg,.jpeg',
        'image/pjpeg' => '.jpg,.jpeg',
        'image/x-png' => '.png',
        'image/png' => '.png',
        'image/webp' => '.webp'
    ));

    if (!$upload->setPath($_MAPS_CONF['path_icons_images'])) {
        $errorMessage = 'The icon image directory is not writable.';
        COM_errorLog('MAPS icon upload path is not writable: ' . $_MAPS_CONF['path_icons_images']);
        return false;
    }

    $iconMaxWidth = 128;
    $iconMaxHeight = 128;
    if (!empty($_MAPS_CONF['max_image_width']) && (int) $_MAPS_CONF['max_image_width'] < $iconMaxWidth) {
        $iconMaxWidth = (int) $_MAPS_CONF['max_image_width'];
    }
    if (!empty($_MAPS_CONF['max_image_height']) && (int) $_MAPS_CONF['max_image_height'] < $iconMaxHeight) {
        $iconMaxHeight = (int) $_MAPS_CONF['max_image_height'];
    }
    $upload->setMaxDimensions($iconMaxWidth, $iconMaxHeight);
    $upload->setMaxFileSize($_MAPS_CONF['max_image_size']);
    $upload->setPerms('0644');

    $filename = 'icon_' . $id . '.' . $extension;
    $oldImage = DB_getItem($_TABLES['maps_map_icons'], 'icon_image', 'icon_id=' . $id);
    $upload->setFileNames($filename);
    $upload->uploadFiles();

    if ($upload->areErrors()) {
        $uploadError = trim(strip_tags($upload->printErrors(false)));
        $errorMessage = $uploadError !== ''
            ? $uploadError
            : 'The icon image could not be uploaded.';
        COM_errorLog('MAPS icon upload failed: ' . $errorMessage);
        return false;
    }

    DB_query(
        "UPDATE {$_TABLES['maps_map_icons']} SET icon_image='"
        . MAPS_dbEscape($filename) . "' WHERE icon_id=" . $id
    );
    if (DB_error()) {
        $errorMessage = 'The icon image was uploaded but its filename could not be saved.';
        return false;
    }

    if ($oldImage !== '' && basename((string) $oldImage) !== $filename) {
        MAPS_deleteIconImage($oldImage);
    }

    return true;
}

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
$requestData = $requestMethod === 'POST' ? $_POST : $_GET;
$mode = isset($requestData['mode']) ? COM_applyFilter($requestData['mode']) : '';
$id = isset($requestData['id']) ? (int) $requestData['id'] : 0;

$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= MAPS_admin_menu();

if (!empty($requestData['msg'])) {
    $display .= MAPS_message(COM_applyFilter($requestData['msg']));
}

if (!MAPS_ensureWritableDirectory($_MAPS_CONF['path_icons_images'])) {
    $display .= MAPS_message(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_icons_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . htmlspecialchars($LANG_MAPS_1['icons_not_writable'], ENT_QUOTES, 'UTF-8') . '</p>',
        $LANG_MAPS_1['error']
    );
} else {
    if (in_array($mode, array('save', 'delete'), true)) {
        if ($requestMethod !== 'POST' || !SEC_checkToken()) {
            COM_accessLog('Rejected Maps icon mutation because of missing or invalid CSRF token.');
            $display .= MAPS_message('Invalid or expired security token.', $LANG_MAPS_1['error']);
            $mode = $id > 0 ? 'edit' : '';
        }
    }

    switch ($mode) {
        case 'delete':
            $affectedMaps = MAPS_getMapsUsingIcon($id);
            $oldImage = $id > 0
                ? DB_getItem($_TABLES['maps_map_icons'], 'icon_image', 'icon_id=' . $id)
                : '';

            if ($id > 0 && (int) DB_count($_TABLES['maps_map_icons'], 'icon_id', $id) === 1) {
                DB_query(
                    "UPDATE {$_TABLES['maps_markers']} SET mk_icon=0, mk_default=1 WHERE mk_icon=" . $id
                );
                DB_query(
                    "UPDATE {$_TABLES['maps_maps']} SET mmk_icon=0, mmk_default=1 WHERE mmk_icon=" . $id
                );
                DB_delete($_TABLES['maps_map_icons'], 'icon_id', $id);
            }

            if ($id > 0 && (int) DB_count($_TABLES['maps_map_icons'], 'icon_id', $id) === 0) {
                MAPS_deleteIconImage($oldImage);
                foreach (array_keys($affectedMaps) as $mid) {
                    updateMap((int) $mid);
                }
                $msg = $LANG_MAPS_1['deletion_succes'];
            } else {
                $msg = $LANG_MAPS_1['deletion_fail'];
            }

            echo COM_refresh(
                $_CONF['site_admin_url'] . '/plugins/maps/icons.php?msg=' . urlencode($msg)
            );
            exit;

        case 'save':
            $post = is_array($_POST) ? $_POST : array();
            $name = trim(isset($post['icon_name']) ? (string) $post['icon_name'] : '');
            if ($name === '') {
                $display .= MAPS_message(
                    htmlspecialchars($LANG_MAPS_1['missing_field'], ENT_QUOTES, 'UTF-8'),
                    $LANG_MAPS_1['error']
                );
                $display .= MAPS_getIconForm(array_merge($post, array('icon_id' => $id)));
                break;
            }

            $affectedMaps = $id > 0 ? MAPS_getMapsUsingIcon($id) : array();
            $escapedName = MAPS_dbEscape($name);
            $createdNew = false;

            if ($id > 0) {
                if ((int) DB_count($_TABLES['maps_map_icons'], 'icon_id', $id) !== 1) {
                    $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                    $display .= MAPS_getIconForm(array_merge($post, array('icon_id' => $id)));
                    break;
                }
                DB_query(
                    "UPDATE {$_TABLES['maps_map_icons']} SET icon_name='"
                    . $escapedName . "' WHERE icon_id=" . $id
                );
            } else {
                DB_query(
                    "INSERT INTO {$_TABLES['maps_map_icons']} SET icon_name='" . $escapedName . "'"
                );
                $id = (int) DB_insertId();
                $createdNew = true;
            }

            if (DB_error() || $id <= 0) {
                $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $display .= MAPS_getIconForm(array_merge($post, array('icon_id' => $id)));
                break;
            }

            $iconUploadError = '';
            if (!MAPS_saveIconImage($_FILES, $id, $iconUploadError)) {
                if ($createdNew) {
                    DB_delete($_TABLES['maps_map_icons'], 'icon_id', $id);
                }
                $message = $LANG_MAPS_1['save_fail'];
                if ($iconUploadError !== '') {
                    $message .= ' ' . $iconUploadError;
                }
                $display .= MAPS_message(
                    htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
                    $LANG_MAPS_1['error']
                );
                $display .= MAPS_getIconForm(array_merge($post, array('icon_id' => $id)));
                break;
            }

            foreach (array_keys($affectedMaps) as $mid) {
                updateMap((int) $mid);
            }

            echo COM_refresh(
                $_CONF['site_admin_url'] . '/plugins/maps/icons.php?msg=' . urlencode($LANG_MAPS_1['save_success'])
            );
            exit;

        case 'edit':
            if ($id > 0 && (int) DB_count($_TABLES['maps_map_icons'], 'icon_id', $id) === 1) {
                $result = DB_query(
                    "SELECT * FROM {$_TABLES['maps_map_icons']} WHERE icon_id=" . $id . ' LIMIT 1'
                );
                $icon = DB_fetchArray($result);
                $display .= MAPS_getIconForm($icon);
            } else {
                $display .= MAPS_getIconForm(array());
            }
            break;

        default:
            $display .= '<h1 class="maps-admin-title">' . htmlspecialchars($LANG_MAPS_1['icons_list'], ENT_QUOTES, 'UTF-8') . '</h1>';
            $display .= '<p class="maps-list-actions"><a class="maps-primary-action" href="'
                . htmlspecialchars($_CONF['site_admin_url'], ENT_QUOTES, 'UTF-8')
                . '/plugins/maps/icons.php?mode=edit">'
                . htmlspecialchars($LANG_MAPS_1['create_icon'], ENT_QUOTES, 'UTF-8') . '</a></p>';
            $display .= MAPS_listIcons();
            break;
    }
}

$display .= MAPS_compatSiteFooter(0);
MAPS_compatOutput($display);
