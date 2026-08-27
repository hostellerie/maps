from pathlib import Path

# 1. After an admin save, display the public map so the administrator can
# immediately validate the result instead of returning to the edit form.
p = Path('admin/map_edit.php')
text = p.read_text()
old = "        echo COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&mid=' . (int) $mid);\n"
new = "        echo COM_refresh($_MAPS_CONF['site_url'] . '/index.php?mode=map&mid=' . (int) $mid);\n"
if old not in text:
    raise SystemExit('Admin map save redirect not found')
p.write_text(text.replace(old, new, 1))

# 2. Expose a native Geeklog menu entry. Menu implementations use
# PLG_getMenuItems()/plugin_getmenuitems_<plugin>(); without this callback some
# themes/menu versions fall back to an empty href which renders as '#'.
p = Path('functions.inc')
text = p.read_text()
if 'function plugin_getmenuitems_maps()' not in text:
    anchor = "function plugin_getuseroption_maps()\n{\n"
    if anchor not in text:
        raise SystemExit('Maps plugin API insertion point not found')
    callback = """/**
 * Return the Maps entry for Geeklog's plugin menu API.
 *
 * Keeping the URL owned by Maps avoids menu/theme-specific fallbacks such as
 * an empty href rendered as '#'.
 *
 * @return array|bool
 */
function plugin_getmenuitems_maps()
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    if (COM_isAnonUser()
        && ((int) MAPS_arrayGet($_CONF, 'loginrequired', 0) === 1
            || (int) MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) === 1)
    ) {
        return false;
    }

    $label = isset($LANG_MAPS_1['maps_label']) && trim((string) $LANG_MAPS_1['maps_label']) !== ''
        ? $LANG_MAPS_1['maps_label']
        : $LANG_MAPS_1['plugin_name'];

    return array($label => $_MAPS_CONF['site_url'] . '/index.php');
}

"""
    text = text.replace(anchor, callback + anchor, 1)
p.write_text(text)

# Release notes
p = Path('RELEASE-NOTES-1.5.9.md')
if p.exists():
    text = p.read_text()
    section = """
## Administration and menu integration

- Saving a map in administration now redirects to its public map page so the administrator can immediately validate the result.
- Maps now implements Geeklog's native `plugin_getmenuitems_maps()` callback and always exposes `/maps/index.php` as its plugin-menu destination, preventing empty `#` menu links.
"""
    if '## Administration and menu integration' not in text:
        p.write_text(text.rstrip() + '\n' + section)
