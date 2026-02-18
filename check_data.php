<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Company;
use App\Models\Secretary;
use App\Models\Teacher;
use App\Models\Employee;
use App\Models\ContractStatus;
use App\Models\TypeFrameworkAgreement;
use App\Models\Student;

echo "Companies: " . Company::count() . PHP_EOL;
echo "Secretaries: " . Secretary::count() . PHP_EOL;
echo "Teachers: " . Teacher::count() . PHP_EOL;
echo "Employees: " . Employee::count() . PHP_EOL;
echo "ContractStatuses: " . ContractStatus::count() . PHP_EOL;
echo "TypeFrameworkAgreements: " . TypeFrameworkAgreement::count() . PHP_EOL;
echo "Students: " . Student::count() . PHP_EOL;

// Show first IDs
echo "\nFirst Company ID: " . Company::first()->id . PHP_EOL;
echo "First Secretary ID: " . Secretary::first()->id . PHP_EOL;
echo "First Teacher ID: " . Teacher::first()->id . PHP_EOL;
echo "First Employee ID: " . Employee::first()->id . PHP_EOL;
echo "First ContractStatus ID: " . ContractStatus::first()->id . PHP_EOL;
echo "First TypeFrameworkAgreement ID: " . TypeFrameworkAgreement::first()->id . PHP_EOL;
echo "First Student ID: " . Student::first()->id . PHP_EOL;
