<?php

$source = file_get_contents(dirname(__DIR__) . '/functions.inc');
$failures = array();

function maps_asset_require($content, $needle, $message, &$failures)
{
    if ($content === false || strpos($content, $needle) === false) {
        $failures[] = $message;
    }
}

maps_asset_require($source, 'function MAPS_assetUrl(', 'Maps asset URL helper is missing.', $failures);
maps_asset_require($source, 'plugin_chkVersion_maps()', 'Maps asset version does not use the plugin release metadata.', $failures);
maps_asset_require($source, 'filemtime($file)', 'Maps asset version does not include file modification time.', $failures);
maps_asset_require($source, "MAPS_assetUrl('maps.css')", 'maps.css is not cache-busted.', $failures);
maps_asset_require($source, "MAPS_assetUrl('js/mapiconmaker.js')", 'mapiconmaker.js is not cache-busted.', $failures);
maps_asset_require($source, 'function MAPS_localAssetHeaderCode()', 'Maps local asset header helper is missing.', $failures);
maps_asset_require($source, 'return $headerCode;', 'Maps plugin header hook does not return local assets.', $failures);
maps_asset_require($source, "'?v='", 'Maps asset URLs do not expose a version query parameter.', $failures);

if (!empty($failures)) {
    fwrite(STDERR, "Maps asset version contract checks failed:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '- ' . $failure . "\n");
    }
    exit(1);
}

echo "Maps asset version contract checks: PASS\n";
