<?php
$base = __DIR__ . '/..';
$checks = [
    'css' => $base . '/public/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/css/styles.css',
    'js' => $base . '/public/vendor/theme/Gxon_v1_22-Dec-2025/demo/public/assets/js/main.js',
    'logo' => $base . '/public/images/logoAd.png',
    'icon' => $base . '/public/images/iconAD.png'
];

foreach ($checks as $k => $path) {
    echo str_pad($k, 6) . ': ' . ($path) . " => " . (file_exists($path) ? "OK" : "MISSING") . PHP_EOL;
}

echo "\nJika salah satu MISSING, pastikan file theme zip diekstrak ke public/vendor/theme dan logo/icon disalin ke public/images." . PHP_EOL;