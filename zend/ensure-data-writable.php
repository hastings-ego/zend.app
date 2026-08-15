<?php
declare(strict_types=1);

/**
 * One-time helper to make the /data directory writable by the web process.
 * Run this file in the browser or from the CLI when deployment permissions are too strict.
 */

$root = realpath(__DIR__ . '/../data');
if ($root === false) {
    http_response_code(500);
    echo "data directory not found";
    exit(1);
}

$targets = [$root];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $item) {
    $targets[] = $item->getPathname();
}

$changed = [];
foreach ($targets as $target) {
    $mode = is_dir($target) ? 0775 : 0664;
    @chmod($target, $mode);
    $changed[] = $target;
}

header('Content-Type: text/plain; charset=utf-8');
echo "Updated permissions for:\n";
echo implode("\n", $changed);
