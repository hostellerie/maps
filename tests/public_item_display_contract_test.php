<?php

$root = dirname(__DIR__);
$publicIndex = file_get_contents($root . '/public_html/index.php');
$interop = file_get_contents($root . '/interoperability.php');
$failures = array();

function maps_item_display_require($content, $needle, $message, &$failures)
{
    if ($content === false || strpos($content, $needle) === false) {
        $failures[] = $message;
    }
}

maps_item_display_require(
    $publicIndex,
    "PLG_itemDisplay((string) \$id, 'maps')",
    'Maps public item dispatcher is missing.',
    $failures
);
maps_item_display_require(
    $publicIndex,
    'MAPS_publicItemDisplay($mid)',
    'Canonical map pages do not expose the generic item-display hook.',
    $failures
);
maps_item_display_require(
    $publicIndex,
    "MAPS_publicItemDisplay('marker:' . \$mkid)",
    'Canonical marker pages do not expose the generic item-display hook.',
    $failures
);
maps_item_display_require(
    $interop,
    "'type' => 'maps'",
    'Maps Item Info no longer exposes the canonical maps type.',
    $failures
);
maps_item_display_require(
    $interop,
    "'id' => 'marker:' . \$mkid",
    'Marker Item Info no longer uses the marker:<mkid> canonical ID.',
    $failures
);

if (!empty($failures)) {
    fwrite(STDERR, "Maps public item-display contract failed:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, '- ' . $failure . "\n");
    }
    exit(1);
}

echo "Maps public item-display contract: OK\n";
