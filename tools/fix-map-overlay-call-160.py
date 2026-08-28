from pathlib import Path

path = Path('admin/map_edit.php')
text = path.read_text()
old = "MAPS_displayAddOverlay($map['mid'])"
new = "MAPS_displayOverlaysToAdd($map['mid'])"
if old not in text:
    raise SystemExit('legacy overlay function call not found')
text = text.replace(old, new, 1)
path.write_text(text)
