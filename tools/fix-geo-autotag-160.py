from pathlib import Path
p = Path('functions.inc')
text = p.read_text()
old = "foreach (preg_split('/\\\\s+/', $raw) as $part) {"
new = "foreach (preg_split('/[[:space:]]+/', $raw) as $part) {"
if old not in text:
    raise SystemExit('geo parser split anchor not found')
text = text.replace(old, new, 1)
p.write_text(text)
