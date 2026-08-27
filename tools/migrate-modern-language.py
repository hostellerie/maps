from pathlib import Path
import re

modern = Path('language/modern.php')
text = modern.read_text(encoding='utf-8')

marker = "if ($isFrench) {"
start = text.find(marker)
if start < 0:
    raise SystemExit('French branch not found')

# Parse balanced braces to extract French body and the following else body.
def matching_brace(s, open_pos):
    depth = 0
    in_single = False
    in_double = False
    escape = False
    for i in range(open_pos, len(s)):
        ch = s[i]
        if escape:
            escape = False
            continue
        if ch == '\\' and (in_single or in_double):
            escape = True
            continue
        if ch == "'" and not in_double:
            in_single = not in_single
            continue
        if ch == '"' and not in_single:
            in_double = not in_double
            continue
        if in_single or in_double:
            continue
        if ch == '{':
            depth += 1
        elif ch == '}':
            depth -= 1
            if depth == 0:
                return i
    raise SystemExit('Unbalanced braces')

fr_open = text.find('{', start)
fr_close = matching_brace(text, fr_open)
else_pos = text.find('else', fr_close)
if else_pos < 0:
    raise SystemExit('English else branch not found')
en_open = text.find('{', else_pos)
en_close = matching_brace(text, en_open)

fr_body = text[fr_open + 1:fr_close].strip('\n')
en_body = text[en_open + 1:en_close].strip('\n')

# Remove the branch indentation so the assignments look native in language files.
def dedent_four(body):
    lines = body.splitlines()
    return '\n'.join(line[4:] if line.startswith('    ') else line for line in lines).rstrip() + '\n'

fr_body = dedent_four(fr_body)
en_body = dedent_four(en_body)

header = "\n\n/* Maps 1.5.7 configuration labels. */\n"

for filename, body in [
    ('language/french_france_utf-8.php', fr_body),
    ('language/english.php', en_body),
]:
    p = Path(filename)
    s = p.read_text(encoding='utf-8')
    if '/* Maps 1.5.7 configuration labels. */' in s:
        raise SystemExit(filename + ' already migrated')
    pos = s.rfind('?>')
    addition = header + body
    if pos >= 0:
        s = s[:pos].rstrip() + addition + "?>\n"
    else:
        s = s.rstrip() + addition
    p.write_text(s, encoding='utf-8')

# Remove the known indirect loader from maps.php.
maps = Path('maps.php')
maps_text = maps.read_text(encoding='utf-8')
loader = """$modernLanguageFile = $_CONF['path'] . 'plugins/maps/language/modern.php';
if (file_exists($modernLanguageFile)) {
    require_once $modernLanguageFile;
}

"""
if loader not in maps_text:
    raise SystemExit('Expected modern language loader not found in maps.php')
maps.write_text(maps_text.replace(loader, '', 1), encoding='utf-8')

# Defensive cleanup for any remaining direct require/include reference.
for p in Path('.').rglob('*.php'):
    if p == modern:
        continue
    s = p.read_text(encoding='utf-8')
    if 'modern.php' not in s:
        continue
    original = s
    lines = []
    for line in s.splitlines(True):
        if 'modern.php' in line and re.search(r'\b(require|require_once|include|include_once)\b', line):
            continue
        lines.append(line)
    s = ''.join(lines)
    if s != original:
        p.write_text(s, encoding='utf-8')

modern.unlink()
