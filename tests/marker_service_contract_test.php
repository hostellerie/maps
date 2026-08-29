<?php

$root = dirname(__DIR__);
$services = file_get_contents($root . '/services.inc.php');
$failures = array();

function maps_contract_require($content, $needle, $message, &$failures)
{
    if ($content === false || strpos($content, $needle) === false) {
        $failures[] = $message;
    }
}

maps_contract_require($services, 'function service_marker_save_maps(', 'marker_save service is missing.', $failures);
maps_contract_require($services, 'MAPS_serviceRejectWeb($args, $svc_msg)', 'marker_save is not restricted to trusted internal calls.', $failures);
maps_contract_require($services, 'source and source_id are required for marker_save', 'marker_save does not require a source identity.', $failures);
maps_contract_require($services, 'INSERT INTO {$_TABLES[\'maps_markers\']}', 'Maps no longer owns marker creation.', $failures);
maps_contract_require($services, 'UPDATE {$_TABLES[\'maps_markers\']}', 'Maps no longer owns marker editing.', $failures);
maps_contract_require($services, 'COM_makeSid()', 'Maps no longer allocates marker IDs.', $failures);
maps_contract_require($services, 'MAPS_notifyMarkerSaved($markerId, $mapId)', 'Maps marker lifecycle notification is missing.', $failures);
maps_contract_require($services, 'updateMap($mapId)', 'Maps map refresh is missing after marker save.', $failures);

if (!empty($failures)) {
    fwrite(STDERR, "Maps marker service contract checks failed:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '- ' . $failure . "\n");
    }
    exit(1);
}

echo "Maps marker service contract checks: PASS\n";
