<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$stmts = [
    "ALTER TABLE `drivers` ADD `name` VARCHAR(255) NULL AFTER `user_id`",
    "ALTER TABLE `drivers` ADD `address` TEXT NULL AFTER `name`",
    "ALTER TABLE `drivers` ADD `wa_contact` VARCHAR(50) NULL AFTER `address`",
];

foreach ($stmts as $sql) {
    try {
        \DB::statement($sql);
        echo "OK: $sql\n";
    } catch (Throwable $e) {
        echo "ERR: {$e->getMessage()}\n";
    }
}

echo "Columns now: \n";
print_r(\Schema::getColumnListing('drivers'));
