from pathlib import Path

# Public map page: introduce marker list with an H2 and align Users Map spacing.
p = Path('public_html/index.php')
text = p.read_text()
old = """            $content .= MAPS_getMap($mid);\n            $content .= MAPS_ListMarkers($mid);\n            $content .= MAPS_renderMapStatistics($mid, true);\n"""
new = """            $content .= MAPS_getMap($mid);\n            $markersHeading = isset($LANG_MAPS_1['map_markers_heading'])\n                ? $LANG_MAPS_1['map_markers_heading']\n                : $LANG_MAPS_1['markers_list'];\n            $content .= '<h2 class=\"maps-section-title\">'\n                . htmlspecialchars($markersHeading, ENT_QUOTES, 'UTF-8') . '</h2>';\n            $content .= MAPS_ListMarkers($mid);\n            $content .= MAPS_renderMapStatistics($mid, true);\n"""
if old not in text:
    raise SystemExit('public map marker-list anchor not found')
text = text.replace(old, new, 1)
old = """        $retval .= '<p class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</p>';\n"""
new = """        $retval .= '<div class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</div>';\n"""
if old not in text:
    raise SystemExit('users map spacing anchor not found')
text = text.replace(old, new, 1)
p.write_text(text)

# Admin home: Geocoder-like hierarchy: menu, H1, list, statistics, API status.
p = Path('admin/index.php')
text = p.read_text()
old = """        $display .= MAPS_adminGoogleApiStatus();\n        $display .= MAPS_renderStatistics(false);\n        $display .= '<h1>' . $LANG_MAPS_1['maps_list'] . '</h1>';\n        $display .= '<p>' . $LANG_MAPS_1['you_can'] . '<a href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';\n        $display .= MAPS_listmaps();\n        $display .= MAPS_adminDocumentation(true);\n"""
new = """        $display .= '<h1>' . htmlspecialchars($LANG_MAPS_1['maps_list'], ENT_QUOTES, 'UTF-8') . '</h1>';\n        $display .= '<p>' . $LANG_MAPS_1['you_can'] . '<a href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';\n        $display .= MAPS_listmaps();\n        $display .= MAPS_renderStatistics(false);\n        $display .= MAPS_adminGoogleApiStatus();\n        $display .= MAPS_adminDocumentation(true);\n"""
if old not in text:
    raise SystemExit('admin home ordering anchor not found')
text = text.replace(old, new, 1)
p.write_text(text)

# Normalize menu function spelling and title spacing across all admin pages.
for p in Path('admin').glob('*.php'):
    text = p.read_text()
    text = text.replace('maps_admin_menu()', 'MAPS_admin_menu()')
    text = text.replace("'<br /><h1>", "'<h1>")
    text = text.replace("'<br><h1>", "'<h1>")
    p.write_text(text)

# Import/export had no page H1 at all. Give it the same menu -> H1 -> content order.
p = Path('admin/import_export.php')
text = p.read_text()
anchor = "$display .= MAPS_admin_menu();\n"
heading = "$display .= '<h1>' . htmlspecialchars($LANG_MAPS_1['import_export'], ENT_QUOTES, 'UTF-8') . '</h1>';\n"
# Add heading to every rendered import/export administration view, but not if already present.
if heading not in text:
    text = text.replace(anchor, anchor + heading)
p.write_text(text)

# Geocoder already has the preferred placement; only normalize its markup whitespace.
p = Path('templates/geocoder.thtml')
text = p.read_text()
text = text.replace('\t  <h1>{geocoder}</h1>', '<h1>{geocoder}</h1>')
p.write_text(text)

# Localized public marker-section title.
for filename, after, line in [
    ('language/english.php', "    'markers_list'          => 'Markers list',\n", "    'map_markers_heading'   => 'Markers on this map',\n"),
    ('language/french_france_utf-8.php', "    'markers_list'          => 'Liste des marqueurs',\n", "    'map_markers_heading'   => 'Marqueurs de cette carte',\n"),
]:
    p = Path(filename)
    text = p.read_text()
    if 'map_markers_heading' not in text:
        if after not in text:
            raise SystemExit('language anchor not found: ' + filename)
        text = text.replace(after, after + line, 1)
    p.write_text(text)

# Document release polish.
p = Path('RELEASE-NOTES-1.6.0.md')
text = p.read_text()
addition = """
## Administration and public hierarchy polish

- Public map pages introduce their marker list with a localized H2.
- The Users Map entry now uses the same block spacing as the map entries on the landing page.
- The Maps administration home follows a consistent menu -> H1 -> list -> statistics -> Google Maps API status hierarchy.
- Administration list pages no longer insert legacy line breaks before their H1 and use the same Maps administration menu helper consistently.
- Import/export now has an explicit page H1, matching the Geocoder title placement.
"""
if '## Administration and public hierarchy polish' not in text:
    text += addition
p.write_text(text)
