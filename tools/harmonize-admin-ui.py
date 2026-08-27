from pathlib import Path


def replace_required(text, old, new, label):
    if old not in text:
        raise SystemExit('Missing anchor: ' + label)
    return text.replace(old, new, 1)

# Admin menu: geolocation is no longer a GET mutation. Point to the secure
# POST action exposed in the administration help instead.
p = Path('templates/admin_menu.thtml')
s = p.read_text(encoding='utf-8')
s = replace_required(
    s,
    '<a href="{site_url}/admin/plugins/maps/index.php?mode=setgeolocation">{set_user_geo}</a>',
    '<a href="{site_url}/admin/plugins/maps/index.php#maps-user-geolocation">{set_user_geo}</a>',
    'admin menu geolocation link'
)
p.write_text(s, encoding='utf-8')

# Give the secure geolocation help/action a stable anchor.
p = Path('admin/index.php')
s = p.read_text(encoding='utf-8')
s = replace_required(
    s,
    "$html .= '<h3>' . $LANG_MAPS_1['admin_help_geo_title'] . '</h3>';",
    "$html .= '<h3 id=\"maps-user-geolocation\">' . $LANG_MAPS_1['admin_help_geo_title'] . '</h3>';",
    'geolocation help heading'
)
# Use the common message helper on the main admin page too, so redirected
# messages look identical to Icons/Overlays.
old = """        if (!empty($requestData['msg'])) {
            $display .= COM_startBlock($LANG_MAPS_1['message'], '', 'blockheader-message.thtml');
            $display .= htmlspecialchars((string) $requestData['msg'], ENT_QUOTES, 'UTF-8');
            $display .= COM_endBlock('blockfooter-message.thtml');
        }
"""
new = """        if (!empty($requestData['msg'])) {
            $display .= MAPS_message(
                htmlspecialchars((string) $requestData['msg'], ENT_QUOTES, 'UTF-8'),
                $LANG_MAPS_1['message']
            );
        }
"""
s = replace_required(s, old, new, 'index message block')
p.write_text(s, encoding='utf-8')

# Icons: make every informational/error block use MAPS_message().
p = Path('admin/icons.php')
s = p.read_text(encoding='utf-8')
old = """    $display .= MAPS_message(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_icons_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . $LANG_MAPS_1['icons_not_writable'] . '</p>'
    );
"""
new = """    $display .= MAPS_message(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_icons_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . htmlspecialchars($LANG_MAPS_1['icons_not_writable'], ENT_QUOTES, 'UTF-8') . '</p>',
        $LANG_MAPS_1['error']
    );
"""
s = replace_required(s, old, new, 'icons writable message')
old = """                $display .= COM_startBlock($LANG_MAPS_1['error'], '', 'blockheader-message.thtml');
                $display .= $LANG_MAPS_1['missing_field'];
                $display .= COM_endBlock('blockfooter-message.thtml');
"""
new = """                $display .= MAPS_message(
                    htmlspecialchars($LANG_MAPS_1['missing_field'], ENT_QUOTES, 'UTF-8'),
                    $LANG_MAPS_1['error']
                );
"""
s = replace_required(s, old, new, 'icons missing field message')
p.write_text(s, encoding='utf-8')

# Overlays: same helper and same error title as Icons.
p = Path('admin/overlays.php')
s = p.read_text(encoding='utf-8')
old = """    $display .= COM_showMessageText(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_overlay_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . htmlspecialchars($LANG_MAPS_1['overlay_not_writable'], ENT_QUOTES, 'UTF-8') . '</p>'
    );
"""
new = """    $display .= MAPS_message(
        '>> ' . htmlspecialchars($_MAPS_CONF['path_overlay_images'], ENT_QUOTES, 'UTF-8')
        . '<p>' . htmlspecialchars($LANG_MAPS_1['overlay_not_writable'], ENT_QUOTES, 'UTF-8') . '</p>',
        $LANG_MAPS_1['error']
    );
"""
s = replace_required(s, old, new, 'overlays writable message')
old = """            $display .= COM_showMessageText($MESSAGE[29], $MESSAGE[30]);
"""
new = """            $display .= MAPS_message(
                'Invalid or expired security token.',
                $LANG_MAPS_1['error']
            );
"""
s = replace_required(s, old, new, 'overlays csrf message')
p.write_text(s, encoding='utf-8')
