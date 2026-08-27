from pathlib import Path

p = Path('functions.inc')
s = p.read_text(encoding='utf-8')

old = '''function plugin_upgrade_maps()\n{\n    global $_CONF, $_TABLES;\n\n    // Maps 1.5.8 removes the obsolete Pro-version infos_label option.\n    if (isset($_TABLES['conf_values']) && $_TABLES['conf_values'] !== '') {\n        DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='maps' AND name='infos_label'");\n    }\n\n    $installed = (string) DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name='maps'");\n'''
new = '''/**\n * Maps 1.5.8 configuration cleanup.\n *\n * Remove the obsolete Pro-only infos_label setting from existing installations\n * and force Geeklog to reload the online configuration so the field disappears\n * immediately after the upgrade. Safe to run more than once.\n *\n * @return bool\n */\nfunction MAPS_cleanupConfig158()\n{\n    global $_CONF, $_TABLES;\n\n    if (!isset($_TABLES['conf_values']) || $_TABLES['conf_values'] === '') {\n        COM_errorLog('Maps 1.5.8 upgrade: Geeklog configuration table is unavailable.');\n        return false;\n    }\n\n    $groupSql = MAPS_dbEscape('maps');\n    $nameSql = MAPS_dbEscape('infos_label');\n    DB_query("DELETE FROM {$_TABLES['conf_values']} WHERE group_name='" . $groupSql\n        . "' AND name='" . $nameSql . "'");\n\n    require_once rtrim($_CONF['path_system'], '/\\\\') . DIRECTORY_SEPARATOR . 'classes'\n        . DIRECTORY_SEPARATOR . 'config.class.php';\n    if (class_exists('config')) {\n        $config = config::get_instance();\n        if (method_exists($config, 'initConfig')) {\n            $config->initConfig();\n        }\n    }\n\n    COM_errorLog('Maps 1.5.8 upgrade: obsolete infos_label configuration removed.', 1);\n    return true;\n}\n\nfunction plugin_upgrade_maps()\n{\n    global $_CONF, $_TABLES;\n\n    $installed = (string) DB_getItem($_TABLES['plugins'], 'pi_version', "pi_name='maps'");\n'''
if old not in s:
    raise SystemExit('upgrade header anchor not found')
s = s.replace(old, new, 1)

anchor = '''    /* Maps 1.5.6 normalizes Geeklog selectionArray metadata (-1, never NULL). */\n    if (version_compare($installed, '1.5.6', '<')) {\n        if (!MAPS_repairConfig156()) {\n            COM_errorLog('Maps 1.5.6 upgrade stopped: configuration metadata repair failed.');\n            return false;\n        }\n    }\n\n    if (version_compare($installed, $code, '<')) {\n'''
replacement = '''    /* Maps 1.5.6 normalizes Geeklog selectionArray metadata (-1, never NULL). */\n    if (version_compare($installed, '1.5.6', '<')) {\n        if (!MAPS_repairConfig156()) {\n            COM_errorLog('Maps 1.5.6 upgrade stopped: configuration metadata repair failed.');\n            return false;\n        }\n    }\n\n    /* Maps 1.5.8 removes the obsolete Pro-version infos_label option. */\n    if (version_compare($installed, '1.5.8', '<')) {\n        if (!MAPS_cleanupConfig158()) {\n            COM_errorLog('Maps 1.5.8 upgrade stopped: configuration cleanup failed.');\n            return false;\n        }\n    }\n\n    if (version_compare($installed, $code, '<')) {\n'''
if anchor not in s:
    raise SystemExit('1.5.6 upgrade anchor not found')
s = s.replace(anchor, replacement, 1)
p.write_text(s, encoding='utf-8')

notes = Path('RELEASE-NOTES-1.5.8.md')
t = notes.read_text(encoding='utf-8')
entry = '- Fixed the 1.5.7 to 1.5.8 upgrade so the obsolete `infos_label` configuration row is removed and Geeklog configuration is reloaded immediately.\n'
if entry not in t:
    t += '\n' + entry
notes.write_text(t, encoding='utf-8')
