from pathlib import Path

# Localized heading used to introduce markers on one public map page.
for filename, anchor, addition in [
    ('language/english.php', "    'markers_list'          => 'Markers list',\n", "    'map_markers_heading'   => 'Markers on this map',\n"),
    ('language/french_france_utf-8.php', "    'markers_list'          => 'Liste des marqueurs',\n", "    'map_markers_heading'   => 'Marqueurs de cette carte',\n"),
]:
    p = Path(filename)
    text = p.read_text()
    if 'map_markers_heading' not in text:
        if anchor not in text:
            raise SystemExit('language anchor not found in ' + filename)
        text = text.replace(anchor, anchor + addition, 1)
        p.write_text(text)

# Public map page: keep the H2 already introduced, but make its wording specific and localized.
p = Path('public_html/index.php')
text = p.read_text()
old = """            $content .= '<h2 class=\"maps-markers-heading\">'\n                . htmlspecialchars($LANG_MAPS_1['markers_list'], ENT_QUOTES, 'UTF-8') . '</h2>';\n"""
new = """            $markersHeading = isset($LANG_MAPS_1['map_markers_heading'])\n                ? $LANG_MAPS_1['map_markers_heading']\n                : $LANG_MAPS_1['markers_list'];\n            $content .= '<h2 class=\"maps-markers-heading\">'\n                . htmlspecialchars($markersHeading, ENT_QUOTES, 'UTF-8') . '</h2>';\n"""
if old in text:
    text = text.replace(old, new, 1)
elif 'map_markers_heading' not in text:
    raise SystemExit('public marker heading is neither legacy nor finalized')

# Landing page: the Users Map entry must use the exact same block element as map entries.
old = """        $retval .= '<p class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</p>';\n"""
new = """        $retval .= '<div class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</div>';\n"""
if old in text:
    text = text.replace(old, new, 1)
elif "'<div class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php'" not in text:
    raise SystemExit('users map block is neither legacy nor finalized')
p.write_text(text)

# Assert the administration hierarchy already established on the branch.
checks = {
    'admin/index.php': [
        '<h1 class="maps-admin-title">',
        'MAPS_listmaps()',
        'MAPS_renderStatistics(false)',
        'MAPS_adminGoogleApiStatus()',
    ],
    'admin/markers.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'admin/icons.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'admin/overlays.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'admin/import_export.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'admin/map_edit.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'admin/marker_edit.php': ['MAPS_admin_menu()', 'maps-admin-title'],
    'templates/admin_menu.thtml': ['maps-admin-menu', 'maps-admin-title'],
    'templates/geocoder.thtml': ['maps-admin-title', 'maps-admin-geocoder'],
}
for filename, needles in checks.items():
    text = Path(filename).read_text()
    for needle in needles:
        if needle not in text:
            raise SystemExit('%s missing expected admin hierarchy marker %s' % (filename, needle))

# Verify dashboard content order: H1/list before stats before API status.
text = Path('admin/index.php').read_text()
pos_h1 = text.rfind("$display .= '<h1 class=\"maps-admin-title\">'")
pos_list = text.rfind('$display .= MAPS_listmaps();')
pos_stats = text.rfind('$display .= MAPS_renderStatistics(false);')
pos_api = text.rfind('$display .= MAPS_adminGoogleApiStatus();')
if min(pos_h1, pos_list, pos_stats, pos_api) < 0 or not (pos_h1 < pos_list < pos_stats < pos_api):
    raise SystemExit('admin dashboard hierarchy is not H1 -> list -> statistics -> API status')

p = Path('RELEASE-NOTES-1.6.0.md')
text = p.read_text()
entry = """
## Administration and content hierarchy polish

- Added a localized H2 introducing marker lists on individual public map pages.
- Matched the Users Map block spacing to the other map entries on the landing page.
- Standardized the Maps administration menu and top-level H1 placement using the Geocoder hierarchy as reference.
- Reordered the administration dashboard to maps list, statistics, then Google Maps API status.
- Added missing page titles to import/export and the main map/marker editors.
- Modernized the Geocoder form while preserving its preferred menu/title hierarchy.
"""
if '## Administration and content hierarchy polish' not in text:
    text += entry
p.write_text(text)
