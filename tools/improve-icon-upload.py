from pathlib import Path


def replace_required(text, old, new, label):
    if old not in text:
        raise SystemExit('Missing anchor: ' + label)
    return text.replace(old, new, 1)

p = Path('admin/icons.php')
s = p.read_text(encoding='utf-8')

old = """    $template->set_var('image', $LANG_MAPS_1['image']);
    $template->set_var('image_message', $LANG_MAPS_1['image_message']);
"""
new = """    $template->set_var('image', $LANG_MAPS_1['image']);
    $isFrench = isset($_CONF['language']) && strpos(strtolower($_CONF['language']), 'french') === 0;
    $uploadHelp = $isFrench
        ? 'Formats autorisés : GIF, JPG/JPEG, PNG et WebP. Dimensions maximales : 128 × 128 px. Les images plus grandes sont redimensionnées automatiquement si une bibliothèque d\'images Geeklog est configurée.'
        : 'Allowed formats: GIF, JPG/JPEG, PNG and WebP. Maximum dimensions: 128 × 128 px. Larger images are resized automatically when a Geeklog image library is configured.';
    $template->set_var(
        'image_message',
        htmlspecialchars($LANG_MAPS_1['image_message'], ENT_QUOTES, 'UTF-8')
        . '<br><small>' . htmlspecialchars($uploadHelp, ENT_QUOTES, 'UTF-8') . '</small>'
    );
"""
s = replace_required(s, old, new, 'icon upload help')

s = replace_required(
    s,
    'function MAPS_saveIconImage($files, $id)\n{',
    'function MAPS_saveIconImage($files, $id, &$errorMessage)\n{',
    'save icon signature'
)

old = """    $id = (int) $id;
    if ($id <= 0 || !isset($files['file']) || empty($files['file']['name'])) {
        return true;
    }

    $extension = strtolower(pathinfo((string) $files['file']['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, array('gif', 'jpg', 'jpeg', 'png'), true)) {
        COM_errorLog('MAPS icon upload rejected unsupported extension: ' . $extension);
        return false;
    }
"""
new = """    $errorMessage = '';
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
"""
s = replace_required(s, old, new, 'allowed extensions')

old = """        'image/x-png' => '.png',
        'image/png' => '.png'
    ));
"""
new = """        'image/x-png' => '.png',
        'image/png' => '.png',
        'image/webp' => '.webp'
    ));
"""
s = replace_required(s, old, new, 'webp mime')

old = """    if (!$upload->setPath($_MAPS_CONF['path_icons_images'])) {
        COM_errorLog('MAPS icon upload path is not writable: ' . $_MAPS_CONF['path_icons_images']);
        return false;
    }

    $upload->setMaxDimensions($_MAPS_CONF['max_image_width'], $_MAPS_CONF['max_image_height']);
    $upload->setMaxFileSize($_MAPS_CONF['max_image_size']);
"""
new = """    if (!$upload->setPath($_MAPS_CONF['path_icons_images'])) {
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
"""
s = replace_required(s, old, new, 'icon dimensions')

old = """    if ($upload->areErrors()) {
        COM_errorLog('MAPS icon upload failed: ' . strip_tags($upload->printErrors(false)));
        return false;
    }
"""
new = """    if ($upload->areErrors()) {
        $uploadError = trim(strip_tags($upload->printErrors(false)));
        $errorMessage = $uploadError !== ''
            ? $uploadError
            : 'The icon image could not be uploaded.';
        COM_errorLog('MAPS icon upload failed: ' . $errorMessage);
        return false;
    }
"""
s = replace_required(s, old, new, 'upload error detail')

old = """    if (DB_error()) {
        return false;
    }
"""
new = """    if (DB_error()) {
        $errorMessage = 'The icon image was uploaded but its filename could not be saved.';
        return false;
    }
"""
s = replace_required(s, old, new, 'db error detail')

old = """            if (!MAPS_saveIconImage($_FILES, $id)) {
                if ($createdNew) {
                    DB_delete($_TABLES['maps_map_icons'], 'icon_id', $id);
                }
                $display .= MAPS_message($LANG_MAPS_1['save_fail'], $LANG_MAPS_1['error']);
                $display .= MAPS_getIconForm(array_merge($post, array('icon_id' => $id)));
                break;
            }
"""
new = """            $iconUploadError = '';
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
"""
s = replace_required(s, old, new, 'save error presentation')

p.write_text(s, encoding='utf-8')

p = Path('templates/icon_form.thtml')
s = p.read_text(encoding='utf-8')
s = replace_required(
    s,
    '<input type="file" dir="ltr" name="file"{xhtml}>',
    '<input type="file" dir="ltr" name="file" accept=".gif,.jpg,.jpeg,.png,.webp,image/gif,image/jpeg,image/png,image/webp"{xhtml}>',
    'file accept types'
)
p.write_text(s, encoding='utf-8')
