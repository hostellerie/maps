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
    'attempt<200'
);

foreach ($checks as $check) {
    if (strpos($source, $check) === false) {
        fwrite(STDERR, "Missing marker render retry guard: {$check}\n");
        exit(1);
    }
}

echo "Embedded marker retry guards are present.\n";
