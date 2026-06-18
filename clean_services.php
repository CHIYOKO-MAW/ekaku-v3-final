<?php

/**
 * Remove dev-only service providers from bootstrap/cache/services.php
 * Run after package:discover to ensure production-only providers.
 */

$file = __DIR__ . '/bootstrap/cache/services.php';

if (!file_exists($file)) {
    echo "services.php not found, skipping\n";
    exit(0);
}

$manifest = require $file;

$devPatterns = [
    'Boost',
    'Mcp\\Server',
    'Telescope\\',
    'TelescopeMcp',
    'Pail\\',
    'Sentinel\\',
    'Collision\\Adapters',
    'Pest\\Laravel',
    'Breeze\\',
    'Sail\\',
    'Roster\\',
];

$removed = [];
$clean = [];

foreach ($manifest['providers'] ?? [] as $key => $provider) {
    $str = is_array($provider) ? implode(',', $provider) : (string) $provider;
    $isDev = false;
    foreach ($devPatterns as $pattern) {
        if (strpos($str, $pattern) !== false) {
            $isDev = true;
            break;
        }
    }
    if ($isDev) {
        $removed[] = $str;
    } else {
        $clean[$key] = $provider;
    }
}

$manifest['providers'] = $clean;

$code = "<?php\nreturn " . var_export($manifest, true) . ";\n";
file_put_contents($file, $code);

echo "Removed " . count($removed) . " dev providers: " . implode(', ', $removed) . "\n";
echo "Kept " . count($clean) . " production providers\n";
