from pathlib import Path


def replace(path, old, new, count=1):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s: %r' % (path, old[:180]))
    p.write_text(text.replace(old, new, count))

# Common public display helpers: keep stored casing untouched, normalize only output.
replace(
    'functions.inc',
    "function MAPS_normalizeMarkerPlace($value)\n{\n    $value = MAPS_normalizeMarkerText($value);\n    if ($value === '' || !function_exists('mb_strtoupper') || !function_exists('mb_strtolower')\n        || !function_exists('mb_convert_case')\n    ) {\n        return $value;\n    }\n\n    $upper = mb_strtoupper($value, 'UTF-8');\n    $lower = mb_strtolower($value, 'UTF-8');\n    if ($value === $upper || $value === $lower) {\n        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');\n    }\n\n    return $value;\n}\n",
    "function MAPS_normalizeMarkerPlace($value)\n{\n    $value = MAPS_normalizeMarkerText($value);\n    if ($value === '' || !function_exists('mb_strtoupper') || !function_exists('mb_strtolower')\n        || !function_exists('mb_convert_case')\n    ) {\n        return $value;\n    }\n\n    $upper = mb_strtoupper($value, 'UTF-8');\n    $lower = mb_strtolower($value, 'UTF-8');\n    if ($value === $upper || $value === $lower) {\n        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');\n    }\n\n    return $value;\n}\n\n/**\n * Return a marker name in the consistent public display casing.\n * Stored data is never rewritten: users remain free to enter the name as they wish.\n *\n * @param mixed $value\n * @return string\n */\nfunction MAPS_markerDisplayName($value)\n{\n    $value = MAPS_normalizeMarkerText($value);\n    if ($value === '') {\n        return '';\n    }\n    return function_exists('mb_strtoupper')\n        ? mb_strtoupper($value, 'UTF-8')\n        : strtoupper($value);\n}\n\n/**\n * Render a discreet placeholder for an empty public marker field.\n *\n * @return string\n */\nfunction MAPS_markerEmptyValue()\n{\n    return '<span class=\"maps-marker-empty\" aria-label=\"Not provided\">—</span>';\n}\n"
)

# Configured custom fields remain visible on detail pages when empty.
replace(
    'functions.inc',
    "        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));\n        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));\n        if ($label === '' || $value === '') {\n            continue;\n        }\n\n        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');\n        $expandedValue = PLG_replaceTags(stripslashes($value));\n        $plainValue = trim(strip_tags((string) $expandedValue));\n        $renderedValue = nl2br(htmlspecialchars($plainValue, ENT_QUOTES, 'UTF-8'));",
    "        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));\n        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));\n        if ($label === '') {\n            continue;\n        }\n        if ($value === '' && $layout === 'compact') {\n            continue;\n        }\n\n        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');\n        if ($value === '') {\n            $renderedValue = MAPS_markerEmptyValue();\n        } else {\n            $expandedValue = PLG_replaceTags(stripslashes($value));\n            $plainValue = trim(strip_tags((string) $expandedValue));\n            $renderedValue = $plainValue === ''\n                ? MAPS_markerEmptyValue()\n                : nl2br(htmlspecialchars($plainValue, ENT_QUOTES, 'UTF-8'));\n        }"
)

# Marker detail: uppercase the display name and show standard empty fields explicitly.
replace(
    'functions.inc',
    "$template->set_var('name', '<span style=\"text-transform:uppercase\">' . htmlspecialchars(MAPS_decodeStoredText($marker['name']), ENT_QUOTES, 'UTF-8') . '</span>');",
    "$template->set_var('name', htmlspecialchars(MAPS_markerDisplayName($marker['name']), ENT_QUOTES, 'UTF-8'));"
)
replace(
    'functions.inc',
    "    foreach (array('street','code','city','state','country','tel','fax') as $field) {\n        $value = MAPS_arrayGet($marker, $field, '');\n        if (in_array($field, array('city', 'state', 'country'), true)) {\n            $value = MAPS_normalizeMarkerPlace($value);\n        } else {\n            $value = MAPS_normalizeMarkerText($value);\n        }\n        $template->set_var($field, $value !== '' && (int) MAPS_arrayGet($_MAPS_CONF, $field, 1) ? '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</p>' : '');\n    }",
    "    foreach (array('street','code','city','state','country','tel','fax') as $field) {\n        $value = MAPS_arrayGet($marker, $field, '');\n        if (in_array($field, array('city', 'state', 'country'), true)) {\n            $value = MAPS_normalizeMarkerPlace($value);\n        } else {\n            $value = MAPS_normalizeMarkerText($value);\n        }\n        if ((int) MAPS_arrayGet($_MAPS_CONF, $field, 1) === 0) {\n            $template->set_var($field, '');\n            continue;\n        }\n        $renderedValue = $value === ''\n            ? MAPS_markerEmptyValue()\n            : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');\n        $template->set_var($field, '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . $renderedValue . '</p>');\n    }"
)
replace(
    'functions.inc',
    "    $webValue = trim((string) MAPS_arrayGet($marker, 'web', ''));\n    $template->set_var(\n        'web',\n        $webValue !== ''\n            ? '<p><strong>' . htmlspecialchars($LANG_MAPS_1['web_label'], ENT_QUOTES, 'UTF-8') . '</strong> '\n                . '<span class=\"maps-marker-user-url\">'\n                . htmlspecialchars(MAPS_decodeStoredText($webValue), ENT_QUOTES, 'UTF-8') . '</span></p>'\n            : ''\n    );",
    "    $webValue = MAPS_normalizeMarkerText(MAPS_arrayGet($marker, 'web', ''));\n    $template->set_var(\n        'web',\n        '<p><strong>' . htmlspecialchars($LANG_MAPS_1['web_label'], ENT_QUOTES, 'UTF-8') . '</strong> '\n            . ($webValue === ''\n                ? MAPS_markerEmptyValue()\n                : '<span class=\"maps-marker-user-url\">'\n                    . htmlspecialchars($webValue, ENT_QUOTES, 'UTF-8') . '</span>')\n            . '</p>'\n    );"
)

# Public map table: uppercase marker names and show empty visible cells with an em dash.
replace(
    'functions.inc',
    "    if ($fieldname === 'name') {\n        $name = MAPS_normalizeMarkerText($a['name']);\n        return COM_createLink(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($a['mkid']) . '&mid=' . (int) $a['mid']);\n    }\n    if ($fieldname === 'city') {\n        return htmlspecialchars(MAPS_normalizeMarkerPlace($fieldvalue), ENT_QUOTES, 'UTF-8');\n    }\n    if ($fieldname === 'code') {\n        return htmlspecialchars(MAPS_normalizeMarkerText($fieldvalue), ENT_QUOTES, 'UTF-8');\n    }\n    return htmlspecialchars(MAPS_normalizeMarkerText($fieldvalue), ENT_QUOTES, 'UTF-8');",
    "    if ($fieldname === 'name') {\n        $name = MAPS_markerDisplayName($a['name']);\n        if ($name === '') {\n            return MAPS_markerEmptyValue();\n        }\n        return COM_createLink(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($a['mkid']) . '&mid=' . (int) $a['mid']);\n    }\n    if ($fieldname === 'city') {\n        $value = MAPS_normalizeMarkerPlace($fieldvalue);\n        return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');\n    }\n    if ($fieldname === 'code') {\n        $value = MAPS_normalizeMarkerText($fieldvalue);\n        return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');\n    }\n    $value = MAPS_normalizeMarkerText($fieldvalue);\n    return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');"
)

# Map marker popups/titles also follow the uppercase display convention.
replace(
    'functions.inc',
    "$name = MAPS_decodeStoredText(MAPS_arrayGet($marker, 'name', ''));",
    "$name = MAPS_markerDisplayName(MAPS_arrayGet($marker, 'name', ''));",
    1
)

# Marker page H1 only: SEO/page title remains stored casing.
replace(
    'public_html/index.php',
    "$content .= '<h1 class=\"maps-page-title\">' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '</h1>';",
    "$content .= '<h1 class=\"maps-page-title\">' . htmlspecialchars(MAPS_markerDisplayName($pageTitle), ENT_QUOTES, 'UTF-8') . '</h1>';"
)

# Discreet empty-value styling.
p = Path('public_html/maps.css')
css = p.read_text()
block = '''\n/* Maps 1.6.0 explicit empty marker values */\n.maps-marker-empty {\n    display: inline-block;\n    min-width: .75em;\n    opacity: .48;\n    font-weight: 400;\n}\n'''
if 'Maps 1.6.0 explicit empty marker values' not in css:
    css += block
p.write_text(css)

# Release note.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
line = '- Consistent marker presentation: public marker names render in uppercase while stored input casing is preserved, and empty public table/detail fields are represented by a discreet em dash.\n'
if line not in notes:
    notes += '\n' + line
p.write_text(notes)
