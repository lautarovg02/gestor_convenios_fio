<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

$contracts = Contract::with(['specifics', 'specificResidenceAgreements', 'individualIntershipAgreements', 'typeFrameworkAgreement'])->get();

foreach ($contracts as $c) {
    $type = 'Marco';
    if ($c->specifics->count() > 0) $type = 'Específico';
    elseif ($c->specificResidenceAgreements->count() > 0) $type = 'Individual Residencia';
    elseif ($c->individualIntershipAgreements->count() > 0) $type = 'Individual Pasantía';

    echo "ID: {$c->id} | TipoMarco: {$c->typeFrameworkAgreement->type} | TipoReal: {$type}" . PHP_EOL;
}
