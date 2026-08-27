from pathlib import Path


def replace_once(path, old, new):
    p = Path(path)
    s = p.read_text(encoding='utf-8')
    if old not in s:
        raise SystemExit('Anchor not found in %s' % path)
    p.write_text(s.replace(old, new, 1), encoding='utf-8')

# Shared visibility rule: customized label OR existing value.
p = Path('functions.inc')
s = p.read_text(encoding='utf-8')
anchor = "function MAPS_renderPublicCustomFields($marker, $layout = 'detail')\n{"
helper = """/**
 * Return whether a configurable marker custom field should be shown in forms.
 *
 * Default placeholder labels do not activate a field. Existing stored values
 * remain editable so upgrades never hide legacy marker data.
 *
 * @param int   $index
 * @param array $marker
 * @return bool
 */
function MAPS_shouldShowCustomField($index, $marker = array())
{
    global $_MAPS_CONF;

    $index = (int) $index;
    if ($index < 1 || $index > 10) {
        return false;
    }

    $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $index, ''));
    $value = trim((string) MAPS_arrayGet($marker, 'item_' . $index, ''));

    if ($value !== '') {
        return true;
    }
    if ($label === '') {
        return false;
    }

    // Current 1.5.8 placeholders plus historical Maps defaults.
    $defaultLabels = array(
        'Custom field ' . $index,
        'Ressource #' . $index,
        'Resource #' . $index
    );

    return !in_array($label, $defaultLabels, true);
}

""" + anchor
if anchor not in s or 'function MAPS_shouldShowCustomField' in s:
    raise SystemExit('functions.inc helper anchor missing or helper already present')
s = s.replace(anchor, helper, 1)
p.write_text(s, encoding='utf-8')

old_admin = """\t\t$arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);\n\t\t$ressources ='';\n\t\tforeach ($arr as &$value) {\n\t\t\t$itemConfig = MAPS_arrayGet($_MAPS_CONF, 'item_' . $value, '');\n\t\t\tif ($itemConfig == '') {\n\t\t\t\t$template->set_var('item_'. $value . '_label', '');\n\t\t\t\t$template->set_var('item_'. $value, '');\n\t\t\t\t$ressources .= '';\n\t\t\t} else {\n\t\t\t\t$template->set_var('item_'. $value . '_label', $itemConfig);\n\t\t\t\t$itemValue = MAPS_arrayGet($marker, 'item_' . $value, '');\n\t\t\t\t$template->set_var('item_'. $value, $itemValue);\n\t\t\t\t$ressources .= '<p>' . htmlspecialchars($itemConfig, ENT_QUOTES, 'UTF-8') . ' <input type=\"text\" name=\"item_' . $value . '\" size=\"80\" maxlength=\"255\" value=\"' . htmlspecialchars($itemValue, ENT_QUOTES, 'UTF-8') . '\"></p>';\n\t\t\t}\n\t\t}\n"""
new_admin = """\t\t$arr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);\n\t\t$ressources = '';\n\t\tforeach ($arr as $value) {\n\t\t\t$itemConfig = MAPS_arrayGet($_MAPS_CONF, 'item_' . $value, '');\n\t\t\t$itemValue = MAPS_arrayGet($marker, 'item_' . $value, '');\n\t\t\tif (!MAPS_shouldShowCustomField($value, $marker)) {\n\t\t\t\t$template->set_var('item_' . $value . '_label', '');\n\t\t\t\t$template->set_var('item_' . $value, '');\n\t\t\t\tcontinue;\n\t\t\t}\n\n\t\t\t$template->set_var('item_' . $value . '_label', $itemConfig);\n\t\t\t$template->set_var('item_' . $value, $itemValue);\n\t\t\t$ressources .= '<p>' . htmlspecialchars($itemConfig, ENT_QUOTES, 'UTF-8')\n\t\t\t    . ' <input type=\"text\" name=\"item_' . $value . '\" size=\"80\" maxlength=\"255\" value=\"'\n\t\t\t    . htmlspecialchars($itemValue, ENT_QUOTES, 'UTF-8') . '\"></p>';\n\t\t}\n"""
replace_once('admin/marker_edit.php', old_admin, new_admin)
replace_once('public_html/markers.php', old_admin, new_admin)

notes = Path('RELEASE-NOTES-1.5.8.md')
t = notes.read_text(encoding='utf-8')
entry = "- Marker edit forms now hide untouched custom-field placeholders; a field appears only after its label is customized, or when the marker already contains a value to preserve legacy data.\n"
if entry not in t:
    t += '\n' + entry
notes.write_text(t, encoding='utf-8')
