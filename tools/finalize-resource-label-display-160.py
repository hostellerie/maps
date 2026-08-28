from pathlib import Path


def replace(path, old, new, count=1):
    p = Path(path)
    text = p.read_text()
    if old not in text:
        raise SystemExit('pattern not found in %s' % path)
    p.write_text(text.replace(old, new, count))

replace(
    'functions.inc',
    "        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));\n        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));\n        if ($label === '') {\n            continue;\n        }\n        if ($value === '' && $layout === 'compact') {\n            continue;\n        }",
    "        $label = trim((string) MAPS_arrayGet($_MAPS_CONF, 'item_' . $i, ''));\n        $value = trim((string) MAPS_arrayGet($marker, 'item_' . $i, ''));\n        $placeholderLabels = array(\n            'Custom field ' . $i,\n            'Ressource #' . $i,\n            'Resource #' . $i\n        );\n        // Empty/default labels mean that this resource field is not configured.\n        // Keep the admin warning in the editor, but never expose placeholders\n        // or empty resource rows on public marker pages.\n        if ($label === '' || in_array($label, $placeholderLabels, true)) {\n            continue;\n        }\n        if ($value === '' && $layout === 'compact') {\n            continue;\n        }"
)

p = Path('RELEASE-NOTES-1.6.0.md')
notes = p.read_text()
line = '- Public marker resources are rendered only when a real resource label is configured; empty/default placeholder labels remain admin-only guidance and are hidden from marker pages.\n'
if line not in notes:
    notes += '\n' + line
p.write_text(notes)
