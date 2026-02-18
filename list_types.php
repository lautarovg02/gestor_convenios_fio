<?php
use App\Models\TypeFrameworkAgreement;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$types = TypeFrameworkAgreement::all();
foreach ($types as $type) {
    echo "ID: " . $type->id . " - Type: " . $type->type . PHP_EOL;
}
