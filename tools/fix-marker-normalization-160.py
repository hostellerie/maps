from pathlib import Path


def replace(path, old, new, count=1):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s: %r' % (path, old[:160]))
    p.write_text(text.replace(old, new, count))

# Common marker text normalization helpers.
replace(
    'functions.inc',
    "function MAPS_decodeStoredText($value)\n{\n    return html_entity_decode(stripslashes((string) $value), ENT_QUOTES, 'UTF-8');\n}\n",
    "function MAPS_decodeStoredText($value)\n{\n    return html_entity_decode(stripslashes((string) $value), ENT_QUOTES, 'UTF-8');\n}\n\n/**\n * Normalize a single-line marker field without changing intentional casing.\n *\n * Leading/trailing whitespace is removed and runs of spaces/tabs/newlines are\n * collapsed to a single space. This prevents invisible prefixes from breaking\n * alphabetical sorting while preserving names and acronyms entered by users.\n *\n * @param mixed $value\n * @return string\n */\nfunction MAPS_normalizeMarkerText($value)\n{\n    $value = MAPS_decodeStoredText($value);\n    $value = preg_replace('/\\s+/u', ' ', trim($value));\n    return ($value === null) ? '' : $value;\n}\n\n/**\n * Normalize a geographic marker field (city/state/country).\n *\n * Mixed-case values are preserved. Values entered entirely in lower-case or\n * upper-case are converted to title case when mbstring is available. This\n * turns e.g. LANESTER / lanester into Lanester without altering mixed-case\n * names such as Saint-Jean-d'Angély that were already deliberately entered.\n *\n * @param mixed $value\n * @return string\n */\nfunction MAPS_normalizeMarkerPlace($value)\n{\n    $value = MAPS_normalizeMarkerText($value);\n    if ($value === '' || !function_exists('mb_strtoupper') || !function_exists('mb_strtolower')\n        || !function_exists('mb_convert_case')\n    ) {\n        return $value;\n    }\n\n    $upper = mb_strtoupper($value, 'UTF-8');\n    $lower = mb_strtolower($value, 'UTF-8');\n    if ($value === $upper || $value === $lower) {\n        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');\n    }\n\n    return $value;\n}\n"
)

# Admin marker editor: remove the duplicate nested H1.
replace(
    'admin/marker_edit.php',
    "\t$display = '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['marker_edit'], ENT_QUOTES, 'UTF-8') . ($marker['name'] !== '' ? ': ' . htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8') : '') . '</h1>';",
    "\t$display = '';"
)

# Admin marker save: normalize before required-field validation and SQL escaping.
replace(
    'admin/marker_edit.php',
    "        $_REQUEST = array_merge($saveDefaults, $_REQUEST);\n\n        if (empty($_REQUEST['name']) || empty($_REQUEST['address'])) {",
    "        $_REQUEST = array_merge($saveDefaults, $_REQUEST);\n\n        $markerSingleLineFields = array(\n            'name', 'address', 'street', 'code', 'tel', 'fax', 'web', 'label',\n            'item_1', 'item_2', 'item_3', 'item_4', 'item_5',\n            'item_6', 'item_7', 'item_8', 'item_9', 'item_10'\n        );\n        foreach ($markerSingleLineFields as $markerField) {\n            $_REQUEST[$markerField] = MAPS_normalizeMarkerText($_REQUEST[$markerField]);\n        }\n        foreach (array('city', 'state', 'country') as $markerPlaceField) {\n            $_REQUEST[$markerPlaceField] = MAPS_normalizeMarkerPlace($_REQUEST[$markerPlaceField]);\n        }\n        $_REQUEST['description'] = trim((string) $_REQUEST['description']);\n        $_REQUEST['remark'] = trim((string) $_REQUEST['remark']);\n\n        if ($_REQUEST['name'] === '' || $_REQUEST['address'] === '') {"
)

# Public/user marker save gets exactly the same normalization rules.
replace(
    'public_html/markers.php',
    "\tcase 'save':\n\n        $safeMkid = isset($_REQUEST['mkid']) ? preg_replace('/[^0-9]/', '', (string) $_REQUEST['mkid']) : '';\n\n\t\tif (empty($_REQUEST['name']) || empty($_REQUEST['address'])) {",
    "\tcase 'save':\n\n        $safeMkid = isset($_REQUEST['mkid']) ? preg_replace('/[^0-9]/', '', (string) $_REQUEST['mkid']) : '';\n        foreach (array('name', 'address', 'street', 'code', 'tel', 'fax', 'web') as $markerField) {\n            $_REQUEST[$markerField] = MAPS_normalizeMarkerText(isset($_REQUEST[$markerField]) ? $_REQUEST[$markerField] : '');\n        }\n        foreach (array('city', 'state', 'country') as $markerPlaceField) {\n            $_REQUEST[$markerPlaceField] = MAPS_normalizeMarkerPlace(isset($_REQUEST[$markerPlaceField]) ? $_REQUEST[$markerPlaceField] : '');\n        }\n        $_REQUEST['description'] = trim(isset($_REQUEST['description']) ? (string) $_REQUEST['description'] : '');\n\n\t\tif ($_REQUEST['name'] === '' || $_REQUEST['address'] === '') {"
)

# Imported markers are normalized before validation/preview/insert.
replace(
    'admin/import_export.php',
    "        if ($marker['name'] === '') {",
    "        foreach (array('name', 'address', 'street', 'code', 'tel', 'fax', 'web',\n                       'item_1', 'item_2', 'item_3', 'item_4', 'item_5',\n                       'item_6', 'item_7', 'item_8', 'item_9', 'item_10') as $markerField) {\n            $marker[$markerField] = MAPS_normalizeMarkerText($marker[$markerField]);\n        }\n        foreach (array('city', 'state', 'country') as $markerPlaceField) {\n            $marker[$markerPlaceField] = MAPS_normalizeMarkerPlace($marker[$markerPlaceField]);\n        }\n        $marker['description'] = trim((string) $marker['description']);\n\n        if ($marker['name'] === '') {"
)

# Existing admin rows sort by normalized text, even before they are re-saved.
p = Path('admin/markers.php')
text = p.read_text()
text = text.replace(
    "array('text' => $LANG_MAPS_1['name'], 'field' => 'name', 'sort' => true),",
    "array('text' => $LANG_MAPS_1['name'], 'field' => 'sort_name', 'sort' => true),",
    1
)
text = text.replace(
    "\t            a.*, b.name as mapname",
    "\t            a.*, LOWER(TRIM(a.name)) AS sort_name, b.name as mapname",
    1
)
text = text.replace(
    "        case \"name\":\n            $map_title = MAPS_decodeStoredText($A['name']);",
    "        case \"sort_name\":\n            $map_title = MAPS_normalizeMarkerText($A['name']);",
    1
)
p.write_text(text)

# User-owned marker list gets the same stable alphabetical order.
p = Path('public_html/markers.php')
text = p.read_text()
text = text.replace(
    "array('text' => $LANG_MAPS_1['name'], 'field' => 'name', 'sort' => true),",
    "array('text' => $LANG_MAPS_1['name'], 'field' => 'sort_name', 'sort' => true),",
    1
)
text = text.replace(
    "$defsort_arr = array('field' => 'mk.name', 'direction' => 'asc');",
    "$defsort_arr = array('field' => 'sort_name', 'direction' => 'asc');",
    1
)
text = text.replace(
    "\t            mk.*, m.free_marker",
    "\t            mk.*, LOWER(TRIM(mk.name)) AS sort_name, m.free_marker",
    1
)
text = text.replace(
    "        case \"name\":\n            $map_title = stripslashes ($A['name']);",
    "        case \"sort_name\":\n            $map_title = MAPS_normalizeMarkerText($A['name']);",
    1
)
p.write_text(text)

# Release notes.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
line = '- Marker data normalization: trim/collapse whitespace on single-line fields, smart-case city/state/country values, normalized alphabetical sorting for legacy rows, and removal of the duplicate marker-editor H1.\n'
if line not in notes:
    notes += '\n' + line
p.write_text(notes)
