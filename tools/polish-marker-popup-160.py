from pathlib import Path

# Refine cluster opacity and popup link markup.
p = Path('functions.inc')
text = p.read_text()
text = text.replace("fill-opacity=\\\".92\\\"", "fill-opacity=\\\".86\\\"")
text = text.replace("stroke-opacity=\\\".20\\\"", "stroke-opacity=\\\".16\\\"")
text = text.replace("$template->set_var('name', '<strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>');\n            $template->set_var('popup_width'", "$template->set_var('name', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));\n            $template->set_var('popup_width'", 1)
text = text.replace("$template->set_var('read_more', '<a href=\"' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '\">' . $LANG_MAPS_1['read_more'] . '</a>');", "$template->set_var('read_more', '<a class=\"maps-popup-primary-action\" href=\"' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '\">' . $LANG_MAPS_1['read_more'] . '</a>');")
text = text.replace("$template->set_var('map', '<p>' . $LANG_MAPS_1['on_map'] . ' <a href=\"' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&mid=' . (int) $marker['mid'] . '\">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></p>');", "$template->set_var('map', '<div class=\"maps-popup-map-link\"><span>' . htmlspecialchars($LANG_MAPS_1['on_map'], ENT_QUOTES, 'UTF-8') . '</span> <a href=\"' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&mid=' . (int) $marker['mid'] . '\">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></div>');")
text = text.replace("$template->set_var('edit', SEC_hasRights('maps.admin') || $currentUid === $referentUid ? '<a href=\"' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '\">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');", "$template->set_var('edit', SEC_hasRights('maps.admin') || $currentUid === $referentUid ? '<a class=\"maps-popup-edit-action\" href=\"' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '\">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');")
p.write_text(text)

# Replace the legacy one-line popup template.
Path('templates/presentation_tab.thtml').write_text('''<!--no-space-and-no-line-break-please--><article class="maps-popup-card" style="--maps-popup-width:{popup_width};--maps-popup-height:{popup_height}"><header class="maps-popup-header"><h2 class="maps-popup-title">{name}</h2>{edit}</header><div class="maps-popup-body">{description}{ressources}</div><div class="maps-popup-actions">{read_more}</div>{map}</article>''')

# Add theme-neutral popup styling.
css = Path('public_html/maps.css')
css_text = css.read_text()
block = '''\n\n/* Maps 1.6.0 marker info windows */\n.maps-popup-card {\n    width: var(--maps-popup-width, 280px);\n    max-width: min(78vw, 340px);\n    max-height: min(62vh, 380px);\n    overflow: auto;\n    box-sizing: border-box;\n    padding: .15rem .2rem .1rem;\n    color: inherit;\n}\n.maps-popup-header {\n    display: flex;\n    align-items: flex-start;\n    justify-content: space-between;\n    gap: .65rem;\n    margin: 0 0 .65rem;\n}\n.maps-popup-title {\n    margin: 0;\n    font-size: 1.08rem;\n    line-height: 1.3;\n    font-weight: 700;\n}\n.maps-popup-edit-action {\n    flex: 0 0 auto;\n    font-size: .78rem;\n    line-height: 1.2;\n    opacity: .72;\n    text-decoration: none;\n}\n.maps-popup-edit-action:hover,\n.maps-popup-edit-action:focus { opacity: 1; text-decoration: underline; }\n.maps-popup-body { line-height: 1.48; }\n.maps-popup-body > p { margin: 0 0 .7rem; }\n.maps-popup-body .maps-custom-fields { margin: .45rem 0 .7rem; }\n.maps-popup-actions { margin-top: .7rem; }\n.maps-popup-primary-action {\n    display: inline-block;\n    padding: .38rem .62rem;\n    border: 1px solid rgba(127,127,127,.28);\n    border-radius: 6px;\n    text-decoration: none;\n    font-weight: 600;\n}\n.maps-popup-primary-action:hover,\n.maps-popup-primary-action:focus { background: rgba(127,127,127,.07); }\n.maps-popup-map-link {\n    margin-top: .7rem;\n    padding-top: .6rem;\n    border-top: 1px solid rgba(127,127,127,.20);\n    font-size: .84rem;\n    opacity: .8;\n}\n.maps-popup-map-link span { margin-right: .2rem; }\n@media (max-width: 520px) {\n    .maps-popup-card { max-width: 74vw; }\n    .maps-popup-title { font-size: 1rem; }\n}\n'''
if '/* Maps 1.6.0 marker info windows */' not in css_text:
    css.write_text(css_text.rstrip() + block + '\n')

notes = Path('RELEASE-NOTES-1.6.0.md')
notes_text = notes.read_text()
addition = '''\n## Marker info-window polish\n\n- Refined cluster opacity for a calmer map while retaining the custom density renderer.\n- Replaced the legacy marker popup layout with a responsive information card.\n- Separated marker title, edit action, description, primary details link and parent-map link for clearer hierarchy.\n'''
if '## Marker info-window polish' not in notes_text:
    notes.write_text(notes_text.rstrip() + addition + '\n')
