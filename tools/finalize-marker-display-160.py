from pathlib import Path


def replace(path, old, new, count=1):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s: %r' % (path, old[:180]))
    p.write_text(text.replace(old, new, count))

# Public marker list: normalize legacy display values without modifying stored data.
replace(
    'functions.inc',
    "function MAPS_getListField_markersList($fieldname, $fieldvalue, $a, $icon_arr)\n{\n    global $_MAPS_CONF;\n    if ($fieldname === 'name') {\n        return COM_createLink(htmlspecialchars(stripslashes($a['name']), ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($a['mkid']) . '&mid=' . (int) $a['mid']);\n    }\n    return htmlspecialchars(stripslashes((string) $fieldvalue), ENT_QUOTES, 'UTF-8');\n}\n",
    "function MAPS_getListField_markersList($fieldname, $fieldvalue, $a, $icon_arr)\n{\n    global $_MAPS_CONF;\n    if ($fieldname === 'name') {\n        $name = MAPS_normalizeMarkerText($a['name']);\n        return COM_createLink(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($a['mkid']) . '&mid=' . (int) $a['mid']);\n    }\n    if ($fieldname === 'city') {\n        return htmlspecialchars(MAPS_normalizeMarkerPlace($fieldvalue), ENT_QUOTES, 'UTF-8');\n    }\n    if ($fieldname === 'code') {\n        return htmlspecialchars(MAPS_normalizeMarkerText($fieldvalue), ENT_QUOTES, 'UTF-8');\n    }\n    return htmlspecialchars(MAPS_normalizeMarkerText($fieldvalue), ENT_QUOTES, 'UTF-8');\n}\n"
)

# Marker detail: normalize legacy geographic fields at display time too.
replace(
    'functions.inc',
    "    foreach (array('street','code','city','state','country','tel','fax') as $field) {\n        $value = MAPS_arrayGet($marker, $field, '');\n        $template->set_var($field, $value !== '' && (int) MAPS_arrayGet($_MAPS_CONF, $field, 1) ? '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . htmlspecialchars(stripslashes($value), ENT_QUOTES, 'UTF-8') . '</p>' : '');\n    }",
    "    foreach (array('street','code','city','state','country','tel','fax') as $field) {\n        $value = MAPS_arrayGet($marker, $field, '');\n        if (in_array($field, array('city', 'state', 'country'), true)) {\n            $value = MAPS_normalizeMarkerPlace($value);\n        } else {\n            $value = MAPS_normalizeMarkerText($value);\n        }\n        $template->set_var($field, $value !== '' && (int) MAPS_arrayGet($_MAPS_CONF, $field, 1) ? '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</p>' : '');\n    }"
)

# Admin editors: select boxes need the same comfortable vertical rhythm as inputs.
p = Path('public_html/maps.css')
css = p.read_text()
block = '''\n/* Maps 1.6.0 admin form control sizing */\n.maps-admin-editor select,\n.maps-admin-editor-card select,\n.maps-admin-form select {\n    min-height: 2.65rem;\n    padding: .48rem .7rem;\n    line-height: 1.35;\n}\n@media (max-width: 640px) {\n    .maps-admin-editor select,\n    .maps-admin-editor-card select,\n    .maps-admin-form select {\n        min-height: 2.8rem;\n    }\n}\n'''
if 'Maps 1.6.0 admin form control sizing' not in css:
    css += block
p.write_text(css)

# Release note.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
line = '- Legacy marker display normalization: geographic fields are harmonized at render time in public tables/details, and admin select controls receive accessible vertical sizing.\n'
if line not in notes:
    notes += '\n' + line
p.write_text(notes)
