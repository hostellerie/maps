from pathlib import Path


def replace_once(path, old, new):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('Pattern not found in %s: %s' % (path, old[:120]))
    p.write_text(text.replace(old, new, 1))

# Public map template: the map name is the page's primary heading.
replace_once('templates/map.thtml', '<h2>{name}</h2>', '<h1>{name}</h1>')

# Canonical marker links in legacy marker UI and search output.
p = Path('functions.inc')
text = p.read_text()
text = text.replace("'/maps/markers.php?mode=show&amp;mkid=', {$markerAlias}.mkid, '&amp;mid=', {$markerAlias}.mid)", "'/maps/index.php?mode=marker&amp;mkid=', {$markerAlias}.mkid)")
text = text.replace("'/markers.php?mode=show&mkid=' . $A['mkid'] . '&mid=' . $A['mid']", "'/index.php?mode=marker&mkid=' . $A['mkid']")
text = text.replace("'/markers.php?mode=show&amp;mkid=' . $A['mkid'] . '&amp;mid=' . $A['mid']", "'/index.php?mode=marker&amp;mkid=' . $A['mkid']")
p.write_text(text)

# Marker URL helper shared by sitemap/search/services/SEO.
p = Path('interoperability.php')
text = p.read_text()
needle = "function MAPS_contentUrl($mid)\n{\n    global $_MAPS_CONF;\n\n    $mid = (int) $mid;\n    if ($mid <= 0) {\n        return '';\n    }\n\n    return rtrim($_MAPS_CONF['site_url'], '/') . '/index.php?mode=map&mid=' . $mid;\n}\n"
addition = needle + "\n/**\n * Return the canonical public URL for a marker.\n *\n * @param string|int $mkid\n * @return string\n */\nfunction MAPS_markerContentUrl($mkid)\n{\n    global $_MAPS_CONF;\n\n    $mkid = trim((string) $mkid);\n    if ($mkid === '') {\n        return '';\n    }\n\n    return rtrim($_MAPS_CONF['site_url'], '/') . '/index.php?mode=marker&mkid='\n        . rawurlencode($mkid);\n}\n"
if 'function MAPS_markerContentUrl' not in text:
    if needle not in text:
        raise SystemExit('MAPS_contentUrl block not found')
    text = text.replace(needle, addition, 1)
p.write_text(text)

# Sitemap: include canonical marker pages as first-class public URLs.
p = Path('distribution.php')
text = p.read_text()
needle = "    while ($row = DB_fetchArray($result)) {\n        if (!is_array($row) || empty($row['mid'])) {\n            continue;\n        }\n        $modified = isset($row['modified']) ? strtotime($row['modified']) : false;\n        $items[] = array(\n            'url' => MAPS_contentUrl((int) $row['mid']),\n            'date-modified' => ($modified === false ? 0 : $modified)\n        );\n    }\n\n    return $items;\n}"
replacement = "    while ($row = DB_fetchArray($result)) {\n        if (!is_array($row) || empty($row['mid'])) {\n            continue;\n        }\n        $modified = isset($row['modified']) ? strtotime($row['modified']) : false;\n        $items[] = array(\n            'url' => MAPS_contentUrl((int) $row['mid']),\n            'date-modified' => ($modified === false ? 0 : $modified)\n        );\n    }\n\n    // Marker pages are indexable content too. Use the same canonical URL as\n    // public links and search results, and require both marker and parent-map\n    // visibility/permissions so the sitemap never advertises an inaccessible URL.\n    $markerSql = \"SELECT mk.mkid,mk.modified FROM {$_TABLES['maps_markers']} mk \"\n        . \"INNER JOIN {$_TABLES['maps_maps']} m ON m.mid=mk.mid \"\n        . \"WHERE mk.active=1 AND mk.hidden=0 AND m.active=1 AND m.hidden=0\"\n        . COM_getPermSQL('AND', $uid, 2, 'mk')\n        . COM_getPermSQL('AND', $uid, 2, 'm')\n        . \" ORDER BY mk.modified DESC, mk.mkid DESC\";\n    $markerResult = DB_query($markerSql);\n    while ($marker = DB_fetchArray($markerResult)) {\n        if (!is_array($marker) || !isset($marker['mkid']) || trim((string) $marker['mkid']) === '') {\n            continue;\n        }\n        $modified = isset($marker['modified']) ? strtotime($marker['modified']) : false;\n        $items[] = array(\n            'url' => MAPS_markerContentUrl($marker['mkid']),\n            'date-modified' => ($modified === false ? 0 : $modified)\n        );\n        if ($limit > 0 && count($items) >= $limit) {\n            break;\n        }\n    }\n\n    if ($limit > 0 && count($items) > $limit) {\n        $items = array_slice($items, 0, $limit);\n    }\n\n    return $items;\n}"
if needle not in text:
    raise SystemExit('Sitemap collector block not found')
text = text.replace(needle, replacement, 1)
p.write_text(text)

# Legacy marker detail URLs permanently converge on the canonical index.php route.
p = Path('public_html/markers.php')
text = p.read_text()
needle = "MAPS_filterVars($vars, $_REQUEST);\n"
insert = "MAPS_filterVars($vars, $_REQUEST);\n\n// Maps 1.5.9 canonical marker URL. Preserve old inbound links but consolidate\n// all public marker detail signals on index.php?mode=marker&mkid=... .\n$legacyMode = isset($_REQUEST['mode']) ? (string) $_REQUEST['mode'] : '';\n$legacyMkid = isset($_REQUEST['mkid']) ? trim((string) $_REQUEST['mkid']) : '';\nif ($legacyMode === 'show' && $legacyMkid !== '') {\n    header('Location: ' . MAPS_markerContentUrl($legacyMkid), true, 301);\n    exit;\n}\n\n// Editing, submission and private marker-management pages are useful to users\n// but must not enter search indexes.\nheader('X-Robots-Tag: noindex, follow');\n"
if insert not in text:
    if needle not in text:
        raise SystemExit('markers.php filter point not found')
    text = text.replace(needle, insert, 1)
p.write_text(text)

# Replace the modern public controller tail with permission-aware SEO rendering.
p = Path('public_html/index.php')
text = p.read_text()
start = text.find("$pageTitle = $LANG_MAPS_1['maps_label'];")
if start < 0:
    raise SystemExit('index.php SEO tail start not found')
new_tail = r'''/**
 * Build a concise plain-text search/social description.
 *
 * @param string $text
 * @param string $fallback
 * @return string
 */
function MAPS_publicDescription($text, $fallback = '')
{
    $text = trim((string) $text);
    if ($text !== '') {
        $text = PLG_replaceTags($text);
    }
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    if ($text === '') {
        $text = trim((string) $fallback);
    }
    if (function_exists('COM_truncate')) {
        return COM_truncate($text, 160, '...');
    }
    return (strlen($text) > 160) ? substr($text, 0, 157) . '...' : $text;
}

/**
 * Build canonical, description, robots, Open Graph, Twitter and JSON-LD tags.
 *
 * @param string $title
 * @param string $description
 * @param string $canonical
 * @param string $robots
 * @param array  $jsonLd
 * @return string
 */
function MAPS_publicSeoHeader($title, $description, $canonical, $robots = '', $jsonLd = array())
{
    $safeTitle = htmlspecialchars((string) $title, ENT_QUOTES, 'UTF-8');
    $safeDescription = htmlspecialchars((string) $description, ENT_QUOTES, 'UTF-8');
    $safeCanonical = htmlspecialchars((string) $canonical, ENT_QUOTES, 'UTF-8');
    $header = '<link rel="canonical" href="' . $safeCanonical . '">' . LB;
    if ($description !== '') {
        $header .= '<meta name="description" content="' . $safeDescription . '">' . LB;
    }
    if ($robots !== '') {
        $header .= '<meta name="robots" content="' . htmlspecialchars($robots, ENT_QUOTES, 'UTF-8') . '">' . LB;
    }
    $header .= '<meta property="og:title" content="' . $safeTitle . '">' . LB
        . '<meta property="og:url" content="' . $safeCanonical . '">' . LB
        . '<meta property="og:type" content="website">' . LB;
    if ($description !== '') {
        $header .= '<meta property="og:description" content="' . $safeDescription . '">' . LB;
    }
    $header .= '<meta name="twitter:card" content="summary">' . LB
        . '<meta name="twitter:title" content="' . $safeTitle . '">' . LB;
    if ($description !== '') {
        $header .= '<meta name="twitter:description" content="' . $safeDescription . '">' . LB;
    }
    if (!empty($jsonLd)) {
        $json = json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json !== false) {
            $header .= '<script type="application/ld+json">' . $json . '</script>' . LB;
        }
    }
    return $header;
}

$pageTitle = $LANG_MAPS_1['maps_label'];
$pageDescription = MAPS_publicDescription(
    MAPS_arrayGet($_MAPS_CONF, 'map_main_header', ''),
    $LANG_MAPS_1['maps_label']
);
$canonical = rtrim($_MAPS_CONF['site_url'], '/') . '/';
$robots = '';
$jsonLd = array();
$mapRow = array();
$markerRow = array();
$markerMapRow = array();

if ($mode === 'map' && $mid > 0) {
    $result = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . $mid . " LIMIT 1");
    $mapRow = DB_fetchArray($result);
    if (!is_array($mapRow) || (int) MAPS_arrayGet($mapRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($mapRow, 'hidden', 0) === 1 || !SEC_hasAccess2($mapRow)) {
        COM_handle404();
        exit;
    }
    $pageTitle = MAPS_decodeStoredText(MAPS_arrayGet($mapRow, 'name', ''));
    $pageDescription = MAPS_publicDescription(
        MAPS_arrayGet($mapRow, 'description', ''),
        $pageTitle
    );
    $canonical = MAPS_contentUrl($mid);
} elseif ($mode === 'marker' && $mkid !== '') {
    $safeMkid = MAPS_dbEscape($mkid);
    $result = DB_query("SELECT * FROM {$_TABLES['maps_markers']} WHERE mkid='" . $safeMkid . "' LIMIT 1");
    $markerRow = DB_fetchArray($result);
    if (!is_array($markerRow) || (int) MAPS_arrayGet($markerRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($markerRow, 'hidden', 0) === 1 || !SEC_hasAccess2($markerRow)) {
        COM_handle404();
        exit;
    }
    $markerMid = (int) MAPS_arrayGet($markerRow, 'mid', 0);
    $mapResult = DB_query("SELECT * FROM {$_TABLES['maps_maps']} WHERE mid=" . $markerMid . " LIMIT 1");
    $markerMapRow = DB_fetchArray($mapResult);
    if (!is_array($markerMapRow) || (int) MAPS_arrayGet($markerMapRow, 'active', 0) !== 1
        || (int) MAPS_arrayGet($markerMapRow, 'hidden', 0) === 1 || !SEC_hasAccess2($markerMapRow)) {
        COM_handle404();
        exit;
    }

    $pageTitle = MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'name', ''));
    $addressFallback = trim(implode(', ', array_filter(array(
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'address', '')),
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'city', '')),
        MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'country', ''))
    ))));
    $pageDescription = MAPS_publicDescription(
        MAPS_arrayGet($markerRow, 'description', ''),
        $addressFallback !== '' ? $pageTitle . ' - ' . $addressFallback : $pageTitle
    );
    $canonical = MAPS_markerContentUrl($mkid);

    $place = array(
        '@context' => 'https://schema.org',
        '@type' => 'Place',
        'name' => $pageTitle,
        'url' => $canonical
    );
    if ($pageDescription !== '') {
        $place['description'] = $pageDescription;
    }
    $postal = array('@type' => 'PostalAddress');
    $postalFields = array(
        'streetAddress' => 'street',
        'postalCode' => 'code',
        'addressLocality' => 'city',
        'addressRegion' => 'state',
        'addressCountry' => 'country'
    );
    foreach ($postalFields as $schemaField => $dbField) {
        $value = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, $dbField, '')));
        if ($value !== '') {
            $postal[$schemaField] = $value;
        }
    }
    if (count($postal) > 1) {
        $place['address'] = $postal;
    } elseif ($addressFallback !== '') {
        $place['address'] = $addressFallback;
    }
    if (MAPS_isValidCoordinatePair(MAPS_arrayGet($markerRow, 'lat', ''), MAPS_arrayGet($markerRow, 'lng', ''))) {
        $place['geo'] = array(
            '@type' => 'GeoCoordinates',
            'latitude' => (float) MAPS_latitude($markerRow['lat']),
            'longitude' => (float) MAPS_longitude($markerRow['lng'])
        );
    }
    $telephone = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'tel', '')));
    if ($telephone !== '') {
        $place['telephone'] = $telephone;
    }
    $website = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'web', '')));
    if (filter_var($website, FILTER_VALIDATE_URL)) {
        $place['sameAs'] = $website;
    }
    $jsonLd = $place;
} elseif ($mode === 'markers') {
    // Marker list views are navigation helpers rather than canonical landing pages.
    $robots = 'noindex,follow';
    $canonical = ($mid > 0) ? MAPS_contentUrl($mid) : rtrim($_MAPS_CONF['site_url'], '/') . '/';
}

$content = MAPS_user_menu();
if (isset($_REQUEST['msg']) && (int) $_REQUEST['msg'] > 0) {
    $content .= COM_showMessage((int) $_REQUEST['msg'], 'maps');
}

switch ($mode) {
    case 'map':
        if ($mid > 0) {
            $content .= MAPS_getMap($mid);
            $content .= MAPS_ListMarkers($mid);
            $content .= MAPS_renderMapStatistics($mid, true);
        } else {
            $content .= MAPS_getGlobalMap();
        }
        break;

    case 'markers':
        $content .= MAPS_ListMarkers($mid);
        break;

    case 'marker':
        if ($mkid !== '') {
            $content .= '<article class="maps-marker-detail">';
            $content .= '<h1>' . htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') . '</h1>';
            $content .= MAPS_ViewMarkerInfos($mkid);
            if (!empty($markerMapRow['mid'])) {
                $mapName = MAPS_decodeStoredText(MAPS_arrayGet($markerMapRow, 'name', ''));
                $content .= '<p class="maps-marker-map-link"><a href="'
                    . htmlspecialchars(MAPS_contentUrl((int) $markerMapRow['mid']), ENT_QUOTES, 'UTF-8') . '">'
                    . htmlspecialchars($mapName, ENT_QUOTES, 'UTF-8') . '</a></p>';
            }
            $content .= '</article>';
        }
        break;

    default:
        $content .= '<h1>' . htmlspecialchars($LANG_MAPS_1['maps_label'], ENT_QUOTES, 'UTF-8') . '</h1>';
        $content .= MAPS_displayFrontPage();
        break;
}

$headercode = MAPS_publicSeoHeader($pageTitle, $pageDescription, $canonical, $robots, $jsonLd);
COM_output(COM_createHTMLDocument(
    $content,
    array(
        'pagetitle' => stripslashes($pageTitle),
        'headercode' => $headercode
    )
));
'''
text = text[:start] + new_tail
p.write_text(text)

# Release notes.
p = Path('RELEASE-NOTES-1.5.9.md')
if p.exists():
    text = p.read_text()
    section = '''\n## Public SEO\n\n- Canonical marker URL is now `/maps/index.php?mode=marker&mkid=...`.\n- Legacy `markers.php?mode=show...` marker detail URLs permanently redirect to the canonical route.\n- Public map and marker pages emit canonical, meta description, Open Graph and Twitter metadata.\n- Marker pages emit Schema.org `Place`, `PostalAddress` and `GeoCoordinates` JSON-LD when data is available.\n- Invalid, hidden or inaccessible maps/markers return a real 404.\n- Map and marker pages now expose a clear H1 and marker pages link back to their parent map.\n- Private marker management pages are `noindex,follow`.\n- XML Sitemap collection includes canonical public marker pages in addition to maps.\n- Internal marker search/list links use the canonical marker URL.\n'''
    if '## Public SEO' not in text:
        text = text.rstrip() + '\n' + section
        p.write_text(text)

# Remove accidental duplicate table declaration while touching 1.5.9 runtime.
p = Path('maps.php')
text = p.read_text()
dup = "$_TABLES['maps_service_operations'] = $_DB_table_prefix . 'maps_service_operations';\n$_TABLES['maps_service_operations'] = $_DB_table_prefix . 'maps_service_operations';"
text = text.replace(dup, "$_TABLES['maps_service_operations'] = $_DB_table_prefix . 'maps_service_operations';")
p.write_text(text)
