from pathlib import Path

p = Path('public_html/index.php')
text = p.read_text()
old = "    $retval .= MAPS_renderStatistics(true);\n    $retval .= '<p>' . $LANG_MAPS_1['user_maps_list'] . '</p>';"
new = "    $retval .= '<p>' . $LANG_MAPS_1['user_maps_list'] . '</p>';"
if old not in text:
    raise SystemExit('front statistics anchor not found')
text = text.replace(old, new, 1)
old2 = "    if ((int) MAPS_arrayGet($_MAPS_CONF, 'users_map', 1) === 1) {\n        $retval .= '<p class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</p>';\n    }\n    if (SEC_hasRights('maps.admin')) {"
new2 = "    if ((int) MAPS_arrayGet($_MAPS_CONF, 'users_map', 1) === 1) {\n        $retval .= '<p class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'\n            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</p>';\n    }\n    $retval .= MAPS_renderStatistics(true);\n    if (SEC_hasRights('maps.admin')) {"
if old2 not in text:
    raise SystemExit('front list tail anchor not found')
text = text.replace(old2, new2, 1)
old3 = "            $content .= MAPS_ViewMarkerInfos($mkid);\n            if (!empty($markerMapRow['mid'])) {"
new3 = "            $content .= MAPS_ViewMarkerInfos($mkid);\n            if (!empty($markerMapRow['mid'])) {\n                $content .= MAPS_getMarkerDetail((int) $markerMapRow['mid'], $mkid);\n            }\n            if (!empty($markerMapRow['mid'])) {"
if old3 not in text:
    raise SystemExit('marker detail anchor not found')
text = text.replace(old3, new3, 1)
p.write_text(text)

p = Path('functions.inc')
text = p.read_text()
old = "$t->set_file('page', $autotag ? 'map_autotag.thtml' : 'map.thtml');"
new = "$t->set_file('page', $autotag ? 'map_autotag.thtml' : 'marker_map.thtml');"
if old not in text:
    raise SystemExit('marker detail template anchor not found')
text = text.replace(old, new, 1)
# Use canonical marker page for popup read-more fallback.
old4 = "$_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($marker['mkid']) . '&mid=' . (int) $marker['mid']"
new4 = "MAPS_markerContentUrl($marker['mkid'])"
text = text.replace(old4, new4)
p.write_text(text)

src = Path('templates/map.thtml').read_text()
# Dedicated marker map: preserve map/directions JS, but avoid an extra H1 and map-level metadata.
src = src.replace("{header}\n<h1>{name}</h1>\n{description}\n", "")
src = src.replace("\n{overlays_checkboxes}\n<small>{date_and_hits}</small>\n{footer}\n{edit_button}", "\n{overlays_checkboxes}")
Path('templates/marker_map.thtml').write_text(src)

p = Path('RELEASE-NOTES-1.6.0.md')
text = p.read_text()
addition = "\n## Final public-page refinements\n\n- the `/maps/` statistics block is rendered after the map list so primary navigational content appears first;\n- canonical marker pages again display an individual map centered on the marker;\n- marker pages restore the driving-directions form and route panel using the modern Google Maps Directions API already maintained by Maps;\n- the dedicated marker map template avoids introducing a second H1 on the canonical marker page.\n"
if '## Final public-page refinements' not in text:
    text += addition
p.write_text(text)
