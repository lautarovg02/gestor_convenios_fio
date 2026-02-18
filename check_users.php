<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::with('roles')->get();
foreach ($users as $user) {
    $roles = $user->roles->pluck('name')->implode(', ');
    echo "ID: {$user->id} | Email: {$user->email} | Roles: {$roles}" . PHP_EOL;
}
