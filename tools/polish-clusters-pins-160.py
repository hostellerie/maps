from pathlib import Path

path = Path('functions.inc')
text = path.read_text()

old_pin = '''function MAPS_svgMarkerUri($fill, $label = '', $labelColor = '#ffffff')
{
    if (!preg_match('/^#[0-9a-f]{6}$/i', $fill)) {
        $fill = '#666666';
    }
    if (!preg_match('/^#[0-9a-f]{6}$/i', $labelColor)) {
        $labelColor = '#ffffff';
    }
    $label = function_exists('mb_substr') ? mb_substr((string) $label, 0, 2, 'UTF-8') : substr((string) $label, 0, 2);
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="44" viewBox="0 0 32 44">'
         . '<path fill="' . $fill . '" stroke="#333" stroke-width="1.5" d="M16 1C7.7 1 1 7.7 1 16c0 11 15 27 15 27s15-16 15-27C31 7.7 24.3 1 16 1z"/>'
         . '<circle cx="16" cy="16" r="9" fill="rgba(255,255,255,.18)"/>';
    if ($label !== '') {
        $svg .= '<text x="16" y="20" text-anchor="middle" font-family="Arial,sans-serif" font-size="11" font-weight="bold" fill="' . $labelColor . '">' . $label . '</text>';
    }
    $svg .= '</svg>';
    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}
'''

new_pin = '''function MAPS_svgMarkerUri($fill, $label = '', $labelColor = '#ffffff')
{
    if (!preg_match('/^#[0-9a-f]{6}$/i', $fill)) {
        $fill = '#666666';
    }
    if (!preg_match('/^#[0-9a-f]{6}$/i', $labelColor)) {
        $labelColor = '#ffffff';
    }
    $label = function_exists('mb_substr') ? mb_substr((string) $label, 0, 2, 'UTF-8') : substr((string) $label, 0, 2);
    $label = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="44" viewBox="0 0 32 44">'
         . '<defs><filter id="s" x="-30%" y="-20%" width="160%" height="170%"><feDropShadow dx="0" dy="1.2" stdDeviation="1.1" flood-color="#000" flood-opacity=".22"/></filter></defs>'
         . '<path filter="url(#s)" fill="' . $fill . '" stroke="#2f2f2f" stroke-width="1.1" d="M16 1.5C8 1.5 1.5 8 1.5 16c0 10.7 14.5 26.2 14.5 26.2S30.5 26.7 30.5 16C30.5 8 24 1.5 16 1.5z"/>'
         . '<circle cx="16" cy="16" r="8.6" fill="rgba(255,255,255,.12)"/>';
    if ($label !== '') {
        $svg .= '<text x="16" y="20" text-anchor="middle" font-family="Arial,sans-serif" font-size="10.5" font-weight="700" fill="' . $labelColor . '">' . $label . '</text>';
    }
    $svg .= '</svg>';
    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}
'''

old_cluster = '''function MAPS_clusterInitJs($mapVariable)
{
    return "if (window.markerClusterer && markerClusterer.MarkerClusterer && markers.length) {\\n"
        . "    new markerClusterer.MarkerClusterer({map: " . $mapVariable . ", markers: markers});\\n"
        . "}\\n";
}
'''

new_cluster = '''function MAPS_clusterInitJs($mapVariable)
{
    return "if (window.markerClusterer && markerClusterer.MarkerClusterer && markers.length) {\\n"
        . "    var mapsClusterRenderer = {\\n"
        . "        render: function(cluster) {\\n"
        . "            var count = Number(cluster.count || 0);\\n"
        . "            var size = count >= 50 ? 42 : (count >= 10 ? 38 : 34);\\n"
        . "            var fill = count >= 50 ? '#315f8f' : (count >= 10 ? '#3f76aa' : '#568bbb');\\n"
        . "            var ring = count >= 50 ? '#24486d' : (count >= 10 ? '#315d86' : '#426f97');\\n"
        . "            var svg = '<svg xmlns=\\\"http://www.w3.org/2000/svg\\\" width=\\\"' + size + '\\\" height=\\\"' + size + '\\\" viewBox=\\\"0 0 ' + size + ' ' + size + '\\\">' +\\n"
        . "                '<defs><filter id=\\\"s\\\" x=\\\"-30%\\\" y=\\\"-30%\\\" width=\\\"160%\\\" height=\\\"160%\\\"><feDropShadow dx=\\\"0\\\" dy=\\\"1\\\" stdDeviation=\\\"1.2\\\" flood-color=\\\"#000\\\" flood-opacity=\\\".18\\\"/></filter></defs>' +\\n"
        . "                '<circle filter=\\\"url(#s)\\\" cx=\\\"' + (size / 2) + '\\\" cy=\\\"' + (size / 2) + '\\\" r=\\\"' + ((size / 2) - 2) + '\\\" fill=\\\"' + fill + '\\\" fill-opacity=\\\".92\\\" stroke=\\\"' + ring + '\\\" stroke-width=\\\"2\\\"/>' +\\n"
        . "                '<circle cx=\\\"' + (size / 2) + '\\\" cy=\\\"' + (size / 2) + '\\\" r=\\\"' + ((size / 2) - 6) + '\\\" fill=\\\"none\\\" stroke=\\\"#fff\\\" stroke-opacity=\\\".20\\\" stroke-width=\\\"1\\\"/>' +\\n"
        . "                '<text x=\\\"50%\\\" y=\\\"50%\\\" dy=\\\".35em\\\" text-anchor=\\\"middle\\\" font-family=\\\"Arial,sans-serif\\\" font-size=\\\"' + (count >= 100 ? 11 : 12) + '\\\" font-weight=\\\"700\\\" fill=\\\"#fff\\\">' + count + '</text></svg>';\\n"
        . "            return new google.maps.Marker({\\n"
        . "                position: cluster.position,\\n"
        . "                icon: {url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), scaledSize: new google.maps.Size(size, size)},\\n"
        . "                zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,\\n"
        . "                title: count + ' markers'\\n"
        . "            });\\n"
        . "        }\\n"
        . "    };\\n"
        . "    new markerClusterer.MarkerClusterer({map: " . $mapVariable . ", markers: markers, renderer: mapsClusterRenderer});\\n"
        . "}\\n";
}
'''

if old_pin not in text:
    raise SystemExit('pin function anchor not found')
if old_cluster not in text:
    raise SystemExit('cluster function anchor not found')

text = text.replace(old_pin, new_pin, 1)
text = text.replace(old_cluster, new_cluster, 1)
path.write_text(text)

notes = Path('RELEASE-NOTES-1.6.0.md')
notes_text = notes.read_text()
addition = '''\n## Marker and cluster visual polish\n\n- Added a compact custom MarkerClusterer renderer with three density levels and a restrained blue palette.\n- Reduced cluster visual dominance while preserving click-to-zoom behavior.\n- Refined generated SVG pins with a thinner outline, lighter highlight and subtle shadow while keeping configured colors and labels.\n'''
if '## Marker and cluster visual polish' not in notes_text:
    notes.write_text(notes_text.rstrip() + addition + '\n')
