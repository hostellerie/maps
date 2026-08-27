from pathlib import Path

p = Path('maps.php')
s = p.read_text(encoding='utf-8')
anchor = """if (!isset($_MAPS_CONF) || !is_array($_MAPS_CONF)) {\n    $_MAPS_CONF = array();\n}\n\n"""
insert = anchor + """// Self-heal installations that were already marked as Maps 1.5.8 before\n// the infos_label upgrade cleanup was corrected. This branch runs only while\n// the obsolete key is still loaded, so normal requests incur no extra query.\nif (isset($_MAPS_CONF['infos_label'])\n    && isset($_TABLES['conf_values']) && $_TABLES['conf_values'] !== ''\n) {\n    DB_query(\"DELETE FROM {$_TABLES['conf_values']} WHERE group_name='maps' AND name='infos_label'\");\n    unset($_MAPS_CONF['infos_label']);\n}\n\n"""
if anchor not in s:
    raise SystemExit('maps.php anchor not found')
if "Self-heal installations that were already marked as Maps 1.5.8" not in s:
    s = s.replace(anchor, insert, 1)
s = s.replace('// | Maps Plugin 1.5.7', '// | Maps Plugin 1.5.8', 1)
p.write_text(s, encoding='utf-8')
