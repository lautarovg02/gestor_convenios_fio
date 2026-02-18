<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

// Simular exactamente lo que hace el controller para Coordinador
$excludeStatus = ['Finalizado', 'Deshabilitado'];

$query = Contract::whereHas('status', function ($q) use ($excludeStatus) {
    $q->whereNotIn('status', $excludeStatus);
});

$query->where(function ($q) {
    $q->whereHas('specificResidenceAgreements')
      ->orWhereHas('individualIntershipAgreements');
});

$results = $query->with(['typeFrameworkAgreement', 'status', 'specificResidenceAgreements', 'individualIntershipAgreements'])->get();

echo "Contratos que ve el Coordinador:\n";
foreach ($results as $c) {
    $hasResidencia = $c->specificResidenceAgreements->count();
    $hasIndividual = $c->individualIntershipAgreements->count();
    echo "ID: {$c->id} | Status: {$c->status->status} | Residencia: {$hasResidencia} | Pasantía: {$hasIndividual}" . PHP_EOL;
}
echo "\nTotal: " . $results->count() . PHP_EOL;
