from pathlib import Path


def replace_once(path, old, new, label):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('%s anchor not found in %s' % (label, path))
    p.write_text(text.replace(old, new, 1))

# Modernize the public maps list into compact editorial cards.
p = Path('public_html/index.php')
text = p.read_text()
old = """    $retval .= '<p>' . $LANG_MAPS_1['user_maps_list'] . '</p>';
    $result = DB_query(\"SELECT mid,name,description,active,hidden,modified,hits FROM {$_TABLES['maps_maps']} ORDER BY name ASC\");
    $count = 0;
    while ($map = DB_fetchArray($result)) {
        if ((int) $map['active'] !== 1 || (int) $map['hidden'] === 1) {
            continue;
        }
        $count++;
        $url = $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $map['mid'];
        $retval .= '<div class=\"maps_list_item\">';
        $retval .= '<strong><a href=\"' . $url . '\">' . htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8') . '</a></strong>';
        if ($map['description'] !== '') {
            $retval .= '<br>' . htmlspecialchars(stripslashes($map['description']), ENT_QUOTES, 'UTF-8');
        }
        $modified = COM_getUserDateTimeFormat($map['modified']);
        $retval .= '<br><small>' . $LANG_MAPS_1['last_modification'] . ' ' . $modified[0];
        if ((int) MAPS_arrayGet($_MAPS_CONF, 'stats_public_enabled', 1) === 1) {
            $markers = DB_count($_TABLES['maps_markers'], 'mid', $map['mid']);
            $retval .= ' | ' . (int) $markers . ' ' . $LANG_MAPS_1['records']
                . ' | ' . (int) $map['hits'] . ' ' . $LANG_MAPS_1['hits'];
        }
        $retval .= '</small>';
        if (SEC_hasRights('maps.admin')) {
            $retval .= ' | <a href=\"' . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&amp;mid=' . (int) $map['mid'] . '\">' . $LANG_MAPS_1['edit_button'] . '</a>';
        }
        $retval .= '</div>';
    }

    if ($count === 0) {
        $retval .= '<p>' . $LANG_MAPS_1['no_map_user'] . '</p>';
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'users_map', 1) === 1) {
        $retval .= '<div class=\"maps_list_item\"><strong><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'
            . $LANG_MAPS_1['users_map'] . '</a></strong><br>' . $LANG_MAPS_1['info_users_map'] . '</div>';
    }
"""
new = """    $retval .= '<section class=\"maps-public-list\">';
    $retval .= '<h2 class=\"maps-public-list-title\">'
        . htmlspecialchars($LANG_MAPS_1['user_maps_list'], ENT_QUOTES, 'UTF-8') . '</h2>';
    $result = DB_query(\"SELECT mid,name,description,active,hidden,modified,hits FROM {$_TABLES['maps_maps']} ORDER BY name ASC\");
    $count = 0;
    while ($map = DB_fetchArray($result)) {
        if ((int) $map['active'] !== 1 || (int) $map['hidden'] === 1) {
            continue;
        }
        $count++;
        $url = $_MAPS_CONF['site_url'] . '/index.php?mode=map&amp;mid=' . (int) $map['mid'];
        $retval .= '<article class=\"maps-list-card\">';
        $retval .= '<div class=\"maps-list-card-main\">';
        $retval .= '<h3 class=\"maps-list-card-title\"><a href=\"' . $url . '\">'
            . htmlspecialchars(stripslashes($map['name']), ENT_QUOTES, 'UTF-8') . '</a></h3>';
        if ($map['description'] !== '') {
            $retval .= '<div class=\"maps-list-card-description\">'
                . htmlspecialchars(stripslashes($map['description']), ENT_QUOTES, 'UTF-8') . '</div>';
        }
        $modified = COM_getUserDateTimeFormat($map['modified']);
        $retval .= '<div class=\"maps-list-card-meta\">';
        $retval .= '<span>' . htmlspecialchars($LANG_MAPS_1['last_modification'], ENT_QUOTES, 'UTF-8') . ' '
            . htmlspecialchars($modified[0], ENT_QUOTES, 'UTF-8') . '</span>';
        if ((int) MAPS_arrayGet($_MAPS_CONF, 'stats_public_enabled', 1) === 1) {
            $markers = (int) DB_count($_TABLES['maps_markers'], 'mid', $map['mid']);
            $markerLabel = ($markers === 1) ? $LANG_MAPS_1['marker_singular'] : $LANG_MAPS_1['marker_plural'];
            $retval .= '<span>' . $markers . ' ' . htmlspecialchars($markerLabel, ENT_QUOTES, 'UTF-8') . '</span>';
            $retval .= '<span>' . (int) $map['hits'] . ' ' . htmlspecialchars($LANG_MAPS_1['views_label'], ENT_QUOTES, 'UTF-8') . '</span>';
        }
        $retval .= '</div></div>';
        if (SEC_hasRights('maps.admin')) {
            $retval .= '<div class=\"maps-list-card-actions\"><a class=\"maps-list-edit\" href=\"'
                . $_CONF['site_admin_url'] . '/plugins/maps/map_edit.php?mode=edit&amp;mid=' . (int) $map['mid'] . '\">'
                . htmlspecialchars($LANG_MAPS_1['edit_button'], ENT_QUOTES, 'UTF-8') . '</a></div>';
        }
        $retval .= '</article>';
    }

    if ($count === 0) {
        $retval .= '<p>' . $LANG_MAPS_1['no_map_user'] . '</p>';
    }
    if ((int) MAPS_arrayGet($_MAPS_CONF, 'users_map', 1) === 1) {
        $retval .= '<article class=\"maps-list-card maps-list-card-users\"><div class=\"maps-list-card-main\">'
            . '<h3 class=\"maps-list-card-title\"><a href=\"' . $_MAPS_CONF['site_url'] . '/users_map.php\">'
            . htmlspecialchars($LANG_MAPS_1['users_map'], ENT_QUOTES, 'UTF-8') . '</a></h3>'
            . '<div class=\"maps-list-card-description\">' . htmlspecialchars($LANG_MAPS_1['info_users_map'], ENT_QUOTES, 'UTF-8') . '</div>'
            . '</div></article>';
    }
    $retval .= '</section>';
"""
if old not in text:
    raise SystemExit('front page list anchor not found')
text = text.replace(old, new, 1)
p.write_text(text)

# Language: replace the database-oriented heading and add compact metadata labels.
for path, old_heading, new_heading, singular, plural, views in [
    ('language/english.php', "'user_maps_list'        => 'List of the maps recorded in our database :'", "'user_maps_list'        => 'Explore our maps'", 'marker', 'markers', 'views'),
    ('language/french_france_utf-8.php', "'user_maps_list'        => 'Voici les cartes présentes dans notre base de données :'", "'user_maps_list'        => 'Découvrez nos cartes'", 'marqueur', 'marqueurs', 'vues')
]:
    p = Path(path)
    t = p.read_text()
    if old_heading not in t:
        raise SystemExit('heading anchor not found in ' + path)
    t = t.replace(old_heading, new_heading, 1)
    anchor = "    'map_markers_heading'"
    pos = t.find(anchor)
    if pos < 0:
        raise SystemExit('language insertion anchor not found in ' + path)
    line_end = t.find('\n', pos)
    insertion = "\n    'marker_singular'      => '%s',\n    'marker_plural'        => '%s',\n    'views_label'          => '%s'," % (singular, plural, views)
    t = t[:line_end] + insertion + t[line_end:]
    p.write_text(t)

# CSS for compact one-column editorial cards.
p = Path('public_html/maps.css')
css = p.read_text()
append = """

/* Maps 1.6.0 public map directory */
.maps-public-list {
    margin: 1.75rem 0 2rem;
}
.maps-public-list-title {
    margin: 0 0 .85rem;
}
.maps-list-card {
    display: flex;
    justify-content: space-between;
    gap: 1rem 1.5rem;
    align-items: flex-start;
    margin: 0 0 .85rem;
    padding: 1rem 1.1rem;
    border: 1px solid rgba(127,127,127,.24);
    border-radius: 9px;
    background: rgba(127,127,127,.035);
}
.maps-list-card-main {
    flex: 1 1 auto;
    min-width: 0;
}
.maps-list-card-title {
    margin: 0;
    font-size: 1.12rem;
    line-height: 1.3;
}
.maps-list-card-description {
    margin-top: .35rem;
    line-height: 1.5;
}
.maps-list-card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem 1rem;
    margin-top: .65rem;
    font-size: .88em;
    opacity: .78;
}
.maps-list-card-meta span + span::before {
    content: "•";
    margin-right: 1rem;
    opacity: .55;
}
.maps-list-card-actions {
    flex: 0 0 auto;
}
.maps-list-edit {
    display: inline-block;
    padding: .35rem .65rem;
    border: 1px solid rgba(127,127,127,.28);
    border-radius: 6px;
    text-decoration: none;
}
.maps-list-edit:hover,
.maps-list-edit:focus {
    background: rgba(127,127,127,.07);
}
.maps-list-card-users {
    margin-top: 1rem;
}
@media (max-width: 640px) {
    .maps-list-card {
        display: block;
        padding: .9rem;
    }
    .maps-list-card-actions {
        margin-top: .75rem;
    }
    .maps-list-card-meta {
        display: grid;
        gap: .3rem;
    }
    .maps-list-card-meta span + span::before {
        content: "";
        margin: 0;
    }
}
"""
if '/* Maps 1.6.0 public map directory */' not in css:
    css += append
p.write_text(css)

# Release note trace.
p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
entry = """
## Public map directory polish

- Replaced the database-style public map list with compact responsive editorial cards.
- Added a clearer Explore our maps / Découvrez nos cartes section heading.
- Separated map title, description, metadata and administrator edit action.
- Replaced frontend records terminology with marker / markers and views.
- Kept the users map in the same visual card system without inventing unavailable statistics.
"""
if '## Public map directory polish' not in notes:
    notes += entry
p.write_text(notes)
