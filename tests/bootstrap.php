<?php

$sqliteFile = __DIR__ . '/database/database.sqlite';
$f = @fopen($sqliteFile, 'rb+');
if ($f !== false) {
    ftruncate($f, 0);
    fclose($f);
} else {
    file_put_contents($sqliteFile, '');
}

require_once __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ .'/bootstrap/app.php';
$app->make(\Illuminate\Foundation\Http\Kernel::class)->bootstrap();
\Illuminate\Support\Facades\Artisan::call('migrate');
