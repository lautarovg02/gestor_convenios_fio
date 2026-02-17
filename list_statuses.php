<?php
use App\Models\ContractStatus;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$statuses = ContractStatus::all();
foreach ($statuses as $status) {
    echo "ID: " . $status->id . " - Status: " . $status->status . PHP_EOL;
}
