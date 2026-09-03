<?php

$source = file_get_contents(dirname(__DIR__) . '/services.inc.php');
if ($source === false) {
    fwrite(STDERR, "Unable to read services.inc.php\n");
    exit(1);
}

$checks = array(
    'ready(attempt)',
    '!el',
    'el.offsetWidth===0',
    'el.offsetHeight===0',
    'ready(attempt+1)',
    'attempt<200',
    "google.maps.importLibrary==='function'",
    "google.maps.importLibrary('maps')",
    "typeof MapCtor!=='function'",
    "typeof google.maps.Marker!=='function'"
);

foreach ($checks as $check) {
    if (strpos($source, $check) === false) {
        fwrite(STDERR, "Missing embedded marker readiness guard: {$check}\n");
        exit(1);
    }
}

echo "Embedded marker async readiness guards are present.\n";
