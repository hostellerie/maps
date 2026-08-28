from pathlib import Path
import re

p = Path('functions.inc')
text = p.read_text()
helper = r'''/**
 * Parse geo autotag arguments while preserving the historical "map" syntax.
 *
 * Supported examples:
 * [geo: Paris, France]
 * [geo: Paris, France zoom:12]
 * [geo: map width:100% height:400px zoom:12 Paris, France]
 *
 * @param mixed $parm1
 * @param mixed $parm2
 * @return array
 */
function MAPS_parseGeoAutotagOptions($parm1, $parm2)
{
    $first = trim((string) $parm1);
    $rest = trim((string) $parm2);
    $raw = (strtolower($first) === 'map') ? $rest : trim($first . ' ' . $rest);
    $result = array('address' => '', 'zoom' => '', 'width' => '', 'height' => '');
    $addressParts = array();

    foreach (preg_split('/\\s+/', $raw) as $part) {
        if (preg_match('/^(zoom|width|height):(.+)$/i', $part, $match)) {
            $key = strtolower($match[1]);
            $result[$key] = trim($match[2]);
        } elseif ($part !== '') {
            $addressParts[] = $part;
        }
    }

    $result['address'] = MAPS_normalizeMarkerText(implode(' ', $addressParts));
    return $result;
}

'''
needle = 'function plugin_autotags_maps($op, $content = \'\', $autotag = \'\')\n'
if 'function MAPS_parseGeoAutotagOptions(' not in text:
    if needle not in text:
        raise SystemExit('autotag function anchor not found')
    text = text.replace(needle, helper + needle, 1)

old = r'''    if ($tag === 'geo' && COM_applyFilter($parm1) === 'map') {
        $zoom = '';
        $width = '';
        $height = '';
        $addressParts = array();
        foreach (preg_split('/\s+/', trim($parm2)) as $part) {
            if (strpos($part, 'zoom:') === 0) {
                $zoom = substr($part, 5);
            } elseif (strpos($part, 'width:') === 0) {
                $width = substr($part, 6);
            } elseif (strpos($part, 'height:') === 0) {
                $height = substr($part, 7);
            } elseif ($part !== '') {
                $addressParts[] = $part;
            }
        }
        $address = trim(implode(' ', $addressParts));
'''
new = r'''    if ($tag === 'geo') {
        $geoOptions = MAPS_parseGeoAutotagOptions($parm1, $parm2);
        $zoom = $geoOptions['zoom'];
        $width = $geoOptions['width'];
        $height = $geoOptions['height'];
        $address = $geoOptions['address'];
'''
if old not in text:
    raise SystemExit('legacy geo parser block not found')
text = text.replace(old, new, 1)
text = text.replace("$geoZoom = is_numeric($zoom) ? (int) $zoom : (int) MAPS_arrayGet($_MAPS_CONF, 'map_zoom_geotag', 10);", "$geoZoom = $zoom !== '' ? MAPS_zoom($zoom, MAPS_arrayGet($_MAPS_CONF, 'map_zoom_geotag', 10)) : MAPS_zoom(MAPS_arrayGet($_MAPS_CONF, 'map_zoom_geotag', 10), 10);", 1)
text = text.replace("$t->set_var('map_width_geotag', $width !== '' ? $width : MAPS_arrayGet($_MAPS_CONF, 'map_width_geotag', '100%'));", "$t->set_var('map_width_geotag', MAPS_cssSize($width !== '' ? $width : MAPS_arrayGet($_MAPS_CONF, 'map_width_geotag', '100%'), '100%'));", 1)
text = text.replace("$t->set_var('map_height_geotag', $height !== '' ? $height : MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', '400px'));", "$t->set_var('map_height_geotag', MAPS_cssSize($height !== '' ? $height : MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', '400px'), '400px'));", 1)
p.write_text(text)

for lang, desc in [
    ('language/english.php', "[geo: Paris, France zoom:12] - Displays a map centered on a place name or address. Optional parameters: zoom, width and height. The historical [geo: map ...] syntax remains supported."),
    ('language/french_france_utf-8.php', "[geo: Paris, France zoom:12] - Affiche une carte centrée sur un nom de lieu ou une adresse. Paramètres facultatifs : zoom, width et height. L’ancienne syntaxe [geo: map ...] reste compatible.")
]:
    lp = Path(lang)
    data = lp.read_text()
    data, count = re.subn(r"('autotag_desc_geo'\s*=>\s*)'[^\n]*'([,;]?)", lambda m: m.group(1) + repr(desc) + m.group(2), data, count=1)
    if count != 1:
        raise SystemExit('autotag_desc_geo not found in ' + lang)
    lp.write_text(data)

rp = Path('RELEASE-NOTES-1.6.0.md')
r = rp.read_text()
bullet = '- Geo autotag modernization: `[geo: Paris, France]` now renders a cached geocoded place directly, with optional `zoom`, `width` and `height`, while preserving the historical `[geo: map ...]` syntax.\n'
if bullet not in r:
    r += ('\n' if not r.endswith('\n') else '') + bullet
rp.write_text(r)
