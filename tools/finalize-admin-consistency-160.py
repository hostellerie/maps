from pathlib import Path
import re


def replace(path, old, new, count=1):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s: %r' % (path, old[:120]))
    text = text.replace(old, new, count)
    p.write_text(text)

# List pages: one consistent action row, no introductory "You can" text.
replace(
    'admin/index.php',
    "$display .= '<p class=\"maps-list-actions\">' . $LANG_MAPS_1['you_can'] . '<a class=\"maps-primary-action\" href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . $LANG_MAPS_1['create_map'] . '</a>.</p>';",
    "$display .= '<p class=\"maps-list-actions\"><a class=\"maps-primary-action\" href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php\">' . htmlspecialchars($LANG_MAPS_1['create_map'], ENT_QUOTES, 'UTF-8') . '</a></p>';"
)
replace(
    'admin/icons.php',
    "$display .= '<p>' . htmlspecialchars($LANG_MAPS_1['you_can'], ENT_QUOTES, 'UTF-8')\n                . '<a href=\"' . htmlspecialchars($_CONF['site_admin_url'], ENT_QUOTES, 'UTF-8')\n                . '/plugins/maps/icons.php?mode=edit\">'\n                . htmlspecialchars($LANG_MAPS_1['create_icon'], ENT_QUOTES, 'UTF-8') . '</a>.</p>';",
    "$display .= '<p class=\"maps-list-actions\"><a class=\"maps-primary-action\" href=\"'\n                . htmlspecialchars($_CONF['site_admin_url'], ENT_QUOTES, 'UTF-8')\n                . '/plugins/maps/icons.php?mode=edit\">'\n                . htmlspecialchars($LANG_MAPS_1['create_icon'], ENT_QUOTES, 'UTF-8') . '</a></p>';"
)

# Overlay list actions become the same action bar instead of a bullet list.
p = Path('admin/overlays.php')
text = p.read_text()
old = '''    $display .= '<ul>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_edit.php', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_overlay'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['manage_groups'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '<li><a href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_group_edit.php?mode=new', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_group'], ENT_QUOTES, 'UTF-8') . '</a></li>'
        . '</ul>';'''
new = '''    $display .= '<div class="maps-list-actions maps-admin-actions">'
        . '<a class="maps-primary-action" href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_edit.php', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_overlay'], ENT_QUOTES, 'UTF-8') . '</a>'
        . '<a class="maps-secondary-action" href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlays.php?mode=groups', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['manage_groups'], ENT_QUOTES, 'UTF-8') . '</a>'
        . '<a class="maps-secondary-action" href="' . htmlspecialchars($_CONF['site_admin_url'] . '/plugins/maps/overlay_group_edit.php?mode=new', ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($LANG_MAPS_1['create_group'], ENT_QUOTES, 'UTF-8') . '</a>'
        . '</div>';'''
if old not in text:
    raise SystemExit('overlay action list not found')
p.write_text(text.replace(old, new, 1))

# Import/export: page controller already owns the H1; form must not add another block title.
replace(
    'admin/import_export.php',
    "    return COM_startBlock($LANG_MAPS_1['import_export'])\n        . $template->parse('output', 'import_export')\n        . COM_endBlock();",
    "    return $template->parse('output', 'import_export');"
)

# Marker editor: remove its historical nested H1 block; main controller owns the single H1.
p = Path('admin/marker_edit.php')
text = p.read_text()
text = text.replace("\t$display = COM_startBlock('<h1>' . $LANG_MAPS_1['marker_edit'] . ' ' . htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8') . '</h1>');", "\t$display = '';", 1)
text = text.replace("    $display .= COM_endBlock();\n\t\n\t$_SCRIPTS->setJavaScriptLibrary('jquery');", "\t$_SCRIPTS->setJavaScriptLibrary('jquery');", 1)
old_title = "$markerEditorTitle = ($requestMode === 'edit' && $mkid !== '') ? $LANG_MAPS_1['marker_edit'] : ucfirst($LANG_MAPS_1['create_marker']);\n$display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($markerEditorTitle, ENT_QUOTES, 'UTF-8') . '</h1>';"
new_title = "$markerEditorTitle = ($requestMode === 'edit' && $mkid !== '') ? $LANG_MAPS_1['marker_edit'] : $LANG_MAPS_1['create_marker'];\nif ($requestMode === 'edit' && $mkid !== '') {\n    $markerTitle = trim((string) DB_getItem($_TABLES['maps_markers'], 'name', \"mkid='\" . MAPS_dbEscape($mkid) . \"'\"));\n    if ($markerTitle !== '') {\n        $markerEditorTitle .= ': ' . MAPS_decodeStoredText($markerTitle);\n    }\n}\n$display .= '<h1 class=\"maps-admin-title\">' . htmlspecialchars($markerEditorTitle, ENT_QUOTES, 'UTF-8') . '</h1>';"
if old_title not in text:
    raise SystemExit('marker main title block not found')
text = text.replace(old_title, new_title, 1)
p.write_text(text)

# Map new/edit titles use the language strings verbatim; no ucfirst side effect.
replace(
    'admin/map_edit.php',
    "$editorTitle = ($mode === 'edit' && $mid > 0) ? $LANG_MAPS_1['map_edit'] : ucfirst($LANG_MAPS_1['create_map']);",
    "$editorTitle = ($mode === 'edit' && $mid > 0) ? $LANG_MAPS_1['map_edit'] : $LANG_MAPS_1['create_map'];"
)

# Ensure no editor still owns a nested block-level H1.
for path, marker in [
    ('admin/icons.php', "COM_startBlock('<h1>"),
    ('admin/overlay_edit.php', "COM_startBlock('<h1>"),
    ('admin/overlay_group_edit.php', "COM_startBlock('<h1>")
]:
    if marker in Path(path).read_text():
        raise SystemExit('nested editor H1 still present in %s' % path)

# Normalize English labels.
p = Path('language/english.php')
text = p.read_text()
text = text.replace("'create_map'            => 'create a new map'", "'create_map'            => 'Create a new map'")
text = text.replace("'create_marker'         => 'create a new marker'", "'create_marker'         => 'Create a new marker'")
text = text.replace("'map_edit'              => 'Map edition:'", "'map_edit'              => 'Edit map'")
text = text.replace("'marker_edit'           => 'Marker edition:'", "'marker_edit'           => 'Edit marker'")
text = re.sub(r"('icon_edit'\s*=>\s*)'[^']*'", r"\1'Edit icon'", text, count=1)
text = re.sub(r"('create_icon'\s*=>\s*)'[^']*'", r"\1'Create a new icon'", text, count=1)
text = re.sub(r"('overlay_edit'\s*=>\s*)'[^']*'", r"\1'Edit overlay'", text, count=1)
text = re.sub(r"('create_overlay'\s*=>\s*)'[^']*'", r"\1'Create a new overlay'", text, count=1)
text = re.sub(r"('group_edit'\s*=>\s*)'[^']*'", r"\1'Edit overlay group'", text, count=1)
text = re.sub(r"('create_group'\s*=>\s*)'[^']*'", r"\1'Create a new overlay group'", text, count=1)
p.write_text(text)

# Normalize French labels using exact historical values so escaped apostrophes remain valid PHP.
p = Path('language/french_france_utf-8.php')
text = p.read_text()
repls = {
    "'create_map'            => 'créer une nouvelle carte'": "'create_map'            => 'Créer une nouvelle carte'",
    "'create_marker'         => 'créer un nouveau marqueur'": "'create_marker'         => 'Créer un nouveau marqueur'",
    "'map_edit'              => 'Edition de la carte :'": "'map_edit'              => 'Modifier la carte'",
    "'marker_edit'           => 'Edition du marqueur :'": "'marker_edit'           => 'Modifier le marqueur'",
    "'create_icon'           => 'créer une nouvelle icône'": "'create_icon'           => 'Créer une nouvelle icône'",
    "'icon_edit'             => 'Edition d\\'icon'": "'icon_edit'             => 'Modifier l’icône'",
    "'create_overlay'        => 'Créer un nouveau calque'": "'create_overlay'        => 'Créer un nouveau calque'",
    "'overlay_edit'          => 'Édition de l\\'overlay :'": "'overlay_edit'          => 'Modifier le calque'",
    "'create_group'          => 'Créer un nouveau groupe de claques'": "'create_group'          => 'Créer un nouveau groupe de calques'",
    "'group_edit'            => 'Edition du groupe :'": "'group_edit'            => 'Modifier le groupe de calques'",
}
for old, new in repls.items():
    if old not in text:
        raise SystemExit('French label pattern not found: %s' % old)
    text = text.replace(old, new, 1)
p.write_text(text)

# Shared action bar styling.
p = Path('public_html/maps.css')
css = p.read_text()
block = '''\n/* Maps 1.6.0 consistent admin action bars */\n.maps-admin-actions {\n    display: flex;\n    flex-wrap: wrap;\n    gap: .55rem;\n    align-items: center;\n}\n.maps-secondary-action {\n    display: inline-block;\n    padding: .45rem .72rem;\n    border: 1px solid rgba(127,127,127,.28);\n    border-radius: 6px;\n    text-decoration: none;\n    font-weight: 600;\n}\n.maps-secondary-action:hover,\n.maps-secondary-action:focus { background: rgba(127,127,127,.07); }\n'''
if 'Maps 1.6.0 consistent admin action bars' not in css:
    css += block
p.write_text(css)

# Release note.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
line = '- Final administration consistency pass: single H1 per editor, consistent Create/Edit labels, unified list action bars, and no duplicate Import/Export block title.\n'
if line not in notes:
    notes += '\n' + line
p.write_text(notes)
