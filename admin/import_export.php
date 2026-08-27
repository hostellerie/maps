<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.5.7                                                         |
// +---------------------------------------------------------------------------+
// | import_export.php                                                         |
// +---------------------------------------------------------------------------+

require_once '../../../lib-common.php';
require_once '../../auth.inc.php';
require_once 'edit_functions.php';

$display = '';

if (!SEC_hasRights('maps.admin')) {
    $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
        . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
        . MAPS_compatSiteFooter();
    COM_accessLog("User {$_USER['username']} tried to illegally access the Maps plugin import screen.");
    MAPS_compatOutput($display);
    exit;
}

/**
 * Escape text for safe HTML output.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_importHtml($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/**
 * Return the marker fields that may be imported/exported.
 *
 * @return array
 */
function MAPS_getFieldsImportExport()
{
    return array(
        'address', 'lat', 'lng', 'name', 'description', 'mk_default',
        'mk_pcolor', 'mk_scolor', 'mk_label', 'mk_label_color', 'street',
        'code', 'city', 'state', 'country', 'tel', 'fax', 'web', 'item_1',
        'item_2', 'item_3', 'item_4', 'item_5', 'item_6', 'item_7', 'item_8',
        'item_9', 'item_10'
    );
}

/**
 * Keep only known import/export fields while preserving their submitted order.
 *
 * @param mixed $fields
 * @return array
 */
function MAPS_filterImportExportFields($fields)
{
    $valid = MAPS_getFieldsImportExport();
    $result = array();

    if (!is_array($fields)) {
        return $result;
    }

    foreach ($fields as $field) {
        $field = (string) $field;
        if (in_array($field, $valid, true) && !in_array($field, $result, true)) {
            $result[] = $field;
        }
    }

    return $result;
}

/**
 * Normalize a supported CSV separator.
 *
 * @param mixed $separator
 * @return string
 */
function MAPS_csvSeparator($separator)
{
    $separator = (string) $separator;
    if ($separator === 'tab') {
        return "\t";
    }
    if ($separator === ',') {
        return ',';
    }
    return ';';
}

/**
 * Resolve a generated CSV staging filename safely inside path_data.
 *
 * @param mixed $filename
 * @return string|false
 */
function MAPS_importTempPath($filename)
{
    global $_CONF;

    $filename = (string) $filename;
    if ($filename === '' || basename($filename) !== $filename
        || !preg_match('/^import_markers_[A-Za-z0-9_-]+\.csv$/D', $filename)
    ) {
        return false;
    }

    return rtrim($_CONF['path_data'], '/\\') . DIRECTORY_SEPARATOR . $filename;
}

/**
 * Verify that the requested map exists.
 *
 * @param int $mid
 * @return bool
 */
function MAPS_importMapExists($mid)
{
    global $_TABLES;

    $mid = (int) $mid;
    return $mid > 0 && (int) DB_count($_TABLES['maps_maps'], 'mid', $mid) === 1;
}

/**
 * Build the import/export form.
 *
 * @return string
 */
function getImportExportForm()
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

    $template->set_var('import', $LANG_MAPS_1['import']);
    $template->set_var('import_message', $LANG_MAPS_1['import_message']);
    $template->set_var('export', $LANG_MAPS_1['export']);
    $template->set_var('export_message', $LANG_MAPS_1['export_message']);
    $template->set_var('select_file', $LANG_MAPS_1['select_file']);
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
    $template->set_var('choose_fields_import', $LANG_MAPS_1['choose_fields_import']);
    $template->set_var('choose_fields_export', $LANG_MAPS_1['choose_fields_export']);
    $template->set_var('checkall', $LANG_MAPS_1['checkall']);

    $fieldsSelector = '';
    foreach (MAPS_getFieldsImportExport() as $field) {
        $fieldsSelector .= '<label><input type="checkbox" name="import_export[]" value="'
            . MAPS_importHtml($field) . '"> ' . MAPS_importHtml($field) . '</label><br>' . LB;
    }
    $template->set_var('fields_selector', $fieldsSelector);
    $template->set_var('ok_button', $LANG_MAPS_1['ok_button']);

    return COM_startBlock($LANG_MAPS_1['import_export'])
        . $template->parse('output', 'import_export')
        . COM_endBlock();
}

/**
 * Upload one CSV file into the private Geeklog data directory.
 *
 * @param array $files
 * @return string|false Generated basename on success
 */
function MAPS_stageImportCsv($files)
{
    global $_CONF, $LANG24;

    if (!is_array($files) || empty($files['file']) || !is_array($files['file'])) {
        return false;
    }

    require_once $_CONF['path_system'] . 'classes/upload.class.php';
    $upload = new upload();
    if (isset($_CONF['debug_image_upload']) && $_CONF['debug_image_upload']) {
        $upload->setLogFile($_CONF['path'] . 'logs/error.log');
        $upload->setDebug(true);
    }
    $upload->setMaxFileUploads(1);
    $upload->setAllowedMimeTypes(array(
        'text/csv' => '.csv',
        'text/plain' => '.csv',
        'text/comma-separated-values' => '.csv',
        'application/vnd.ms-excel' => '.csv'
    ));

    if (!$upload->setPath($_CONF['path_data'])) {
        COM_errorLog('MAPS import: path_data is not writable.');
        return false;
    }

    $upload->setPerms('0644');
    $filename = 'import_markers_' . COM_makesid() . '.csv';
    $upload->setFileNames($filename);
    $upload->uploadFiles();

    if ($upload->areErrors()) {
        COM_errorLog('MAPS import upload error: ' . strip_tags($upload->printErrors(false)));
        return false;
    }

    return $filename;
}

/**
 * Parse the staged CSV according to the selected field order.
 *
 * @param string $filename
 * @param string $separator
 * @param array  $fields
 * @param string &$error
 * @return array
 */
function MAPS_readImportCsv($filename, $separator, $fields, &$error)
{
    $error = '';
    $path = MAPS_importTempPath($filename);
    if ($path === false || !is_file($path) || !is_readable($path)) {
        $error = 'Invalid or unavailable import file.';
        return array();
    }

    $fields = MAPS_filterImportExportFields($fields);
    if (empty($fields)) {
        $error = 'No valid import fields were selected.';
        return array();
    }

    $handle = fopen($path, 'rb');
    if ($handle === false) {
        $error = 'Unable to open the import file.';
        return array();
    }

    $delimiter = MAPS_csvSeparator($separator);
    $rows = array();
    $line = 0;

    while (($values = fgetcsv($handle, 0, $delimiter)) !== false) {
        $line++;
        if ($values === array(null) || $values === array()) {
            continue;
        }

        $marker = array_fill_keys(MAPS_getFieldsImportExport(), '');
        foreach ($fields as $index => $field) {
            $marker[$field] = isset($values[$index]) ? trim((string) $values[$index]) : '';
        }

        if ($marker['name'] === '') {
            $error = 'Marker name is missing on line ' . $line . '.';
            break;
        }
        if ($marker['address'] === '' && ($marker['lat'] === '' || $marker['lng'] === '')) {
            $error = 'Address or coordinates are required on line ' . $line . '.';
            break;
        }

        if ($marker['lat'] === '' || $marker['lng'] === '') {
            $lat = '';
            $lng = '';
            if (!MAPS_getCoords($marker['address'], $lat, $lng)) {
                $error = 'Unable to geocode marker on line ' . $line . '.';
                break;
            }
            $marker['lat'] = $lat;
            $marker['lng'] = $lng;
        }

        $lat = MAPS_normalizeNumber($marker['lat'], null);
        $lng = MAPS_normalizeNumber($marker['lng'], null);
        if (!MAPS_isValidCoordinatePair($lat, $lng)) {
            $error = 'Invalid coordinates on line ' . $line . '.';
            break;
        }
        $marker['lat'] = MAPS_canonicalNumberString($lat, 0);
        $marker['lng'] = MAPS_canonicalNumberString($lng, 0);
        $marker['mk_default'] = (string) ((int) $marker['mk_default'] === 0 ? 0 : 1);
        $marker['mk_label_color'] = (string) ((int) $marker['mk_label_color'] === 1 ? 1 : 0);
        $marker['mk_pcolor'] = MAPS_htmlColor($marker['mk_pcolor'], '#666666');
        $marker['mk_scolor'] = MAPS_htmlColor($marker['mk_scolor'], '#666666');
        $marker['mk_label'] = substr($marker['mk_label'], 0, 1);

        $rows[] = $marker;
    }

    fclose($handle);

    if ($error !== '') {
        return array();
    }

    return $rows;
}

/**
 * Render a safe preview of imported markers and confirmation form.
 *
 * @param array  $rows
 * @param int    $mid
 * @param string $separator
 * @param array  $fields
 * @param string $filename
 * @return string
 */
function MAPS_importPreview($rows, $mid, $separator, $fields, $filename)
{
    global $_CONF, $_TABLES, $LANG_MAPS_1;

    $mapName = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $mid);
    $html = '<p>' . MAPS_importHtml($LANG_MAPS_1['markers_to_add']) . ' '
        . MAPS_importHtml($mapName) . '</p><ul>';

    foreach ($rows as $index => $marker) {
        $html .= '<li>#' . ($index + 1)
            . ' — ' . MAPS_importHtml($marker['name'])
            . ' — ' . MAPS_importHtml($marker['address'])
            . ' [' . MAPS_importHtml($marker['lat']) . ', ' . MAPS_importHtml($marker['lng']) . ']</li>';
    }
    $html .= '</ul>';

    $token = SEC_createToken();
    $action = MAPS_importHtml($_CONF['site_admin_url'] . '/plugins/maps/import_export.php');
    $html .= '<form action="' . $action . '" method="post">'
        . '<input type="hidden" name="mode" value="valid">'
        . '<input type="hidden" name="filename" value="' . MAPS_importHtml($filename) . '">'
        . '<input type="hidden" name="mid" value="' . (int) $mid . '">'
        . '<input type="hidden" name="separator_in" value="' . MAPS_importHtml($separator) . '">'
        . '<input type="hidden" name="' . MAPS_importHtml(CSRF_TOKEN) . '" value="' . MAPS_importHtml($token) . '">';

    foreach (MAPS_filterImportExportFields($fields) as $field) {
        $html .= '<input type="hidden" name="import_export[]" value="' . MAPS_importHtml($field) . '">';
    }

    $html .= '<button type="submit" name="confirm" value="yes">'
        . MAPS_importHtml($LANG_MAPS_1['yes']) . '</button> '
        . '<button type="submit" name="confirm" value="no">'
        . MAPS_importHtml($LANG_MAPS_1['no']) . '</button>'
        . '</form>';

    return $html;
}

/**
 * Generate an unused BIGINT marker id.
 *
 * @return string
 */
function MAPS_importMarkerId()
{
    global $_TABLES;

    do {
        $mkid = date('ymdHis') . sprintf('%06d', mt_rand(0, 999999));
    } while ((int) DB_count($_TABLES['maps_markers'], 'mkid', $mkid) > 0);

    return $mkid;
}

/**
 * Insert parsed CSV rows. The parent map lifecycle is emitted once per batch.
 *
 * @param array $rows
 * @param int   $mid
 * @return int Number inserted
 */
function MAPS_commitImportRows($rows, $mid)
{
    global $_TABLES, $_USER;

    $mid = (int) $mid;
    if (!MAPS_importMapExists($mid)) {
        return 0;
    }

    $validFields = MAPS_getFieldsImportExport();
    $inserted = 0;
    $insertedMarkerIds = array();
    $now = date('Y-m-d H:i:s');

    foreach ($rows as $marker) {
        $importMarkerId = MAPS_importMarkerId();
        $columns = array(
            'mkid', 'mid', 'owner_id', 'created', 'modified',
            'validity_start', 'validity_end', 'remark'
        );
        $values = array(
            "'" . MAPS_dbEscape($importMarkerId) . "'",
            (string) $mid,
            (string) (int) $_USER['uid'],
            "'" . MAPS_dbEscape($now) . "'",
            "'" . MAPS_dbEscape($now) . "'",
            "'" . MAPS_dbEscape($now) . "'",
            "'" . MAPS_dbEscape($now) . "'",
            "''"
        );

        foreach ($validFields as $field) {
            $columns[] = $field;
            $values[] = "'" . MAPS_dbEscape(isset($marker[$field]) ? $marker[$field] : '') . "'";
        }

        DB_query(
            "INSERT INTO {$_TABLES['maps_markers']} (" . implode(',', $columns) . ') VALUES ('
            . implode(',', $values) . ')'
        );
        if (!DB_error()) {
            $inserted++;
            $insertedMarkerIds[] = $importMarkerId;
        }
    }

    if ($inserted > 0) {
        foreach ($insertedMarkerIds as $insertedMarkerId) {
            MAPS_notifyMarkerSaved($insertedMarkerId, 0, 0, false);
        }
        updateMap($mid);
    }

    return $inserted;
}

/**
 * Export selected marker fields as CSV.
 *
 * @param int    $mid
 * @param string $separator
 * @param array  $fields
 * @return void
 */
function MAPS_exportCSV($mid, $separator, $fields)
{
    global $_CONF, $_TABLES, $LANG_MAPS_1;

    $mid = (int) $mid;
    $fields = MAPS_filterImportExportFields($fields);
    if (!MAPS_importMapExists($mid) || empty($fields)) {
        $display = MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name'])
            . MAPS_admin_menu()
            . MAPS_message($LANG_MAPS_1['no_marker_to_export'])
            . MAPS_compatSiteFooter(0);
        MAPS_compatOutput($display);
        exit;
    }

    $result = DB_query(
        'SELECT ' . implode(',', $fields)
        . " FROM {$_TABLES['maps_markers']} WHERE mid={$mid}"
    );
    if (DB_numRows($result) < 1) {
        $display = MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name'])
            . MAPS_admin_menu()
            . MAPS_message($LANG_MAPS_1['no_marker_to_export'])
            . MAPS_compatSiteFooter(0);
        MAPS_compatOutput($display);
        exit;
    }

    $siteName = preg_replace('/[^A-Za-z0-9_-]+/', '_', $_CONF['site_name']);
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="map_' . $mid . '_' . $siteName . '.csv"');
    header('X-Content-Type-Options: nosniff');

    $fp = fopen('php://output', 'wb');
    $delimiter = MAPS_csvSeparator($separator);
    while ($row = DB_fetchArray($result, false)) {
        fputcsv($fp, $row, $delimiter, '"');
    }
    fclose($fp);
    exit;
}

// MAIN
$mode = isset($_POST['mode']) ? COM_applyFilter($_POST['mode']) : '';

if ($mode !== '') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !SEC_checkToken()) {
        COM_accessLog('Invalid CSRF token on Maps import/export action.');
        $display .= MAPS_compatSiteHeader('menu', $MESSAGE[30])
            . COM_showMessageText($MESSAGE[29], $MESSAGE[30])
            . MAPS_compatSiteFooter();
        MAPS_compatOutput($display);
        exit;
    }

    $mid = isset($_POST['mid']) ? (int) $_POST['mid'] : 0;
    $fields = MAPS_filterImportExportFields(isset($_POST['import_export']) ? $_POST['import_export'] : array());

    if ($mode === 'export') {
        MAPS_exportCSV(
            $mid,
            isset($_POST['separator_out']) ? $_POST['separator_out'] : ';',
            $fields
        );
    }

    $display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
    $display .= MAPS_admin_menu();

    if (!MAPS_importMapExists($mid)) {
        $display .= MAPS_message('Invalid map.');
        $display .= getImportExportForm();
    } elseif ($mode === 'import') {
        $filename = MAPS_stageImportCsv($_FILES);
        if ($filename === false) {
            $display .= MAPS_message('Unable to upload the CSV file.');
            $display .= getImportExportForm();
        } else {
            $separator = isset($_POST['separator_in']) ? (string) $_POST['separator_in'] : ';';
            $error = '';
            $rows = MAPS_readImportCsv($filename, $separator, $fields, $error);
            if ($error !== '') {
                $path = MAPS_importTempPath($filename);
                if ($path !== false && is_file($path)) {
                    @unlink($path);
                }
                $display .= MAPS_message(MAPS_importHtml($error));
                $display .= getImportExportForm();
            } else {
                $display .= MAPS_importPreview($rows, $mid, $separator, $fields, $filename);
            }
        }
    } elseif ($mode === 'valid') {
        $filename = isset($_POST['filename']) ? (string) $_POST['filename'] : '';
        $path = MAPS_importTempPath($filename);
        $confirm = isset($_POST['confirm']) ? COM_applyFilter($_POST['confirm']) : 'no';

        if ($path === false || !is_file($path)) {
            $display .= MAPS_message('Invalid or expired import file.');
            $display .= getImportExportForm();
        } elseif ($confirm !== 'yes') {
            @unlink($path);
            $display .= getImportExportForm();
        } else {
            $separator = isset($_POST['separator_in']) ? (string) $_POST['separator_in'] : ';';
            $error = '';
            $rows = MAPS_readImportCsv($filename, $separator, $fields, $error);
            if ($error !== '') {
                @unlink($path);
                $display .= MAPS_message(MAPS_importHtml($error));
                $display .= getImportExportForm();
            } else {
                $inserted = MAPS_commitImportRows($rows, $mid);
                @unlink($path);
                $mapName = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . $mid);
                $display .= '<p>' . MAPS_importHtml($LANG_MAPS_1['markers_added']) . ' '
                    . MAPS_importHtml($mapName) . ': ' . (int) $inserted . '</p>';
                $display .= getImportExportForm();
            }
        }
    } else {
        $display .= getImportExportForm();
    }

    $display .= MAPS_compatSiteFooter(0);
    MAPS_compatOutput($display);
    exit;
}

$display .= MAPS_compatSiteHeader('menu', $LANG_MAPS_1['plugin_name']);
$display .= MAPS_admin_menu();
$display .= getImportExportForm();
$display .= MAPS_compatSiteFooter(0);
MAPS_compatOutput($display);
