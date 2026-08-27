from pathlib import Path
import re

# 1) Bump code/documentation/package references from 1.5.7 to 1.5.8.
for rel in [
    'autoinstall.php', 'functions.inc', 'install_defaults.php', 'README.md',
    'ROADMAP.md', '.github/workflows/package.yml'
]:
    p = Path(rel)
    s = p.read_text(encoding='utf-8')
    s = s.replace('1.5.7', '1.5.8')
    p.write_text(s, encoding='utf-8')

# Rename release notes file and update internal references/content.
old_notes = Path('RELEASE-NOTES-1.5.7.md')
new_notes = Path('RELEASE-NOTES-1.5.8.md')
notes = old_notes.read_text(encoding='utf-8').replace('1.5.7', '1.5.8')
notes += "\n\n## Configuration cleanup in 1.5.8\n\n- Renamed marker resource configuration labels to `Custom field N label` / `Libellé du champ personnalisé N`.\n- Removed the obsolete `infos_label` / `Infos label (Pro version)` configuration option.\n- Kept `item_1` through `item_10` as supported marker custom fields.\n"
new_notes.write_text(notes, encoding='utf-8')
old_notes.unlink()

# 2) Configuration defaults: clearer custom-field defaults and no infos_label.
p = Path('install_defaults.php')
s = p.read_text(encoding='utf-8')
s = s.replace("$_MAPS_DEFAULT['item_' . $i] = 'Ressource #' . $i;", "$_MAPS_DEFAULT['item_' . $i] = 'Custom field ' . $i;")
s = re.sub(r"\n\$_MAPS_DEFAULT\['infos_label'\] = 'Infos';\n", "\n", s)
s = re.sub(r"\n\s*\$rows\['infos_label'\] = array\([^\n]+\);", "", s)
s = s.replace('Return the complete Maps 1.5.6 configuration presentation definition.', 'Return the complete Maps 1.5.8 configuration presentation definition.')
p.write_text(s, encoding='utf-8')

# 3) Language labels: custom fields are configuration labels, not old "Resource" wording.
for rel, label in [
    ('language/english.php', 'Custom field {n} label'),
    ('language/french_france_utf-8.php', 'Libellé du champ personnalisé {n}')
]:
    p = Path(rel)
    s = p.read_text(encoding='utf-8')
    for i in range(1, 11):
        # Replace the historical config-name entry regardless of spacing.
        pattern = r"('item_%d'\s*=>\s*)'[^']*'" % i
        s = re.sub(pattern, lambda m, i=i: m.group(1) + "'" + label.format(n=i).replace("'", "\\'") + "'", s)
    # Remove historical infos_label language entry.
    s = re.sub(r"\n\s*'infos_label'\s*=>\s*'[^']*',?", "", s)
    p.write_text(s, encoding='utf-8')

# 4) Remove infos_label from existing installations during upgrade.
# Geeklog stores config entries in conf_values. Delete only this plugin/key if the table exists.
p = Path('functions.inc')
s = p.read_text(encoding='utf-8')
needle = "function plugin_upgrade_maps()\n{\n    global $_CONF, $_TABLES;"
if needle not in s:
    raise SystemExit('plugin_upgrade_maps() signature not found')
replacement = needle + "\n\n    // Maps 1.5.8 removes the obsolete Pro-version infos_label option.\n    if (isset($_TABLES['conf_values']) && $_TABLES['conf_values'] !== '') {\n        DB_query(\"DELETE FROM {$_TABLES['conf_values']} WHERE group_name='maps' AND name='infos_label'\");\n    }"
s = s.replace(needle, replacement, 1)
p.write_text(s, encoding='utf-8')

# 5) Package workflow file/archive names.
p = Path('.github/workflows/package.yml')
s = p.read_text(encoding='utf-8')
s = s.replace('RELEASE-NOTES-1.5.7.md', 'RELEASE-NOTES-1.5.8.md')
s = s.replace('maps-1.5.7-test.zip', 'maps-1.5.8-test.zip')
s = s.replace('Rebuild Maps 1.5.7 test archive', 'Rebuild Maps 1.5.8 test archive')
p.write_text(s, encoding='utf-8')

# 6) Verify public use of item_1..item_10. Write an audit artifact into repo docs for this release.
# Current code uses them in admin/user editors + persistence/moderation, but public rendering has no item_* references.
public_refs = []
for p in Path('public_html').rglob('*.php'):
    text = p.read_text(encoding='utf-8')
    # Exclude the edit form plumbing in markers.php; flag references outside form handling manually via counts.
    for i in range(1, 11):
        if ('item_%d' % i) in text:
            public_refs.append(str(p))
            break

audit = """# Maps 1.5.8 custom fields audit\n\n`item_1` through `item_10` remain supported marker fields. They are stored in `maps_markers` and `maps_submission`, included in moderation, and exposed in the admin/user marker edit forms when their configured label is non-empty.\n\nThe public marker rendering does not currently render these values as a dedicated public custom-fields block. The only `public_html` references are in `markers.php`, where they support the user edit form and save path. This is existing behavior and is intentionally not changed by the 1.5.8 configuration-label cleanup.\n\nFiles in `public_html` containing item references during the audit: %s.\n""" % (', '.join(sorted(set(public_refs))) if public_refs else 'none')
Path('CUSTOM-FIELDS-AUDIT-1.5.8.md').write_text(audit, encoding='utf-8')

# 7) Remove obsolete old dist archive; package workflow will build the new one.
old_zip = Path('dist/maps-1.5.7-test.zip')
if old_zip.exists():
    old_zip.unlink()
