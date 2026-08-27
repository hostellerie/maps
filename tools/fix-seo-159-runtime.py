from pathlib import Path

p = Path('public_html/index.php')
text = p.read_text()
old = """    if (MAPS_isValidCoordinatePair(MAPS_arrayGet($markerRow, 'lat', ''), MAPS_arrayGet($markerRow, 'lng', ''))) {\n        $place['geo'] = array(\n            '@type' => 'GeoCoordinates',\n            'latitude' => (float) MAPS_latitude($markerRow['lat']),\n            'longitude' => (float) MAPS_longitude($markerRow['lng'])\n        );\n    }\n"""
new = """    $markerLat = MAPS_arrayGet($markerRow, 'lat', '');\n    $markerLng = MAPS_arrayGet($markerRow, 'lng', '');\n    if (is_numeric($markerLat) && is_numeric($markerLng)\n        && (float) $markerLat >= -90.0 && (float) $markerLat <= 90.0\n        && (float) $markerLng >= -180.0 && (float) $markerLng <= 180.0) {\n        $place['geo'] = array(\n            '@type' => 'GeoCoordinates',\n            'latitude' => (float) $markerLat,\n            'longitude' => (float) $markerLng\n        );\n    }\n"""
if old not in text:
    raise SystemExit('Coordinate SEO block not found')
p.write_text(text.replace(old, new, 1))
