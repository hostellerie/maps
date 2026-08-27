from pathlib import Path


def replace_once(path, old, new, label):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('%s anchor not found in %s' % (label, path))
    p.write_text(text.replace(old, new, 1))

# Public map page: introduce the marker collection with an H2.
replace_once(
    'public_html/index.php',
    """            $content .= MAPS_getMap($mid);\n            $content .= MAPS_ListMarkers($mid);\n            $content .= MAPS_renderMapStatistics($mid, true);\n""",
    """            $content .= MAPS_getMap($mid);\n            $content .= '<h2 class=\"maps-markers-heading\">'\n                . htmlspecialchars($LANG_MAPS_1['markers_list'], ENT_QUOTES, 'UTF-8') . '</h2>';\n            $content .= MAPS_ListMarkers($mid);\n            $content .= MAPS_renderMapStatistics($mid, true);\n""",
    'public map marker heading'
)

# Admin home: H1 immediately after menu; list first, stats second, API status third.
replace_once(
    'admin/index.php',
    """        $display .= MAPS_adminGoogleApiStatus();\n        $display .= MAPS_renderStatistics(false);\n        $display .= '<h1>' . $LANG_MAPS_1['maps_list'] . '</h1>';\n        $display .= '<p>' . $LANG_MAPS_1['you_can'] . '<a href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';\n        $display .= MAPS_listmaps();\n        $display .= MAPS_adminDocumentation(true);\n""",
    """        $display .= '<h1 class=\"maps-admin-title\">' . $LANG_MAPS_1['maps_list'] . '</h1>';\n        $display .= '<p class=\"maps-list-actions\">' . $LANG_MAPS_1['you_can'] . '<a class=\"maps-primary-action\" href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';\n        $display .= MAPS_listmaps();\n        $display .= MAPS_renderStatistics(false);\n        $display .= MAPS_adminGoogleApiStatus();\n        $display .= MAPS_adminDocumentation(true);\n""",
    'admin home ordering'
)

# Normalize menu call capitalization and top-level H1 placement.
for path in [
    'admin/markers.php', 'admin/icons.php', 'admin/overlays.php',
    'admin/marker_edit.php'
]:
    p = Path(path)
    text = p.read_text().replace('maps_admin_menu()', 'MAPS_admin_menu()')
    p.write_text(text)

replace_once(
    'admin/markers.php',
    "$display .= '<br /><h1>' . $LANG_MAPS_1['markers_list'] . '</h1>';",
    "$display .= '<h1 class=\"maps-admin-title\">' . $LANG_MAPS_1['markers_list'] . '</h1>';",
    'markers H1'
)
replace_once(
    'admin/icons.php',
    "$display .= '<br><h1>' . htmlspecialchars($LANG_MAPS_1['icons_list'], ENT_QUOTES, 'UTF-8') . '</h1>';",
    "$display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['icons_list'], ENT_QUOTES, 'UTF-8') . '</h1>';",
    'icons H1'
)
replace_once(
    'admin/overlays.php',
    "$display .= '<br><h1>' . htmlspecialchars($LANG_MAPS_1['overlays_list'], ENT_QUOTES, 'UTF-8') . '</h1>';",
    "$display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['overlays_list'], ENT_QUOTES, 'UTF-8') . '</h1>';",
    'overlays H1'
)

# Import/export had no page H1 at all. Add it after the common menu in every rendered branch.
p = Path('admin/import_export.php')
text = p.read_text()
needle = "$display .= MAPS_admin_menu();\n"
insert = "$display .= MAPS_admin_menu();\n    $display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['import_export'], ENT_QUOTES, 'UTF-8') . '</h1>';\n"
# First rendered POST branch.
pos = text.find(needle, text.find("if ($mode !== '')"))
if pos < 0:
    raise SystemExit('import/export POST menu anchor not found')
text = text[:pos] + text[pos:].replace(needle, insert, 1)
# Final GET branch.
pos = text.rfind(needle)
if pos < 0:
    raise SystemExit('import/export GET menu anchor not found')
segment = text[pos:]
if 'maps-admin-title' not in segment.split('$display .= getImportExportForm();', 1)[0]:
    text = text[:pos] + segment.replace(needle, insert, 1)
p.write_text(text)

# Geocoder: retain its good menu -> H1 structure but modernize spacing and controls.
Path('templates/geocoder.thtml').write_text("""<section class=\"maps-admin-geocoder\">\n    <h1 class=\"maps-admin-title\">{geocoder}</h1>\n    <p>{geocoder_text}</p>\n    <div class=\"maps-geocoder-form\">\n        <label for=\"address\" class=\"maps-visually-hidden\">{geocoder}</label>\n        <input type=\"text\" id=\"address\" value=\"1600 Amphitheatre Pky, Mountain View, CA\">\n        <button type=\"button\" onclick=\"codeAddress()\">{go}</button>\n    </div>\n    <div id=\"map_canvas\" class=\"maps-geocoder-map\"></div>\n</section>\n""")

# Common admin menu styling. This is rendered by every admin screen and removes theme-dependent inconsistencies.
Path('templates/admin_menu.thtml').write_text("""<style type=\"text/css\">\n.maps-admin-menu { display:flex; flex-wrap:wrap; gap:.4rem .55rem; align-items:center; margin:0 0 1.1rem; padding:.65rem .75rem; border:1px solid rgba(127,127,127,.22); border-radius:10px; background:rgba(127,127,127,.045); }\n.maps-admin-menu a { display:inline-block; padding:.4rem .6rem; border-radius:6px; text-decoration:none; }\n.maps-admin-menu a:hover, .maps-admin-menu a:focus { background:rgba(127,127,127,.12); }\n.maps-admin-menu form { display:none; }\n.maps-admin-title { margin:.2rem 0 1rem; line-height:1.2; }\n.maps-list-actions { margin:0 0 1rem; }\n.maps-primary-action { font-weight:600; }\n.maps-admin-geocoder { margin-bottom:1.5rem; }\n.maps-geocoder-form { display:flex; flex-wrap:wrap; gap:.55rem; margin:1rem 0; }\n.maps-geocoder-form input[type=\"text\"] { flex:1 1 320px; min-width:0; box-sizing:border-box; padding:.65rem .75rem; font:inherit; }\n.maps-geocoder-form button { padding:.65rem 1rem; font:inherit; cursor:pointer; }\n.maps-geocoder-map { width:100%; height:400px; min-height:300px; border-radius:12px; overflow:hidden; background:#f2f2f2; }\n.maps-visually-hidden { position:absolute!important; width:1px!important; height:1px!important; padding:0!important; margin:-1px!important; overflow:hidden!important; clip:rect(0,0,0,0)!important; white-space:nowrap!important; border:0!important; }\n@media (max-width:680px) { .maps-admin-menu { align-items:stretch; } .maps-admin-menu a { flex:1 1 auto; text-align:center; } .maps-geocoder-map { height:320px; } }\n</style>\n<nav class=\"maps-admin-menu\" aria-label=\"Maps administration\">\n <a href=\"{site_url}/admin/plugins/maps/index.php\">{admin_home}</a>\n <a href=\"{site_url}/admin/plugins/maps/markers.php\">{markers}</a>\n <a href=\"{site_url}/admin/plugins/maps/icons.php\">{icons}</a>\n <a href=\"{site_url}/admin/plugins/maps/overlays.php\">{overlays}</a>\n <a href=\"{site_url}/admin/plugins/maps/import_export.php\">{import_export}</a>\n <a href=\"{site_url}/admin/plugins/maps/geocoder.php\">{geocoder}</a>\n <a href=\"{site_url}/admin/plugins/maps/index.php#maps-user-geolocation\">{set_user_geo}</a>\n <a href=\"#\" onclick=\"document.maps_conf_link.submit(); return false;\">{configuration}</a>\n <form name=\"maps_conf_link\" action=\"{site_admin_url}/configuration.php\" method=\"POST\">\n  <input type=\"hidden\" name=\"conf_group\" value=\"maps\">\n </form>\n</nav>\n""")

# Add explicit H1s to the two main editors, directly after the common menu.
replace_once(
    'admin/map_edit.php',
    "$content = MAPS_admin_menu();\n",
    "$content = MAPS_admin_menu();\n$editorTitle = ($mode === 'edit' && $mid > 0) ? $LANG_MAPS_1['map_edit'] : ucfirst($LANG_MAPS_1['create_map']);\n$content .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($editorTitle, ENT_QUOTES, 'UTF-8') . '</h1>';\n",
    'map editor H1'
)
replace_once(
    'admin/marker_edit.php',
    "$display .= MAPS_admin_menu();\n\nif (in_array($requestMode, array('save', 'delete'), true)) {",
    "$display .= MAPS_admin_menu();\n$markerEditorTitle = ($requestMode === 'edit' && $mkid !== '') ? $LANG_MAPS_1['marker_edit'] : ucfirst($LANG_MAPS_1['create_marker']);\n$display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($markerEditorTitle, ENT_QUOTES, 'UTF-8') . '</h1>';\n\nif (in_array($requestMode, array('save', 'delete'), true)) {",
    'marker editor H1'
)

# Release note trace.
p = Path('RELEASE-NOTES-1.6.0.md')
text = p.read_text()
entry = "\n## Administration and content hierarchy polish\n\n- Added an H2 introducing marker lists on individual public map pages.\n- Standardized the Maps administration menu and top-level H1 placement.\n- Reordered the administration dashboard to maps list, statistics, then Google Maps API status.\n- Added missing page titles to import/export and the main map/marker editors.\n- Modernized the geocoder form while preserving its menu/title hierarchy.\n"
if '## Administration and content hierarchy polish' not in text:
    text += entry
p.write_text(text)
