from pathlib import Path
import re

p = Path('functions.inc')
s = p.read_text(encoding='utf-8')

# Insert one shared public renderer after MAPS_arrayGet().
needle = "function MAPS_arrayGet($array, $key, $default = '')\n{\n    return (is_array($array) && isset($array[$key])) ? $array[$key] : $default;\n}\n"
helper = needle + "\n/**\n * Render configured marker custom fields for public output.\n *\n * A field is public only when both its configured label and stored value are\n * non-empty. Values keep Maps' historical autotag support through\n * PLG_replaceTags(), while labels are always escaped.\n *\n * @param array  $marker\n * @param string $layout compact for info windows, detail for marker pages\n * @return string\n */\nfunction MAPS_renderPublicCustomFields($marker, $layout = 'detail')\n{\n    global $_MAPS_CONF;\n\n    $html = '';\n    for ($i = 1; $i <= 10; $i++) {\n        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));\n        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));\n        if ($label === '' || $value === '') {\n            continue;\n        }\n\n        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');\n        $renderedValue = PLG_replaceTags(stripslashes($value));\n        if ($layout === 'compact') {\n            $html .= '<div class=\"maps-custom-field maps-custom-field-compact\">'\n                . '<strong>' . $safeLabel . '</strong> ' . $renderedValue . '</div>';\n        } else {\n            $html .= '<div class=\"maps-custom-field\">'\n                . '<strong class=\"maps-custom-field-label\">' . $safeLabel . '</strong>'\n                . '<div class=\"maps-custom-field-value\">' . $renderedValue . '</div></div>';\n        }\n    }\n\n    if ($html === '') {\n        return '';\n    }\n\n    return '<div class=\"maps-custom-fields\">' . $html . '</div>';\n}\n"
if needle not in s:
    raise SystemExit('MAPS_arrayGet helper anchor not found')
s = s.replace(needle, helper, 1)

# Normalize popup renderer loop to shared helper.
popup_pattern = re.compile(r"\s*\$resources = '';\n\s*for \(\$i = 1; \$i <= 10; \$i\+\+\) \{\n\s*\$cfg = .*?\n\s*\$val = .*?\n\s*\$template->set_var\('item_' \. \$i, PLG_replaceTags\(stripslashes\(\$val\)\)\);\n\s*if \(\$cfg !== '' && \$val !== ''\) \{\n\s*\$resources \.= '<h3>' .*?;\n\s*\}\n\s*\}\n\s*\$template->set_var\('ressources', \$resources\);", re.S)
s, popup_count = popup_pattern.subn("\n            $template->set_var('ressources', MAPS_renderPublicCustomFields($marker, 'compact'));", s)
if popup_count < 1:
    raise SystemExit('Popup custom-field loop not found')

# Normalize detail renderer loop to shared helper.
detail_pattern = re.compile(r"\s*\$resources = '';\n\s*for \(\$i = 1; \$i <= 10; \$i\+\+\) \{\n\s*\$label = MAPS_arrayGet\(\$_MAPS_CONF, 'item_' \. \$i, ''\);\n\s*\$value = MAPS_arrayGet\(\$marker, 'item_' \. \$i, ''\);\n\s*if \(\$label !== '' && \$value !== ''\) \{\n\s*\$resources \.= '<p><strong>' .*?;\n\s*\}\n\s*\}\n\s*\$template->set_var\('ressources', \$resources\);", re.S)
s, detail_count = detail_pattern.subn("\n    $template->set_var('ressources', MAPS_renderPublicCustomFields($marker, 'detail'));", s)
if detail_count < 1:
    raise SystemExit('Detail custom-field loop not found')

# Remove the last legacy configuration-repair definition of infos_label.
s = re.sub(r"\n\s*\$rows\['infos_label'\] = array\([^\n]+\);", "", s)

p.write_text(s, encoding='utf-8')

# Correct the audit: public rendering is implemented in functions.inc.
audit = Path('CUSTOM-FIELDS-AUDIT-1.5.8.md')
audit.write_text("""# Maps 1.5.8 custom fields audit\n\n`item_1` through `item_10` remain supported marker custom fields. They are stored in `maps_markers` and `maps_submission`, included in moderation, and exposed in the admin/user marker edit forms when their configured label is non-empty.\n\n## Public rendering\n\nMaps 1.5.8 renders configured custom fields publicly in both places where marker content is presented:\n\n- the marker detail page;\n- the Google Maps info window.\n\nA custom field is rendered only when **both** its configured label and its stored marker value are non-empty. The rendering is centralized in `MAPS_renderPublicCustomFields()` so the detail page and info window apply the same visibility rule and escaping policy. Values continue to support Geeklog autotags through `PLG_replaceTags()`.\n\nThe obsolete `infos_label` option is not used for this rendering and is removed in Maps 1.5.8.\n""", encoding='utf-8')

# Release note entry.
notes = Path('RELEASE-NOTES-1.5.8.md')
t = notes.read_text(encoding='utf-8')
entry = "\n- Centralized public rendering of marker custom fields on marker detail pages and map info windows; fields are shown only when both label and value are present.\n"
if entry.strip() not in t:
    t += entry
notes.write_text(t, encoding='utf-8')
