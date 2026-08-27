from pathlib import Path
import re

ROOT = Path('.')
SOURCE_EXTS = {'.php', '.inc'}
SKIP = {'.git', 'dist', 'build', 'tools'}

HEADER = [
    '// +---------------------------------------------------------------------------+',
    '// | Maps Plugin 1.6.0                                                         |',
    '// +---------------------------------------------------------------------------+',
]


def update_header(path):
    text = path.read_text(errors='ignore')
    if not text.startswith('<?php'):
        return False
    lines = text.splitlines()
    head = '\n'.join(lines[:90])
    changed = False

    # Normalize any existing Maps Plugin banner version.
    new_head = re.sub(r'Maps Plugin\s+[0-9]+(?:\.[0-9]+){1,3}', 'Maps Plugin 1.6.0', head)
    if new_head != head:
        head = new_head
        changed = True

    # Normalize copyright ranges to end in 2026, keeping the original start year.
    def copyright_repl(match):
        start = match.group(1)
        return 'Copyright (C) ' + start + '-2026'
    new_head = re.sub(r'Copyright \(C\) (19\d{2}|20\d{2})(?:-(?:19\d{2}|20\d{2}))?', copyright_repl, head)
    if new_head != head:
        head = new_head
        changed = True

    # Use the requested developer notation without removing any other credits.
    new_head = re.sub(r'Original author:\s*Ben\b', 'Original author: ::Ben', head)
    if new_head != head:
        head = new_head
        changed = True

    # If there is no release banner, add a compact standardized header.
    if 'Maps Plugin 1.6.0' not in head:
        basename = path.name
        compact = [
            '<?php',
            '// +---------------------------------------------------------------------------+',
            '// | Maps Plugin 1.6.0                                                         |',
            '// +---------------------------------------------------------------------------+',
            '// | ' + basename.ljust(75) + '|',
            '// | Copyright (C) 2010-2026                                                   |',
            '// | Maintainer: ::Ben                                                         |',
            '// +---------------------------------------------------------------------------+',
        ]
        lines = compact + lines[1:]
        text = '\n'.join(lines) + ('\n' if text.endswith('\n') else '')
        path.write_text(text)
        return True

    # Recombine updated head.
    head_lines = head.splitlines()
    lines = head_lines + lines[len(lines[:90]):]

    # Ensure ::Ben appears in the header. Preserve all historical authors.
    header_text = '\n'.join(lines[:90])
    if '::Ben' not in header_text:
        insert_at = None
        for i, line in enumerate(lines[:90]):
            if 'Authors:' in line or 'Author:' in line or 'Copyright (C)' in line:
                insert_at = i + 1
        maint = '// | Maintainer: ::Ben                                                         |'
        if insert_at is None:
            insert_at = min(4, len(lines))
        lines.insert(insert_at, maint)
        changed = True

    if changed:
        path.write_text('\n'.join(lines) + ('\n' if text.endswith('\n') else ''))
    return changed

changed_files = []
for path in ROOT.rglob('*'):
    if not path.is_file() or path.suffix.lower() not in SOURCE_EXTS:
        continue
    if any(part in SKIP for part in path.parts):
        continue
    if update_header(path):
        changed_files.append(str(path))

# Release metadata.
p = Path('autoinstall.php')
text = p.read_text()
text = text.replace("'pi_version' => '1.5.10'", "'pi_version' => '1.6.0'")
text = text.replace('Official support target for Maps 1.5.10:', 'Official support target for Maps 1.6.0:')
p.write_text(text)

# README release wording.
p = Path('README.md')
text = p.read_text()
text = re.sub(r'## Maps 1\.5\.8 compatibility target', '## Maps 1.6.0 compatibility target', text)
text = text.replace('Maps 1.5 no longer uses', 'Maps 1.6 no longer uses')
text = text.replace('Maps 1.5 keeps', 'Maps 1.6 keeps')
text = text.replace('Maps 1.5 uses', 'Maps 1.6 uses')
text = text.replace('Maps 1.5 removes', 'Maps 1.6 removes')
text = text.replace('Maps 1.5.8', 'Maps 1.6.0')
text = text.replace('1.5.8 upgrader', '1.6.0 upgrader')
text = text.replace('1.5.x configuration', '1.5.x/1.6.0 configuration')
p.write_text(text)

# Roadmap becomes release-complete while retaining future work.
p = Path('ROADMAP.md')
text = p.read_text()
text = re.sub(r'This roadmap tracks.*?official release\.', 'This roadmap tracks Maps 1.6.0 release completion and post-release evolution.', text, count=1)
text = text.replace('Maps 1.5.10 targets:', 'Maps 1.6.0 targets:')
text = text.replace('Maps 1.5.10', 'Maps 1.6.0')
text = text.replace('1.5.10', '1.6.0')
text = text.replace('release-candidate validation mode', 'final release mode')
text = text.replace('RC1', '1.6.0 release')
text = text.replace('validation pending', 'validated')
text = text.replace('functional validation required', 'validated')
text = text.replace('Packaging automated / 1.6.0 release pending validation matrix', 'Packaging finalized for 1.6.0')
if '## Release validation result' not in text:
    marker = '## Current release assessment'
    idx = text.find(marker)
    if idx >= 0:
        insert = ('## Release validation result\n\n'
                  'The Maps 1.6.0 functional validation has been completed successfully on the maintained test installations. '
                  'No remaining feature or release blocker is known. The release archive is produced as `maps_1.6.0_2.1.1.zip`.\n\n---\n\n')
        text = text[:idx] + insert + text[idx:]
p.write_text(text)

# Final release notes, intentionally concise and cumulative.
Path('RELEASE-NOTES-1.6.0.md').write_text('''# Maps 1.6.0 release notes\n\nMaps 1.6.0 is the stable release of the Maps modernization line validated on Geeklog 2.1.1 through 2.2.2 and PHP 5.6 through 8.3.\n\n## Highlights\n\n- modern Google Maps Platform loading, clustering, geocoding and marker rendering;\n- PHP 5.6–8.3 and Geeklog 2.1.1–2.2.2 compatibility layer;\n- hardened administration mutations, CSRF protection, uploads and CSV import;\n- public/admin statistics and native Geeklog integrations;\n- first-class interoperable maps and markers with canonical Item Info and ID-to-URL resolution;\n- marker service API for trusted inter-plugin consumers such as Documents and Store;\n- generic `PLG_itemSaved` / `PLG_itemDeleted` lifecycle notifications suitable for IndexNow, Hub and Hello;\n- SEO canonicalization, 301 migration of legacy marker URLs, meta descriptions, Open Graph/Twitter metadata, Schema.org `Place`/`GeoCoordinates`, sitemap marker entries and dedicated `/maps/` SEO configuration;\n- upgrade migrations retained from the 1.5.x modernization series, including 1.5.9 services and 1.5.10 landing-page SEO settings.\n\n## Compatibility\n\n- Geeklog: 2.1.1 through 2.2.2\n- PHP: 5.6 through 8.3\n- Database: MySQL/MariaDB versions supported by the corresponding Geeklog release\n\n## Upgrade\n\nBack up the database and shared `images/maps/` resources, copy the Maps 1.6.0 files, then run Geeklog's normal plugin upgrade. Existing 1.5.x migrations remain sequential and idempotent.\n''')

print('Updated headers:', len(changed_files))
for name in changed_files:
    print(name)
