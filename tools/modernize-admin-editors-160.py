from pathlib import Path
import re

# Modern templates share the map editor's CSS vocabulary.
Path('templates/icon_form.thtml').write_text('''<div id="icon_form" class="maps-admin-editor">
<form name="icon_edit" action="{site_admin_url}/plugins/maps/icons.php" method="POST" enctype="multipart/form-data">
{csrf_token}
<fieldset class="maps-editor-section"><legend>{informations}</legend>
<p class="maps-help-text">{icon_presentation}</p>
<div class="maps-form-grid">
<label class="maps-field maps-field-wide"><span>{name_label}<span class="maps-required">*</span></span><input type="text" name="icon_name" value="{name}" maxlength="255" required></label>
<label class="maps-field maps-field-wide"><span>{image}</span><input type="file" dir="ltr" name="file" accept=".gif,.jpg,.jpeg,.png,.webp,image/gif,image/jpeg,image/png,image/webp"{xhtml}></label>
</div>
<p class="maps-help-text">{image_message}</p>
<div class="maps-current-media">{icon_image}</div>
</fieldset>
<p class="maps-help-text"><span class="maps-required">*</span> {required_field}</p>
<div class="maps-form-actions">
<button type="submit" name="mode" value="save">{save_button}</button>
{delete_action}
{id}
</div>
</form>
</div>''')

Path('templates/overlay_form.thtml').write_text('''<div id="overlay_form" class="maps-admin-editor">
<form name="overlay_edit" action="{site_admin_url}/plugins/maps/overlay_edit.php" method="POST" enctype="multipart/form-data">
{csrf_token}
<fieldset class="maps-editor-section"><legend>{informations}</legend>
<p class="maps-help-text">{overlay_presentation}</p>
<div class="maps-form-grid">
<label class="maps-field maps-field-wide"><span>{name_label}<span class="maps-required">*</span></span><input type="text" name="name" value="{name}" maxlength="255" required></label>
<div class="maps-field maps-field-wide"><span>{group_label}</span>{group}</div>
<label class="maps-field"><span>{sw_lat}</span><input type="text" id="sw_lat" name="sw_lat" value="{sw_lat_value}" inputmode="decimal"></label>
<label class="maps-field"><span>{sw_lng}</span><input type="text" id="sw_lng" name="sw_lng" value="{sw_lng_value}" inputmode="decimal"></label>
<label class="maps-field"><span>{ne_lat}</span><input type="text" id="ne_lat" name="ne_lat" value="{ne_lat_value}" inputmode="decimal"></label>
<label class="maps-field"><span>{ne_lng}</span><input type="text" id="ne_lng" name="ne_lng" value="{ne_lng_value}" inputmode="decimal"></label>
<label class="maps-field"><span>{zoom_min_label}</span><input type="number" id="zoom_min" name="zoom_min" value="{zoom_min}" min="0" max="22"></label>
<label class="maps-field"><span>{zoom_max_label}</span><input type="number" id="zoom_max" name="zoom_max" value="{zoom_max}" min="0" max="22"></label>
<label class="maps-field"><span>{active}</span><select name="active"><option value="0"{active_no}>{no}</option><option value="1"{active_yes}>{yes}</option></select></label>
</div>
</fieldset>
<fieldset class="maps-editor-section"><legend>{image}</legend>
<p class="maps-help-text">{image_message}</p>
<label class="maps-field maps-field-wide"><span>{image}</span><input type="file" dir="ltr" name="file" accept=".gif,.jpg,.jpeg,.png,image/gif,image/jpeg,image/png"{xhtml}></label>
<div class="maps-current-media">{overlay_image}</div>
</fieldset>
<p class="maps-help-text"><span class="maps-required">*</span> {required_field}</p>
<div class="maps-form-actions">
<button type="submit" name="mode" value="save">{save_button}</button>
{delete_action}
{oid}
</div>
</form>
</div>''')

Path('templates/group_overlay_form.thtml').write_text('''<div id="group_overlay_form" class="maps-admin-editor maps-admin-editor-compact">
<form name="group_overlay_edit" action="{site_admin_url}/plugins/maps/overlay_group_edit.php" method="POST">
{csrf_token}
<fieldset class="maps-editor-section"><legend>{informations}</legend>
<p class="maps-help-text">{group_overlay_presentation}</p>
<div class="maps-form-grid">
<label class="maps-field maps-field-wide"><span>{name_label}<span class="maps-required">*</span></span><input type="text" name="o_group_name" value="{name}" maxlength="255" required></label>
</div>
</fieldset>
<p class="maps-help-text"><span class="maps-required">*</span> {required_field}</p>
<div class="maps-form-actions">
<button type="submit" name="mode" value="save">{save_button}</button>
{delete_action}
{o_group_id}
</div>
</form>
</div>''')

Path('templates/marker_form.thtml').write_text('''<div class="maps-location-editor maps-editor-section">
<form action="#" onsubmit="codeAddress(); return false">
<label class="maps-field maps-field-wide" for="geoaddress"><span>{location_search_label}</span></label>
<div class="maps-location-search"><input type="search" id="geoaddress" name="geoaddress" value="{default_address}" oninput="copyText()"><button type="submit" onfocus="copyText();">{go}</button></div>
<p class="maps-help-text">{location_search_help}</p>
<div id="map_contener_submission"><div id="map_canvas" class="maps-marker-location-map" style="width:{marker_editor_width};height:{marker_editor_height}"></div><p class="maps-help-text">{use_map_click_help}</p><div id="submission_presentation"></div></div>
</form>
</div>
<div id="marker_form" class="maps-admin-editor">
<form name="marker_edit" action="{site_url}/admin/plugins/maps/marker_edit.php" method="POST">
{csrf_token}
<fieldset class="maps-editor-section"><legend>{section_location}</legend>
<div class="maps-form-grid">
<label class="maps-field maps-field-wide"><span>{name_label}<span class="maps-required">*</span></span><input type="text" name="name" value="{name}" maxlength="255" required></label>
<label class="maps-field maps-field-wide"><span>{address_label}<span class="maps-required">*</span></span><input type="text" id="address" name="address" value="{address}" maxlength="255" required></label>
<label class="maps-field maps-field-wide"><span>{mid_label}</span><select id="mid" name="mid">{map_options}</select><small>{select_marker_map}</small></label>
</div>
<details class="maps-technical-coordinates"><summary>{technical_coordinates}</summary><div class="maps-form-grid maps-form-grid-compact"><label class="maps-field"><span>{lat}</span><input type="text" id="lat" name="lat" value="{lat_value}" inputmode="decimal"></label><label class="maps-field"><span>{lng}</span><input type="text" id="lng" name="lng" value="{lng_value}" inputmode="decimal"></label></div><p class="maps-help-text">{empty_for_geo}</p></details>
<p class="maps-help-text">{created_label} {created} · {modified_label} {modified}</p>
</fieldset>
<details class="maps-advanced-panel" open><summary>{section_appearance}</summary><fieldset class="maps-editor-section"><div class="maps-form-grid"><label class="maps-field"><span>{mk_default}</span><select name="mk_default"><option value="0"{mk_default_no}>{no}</option><option value="1"{mk_default_yes}>{yes}</option></select></label><label class="maps-field"><span>{primary_color_label}</span><input type="color" id="primary_color" name="primary_color" value="{primary_color}"></label><label class="maps-field"><span>{stroke_color_label}</span><input type="color" id="stroke_color" name="stroke_color" value="{stroke_color}"></label><label class="maps-field"><span>{label_label}</span><input type="text" id="label" name="label" value="{label}" maxlength="1"></label><label class="maps-field"><span>{label_color_label}</span><select name="label_color"><option value="0"{label_color_black}>{black}</option><option value="1"{label_color_white}>{white}</option></select></label></div><div>{icon}</div></fieldset></details>
<details class="maps-advanced-panel"><summary>{section_publication}</summary><fieldset class="maps-editor-section"><div class="maps-form-grid"><label class="maps-field"><span>{payed}</span><select name="payed"><option value="0"{payed_no}>{no}</option><option value="1"{payed_yes}>{yes}</option></select></label><label class="maps-field"><span>{active}</span><select name="active"><option value="0"{active_no}>{no}</option><option value="1"{active_yes}>{yes}</option></select></label><label class="maps-field"><span>{hidden}</span><select name="hidden"><option value="0"{hidden_no}>{no}</option><option value="1"{hidden_yes}>{yes}</option></select></label><label class="maps-field"><span>{marker_validity}</span><select id="validity" name="validity" onchange="changeValidity();"><option value="0"{validity_no}>{no}</option><option value="1"{validity_yes}>{yes}</option></select></label><label class="maps-field"><span>{from_label}</span><input type="date" id="from" name="from" value="{from}"{disabled}></label><label class="maps-field"><span>{to_label}</span><input type="date" id="to" name="to" value="{to}"{disabled}></label><label class="maps-field maps-field-wide"><span>{remark_label}</span><textarea rows="4" id="remark" name="remark">{remark}</textarea></label></div></fieldset></details>
<fieldset class="maps-editor-section"><legend>{section_content_contact}</legend><div class="maps-form-grid"><label class="maps-field maps-field-wide"><span>{description_label}</span><textarea rows="5" id="description" name="description">{description}</textarea></label><div class="maps-field"><span>{street_label}</span>{street}</div><div class="maps-field"><span>{code_label}</span>{code}</div><div class="maps-field"><span>{city_label}</span>{city}</div><div class="maps-field"><span>{state_label}</span>{state}</div><div class="maps-field"><span>{country_label}</span>{country}</div><div class="maps-field"><span>{tel_label}</span>{tel}</div><div class="maps-field"><span>{fax_label}</span>{fax}</div><div class="maps-field maps-field-wide"><span>{web_label}</span>{web}</div></div></fieldset>
<details class="maps-advanced-panel"><summary>{section_resources}</summary><fieldset class="maps-editor-section">{ressources}</fieldset></details>
<details class="maps-advanced-panel"><summary>{section_ownership}</summary><fieldset class="maps-editor-section"><div class="maps-form-grid"><div class="maps-field"><span>{lang_owner}</span>{owner_select}</div><div class="maps-field"><span>{lang_group}</span>{group_dropdown}</div></div></fieldset></details>
<details class="maps-advanced-panel"><summary>{section_permissions}</summary><fieldset class="maps-editor-section"><p>{lang_perm_key}</p><p>{permissions_editor}</p><p class="maps-help-text">{lang_permissions_msg}</p></fieldset></details>
<p class="maps-help-text"><span class="maps-required">*</span> {required_field}</p>
<div class="maps-form-actions"><input type="hidden" name="submission" value="{submission}"><button type="submit" name="mode" value="save">{save_button}</button><button type="submit" name="mode" value="delete" class="maps-danger-action maps-delete-marker">{delete_button}</button>{mkid}</div>
</form>
</div>''')

# PHP wrappers: one page-level H1, no nested Geeklog block titles.
def replace_once(path, old, new):
    p = Path(path); text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s: %s' % (path, old[:80]))
    p.write_text(text.replace(old, new, 1))

replace_once('admin/marker_edit.php', "$display = COM_startBlock('<h1>' . $LANG_MAPS_1['marker_edit'] . ' ' . htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8') . '</h1>');", "$display = '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['marker_edit'], ENT_QUOTES, 'UTF-8') . ($marker['name'] !== '' ? ': ' . htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8') : '') . '</h1>';" )
# Remove only the function's closing wrapper directly before return.
p=Path('admin/marker_edit.php'); t=p.read_text(); t=t.replace("\n\t$display .= COM_endBlock();\n\n\treturn $display;", "\n\treturn $display;", 1); p.write_text(t)

replace_once('admin/icons.php', "$display = COM_startBlock('<h1>' . htmlspecialchars($LANG_MAPS_1['icon_edit'], ENT_QUOTES, 'UTF-8') . '</h1>');", "$display = '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['icon_edit'], ENT_QUOTES, 'UTF-8') . ($icon['icon_name'] !== '' ? ': ' . htmlspecialchars($icon['icon_name'], ENT_QUOTES, 'UTF-8') : '') . '</h1>';" )
p=Path('admin/icons.php'); t=p.read_text(); t=t.replace("    $display .= COM_endBlock();\n    return $display;", "    return $display;", 1)
# Add direct delete action variable expected by new template.
t=t.replace("$template->set_var('save_button', $LANG_MAPS_1['save_button']);", "$template->set_var('save_button', $LANG_MAPS_1['save_button']);\n    $template->set_var('delete_action', (int) $icon['icon_id'] > 0 ? '<button type=\"submit\" name=\"mode\" value=\"delete\" class=\"maps-danger-action\">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>' : '');",1)
p.write_text(t)

replace_once('admin/overlay_edit.php', "$display = COM_startBlock('<h1>' . $LANG_MAPS_1['overlay_edit'] . ' ' . htmlspecialchars((string) $overlay['name'], ENT_QUOTES, 'UTF-8') . '</h1>');", "$display = '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['overlay_edit'], ENT_QUOTES, 'UTF-8') . ($overlay['o_name'] !== '' ? ': ' . htmlspecialchars((string) $overlay['o_name'], ENT_QUOTES, 'UTF-8') : '') . '</h1>';" )
p=Path('admin/overlay_edit.php'); t=p.read_text(); t=t.replace("\n    $display .= COM_endBlock();\n\n    return $display;", "\n    return $display;",1)
t=t.replace("$template->set_var('group', MAPS_selectGroupOverlays($overlay['o_group']) );", "$template->set_var('group_label', $LANG_MAPS_1['group_label']);\n        $template->set_var('group', MAPS_selectGroupOverlays($overlay['o_group'], false));",1)
t=t.replace("$template->set_var('save_button', $LANG_MAPS_1['save_button']);", "$template->set_var('save_button', $LANG_MAPS_1['save_button']);\n        $template->set_var('delete_action', (int) $overlay['oid'] > 0 ? '<button type=\"submit\" name=\"mode\" value=\"delete\" class=\"maps-danger-action\">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>' : '');",1)
# Make group selector reusable without embedding a bold label.
t=t.replace("function MAPS_selectGroupOverlays ($selected)", "function MAPS_selectGroupOverlays ($selected, $withLabel = true)",1)
t=t.replace("$retval = '<b>' . $LANG_MAPS_1['group_label'] . '</b> <select name=\"o_group\">' .", "$retval = ($withLabel ? '<b>' . $LANG_MAPS_1['group_label'] . '</b> ' : '') . '<select name=\"o_group\">' .",1)
p.write_text(t)

replace_once('admin/overlay_group_edit.php', "$display = COM_startBlock('<h1>' . $LANG_MAPS_1['group_edit'] . ' ' . $safeName . '</h1>');", "$display = '<h1 class=\"maps-admin-title\">' . htmlspecialchars($LANG_MAPS_1['group_edit'], ENT_QUOTES, 'UTF-8') . ($safeName !== '' ? ': ' . $safeName : '') . '</h1>';" )
p=Path('admin/overlay_group_edit.php'); t=p.read_text(); t=t.replace("    $display .= COM_endBlock();\n\n    return $display;", "    return $display;",1)
t=t.replace("$template->set_var('save_button', $LANG_MAPS_1['save_button']);", "$template->set_var('save_button', $LANG_MAPS_1['save_button']);\n    $template->set_var('delete_action', (int) $group['o_group_id'] > 0 ? '<button type=\"submit\" name=\"mode\" value=\"delete\" class=\"maps-danger-action\">' . htmlspecialchars($LANG_MAPS_1['delete_button'], ENT_QUOTES, 'UTF-8') . '</button>' : '');",1)
t=t.replace("$display .= maps_admin_menu();", "$display .= MAPS_admin_menu();",1)
p.write_text(t)

# Shared CSS refinements for all admin editors.
css=Path('public_html/maps.css'); c=css.read_text()
block='''\n\n/* Maps 1.6.0 shared admin editors */\n.maps-admin-editor { margin: 0 0 1.25rem; }\n.maps-admin-editor-compact { max-width: 760px; }\n.maps-admin-editor .maps-editor-section,\n.maps-location-editor.maps-editor-section {\n    margin: 0 0 1rem;\n    padding: 1rem;\n    border: 1px solid rgba(127,127,127,.24);\n    border-radius: 9px;\n    background: rgba(127,127,127,.025);\n}\n.maps-admin-editor .maps-editor-section legend { padding: 0 .35rem; font-weight: 700; }\n.maps-admin-editor .maps-form-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.85rem 1rem; }\n.maps-admin-editor .maps-field { display:flex; flex-direction:column; gap:.3rem; min-width:0; }\n.maps-admin-editor .maps-field-wide { grid-column:1 / -1; }\n.maps-admin-editor input[type=text],\n.maps-admin-editor input[type=search],\n.maps-admin-editor input[type=date],\n.maps-admin-editor input[type=number],\n.maps-admin-editor input[type=file],\n.maps-admin-editor select,\n.maps-admin-editor textarea { width:100%; max-width:100%; box-sizing:border-box; }\n.maps-admin-editor textarea { resize:vertical; min-height:5.5rem; }\n.maps-admin-editor .maps-current-media img { max-width:min(100%,350px); height:auto; margin-top:.55rem; border-radius:7px; }\n.maps-marker-location-map { border-radius:9px; overflow:hidden; }\n.maps-admin-editor .maps-advanced-panel { margin:0 0 1rem; border:1px solid rgba(127,127,127,.24); border-radius:9px; overflow:hidden; }\n.maps-admin-editor .maps-advanced-panel > summary { cursor:pointer; padding:.8rem 1rem; font-weight:700; background:rgba(127,127,127,.04); }\n.maps-admin-editor .maps-advanced-panel > .maps-editor-section { margin:0; border:0; border-radius:0; background:transparent; }\n.maps-required { color:#b42318; margin-left:.15rem; }\n@media (max-width:720px) { .maps-admin-editor .maps-form-grid { grid-template-columns:1fr; } .maps-admin-editor .maps-field-wide { grid-column:auto; } }\n'''
if '/* Maps 1.6.0 shared admin editors */' not in c:
    css.write_text(c.rstrip()+block+'\n')

notes=Path('RELEASE-NOTES-1.6.0.md'); n=notes.read_text()
addition='''\n## Administration editor consistency\n\n- Modernized marker, icon, overlay and overlay-group editors with the same responsive form system as the map editor.\n- Standardized each editor on menu → one H1 → content, removing nested legacy block titles.\n- Replaced legacy Save/Delete selectors with explicit actions and improved responsive field grouping.\n'''
if '## Administration editor consistency' not in n:
    notes.write_text(n.rstrip()+addition+'\n')
