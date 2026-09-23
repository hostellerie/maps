<?php

$root = dirname(__DIR__);
$defaults = file_get_contents($root . '/install_defaults.php');
$runtime = file_get_contents($root . '/maps.php');
$functions = file_get_contents($root . '/functions.inc');
$users = file_get_contents($root . '/public_html/users_map.php');
$admin = file_get_contents($root . '/admin/index.php');
$template = file_get_contents($root . '/templates/marker_map.thtml');
$failures = array();

function maps_issue_require($content, $needle, $message, &$failures)
{
    if ($content === false || strpos($content, $needle) === false) {
        $failures[] = $message;
    }
}

foreach (array('users_map_lat', 'users_map_lng', 'users_map_zoom', 'users_map_type', 'users_map_width', 'users_map_height') as $name) {
    maps_issue_require($defaults, "'".$name."'", 'Issue #4 missing configuration: ' . $name, $failures);
    maps_issue_require($users, "'".$name."'", 'Issue #4 Users Map does not consume: ' . $name, $failures);
}

maps_issue_require($runtime, "return '';", 'Issue #14 geocode URL does not fail safely without a server key.', $failures);
if (strpos($runtime, "} elseif (isset(\$_MAPS_CONF['google_api_key']))") !== false) {
    $failures[] = 'Issue #14 browser-key fallback still exists in server geocoding.';
}
maps_issue_require($functions, 'no dedicated Google Geocoding server API key configured', 'Issue #14 server-key requirement is not enforced.', $failures);

maps_issue_require($admin, 'function MAPS_adminIntegrations()', 'Issue #5 integration discovery is missing.', $failures);
maps_issue_require($admin, "'xmlsitemap'", 'Issue #5 XMLSitemap discovery is missing.', $failures);
maps_issue_require($admin, "'documents'", 'Issue #5 Documents discovery is missing.', $failures);
maps_issue_require($admin, "'indexnow'", 'Issue #5 IndexNow discovery is missing.', $failures);
maps_issue_require($admin, '/syndication.php', 'Issue #5 feed discovery is missing.', $failures);

maps_issue_require($admin, 'function MAPS_adminPlatformConfiguration()', 'Issue #15 API configuration diagnostic is missing.', $failures);
maps_issue_require($template, 'navigator.geolocation', 'Issue #15 browser geolocation is missing.', $failures);
maps_issue_require($template, 'use_current_location{gid}', 'Issue #15 route geolocation hook is missing.', $failures);

if (!empty($failures)) {
    fwrite(STDERR, "Maps issue #4/#5/#14/#15 contract checks failed:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '- ' . $failure . "\n");
    }
    exit(1);
}

echo "Maps issue #4/#5/#14/#15 contract checks: PASS\n";
