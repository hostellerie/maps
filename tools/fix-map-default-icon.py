from pathlib import Path

p = Path('functions.inc')
s = p.read_text(encoding='utf-8')
old = """    $markerIconId = (int) MAPS_arrayGet($marker, 'mk_icon', 0);
    $useDefault = (int) MAPS_arrayGet($marker, 'mk_default', 1);
    $mapDefault = (int) MAPS_arrayGet($map3, 'mmk_default', 1);
    $mapIconId = (int) MAPS_arrayGet($map3, 'mmk_icon', 0);
    $selectedIcon = $useDefault === 0 ? $markerIconId : ($mapDefault === 0 ? $mapIconId : 0);
"""
new = """    $markerIconId = (int) MAPS_arrayGet($marker, 'mk_icon', 0);
    $useDefault = (int) MAPS_arrayGet($marker, 'mk_default', 1);
    $mapIconId = (int) MAPS_arrayGet($map3, 'mmk_icon', 0);

    // Icon priority is explicit marker icon, then explicit map icon, then
    // the generated/default Google marker. Historically mmk_default could
    // suppress a selected map icon, leaving mmk_icon stored but ignored.
    if ($useDefault === 0 && $markerIconId > 0) {
        $selectedIcon = $markerIconId;
    } elseif ($useDefault !== 0 && $mapIconId > 0) {
        $selectedIcon = $mapIconId;
    } else {
        $selectedIcon = 0;
    }
"""
if old not in s:
    raise SystemExit('Expected icon selection block not found')
s = s.replace(old, new, 1)
p.write_text(s, encoding='utf-8')
