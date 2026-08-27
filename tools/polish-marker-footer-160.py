from pathlib import Path


def replace_once(path, old, new, label):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('%s anchor not found in %s' % (label, path))
    p.write_text(text.replace(old, new, 1))

# Public custom marker fields: expand autotags for their text value, then
# strip every HTML tag and escape the result. User-supplied content must never
# create a clickable link or executable markup on public marker pages/popups.
p = Path('functions.inc')
text = p.read_text()
old = """        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $renderedValue = PLG_replaceTags(stripslashes($value));
        if ($layout === 'compact') {
"""
new = """        $safeLabel = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
        $expandedValue = PLG_replaceTags(stripslashes($value));
        $plainValue = trim(strip_tags((string) $expandedValue));
        $renderedValue = nl2br(htmlspecialchars($plainValue, ENT_QUOTES, 'UTF-8'));
        if ($layout === 'compact') {
"""
if old in text:
    text = text.replace(old, new, 1)

# Marker info-window Read more must always target the canonical Maps marker
# page, never a legacy/user-provided URL column.
old = """            $readMore = MAPS_arrayGet($marker, 'url', '') !== '' ? $_CONF['site_url'] . '/' . ltrim($marker['url'], '/') : MAPS_markerContentUrl($marker['mkid']);
            $template->set_var('read_more', '<a href=\"' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '\">' . $LANG_MAPS_1['read_more'] . '</a>');
"""
new = """            $readMore = MAPS_markerContentUrl($marker['mkid']);
            $template->set_var('read_more', '<a href=\"' . htmlspecialchars($readMore, ENT_QUOTES, 'UTF-8') . '\">' . $LANG_MAPS_1['read_more'] . '</a>');
"""
if old in text:
    text = text.replace(old, new, 1)

# Public marker detail: user-entered website is displayed as text only.
old = """    $template->set_var('web', MAPS_arrayGet($marker, 'web', '') !== '' ? '<p><strong>' . $LANG_MAPS_1['web_label'] . '</strong> ' . MAPS_convertLinkToUrl($marker['web']) . '</p>' : '');
"""
new = """    $webValue = trim((string) MAPS_arrayGet($marker, 'web', ''));
    $template->set_var(
        'web',
        $webValue !== ''
            ? '<p><strong>' . htmlspecialchars($LANG_MAPS_1['web_label'], ENT_QUOTES, 'UTF-8') . '</strong> '
                . '<span class=\"maps-marker-user-url\">'
                . htmlspecialchars(MAPS_decodeStoredText($webValue), ENT_QUOTES, 'UTF-8') . '</span></p>'
            : ''
    );
"""
if old not in text:
    raise SystemExit('marker web anchor not found')
text = text.replace(old, new, 1)

# Rebuild marker metadata into labelled, balanced items. Internal Geeklog/Maps
# links remain clickable; no user-controlled external URL is linked.
old = """    $mapname = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $marker['mid']);
    $template->set_var('map', $LANG_MAPS_1['from_map'] . ' <a href=\"' . $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $marker['mid'] . '\">' . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a>');
    if ($ownerUid > 1) {
        $profileUrl = $_CONF['site_url'] . '/users.php?mode=profile&amp;uid=' . $ownerUid;
        $template->set_var('owner', $LANG_MAPS_1['from_owner'] . ' ' . COM_createLink(COM_getDisplayName($ownerUid), $profileUrl));
        $template->set_var('report', '<a href=\"' . $_CONF['site_url'] . '/profiles.php?uid=' . $ownerUid . '&amp;subject=' . rawurlencode($LANG_MAPS_1['report_subject'] . $marker['mkid']) . '\">' . $LANG_MAPS_1['report'] . '</a>');
    } else {
        $template->set_var('owner', '');
        $template->set_var('report', '');
    }
    $update = COM_getUserDateTimeFormat($marker['modified']);
    $template->set_var('update', $LANG_MAPS_1['last_modification'] . ' ' . $update[0] . ' · ' . (int) $marker['hits'] . ' ' . $LANG_MAPS_1['hits']);
"""
new = """    $mapname = DB_getItem($_TABLES['maps_maps'], 'name', 'mid=' . (int) $marker['mid']);
    $template->set_var(
        'map',
        '<span class=\"maps-marker-meta-item\"><strong>'
        . htmlspecialchars($LANG_MAPS_1['from_map'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . '<a href=\"' . htmlspecialchars(MAPS_contentUrl((int) $marker['mid']), ENT_QUOTES, 'UTF-8') . '\">'
        . htmlspecialchars(stripslashes($mapname), ENT_QUOTES, 'UTF-8') . '</a></span>'
    );
    if ($ownerUid > 1) {
        $profileUrl = $_CONF['site_url'] . '/users.php?mode=profile&amp;uid=' . $ownerUid;
        $template->set_var(
            'owner',
            '<span class=\"maps-marker-meta-item\"><strong>'
            . htmlspecialchars($LANG_MAPS_1['from_owner'], ENT_QUOTES, 'UTF-8') . '</strong> '
            . COM_createLink(COM_getDisplayName($ownerUid), $profileUrl) . '</span>'
        );
        $template->set_var('report', '<a class=\"maps-marker-secondary-action\" href=\"' . $_CONF['site_url'] . '/profiles.php?uid=' . $ownerUid . '&amp;subject=' . rawurlencode($LANG_MAPS_1['report_subject'] . $marker['mkid']) . '\">' . htmlspecialchars($LANG_MAPS_1['report'], ENT_QUOTES, 'UTF-8') . '</a>');
    } else {
        $template->set_var('owner', '');
        $template->set_var('report', '');
    }
    $update = COM_getUserDateTimeFormat($marker['modified']);
    $template->set_var(
        'update',
        '<span class=\"maps-marker-meta-item\"><strong>'
        . htmlspecialchars($LANG_MAPS_1['last_modification'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . htmlspecialchars($update[0], ENT_QUOTES, 'UTF-8') . '</span>'
        . '<span class=\"maps-marker-meta-item\"><strong>'
        . htmlspecialchars($LANG_MAPS_1['hits'], ENT_QUOTES, 'UTF-8') . '</strong> '
        . (int) $marker['hits'] . '</span>'
    );
"""
if old not in text:
    raise SystemExit('marker metadata anchor not found')
text = text.replace(old, new, 1)

# Add a class to the trusted internal Edit action.
old = """    $template->set_var('edit', SEC_hasRights('maps.admin') || $uid === $ownerUid ? '<a href=\"' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '\">' . $LANG_MAPS_1['edit_button'] . '</a>' : '');
"""
new = """    $template->set_var('edit', SEC_hasRights('maps.admin') || $uid === $ownerUid ? '<a class=\"maps-marker-secondary-action\" href=\"' . $_MAPS_CONF['site_url'] . '/markers.php?mode=edit&amp;mkid=' . rawurlencode($marker['mkid']) . '\">' . htmlspecialchars($LANG_MAPS_1['edit_button'], ENT_QUOTES, 'UTF-8') . '</a>' : '');
"""
if old not in text:
    raise SystemExit('marker edit anchor not found')
text = text.replace(old, new, 1)
p.write_text(text)

# Marker template: one coherent footer row with facts and actions.
Path('templates/marker.thtml').write_text("""<section class=\"maps-marker-card\">\n    <div class=\"maps-marker-grid\">\n        <div class=\"maps-marker-panel maps-marker-location\">\n            <div class=\"maps-marker-address\">\n                {street}\n                {code}\n                {city}\n                {state}\n                {country}\n            </div>\n            <div class=\"maps-marker-contact\">\n                {tel}\n                {fax}\n                {web}\n            </div>\n        </div>\n        <div class=\"maps-marker-panel maps-marker-content\">\n            <div class=\"maps-marker-description\">{description}</div>\n            <div class=\"maps-marker-resources\">{ressources}</div>\n        </div>\n    </div>\n    <div class=\"maps-marker-directions-result\">{directions_table_result}</div>\n    <footer class=\"maps-marker-meta\">\n        <div class=\"maps-marker-meta-facts\">{owner}{map}{update}</div>\n        <div class=\"maps-marker-meta-actions\">{report}{edit}</div>\n    </footer>\n</section>\n""")

# Canonical marker route already contains the map link inside marker metadata;
# remove the obsolete duplicate link beneath the card.
p = Path('public_html/index.php')
text = p.read_text()
old = """            $content .= MAPS_ViewMarkerInfos($mkid);
            if (!empty($markerMapRow['mid'])) {
                $mapName = MAPS_decodeStoredText(MAPS_arrayGet($markerMapRow, 'name', ''));
                $content .= '<p class=\"maps-marker-map-link\"><a href=\"'
                    . htmlspecialchars(MAPS_contentUrl((int) $markerMapRow['mid']), ENT_QUOTES, 'UTF-8') . '\">'
                    . htmlspecialchars($mapName, ENT_QUOTES, 'UTF-8') . '</a></p>';
            }
            $content .= '</article>';
"""
new = """            $content .= MAPS_ViewMarkerInfos($mkid);
            $content .= '</article>';
"""
if old not in text:
    raise SystemExit('duplicate map link anchor not found')
text = text.replace(old, new, 1)

# Do not advertise a user-entered external website as schema.org sameAs.
old = """    $website = trim(MAPS_decodeStoredText(MAPS_arrayGet($markerRow, 'web', '')));
    if (filter_var($website, FILTER_VALIDATE_URL)) {
        $place['sameAs'] = $website;
    }
    $jsonLd = $place;
"""
new = """    $jsonLd = $place;
"""
if old in text:
    text = text.replace(old, new, 1)
p.write_text(text)

# Improve public marker footer labels in both bundled languages.
for path, replacements in {
    'language/english.php': {
        "'from_owner'            => 'from'": "'from_owner'            => 'Added by:'",
        "'from_map'              => 'On map'": "'from_map'              => 'Map:'"
    },
    'language/french_france_utf-8.php': {
        "'from_owner'            => 'de'": "'from_owner'            => 'Ajouté par :',",
        "'from_map'              => 'Sur la carte'": "'from_map'              => 'Carte :',"
    }
}.items():
    p = Path(path)
    text = p.read_text()
    for old, new in replacements.items():
        if old in text:
            # Account for whether the original match already includes comma.
            original_line_start = text.find(old)
            line_end = text.find('\n', original_line_start)
            current = text[original_line_start:line_end]
            replacement = new
            if current.rstrip().endswith(',') and not replacement.rstrip().endswith(','):
                replacement += ','
            text = text[:original_line_start] + current.replace(old, replacement, 1) + text[line_end:]
    p.write_text(text)

# Public CSS: simplify footer into a compact metadata band and make untrusted
# URLs visually readable without link affordance.
p = Path('public_html/maps.css')
css = p.read_text()
old = """.maps-marker-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem 1rem;
    align-items: center;
    margin-top: 1.25rem;
    padding-top: .8rem;
    border-top: 1px solid rgba(127,127,127,.25);
    font-size: .9em;
}
.maps-marker-meta-primary,
.maps-marker-meta-secondary,
.maps-marker-meta-actions { display: flex; flex-wrap: wrap; gap: .35rem; align-items: center; }
.maps-marker-meta-actions { margin-left: auto; }
.maps-marker-meta-actions a + a::before { content: "·"; margin-right: .35rem; text-decoration: none; }
@media (max-width: 640px) {
    .maps-marker-details { grid-template-columns: 1fr; }
    .maps-marker-meta { display: block; }
    .maps-marker-meta > div { margin-top: .35rem; }
    .maps-marker-meta-actions { margin-left: 0; }
}
"""
new = """.maps-marker-meta {
    display: flex;
    justify-content: space-between;
    gap: .8rem 1.5rem;
    align-items: flex-end;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(127,127,127,.22);
    font-size: .9em;
}
.maps-marker-meta-facts {
    display: flex;
    flex-wrap: wrap;
    gap: .55rem 1.25rem;
    min-width: 0;
}
.maps-marker-meta-item {
    display: inline-flex;
    flex-wrap: wrap;
    gap: .3rem;
    align-items: baseline;
    color: inherit;
}
.maps-marker-meta-item strong { font-weight: 600; }
.maps-marker-meta-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    flex: 0 0 auto;
}
.maps-marker-secondary-action {
    display: inline-block;
    padding: .35rem .6rem;
    border: 1px solid rgba(127,127,127,.28);
    border-radius: 6px;
    text-decoration: none;
}
.maps-marker-secondary-action:hover,
.maps-marker-secondary-action:focus { background: rgba(127,127,127,.07); }
.maps-marker-user-url {
    overflow-wrap: anywhere;
    word-break: break-word;
}
@media (max-width: 640px) {
    .maps-marker-details { grid-template-columns: 1fr; }
    .maps-marker-meta { display: block; }
    .maps-marker-meta-facts { display: grid; gap: .45rem; }
    .maps-marker-meta-actions { margin-top: .8rem; }
}
"""
if old not in css:
    raise SystemExit('marker meta CSS anchor not found')
css = css.replace(old, new, 1)
p.write_text(css)

# Release note trace.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
entry = """\n## Marker footer and user-link hardening\n\n- Reworked marker metadata into consistent labelled items and actions.\n- Removed the duplicate parent-map link beneath canonical marker pages.\n- Replaced the legacy lowercase `from` label with localized `Added by` / `Ajouté par`.\n- User-entered website and custom-field values are rendered as non-clickable escaped text.\n- Marker info windows always link to the canonical Maps marker page instead of a user-provided URL.\n- User-entered websites are no longer emitted as schema.org `sameAs`.\n"""
if '## Marker footer and user-link hardening' not in notes:
    notes += entry
p.write_text(notes)
