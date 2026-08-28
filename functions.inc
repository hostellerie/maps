<?php

/* Reminder: always indent with 4 spaces (no tabs). */
// +---------------------------------------------------------------------------+
// | Maps Plugin 1.6.0                                                         |
// +---------------------------------------------------------------------------+
// | functions.inc                                                             |
// |                                                                           |
// | Geeklog plugin API and common Maps functions.                             |
// +---------------------------------------------------------------------------+
// | Copyright (C) 2010-2026                                                   |
// | Original author: ::Ben                                                      |
// +---------------------------------------------------------------------------+

if (strpos(strtolower(isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : ''), 'functions.inc') !== false) {
    die('This file can not be used on its own.');
}

$plugin_path = $_CONF['path'] . 'plugins/maps/';
$langfile = $plugin_path . 'language/' . $_CONF['language'] . '.php';
if (file_exists($langfile)) {
    require_once $langfile;
} else {
    require_once $plugin_path . 'language/english.php';
}

require_once $_CONF['path_system'] . 'classes/config.class.php';
$c = config::get_instance();
$_MAPS_CONF = $c->get_config('maps');
if (!is_array($_MAPS_CONF)) {
    $_MAPS_CONF = array();
}
require_once $_CONF['path'] . 'plugins/maps/maps.php';
require_once $_CONF['path'] . 'plugins/maps/services.inc.php';


/**
 * Return true when the current Geeklog installation provides the document API.
 *
 * @return bool
 */
function MAPS_usesModernDocument()
{
    return function_exists('COM_createHTMLDocument');
}

/**
 * Compatibility wrapper for page start.
 *
 * Geeklog 2.1.1 and later provide COM_createHTMLDocument(). The legacy
 * renderer is kept only as a defensive fallback for environments where that
 * function is genuinely unavailable.
 *
 * @return string
 */
function MAPS_compatSiteHeader()
{
    global $_MAPS_RENDER_TITLE, $_MAPS_RENDER_WHAT;

    $args = func_get_args();
    $_MAPS_RENDER_WHAT = isset($args[0]) ? $args[0] : 'menu';
    if (isset($args[1]) && $args[1] !== '') {
        $_MAPS_RENDER_TITLE = $args[1];
    }

    if (MAPS_usesModernDocument()) {
        return '';
    }

    return call_user_func_array('COM_' . 'siteHeader', $args);
}

/**
 * Compatibility wrapper for page end.
 *
 * @return string
 */
function MAPS_compatSiteFooter()
{
    if (MAPS_usesModernDocument()) {
        return '';
    }

    return call_user_func_array('COM_' . 'siteFooter', func_get_args());
}

/**
 * Output a complete Geeklog page on both supported Geeklog generations.
 *
 * @param string $content
 * @param string $title
 * @return void
 */
function MAPS_compatOutput($content, $title = '')
{
    global $_MAPS_RENDER_TITLE, $_MAPS_RENDER_WHAT;

    if (MAPS_usesModernDocument()) {
        if ($title === '' && isset($_MAPS_RENDER_TITLE)) {
            $title = $_MAPS_RENDER_TITLE;
        }
        $options = array();
        if ($title !== '') {
            $options['pagetitle'] = $title;
        }
        if (isset($_MAPS_RENDER_WHAT) && $_MAPS_RENDER_WHAT !== '') {
            $options['what'] = $_MAPS_RENDER_WHAT;
        }
        $content = COM_createHTMLDocument($content, $options);
    }

    COM_output($content);
}

/**
 * Escape a value for SQL while keeping compatibility with older Geeklog.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_dbEscape($value)
{
    if (function_exists('DB_escapeString')) {
        return DB_escapeString((string) $value);
    }
    return addslashes((string) $value);
}

/**
 * Ensure a configured Maps directory exists and is writable.
 *
 * Missing image directories can occur after manual installs, migrations or
 * archive extraction. Create them recursively when the parent path permits it,
 * then verify the resulting directory before uploads are attempted.
 *
 * @param mixed $path
 * @return bool
 */
function MAPS_ensureWritableDirectory($path)
{
    $path = rtrim((string) $path, '/\\');
    if ($path === '') {
        return false;
    }

    if (!is_dir($path)) {
        $oldUmask = umask(0);
        $created = @mkdir($path, 0755, true);
        umask($oldUmask);

        if (!$created && !is_dir($path)) {
            COM_errorLog('MAPS could not create directory: ' . $path);
            return false;
        }
    }

    if (!is_writable($path)) {
        COM_errorLog('MAPS directory is not writable: ' . $path);
        return false;
    }

    return true;
}

/**
 * Decode HTML entities that may have been stored by historical input filters.
 * Output contexts must still apply their own escaping afterwards.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_decodeStoredText($value)
{
    return html_entity_decode(stripslashes((string) $value), ENT_QUOTES, 'UTF-8');
}

/**
 * Normalize a single-line marker field without changing intentional casing.
 *
 * Leading/trailing whitespace is removed and runs of spaces/tabs/newlines are
 * collapsed to a single space. This prevents invisible prefixes from breaking
 * alphabetical sorting while preserving names and acronyms entered by users.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_normalizeMarkerText($value)
{
    $value = MAPS_decodeStoredText($value);
    $value = preg_replace('/\s+/u', ' ', trim($value));
    return ($value === null) ? '' : $value;
}

/**
 * Normalize a geographic marker field (city/state/country).
 *
 * Mixed-case values are preserved. Values entered entirely in lower-case or
 * upper-case are converted to title case when mbstring is available. This
 * turns e.g. LANESTER / lanester into Lanester without altering mixed-case
 * names such as Saint-Jean-d'Angély that were already deliberately entered.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_normalizeMarkerPlace($value)
{
    $value = MAPS_normalizeMarkerText($value);
    if ($value === '' || !function_exists('mb_strtoupper') || !function_exists('mb_strtolower')
        || !function_exists('mb_convert_case')
    ) {
        return $value;
    }

    $upper = mb_strtoupper($value, 'UTF-8');
    $lower = mb_strtolower($value, 'UTF-8');
    if ($value === $upper || $value === $lower) {
        return mb_convert_case($lower, MB_CASE_TITLE, 'UTF-8');
    }

    return $value;
}

/**
 * Return a marker name in the consistent public display casing.
 * Stored data is never rewritten: users remain free to enter the name as they wish.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_markerDisplayName($value)
{
    $value = MAPS_normalizeMarkerText($value);
    if ($value === '') {
        return '';
    }
    return function_exists('mb_strtoupper')
        ? mb_strtoupper($value, 'UTF-8')
        : strtoupper($value);
}

/**
 * Render a discreet placeholder for an empty public marker field.
 *
 * @return string
 */
function MAPS_markerEmptyValue()
{
    return '<span class="maps-marker-empty" aria-label="Not provided">—</span>';
}

/**
 * Render a compact accessible status badge for administration lists.
 *
 * @param mixed  $state
 * @param string $trueLabel
 * @param string $falseLabel
 * @param string $trueClass
 * @param string $falseClass
 * @return string
 */
function MAPS_adminStatusBadge($state, $trueLabel, $falseLabel, $trueClass = 'is-positive', $falseClass = 'is-muted')
{
    $enabled = ((int) $state === 1);
    $label = $enabled ? $trueLabel : $falseLabel;
    $class = $enabled ? $trueClass : $falseClass;

    return '<span class="maps-status-badge ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</span>';
}

/**
 * Return a JavaScript-safe quoted string.
 *
 * @param mixed $value
 * @return string
 */
function MAPS_jsString($value)
{
    $json = json_encode((string) $value);
    return ($json === false) ? '""' : $json;
}

/**
 * Serialize a numeric value as a quoted JavaScript value.
 * Number() in the generated script then performs the conversion without
 * exposing locale-dependent or malformed legacy numeric strings to the parser.
 *
 * @param mixed $value
 * @param mixed $default
 * @return string
 */
function MAPS_normalizeNumber($value, $default = 0)
{
    if (is_string($value)) {
        $value = trim($value);
        // Legacy Maps installations may contain locale-formatted coordinates
        // such as "48,364407". JavaScript requires a decimal point.
        $value = str_replace(array("\xc2\xa0", ' '), '', $value);
        if (strpos($value, ',') !== false && strpos($value, '.') === false) {
            $value = str_replace(',', '.', $value);
        }
    }

    if (!is_numeric($value)) {
        $value = $default;
    }

    return $value;
}

function MAPS_coordinate($value, $minimum, $maximum, $default = 0)
{
    $value = MAPS_normalizeNumber($value, $default);
    $number = (float) $value;
    if ($number < (float) $minimum || $number > (float) $maximum) {
        $number = (float) $default;
    }

    return $number;
}

function MAPS_latitude($value, $default = 0)
{
    return MAPS_coordinate($value, -90, 90, $default);
}

function MAPS_longitude($value, $default = 0)
{
    return MAPS_coordinate($value, -180, 180, $default);
}

function MAPS_isValidCoordinatePair($lat, $lng)
{
    $latValue = MAPS_normalizeNumber($lat, null);
    $lngValue = MAPS_normalizeNumber($lng, null);
    if ($latValue === null || $lngValue === null) {
        return false;
    }

    $latValue = (float) $latValue;
    $lngValue = (float) $lngValue;
    return $latValue >= -90 && $latValue <= 90 && $lngValue >= -180 && $lngValue <= 180;
}

function MAPS_zoom($value, $default = 2)
{
    $value = (int) MAPS_normalizeNumber($value, $default);
    if ($value < 0 || $value > 22) {
        $value = (int) $default;
    }
    return $value;
}

/**
 * Return a locale-independent decimal representation for JavaScript.
 *
 * PHP 5.x may format a float with the active locale when it is converted
 * back to a string (for example 46.227638 can become 46,227638 under a
 * French locale). JavaScript Number() does not accept that representation.
 * Upper-case F in sprintf() is deliberately locale-independent.
 *
 * @param mixed $value
 * @param mixed $default
 * @return string
 */
function MAPS_canonicalNumberString($value, $default = 0)
{
    $value = MAPS_normalizeNumber($value, $default);
    if (!is_numeric($value)) {
        $value = MAPS_normalizeNumber($default, 0);
    }

    $number = (float) $value;
    if (is_nan($number) || is_infinite($number)) {
        $number = (float) MAPS_normalizeNumber($default, 0);
    }

    $text = sprintf('%.14F', $number);
    $text = rtrim(rtrim($text, '0'), '.');

    if ($text === '' || $text === '-0') {
        $text = '0';
    }

    return $text;
}

function MAPS_jsNumber($value, $default = 0)
{
    return MAPS_jsString(MAPS_canonicalNumberString($value, $default));
}

/**
 * Safe array getter for PHP 5.6 through 8.1.
 *
 * @param array  $array
 * @param string $key
 * @param mixed  $default
 * @return mixed
 */
function MAPS_arrayGet($array, $key, $default = '')
{
    return (is_array($array) && isset($array[$key])) ? $array[$key] : $default;
}

/**
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

/**
 * Render configured marker custom fields for public output.
 *
 * A field is public only when both its configured label and stored value are
 * non-empty. Values keep Maps' historical autotag support through
 * PLG_replaceTags(), while labels are always escaped.
 *
 * @param array  $marker
 * @param string $layout compact for info windows, detail for marker pages
 * @return string
 */
function MAPS_renderPublicCustomFields($marker, $layout = 'detail')
{
    global $_MAPS_CONF;

    $html = '';
    for ($i = 1; $i <= 10; $i++) {
        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));
        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));
        $placeholderLabels = array(
            'Custom field ' . $i,
            'Ressource #' . $i,
            'Resource #' . $i
        );
        // Empty/default labels mean that this resource field is not configured.
        // Keep the admin warning in the editor, but never expose placeholders
        // or empty resource rows on public marker pages.
        if ($label === '' || in_array($label, $placeholderLabels, true)) {
            continue;
        }
        if ($value === '' && $layout === 'compact') {
            continue;
        }

        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        if ($value === '') {
            $renderedValue = MAPS_markerEmptyValue();
        } else {
            $expandedValue = PLG_replaceTags(stripslashes($value));
            $plainValue = trim(strip_tags((string) $expandedValue));
            $renderedValue = $plainValue === ''
                ? MAPS_markerEmptyValue()
                : nl2br(htmlspecialchars($plainValue, ENT_QUOTES, 'UTF-8'));
        }
        if ($layout === 'compact') {
            $html .= '<div class="maps-custom-field maps-custom-field-compact">'
                . '<strong>' . $safeLabel . '</strong> ' . $renderedValue . '</div>';
        } else {
            $html .= '<div class="maps-custom-field">'
                . '<strong class="maps-custom-field-label">' . $safeLabel . '</strong>'
                . '<div class="maps-custom-field-value">' . $renderedValue . '</div></div>';
        }
    }

    if ($html === '') {
        return '';
    }

    return '<div class="maps-custom-fields">' . $html . '</div>';
}

/**
 * Normalize a configurable CSS size (for example 250px, 40em or 100%).
 *
 * @param mixed  $value
 * @param string $default
 * @return string
 */
function MAPS_cssSize($value, $default)
{
    $value = trim((string) $value);
    // Maps <= 1.4 sometimes stored dimensions as bare numbers. CSS requires
    // a unit, so preserve those historical values by treating them as pixels.
    if (preg_match('/^[0-9]+(?:\.[0-9]+)?$/', $value)) {
        return $value . 'px';
    }
    if (preg_match('/^[0-9]+(?:\.[0-9]+)?(?:px|%|em|rem|vh|vw)$/i', $value)) {
        return $value;
    }

    return $default;
}

/**
 * Return a validated Google map type constant name.
 *
 * @param mixed  $value
 * @param string $default
 * @return string
 */
function MAPS_mapType($value, $default = 'ROADMAP')
{
    $value = preg_replace('/[^A-Z_]/', '', strtoupper((string) $value));
    if (!in_array($value, array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN'), true)) {
        $value = $default;
    }

    return $value;
}

/**
 * Return the Geeklog table that stores extended user profile attributes.
 * Geeklog 2.2.2 merged the legacy userinfo table into user_attributes.
 *
 * @return string
 */
function MAPS_userAttributesTable()
{
    global $_TABLES;

    if (isset($_TABLES['user_attributes']) && $_TABLES['user_attributes'] !== '') {
        return $_TABLES['user_attributes'];
    }
    if (isset($_TABLES['userinfo']) && $_TABLES['userinfo'] !== '') {
        return $_TABLES['userinfo'];
    }

    return '';
}

/**
 * Normalize a value for an HTML <input type="color">.
 * Historical rows may contain an empty or malformed color, which modern
 * browsers reject and report in the console.
 *
 * @param string $value
 * @param string $fallback
 * @return string
 */
function MAPS_htmlColor($value, $fallback = '#666666')
{
    $value = trim((string) $value);
    $fallback = trim((string) $fallback);

    if (!preg_match('/^#[0-9a-f]{6}$/i', $fallback)) {
        $fallback = '#666666';
    }
    if (!preg_match('/^#[0-9a-f]{6}$/i', $value)) {
        return $fallback;
    }

    return strtolower($value);
}

/**
 * Build a compact SVG pin as a data URI. This replaces the retired Google
 * Image Charts marker service while preserving configurable marker colors.
 *
 * @param string $fill
 * @param string $label
 * @param string $labelColor
 * @return string
 */
function MAPS_svgMarkerUri($fill, $label = '', $labelColor = '#ffffff')
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

/**
 * Return the pinned modern MarkerClusterer UMD script.
 *
 * @return string
 */
function MAPS_clusterScriptTag()
{
    return '<script src="https://unpkg.com/@googlemaps/markerclusterer@2.6.2/dist/index.min.js"></script>';
}

/**
 * Generate safe clustering initialization code.
 *
 * @param string $mapVariable
 * @return string
 */
function MAPS_clusterInitJs($mapVariable)
{
    return "if (window.markerClusterer && markerClusterer.MarkerClusterer && markers.length) {\n"
        . "    var mapsClusterRenderer = {\n"
        . "        render: function(cluster) {\n"
        . "            var count = Number(cluster.count || 0);\n"
        . "            var size = count >= 50 ? 42 : (count >= 10 ? 38 : 34);\n"
        . "            var fill = count >= 50 ? '#315f8f' : (count >= 10 ? '#3f76aa' : '#568bbb');\n"
        . "            var ring = count >= 50 ? '#24486d' : (count >= 10 ? '#315d86' : '#426f97');\n"
        . "            var svg = '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"' + size + '\" height=\"' + size + '\" viewBox=\"0 0 ' + size + ' ' + size + '\">' +\n"
        . "                '<defs><filter id=\"s\" x=\"-30%\" y=\"-30%\" width=\"160%\" height=\"160%\"><feDropShadow dx=\"0\" dy=\"1\" stdDeviation=\"1.2\" flood-color=\"#000\" flood-opacity=\".18\"/></filter></defs>' +\n"
        . "                '<circle filter=\"url(#s)\" cx=\"' + (size / 2) + '\" cy=\"' + (size / 2) + '\" r=\"' + ((size / 2) - 2) + '\" fill=\"' + fill + '\" fill-opacity=\".86\" stroke=\"' + ring + '\" stroke-width=\"2\"/>' +\n"
        . "                '<circle cx=\"' + (size / 2) + '\" cy=\"' + (size / 2) + '\" r=\"' + ((size / 2) - 6) + '\" fill=\"none\" stroke=\"#fff\" stroke-opacity=\".16\" stroke-width=\"1\"/>' +\n"
        . "                '<text x=\"50%\" y=\"50%\" dy=\".35em\" text-anchor=\"middle\" font-family=\"Arial,sans-serif\" font-size=\"' + (count >= 100 ? 11 : 12) + '\" font-weight=\"700\" fill=\"#fff\">' + count + '</text></svg>';\n"
        . "            return new google.maps.Marker({\n"
        . "                position: cluster.position,\n"
        . "                icon: {url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), scaledSize: new google.maps.Size(size, size)},\n"
        . "                zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,\n"
        . "                title: count + ' markers'\n"
        . "            });\n"
        . "        }\n"
        . "    };\n"
        . "    new markerClusterer.MarkerClusterer({map: " . $mapVariable . ", markers: markers, renderer: mapsClusterRenderer});\n"
        . "}\n";
}

/**
 * Register the Google Maps JavaScript API using the same mechanism that is
 * known to work on Geeklog 2.1.1 (historically used by geocoder.php).
 *
 * $_SCRIPTS->setJavaScriptFile() does not reliably emit remote Google API
 * URLs on every supported Geeklog/theme combination.  Registering the full
 * script element through setJavaScript() works on both the tested 2.1.1
 * renderer and current Geeklog releases.
 *
 * @return bool True when the API was registered (or had already been).
 */
function MAPS_loadGoogleMapsApi()
{
    global $_MAPS_CONF, $_SCRIPTS;

    static $registered = false;

    if ($registered) {
        return true;
    }

    $key = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', ''));
    if ($key === '') {
        return false;
    }

    $apiUrl = MAPS_googleMapsApiUrl();
    $_SCRIPTS->setJavaScript(
        '<script src="' . htmlspecialchars($apiUrl, ENT_QUOTES, 'UTF-8') . '"></script>',
        false,
        false
    );

    $registered = true;
    return true;
}

/**
 * Load Google Maps once and inject Calendar integration where requested.
 */
function plugin_getheadercode_maps()
{
    global $_MAPS_CONF, $_SCRIPTS, $_PLUGINS;

    /*
     * The Maps administration dashboard performs its own Google Maps API
     * health check. Do not preload the API there, otherwise gm_authFailure
     * would be registered too late to reliably diagnose authentication errors.
     */
    $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME']) : '';
    if (preg_match('#/admin/plugins/maps/index\.php$#', $scriptName)) {
        return;
    }

    if (!MAPS_loadGoogleMapsApi()) {
        return;
    }

    $_SCRIPTS->setJavaScriptLibrary('jquery');

    $str = '';
    $url = COM_getCurrentURL();
    $eid = isset($_GET['eid']) ? COM_applyFilter($_GET['eid']) : '';
    $calendarEnabled = in_array('calendar', $_PLUGINS)
        && (int) MAPS_arrayGet($_MAPS_CONF, 'display_events_map', 0) === 1;

    if ($calendarEnabled && stripos($url, 'calendar/index.php') !== false) {
        $str .= MAPS_displayEventsMap(false);
        $str .= "jQuery(function(){jQuery('.cal-header').after('<div id=\"map_contener_mevents\"><div id=\"map_canvas_mevents\"></div></div>');});\n";
    } elseif ($calendarEnabled && stripos($url, 'event.php') !== false && $eid !== '') {
        $str .= MAPS_displayEventsMap(false, $eid);
        $str .= "jQuery(function(){jQuery('.location').append('<div style=\"margin:10px 0\" id=\"map_contener_mevents\"><div id=\"map_canvas_mevents\"></div></div>');});\n";
    }

    if ($str !== '') {
        $_SCRIPTS->setJavaScript($str, true, true);
    }
}

/** Load plugin CSS/legacy color helper. */
function MAPS_getheadercode()
{
    global $_CONF, $_MAPS_CONF, $_SCRIPTS;

    $_SCRIPTS->setCSSFile('maps_css', '/maps/maps.css');
    if (isset($_CONF['theme'])) {
        $_SCRIPTS->setCSSFile('datepicker_ui', '/layout/' . $_CONF['theme'] . '/jquery_ui/jquery.ui.datepicker.css');
    }
    $_SCRIPTS->setJavaScriptFile('maps_iconmaker', '/maps/js/mapiconmaker.js', false);

    /*
     * Public/admin Maps pages call this function explicitly before Geeklog
     * renders the document header. Load the Google Maps API here instead of
     * relying only on plugin_getheadercode_maps(). This is important on older
     * Geeklog versions/themes where the plugin header hook may run before the
     * Maps configuration is fully available or may not re-render a script
     * registered later in the request.
     *
     * The Maps admin dashboard is the one exception: it intentionally loads
     * the API itself after installing gm_authFailure() so authentication
     * failures can be diagnosed reliably.
     */
    $scriptName = isset($_SERVER['SCRIPT_NAME'])
        ? str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME'])
        : '';
    $isAdminDashboard = (bool) preg_match('#/admin/plugins/maps/index\.php$#', $scriptName);
    if (!$isAdminDashboard) {
        MAPS_loadGoogleMapsApi();
    }
}

function plugin_getdocumentationurl_maps()
{
    return 'https://github.com/Geeklog-Plugins/maps';
}

function plugin_getmenuitems_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1;

    $menuitems = array();
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'hide_maps_menu', 0) === 0) {
        $menuitems[$LANG_MAPS_1['plugin_name']] = $_MAPS_CONF['site_url'] . '/index.php';
    }
    return $menuitems;
}

function plugin_searchtypes_maps()
{
    global $LANG_MAPS_1;
    return array('maps' => $LANG_MAPS_1['plugin_name']);
}

function plugin_dopluginsearch_maps($query, $datestart, $dateend, $topic, $type, $author, $keyType, $page, $perpage)
{
    global $_TABLES, $LANG_MAPS_1;

    // Follow the same SearchCriteria contract used by Geeklog core plugins:
    // id, title, description, date, uid, hits and url.
    $query = trim(DB_escapeString($query));

    $markerAlias = 'maps_marker';
    $mapAlias = 'maps_parent';
    $markerOwnerAlias = 'maps_marker_owner';
    $mapOwnerAlias = 'maps_map_owner';
    // Historical Maps rows can keep an owner_id after the corresponding Geeklog
    // user has been deleted. Only expose an owner when the user still exists;
    // otherwise fall back to the parent map owner, then Anonymous (uid 1).
    $ownerSql = "CASE "
              . "WHEN {$markerAlias}.owner_id > 1 AND {$markerOwnerAlias}.uid IS NOT NULL THEN {$markerAlias}.owner_id "
              . "WHEN {$mapAlias}.owner_id > 1 AND {$mapOwnerAlias}.uid IS NOT NULL THEN {$mapAlias}.owner_id "
              . "ELSE 1 END";

    // COM_getPermSQL() emits unqualified permission fields. Qualify them because
    // both joined Maps tables expose the same permission column names.
    $permSql = COM_getPermSQL('AND');
    $permSql = preg_replace(
        '/\\b(owner_id|group_id|perm_owner|perm_group|perm_members|perm_anon)\\b/',
        $markerAlias . '.$1',
        $permSql
    );

    $sql = "SELECT {$markerAlias}.mkid AS id, {$markerAlias}.name AS title, "
         . "CASE WHEN TRIM({$markerAlias}.description) <> '' THEN {$markerAlias}.description "
         . "WHEN TRIM({$markerAlias}.address) <> '' THEN {$markerAlias}.address "
         . "ELSE {$markerAlias}.name END AS description, "
         . "UNIX_TIMESTAMP({$markerAlias}.created) AS date, "
         . $ownerSql . " AS uid, {$markerAlias}.hits AS hits, "
         . "CONCAT('/maps/index.php?mode=marker&amp;mkid=', {$markerAlias}.mkid) AS url "
         . "FROM {$_TABLES['maps_markers']} AS {$markerAlias} "
         . "LEFT JOIN {$_TABLES['maps_maps']} AS {$mapAlias} ON {$mapAlias}.mid = {$markerAlias}.mid "
         . "LEFT JOIN {$_TABLES['users']} AS {$markerOwnerAlias} ON {$markerOwnerAlias}.uid = {$markerAlias}.owner_id "
         . "LEFT JOIN {$_TABLES['users']} AS {$mapOwnerAlias} ON {$mapOwnerAlias}.uid = {$mapAlias}.owner_id "
         . "WHERE {$markerAlias}.active = 1 AND {$markerAlias}.hidden = 0 "
         . $permSql . ' ';

    if (!empty($author)) {
        $sql .= 'AND (' . $ownerSql . ') = ' . (int) $author . ' ';
    }

    $search = new SearchCriteria('maps', $LANG_MAPS_1['plugin_name']);
    $columns = array(
        'title' => $markerAlias . '.name',
        $markerAlias . '.description',
        $markerAlias . '.address'
    );

    $sql .= $search->getDateRangeSQL('AND', $markerAlias . '.created', $datestart, $dateend);
    list($sql, $ftsql) = $search->buildSearchSQL($keyType, $query, $columns, $sql);

    $search->setSQL($sql);
    $search->setFtSQL($ftsql);
    $search->setRank(3);
    $search->setAppendQuery(false);
    // Maps does not define a rewritten /markers.php/show/... route.
    $search->setURLRewrite(false);

    return $search;
}

function plugin_autotags_maps($op, $content = '', $autotag = '')
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_MAPS_1;

    if ($op === 'tagname') {
        return array('geo', 'maps', 'marker');
    }
    if ($op === 'description') {
        return array(
            'maps' => $LANG_MAPS_1['autotag_desc_maps'],
            'geo' => $LANG_MAPS_1['autotag_desc_geo'],
            'marker' => $LANG_MAPS_1['autotag_desc_marker']
        );
    }
    if ($op !== 'parse' || !is_array($autotag)) {
        return $content;
    }

    MAPS_getheadercode();
    $html = '';
    $tag = MAPS_arrayGet($autotag, 'tag', '');
    $parm1 = MAPS_arrayGet($autotag, 'parm1', '');
    $parm2 = MAPS_arrayGet($autotag, 'parm2', '');

    if ($tag === 'geo' && COM_applyFilter($parm1) === 'map') {
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
        if ($address !== '') {
            $gid = '';
            $lat = 0;
            $lng = 0;
            MAPS_getDBCoords($gid, $address, $lat, $lng);
            if ($lat != 0 || $lng != 0) {
                $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
                $t->set_file('page', 'geo.thtml');
                $rid = mt_rand(1000, 999999);
                $t->set_var('gid', $rid);
                $t->set_var('address', htmlspecialchars($address, ENT_QUOTES, 'UTF-8'));
                $t->set_var('address_js', MAPS_jsString($address));
                $geoZoom = is_numeric($zoom) ? (int) $zoom : (int) MAPS_arrayGet($_MAPS_CONF, 'map_zoom_geotag', 10);
                $t->set_var('lat_js', MAPS_jsNumber($lat, 0));
                $t->set_var('lng_js', MAPS_jsNumber($lng, 0));
                $t->set_var('zoom_js', MAPS_jsNumber($geoZoom, 10));
                $t->set_var('lat', (float) $lat);
                $t->set_var('lng', (float) $lng);
                $geoMapType = preg_replace('/[^A-Z_]/', '', strtoupper((string) MAPS_arrayGet($_MAPS_CONF, 'map_type_geotag', 'ROADMAP')));
                if (!in_array($geoMapType, array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN'), true)) {
                    $geoMapType = 'ROADMAP';
                }
                $t->set_var('map_type', $geoMapType);
                $t->set_var('map_width_geotag', $width !== '' ? $width : MAPS_arrayGet($_MAPS_CONF, 'map_width_geotag', '100%'));
                $t->set_var('map_height_geotag', $height !== '' ? $height : MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', '400px'));
                $t->set_var('zoom', $geoZoom);
                $t->set_var('show_directions_geo', (int) MAPS_arrayGet($_MAPS_CONF, 'show_directions_geo', 0) ? 'block' : 'none');
                $t->set_var('error_address_empty', $LANG_MAPS_1['error_address_empty']);
                $t->set_var('error_address_empty_js', MAPS_jsString($LANG_MAPS_1['error_address_empty']));
                $t->set_var('error_invalid_address', $LANG_MAPS_1['error_invalid_address']);
                $t->set_var('error_google_error', $LANG_MAPS_1['error_google_error']);
                $t->set_var('error_google_error_js', MAPS_jsString($LANG_MAPS_1['error_google_error']));
                $t->set_var('error_no_map_info', $LANG_MAPS_1['error_no_map_info']);
                $t->set_var('need_directions', $LANG_MAPS_1['need_directions']);
                $t->set_var('get_directions', $LANG_MAPS_1['get_directions']);
                $t->set_var('value', htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8'));
                $t->parse('output', 'page');
                $html = $t->finish($t->get_var('output'));
            }
        }
    } elseif ($tag === 'maps') {
        $mid = (int) COM_applyFilter($parm1, true);
        $zoom = '';
        $addressParts = array();
        foreach (preg_split('/\s+/', trim($parm2)) as $part) {
            if (strpos($part, 'zoom:') === 0) {
                $zoom = substr($part, 5);
            } elseif ($part !== '') {
                $addressParts[] = $part;
            }
        }
        $address = trim(implode(' ', $addressParts));
        $html = $mid > 0 ? MAPS_getMap($mid, $zoom, $address) : MAPS_getGlobalMap($zoom, $address);
    } elseif ($tag === 'marker') {
        $mkid = COM_applyFilter($parm1);
        if ($mkid !== '') {
            $safe = MAPS_dbEscape($mkid);
            $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='{$safe}' LIMIT 1");
            $a = DB_fetchArray($res);
            if (is_array($a) && SEC_hasAccess2($a) && MAPS_arrayGet($a, 'mid', '') !== '') {
                $html = MAPS_getMarkerDetail((int) $a['mid'], $mkid, 1);
            }
        }
    }

    return str_replace(MAPS_arrayGet($autotag, 'tagstr', ''), $html, $content);
}

/** Compatibility no-op: Maps AdSense integration was removed by Google. */
function MAPS_getAds($mid)
{
    return '';
}

function plugin_profileblocksdisplay_maps($uid)
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_MAPS_1, $_SCRIPTS;

    if ((int) MAPS_arrayGet($_MAPS_CONF, 'display_geo_profile', 0) === 0
        || trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', '')) === '') {
        return '';
    }

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    MAPS_loadGoogleMapsApi();

    $uid = (int) $uid;
    $userAttributesTable = MAPS_userAttributesTable();
    if ($userAttributesTable === '') {
        return '';
    }
    $address = DB_getItem($userAttributesTable, 'location', 'uid=' . $uid);
    if ($address === '') {
        return '';
    }

    $gid = 'profile' . $uid;
    $lat = 0;
    $lng = 0;
    MAPS_getDBCoords($cacheId, $address, $lat, $lng);
    if ($lat == 0 && $lng == 0) {
        return '';
    }

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'geo_profile.thtml');
    $t->set_var('gid', $gid);
    $t->set_var('map_width_geotag', MAPS_arrayGet($_MAPS_CONF, 'map_width_profile', '100%'));
    $t->set_var('map_height_geotag', MAPS_arrayGet($_MAPS_CONF, 'map_height_profile', '400px'));
    $t->set_var('show_directions_geo', (int) MAPS_arrayGet($_MAPS_CONF, 'show_directions_profile', 0) ? 'block' : 'none');
    $t->set_var('need_directions', $LANG_MAPS_1['need_directions']);
    $t->set_var('get_directions', $LANG_MAPS_1['get_directions']);
    $t->set_var('value', htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8'));
    $t->parse('output', 'page');

    $safeAddress = MAPS_jsString($address);
    $mapType = preg_replace('/[^A-Z_]/', '', strtoupper(MAPS_arrayGet($_MAPS_CONF, 'map_type_profile', 'ROADMAP')));
    $zoom = (int) MAPS_arrayGet($_MAPS_CONF, 'zoom_profile', 10);
    $js = "jQuery(function(){\n"
        . "var el=document.getElementById('map_canvas{$gid}'); if(!el||!window.google){return;}\n"
        . "var m=new google.maps.Map(el,{center:{lat:Number(" . MAPS_jsNumber($lat, 0) . "),lng:Number(" . MAPS_jsNumber($lng, 0) . ")},zoom:{$zoom},mapTypeId:google.maps.MapTypeId.{$mapType}});\n"
        . "new google.maps.Marker({position:{lat:Number(" . MAPS_jsNumber($lat, 0) . "),lng:Number(" . MAPS_jsNumber($lng, 0) . ")},map:m,title:{$safeAddress}});\n"
        . "var renderer=new google.maps.DirectionsRenderer({map:m,panel:document.getElementById('directions{$gid}')});\n"
        . "window.get_directions{$gid}=function(){var i=document.getElementById('from_address{$gid}');if(!i||!i.value){return;}new google.maps.DirectionsService().route({origin:i.value,destination:{$safeAddress},travelMode:google.maps.TravelMode.DRIVING},function(r,s){if(s==='OK'||s===google.maps.DirectionsStatus.OK){renderer.setDirections(r);}else{alert(" . MAPS_jsString($LANG_MAPS_1['error_google_error']) . "+' ('+s+')');}});};\n"
        . "});";
    $_SCRIPTS->setJavaScript($js, true, true);

    return COM_startBlock($LANG_MAPS_1['profile_title'])
        . $t->finish($t->get_var('output'))
        . COM_endBlock();
}

function plugin_cclabel_maps()
{
    global $_CONF, $LANG_MAPS_1;
    if (SEC_hasRights('maps.admin')) {
        return array($LANG_MAPS_1['plugin_name'], $_CONF['site_admin_url'] . '/plugins/maps/index.php', plugin_geticon_maps());
    }
    return array();
}

function plugin_getadminoption_maps()
{
    global $_CONF, $LANG_MAPS_1, $_TABLES;
    if (SEC_hasRights('maps.admin')) {
        return array($LANG_MAPS_1['plugin_name'], $_CONF['site_admin_url'] . '/plugins/maps/index.php', DB_count($_TABLES['maps_maps']));
    }
    return array();
}

function plugin_geticon_maps()
{
    global $_CONF;
    return $_CONF['site_admin_url'] . '/plugins/maps/images/maps.png';
}

function plugin_chkVersion_maps()
{
    global $_CONF;
    require_once $_CONF['path'] . 'plugins/maps/autoinstall.php';
    $p = plugin_autoinstall_maps('maps');
    return $p['info']['pi_version'];
}

/**
 * Upgrade Maps configuration to 1.5.1 without destructive data migration.
 *
 * The 1.5.0 -> 1.5.1 step normalizes configuration rows left by Maps 1.4,
 * removes obsolete Google Ads settings and creates/repositions the current
 * Google Maps Platform and marker-field settings.
 */
/**
 * Repair and normalize the Maps 1.5.2 configuration layout.
 *
 * Maps 1.4 stored Google Ads options and used a different set of fieldset
 * numbers. Upgrading used to add the new Google options without removing or
 * relocating the legacy rows, which produced a mixed 1.4/1.5 configuration
 * screen. This repair is intentionally idempotent and also runs when the
 * migration is also safe to run more than once.
 */
function MAPS_repairConfig151()
{
    global $_CONF, $_TABLES, $_MAPS_CONF, $_MAPS_DEFAULT;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/maps/install_defaults.php';

    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $config = config::get_instance();

    // Google Maps AdSense integration was removed from Maps 1.5.
    $obsolete = array(
        'monetize', 'fs_ads', 'AdsOnMap', 'publisher_id', 'channel_id',
        'maxAdsOnMap', 'fs_map_default', 'fs_marker_default'
    );
    foreach ($obsolete as $name) {
        DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql
            . "' AND name='" . MAPS_dbEscape($name) . "'");
    }

    // Complete description of the 1.5 configuration. Existing values are kept;
    // only missing rows are added and their presentation metadata is normalized.
    $rows = array(
        'sg_main' => array(null, 'subgroup', 0, 0, null, 0),
        'fs_main' => array(null, 'fieldset', 0, 0, null, 0),
        'maps_login_required' => array($_MAPS_DEFAULT['maps_login_required'], 'select', 0, 0, 3, 20),
        'hide_maps_menu' => array($_MAPS_DEFAULT['hide_maps_menu'], 'select', 0, 0, 3, 30),
        'marker_submission' => array($_MAPS_DEFAULT['marker_submission'], 'select', 0, 0, 3, 40),
        'submit_login_required' => array($_MAPS_DEFAULT['submit_login_required'], 'select', 0, 0, 3, 50),
        'marker_edition' => array($_MAPS_DEFAULT['marker_edition'], 'select', 0, 0, 3, 60),

        'fs_google' => array(null, 'fieldset', 0, 1, null, 0),
        'autofill_coord' => array($_MAPS_DEFAULT['autofill_coord'], 'select', 0, 1, 3, 100),
        'google_api_key' => array($_MAPS_DEFAULT['google_api_key'], 'text', 0, 1, 0, 110),
        'google_server_api_key' => array($_MAPS_DEFAULT['google_server_api_key'], 'text', 0, 1, 0, 120),
        'google_map_id' => array($_MAPS_DEFAULT['google_map_id'], 'text', 0, 1, 0, 130),
        'google_language' => array($_MAPS_DEFAULT['google_language'], 'text', 0, 1, 0, 140),
        'google_region' => array($_MAPS_DEFAULT['google_region'], 'text', 0, 1, 0, 150),
        'url_geocode' => array($_MAPS_DEFAULT['url_geocode'], 'text', 0, 1, 0, 160),

        'fs_permissions' => array(null, 'fieldset', 0, 2, null, 0),
        'default_permissions' => array($_MAPS_DEFAULT['default_permissions'], '@select', 0, 2, 12, 200),

        'sg_display' => array(null, 'subgroup', 1, 0, null, 0),
        'fs_display' => array(null, 'fieldset', 1, 0, null, 0),
        'map_main_header' => array($_MAPS_DEFAULT['map_main_header'], 'text', 1, 0, 0, 10),
        'map_main_footer' => array($_MAPS_DEFAULT['map_main_footer'], 'text', 1, 0, 0, 20),
        'use_cluster' => array($_MAPS_DEFAULT['use_cluster'], 'select', 1, 0, 3, 30),
        'display_events_map' => array($_MAPS_DEFAULT['display_events_map'], 'select', 1, 0, 3, 40),

        'fs_global_map' => array(null, 'fieldset', 1, 1, null, 0),
        'users_map' => array($_MAPS_DEFAULT['users_map'], 'select', 1, 1, 3, 10),
        'global_map' => array($_MAPS_DEFAULT['global_map'], 'select', 1, 1, 3, 20),
        'global_type' => array($_MAPS_DEFAULT['global_type'], 'select', 1, 1, 20, 30),
        'global_width' => array($_MAPS_DEFAULT['global_width'], 'text', 1, 1, 0, 40),
        'global_height' => array($_MAPS_DEFAULT['global_height'], 'text', 1, 1, 0, 50),
        'global_zoom' => array($_MAPS_DEFAULT['global_zoom'], 'text', 1, 1, 0, 60),

        'fs_display_profile' => array(null, 'fieldset', 1, 2, null, 0),
        'display_geo_profile' => array($_MAPS_DEFAULT['display_geo_profile'], 'select', 1, 2, 3, 10),
        'map_type_profile' => array($_MAPS_DEFAULT['map_type_profile'], 'select', 1, 2, 20, 20),
        'map_width_profile' => array($_MAPS_DEFAULT['map_width_profile'], 'text', 1, 2, 0, 30),
        'map_height_profile' => array($_MAPS_DEFAULT['map_height_profile'], 'text', 1, 2, 0, 40),
        'zoom_profile' => array($_MAPS_DEFAULT['zoom_profile'], 'text', 1, 2, 0, 50),
        'show_directions_profile' => array($_MAPS_DEFAULT['show_directions_profile'], 'select', 1, 2, 3, 60),

        'fs_display_geo' => array(null, 'fieldset', 1, 3, null, 0),
        'map_type_geotag' => array($_MAPS_DEFAULT['map_type_geotag'], 'select', 1, 3, 20, 10),
        'map_width_geotag' => array($_MAPS_DEFAULT['map_width_geotag'], 'text', 1, 3, 0, 20),
        'map_height_geotag' => array($_MAPS_DEFAULT['map_height_geotag'], 'text', 1, 3, 0, 30),
        'map_zoom_geotag' => array($_MAPS_DEFAULT['map_zoom_geotag'], 'text', 1, 3, 0, 40),
        'show_directions_geo' => array($_MAPS_DEFAULT['show_directions_geo'], 'select', 1, 3, 3, 50),

        'fs_map_defaults' => array(null, 'fieldset', 1, 4, null, 0),
        'map_type' => array($_MAPS_DEFAULT['map_type'], 'select', 1, 4, 20, 10),
        'map_width' => array($_MAPS_DEFAULT['map_width'], 'text', 1, 4, 0, 20),
        'map_height' => array($_MAPS_DEFAULT['map_height'], 'text', 1, 4, 0, 30),
        'map_zoom' => array($_MAPS_DEFAULT['map_zoom'], 'text', 1, 4, 0, 40),
        'map_active' => array($_MAPS_DEFAULT['map_active'], 'select', 1, 4, 3, 50),
        'map_hidden' => array($_MAPS_DEFAULT['map_hidden'], 'select', 1, 4, 3, 60),
        'free_markers' => array($_MAPS_DEFAULT['free_markers'], 'select', 1, 4, 3, 70),
        'paid_markers' => array($_MAPS_DEFAULT['paid_markers'], 'select', 1, 4, 3, 80),

        'fs_marker_defaults' => array(null, 'fieldset', 1, 5, null, 0),
        'marker_active' => array($_MAPS_DEFAULT['marker_active'], 'select', 1, 5, 3, 10),
        'marker_hidden' => array($_MAPS_DEFAULT['marker_hidden'], 'select', 1, 5, 3, 20),
        'marker_payed' => array($_MAPS_DEFAULT['marker_payed'], 'select', 1, 5, 3, 30),
        'marker_validity' => array($_MAPS_DEFAULT['marker_validity'], 'select', 1, 5, 3, 40),
        'star_primary_color' => array($_MAPS_DEFAULT['star_primary_color'], 'text', 1, 5, 0, 50),
        'star_stroke_color' => array($_MAPS_DEFAULT['star_stroke_color'], 'text', 1, 5, 0, 60),
        'label_color' => array($_MAPS_DEFAULT['label_color'], 'select', 1, 5, 0, 70),
        'detail_zoom' => array($_MAPS_DEFAULT['detail_zoom'], 'text', 1, 5, 0, 80),

        'fs_marker_fields' => array(null, 'fieldset', 1, 6, null, 0)
    );

    $fieldOrder = 10;
    foreach (array('street', 'code', 'city', 'state', 'country', 'tel', 'fax', 'web') as $name) {
        $rows[$name] = array($_MAPS_DEFAULT[$name], 'select', 1, 6, 3, $fieldOrder);
        $fieldOrder += 10;
    }
    for ($i = 1; $i <= 10; $i++) {
        $name = 'item_' . $i;
        $rows[$name] = array($_MAPS_DEFAULT[$name], 'text', 1, 6, 0, $fieldOrder);
        $fieldOrder += 10;
    }

    foreach ($rows as $name => $def) {
        $nameSql = MAPS_dbEscape($name);
        $exists = (int) DB_getItem(
            $_TABLES['conf_values'],
            'COUNT(*)',
            "group_name='" . $groupSql . "' AND name='" . $nameSql . "'"
        );
        if ($exists === 0) {
            $config->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group);
        }

        $selection = ($def[4] === null) ? -1 : (int) $def[4];
        DB_query("UPDATE {$_TABLES['conf_values']} SET type='" . MAPS_dbEscape($def[1])
            . "', subgroup=" . (int) $def[2]
            . ", fieldset=" . (int) $def[3]
            . ", selectionArray=" . $selection
            . ", sort_order=" . (int) $def[5]
            . " WHERE group_name='" . $groupSql . "' AND name='" . $nameSql . "'");
    }

    // Convert the 1.4 geocoding URL template to the current endpoint while
    // preserving a custom endpoint if an administrator intentionally changed it.
    $legacyGeocode = isset($_MAPS_CONF['url_geocode']) ? (string) $_MAPS_CONF['url_geocode'] : '';
    if ($legacyGeocode === '' || strpos($legacyGeocode, '%address%') !== false || strpos($legacyGeocode, 'sensor=') !== false) {
        $config->set('url_geocode', $_MAPS_DEFAULT['url_geocode'], $group);
    }

    return true;
}


/**
 * Validate a legacy public folder configured by Maps <= 1.5.1.
 *
 * Nested relative folders are accepted, but traversal and absolute paths are
 * rejected. The returned value never has leading/trailing slashes.
 *
 * @param string $folder
 * @return string
 */
function MAPS_normalizeLegacyPublicFolder152($folder)
{
    $folder = trim(str_replace('\\', '/', (string) $folder));
    $folder = trim($folder, '/');

    if ($folder === '' || $folder === 'maps' || strpos($folder, "\0") !== false) {
        return '';
    }

    $parts = explode('/', $folder);
    foreach ($parts as $part) {
        if ($part === '' || $part === '.' || $part === '..') {
            return '';
        }
    }

    // Drive letters, URL schemes and query fragments are not filesystem slugs.
    if (strpos($folder, ':') !== false || strpos($folder, '?') !== false || strpos($folder, '#') !== false) {
        return '';
    }

    return $folder;
}

/**
 * Verify the canonical public folder installed by Geeklog.
 *
 * Geeklog's native plugin uploader extracts the package and moves the package
 * public_html directory to $_CONF['path_html'] . 'maps' before calling the
 * plugin upgrade callback. Maps must therefore never reconstruct a source
 * path or copy files out of the installed plugin directory here.
 *
 * @return bool
 */
function MAPS_deployCanonicalPublicFolder152()
{
    global $_CONF;

    if (!isset($_CONF['path_html']) || trim((string) $_CONF['path_html']) === '') {
        COM_errorLog('Maps 1.5.2 upgrade: Geeklog path_html is not configured.');
        return false;
    }

    $target = rtrim((string) $_CONF['path_html'], '/\\')
        . DIRECTORY_SEPARATOR . 'maps';

    COM_errorLog('Maps 1.5.2 upgrade: canonical public folder from Geeklog path_html: ' . $target);

    if (!is_dir($target)) {
        COM_errorLog('Maps 1.5.2 upgrade: canonical public folder installed by Geeklog is missing: ' . $target);
        return false;
    }

    // index.php and markers.php are core Maps public entry points. Their
    // presence confirms that Geeklog deployed the public part of this package.
    foreach (array('index.php', 'markers.php') as $entry) {
        $path = $target . DIRECTORY_SEPARATOR . $entry;
        if (!is_file($path)) {
            COM_errorLog('Maps 1.5.2 upgrade: expected canonical public file is missing: ' . $path);
            return false;
        }
    }

    return true;
}

/**
 * Return true when an existing legacy PHP entry point belongs to Maps (or is
 * one of the 1.5.2 redirect stubs). This prevents overwriting unrelated files.
 *
 * @param string $path
 * @return bool
 */
function MAPS_isLegacyMapsEntryPoint152($path)
{
    if (!file_exists($path)) {
        return true;
    }
    if (!is_file($path) || !is_readable($path)) {
        return false;
    }

    $sample = @file_get_contents($path, false, null, 0, 16384);
    if ($sample === false) {
        return false;
    }
    $sampleLower = strtolower($sample);

    return strpos($sample, 'MAPS_') !== false
        || strpos($sampleLower, 'plugins/maps') !== false
        || strpos($sampleLower, 'maps plugin') !== false
        || strpos($sampleLower, 'maps 1.5.2 permanent seo redirect') !== false;
}

/**
 * Install permanent redirect entry points in a legacy Maps public folder.
 * Query strings are preserved exactly (minus CR/LF header characters).
 *
 * @param string $legacyFolder Relative folder under path_html
 * @return bool
 */
function MAPS_installLegacyRedirects152($legacyFolder)
{
    global $_CONF;

    $legacyFolder = MAPS_normalizeLegacyPublicFolder152($legacyFolder);
    if ($legacyFolder === '') {
        return true;
    }

    $legacyPath = rtrim($_CONF['path_html'], '/\\') . DIRECTORY_SEPARATOR
        . str_replace('/', DIRECTORY_SEPARATOR, $legacyFolder);

    if (!is_dir($legacyPath) && !@mkdir($legacyPath, 0755, true)) {
        COM_errorLog('Maps 1.5.2 upgrade: unable to create legacy redirect folder: ' . $legacyPath);
        return false;
    }

    // On case-insensitive filesystems, a legacy slug such as "Maps" may resolve
    // to the canonical "maps" directory. Never replace canonical files with
    // redirect stubs in that situation.
    $canonicalPath = rtrim($_CONF['path_html'], '/\\') . DIRECTORY_SEPARATOR . 'maps';
    $legacyReal = realpath($legacyPath);
    $canonicalReal = realpath($canonicalPath);
    if ($legacyReal !== false && $canonicalReal !== false && $legacyReal === $canonicalReal) {
        return true;
    }

    $depth = count(explode('/', $legacyFolder));
    $prefix = str_repeat('../', $depth) . 'maps/';
    $entries = array('index.php', 'markers.php', 'map_detail.php', 'users_map.php');

    foreach ($entries as $entry) {
        $path = $legacyPath . DIRECTORY_SEPARATOR . $entry;
        if (!MAPS_isLegacyMapsEntryPoint152($path)) {
            COM_errorLog('Maps 1.5.2 upgrade: refusing to overwrite non-Maps legacy file: ' . $path);
            return false;
        }

        $target = $prefix . $entry;
        $stub = "<?php\n"
            . "// Maps 1.5.2 permanent SEO redirect. Keep this file for old links.\n"
            . "\$target = " . var_export($target, true) . ";\n"
            . "\$query = isset(\$_SERVER['QUERY_STRING']) ? str_replace(array(\"\\r\", \"\\n\"), '', (string) \$_SERVER['QUERY_STRING']) : '';\n"
            . "if (\$query !== '') {\$target .= '?' . \$query;}\n"
            . "header('Location: ' . \$target, true, 301);\n"
            . "exit;\n";

        if (@file_put_contents($path, $stub, LOCK_EX) === false) {
            COM_errorLog('Maps 1.5.2 upgrade: unable to write legacy redirect: ' . $path);
            return false;
        }
        @chmod($path, 0644);
    }

    $readme = $legacyPath . DIRECTORY_SEPARATOR . 'MAPS-LEGACY-REDIRECT.txt';
    $note = "Maps 1.5.2 legacy public folder\n"
        . "This directory is intentionally retained for permanent HTTP 301 redirects.\n"
        . "Do not remove it while old links or search-engine indexes may still reference /"
        . $legacyFolder . "/.\nCanonical Maps URLs now use /maps/.\n";
    @file_put_contents($readme, $note, LOCK_EX);

    return true;
}

/**
 * Maps 1.5.2 migration: canonicalize the public folder to /maps while keeping
 * permanent 301 redirects from the previously configured slug.
 *
 * @return bool
 */
function MAPS_migratePublicFolder152()
{
    global $_CONF, $_TABLES, $_MAPS_CONF;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $config = config::get_instance();

    $legacy = isset($_MAPS_CONF['_legacy_maps_folder'])
        ? (string) $_MAPS_CONF['_legacy_maps_folder'] : 'maps';
    $legacy = MAPS_normalizeLegacyPublicFolder152($legacy);

    if (!MAPS_deployCanonicalPublicFolder152()) {
        return false;
    }

    if ($legacy !== '' && !MAPS_installLegacyRedirects152($legacy)) {
        return false;
    }

    // Keep the previous slug as internal state, not as a visible Maps
    // configuration item. Geeklog's vars table is intended for plugin/runtime
    // state that does not belong on admin/configuration.php.
    $legacyVar = 'maps_legacy_folder';
    $legacyVarSql = MAPS_dbEscape($legacyVar);
    $legacySql = MAPS_dbEscape($legacy);
    if (DB_count($_TABLES['vars'], 'name', $legacyVar) > 0) {
        DB_change($_TABLES['vars'], 'value', $legacySql, 'name', $legacyVar);
    } else {
        DB_query("INSERT INTO {$_TABLES['vars']} (name, value) VALUES ('"
            . $legacyVarSql . "', '" . $legacySql . "')");
    }

    // Remove both the historical editable folder setting and the alpha3
    // internal setting if they exist in the online configuration.
    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql
        . "' AND name IN ('maps_folder','legacy_maps_folder')");

    return true;
}

/**
 * Maps 1.5.3 configuration cleanup.
 *
 * Earlier 1.5.2 alpha builds temporarily stored the legacy public folder in
 * the online configuration. Internal SEO migration state belongs in Geeklog's
 * vars table, not on admin/configuration.php.
 *
 * @return bool
 */
function MAPS_cleanupConfig153()
{
    global $_CONF, $_TABLES;

    if (!isset($_TABLES['conf_values']) || !isset($_TABLES['vars'])) {
        COM_errorLog('Maps 1.5.3 upgrade: Geeklog configuration tables are unavailable.');
        return false;
    }

    $groupSql = MAPS_dbEscape('maps');
    $nameSql = MAPS_dbEscape('legacy_maps_folder');
    $legacy = '';

    $result = DB_query("SELECT value FROM {$_TABLES['conf_values']} WHERE group_name='"
        . $groupSql . "' AND name='" . $nameSql . "' LIMIT 1");
    if ($result && DB_numRows($result) > 0) {
        $row = DB_fetchArray($result);
        if (isset($row['value'])) {
            $raw = (string) $row['value'];
            // Configuration strings are normally serialized by Geeklog.
            $decoded = @unserialize($raw);
            if ($decoded !== false || $raw === 'b:0;') {
                $legacy = (string) $decoded;
            } else {
                $legacy = trim($raw, "\"'");
            }
        }
    }

    $legacy = MAPS_normalizeLegacyPublicFolder152($legacy);
    if ($legacy !== '') {
        $varName = 'maps_legacy_folder';
        $varNameSql = MAPS_dbEscape($varName);
        $legacySql = MAPS_dbEscape($legacy);
        if (DB_count($_TABLES['vars'], 'name', $varName) > 0) {
            DB_change($_TABLES['vars'], 'value', $legacySql, 'name', $varName);
        } else {
            DB_query("INSERT INTO {$_TABLES['vars']} (name, value) VALUES ('"
                . $varNameSql . "', '" . $legacySql . "')");
        }
    }

    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='"
        . $groupSql . "' AND name='" . $nameSql . "'");

    // Also remove maps_folder if an older migration left it behind. In 1.5.2+
    // /maps/ is canonical and this setting is no longer user-editable.
    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='"
        . $groupSql . "' AND name='maps_folder'");

    // Force Geeklog to rebuild its in-memory configuration after the cleanup.
    if (isset($_CONF['path_system'])) {
        require_once rtrim($_CONF['path_system'], '/\\') . DIRECTORY_SEPARATOR . 'classes'
            . DIRECTORY_SEPARATOR . 'config.class.php';
        if (class_exists('config')) {
            $config = config::get_instance();
            if (method_exists($config, 'initConfig')) {
                $config->initConfig();
            }
        }
    }

    COM_errorLog('Maps 1.5.3 upgrade: obsolete legacy_maps_folder configuration removed.', 1);
    return true;
}


/**
 * Maps 1.5.4 configuration reorganization.
 *
 * Existing values are preserved. Only presentation metadata is moved to the
 * new tabs/fieldsets, and new settings receive conservative defaults.
 *
 * @return bool
 */
function MAPS_repairConfig154()
{
    global $_CONF, $_TABLES;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/maps/install_defaults.php';

    if (!function_exists('MAPS_configDefinition154')) {
        COM_errorLog('Maps 1.5.4 upgrade: configuration definition is unavailable.');
        return false;
    }

    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $config = config::get_instance();
    $rows = MAPS_configDefinition154();

    // The former catch-all "Display" subgroup is replaced by explicit tabs.
    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql
        . "' AND name='sg_display'");

    foreach ($rows as $name => $def) {
        $nameSql = MAPS_dbEscape($name);
        $exists = (int) DB_getItem(
            $_TABLES['conf_values'],
            'COUNT(*)',
            "group_name='" . $groupSql . "' AND name='" . $nameSql . "'"
        );
        if ($exists === 0) {
            $config->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group);
        }

        $selection = ($def[4] === null) ? -1 : (int) $def[4];
        DB_query("UPDATE {$_TABLES['conf_values']} SET type='" . MAPS_dbEscape($def[1])
            . "', subgroup=" . (int) $def[2]
            . ", fieldset=" . (int) $def[3]
            . ", selectionArray=" . $selection
            . ", sort_order=" . (int) $def[5]
            . " WHERE group_name='" . $groupSql . "' AND name='" . $nameSql . "'");
    }

    if (method_exists($config, 'initConfig')) {
        $config->initConfig();
    }

    COM_errorLog('Maps 1.5.4 upgrade: configuration reorganized into dedicated tabs.', 1);
    return true;
}


/**
 * Maps 1.5.5 configuration tab repair.
 *
 * Maps 1.5.4 incorrectly used multiple subgroups where Geeklog expects one
 * plugin subgroup plus explicit entries of type "tab". Preserve every
 * existing value and only repair the presentation metadata.
 *
 * @return bool
 */
function MAPS_repairConfig155()
{
    global $_CONF, $_TABLES;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/maps/install_defaults.php';

    if (!function_exists('MAPS_configDefinition155')) {
        COM_errorLog('Maps 1.5.5 upgrade: configuration definition is unavailable.');
        return false;
    }

    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $config = config::get_instance();
    $rows = MAPS_configDefinition155();

    // Remove the 1.5.4 pseudo-subgroups. sg_main remains the single plugin subgroup.
    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql
        . "' AND name IN ('sg_google','sg_maps','sg_markers','sg_fields','sg_display')");

    foreach ($rows as $name => $def) {
        $nameSql = MAPS_dbEscape($name);
        $exists = (int) DB_getItem(
            $_TABLES['conf_values'],
            'COUNT(*)',
            "group_name='" . $groupSql . "' AND name='" . $nameSql . "'"
        );

        if ($exists === 0) {
            $config->add(
                $name,
                $def[0],
                $def[1],
                $def[2],
                $def[3],
                $def[4],
                $def[5],
                true,
                $group,
                $def[6]
            );
        }

        $selection = ($def[4] === null) ? -1 : (int) $def[4];
        DB_query("UPDATE {$_TABLES['conf_values']} SET type='" . MAPS_dbEscape($def[1])
            . "', subgroup=" . (int) $def[2]
            . ", fieldset=" . (int) $def[3]
            . ", selectionArray=" . $selection
            . ", sort_order=" . (int) $def[5]
            . ", tab=" . (int) $def[6]
            . " WHERE group_name='" . $groupSql . "' AND name='" . $nameSql . "'");
    }

    if (method_exists($config, 'initConfig')) {
        $config->initConfig();
    }

    COM_errorLog('Maps 1.5.5 upgrade: Geeklog configuration tabs repaired.', 1);
    return true;
}

/**
 * Maps 1.5.6 configuration metadata repair.
 *
 * Geeklog stores -1 in selectionArray when a configuration row has no
 * selection list. Earlier 1.5.5 migration code wrote SQL NULL for fieldsets
 * and tabs. config::_get_extended() sees that as an empty string and tries to
 * resolve $LANG_configselects['maps'][''], producing an undefined-array-key
 * warning. Preserve all values and normalize presentation metadata only.
 *
 * @return bool
 */
function MAPS_repairConfig156()
{
    global $_CONF, $_TABLES;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/maps/install_defaults.php';

    if (!function_exists('MAPS_configDefinition155')) {
        COM_errorLog('Maps 1.5.6 upgrade: configuration definition is unavailable.');
        return false;
    }

    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $config = config::get_instance();
    $rows = MAPS_configDefinition155();

    foreach ($rows as $name => $def) {
        $nameSql = MAPS_dbEscape($name);
        $exists = (int) DB_getItem(
            $_TABLES['conf_values'],
            'COUNT(*)',
            "group_name='" . $groupSql . "' AND name='" . $nameSql . "'"
        );

        if ($exists === 0) {
            $config->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group, $def[6]);
        }

        $selection = ($def[4] === null) ? -1 : (int) $def[4];
        DB_query("UPDATE {$_TABLES['conf_values']} SET type='" . MAPS_dbEscape($def[1])
            . "', subgroup=" . (int) $def[2]
            . ", fieldset=" . (int) $def[3]
            . ", selectionArray=" . $selection
            . ", sort_order=" . (int) $def[5]
            . ", tab=" . (int) $def[6]
            . " WHERE group_name='" . $groupSql . "' AND name='" . $nameSql . "'");
    }

    DB_query("UPDATE {$_TABLES['conf_values']} SET selectionArray=-1"
        . " WHERE group_name='" . $groupSql . "' AND selectionArray IS NULL");

    if (method_exists($config, 'initConfig')) {
        $config->initConfig();
    }

    COM_errorLog('Maps 1.5.6 upgrade: configuration selection metadata repaired.', 1);
    return true;
}

/**
 * Maps 1.5.8 configuration cleanup.
 *
 * Remove the obsolete Pro-only infos_label setting from existing installations
 * and force Geeklog to reload the online configuration so the field disappears
 * immediately after the upgrade. Safe to run more than once.
 *
 * @return bool
 */
function MAPS_cleanupConfig158()
{
    global $_CONF, $_TABLES;

    if (!isset($_TABLES['conf_values']) || $_TABLES['conf_values'] === '') {
        COM_errorLog('Maps 1.5.8 upgrade: Geeklog configuration table is unavailable.');
        return false;
    }

    $groupSql = MAPS_dbEscape('maps');
    $nameSql = MAPS_dbEscape('infos_label');
    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql
        . "' AND name='" . $nameSql . "'");

    require_once rtrim($_CONF['path_system'], '/\\') . DIRECTORY_SEPARATOR . 'classes'
        . DIRECTORY_SEPARATOR . 'config.class.php';
    if (class_exists('config')) {
        $config = config::get_instance();
        if (method_exists($config, 'initConfig')) {
            $config->initConfig();
        }
    }

    COM_errorLog('Maps 1.5.8 upgrade: obsolete infos_label configuration removed.', 1);
    return true;
}

function MAPS_upgrade159()
{
    global $_TABLES;
    if (!isset($_TABLES['maps_service_operations'])) {
        return false;
    }
    DB_query("CREATE TABLE IF NOT EXISTS {$_TABLES['maps_service_operations']} (operation_key char(64) NOT NULL, operation_id varchar(255) NOT NULL default '', action varchar(32) NOT NULL default '', marker_id BIGINT NOT NULL, source varchar(64) NOT NULL default '', source_id varchar(255) NOT NULL default '', created datetime NOT NULL, PRIMARY KEY (operation_key), KEY marker_id (marker_id)) ENGINE=MyISAM");
    return !DB_error();
}

/**
 * Maps 1.5.10 adds dedicated SEO settings for the public Maps landing page.
 * Existing display content remains untouched; empty SEO values keep the
 * automatic fallbacks introduced in 1.5.9.
 *
 * @return bool
 */
function MAPS_upgrade1510()
{
    global $_CONF, $_TABLES, $_MAPS_DEFAULT;

    require_once $_CONF['path_system'] . 'classes/config.class.php';
    require_once $_CONF['path'] . 'plugins/maps/install_defaults.php';

    $config = config::get_instance();
    $group = 'maps';
    $groupSql = MAPS_dbEscape($group);
    $rows = array(
        'fs_seo' => array(null, 'fieldset', 0, 6, null, 0, 2),
        'maps_page_title' => array($_MAPS_DEFAULT['maps_page_title'], 'text', 0, 6, 0, 10, 2),
        'maps_page_h1' => array($_MAPS_DEFAULT['maps_page_h1'], 'text', 0, 6, 0, 20, 2),
        'maps_meta_description' => array($_MAPS_DEFAULT['maps_meta_description'], 'text', 0, 6, 0, 30, 2)
    );

    foreach ($rows as $name => $def) {
        $nameSql = MAPS_dbEscape($name);
        $exists = (int) DB_getItem(
            $_TABLES['conf_values'],
            'COUNT(*)',
            "group_name='" . $groupSql . "' AND name='" . $nameSql . "'"
        );
        if ($exists === 0) {
            $config->add($name, $def[0], $def[1], $def[2], $def[3], $def[4], $def[5], true, $group, $def[6]);
        }

        $selection = ($def[4] === null) ? -1 : (int) $def[4];
        DB_query("UPDATE {$_TABLES['conf_values']} SET type='" . MAPS_dbEscape($def[1])
            . "', subgroup=" . (int) $def[2]
            . ", fieldset=" . (int) $def[3]
            . ", selectionArray=" . $selection
            . ", sort_order=" . (int) $def[5]
            . ", tab=" . (int) $def[6]
            . " WHERE group_name='" . $groupSql . "' AND name='" . $nameSql . "'");
    }

    if (method_exists($config, 'initConfig')) {
        $config->initConfig();
    }

    COM_errorLog('Maps 1.5.10 upgrade: public landing-page SEO settings added.', 1);
    return !DB_error();
}

function plugin_upgrade_maps()
{
    global $_CONF, $_TABLES;

    $installed = (string) DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name='maps'");
    $code = plugin_chkVersion_maps();

    require_once $_CONF['path'] . 'plugins/maps/autoinstall.php';
    if (!plugin_compatible_with_this_version_maps('maps')) {
        return 3002;
    }

    if (version_compare($installed, '1.4.0', '<')) {
        COM_errorLog('Maps 1.5.6 upgrade requires Maps 1.4.0 or newer. Upgrade to 1.4.0 first.');
        return false;
    }

    /* Maps 1.5.1 configuration cleanup (also used for direct 1.4.x upgrades). */
    if (version_compare($installed, '1.5.1', '<')) {
        if (!MAPS_repairConfig151()) {
            return false;
        }
    }

    /*
     * Maps 1.5.2 SEO/public-folder migration.
     * The canonical public folder becomes /maps/. A previous custom folder is
     * retained as permanent 301 redirect entry points.
     */
    if (version_compare($installed, '1.5.2', '<')) {
        if (!MAPS_migratePublicFolder152()) {
            COM_errorLog('Maps 1.5.2 upgrade stopped: public-folder/SEO migration failed.');
            return false;
        }
    }

    /* Maps 1.5.3 configuration-only cleanup for sites already on 1.5.2. */
    if (version_compare($installed, '1.5.3', '<')) {
        if (!MAPS_cleanupConfig153()) {
            COM_errorLog('Maps 1.5.3 upgrade stopped: configuration cleanup failed.');
            return false;
        }
    }

    /* Maps 1.5.4 configuration tabs and extracted display defaults. */
    if (version_compare($installed, '1.5.4', '<')) {
        if (!MAPS_repairConfig154()) {
            COM_errorLog('Maps 1.5.4 upgrade stopped: configuration reorganization failed.');
            return false;
        }
    }

    /* Maps 1.5.5 fixes the 1.5.4 subgroup/tab presentation metadata. */
    if (version_compare($installed, '1.5.5', '<')) {
        if (!MAPS_repairConfig155()) {
            COM_errorLog('Maps 1.5.5 upgrade stopped: configuration tab repair failed.');
            return false;
        }
    }

    /* Maps 1.5.6 normalizes Geeklog selectionArray metadata (-1, never NULL). */
    if (version_compare($installed, '1.5.6', '<')) {
        if (!MAPS_repairConfig156()) {
            COM_errorLog('Maps 1.5.6 upgrade stopped: configuration metadata repair failed.');
            return false;
        }
    }

    /* Maps 1.5.8 removes the obsolete Pro-version infos_label option. */
    if (version_compare($installed, '1.5.8', '<')) {
        if (!MAPS_cleanupConfig158()) {
            COM_errorLog('Maps 1.5.8 upgrade stopped: configuration cleanup failed.');
            return false;
        }
    }

    /* Maps 1.5.9 adds the internal marker service operation ledger. */
    if (version_compare($installed, '1.5.9', '<')) {
        if (!MAPS_upgrade159()) {
            COM_errorLog('Maps 1.5.9 upgrade stopped: marker service operation table creation failed.');
            return false;
        }
    }

    /* Maps 1.5.10 adds landing-page SEO title, H1 and meta description settings. */
    if (version_compare($installed, '1.5.10', '<')) {
        if (!MAPS_upgrade1510()) {
            COM_errorLog('Maps 1.5.10 upgrade stopped: SEO configuration migration failed.');
            return false;
        }
    }

    if (version_compare($installed, $code, '<')) {
        DB_query("UPDATE {$_TABLES['plugins']} SET pi_version='" . MAPS_dbEscape($code)
            . "', pi_gl_version='2.1.1' WHERE pi_name='maps'");
        COM_errorLog('Updated Maps plugin from v' . $installed . ' to v' . $code, 1);
    }

    return true;
}

function MAPS_delTree($dir)
{
    if (!is_dir($dir)) {
        return true;
    }
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path) && !is_link($path)) {
            MAPS_delTree($path);
        } else {
            @unlink($path);
        }
    }
    return @rmdir($dir);
}

function plugin_autouninstall_maps()
{
    return array(
        'tables' => array('maps_maps', 'maps_geo', 'maps_markers', 'maps_submission', 'maps_overlays', 'maps_map_overlay', 'maps_map_icons', 'maps_overlays_groups', 'maps_service_operations'),
        'groups' => array('Maps Admin'),
        'features' => array('maps.admin'),
        'php_blocks' => array(),
        'vars' => array('globalMapHits')
    );
}

/**
 * Resolve an address with Google Geocoding API.
 */
function MAPS_getCoords($address, &$lat, &$lng)
{
    global $_MAPS_CONF;

    $lat = 0;
    $lng = 0;
    $browserKey = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_api_key', ''));
    $serverKey = trim((string) MAPS_arrayGet($_MAPS_CONF, 'google_server_api_key', ''));
    if ($browserKey === '' && $serverKey === '') {
        COM_errorLog('MAPS geocoder: no Google API key configured.');
        return false;
    }

    $url = MAPS_googleGeocodeUrl(stripslashes((string) $address));
    $response = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        if ($response === false) {
            COM_errorLog('MAPS geocoder cURL error: ' . curl_error($ch));
        }
        curl_close($ch);
    } elseif ((bool) ini_get('allow_url_fopen')) {
        $context = stream_context_create(array('http' => array('timeout' => 20), 'ssl' => array('verify_peer' => true, 'verify_peer_name' => true)));
        $response = @file_get_contents($url, false, $context);
    }

    if ($response === false || $response === '') {
        COM_errorLog('MAPS geocoder: unable to contact Google Geocoding API.');
        return false;
    }

    $json = json_decode($response, true);
    if (!is_array($json) || MAPS_arrayGet($json, 'status', '') !== 'OK'
        || !isset($json['results'][0]['geometry']['location']['lat'], $json['results'][0]['geometry']['location']['lng'])) {
        $status = is_array($json) ? MAPS_arrayGet($json, 'status', 'INVALID_RESPONSE') : 'INVALID_JSON';
        COM_errorLog('MAPS geocoder returned status: ' . $status);
        return false;
    }

    $lat = (float) $json['results'][0]['geometry']['location']['lat'];
    $lng = (float) $json['results'][0]['geometry']['location']['lng'];
    return true;
}

function MAPS_getDBCoords(&$gid, $address, &$lat, &$lng)
{
    global $_TABLES;

    $gid = '';
    $lat = 0;
    $lng = 0;
    $plainAddress = trim(stripslashes((string) $address));
    if ($plainAddress === '') {
        return;
    }
    $safeAddress = MAPS_dbEscape($plainAddress);
    $table = $_TABLES['maps_geo'];
    $res = DB_query("SELECT gid,lat,lng FROM {$table} WHERE geo='{$safeAddress}' LIMIT 1");
    if (DB_numRows($res) > 0) {
        $row = DB_fetchArray($res);
        $gid = $row['gid'];
        $lat = (float) MAPS_normalizeNumber($row['lat'], 0);
        $lng = (float) MAPS_normalizeNumber($row['lng'], 0);
        if ($lat != 0 || $lng != 0) {
            return;
        }
    }

    if (!MAPS_getCoords($plainAddress, $lat, $lng)) {
        return;
    }

    if ($gid !== '') {
        DB_query("UPDATE {$table} SET lat='" . (float) $lat . "',lng='" . (float) $lng . "' WHERE gid=" . (int) $gid);
    } else {
        DB_query("INSERT INTO {$table} (geo,lat,lng) VALUES ('{$safeAddress}','" . (float) $lat . "','" . (float) $lng . "')");
        $gid = DB_insertId();
    }
}

function MAPS_user_menu()
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    $menu = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $menu->set_file(array('menu' => 'user_menu.thtml'));
    $menu->set_var('maps_url', $_MAPS_CONF['site_url']);
    $menu->set_var('site_url', $_CONF['site_url']);
    $menu->set_var('maps', $LANG_MAPS_1['user_home']);
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) === 1) {
        $menu->set_var('submit_marker', ' | <a href="' . $_CONF['site_url'] . '/submit.php?type=maps">' . $LANG_MAPS_1['submit_marker'] . '</a>');
        $menu->set_var('my_markers', COM_isAnonUser() ? '' : ' | <a href="' . $_MAPS_CONF['site_url'] . '/markers.php">' . $LANG_MAPS_1['my_markers'] . '</a>');
    } else {
        $menu->set_var('submit_marker', '');
        $menu->set_var('my_markers', '');
    }
    $menu->set_var('admin', SEC_hasRights('maps.admin') ? ' | <a href="' . $_CONF['site_admin_url'] . '/plugins/maps/index.php">' . $LANG_MAPS_1['admin'] . '</a>' : '');
    return '<p>' . $menu->parse('output', 'menu') . '</p>';
}

function MAPS_admin_menu()
{
    global $_CONF, $LANG_MAPS_1;

    $menu = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $menu->set_file(array('menu' => 'admin_menu.thtml'));
    $menu->set_var('site_admin_url', $_CONF['site_admin_url']);
    $menu->set_var('site_url', $_CONF['site_url']);
    $menu->set_var('admin_home', isset($LANG_MAPS_1['admin_menu_maps']) ? $LANG_MAPS_1['admin_menu_maps'] : $LANG_MAPS_1['maps']);
    $menu->set_var('markers', isset($LANG_MAPS_1['admin_menu_markers']) ? $LANG_MAPS_1['admin_menu_markers'] : $LANG_MAPS_1['markers']);
    $menu->set_var('icons', isset($LANG_MAPS_1['admin_menu_icons']) ? $LANG_MAPS_1['admin_menu_icons'] : $LANG_MAPS_1['icons']);
    $menu->set_var('overlays', isset($LANG_MAPS_1['admin_menu_overlays']) ? $LANG_MAPS_1['admin_menu_overlays'] : $LANG_MAPS_1['overlays']);
    $menu->set_var('import_export', isset($LANG_MAPS_1['admin_menu_import_export']) ? $LANG_MAPS_1['admin_menu_import_export'] : $LANG_MAPS_1['import_export']);
    $menu->set_var('geocoder', isset($LANG_MAPS_1['admin_menu_geocoder']) ? $LANG_MAPS_1['admin_menu_geocoder'] : $LANG_MAPS_1['geocoder']);
    $menu->set_var('set_user_geo', isset($LANG_MAPS_1['admin_menu_geolocation']) ? $LANG_MAPS_1['admin_menu_geolocation'] : $LANG_MAPS_1['set_user_geo']);
    $menu->set_var('configuration', isset($LANG_MAPS_1['admin_menu_configuration']) ? $LANG_MAPS_1['admin_menu_configuration'] : (isset($LANG_MAPS_1['configuration']) ? $LANG_MAPS_1['configuration'] : 'Configuration'));
    return '<p>' . $menu->parse('output', 'menu') . '</p>';
}

function MAPS_filterVars($vars, &$arr)
{
    foreach ($vars as $var => $type) {
        $value = isset($arr[$var]) ? $arr[$var] : '';
        switch ($type) {
            case 'number':
                $arr[$var] = COM_applyFilter($value, true);
                break;
            case 'html':
                $arr[$var] = COM_checkHTML($value, 'maps.admin');
                break;
            case 'text':
                // Plain text must remain plain in storage. HTML escaping belongs
                // to the output context, not the input/database layer.
                $arr[$var] = strip_tags((string) $value);
                break;
            case 'int':
                $arr[$var] = (int) $value;
                break;
            default:
                $arr[$var] = COM_applyFilter($value, false);
                break;
        }
    }
}

function MAPS_geocoding()
{
    global $_CONF, $LANG_MAPS_1;
    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('block', 'geocoder.thtml');
    $t->set_var('geocoder', $LANG_MAPS_1['geocoder']);
    $t->set_var('geocoder_text', $LANG_MAPS_1['geocoder_text']);
    $t->set_var('go', $LANG_MAPS_1['go']);
    $t->parse('output', 'block');
    return $t->finish($t->get_var('output'));
}

function MAPS_recurseMaps($sel)
{
    global $_TABLES, $LANG_MAPS_1;
    $str = '';
    $result = DB_query("SELECT mid,name FROM {$_TABLES['maps_maps']} ORDER BY name");
    while ($row = DB_fetchArray($result, false)) {
        $count = DB_count($_TABLES['maps_markers'], 'mid', $row['mid']);
        $unit = $count == 1 ? $LANG_MAPS_1['1marker'] : $LANG_MAPS_1['xmarkers'];
        $selected = ((string) $row['mid'] === (string) $sel) ? ' selected="selected"' : '';
        $str .= '<option value="' . (int) $row['mid'] . '"' . $selected . '>'
            . (int) $row['mid'] . '. ' . htmlspecialchars(stripslashes($row['name']), ENT_QUOTES, 'UTF-8')
            . ' (' . (int) $count . ' ' . $unit . ')</option>' . LB;
    }
    return $str;
}

/** Fill shared map template variables. */
function MAPS_fillMapCommon($t, $id, $lat, $lng, $zoom, $type, $width, $height)
{
    global $_MAPS_CONF, $LANG_MAPS_1;
    $t->set_var('mid', $id);
    $t->set_var('gid', $id);
    $safeLat = MAPS_latitude($lat, 0);
    $safeLng = MAPS_longitude($lng, 0);
    $safeZoom = MAPS_zoom($zoom, 2);
    $t->set_var('lat_js', MAPS_jsNumber($safeLat, 0));
    $t->set_var('lng_js', MAPS_jsNumber($safeLng, 0));
    $t->set_var('zoom_js', MAPS_jsNumber($safeZoom, 2));
    // Keep legacy template variables for non-JavaScript uses.
    $t->set_var('lat', $safeLat);
    $t->set_var('lng', $safeLng);
    $t->set_var('zoom', $safeZoom);
    $mapType = preg_replace('/[^A-Z_]/', '', strtoupper((string) $type));
    if (!in_array($mapType, array('ROADMAP', 'SATELLITE', 'HYBRID', 'TERRAIN'), true)) {
        $mapType = 'ROADMAP';
    }
    $t->set_var('map_type', $mapType);
    $t->set_var('map_width', MAPS_cssSize($width, '100%'));
    $t->set_var('map_height', MAPS_cssSize($height, '400px'));
    $t->set_var('current_address', '');
    $t->set_var('current_address_js', MAPS_jsString(''));
    $t->set_var('error_address_empty_js', MAPS_jsString($LANG_MAPS_1['error_address_empty']));
    $t->set_var('error_google_error_js', MAPS_jsString($LANG_MAPS_1['error_google_error']));
    $t->set_var('directions_input', '');
    $t->set_var('overlays_js', '');
    $t->set_var('overlays', '');
    $t->set_var('overlays_checkboxes', '');
    $t->set_var('date_and_hits', '');
    $t->set_var('edit_button', '');
    $t->set_var('error_address_empty', $LANG_MAPS_1['error_address_empty']);
    $t->set_var('error_invalid_address', $LANG_MAPS_1['error_invalid_address']);
    $t->set_var('error_google_error', $LANG_MAPS_1['error_google_error']);
    $t->set_var('error_no_map_info', $LANG_MAPS_1['error_no_map_info']);
    $t->set_var('markerclusterer', (int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0) ? MAPS_clusterScriptTag() : '');
}

function MAPS_getMap($mid, $zoom = '', $address = '')
{
    global $_CONF, $_TABLES, $_MAPS_CONF, $LANG_MAPS_1, $LANG_LOGIN, $_SCRIPTS;

    $mid = (int) $mid;
    if ($mid < 1) {
        return '';
    }
    $res = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid={$mid} LIMIT 1");
    if (DB_numRows($res) !== 1) {
        return '';
    }
    $a = DB_fetchArray($res);
    if ((int) $a['active'] === 0 && !SEC_hasRights('maps.admin')) {
        return '';
    }
    if (SEC_hasAccess($a['owner_id'], $a['group_id'], $a['perm_owner'], $a['perm_group'], $a['perm_members'], $a['perm_anon']) < 2) {
        return MAPS_message($LANG_MAPS_1['private_access'], $LANG_MAPS_1['private_access']);
    }

    $lat = MAPS_latitude($a['lat'], 0);
    $lng = MAPS_longitude($a['lng'], 0);
    if (trim($address) !== '') {
        MAPS_getDBCoords($gid, $address, $lat, $lng);
    }
    $mapZoom = is_numeric($zoom) ? (int) $zoom : (int) $a['zoom'];

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'map.thtml');
    MAPS_fillMapCommon($t, $mid, $lat, $lng, $mapZoom, $a['type'], $a['width'], $a['height']);
    $t->set_var('name', htmlspecialchars(stripslashes($a['name']), ENT_QUOTES, 'UTF-8'));
    $t->set_var('description', $a['description'] !== '' ? '<p>' . PLG_replaceTags(stripslashes($a['description'])) . '</p>' : '');
    $t->set_var('header', $a['header'] !== '' ? PLG_replaceTags($a['header']) : '');
    $t->set_var('footer', $a['footer'] !== '' ? PLG_replaceTags($a['footer']) : '');
    $t->set_var('address', htmlspecialchars($address !== '' ? $address : $a['geo'], ENT_QUOTES, 'UTF-8'));

    $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE mid={$mid} AND active=1 AND hidden=0";
    $markers = 'var markers = [];' . LB
        . MAPS_getMarkers($sql, $a['primary_color'], $a['stroke_color'], $a['label'], ((int) $a['label_color'] ? '#FFFFFF' : '#000000'), 0, $a['name'], $mid, $a);
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $markers .= MAPS_clusterInitJs('map' . $mid);
    }
    $t->set_var('markers', $markers);

    $overlays = '';
    $checks = '';
    $or = DB_query("SELECT o.* FROM {$_TABLES['maps_map_overlay']} mo LEFT JOIN {$_TABLES['maps_overlays']} o ON mo.mo_oid=o.oid WHERE mo.mo_mid={$mid} AND o.o_active=1 ORDER BY o.o_order");
    $oi = 0;
    while ($o = DB_fetchArray($or)) {
        $oi++;
        $image = $_MAPS_CONF['images_overlay_url'] . rawurlencode($o['o_image']);
        $swLat = MAPS_arrayGet($o, 'o_sw_lat', null);
        $swLng = MAPS_arrayGet($o, 'o_sw_lng', null);
        $neLat = MAPS_arrayGet($o, 'o_ne_lat', null);
        $neLng = MAPS_arrayGet($o, 'o_ne_lng', null);
        if (!MAPS_isValidCoordinatePair($swLat, $swLng) || !MAPS_isValidCoordinatePair($neLat, $neLng)) {
            COM_errorLog('Maps: overlay ' . (string) MAPS_arrayGet($o, 'oid', '') . ' ignored because its bounds are invalid.');
            continue;
        }
        $overlays .= "var bounds{$oi}=new google.maps.LatLngBounds({lat:Number(" . MAPS_jsNumber(MAPS_latitude($swLat, 0), 0) . "),lng:Number(" . MAPS_jsNumber(MAPS_longitude($swLng, 0), 0) . ")},{lat:Number(" . MAPS_jsNumber(MAPS_latitude($neLat, 0), 0) . "),lng:Number(" . MAPS_jsNumber(MAPS_longitude($neLng, 0), 0) . ")});\n"
            . "var overlay{$oi}=new google.maps.GroundOverlay(" . MAPS_jsString($image) . ",bounds{$oi});\n"
            . "overlay{$oi}.setMap(map{$mid});\n";
        $checks .= '<div><strong>' . htmlspecialchars($o['o_name'], ENT_QUOTES, 'UTF-8') . '</strong><br><img src="' . htmlspecialchars($image, ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:200px;height:auto"></div>';
    }
    $t->set_var('overlays', $overlays);
    $t->set_var('overlays_checkboxes', $checks);

    $modified = COM_getUserDateTimeFormat($a['modified']);
    $t->set_var('date_and_hits', $LANG_MAPS_1['last_modification'] . ' ' . $modified[0] . ' | ' . (int) $a['hits'] . ' ' . $LANG_MAPS_1['hits']);
    if (SEC_hasRights('maps.admin')) {
        $t->set_var('edit_button', '<p><a href="' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&mid=' . $mid . '">' . $LANG_MAPS_1['edit_button'] . '</a></p>');
    }
    $t->parse('output', 'page');
    hitMap($mid);
    return $t->finish($t->get_var('output'));
}

function MAPS_getGlobalMap($zoom = '', $address = '', $pagelist = false)
{
    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF, $_CONF;

    if ((int) MAPS_arrayGet($_MAPS_CONF, 'global_map', 1) !== 1) {
        return '';
    }
    $first = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 ORDER BY mid ASC LIMIT 1");
    if (DB_numRows($first) < 1) {
        return '';
    }
    $a = DB_fetchArray($first);
    $lat = MAPS_latitude($a['lat'], 0);
    $lng = MAPS_longitude($a['lng'], 0);
    if ($address !== '') {
        MAPS_getDBCoords($gid, $address, $lat, $lng);
    }
    $mapZoom = is_numeric($zoom) ? (int) $zoom : (int) MAPS_arrayGet($_MAPS_CONF, 'global_zoom', 2);

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', 'map.thtml');
    MAPS_fillMapCommon($t, 0, $lat, $lng, $mapZoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), MAPS_arrayGet($_MAPS_CONF, 'global_width', '100%'), MAPS_arrayGet($_MAPS_CONF, 'global_height', '600px'));
    $t->set_var('name', $pagelist ? '' : $LANG_MAPS_1['global_map']);
    $t->set_var('description', $pagelist ? '' : '<p>' . $LANG_MAPS_1['info_global_map'] . '</p>');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('address', htmlspecialchars($address !== '' ? $address : $a['geo'], ENT_QUOTES, 'UTF-8'));

    $js = 'var markers = [];' . LB;
    $maps = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 AND hidden=0 ORDER BY mid ASC");
    while ($m = DB_fetchArray($maps)) {
        if (!SEC_hasAccess2($m)) {
            continue;
        }
        $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE mid=" . (int) $m['mid'] . " AND active=1 AND hidden=0";
        $js .= MAPS_getMarkers($sql, $m['primary_color'], $m['stroke_color'], $m['label'], ((int) $m['label_color'] ? '#FFFFFF' : '#000000'), 1, $m['name'], 0, $m);
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('map0');
    }
    $t->set_var('markers', $js);
    $t->parse('output', 'page');
    hitMap(0);
    return $t->finish($t->get_var('output'));
}

function MAPS_getMarkers($sql, $primaryColor, $strokecolor, $label, $label_color, $global, $mapname = '', $mapid = '', $map = array())
{
    $markers = '';
    if ($sql === '') {
        return $markers;
    }
    $res = DB_query($sql);
    while ($marker = DB_fetchArray($res)) {
        if (SEC_hasAccess2($marker)) {
            $markers .= MAPS_renderMarker($marker, $primaryColor, $strokecolor, $label, $label_color, $mapname, '', '', $mapid, true, $map);
        }
    }
    return $markers;
}

function MAPS_renderMarker($marker, $primaryColor, $strokecolor, $label, $label_color, $mapname = '', $field_tab = '', $category = '', $mapid = 0, $infos = true, $map3 = array())
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_1, $_TABLES, $_USER;

    if (!is_array($marker) || !MAPS_checkMarkervalidity($marker)) {
        return '';
    }
    if ((int) MAPS_arrayGet($marker, 'active', 1) === 0
        || ((int) MAPS_arrayGet($marker, 'hidden', 0) === 1 && !SEC_hasRights('maps.admin'))) {
        return '';
    }

    $mkid = preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $marker['mkid']);
    $var = 'marker' . $mkid;
    $mapVar = 'map' . $mapid;
    $name = MAPS_markerDisplayName(MAPS_arrayGet($marker, 'name', ''));
    if (!MAPS_isValidCoordinatePair(MAPS_arrayGet($marker, 'lat', null), MAPS_arrayGet($marker, 'lng', null))) {
        if (function_exists('COM_errorLog')) {
            COM_errorLog('Maps: marker ' . (string) MAPS_arrayGet($marker, 'mkid', '') . ' ignored because its coordinates are invalid.');
        }
        return '';
    }
    $lat = MAPS_latitude(MAPS_arrayGet($marker, 'lat', 0), 0);
    $lng = MAPS_longitude(MAPS_arrayGet($marker, 'lng', 0), 0);
    $iconUrl = '';

    $markerIconId = (int) MAPS_arrayGet($marker, 'mk_icon', 0);
    $useDefault = (int) MAPS_arrayGet($marker, 'mk_default', 1);
    $mapIconId = (int) MAPS_arrayGet($map3, 'mmk_icon', 0);

    // Icon priority is explicit marker icon, then explicit map icon, then
    // the generated/default Google marker. Historically mmk_default could
    // suppress a selected map icon, leaving mmk_icon stored but ignored.
    if ($useDefault === 0 && $markerIconId > 0) {
        $selectedIcon = $markerIconId;
    } elseif ($useDefault !== 0 && $mapIconId > 0) {
        $selectedIcon = $mapIconId;
    } else {
        $selectedIcon = 0;
    }

    if ($selectedIcon > 0) {
        $icon = DB_getItem($_TABLES['maps_map_icons'], 'icon_image', 'icon_id=' . $selectedIcon);
        $path = $_MAPS_CONF['path_icons_images'] . $icon;
        if ($icon !== '' && file_exists($path) && @getimagesize($path) !== false) {
            $iconUrl = $_MAPS_CONF['images_icons_url'] . rawurlencode($icon);
        }
    }
    if ($iconUrl === '') {
        $fill = $useDefault === 0 ? MAPS_arrayGet($marker, 'mk_pcolor', $primaryColor) : $primaryColor;
        $text = $useDefault === 0 ? MAPS_arrayGet($marker, 'mk_label', $label) : $label;
        $textColor = $useDefault === 0 && (int) MAPS_arrayGet($marker, 'mk_label_color', 0) ? '#FFFFFF' : $label_color;
        $iconUrl = MAPS_svgMarkerUri($fill, $text, $textColor);
    }

    $js = "var {$var}=new google.maps.Marker({position:{lat:Number(" . MAPS_jsNumber($lat, 0) . "),lng:Number(" . MAPS_jsNumber($lng, 0) . ")},map:{$mapVar},title:" . MAPS_jsString($name) . ",icon:" . MAPS_jsString($iconUrl) . "});\n";

    if ($infos) {
        $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
        if ((string) $mapid === 'events') {
            $template->set_file(array('presentation' => 'presentation_tab_event.thtml'));
            $template->set_var('name', htmlspecialchars($name, ENT_QUOTES, 'UTF-8'));
            $template->set_var('popup_width', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_width', '250px'), '250px'));
            $template->set_var('popup_height', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_height', '150px'), '150px'));
            $description = (string) MAPS_arrayGet($marker, 'description', '');
            if (function_exists('mb_substr')) {
                $description = mb_substr($description, 0, 150, 'UTF-8');
            } else {
                $description = substr($description, 0, 150);
            }
            $template->set_var('description', $description !== '' ? '<p>' . $description . '</p>' : '');
            $template->set_var('read_more', '<a href="' . $_CONF['site_url'] . '/calendar/event.php?eid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['read_more'] . '</a>');
            $template->set_var('map', '');
        } else {
            $template->set_file(array('presentation' => 'presentation_tab.thtml'));
            $template->set_var('name', '<strong>' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '</strong>');
            $template->set_var('popup_width', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_width', '250px'), '250px'));
            $template->set_var('popup_height', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'popup_height', '150px'), '150px'));
            $description = MAPS_decodeStoredText(MAPS_arrayGet($marker, 'description', ''));
            if (function_exists('mb_substr')) {
                $short = mb_substr($description, 0, 150, 'UTF-8');
            } else {
                $short = substr($description, 0, 150);
            }
            $template->set_var('description', $short !== '' ? '<p>' . nl2br(htmlspecialchars($short, ENT_QUOTES, 'UTF-8')) . (strlen($description) > strlen($short) ? '…' : '') . '</p>' : '');
            $template->set_var('ressources', MAPS_renderPublicCustomFields($marker, 'compact'));
            $readMore = MAPS_markerContentUrl($marker['mkid']);
            $template->set_var('read_more', '<a class="maps-popup-primary-action" href="' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '">' . $LANG_MAPS_1['read_more'] . '</a>');
            $template->set_var('map', '<div class="maps-popup-map-link"><span>' . htmlspecialchars($LANG_MAPS_1['on_map'], ENT_QUOTES, 'UTF-8') . '</span> <a href="' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&mid=' . (int) $marker['mid'] . '">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></div>');
            $currentUid = isset($_USER['uid']) ? (int) $_USER['uid'] : 1;
            $referentUid = MAPS_resolveMarkerOwnerUid($marker);
            $template->set_var('edit', SEC_hasRights('maps.admin') || $currentUid === $referentUid ? '<a class="maps-popup-edit-action" href="' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');
        }
        $presentation = $template->parse('output', 'presentation');
        $infoVar = 'infowindow' . $mkid;
        $js .= "var {$infoVar}=new google.maps.InfoWindow({content:" . MAPS_jsString($presentation) . "});\n"
            . "{$var}.addListener('click',function(){ {$infoVar}.open({map:{$mapVar},anchor:{$var}}); });\n";
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= "markers.push({$var});\n";
    }
    return $js;
}

function MAPS_checkMarkervalidity($marker)
{
    if (!is_array($marker) || !SEC_hasAccess2($marker)) {
        return false;
    }
    if ((int) MAPS_arrayGet($marker, 'validity', 0) === 1 && !SEC_hasRights('maps.admin')) {
        $now = time();
        $start = strtotime(MAPS_arrayGet($marker, 'validity_start', ''));
        $end = strtotime(MAPS_arrayGet($marker, 'validity_end', ''));
        if (($start && $now < $start) || ($end && $now > $end)) {
            return false;
        }
    }
    return true;
}

function MAPS_selectedMarkers($width, $height, $zoom, $mkids = array())
{
    global $_TABLES;
    if (!is_array($mkids) || empty($mkids)) {
        return '';
    }
    $ids = array();
    foreach ($mkids as $id) {
        $ids[] = "'" . MAPS_dbEscape($id) . "'";
    }
    $res = DB_query("SELECT mk.*,ma.mmk_default,ma.mmk_icon,ma.name AS mapname,ma.primary_color,ma.stroke_color,ma.label,ma.label_color FROM {$_TABLES['maps_markers']} mk LEFT JOIN {$_TABLES['maps_maps']} ma ON mk.mid=ma.mid WHERE mk.mkid IN (" . implode(',', $ids) . ')');
    $markers = array();
    $lat = 0;
    $lng = 0;
    while ($a = DB_fetchArray($res)) {
        $markers[] = $a;
        $lat += MAPS_latitude($a['lat'], 0);
        $lng += MAPS_longitude($a['lng'], 0);
    }
    if (empty($markers)) {
        return '';
    }
    $lat /= count($markers);
    $lng /= count($markers);
    return MAPS_getMarkerDetail(0, $markers, 1, $width, $height, $zoom, $lat, $lng);
}

function MAPS_getMarkerDetail($mid = 0, $mkid = '', $autotag = 0, $mapwidth = '', $mapheight = '', $mapzoom = '', $lat = '0', $lng = '0')
{
    global $_TABLES, $LANG_MAPS_1, $_MAPS_CONF, $_CONF;

    $list = array();
    $map = array('mmk_default' => 1, 'mmk_icon' => 0, 'name' => '', 'primary_color' => MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666'), 'stroke_color' => MAPS_arrayGet($_MAPS_CONF, 'map_stroke_color', '#333333'), 'label' => MAPS_arrayGet($_MAPS_CONF, 'map_label', ''), 'label_color' => MAPS_arrayGet($_MAPS_CONF, 'map_label_color', 0));
    if ((int) $mid > 0) {
        $resMap = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . (int) $mid . ' LIMIT 1');
        if (DB_numRows($resMap) < 1) {
            return '';
        }
        $map = DB_fetchArray($resMap);
        if (!SEC_hasAccess2($map)) {
            return '';
        }
        $safe = MAPS_dbEscape($mkid);
        $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='{$safe}' LIMIT 1");
        if (DB_numRows($res) < 1) {
            return '';
        }
        $marker = DB_fetchArray($res);
        if (!MAPS_checkMarkervalidity($marker)) {
            return $LANG_MAPS_1['marker_limited'];
        }
        $list[] = $marker;
        $lat = $marker['lat'];
        $lng = $marker['lng'];
        $mapwidth = MAPS_arrayGet($_MAPS_CONF, 'global_width', $mapwidth);
        $mapheight = MAPS_arrayGet($_MAPS_CONF, 'map_height_geotag', $mapheight);
        $mapzoom = MAPS_arrayGet($_MAPS_CONF, 'detail_zoom', $mapzoom);
    } elseif (is_array($mkid)) {
        $list = $mkid;
    }
    if (empty($list)) {
        return '';
    }

    $id = (int) $mid > 0 ? preg_replace('/[^a-zA-Z0-9_]/', '_', (string) $list[0]['mkid']) : mt_rand(10000, 999999);
    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', $autotag ? 'map_autotag.thtml' : 'marker_map.thtml');
    MAPS_fillMapCommon($t, $id, $lat, $lng, $mapzoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), $mapwidth, $mapheight);
    $t->set_var('name', '');
    $t->set_var('description', '');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('address', '');

    $js = 'var markers = [];' . LB;
    foreach ($list as $marker) {
        if (!MAPS_checkMarkervalidity($marker)) {
            continue;
        }
        $js .= MAPS_renderMarker($marker, MAPS_arrayGet($map, 'primary_color', '#666666'), MAPS_arrayGet($map, 'stroke_color', '#333333'), MAPS_arrayGet($map, 'label', ''), (int) MAPS_arrayGet($map, 'label_color', 0) ? '#FFFFFF' : '#000000', MAPS_arrayGet($map, 'name', MAPS_arrayGet($marker, 'mapname', '')), '', '', $id, true, $map);
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('map' . $id);
    }
    $t->set_var('markers', $js);

    if (!$autotag && count($list) === 1) {
        $gid = $id;
        $address = MAPS_arrayGet($list[0], 'address', '');
        $value = htmlentities($LANG_MAPS_1['value_directions'], ENT_QUOTES, 'UTF-8');
        $directionsTitle = isset($LANG_MAPS_1['directions_title']) ? $LANG_MAPS_1['directions_title'] : $LANG_MAPS_1['need_directions'];
        $directionsStart = isset($LANG_MAPS_1['directions_start']) ? $LANG_MAPS_1['directions_start'] : $LANG_MAPS_1['from'];
        $t->set_var('current_address', htmlspecialchars($address, ENT_QUOTES, 'UTF-8'));
        $t->set_var('current_address_js', MAPS_jsString($address));
        $t->set_var('directions_input', '<section id="map_directions" class="maps-directions-panel"><h2>'
            . htmlspecialchars($directionsTitle, ENT_QUOTES, 'UTF-8') . '</h2><label for="from_address' . $gid . '">'
            . htmlspecialchars($directionsStart, ENT_QUOTES, 'UTF-8') . '</label><div class="maps-directions-form"><input type="text" id="from_address'
            . $gid . '" value="" placeholder="' . $value . '" autocomplete="street-address"><input type="button" value="'
            . htmlspecialchars(trim($LANG_MAPS_1['get_directions']), ENT_QUOTES, 'UTF-8') . '" onclick="get_directions' . $gid
            . '();"></div></section><a id="directions_' . $gid . '_table"></a><div id="directions' . $gid . '" class="maps-directions-result"></div>');
    }
    $t->parse('output', 'page');
    return $t->finish($t->get_var('output'));
}

function plugin_ismoderator_maps()
{
    return SEC_hasRights('maps.admin');
}

function plugin_submissioncount_maps()
{
    global $_TABLES;
    return DB_count($_TABLES['maps_submission']);
}

function plugin_submit_maps($marker = array())
{
    global $_CONF, $_MAPS_CONF, $LANG12, $LANG_MAPS_1, $LANG_LOGIN, $_SCRIPTS;

    if (!is_array($marker)) {
        $marker = array();
    }
    if (SEC_hasRights('maps.admin')) {
        return COM_refresh($_CONF['site_admin_url'] . '/plugins/maps/marker_edit.php');
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) !== 1) {
        return $LANG_MAPS_1['submission_disabled'];
    }
    if (COM_isAnonUser() && ((int) MAPS_arrayGet($_CONF, 'loginrequired', 0) || (int) MAPS_arrayGet($_MAPS_CONF, 'maps_login_required', 0) || (int) MAPS_arrayGet($_MAPS_CONF, 'submit_login_required', 0))) {
        return COM_startBlock($LANG_LOGIN[1]) . $LANG_LOGIN[2] . COM_endBlock();
    }

    $defaults = array(
        'name' => '', 'address' => '', 'lat' => '37.4217913', 'lng' => '-122.0837139', 'mid' => '',
        'created' => time(), 'modified' => time(), 'description' => '', 'street' => '', 'code' => '', 'city' => '',
        'state' => '', 'country' => '', 'tel' => '', 'fax' => '', 'web' => '', 'remark' => '', 'validity' => 0,
        'from' => '', 'to' => '', 'group_id' => 2, 'mkid' => ''
    );
    for ($i = 1; $i <= 10; $i++) {
        $defaults['item_' . $i] = '';
    }
    $marker = array_merge($defaults, $marker);

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('form' => 'marker_submit_form.thtml'));
        $template->set_var('marker_editor_width', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'marker_editor_width', '100%'), '100%'));
        $template->set_var('marker_editor_height', MAPS_cssSize(MAPS_arrayGet($_MAPS_CONF, 'marker_editor_height', '400px'), '400px'));
    $template->set_var('site_url', $_CONF['site_url']);
    $template->set_var('go', $LANG_MAPS_1['go']);
    $template->set_var('submit_marker', $LANG_MAPS_1['submit_marker']);
    $template->set_var('submit_marker_text', $LANG_MAPS_1['submit_marker_text']);
    $template->set_var('lang_preview', $LANG12[32]);
    $template->set_var('lang_save', $LANG12[8]);
    $template->set_var('informations', $LANG_MAPS_1['informations']);
    $template->set_var('name_label', $LANG_MAPS_1['marker_name_label']);
    $template->set_var('name', htmlspecialchars($marker['name'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('address_label', $LANG_MAPS_1['address_label']);
    $template->set_var('address', htmlspecialchars($marker['address'], ENT_QUOTES, 'UTF-8'));
    $template->set_var('empty_for_geo', $LANG_MAPS_1['empty_for_geo']);
    $template->set_var('lat', $LANG_MAPS_1['lat']);
    $template->set_var('lat_value', $marker['lat']);
    $template->set_var('lng', $LANG_MAPS_1['lng']);
    $template->set_var('lng_value', $marker['lng']);
    $template->set_var('select_marker_map', $LANG_MAPS_1['select_marker_map']);
    $template->set_var('mid_label', $LANG_MAPS_1['name_label']);
    $template->set_var('mid', $marker['mid']);
    $template->set_var('map_options', MAPS_recurseMaps($marker['mid']));
    $template->set_var('created_label', $LANG_MAPS_1['marker_created']);
    $template->set_var('modified_label', $LANG_MAPS_1['modified']);
    $template->set_var('created', is_numeric($marker['created']) ? date('Y-m-d H:i', $marker['created']) : $marker['created']);
    $template->set_var('modified', is_numeric($marker['modified']) ? date('Y-m-d H:i', $marker['modified']) : $marker['modified']);
    $template->set_var('required_field', $LANG_MAPS_1['required_field']);
    $template->set_var('presentation_tab', $LANG_MAPS_1['presentation_tab']);
    $template->set_var('description_label', $LANG_MAPS_1['description_label']);
    $template->set_var('description', htmlspecialchars($marker['description'], ENT_QUOTES, 'UTF-8'));

    foreach (array('street','code','city','state','country','tel','fax','web') as $field) {
        $labelKey = $field . '_label';
        $template->set_var($labelKey, $LANG_MAPS_1[$labelKey]);
        $template->set_var($field, '<input type="text" name="' . $field . '" value="' . htmlspecialchars($marker[$field], ENT_QUOTES, 'UTF-8') . '">');
    }
    $template->set_var('ressources_tab', $LANG_MAPS_1['ressources_tab']);
    $template->set_var('max_char', $LANG_MAPS_1['max_char']);
    $resources = '';
    for ($i = 1; $i <= 10; $i++) {
        $key = 'item_' . $i;
        $label = MAPS_arrayGet($_MAPS_CONF, $key, '');
        $template->set_var($key . '_label', $label);
        $template->set_var($key, htmlspecialchars($marker[$key], ENT_QUOTES, 'UTF-8'));
        if ($label !== '') {
            $resources .= '<p>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . ' <input type="text" name="' . $key . '" value="' . htmlspecialchars($marker[$key], ENT_QUOTES, 'UTF-8') . '"></p>';
        }
    }
    $template->set_var('ressources', $resources !== '' ? $resources : $LANG_MAPS_1['empty_ressources']);
    $template->set_var('save_button', $LANG_MAPS_1['save_button']);
    $template->set_var('delete_button', $LANG_MAPS_1['delete_button']);
    $template->set_var('mkid', $marker['mkid'] !== '' ? '<input type="hidden" name="mkid" value="' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '">' : '');

    $_SCRIPTS->setJavaScriptLibrary('jquery');
    $editorZoom = (int) MAPS_arrayGet($_MAPS_CONF, 'marker_editor_zoom', 10);
    $editorType = MAPS_mapType(MAPS_arrayGet($_MAPS_CONF, 'marker_editor_type', 'ROADMAP'));
    $js = "jQuery(function(){var el=document.getElementById('map_canvas');if(!el||!window.google){return;}var map=new google.maps.Map(el,{center:{lat:Number(" . MAPS_jsNumber($marker['lat'], 0) . "),lng:Number(" . MAPS_jsNumber($marker['lng'], 0) . ")},zoom:" . $editorZoom . ",mapTypeId:google.maps.MapTypeId." . $editorType . "});var mk=new google.maps.Marker({map:map,position:{lat:Number(" . MAPS_jsNumber($marker['lat'], 0) . "),lng:Number(" . MAPS_jsNumber($marker['lng'], 0) . ")},draggable:true});mk.addListener('dragend',function(e){document.getElementById('lat').value=e.latLng.lat().toFixed(6);document.getElementById('lng').value=e.latLng.lng().toFixed(6);});window.codeAddress=function(){var g=new google.maps.Geocoder();g.geocode({address:document.getElementById('geoaddress').value},function(r,s){if(s==='OK'&&r[0]){map.setCenter(r[0].geometry.location);mk.setPosition(r[0].geometry.location);document.getElementById('lat').value=r[0].geometry.location.lat();document.getElementById('lng').value=r[0].geometry.location.lng();}else{alert('Geocode: '+s);}});};});";
    $_SCRIPTS->setJavaScript($js, true, true);
    return $template->parse('output', 'form');
}

function plugin_savesubmission_maps($a)
{
    global $_TABLES, $_USER, $LANG_MAPS_1, $_MAPS_CONF;

    if (!is_array($a) || empty($a['name']) || empty($a['address'])) {
        COM_resetSpeedlimit('submit');
        return MAPS_message($LANG_MAPS_1['missing_field'], $LANG_MAPS_1['error']) . plugin_submit_maps(is_array($a) ? $a : array());
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) !== 1) {
        return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=2');
    }

    $ownerId = isset($_USER['uid']) && (int) $_USER['uid'] > 0 ? (int) $_USER['uid'] : 1;
    $lat = isset($a['lat']) ? MAPS_latitude($a['lat'], 0) : 0;
    $lng = isset($a['lng']) ? MAPS_longitude($a['lng'], 0) : 0;
    if ($lat == 0 && $lng == 0) {
        MAPS_getCoords($a['address'], $lat, $lng);
    }
    $mkid = COM_makeSid();
    $now = date('YmdHis');
    $from = !empty($a['from']) ? date('YmdHis', strtotime($a['from'])) : $now;
    $to = !empty($a['to']) ? date('YmdHis', strtotime($a['to'])) : $now;
    if (strtotime($from) > strtotime($to)) {
        return MAPS_message($LANG_MAPS_1['date_issue'], $LANG_MAPS_1['error']) . plugin_submit_maps($a);
    }

    $fields = array('name','description','address','remark','street','code','city','state','country','tel','fax','web');
    for ($i = 1; $i <= 10; $i++) {
        $fields[] = 'item_' . $i;
    }
    foreach ($fields as $field) {
        $a[$field] = MAPS_dbEscape(MAPS_arrayGet($a, $field, ''));
    }
    $mid = (int) MAPS_arrayGet($a, 'mid', 0);
    $groupId = (int) MAPS_arrayGet($a, 'group_id', 2);
    $perm = MAPS_arrayGet($_MAPS_CONF, 'default_permissions', array(3,3,2,2));
    if (!is_array($perm) || count($perm) < 4) {
        $perm = array(3,3,2,2);
    }

    $columns = 'mkid,name,description,created,modified,payed,validity,validity_start,validity_end,active,hidden,address,lat,lng,mid,remark,street,city,code,state,country,tel,fax,web,item_1,item_2,item_3,item_4,item_5,item_6,item_7,item_8,item_9,item_10,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon,submission,mk_default';
    $values = array("'" . MAPS_dbEscape($mkid) . "'", "'{$a['name']}'", "'{$a['description']}'", "'{$now}'", "'{$now}'", '0', (int) MAPS_arrayGet($a, 'validity', 0), "'{$from}'", "'{$to}'", '1', '0', "'{$a['address']}'", (float) $lat, (float) $lng, $mid, "'{$a['remark']}'", "'{$a['street']}'", "'{$a['city']}'", "'{$a['code']}'", "'{$a['state']}'", "'{$a['country']}'", "'{$a['tel']}'", "'{$a['fax']}'", "'{$a['web']}'");
    for ($i = 1; $i <= 10; $i++) {
        $values[] = "'{$a['item_{$i}']}'";
    }
    $values = array_merge($values, array($ownerId, $groupId, (int) $perm[0], (int) $perm[1], (int) $perm[2], (int) $perm[3], 1, 1));
    DB_save($_TABLES['maps_submission'], $columns, implode(',', $values));
    if (DB_error()) {
        return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=3');
    }
    $a['mkid'] = $mkid;
    MAPS_sendNotification($a);
    return COM_refresh($_MAPS_CONF['site_url'] . '/index.php?msg=1');
}

function MAPS_sendNotification($data, $modification = false)
{
    global $_CONF, $_MAPS_CONF, $LANG_MAPS_EMAIL, $_TABLES;
    $name = stripslashes(MAPS_arrayGet($data, 'name', ''));
    $description = stripslashes(MAPS_arrayGet($data, 'description', ''));
    $map = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) MAPS_arrayGet($data, 'mid', 0));
    $action = $modification ? $LANG_MAPS_EMAIL['marker_modification'] : $LANG_MAPS_EMAIL['new_marker'];
    $body = $action . "\n\n" . $LANG_MAPS_EMAIL['name'] . ' ' . $name . "\n" . $LANG_MAPS_EMAIL['description'] . ' ' . $description . "\n\n" . $LANG_MAPS_EMAIL['on_map'] . ' ' . $map . "\n\n";
    if ($modification) {
        $body .= $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode(MAPS_arrayGet($data, 'mkid', '')) . '&mid=' . (int) MAPS_arrayGet($data, 'mid', 0);
        $subject = $_CONF['site_name'] . ' ' . $LANG_MAPS_EMAIL['marker_modification'];
    } else {
        $body .= $LANG_MAPS_EMAIL['submissions'] . ' >> ' . $_CONF['site_admin_url'] . '/moderation.php';
        $subject = $_CONF['site_name'] . ' ' . $LANG_MAPS_EMAIL['marker_submissions'];
    }
    COM_mail($_CONF['site_mail'], $subject, $body);
}

function plugin_moderationvalues_maps()
{
    global $_TABLES;
    return array('mkid', $_TABLES['maps_markers'], 'mkid,name,address,lat,lng,mid,mk_default,payed,created,modified,validity,validity_start,validity_end,active,hidden,remark,description,street,code,city,state,country,tel,fax,web,item_1,item_2,item_3,item_4,item_5,item_6,item_7,item_8,item_9,item_10,hits,owner_id,group_id,perm_owner,perm_group,perm_members,perm_anon,submission', $_TABLES['maps_submission']);
}

function plugin_itemlist_maps()
{
    global $_TABLES, $LANG_MAPS_1;
    if (!plugin_ismoderator_maps()) {
        return null;
    }
    $plugin = new Plugin();
    $plugin->submissionlabel = $LANG_MAPS_1['markers_submissions'];
    $plugin->getsubmissionssql = "SELECT mkid AS id,name,created,address FROM {$_TABLES['maps_submission']}";
    $plugin->addSubmissionHeading($LANG_MAPS_1['name']);
    $plugin->addSubmissionHeading($LANG_MAPS_1['created']);
    $plugin->addSubmissionHeading($LANG_MAPS_1['address']);
    return $plugin;
}

function plugin_moderationapprove_maps($id)
{
    global $_TABLES;
    $gid = DB_getItem($_TABLES['groups'], 'grp_id', "grp_name='Maps Admin'");
    DB_query("UPDATE {$_TABLES['maps_markers']} SET active=1,submission=0,group_id=" . (int) $gid . " WHERE mkid='" . MAPS_dbEscape($id) . "'");
    return DB_error() ? 'Error' : '';
}

function plugin_moderationdelete_maps($id)
{
    return '';
}

function hitMap($map)
{
    global $_TABLES;
    if ((int) $map === 0) {
        DB_query("UPDATE {$_TABLES['vars']} SET value=value+1 WHERE name='globalMapHits'");
    } else {
        DB_query("UPDATE {$_TABLES['maps_maps']} SET hits=hits+1 WHERE mid=" . (int) $map);
    }
}

function hitMarker($mkid)
{
    global $_TABLES;
    DB_query("UPDATE {$_TABLES['maps_markers']} SET hits=hits+1 WHERE mkid='" . MAPS_dbEscape($mkid) . "'");
}

function updateMap($map)
{
    global $_TABLES;

    $map = (int) $map;
    if ($map <= 0 || (int) DB_count($_TABLES['maps_maps'], 'mid', $map) === 0) {
        return false;
    }

    DB_query("UPDATE {$_TABLES['maps_maps']} SET modified='" . date('YmdHis') . "' WHERE mid=" . $map);
    if (!DB_error() && function_exists('PLG_itemSaved')) {
        PLG_itemSaved($map, 'maps');
    }

    return !DB_error();
}

function plugin_getuseroption_maps()
{
    global $_MAPS_CONF, $LANG_MAPS_1;
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'marker_submission', 0) === 1) {
        return array($LANG_MAPS_1['my_markers'], $_MAPS_CONF['site_url'] . '/markers.php', 0);
    }
    return array();
}

function MAPS_message($message, $title = '')
{
    global $LANG_MAPS_MESSAGE;
    if ($message === '') {
        return '';
    }
    $defaultTitle = isset($LANG_MAPS_MESSAGE['message']) ? $LANG_MAPS_MESSAGE['message'] : 'Message';
    return COM_startBlock($title === '' ? $defaultTitle : $title) . stripslashes($message) . COM_endBlock();
}


function MAPS_resolveMarkerOwnerUid($marker)
{
    global $_TABLES;

    $markerOwner = (int) MAPS_arrayGet($marker, 'owner_id', 0);
    if ($markerOwner > 1 && DB_count($_TABLES['users'], 'uid', $markerOwner) > 0) {
        return $markerOwner;
    }

    $mid = (int) MAPS_arrayGet($marker, 'mid', 0);
    if ($mid > 0) {
        $mapOwner = (int) DB_getItem($_TABLES['maps_maps'], 'owner_id', 'mid=' . $mid);
        if ($mapOwner > 1 && DB_count($_TABLES['users'], 'uid', $mapOwner) > 0) {
            return $mapOwner;
        }
    }

    return 1;
}

function MAPS_ViewMarkerInfos($mkid)
{
    global $LANG_MAPS_1, $_TABLES, $_CONF, $_MAPS_CONF, $_USER;
    if ($mkid === '') {
        return '';
    }
    $res = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='" . MAPS_dbEscape($mkid) . "' LIMIT 1");
    if (DB_numRows($res) < 1) {
        return '';
    }
    $marker = DB_fetchArray($res);
    if (!MAPS_checkMarkervalidity($marker)) {
        return '';
    }

    $template = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $template->set_file(array('presentation' => 'marker.thtml'));
    $template->set_var('name', htmlspecialchars(MAPS_markerDisplayName($marker['name']), ENT_QUOTES, 'UTF-8'));
    $template->set_var('description', $marker['description'] !== '' ? '<p>' . nl2br(htmlspecialchars(MAPS_decodeStoredText($marker['description']), ENT_QUOTES, 'UTF-8')) . '</p>' : '');
    foreach (array('street','code','city','state','country','tel','fax') as $field) {
        $value = MAPS_arrayGet($marker, $field, '');
        if (in_array($field, array('city', 'state', 'country'), true)) {
            $value = MAPS_normalizeMarkerPlace($value);
        } else {
            $value = MAPS_normalizeMarkerText($value);
        }
        if ((int) MAPS_arrayGet($_MAPS_CONF, $field, 1) === 0) {
            $template->set_var($field, '');
            continue;
        }
        $renderedValue = $value === ''
            ? MAPS_markerEmptyValue()
            : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $template->set_var($field, '<p><strong>' . $LANG_MAPS_1[$field . '_label'] . '</strong> ' . $renderedValue . '</p>');
    }
    $webValue = MAPS_normalizeMarkerText(MAPS_arrayGet($marker, 'web', ''));
    $template->set_var(
        'web',
        '<p><strong>' . htmlspecialchars($LANG_MAPS_1['web_label'], ENT_QUOTES, 'UTF-8') . '</strong> '
            . ($webValue === ''
                ? MAPS_markerEmptyValue()
                : '<span class="maps-marker-user-url">'
                    . htmlspecialchars($webValue, ENT_QUOTES, 'UTF-8') . '</span>')
            . '</p>'
    );
    $template->set_var('space', '');
    $template->set_var('ressources', MAPS_renderPublicCustomFields($marker, 'detail'));
    $ownerUid = MAPS_resolveMarkerOwnerUid($marker);
    $uid = isset($_USER['uid']) ? (int) $_USER['uid'] : 1;
    $template->set_var('edit', SEC_hasRights('maps.admin') || $uid === $ownerUid ? '<a class="maps-marker-secondary-action" href="' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '">' . htmlspecialchars($LANG_MAPS_1['edit_button'], ENT_QUOTES, 'UTF-8') . '</a>' : '');
    $mapname = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $marker['mid']);
    $template->set_var(
        'map',
        '<span class="maps-marker-meta-item"><strong>'
        . htmlspecialchars($LANG_MAPS_1['from_map'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . '<a href="' . htmlspecialchars(MAPS_contentUrl((int) $marker['mid']), ENT_QUOTES, 'UTF-8') . '">'
        . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></span>'
    );
    if ($ownerUid > 1) {
        $profileUrl = $_CONF['site_url'] . '/users.php?mode=profile&amp;uid=' . $ownerUid;
        $template->set_var(
            'owner',
            '<span class="maps-marker-meta-item"><strong>'
            . htmlspecialchars($LANG_MAPS_1['from_owner'], ENT_QUOTES, 'UTF-8') . '</strong> '
            . COM_createLink(COM_getDisplayName($ownerUid), $profileUrl) . '</span>'
        );
        $template->set_var('report', '<a class="maps-marker-secondary-action" href="' . $_CONF['site_url'] . '/profiles.php?uid=' . $ownerUid . '&amp;subject=' . rawurlencode($LANG_MAPS_1['report_subject'] . $marker['mkid']) . '">' . htmlspecialchars($LANG_MAPS_1['report'], ENT_QUOTES, 'UTF-8') . '</a>');
    } else {
        $template->set_var('owner', '');
        $template->set_var('report', '');
    }
    $update = COM_getUserDateTimeFormat($marker['modified']);
    $template->set_var(
        'update',
        '<span class="maps-marker-meta-item"><strong>'
        . htmlspecialchars($LANG_MAPS_1['last_modification'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . htmlspecialchars($update[0], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<span class="maps-marker-meta-item"><strong>'
        . htmlspecialchars($LANG_MAPS_1['hits'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . (int) $marker['hits'] . '</span>'
    );
    $template->set_var('directions_table_result', '<a id="directions_' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '_table"></a><div id="directions' . htmlspecialchars($marker['mkid'], ENT_QUOTES, 'UTF-8') . '"></div>');
    hitMarker($mkid);
    return $template->parse('output', 'presentation');
}

/** PHP 7/8-safe replacement for the old preg_replace /e implementation. */
function MAPS_convertLinkToUrl($url, $length = 35)
{
    global $_CONF;
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('~^https?://~i', $url)) {
        $url = 'http://' . $url;
    }
    $parts = parse_url($url);
    $name = isset($parts['host']) ? $parts['host'] : $url;
    $display = strlen($name) > (int) $length ? substr($name, 0, (int) $length) . '…' : $name;
    $target = strpos($url, $_CONF['site_url']) === 0 ? '' : ' target="_blank" rel="noopener noreferrer"';
    return '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '"' . $target . '>' . htmlspecialchars($display, ENT_QUOTES, 'UTF-8') . '</a>';
}

function MAPS_ListMarkers($mid = '')
{
    global $_CONF, $_MAPS_CONF, $_TABLES, $LANG_ADMIN, $LANG_MAPS_1;
    if ($mid === '') {
        return '';
    }
    require_once $_CONF['path_system'] . 'lib-admin.php';
    $mid = (int) $mid;
    $header = array(
        array('text' => $LANG_MAPS_1['name'], 'field' => 'name', 'sort' => true),
        array('text' => $LANG_MAPS_1['code'], 'field' => 'code', 'sort' => true),
        array('text' => $LANG_MAPS_1['city'], 'field' => 'city', 'sort' => true)
    );
    $sql = "SELECT * FROM {$_TABLES['maps_markers']} WHERE active=1 AND hidden=0" . ($mid > 0 ? ' AND mid=' . $mid : '');
    $query = array('table' => 'maps_markers', 'sql' => $sql, 'query_fields' => array('name','code','city'), 'default_filter' => COM_getPermSQL('AND', 0, 2));
    $text = array('has_extras' => true, 'form_url' => $_MAPS_CONF['site_url'] . '/index.php?mode=markers&mid=' . $mid);
    $sort = array('field' => 'code', 'direction' => 'asc');
    return ADMIN_list('markers', 'MAPS_getListField_markersList', $header, $text, $query, $sort);
}

function MAPS_getListField_markersList($fieldname, $fieldvalue, $a, $icon_arr)
{
    global $_MAPS_CONF;
    if ($fieldname === 'name') {
        $name = MAPS_markerDisplayName($a['name']);
        if ($name === '') {
            return MAPS_markerEmptyValue();
        }
        return COM_createLink(htmlspecialchars($name, ENT_QUOTES, 'UTF-8'), $_MAPS_CONF['site_url'] . '/markers.php?mode=show&mkid=' . rawurlencode($a['mkid']) . '&mid=' . (int) $a['mid']);
    }
    if ($fieldname === 'city') {
        $value = MAPS_normalizeMarkerPlace($fieldvalue);
        return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    if ($fieldname === 'code') {
        $value = MAPS_normalizeMarkerText($fieldvalue);
        return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    $value = MAPS_normalizeMarkerText($fieldvalue);
    return $value === '' ? MAPS_markerEmptyValue() : htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function MAPS_setGeoLocation()
{
    $userAttributesTable = MAPS_userAttributesTable();
    if ($userAttributesTable === '') {
        return;
    }
    $locations = DB_query("SELECT location FROM {$userAttributesTable} WHERE location<>''");
    while ($geo = DB_fetchArray($locations)) {
        $gid = '';
        $lat = 0;
        $lng = 0;
        MAPS_getDBCoords($gid, $geo['location'], $lat, $lng);
    }
}

function MAPS_displayEventsMap($output = true, $event_id = '')
{
    global $_TABLES, $_CONF, $_MAPS_CONF, $LANG_MAPS_1;

    if (!isset($_TABLES['events'])) {
        return '';
    }
    $first = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE active=1 ORDER BY mid ASC LIMIT 1");
    if (DB_numRows($first) < 1) {
        return '';
    }
    $a = DB_fetchArray($first);
    $lat = MAPS_latitude($a['lat'], 0);
    $lng = MAPS_longitude($a['lng'], 0);
    $zoom = (int) MAPS_arrayGet($_MAPS_CONF, 'global_zoom', 2);
    $height = MAPS_arrayGet($_MAPS_CONF, 'global_height', '600px');

    if ($event_id === '') {
        $events = DB_query("SELECT * FROM {$_TABLES['events']} WHERE dateend>=CURDATE()");
    } else {
        $events = DB_query("SELECT * FROM {$_TABLES['events']} WHERE eid='" . MAPS_dbEscape($event_id) . "' LIMIT 1");
        $zoom = (int) MAPS_arrayGet($_MAPS_CONF, 'events_map_zoom', 8);
        $height = MAPS_arrayGet($_MAPS_CONF, 'events_map_height', '300px');
    }

    $js = 'var markers = [];' . LB;
    while ($event = DB_fetchArray($events)) {
        if (SEC_hasAccess($event['owner_id'], $event['group_id'], $event['perm_owner'], $event['perm_group'], $event['perm_members'], $event['perm_anon']) < 2 || trim($event['location']) === '') {
            continue;
        }
        $gid = '';
        $elat = 0;
        $elng = 0;
        MAPS_getDBCoords($gid, $event['location'], $elat, $elng);
        if ($event_id !== '') {
            $lat = $elat;
            $lng = $elng;
        }
        $marker = array(
            'mkid' => $event['eid'], 'hidden' => 0, 'active' => 1, 'name' => $event['title'],
            'description' => $event_id === '' ? MAPS_arrayGet($event, 'description', '') : '',
            'payed' => 0, 'mk_default' => 1, 'validity' => 0,
            'perm_owner' => 2, 'perm_group' => 2, 'perm_members' => 2, 'perm_anon' => 2,
            'owner_id' => $event['owner_id'], 'group_id' => $event['group_id'], 'lat' => $elat, 'lng' => $elng,
            'mid' => 0, 'mk_icon' => 0, 'mk_pcolor' => MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666'), 'mk_label' => MAPS_arrayGet($_MAPS_CONF, 'map_label', ''), 'mk_label_color' => MAPS_arrayGet($_MAPS_CONF, 'map_label_color', 0)
        );
        $js .= MAPS_renderMarker($marker, MAPS_arrayGet($_MAPS_CONF, 'map_primary_color', '#666666'), MAPS_arrayGet($_MAPS_CONF, 'map_stroke_color', '#333333'), MAPS_arrayGet($_MAPS_CONF, 'map_label', ''), (int) MAPS_arrayGet($_MAPS_CONF, 'map_label_color', 0) ? '#FFFFFF' : '#000000', $LANG_MAPS_1['events_map'], '', '', 'events', $event_id === '', array('mmk_default' => 1, 'mmk_icon' => 0));
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'use_cluster', 0)) {
        $js .= MAPS_clusterInitJs('mapevents');
    }

    $t = COM_newTemplate($_CONF['path'] . 'plugins/maps/templates');
    $t->set_file('page', $output ? 'map_autotag.thtml' : 'map_only_script.thtml');
    MAPS_fillMapCommon($t, 'events', $lat, $lng, $zoom, MAPS_arrayGet($_MAPS_CONF, 'global_type', 'ROADMAP'), MAPS_arrayGet($_MAPS_CONF, 'global_width', '100%'), $height);
    $t->set_var('name', $LANG_MAPS_1['events_map']);
    $t->set_var('description', '<p>' . $LANG_MAPS_1['info_events_map'] . '</p>');
    $t->set_var('header', '');
    $t->set_var('footer', '');
    $t->set_var('markers', $js);
    $t->parse('result', 'page');
    return $t->finish($t->get_var('result'));
}
