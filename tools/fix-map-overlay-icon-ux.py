from pathlib import Path


def replace_required(text, old, new, label):
    if old not in text:
        raise SystemExit('Missing anchor: ' + label)
    return text.replace(old, new, 1)

# 1) Correct overlay action labels in EN/FR language files.
for filename, old, new in [
    (
        'language/english.php',
        "'add_overlay'           => 'You must save your map first if you want to add overlay',",
        "'add_overlay'           => 'Add overlay',"
    ),
    (
        'language/french_france_utf-8.php',
        "'add_overlay'           => 'Vous devez d\\'abord sauvegarder la carte avant de pouvoir ajouter un overlay',",
        "'add_overlay'           => 'Ajouter l\\'overlay',"
    ),
]:
    p = Path(filename)
    s = p.read_text(encoding='utf-8')
    if old not in s:
        # Be tolerant of historical French wording; locate the key itself.
        if filename.endswith('french_france_utf-8.php'):
            lines = s.splitlines(True)
            changed = False
            for i, line in enumerate(lines):
                if "'add_overlay'" in line:
                    prefix = line[:len(line) - len(line.lstrip())]
                    lines[i] = prefix + "'add_overlay'           => 'Ajouter l\\'overlay',\n"
                    changed = True
                    break
            if not changed:
                raise SystemExit('Missing anchor: French add_overlay')
            s = ''.join(lines)
        else:
            raise SystemExit('Missing anchor: English add_overlay')
    else:
        s = s.replace(old, new, 1)
    p.write_text(s, encoding='utf-8')

# 2) Make map names unambiguously clickable on the admin list.
p = Path('admin/index.php')
s = p.read_text(encoding='utf-8')
old = """            $link = COM_createLink(
                $safeTitle,
                $url,
                array('title' => htmlspecialchars($LANG_MAPS_1['title_display'], ENT_QUOTES, 'UTF-8'))
            );

            if ($A['description'] != '') {
                $safeDescription = htmlspecialchars(
                    MAPS_decodeStoredText($A['description']),
                    ENT_QUOTES,
                    'UTF-8'
                );
                $retval = COM_getTooltip($safeTitle, $safeDescription, $url, $safeTitle, 'help');
            } else {
                $retval = $link;
            }
"""
new = """            $linkTitle = $LANG_MAPS_1['title_display'];
            if ($A['description'] != '') {
                $description = trim(MAPS_decodeStoredText($A['description']));
                if ($description !== '') {
                    $linkTitle .= ' — ' . $description;
                }
            }
            $retval = COM_createLink(
                $safeTitle,
                $url,
                array('title' => htmlspecialchars($linkTitle, ENT_QUOTES, 'UTF-8'))
            );
"""
s = replace_required(s, old, new, 'admin map name public link')
p.write_text(s, encoding='utf-8')

# 3) Cache-bust icon previews/list/radio choices using filemtime so a replaced image is visible immediately.
p = Path('admin/icons.php')
s = p.read_text(encoding='utf-8')
old = """                $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename);
                return '<img src=\"' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
"""
new = """                $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename)
                    . '?v=' . (int) @filemtime($path);
                return '<img src=\"' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8')
"""
s = replace_required(s, old, new, 'icon list cache bust')
old = """        $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename);
        $template->set_var(
"""
new = """        $url = $_MAPS_CONF['images_icons_url'] . rawurlencode($filename)
            . '?v=' . (int) @filemtime($path);
        $template->set_var(
"""
s = replace_required(s, old, new, 'icon edit preview cache bust')
p.write_text(s, encoding='utf-8')

p = Path('admin/map_edit.php')
s = p.read_text(encoding='utf-8')
old = """        $radio .= '<label><input type=\"radio\" name=\"mk_icon\" value=\"' . (int) $icon['icon_id'] . '\"' . ((int) $map['mmk_icon'] === (int) $icon['icon_id'] ? ' checked=\"checked\"' : '') . '> <img src=\"' . $_MAPS_CONF['images_icons_url'] . rawurlencode($icon['icon_image']) . '\" alt=\"\" style=\"max-width:32px;max-height:32px\"></label> ';
"""
new = """        $iconFile = basename((string) $icon['icon_image']);
        $iconPath = $_MAPS_CONF['path_icons_images'] . $iconFile;
        $iconUrl = $_MAPS_CONF['images_icons_url'] . rawurlencode($iconFile);
        if ($iconFile !== '' && is_file($iconPath)) {
            $iconUrl .= '?v=' . (int) @filemtime($iconPath);
        }
        $radio .= '<label><input type=\"radio\" name=\"mk_icon\" value=\"' . (int) $icon['icon_id'] . '\"' . ((int) $map['mmk_icon'] === (int) $icon['icon_id'] ? ' checked=\"checked\"' : '') . '> <img src=\"' . htmlspecialchars($iconUrl, ENT_QUOTES, 'UTF-8') . '\" alt=\"\" style=\"max-width:32px;max-height:32px\"></label> ';
"""
s = replace_required(s, old, new, 'map icon radio cache bust')
p.write_text(s, encoding='utf-8')
