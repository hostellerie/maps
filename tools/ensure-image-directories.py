from pathlib import Path


def replace_required(text, old, new, label):
    if old not in text:
        raise SystemExit('Missing anchor: ' + label)
    return text.replace(old, new, 1)

# Add one reusable helper to functions.inc.
p = Path('functions.inc')
s = p.read_text(encoding='utf-8')
anchor = """function MAPS_dbEscape($value)\n{\n    if (function_exists('DB_escapeString')) {\n        return DB_escapeString((string) $value);\n    }\n    return addslashes((string) $value);\n}\n\n"""
helper = anchor + """/**\n * Ensure a configured Maps directory exists and is writable.\n *\n * Missing image directories can occur after manual installs, migrations or\n * archive extraction. Create them recursively when the parent path permits it,\n * then verify the resulting directory before uploads are attempted.\n *\n * @param mixed $path\n * @return bool\n */\nfunction MAPS_ensureWritableDirectory($path)\n{\n    $path = rtrim((string) $path, '/\\\\');\n    if ($path === '') {\n        return false;\n    }\n\n    if (!is_dir($path)) {\n        $oldUmask = umask(0);\n        $created = @mkdir($path, 0755, true);\n        umask($oldUmask);\n\n        if (!$created && !is_dir($path)) {\n            COM_errorLog('MAPS could not create directory: ' . $path);\n            return false;\n        }\n    }\n\n    if (!is_writable($path)) {\n        COM_errorLog('MAPS directory is not writable: ' . $path);\n        return false;\n    }\n\n    return true;\n}\n\n"""
s = replace_required(s, anchor, helper, 'functions helper insertion')
p.write_text(s, encoding='utf-8')

# Icons page: attempt creation before showing the existing error block.
p = Path('admin/icons.php')
s = p.read_text(encoding='utf-8')
s = replace_required(
    s,
    "if (!file_exists($_MAPS_CONF['path_icons_images']) || !is_writable($_MAPS_CONF['path_icons_images'])) {",
    "if (!MAPS_ensureWritableDirectory($_MAPS_CONF['path_icons_images'])) {",
    'icons directory check'
)
p.write_text(s, encoding='utf-8')

# Overlays page: same behavior.
p = Path('admin/overlays.php')
s = p.read_text(encoding='utf-8')
s = replace_required(
    s,
    "if (!file_exists($_MAPS_CONF['path_overlay_images']) || !is_writable($_MAPS_CONF['path_overlay_images'])) {",
    "if (!MAPS_ensureWritableDirectory($_MAPS_CONF['path_overlay_images'])) {",
    'overlays directory check'
)
p.write_text(s, encoding='utf-8')
