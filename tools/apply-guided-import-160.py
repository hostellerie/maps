from pathlib import Path
import re

p = Path('admin/import_export.php')
text = p.read_text()

anchor = "function MAPS_filterImportExportFields($fields)\n{"
helpers = '''/**
 * Preferred visual order for CSV field mapping.
 *
 * @return array
 */
function MAPS_getImportFieldOrder()
{
    return array(
        'name', 'address', 'lat', 'lng', 'description', 'street', 'code',
        'city', 'state', 'country', 'tel', 'web', 'fax', 'mk_default',
        'mk_pcolor', 'mk_scolor', 'mk_label', 'mk_label_color', 'item_1',
        'item_2', 'item_3', 'item_4', 'item_5', 'item_6', 'item_7', 'item_8',
        'item_9', 'item_10'
    );
}

function MAPS_getMinimumImportFields()
{
    return array('name', 'address');
}

function MAPS_getRecommendedImportFields()
{
    return array(
        'name', 'address', 'lat', 'lng', 'description', 'street', 'code',
        'city', 'state', 'country', 'tel', 'web'
    );
}

'''
if 'function MAPS_getImportFieldOrder()' not in text:
    text = text.replace(anchor, helpers + anchor, 1)

new_form = r'''function getImportExportForm()
{
    global $_CONF, $LANG_MAPS_1;

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('import_export' => 'import_export_form.thtml'));
    $template->set_var('site_admin_url', $_CONF['site_admin_url']);

    $token = SEC_createToken();
    $template->set_var(
        'csrf_token',
        '<input type="hidden" name="' . MAPS_importHtml(CSRF_TOKEN)
        . '" value="' . MAPS_importHtml($token) . '">'
    );

    foreach (array(
        'import', 'import_message', 'export', 'export_message', 'select_file',
        'choose_fields_import', 'choose_fields_export', 'checkall',
        'import_step_1', 'import_step_1_text', 'import_step_2', 'import_step_2_text',
        'import_step_3', 'import_step_3_text', 'import_minimum', 'import_recommended',
        'import_minimum_help', 'import_recommended_help', 'import_order_help',
        'import_select_minimum', 'import_select_recommended', 'import_clear_fields'
    ) as $langKey) {
        $template->set_var($langKey, isset($LANG_MAPS_1[$langKey]) ? $LANG_MAPS_1[$langKey] : $langKey);
    }
    $template->set_var('separator_in', $LANG_MAPS_1['separator']);
    $template->set_var('separator_out', $LANG_MAPS_1['separator']);

    $separatorOptions = '<option value=";">;</option>' . LB
        . '<option value="tab">tab</option>' . LB
        . '<option value=",">,</option>' . LB;
    $template->set_var('separator_options_in', $separatorOptions);
    $template->set_var('separator_options_out', $separatorOptions);

    $selectedMid = isset($_REQUEST['mid']) ? (int) $_REQUEST['mid'] : 0;
    $template->set_var('mid_label', $LANG_MAPS_1['name_label']);
    $template->set_var('map_options', MAPS_recurseMaps($selectedMid));

    $minimum = MAPS_getMinimumImportFields();
    $recommended = MAPS_getRecommendedImportFields();
    $importFieldsSelector = '';
    foreach (MAPS_getImportFieldOrder() as $field) {
        $classes = array('maps-import-field');
        if (in_array($field, $minimum, true)) {
            $classes[] = 'maps-import-minimum';
        }
        if (in_array($field, $recommended, true)) {
            $classes[] = 'maps-import-recommended';
        }
        $importFieldsSelector .= '<label class="maps-import-field-choice"><input type="checkbox" class="'
            . implode(' ', $classes) . '" name="import_export[]" value="'
            . MAPS_importHtml($field) . '"> <code>' . MAPS_importHtml($field) . '</code></label>' . LB;
    }
    $template->set_var('import_fields_selector', $importFieldsSelector);

    $exportFieldsSelector = '';
    foreach (MAPS_getImportFieldOrder() as $field) {
        $exportFieldsSelector .= '<label class="maps-import-field-choice"><input type="checkbox" name="import_export[]" value="'
            . MAPS_importHtml($field) . '"> <code>' . MAPS_importHtml($field) . '</code></label>' . LB;
    }
    $template->set_var('export_fields_selector', $exportFieldsSelector);
    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);

    return $template->parse('output', 'import_export');
}'''
text, n = re.subn(r'function getImportExportForm\(\)\n\{.*?\n\}', new_form, text, count=1, flags=re.S)
if n != 1:
    raise SystemExit('getImportExportForm replacement failed')

old = """        $marker = array_fill_keys(MAPS_getFieldsImportExport(), '');
        foreach ($fields as $index => $field) {"""
new = """        $marker = array_fill_keys(MAPS_getFieldsImportExport(), '');
        $marker['_source_line'] = $line;
        $marker['_geocoded'] = 0;
        foreach ($fields as $index => $field) {"""
if old not in text:
    raise SystemExit('import marker initialization pattern missing')
text = text.replace(old, new, 1)

old = """            if (!MAPS_getCoords($marker['address'], $lat, $lng)) {
                $error = 'Unable to geocode marker on line ' . $line . '.';
                break;
            }
            $marker['lat'] = $lat;"""
new = """            if (!MAPS_getCoords($marker['address'], $lat, $lng)) {
                $error = 'Unable to geocode marker on line ' . $line . '.';
                break;
            }
            $marker['_geocoded'] = 1;
            $marker['lat'] = $lat;"""
if old not in text:
    raise SystemExit('geocode pattern missing')
text = text.replace(old, new, 1)

preview = r'''function MAPS_importPreview($rows, $mid, $separator, $fields, $filename)
{
    global $_CONF, $_TABLES, $_USER, $LANG_MAPS_1;

    $mapName = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $mid);
    $total = count($rows);
    $geocoded = 0;
    $partial = 0;
    foreach ($rows as $marker) {
        if (!empty($marker['_geocoded'])) {
            $geocoded++;
        }
        if (trim((string) $marker['code']) === '' || trim((string) $marker['city']) === '') {
            $partial++;
        }
    }
    $providedCoordinates = $total - $geocoded;
    $permissions = MAPS_importPermissionValues();
    $ownerName = COM_getDisplayName((int) $_USER['uid']);
    $groupName = MAPS_importGroupName();

    $html = '<section class="maps-import-assistant maps-import-preview">'
        . '<div class="maps-import-step is-active"><span>2</span><div><strong>'
        . MAPS_importHtml($LANG_MAPS_1['import_preview_title']) . '</strong><p>'
        . MAPS_importHtml($LANG_MAPS_1['import_preview_text']) . '</p></div></div>';
    $html .= '<div class="maps-import-summary">'
        . '<div><strong>' . $total . '</strong><span>' . MAPS_importHtml($LANG_MAPS_1['import_summary_rows']) . '</span></div>'
        . '<div><strong>' . $providedCoordinates . '</strong><span>' . MAPS_importHtml($LANG_MAPS_1['import_summary_coordinates']) . '</span></div>'
        . '<div><strong>' . $geocoded . '</strong><span>' . MAPS_importHtml($LANG_MAPS_1['import_summary_geocoded']) . '</span></div>'
        . '<div><strong>' . $partial . '</strong><span>' . MAPS_importHtml($LANG_MAPS_1['import_summary_partial']) . '</span></div>'
        . '</div>';

    $html .= '<div class="maps-import-table-wrap"><table class="maps-import-preview-table"><thead><tr>'
        . '<th>#</th><th>' . MAPS_importHtml($LANG_MAPS_1['name']) . '</th>'
        . '<th>' . MAPS_importHtml($LANG_MAPS_1['address']) . '</th>'
        . '<th>' . MAPS_importHtml($LANG_MAPS_1['code']) . '</th>'
        . '<th>' . MAPS_importHtml($LANG_MAPS_1['city']) . '</th>'
        . '<th>Lat.</th><th>Lng.</th><th>' . MAPS_importHtml($LANG_MAPS_1['import_status']) . '</th>'
        . '</tr></thead><tbody>';

    foreach ($rows as $index => $marker) {
        $isPartial = trim((string) $marker['code']) === '' || trim((string) $marker['city']) === '';
        $statusClass = $isPartial ? 'is-warning' : 'is-ready';
        $statusText = $isPartial ? $LANG_MAPS_1['import_status_partial'] : $LANG_MAPS_1['import_status_ready'];
        if (!empty($marker['_geocoded'])) {
            $statusText .= ' · ' . $LANG_MAPS_1['import_status_geocoded'];
        }
        $html .= '<tr><td>' . ($index + 1) . '</td>'
            . '<td><strong>' . MAPS_importHtml(MAPS_markerDisplayName($marker['name'])) . '</strong></td>'
            . '<td>' . MAPS_importPreviewValue($marker['address']) . '</td>'
            . '<td>' . MAPS_importPreviewValue($marker['code']) . '</td>'
            . '<td>' . MAPS_importPreviewValue(MAPS_normalizeMarkerPlace($marker['city'])) . '</td>'
            . '<td>' . MAPS_importHtml($marker['lat']) . '</td>'
            . '<td>' . MAPS_importHtml($marker['lng']) . '</td>'
            . '<td><span class="maps-import-status ' . $statusClass . '">' . MAPS_importHtml($statusText) . '</span></td></tr>';
    }
    $html .= '</tbody></table></div>';

    $html .= '<div class="maps-import-step is-active"><span>3</span><div><strong>'
        . MAPS_importHtml($LANG_MAPS_1['import_confirm_title']) . '</strong><p>'
        . MAPS_importHtml($LANG_MAPS_1['import_confirm_text']) . '</p></div></div>';
    $html .= '<dl class="maps-import-confirm-summary">'
        . '<dt>' . MAPS_importHtml($LANG_MAPS_1['map_label']) . '</dt><dd>' . MAPS_importHtml($mapName) . '</dd>'
        . '<dt>' . MAPS_importHtml($LANG_MAPS_1['section_ownership']) . '</dt><dd>' . MAPS_importHtml($ownerName) . '</dd>'
        . '<dt>' . MAPS_importHtml($LANG_MAPS_1['group']) . '</dt><dd>' . MAPS_importHtml($groupName) . '</dd>'
        . '<dt>' . MAPS_importHtml($LANG_MAPS_1['section_permissions']) . '</dt><dd>'
        . MAPS_importHtml('Owner ' . MAPS_permissionLabel($permissions[0])
            . ' · Group ' . MAPS_permissionLabel($permissions[1])
            . ' · Members ' . MAPS_permissionLabel($permissions[2])
            . ' · Anonymous ' . MAPS_permissionLabel($permissions[3])) . '</dd>'
        . '</dl>';

    $token = SEC_createToken();
    $action = MAPS_importHtml($_CONF['site_admin_url'] . '/plugins/maps/import_export.php');
    $html .= '<form class="maps-import-confirm-actions" action="' . $action . '" method="post">'
        . '<input type="hidden" name="mode" value="valid">'
        . '<input type="hidden" name="filename" value="' . MAPS_importHtml($filename) . '">'
        . '<input type="hidden" name="mid" value="' . (int) $mid . '">'
        . '<input type="hidden" name="separator_in" value="' . MAPS_importHtml($separator) . '">'
        . '<input type="hidden" name="' . MAPS_importHtml(CSRF_TOKEN) . '" value="' . MAPS_importHtml($token) . '">';

    foreach (MAPS_filterImportExportFields($fields) as $field) {
        $html .= '<input type="hidden" name="import_export[]" value="' . MAPS_importHtml($field) . '">';
    }

    $html .= '<button class="maps-import-primary" type="submit" name="confirm" value="yes">'
        . MAPS_importHtml(sprintf($LANG_MAPS_1['import_confirm_button'], $total)) . '</button> '
        . '<button type="submit" name="confirm" value="no">'
        . MAPS_importHtml($LANG_MAPS_1['import_cancel_button']) . '</button>'
        . '</form></section>';

    return $html;
}'''
text, n = re.subn(r'function MAPS_importPreview\(\$rows, \$mid, \$separator, \$fields, \$filename\)\n\{.*?\n\}', preview, text, count=1, flags=re.S)
if n != 1:
    raise SystemExit('preview replacement failed')

helper_anchor = "/**\n * Generate an unused BIGINT marker id."
import_helpers = '''/**
 * Render one preview cell while making missing information explicit.
 */
function MAPS_importPreviewValue($value)
{
    $value = trim((string) $value);
    return $value === '' ? '<span class="maps-marker-empty">—</span>' : MAPS_importHtml($value);
}

function MAPS_importPermissionValues()
{
    global $_MAPS_CONF;

    $permissions = MAPS_arrayGet($_MAPS_CONF, 'default_permissions', array(3, 3, 2, 2));
    if (!is_array($permissions) || count($permissions) < 4) {
        $permissions = array(3, 3, 2, 2);
    }
    for ($i = 0; $i < 4; $i++) {
        $permissions[$i] = max(0, min(3, (int) $permissions[$i]));
    }
    return array($permissions[0], $permissions[1], $permissions[2], $permissions[3]);
}

function MAPS_importGroupId()
{
    global $_TABLES;

    $groupId = (int) DB_getItem($_TABLES['groups'], 'grp_id', "grp_name='Maps Admin'");
    return $groupId > 0 ? $groupId : 1;
}

function MAPS_importGroupName()
{
    global $_TABLES;

    $groupId = MAPS_importGroupId();
    $name = DB_getItem($_TABLES['groups'], 'grp_name', 'grp_id=' . $groupId);
    return $name !== '' ? $name : 'Maps Admin';
}

function MAPS_permissionLabel($permission)
{
    $permission = (int) $permission;
    if ($permission === 3) {
        return 'Read/Edit';
    }
    if ($permission === 2) {
        return 'Read';
    }
    if ($permission === 1) {
        return 'Edit';
    }
    return 'None';
}

'''
if 'function MAPS_importPermissionValues()' not in text:
    text = text.replace(helper_anchor, import_helpers + helper_anchor, 1)

old = """    $insertedMarkerIds = array();
    $now = date('Y-m-d H:i:s');

    foreach ($rows as $marker) {"""
new = """    $insertedMarkerIds = array();
    $now = date('Y-m-d H:i:s');
    $permissions = MAPS_importPermissionValues();
    $groupId = MAPS_importGroupId();

    foreach ($rows as $marker) {"""
if old not in text:
    raise SystemExit('commit pre-loop pattern missing')
text = text.replace(old, new, 1)

old = """        $columns = array(
            'mkid', 'mid', 'owner_id', 'created', 'modified',
            'validity_start', 'validity_end', 'remark'
        );
        $values = array(
            \"'\" . MAPS_dbEscape($importMarkerId) . \"'\",
            (string) $mid,
            (string) (int) $_USER['uid'],
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"''\"
        );"""
new = """        $columns = array(
            'mkid', 'mid', 'owner_id', 'group_id', 'perm_owner', 'perm_group',
            'perm_members', 'perm_anon', 'created', 'modified',
            'validity_start', 'validity_end', 'remark'
        );
        $values = array(
            \"'\" . MAPS_dbEscape($importMarkerId) . \"'\",
            (string) $mid,
            (string) (int) $_USER['uid'],
            (string) $groupId,
            (string) $permissions[0],
            (string) $permissions[1],
            (string) $permissions[2],
            (string) $permissions[3],
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"'\" . MAPS_dbEscape($now) . \"'\",
            \"''\"
        );"""
if old not in text:
    raise SystemExit('commit columns pattern missing')
text = text.replace(old, new, 1)
p.write_text(text)

template = '''<script type="text/javascript">
(function () {
    function toggleAll(formId, source) {
        var form = document.getElementById(formId);
        if (!form) { return; }
        var boxes = form.querySelectorAll('input[name="import_export[]"]');
        for (var i = 0; i < boxes.length; i++) { boxes[i].checked = source.checked; }
    }
    function setImportPreset(className) {
        var form = document.getElementById('maps-import-form');
        if (!form) { return; }
        var boxes = form.querySelectorAll('input[name="import_export[]"]');
        for (var i = 0; i < boxes.length; i++) {
            boxes[i].checked = className !== '' && boxes[i].classList.contains(className);
        }
    }
    window.MAPS_toggleImportFields = function (source) { toggleAll('maps-import-form', source); };
    window.MAPS_toggleExportFields = function (source) { toggleAll('maps-export-form', source); };
    window.MAPS_importPreset = setImportPreset;
}());
</script>

<div class="maps-import-assistant">
    <div class="maps-import-steps" aria-label="Import workflow">
        <div class="maps-import-step is-active"><span>1</span><div><strong>{import_step_1}</strong><p>{import_step_1_text}</p></div></div>
        <div class="maps-import-step"><span>2</span><div><strong>{import_step_2}</strong><p>{import_step_2_text}</p></div></div>
        <div class="maps-import-step"><span>3</span><div><strong>{import_step_3}</strong><p>{import_step_3_text}</p></div></div>
    </div>
    <form id="maps-import-form" class="maps-admin-form maps-import-form" action="{site_admin_url}/plugins/maps/import_export.php" method="post" enctype="multipart/form-data">
        {csrf_token}
        <fieldset class="maps-import-panel">
            <legend>{import}</legend>
            <p>{import_message}</p>
            <div class="maps-import-requirements">
                <div><strong>{import_minimum}</strong><p>{import_minimum_help}</p></div>
                <div><strong>{import_recommended}</strong><p>{import_recommended_help}</p></div>
            </div>
            <div class="maps-import-core-fields">
                <label><span>{mid_label}</span><select name="mid">{map_options}</select></label>
                <label><span>{select_file}</span><input type="file" name="file" accept=".csv,text/csv" required></label>
                <label><span>{separator_in}</span><select name="separator_in">{separator_options_in}</select></label>
            </div>
            <div class="maps-import-field-header">
                <div><strong>{choose_fields_import}</strong><p>{import_order_help}</p></div>
                <div class="maps-admin-actions">
                    <button type="button" class="maps-secondary-action" onclick="MAPS_importPreset('maps-import-minimum')">{import_select_minimum}</button>
                    <button type="button" class="maps-secondary-action" onclick="MAPS_importPreset('maps-import-recommended')">{import_select_recommended}</button>
                    <button type="button" class="maps-secondary-action" onclick="MAPS_importPreset('')">{import_clear_fields}</button>
                </div>
            </div>
            <div class="maps-import-fields-grid">{import_fields_selector}</div>
            <p><label><input type="checkbox" onclick="MAPS_toggleImportFields(this)"> {checkall}</label></p>
            <input type="hidden" name="mode" value="import">
            <p><input type="submit" name="submit" value="{import}"></p>
        </fieldset>
    </form>
</div>

<details class="maps-import-export-secondary">
    <summary>{export}</summary>
    <form id="maps-export-form" class="maps-admin-form" action="{site_admin_url}/plugins/maps/import_export.php" method="post">
        {csrf_token}
        <fieldset class="maps-import-panel">
            <legend>{export}</legend>
            <p>{export_message}</p>
            <div class="maps-import-core-fields">
                <label><span>{mid_label}</span><select name="mid">{map_options}</select></label>
                <label><span>{separator_out}</span><select name="separator_out">{separator_options_out}</select></label>
            </div>
            <p><strong>{choose_fields_export}</strong></p>
            <div class="maps-import-fields-grid">{export_fields_selector}</div>
            <p><label><input type="checkbox" onclick="MAPS_toggleExportFields(this)"> {checkall}</label></p>
            <input type="hidden" name="mode" value="export">
            <p><input type="submit" name="submit" value="{export}"></p>
        </fieldset>
    </form>
</details>
'''
Path('templates/import_export_form.thtml').write_text(template)

lang_additions = {
    'language/english.php': {
        'import_step_1': 'Prepare the import',
        'import_step_1_text': 'Choose the destination map, CSV file, delimiter and column order.',
        'import_step_2': 'Check the data',
        'import_step_2_text': 'Maps validates, normalizes and geocodes the rows before anything is written.',
        'import_step_3': 'Confirm the import',
        'import_step_3_text': 'Review the destination, owner and permissions, then confirm the batch.',
        'import_minimum': 'Minimum fields',
        'import_minimum_help': 'name + address, or name + lat + lng. The Minimum preset uses the address-based option.',
        'import_recommended': 'Recommended fields',
        'import_recommended_help': 'name, address, lat, lng, description, street, code, city, state, country, tel and web.',
        'import_order_help': 'CSV columns must follow the same order as the selected fields shown below.',
        'import_select_minimum': 'Minimum fields',
        'import_select_recommended': 'Recommended fields',
        'import_clear_fields': 'Clear selection',
        'import_preview_title': 'Check the data',
        'import_preview_text': 'These are the normalized values that will be written if you confirm the import.',
        'import_summary_rows': 'rows ready',
        'import_summary_coordinates': 'coordinates supplied',
        'import_summary_geocoded': 'automatically geocoded',
        'import_summary_partial': 'with partial address details',
        'import_status': 'Status',
        'import_status_ready': 'Ready',
        'import_status_partial': 'Ready · partial details',
        'import_status_geocoded': 'geocoded',
        'import_confirm_title': 'Confirm the import',
        'import_confirm_text': 'Check the batch settings before creating the markers.',
        'import_confirm_button': 'Import %d markers',
        'import_cancel_button': 'Cancel'
    },
    'language/french_france_utf-8.php': {
        'import_step_1': 'Préparer l’import',
        'import_step_1_text': 'Choisissez la carte, le fichier CSV, le séparateur et l’ordre des colonnes.',
        'import_step_2': 'Vérifier les données',
        'import_step_2_text': 'Maps valide, normalise et géocode les lignes avant toute écriture.',
        'import_step_3': 'Confirmer l’import',
        'import_step_3_text': 'Vérifiez la destination, le propriétaire et les permissions avant de confirmer.',
        'import_minimum': 'Champs minimum',
        'import_minimum_help': 'name + address, ou name + lat + lng. Le préréglage Minimum utilise la variante avec adresse.',
        'import_recommended': 'Champs recommandés',
        'import_recommended_help': 'name, address, lat, lng, description, street, code, city, state, country, tel et web.',
        'import_order_help': 'Les colonnes du CSV doivent suivre le même ordre que les champs sélectionnés ci-dessous.',
        'import_select_minimum': 'Champs minimum',
        'import_select_recommended': 'Champs recommandés',
        'import_clear_fields': 'Effacer la sélection',
        'import_preview_title': 'Vérifier les données',
        'import_preview_text': 'Voici les valeurs normalisées qui seront enregistrées si vous confirmez l’import.',
        'import_summary_rows': 'lignes prêtes',
        'import_summary_coordinates': 'coordonnées fournies',
        'import_summary_geocoded': 'géocodées automatiquement',
        'import_summary_partial': 'avec adresse partielle',
        'import_status': 'État',
        'import_status_ready': 'Prêt',
        'import_status_partial': 'Prêt · informations partielles',
        'import_status_geocoded': 'géocodé',
        'import_confirm_title': 'Confirmer l’import',
        'import_confirm_text': 'Vérifiez les paramètres du lot avant de créer les marqueurs.',
        'import_confirm_button': 'Importer %d marqueurs',
        'import_cancel_button': 'Annuler'
    }
}
for path, additions in lang_additions.items():
    lp = Path(path)
    data = lp.read_text()
    if "'import_step_1'" in data:
        continue
    pos = data.find("'checkall'")
    if pos < 0:
        raise SystemExit('language checkall anchor missing: ' + path)
    end = data.find('\n', pos) + 1
    lines = ''
    for key, value in additions.items():
        value = value.replace('\\', '\\\\').replace("'", "\\'")
        lines += "    '" + key + "' => '" + value + "',\n"
    data = data[:end] + lines + data[end:]
    lp.write_text(data)

css = Path('public_html/maps.css')
css_text = css.read_text()
css_block = '''

/* Maps 1.6.0 guided CSV import assistant */
.maps-import-assistant { margin: 0 0 1.25rem; }
.maps-import-steps { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.75rem; margin:0 0 1rem; }
.maps-import-step { display:flex; gap:.65rem; align-items:flex-start; padding:.8rem; border:1px solid rgba(127,127,127,.22); border-radius:9px; opacity:.7; }
.maps-import-step.is-active { opacity:1; background:rgba(127,127,127,.035); }
.maps-import-step > span { display:inline-flex; align-items:center; justify-content:center; flex:0 0 1.8rem; height:1.8rem; border-radius:50%; background:rgba(127,127,127,.13); font-weight:700; }
.maps-import-step p { margin:.15rem 0 0; font-size:.92em; }
.maps-import-panel { margin:0; padding:1rem; border:1px solid rgba(127,127,127,.24); border-radius:9px; }
.maps-import-panel legend { padding:0 .35rem; font-weight:700; }
.maps-import-requirements { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:.75rem; margin:.8rem 0 1rem; }
.maps-import-requirements > div { padding:.75rem; border-left:3px solid rgba(127,127,127,.28); background:rgba(127,127,127,.035); }
.maps-import-requirements p { margin:.2rem 0 0; }
.maps-import-core-fields { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:.8rem; margin:0 0 1rem; }
.maps-import-core-fields label { display:flex; flex-direction:column; gap:.3rem; }
.maps-import-core-fields input[type=file], .maps-import-core-fields select { width:100%; box-sizing:border-box; }
.maps-import-field-header { display:flex; justify-content:space-between; gap:1rem; align-items:flex-start; margin:.9rem 0 .65rem; }
.maps-import-field-header p { margin:.2rem 0 0; }
.maps-import-fields-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:.4rem .75rem; padding:.75rem; border:1px solid rgba(127,127,127,.18); border-radius:8px; }
.maps-import-field-choice { display:flex; align-items:center; gap:.35rem; min-width:0; }
.maps-import-field-choice code { overflow-wrap:anywhere; }
.maps-import-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:.65rem; margin:.8rem 0 1rem; }
.maps-import-summary > div { display:flex; flex-direction:column; padding:.7rem; border:1px solid rgba(127,127,127,.18); border-radius:8px; }
.maps-import-summary strong { font-size:1.35rem; }
.maps-import-summary span { font-size:.9em; opacity:.75; }
.maps-import-table-wrap { overflow-x:auto; margin:0 0 1rem; }
.maps-import-preview-table { width:100%; border-collapse:collapse; }
.maps-import-preview-table th, .maps-import-preview-table td { padding:.55rem .6rem; border-bottom:1px solid rgba(127,127,127,.18); text-align:left; vertical-align:top; white-space:nowrap; }
.maps-import-preview-table td:nth-child(2), .maps-import-preview-table td:nth-child(3) { white-space:normal; min-width:12rem; }
.maps-import-status { display:inline-block; padding:.2rem .45rem; border-radius:999px; font-size:.86em; font-weight:600; }
.maps-import-status.is-ready { background:rgba(46,125,50,.10); }
.maps-import-status.is-warning { background:rgba(184,134,11,.12); }
.maps-import-confirm-summary { display:grid; grid-template-columns:max-content 1fr; gap:.35rem .75rem; padding:.8rem; margin:.75rem 0; background:rgba(127,127,127,.035); border-radius:8px; }
.maps-import-confirm-summary dt { font-weight:700; }
.maps-import-confirm-summary dd { margin:0; }
.maps-import-confirm-actions { display:flex; flex-wrap:wrap; gap:.55rem; align-items:center; }
.maps-import-primary { font-weight:700; }
.maps-import-export-secondary { margin:1rem 0; }
.maps-import-export-secondary > summary { cursor:pointer; font-weight:700; padding:.65rem 0; }
@media (max-width:900px) {
    .maps-import-steps, .maps-import-core-fields { grid-template-columns:1fr; }
    .maps-import-fields-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .maps-import-summary { grid-template-columns:repeat(2,minmax(0,1fr)); }
    .maps-import-field-header { flex-direction:column; }
}
@media (max-width:560px) {
    .maps-import-requirements, .maps-import-fields-grid, .maps-import-summary { grid-template-columns:1fr; }
}
'''
if 'Maps 1.6.0 guided CSV import assistant' not in css_text:
    css.write_text(css_text.rstrip() + css_block + '\n')

notes = Path('RELEASE-NOTES-1.6.0.md')
notes_text = notes.read_text()
note = '- Guided CSV import assistant: minimum/recommended presets, normalized tabular preview, batch summary and confirmation, plus imported-marker permissions aligned with default_permissions.\n'
if note not in notes_text:
    notes.write_text(notes_text.rstrip() + '\n\n' + note)
