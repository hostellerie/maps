from pathlib import Path


def once(path, old, new):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('Pattern not found in %s: %s' % (path, old[:120]))
    p.write_text(text.replace(old, new, 1))

# Version metadata.
p = Path('autoinstall.php')
text = p.read_text()
text = text.replace('Maps Plugin 1.5.9', 'Maps Plugin 1.5.10', 1)
text = text.replace("'pi_version' => '1.5.9'", "'pi_version' => '1.5.10'", 1)
text = text.replace('Official support target for Maps 1.5.9:', 'Official support target for Maps 1.5.10:', 1)
p.write_text(text)

# Defaults and configuration definition.
p = Path('install_defaults.php')
text = p.read_text()
needle = "$_MAPS_DEFAULT['map_main_header'] = '';\n$_MAPS_DEFAULT['map_main_footer'] = '';"
replacement = "$_MAPS_DEFAULT['map_main_header'] = '';\n$_MAPS_DEFAULT['map_main_footer'] = '';\n$_MAPS_DEFAULT['maps_page_title'] = '';\n$_MAPS_DEFAULT['maps_page_h1'] = '';\n$_MAPS_DEFAULT['maps_meta_description'] = '';"
if needle not in text:
    raise SystemExit('Defaults insertion point not found')
text = text.replace(needle, replacement, 1)
needle = "        'display_events_map' => array($_MAPS_DEFAULT['display_events_map'], 'select', 0, 0, 3, 40, 2),\n        'fs_global_map'"
replacement = "        'display_events_map' => array($_MAPS_DEFAULT['display_events_map'], 'select', 0, 0, 3, 40, 2),\n        'fs_seo' => array(null, 'fieldset', 0, 6, null, 0, 2),\n        'maps_page_title' => array($_MAPS_DEFAULT['maps_page_title'], 'text', 0, 6, 0, 10, 2),\n        'maps_page_h1' => array($_MAPS_DEFAULT['maps_page_h1'], 'text', 0, 6, 0, 20, 2),\n        'maps_meta_description' => array($_MAPS_DEFAULT['maps_meta_description'], 'text', 0, 6, 0, 30, 2),\n        'fs_global_map'"
if needle not in text:
    raise SystemExit('Config definition insertion point not found')
text = text.replace(needle, replacement, 1)
p.write_text(text)

# 1.5.10 upgrade step.
p = Path('functions.inc')
text = p.read_text()
needle = "function plugin_upgrade_maps()\n{"
upgrade = r'''/**
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
{'''
if needle not in text:
    raise SystemExit('plugin_upgrade_maps insertion point not found')
text = text.replace(needle, upgrade, 1)
needle = "    /* Maps 1.5.9 adds the internal marker service operation ledger. */\n    if (version_compare($installed, '1.5.9', '<')) {\n        if (!MAPS_upgrade159()) {\n            COM_errorLog('Maps 1.5.9 upgrade stopped: marker service operation table creation failed.');\n            return false;\n        }\n    }\n\n    if (version_compare($installed, $code, '<')) {"
replacement = "    /* Maps 1.5.9 adds the internal marker service operation ledger. */\n    if (version_compare($installed, '1.5.9', '<')) {\n        if (!MAPS_upgrade159()) {\n            COM_errorLog('Maps 1.5.9 upgrade stopped: marker service operation table creation failed.');\n            return false;\n        }\n    }\n\n    /* Maps 1.5.10 adds landing-page SEO title, H1 and meta description settings. */\n    if (version_compare($installed, '1.5.10', '<')) {\n        if (!MAPS_upgrade1510()) {\n            COM_errorLog('Maps 1.5.10 upgrade stopped: SEO configuration migration failed.');\n            return false;\n        }\n    }\n\n    if (version_compare($installed, $code, '<')) {"
if needle not in text:
    raise SystemExit('1.5.9 upgrade block not found')
text = text.replace(needle, replacement, 1)
p.write_text(text)

# Public landing page fallbacks.
p = Path('public_html/index.php')
text = p.read_text()
needle = "$pageTitle = $LANG_MAPS_1['maps_label'];\n$pageDescription = MAPS_publicDescription(\n    MAPS_arrayGet($_MAPS_CONF, 'map_main_header', ''),\n    $LANG_MAPS_1['maps_label']\n);"
replacement = "$pageTitle = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_page_title', ''));\nif ($pageTitle === '') {\n    $pageTitle = $LANG_MAPS_1['maps_label'];\n}\n$pageH1 = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_page_h1', ''));\nif ($pageH1 === '') {\n    $pageH1 = $pageTitle;\n}\n$configuredMetaDescription = trim((string) MAPS_arrayGet($_MAPS_CONF, 'maps_meta_description', ''));\n$pageDescription = MAPS_publicDescription(\n    $configuredMetaDescription !== '' ? $configuredMetaDescription : MAPS_arrayGet($_MAPS_CONF, 'map_main_header', ''),\n    $pageTitle\n);"
if needle not in text:
    raise SystemExit('Landing SEO initialization block not found')
text = text.replace(needle, replacement, 1)
needle = "        $content .= '<h1>' . htmlspecialchars($LANG_MAPS_1['maps_label'], ENT_QUOTES, 'UTF-8') . '</h1>';"
replacement = "        $content .= '<h1>' . htmlspecialchars($pageH1, ENT_QUOTES, 'UTF-8') . '</h1>';"
if needle not in text:
    raise SystemExit('Landing H1 block not found')
text = text.replace(needle, replacement, 1)
p.write_text(text)

# Localized labels. Keep the historical key but give it a clearer admin label.
for path, labels in {
    'language/english.php': {
        'title': 'SEO title for the Maps landing page',
        'h1': 'H1 heading for the Maps landing page',
        'meta': 'Meta description for the Maps landing page',
        'fieldset': 'Landing page SEO',
        'intro': 'Introductory content for the Maps landing page (autotags supported)'
    },
    'language/french_france_utf-8.php': {
        'title': 'Titre SEO de la page des cartes',
        'h1': 'Titre H1 de la page des cartes',
        'meta': 'Meta description de la page des cartes',
        'fieldset': 'SEO de la page des cartes',
        'intro': 'Contenu introductif de la page Maps (autotags acceptés)'
    }
}.items():
    p = Path(path)
    text = p.read_text()
    marker = '?>'
    additions = "\n/* Maps 1.5.10 landing-page SEO configuration. */\n"
    additions += "$LANG_fs['maps']['fs_seo'] = %r;\n" % labels['fieldset']
    additions += "$LANG_confignames['maps']['maps_page_title'] = %r;\n" % labels['title']
    additions += "$LANG_confignames['maps']['maps_page_h1'] = %r;\n" % labels['h1']
    additions += "$LANG_confignames['maps']['maps_meta_description'] = %r;\n" % labels['meta']
    additions += "$LANG_confignames['maps']['map_main_header'] = %r;\n" % labels['intro']
    if 'maps_page_title' not in text:
        if marker in text:
            text = text.replace(marker, additions + marker, 1)
        else:
            text += additions
    p.write_text(text)

# Release notes.
Path('RELEASE-NOTES-1.5.10.md').write_text('''# Maps 1.5.10 release notes\n\n## Landing-page SEO configuration\n\nMaps 1.5.10 adds three dedicated SEO settings for `/maps/` without changing map or marker SEO automation:\n\n- `maps_page_title` controls the landing-page document title, Open Graph title and Twitter title;\n- `maps_page_h1` controls the visible H1 independently from the menu label and document title;\n- `maps_meta_description` controls the landing-page meta/social description.\n\nFallbacks remain safe and automatic when fields are empty: the plugin label is used for the title, the SEO title for the H1, and the introductory Maps content for the description. Canonical URLs remain automatic and are not administrator-editable.\n\nThe existing `map_main_header` key is retained for compatibility but is labeled more clearly in administration as introductory Maps-page content.\n''')
