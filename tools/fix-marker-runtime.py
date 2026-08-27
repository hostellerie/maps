from pathlib import Path

# Compact map list: keep only Edit + linked map name.
p = Path('admin/index.php')
s = p.read_text(encoding='utf-8')
s = s.replace("    $viewLabel = isset($LANG_ADMIN['view']) ? $LANG_ADMIN['view'] : 'View';\n", '')
s = s.replace("        array('text' => $viewLabel, 'field' => 'view', 'sort' => false),\n", '')
start = s.find('        case "view":')
end = s.find('        case "name":', start)
if start >= 0 and end > start:
    s = s[:start] + s[end:]
p.write_text(s, encoding='utf-8')

# Native browser date controls: no jQuery UI required.
p = Path('templates/marker_form.thtml')
s = p.read_text(encoding='utf-8')
s = s.replace('type="text" id="from" name="from"', 'type="date" id="from" name="from"')
s = s.replace('type="text" id="to" name="to"', 'type="date" id="to" name="to"')
p.write_text(s, encoding='utf-8')

p = Path('admin/marker_edit.php')
s = p.read_text(encoding='utf-8')
s = s.replace("date(\"m/d/Y\", strtotime($marker['validity_start']))", "date(\"Y-m-d\", strtotime($marker['validity_start']))")
s = s.replace("date(\"m/d/Y\", strtotime($marker['validity_end']))", "date(\"Y-m-d\", strtotime($marker['validity_end']))")
s = s.replace('date("m/d/Y")', 'date("Y-m-d")')

# Remove all legacy datepicker initialization between its wrapper and Google setup.
dp_start = s.find('\t\tjQuery(function() {')
dp_end = s.find('\t\tvar geocoder', dp_start)
if dp_start >= 0 and dp_end > dp_start:
    s = s[:dp_start] + s[dp_end:]

# Never instantiate Geocoder before Google Maps has fully loaded.
s = s.replace('\t\tvar geocoder = new google.maps.Geocoder();', '\t\tvar geocoder = null;')
old_init = '''\t\tfunction initializeGMap() {
\t\t\tvar initialPosition = {lat:Number('''
new_init = '''\t\tfunction initializeGMap(attempt) {
\t\t\tif (typeof window.google === "undefined" || !google.maps
\t\t\t    || typeof google.maps.Map !== "function"
\t\t\t    || typeof google.maps.Marker !== "function"
\t\t\t    || typeof google.maps.Geocoder !== "function"
\t\t\t    || !google.maps.MapTypeId || !google.maps.event) {
\t\t\t\tif (attempt < 120) {
\t\t\t\t\twindow.setTimeout(function () { initializeGMap(attempt + 1); }, 100);
\t\t\t\t}
\t\t\t\treturn;
\t\t\t}
\t\t\tif (map) { return; }
\t\t\tgeocoder = new google.maps.Geocoder();
\t\t\tvar initialPosition = {lat:Number('''
if old_init in s:
    s = s.replace(old_init, new_init, 1)
elif 'function initializeGMap(attempt)' not in s:
    raise SystemExit('initializeGMap anchor not found')

old_ready = 'if (document.readyState === "complete") { initializeGMap(); } else { window.addEventListener("load", initializeGMap); }'
new_ready = 'if (document.readyState === "complete") { initializeGMap(0); } else { window.addEventListener("load", function () { initializeGMap(0); }); }'
if old_ready in s:
    s = s.replace(old_ready, new_ready, 1)
elif new_ready not in s:
    raise SystemExit('Google Maps ready anchor not found')

old_geocode = '''\t\t  geocoder.geocode({"address": address}, function(results, status) {'''
new_geocode = '''\t\t  if (!geocoder) {
\t\t\tinitializeGMap(0);
\t\t\treturn;
\t\t  }
\t\t  geocoder.geocode({"address": address}, function(results, status) {'''
if '\t\t  if (!geocoder) {' not in s:
    if old_geocode not in s:
        raise SystemExit('geocoder call anchor not found')
    s = s.replace(old_geocode, new_geocode, 1)

s = s.replace("\t$_SCRIPTS->setJavaScriptFile('ui_core', '/javascript/jquery_ui/jquery.ui.core.min.js');\n", '')
s = s.replace("\t$_SCRIPTS->setJavaScriptFile('datepicker', '/javascript/jquery_ui/jquery.ui.datepicker.min.js');\n", '')
p.write_text(s, encoding='utf-8')
